<?php
namespace MailPoet\Premium\API\JSON\v1;

use MailPoet\Config\AccessControl;
use MailPoet\API\JSON\Endpoint as APIEndpoint;
use MailPoet\API\JSON\Error as APIError;
use MailPoet\Features\FeaturesController;
use MailPoet\Models\Newsletter;
use MailPoet\Models\ScheduledTask;
use MailPoet\Newsletter\Url as NewsletterUrl;
use MailPoet\Premium\Models\NewsletterExtraData;
use MailPoet\Premium\Newsletter\Stats as CampaignStats;
use MailPoet\Premium\Newsletter\Stats\SubscriberEngagement;
use MailPoet\WooCommerce\Helper as WCHelper;

if (!defined('ABSPATH')) exit;
use MailPoet\WP\Functions as WPFunctions;

class Stats extends APIEndpoint {
  public $permissions = [
    'global' => AccessControl::PERMISSION_MANAGE_EMAILS,
  ];

  /** @var FeaturesController */
  private $features_controller;

  /** @var WCHelper */
  private $woocommerce_helper;

  function __construct(FeaturesController $features_controller, WCHelper $woocommerce_helper) {
    $this->features_controller = $features_controller;
    $this->woocommerce_helper = $woocommerce_helper;
  }

  function get($data = []) {
    $id = (isset($data['id']) ? (int)$data['id'] : false);
    $newsletter = Newsletter::findOne($id);
    if (!$newsletter instanceof Newsletter) {
      return $this->errorResponse(
        [
          APIError::NOT_FOUND => WPFunctions::get()->__('This newsletter does not exist.', 'mailpoet-premium'),
        ]
      );
    }

    $newsletter->withSegments()
      ->withSendingQueue()
      ->withTotalSent()
      ->withStatistics($this->woocommerce_helper, $this->features_controller);

    if (!$this->isNewsletterSent($newsletter)) {
      return $this->errorResponse(
        [
          APIError::NOT_FOUND => WPFunctions::get()->__('This newsletter is not sent yet.', 'mailpoet-premium'),
        ]
      );
    }

    $clicked_links = CampaignStats::getClickedLinks($newsletter);

    $preview_url = NewsletterUrl::getViewInBrowserUrl(
      NewsletterUrl::TYPE_LISTING_EDITOR,
      $newsletter
    );

    $newsletter = $newsletter->asArray();
    $newsletter = NewsletterExtraData::getFields($newsletter);
    $newsletter['clicked_links'] = $clicked_links;
    $newsletter['preview_url'] = $preview_url;

    return $this->successResponse($newsletter);
  }

  function listing($data = []) {
    $id = (isset($data['params']['id']) ? (int)$data['params']['id'] : false);
    $newsletter = Newsletter::findOne($id);
    if (!$newsletter instanceof Newsletter) {
      return $this->errorResponse([
        APIError::NOT_FOUND => WPFunctions::get()->__('This newsletter does not exist.', 'mailpoet-premium'),
      ]);
    }

    $newsletter->withSendingQueue();

    if (!$this->isNewsletterSent($newsletter)) {
      return $this->errorResponse(
        [
          APIError::NOT_FOUND => WPFunctions::get()->__('This newsletter is not sent yet.', 'mailpoet-premium'),
        ]
      );
    }

    $listing = new SubscriberEngagement($data);
    $listing_data = $listing->get();

    foreach ($listing_data['items'] as &$item) {
      $item['id'] = (int)$item['subscriber_id']; // required for React ListingItems and ListingItem components
      $item['subscriber_url'] = WPFunctions::get()->adminUrl(
        'admin.php?page=mailpoet-subscribers#/edit/' . $item['subscriber_id']
      );
    }
    unset($item);

    return $this->successResponse($listing_data['items'], [
      'count' => (int)$listing_data['count'],
      'filters' => $listing_data['filters'],
      'groups' => $listing_data['groups'],
    ]);
  }

  function isNewsletterSent($newsletter) {
    // for statistics purposes, newsletter (except for welcome notifications) is sent
    // when it has a queue record and it's status is not scheduled
    if (!$newsletter->queue) return false;

    if ($newsletter->type === Newsletter::TYPE_WELCOME) return true;

    return $newsletter->queue['status'] !== ScheduledTask::STATUS_SCHEDULED;
  }
}
