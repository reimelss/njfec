<?php

namespace MailPoet\Twig;

use Carbon\Carbon;
use MailPoet\Config\ServicesChecker;
use MailPoet\Settings\SettingsController;
use MailPoet\Util\FreeDomains;
use MailPoet\WooCommerce\Helper as WooCommerceHelper;
use MailPoet\WP\Functions as WPFunctions;
use MailPoetVendor\Twig\Extension\AbstractExtension;
use MailPoetVendor\Twig\TwigFunction;

if (!defined('ABSPATH')) exit;

class Functions extends AbstractExtension {

  /** @var SettingsController */
  private $settings;

  /** @var WooCommerceHelper */
  private $woocommerce_helper;

  public function __construct() {
    $this->settings = new SettingsController();
    $this->woocommerce_helper = new WooCommerceHelper();
  }

  function getFunctions() {
    return array(
      new TwigFunction(
        'json_encode',
        'json_encode',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'json_decode',
        'json_decode',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'wp_nonce_field',
        'wp_nonce_field',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'params',
        array($this, 'params'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'admin_url',
        'admin_url',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'get_option',
        'get_option',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'sending_frequency',
        array($this, 'getSendingFrequency'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'wp_date_format',
        array($this, 'getWPDateFormat'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'mailpoet_version',
        array($this, 'getMailPoetVersion'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'mailpoet_premium_version',
        array($this, 'getMailPoetPremiumVersion'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'mailpoet_has_valid_premium_key',
        array($this, 'hasValidPremiumKey'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'wp_time_format',
        array($this, 'getWPTimeFormat'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'wp_datetime_format',
        array($this, 'getWPDateTimeFormat'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'do_action',
        'do_action',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'is_rtl',
        array($this, 'isRtl'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'number_format_i18n',
        'number_format_i18n',
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'mailpoet_locale',
        array($this, 'getTwoLettersLocale'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'mailpoet_free_domains',
        array($this, 'getFreeDomains'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'is_woocommerce_active',
        array($this, 'isWoocommerceActive'),
        array('is_safe' => array('all'))
      ),
      new TwigFunction(
        'wp_start_of_week',
        array($this, 'getWPStartOfWeek'),
        array('is_safe' => array('all'))
      ),
    );
  }

  function getSendingFrequency() {
    $args = func_get_args();
    $value = (int)array_shift($args);

    $label = null;
    $labels = array(
      'minute' => WPFunctions::get()->__('every minute', 'mailpoet'),
      'minutes' => WPFunctions::get()->__('every %1$d minutes', 'mailpoet'),
      'hour' => WPFunctions::get()->__('every hour', 'mailpoet'),
      'hours' => WPFunctions::get()->__('every %1$d hours', 'mailpoet')
    );

    if ($value >= 60) {
      // we're dealing with hours
      if ($value === 60) {
        $label = $labels['hour'];
      } else {
        $label = $labels['hours'];
      }
      $value /= 60;
    } else {
      // we're dealing with minutes
      if ($value === 1) {
        $label = $labels['minute'];
      } else {
        $label = $labels['minutes'];
      }
    }

    if ($label !== null) {
      return sprintf($label, $value);
    } else {
      return $value;
    }
  }

  function getWPDateFormat() {
    return WPFunctions::get()->getOption('date_format') ?: 'F j, Y';
  }

  function getWPStartOfWeek() {
    return WPFunctions::get()->getOption('start_of_week') ?: 0;
  }

  function getMailPoetVersion() {
    return MAILPOET_VERSION;
  }

  function getMailPoetPremiumVersion() {
    return (defined('MAILPOET_PREMIUM_VERSION')) ? MAILPOET_PREMIUM_VERSION : false;
  }

  function getWPTimeFormat() {
    return WPFunctions::get()->getOption('time_format') ?: 'g:i a';
  }

  function getWPDateTimeFormat() {
    return sprintf('%s %s', $this->getWPDateFormat(), $this->getWPTimeFormat());
  }

  function params($key = null) {
    $args = WPFunctions::get()->stripslashesDeep($_GET);
    if (array_key_exists($key, $args)) {
      return $args[$key];
    }
    return null;
  }

  function hasValidPremiumKey() {
    $checker = new ServicesChecker();
    return $checker->isPremiumKeyValid(false);
  }

  function installedInLastTwoWeeks() {
    $max_number_of_weeks = 2;
    $installed_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->settings->get('installed_at'));
    return $installed_at->diffInWeeks(Carbon::now()) < $max_number_of_weeks;
  }

  function isRtl() {
    return WPFunctions::get()->isRtl();
  }

  function getTwoLettersLocale() {
    return explode('_', WPFunctions::get()->getLocale())[0];
  }

  function getFreeDomains() {
    return FreeDomains::FREE_DOMAINS;
  }

  function isWoocommerceActive() {
    return $this->woocommerce_helper->isWooCommerceActive();
  }
}
