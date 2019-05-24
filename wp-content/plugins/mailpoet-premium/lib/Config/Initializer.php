<?php

namespace MailPoet\Premium\Config;

use MailPoet\DI\ContainerWrapper;
use MailPoet\Premium\DynamicSegments\SegmentStringsFilter;
use MailPoet\Premium\Models\NewsletterExtraData;
use MailPoet\Premium\AutomaticEmails\AutomaticEmails as AutomaticEmails;
use MailPoet\Premium\Newsletter\GATracking;
use MailPoet\WP\Functions as WPFunctions;
use MailPoet\Settings\SettingsController;
use MailPoet\Premium\Config\Hooks as ConfigHooks;
use MailPoetVendor\Psr\Container\ContainerInterface;

if (!defined('ABSPATH')) exit;

class Initializer {
  /** @var Menu */
  private $menu;
  public $automatic_emails;

  /** @var ContainerInterface */
  private $container;
  private $renderer;

  private $wp;

  function __construct($params = [
    'file' => '',
    'version' => '1.0.0',
  ]) {
    Env::init($params['file'], $params['version']);
    $this->wp = new WPFunctions;
  }

  function init() {
    WPFunctions::get()->registerActivationHook(
      Env::$file,
      ['MailPoet\Premium\Config\Activator', 'activate']
    );

    $this->wp->addAction('mailpoet_initialized', [
      $this,
      'setup',
    ]);
  }

  function setup() {
    $this->loadContainer();
    $this->setupLocalizer();
    $this->setupDB();
    $this->maybeDbUpdate();
    $this->setupRenderer();
    $this->setupMenu();

    $this->wp->addAction(
      'mailpoet_styles_admin_after',
      [$this, 'includePremiumStyles']
    );

    $this->wp->addAction(
      'mailpoet_scripts_admin_before',
      [$this, 'includePremiumJavascript']
    );

    $this->setupNewsletterExtraData();
    $this->setupGATracking();
    $this->setupCampaignStats();
    $this->setupAutomaticEmails();

    $this->wp->addAction(
      'mailpoet_setup_reset',
      ['MailPoet\Premium\Config\Activator', 'reset']
    );

    $this->setupHooks();
  }

  function maybeDbUpdate() {
    try {
      $current_db_version = $this->container->get(SettingsController::class)->get('premium_db_version');
    } catch (\Exception $e) {
      $current_db_version = null;
    }

    // if current db version and plugin version differ
    if (version_compare($current_db_version, Env::$version) !== 0) {
      Activator::activate();
    }
  }

  function setupDB() {
    $database = new Database();
    $database->init();
  }

  function loadContainer() {
    $this->container = ContainerWrapper::getInstance(WP_DEBUG)->getPremiumContainer();
  }

  function setupRenderer() {
    $this->renderer = $this->container->get(Renderer::class);
  }

  function setupLocalizer() {
    $localizer = new Localizer();
    $localizer->init();
  }

  function setupMenu() {
    $this->menu = $this->container->get(Menu::class);
    $this->wp->addAction(
      'mailpoet_menu_after_lists',
      [$this->menu, 'afterLists']
    );
  }

  function setupAutomaticEmails() {
    $automatic_emails = new AutomaticEmails();
    $automatic_emails->init();
    $this->automatic_emails = $automatic_emails->getAutomaticEmails();

    $this->wp->addAction(
      'mailpoet_newsletters_translations_after',
      [$this, 'includeAutomaticEmailsData']
    );

    $this->wp->addAction(
      'mailpoet_newsletter_editor_after_javascript',
      [$this, 'includeAutomaticEmailsData']
    );
  }

  function setupNewsletterExtraData() {
    NewsletterExtraData::init();
  }

  function setupGATracking() {
    GATracking::init();

    $this->wp->addAction(
      'mailpoet_newsletters_translations_after',
      [$this, 'newslettersGATracking']
    );
  }

  function setupCampaignStats() {
    $this->wp->addAction(
      'mailpoet_newsletters_translations_after',
      [$this, 'newslettersCampaignStats']
    );
  }

  function newslettersGATracking() {
    echo $this->renderer->render('newsletters/ga_tracking.html');
  }

  function newslettersCampaignStats() {
    // shortcode URLs to substitute with user-friendly names
    $data['shortcode_links'] = [
      '[link:subscription_unsubscribe_url]' => WPFunctions::get()->__('Unsubscribe link', 'mailpoet-premium'),
      '[link:subscription_manage_url]' => WPFunctions::get()->__('Manage subscription link', 'mailpoet-premium'),
      '[link:newsletter_view_in_browser_url]' => WPFunctions::get()->__('View in browser link', 'mailpoet-premium'),
    ];

    echo $this->renderer->render('newsletters/campaign_stats.html', $data);
  }

  function includePremiumStyles() {
    echo $this->renderer->render('styles.html');
  }

  function includePremiumJavascript() {
    echo $this->renderer->render('scripts.html');
  }

  function includeAutomaticEmailsData() {
    $data = [
      'automatic_emails' => $this->automatic_emails,
    ];

    echo $this->renderer->render('newsletters/automatic_emails.html', $data);
  }

  function setupHooks() {
    $hooks = new ConfigHooks();
    $hooks->init();
  }
}
