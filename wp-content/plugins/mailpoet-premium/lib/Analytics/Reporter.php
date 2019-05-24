<?php
namespace MailPoet\Premium\Analytics;

use MailPoet\Models\Newsletter;
use MailPoet\Premium\AutomaticEmails\WooCommerce\Events\FirstPurchase;
use MailPoet\Premium\AutomaticEmails\WooCommerce\Events\PurchasedProduct;

class Reporter {

  function getData() {
    $first_purchase_emails = Newsletter::getPublished()
      ->filter('filterType', Newsletter::TYPE_AUTOMATIC, FirstPurchase::SLUG)
      ->filter('filterStatus', Newsletter::STATUS_ACTIVE)
      ->count();

    $purchased_product_emails = Newsletter::getPublished()
      ->filter('filterType', Newsletter::TYPE_AUTOMATIC, PurchasedProduct::SLUG)
      ->filter('filterStatus', Newsletter::STATUS_ACTIVE)
      ->count();

    return [
      'Number of active WooCommerce First Purchase emails' => $first_purchase_emails,
      'Number of active WooCommerce Purchased This Product emails' => $purchased_product_emails,
    ];
  }

}
