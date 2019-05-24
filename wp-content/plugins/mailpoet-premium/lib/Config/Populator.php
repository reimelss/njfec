<?php

namespace MailPoet\Premium\Config;

use MailPoet\Config\Env as ParentEnv;
use MailPoet\Models\Newsletter;

if (!defined('ABSPATH')) exit;

class Populator extends \MailPoet\Config\Populator {
  function __construct() {
    parent::__construct();
    $this->prefix = ParentEnv::$db_prefix;
    $this->models = [
      'newsletter_option_fields',
    ];
    $this->templates = [];
  }

  function up() {
    array_map([$this, 'populate'], $this->models);
  }

  protected function newsletterOptionFields() {
    $fields = [
      'group',
      'event',
      'sendTo',
      'segment',
      'afterTimeNumber',
      'afterTimeType',
      'meta',
    ];

    return [
      'rows' => array_map(function($field) {
        return [
          'name' => $field,
          'newsletter_type' => Newsletter::TYPE_AUTOMATIC,
        ];
      }, $fields),
      'identification_columns' => [
        'name',
        'newsletter_type',
      ],
    ];
  }
}
