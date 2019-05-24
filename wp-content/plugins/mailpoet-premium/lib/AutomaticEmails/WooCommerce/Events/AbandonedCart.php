<?php

namespace MailPoet\Premium\AutomaticEmails\WooCommerce\Events;
use MailPoet\WP\Functions as WPFunctions;

class AbandonedCart {
  const SLUG = 'woocommerce_abandoned_shopping_cart';

  function getEventDetails() {
    return [
      'slug' => self::SLUG,
      'title' => WPFunctions::get()->__('Abandoned Shopping Cart', 'mailpoet-premium'),
      'description' => WPFunctions::get()->__('Send an email to logged-in visitors who have items in their shopping carts but left your website without checking out. Can convert up to 5% of abandoned carts.', 'mailpoet-premium'),
      'listingScheduleDisplayText' => WPFunctions::get()->__('when cart is abandoned', 'mailpoet-premium'),
      'soon' => true,
      'badge' => [
        'text' => WPFunctions::get()->__('Must-have', 'mailpoet-premium'),
        'style' => 'red',
      ],
    ];
  }
}