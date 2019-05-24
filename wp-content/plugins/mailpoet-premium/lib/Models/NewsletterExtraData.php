<?php
namespace MailPoet\Premium\Models;

use MailPoet\Models\Model;
use MailPoet\WP\Functions as WPFunctions;

if (!defined('ABSPATH')) exit;

class NewsletterExtraData extends Model {
  public static $_table = MP_NEWSLETTER_EXTRA_DATA_TABLE;

  // form fields to be managed by this model
  public static $premium_field_names = ['ga_campaign'];
  public static $premium_fields_holder;

  static function init(WPFunctions $wp = null) {
    if ($wp == null) {
      $wp = new WPFunctions;
    }
    $wp->addFilter(
      'mailpoet_api_newsletters_save_before',
      [__CLASS__, 'extractFields']
    );

    $wp->addAction(
      'mailpoet_api_newsletters_save_after',
      [__CLASS__, 'saveFields']
    );

    $wp->addFilter(
      'mailpoet_api_newsletters_get_after',
      [__CLASS__, 'getFields']
    );

    $wp->addAction(
      'mailpoet_api_newsletters_duplicate_after',
      [__CLASS__, 'duplicateFields'],
      10,
      2
    );

    $wp->addFilter(
      'mailpoet_api_newsletters_listing_item',
      [__CLASS__, 'processListingItem']
    );
  }

  static function extractFields($data) {
    $result = [];
    foreach (self::$premium_field_names as $field) {
      if (isset($data[$field])) {
        $result[$field] = $data[$field];
        unset($data[$field]);
      }
    }
    self::$premium_fields_holder = $result;
    if (!empty($data['options']['meta'])) {
      $data['options']['meta'] = json_encode($data['options']['meta']);
    }
    return $data;
  }

  static function saveFields($newsletter, $data = null) {
    $data = $data ?: self::$premium_fields_holder;
    $data['newsletter_id'] = $newsletter->id;
    $model = self::where('newsletter_id', $newsletter->id)->findOne();
    if ($model instanceof NewsletterExtraData) {
      $model->set($data);
    } else {
      $model = self::create();
      $model->hydrate($data);
    }
    $model->save();
    return $model;
  }

  /**
   * @param \MailPoet\Models\Newsletter|array|string $newsletter
   * @return array
   */
  static function getFields($newsletter) {
    if (is_object($newsletter)) {
      $newsletter = $newsletter->asArray();
    } elseif (!is_array($newsletter)) {
      $newsletter = ['id' => (int)$newsletter];
    }
    if (!empty($newsletter['options']['meta'])) {
      $newsletter['options']['meta'] = json_decode($newsletter['options']['meta'], true);
    }
    if (!empty($newsletter['options']['afterTimeNumber'])) {
      $newsletter['options']['afterTimeNumber'] = (int)$newsletter['options']['afterTimeNumber'];
    }
    $fields = self::where('newsletter_id', $newsletter['id'])->findOne();
    if (!$fields instanceof NewsletterExtraData) {
      return $newsletter;
    }
    $filtered_fields = array_intersect_key(
      $fields->asArray(),
      array_flip(self::$premium_field_names)
    );
    return array_merge($newsletter, $filtered_fields);
  }

  static function duplicateFields($newsletter, $duplicate) {
    $fields = self::getFields($newsletter);
    self::extractFields($fields);
    return self::saveFields($duplicate);
  }

  static function processListingItem($newsletter) {
    if (!empty($newsletter['options']['meta'])) {
      $newsletter['options']['meta'] = json_decode($newsletter['options']['meta'], true);
    }
    return $newsletter;
  }
}
