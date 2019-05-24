<?php

namespace MailPoet\Premium\AutomaticEmails\WooCommerce\Events;

use MailPoet\Models\Newsletter;
use MailPoet\Models\ScheduledTask;
use MailPoet\Models\ScheduledTaskSubscriber;
use MailPoet\Models\SendingQueue;
use MailPoet\Models\Subscriber;
use MailPoet\Newsletter\Scheduler\Scheduler;
use MailPoet\Premium\AutomaticEmails\WooCommerce\WooCommerce;
use MailPoet\WP\Hooks;
use MailPoet\Logging\Logger;

class FirstPurchase {
  const SLUG = 'woocommerce_first_purchase';
  const ORDER_TOTAL_SHORTCODE = '[woocommerce:order_total]';
  const ORDER_DATE_SHORTCODE = '[woocommerce:order_date]';

  function init() {
    Hooks::addFilter('mailpoet_newsletter_shortcode', array(
      $this,
      'handleOrderTotalShortcode'
    ), 10, 4);
    Hooks::addFilter('mailpoet_newsletter_shortcode', array(
      $this,
      'handleOrderDateShortcode'
    ), 10, 4);

    // We have to use a set of states because an order state after checkout differs for different payment methods
    $accepted_order_states = Hooks::applyFilters('mailpoet_first_purchase_order_states', ['completed', 'processing']);

    foreach($accepted_order_states as $state) {
      Hooks::addAction('woocommerce_order_status_' . $state, [
        $this,
        'scheduleEmailWhenOrderIsPlaced'
      ], 10, 1);
    }
  }

  function getEventDetails() {
    return array(
      'slug' => self::SLUG,
      'title' => __('First Purchase', 'mailpoet-premium'),
      'description' => __('Let MailPoet send an email to customers who make their first purchase.', 'mailpoet-premium'),
      'listingScheduleDisplayText' => __('when first purchase is made', 'mailpoet-premium'),
      'badge' => array(
        'text' => __('Must-have', 'mailpoet-premium'),
        'style' => 'red'
      ),
      'shortcodes' => array(
        array(
          'text' => __('Order amount', 'mailpoet-premium'),
          'shortcode' => self::ORDER_TOTAL_SHORTCODE
        ),
        array(
          'text' => __('Order date', 'mailpoet-premium'),
          'shortcode' => self::ORDER_DATE_SHORTCODE
        )
      )
    );
  }

  function handleOrderDateShortcode($shortcode, $newsletter, $subscriber, $queue) {
    $result = $shortcode;
    if($shortcode === self::ORDER_DATE_SHORTCODE) {
      $default_value = date_i18n(get_option('date_format'));
      if(!$queue) {
        $result = $default_value;
      } else {
        $meta = $queue->getMeta();
        $result = (!empty($meta['order_date'])) ? date_i18n(get_option('date_format'), $meta['order_date']) : $default_value;
      }
    }
    Logger::getLogger(self::SLUG)->addInfo(
      'handleOrderDateShortcode called', [
        'newsletter_id' => ($newsletter instanceof Newsletter) ? $newsletter->id : null,
        'subscriber_id' => ($subscriber instanceof Subscriber) ? $subscriber->id : null,
        'task_id' => ($queue instanceof SendingQueue) ? $queue->task_id : null,
        'shortcode' => $shortcode,
        'result' => $result
      ]
    );
    return $result;
  }

  function handleOrderTotalShortcode($shortcode, $newsletter, $subscriber, $queue) {
    $result = $shortcode;
    if($shortcode === self::ORDER_TOTAL_SHORTCODE) {
      $default_value = wc_price(0);
      if(!$queue) {
        $result = $default_value;
      } else {
        $meta = $queue->getMeta();
        $result = (!empty($meta['order_amount'])) ? wc_price($meta['order_amount']) : $default_value;
      }
    }
    Logger::getLogger(self::SLUG)->addInfo(
      'handleOrderTotalShortcode called', [
        'newsletter_id' => ($newsletter instanceof Newsletter) ? $newsletter->id : null,
        'subscriber_id' => ($subscriber instanceof Subscriber) ? $subscriber->id : null,
        'task_id' => ($queue instanceof SendingQueue) ? $queue->task_id : null,
        'shortcode' => $shortcode,
        'result' => $result
      ]
    );
    return $result;
  }

  function scheduleEmailWhenOrderIsPlaced($order_id) {
    $order_details = wc_get_order($order_id);
    if(!$order_details || !$order_details->get_customer_id()) {
      Logger::getLogger(self::SLUG)->addInfo(
        'Email not scheduled because the order customer was not found',
        ['order_id' => $order_id]
      );
      return;
    }

    $customer_id = $order_details->get_customer_id();
    $customer_order_count = wc_get_customer_order_count($customer_id);
    if($customer_order_count > 1) {
      Logger::getLogger(self::SLUG)->addInfo(
        'Email not scheduled because this is not the first order of the customer', [
          'order_id' => $order_id,
          'customer_id' => $customer_id,
          'order_count' => $customer_order_count
        ]
      );
      return;
    }

    $meta = array(
      'order_amount' => $order_details->total,
      'order_date' => $order_details->get_date_created()->getTimestamp(),
      'order_id' => $order_details->get_id()
    );

    $subscriber = Subscriber::where('wp_user_id', $customer_id)->findOne();
    if(!$subscriber) {
      Logger::getLogger(self::SLUG)->addInfo(
        'Email not scheduled because the customer was not found as subscriber',
        ['order_id' => $order_id, 'customer_id' => $customer_id]
      );
      return;
    }

    $check_email_was_not_scheduled = function (Newsletter $newsletter) use ($subscriber) {
      return !$newsletter->wasScheduledForSubscriber($subscriber->id);
    };

    Logger::getLogger(self::SLUG)->addInfo(
      'Email scheduled', [
        'order_id' => $order_id,
        'customer_id' => $customer_id,
        'subscriber_id' => $subscriber->id
      ]
    );
    Scheduler::scheduleAutomaticEmail(WooCommerce::SLUG, self::SLUG, $check_email_was_not_scheduled, $subscriber->id, $meta);
  }

}
