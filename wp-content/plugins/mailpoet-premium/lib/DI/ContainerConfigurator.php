<?php

namespace MailPoet\Premium\DI;

use MailPoet\DI\IContainerConfigurator;
use MailPoetVendor\Symfony\Component\DependencyInjection\ContainerBuilder;
use MailPoetVendor\Symfony\Component\DependencyInjection\Reference;

class ContainerConfigurator implements IContainerConfigurator {

  function getDumpNamespace() {
    return 'MailPoetGenerated';
  }

  function getDumpClassname() {
    return 'PremiumCachedContainer';
  }

  function configure(ContainerBuilder $container) {
    // Factory for free deps
    $container->register(IContainerConfigurator::FREE_CONTAINER_SERVICE_SLUG)
      ->setSynthetic(true)
      ->setPublic(true);

    // Free plugin dependencies
    $this->registerFreeService($container, \MailPoet\Config\AccessControl::class);
    $this->registerFreeService($container, \MailPoet\Config\Menu::class);
    $this->registerFreeService($container, \MailPoet\Features\FeaturesController::class);
    $this->registerFreeService($container, \MailPoet\Listing\BulkActionController::class);
    $this->registerFreeService($container, \MailPoet\Listing\Handler::class);
    $this->registerFreeService($container, \MailPoet\WooCommerce\Helper::class);
    $this->registerFreeService($container, \MailPoet\Settings\SettingsController::class);
    $this->registerFreeService($container, \MailPoet\WP\Functions::class);

    // API
    $container->autowire(\MailPoet\Premium\API\JSON\v1\AutomaticEmails::class)->setPublic(true);
    $container->autowire(\MailPoet\Premium\API\JSON\v1\DynamicSegments::class)->setPublic(true);
    $container->autowire(\MailPoet\Premium\API\JSON\v1\NewsletterLinks::class)->setPublic(true);
    $container->autowire(\MailPoet\Premium\API\JSON\v1\Stats::class)->setPublic(true);
    // AutomaticEmails
    $container->autowire(\MailPoet\Premium\AutomaticEmails\WooCommerce\Helper::class);
    // Config
    $container->autowire(\MailPoet\Premium\Config\Menu::class)->setPublic(true);
    $container->register(\MailPoet\Premium\Config\Renderer::class)
      ->setPublic(true)
      ->setFactory([__CLASS__, 'createRenderer']);
    return $container;
  }

  private function registerFreeService(ContainerBuilder $container, $id) {
    $container->register($id)
      ->setPublic(true)
      ->addArgument($id)
      ->setFactory([
        new Reference(IContainerConfigurator::FREE_CONTAINER_SERVICE_SLUG),
        'get',
      ]);
  }

  static function createRenderer() {
    $caching = !WP_DEBUG;
    $debugging = WP_DEBUG;
    return new \MailPoet\Premium\Config\Renderer($caching, $debugging);
  }
}
