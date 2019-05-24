<?php

namespace MailPoet\Premium\Config;

if (!defined('ABSPATH')) exit;
use MailPoet\WP\Functions as WPFunctions;

class Localizer {
  function init() {
    $this->loadGlobalText();
    $this->loadPluginText();
  }

  function loadGlobalText() {
    $language_path = sprintf(
      '%s/%s.mo',
      Env::$languages_path,
      $this->locale()
    );
    WPFunctions::get()->loadTextdomain(Env::$plugin_name, $language_path);
  }

  function loadPluginText() {
    WPFunctions::get()->loadPluginTextdomain(
      Env::$plugin_name,
      false,
      dirname(plugin_basename(Env::$file)) . '/lang/'
    );
  }

  function locale() {
    $locale = WPFunctions::get()->applyFilters(
      'plugin_locale',
      WPFunctions::get()->getLocale(),
      Env::$plugin_name
    );
    return $locale;
  }
}
