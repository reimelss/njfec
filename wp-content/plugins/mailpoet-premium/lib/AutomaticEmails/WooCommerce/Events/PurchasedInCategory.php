<?php

namespace MailPoet\Premium\AutomaticEmails\WooCommerce\Events;
use MailPoet\WP\Functions as WPFunctions;

class PurchasedInCategory {
  const SLUG = 'woocommerce_product_purchased_in_category';

  function getEventDetails() {
    return [
      'slug' => self::SLUG,
      'title' => WPFunctions::get()->__('Purchased In This Category', 'mailpoet-premium'),
      'description' => WPFunctions::get()->__('Let MailPoet send an email to customers who purchase a product from a specific category.', 'mailpoet-premium'),
      'listingScheduleDisplayText' => WPFunctions::get()->__('when product is purchased in %s', 'mailpoet-premium'),
      'soon' => true,
    ];
  }
}