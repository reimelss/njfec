<?php

namespace MailPoet\Premium\AutomaticEmails\WooCommerce\Events;

use MailPoet\Models\Newsletter;
use MailPoet\Models\SendingQueue;
use MailPoet\Models\Subscriber;
use MailPoet\Newsletter\Scheduler\Scheduler;
use MailPoet\Premium\AutomaticEmails\WooCommerce\Helper as WCPremiumHelper;
use MailPoet\Premium\AutomaticEmails\WooCommerce\WooCommerce;
use MailPoet\WP\Functions as WPFunctions;
use MailPoet\Logging\Logger;
use MailPoet\WooCommerce\Helper as WCHelper;

class FirstPurchase {
  const SLUG = 'woocommerce_first_purchase';
  const ORDER_TOTAL_SHORTCODE = '[woocommerce:order_total]';
  const ORDER_DATE_SHORTCODE = '[woocommerce:order_date]';
  /**
   * @var \MailPoet\WooCommerce\Helper
   */
  private $helper;

  /**
   * @var WCPremiumHelper
   */
  private $premium_helper;

  function __construct(WCHelper $helper = null, WCPremiumHelper $premium_helper = null) {
    if ($helper === null) {
      $helper = new WCHelper(new WPFunctions());
    }
    if ($premium_helper === null) {
      $premium_helper = new WCPremiumHelper;
    }
    $this->helper = $helper;
    $this->premium_helper = $premium_helper;
  }

  function init() {
    WPFunctions::get()->addFilter('mailpoet_newsletter_shortcode', [
      $this,
      'handleOrderTotalShortcode',
    ], 10, 4);
    WPFunctions::get()->addFilter('mailpoet_newsletter_shortcode', [
      $this,
      'handleOrderDateShortcode',
    ], 10, 4);

    // We have to use a set of states because an order state after checkout differs for different payment methods
    $accepted_order_states = WPFunctions::get()->applyFilters('mailpoet_first_purchase_order_states', ['completed', 'processing']);

    foreach ($accepted_order_states as $state) {
      WPFunctions::get()->addAction('woocommerce_order_status_' . $state, [
        $this,
        'scheduleEmailWhenOrderIsPlaced',
      ], 10, 1);
    }
  }

  function getEventDetails() {
    return [
      'slug' => self::SLUG,
      'title' => WPFunctions::get()->__('First Purchase', 'mailpoet-premium'),
      'description' => WPFunctions::get()->__('Let MailPoet send an email to customers who make their first purchase.', 'mailpoet-premium'),
      'listingScheduleDisplayText' => WPFunctions::get()->__('when first purchase is made', 'mailpoet-premium'),
      'badge' => [
        'text' => WPFunctions::get()->__('Must-have', 'mailpoet-premium'),
        'style' => 'red',
      ],
      'shortcodes' => [
        [
          'text' => WPFunctions::get()->__('Order amount', 'mailpoet-premium'),
          'shortcode' => self::ORDER_TOTAL_SHORTCODE,
        ],
        [
          'text' => WPFunctions::get()->__('Order date', 'mailpoet-premium'),
          'shortcode' => self::ORDER_DATE_SHORTCODE,
        ],
      ],
    ];
  }

  function handleOrderDateShortcode($shortcode, $newsletter, $subscriber, $queue) {
    $result = $shortcode;
    if ($shortcode === self::ORDER_DATE_SHORTCODE) {
      $default_value = WPFunctions::get()->dateI18n(get_option('date_format'));
      if (!$queue) {
        $result = $default_value;
      } else {
        $meta = $queue->getMeta();
        $result = (!empty($meta['order_date'])) ? WPFunctions::get()->dateI18n(get_option('date_format'), $meta['order_date']) : $default_value;
      }
    }
    Logger::getLogger(self::SLUG)->addInfo(
      'handleOrderDateShortcode called', [
        'newsletter_id' => ($newsletter instanceof Newsletter) ? $newsletter->id : null,
        'subscriber_id' => ($subscriber instanceof Subscriber) ? $subscriber->id : null,
        'task_id' => ($queue instanceof SendingQueue) ? $queue->task_id : null,
        'shortcode' => $shortcode,
        'result' => $result,
      ]
    );
    return $result;
  }

  function handleOrderTotalShortcode($shortcode, $newsletter, $subscriber, $queue) {
    $result = $shortcode;
    if ($shortcode === self::ORDER_TOTAL_SHORTCODE) {
      $default_value = $this->helper->wcPrice(0);
      if (!$queue) {
        $result = $default_value;
      } else {
        $meta = $queue->getMeta();
        $result = (!empty($meta['order_amount'])) ? $this->helper->wcPrice($meta['order_amount']) : $default_value;
      }
    }
    Logger::getLogger(self::SLUG)->addInfo(
      'handleOrderTotalShortcode called', [
        'newsletter_id' => ($newsletter instanceof Newsletter) ? $newsletter->id : null,
        'subscriber_id' => ($subscriber instanceof Subscriber) ? $subscriber->id : null,
        'task_id' => ($queue instanceof SendingQueue) ? $queue->task_id : null,
        'shortcode' => $shortcode,
        'result' => $result,
      ]
    );
    return $result;
  }

  function scheduleEmailWhenOrderIsPlaced($order_id) {
    $order_details = $this->helper->wcGetOrder($order_id);
    if (!$order_details || !$order_details->get_billing_email()) {
      Logger::getLogger(self::SLUG)->addInfo(
        'Email not scheduled because the order customer was not found',
        ['order_id' => $order_id]
      );
      return;
    }

    $customer_email = $order_details->get_billing_email();
    $customer_order_count = $this->premium_helper->getCustomerOrderCount($customer_email);
    if ($customer_order_count > 1) {
      Logger::getLogger(self::SLUG)->addInfo(
        'Email not scheduled because this is not the first order of the customer', [
          'order_id' => $order_id,
          'customer_email' => $customer_email,
          'order_count' => $customer_order_count,
        ]
      );
      return;
    }

    $meta = [
      'order_amount' => $order_details->get_total(),
      'order_date' => $order_details->get_date_created()->getTimestamp(),
      'order_id' => $order_details->get_id(),
    ];

    $subscriber = $this->premium_helper->getWooCommerceSegmentSubscriber($customer_email);

    if (!$subscriber) {
      Logger::getLogger(self::SLUG)->addInfo(
        'Email not scheduled because the customer was not found as WooCommerce list subscriber',
        ['order_id' => $order_id, 'customer_email' => $customer_email]
      );
      return;
    }

    $check_email_was_not_scheduled = function (Newsletter $newsletter) use ($subscriber) {
      return !$newsletter->wasScheduledForSubscriber($subscriber->id);
    };

    Logger::getLogger(self::SLUG)->addInfo(
      'Email scheduled', [
        'order_id' => $order_id,
        'customer_email' => $customer_email,
        'subscriber_id' => $subscriber->id,
      ]
    );
    Scheduler::scheduleAutomaticEmail(WooCommerce::SLUG, self::SLUG, $check_email_was_not_scheduled, $subscriber->id, $meta);
  }

}
