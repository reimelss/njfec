<?php

namespace MailPoet\Premium\Config;

use MailPoet\Settings\SettingsController;

if (!defined('ABSPATH')) exit;

class Activator {

  static function activate() {
    $migrator = new Migrator();
    $migrator->up();

    $populator = new Populator();
    $populator->up();

    $settings = new SettingsController();
    $settings->set('premium_db_version', Env::$version);
  }

  static function deactivate() {
    $migrator = new Migrator();
    $migrator->down();
  }

  static function reset() {
    self::deactivate();
    self::activate();
  }
}
