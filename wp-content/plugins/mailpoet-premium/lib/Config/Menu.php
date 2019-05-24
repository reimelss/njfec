<?php

namespace MailPoet\Premium\Config;

use MailPoet\Config\AccessControl;
use MailPoet\Models\Newsletter;
use MailPoet\WooCommerce\Helper as WooCommerceHelper;
use MailPoet\WP\Functions as WPFunctions;

class Menu {

  /** @var Renderer */
  private $renderer;

  /** @var AccessControl */
  private $access_control;

  /** @var \MailPoet\Config\Menu */
  private $free_plugin_menu;

  /** @var WooCommerceHelper */
  private $woocommerce_helper;

  private $wp;

  function __construct(Renderer $renderer, AccessControl $access_control, \MailPoet\Config\Menu $free_plugin_menu, WPFunctions $wp, WooCommerceHelper $woocommerce_helper) {
    $this->access_control = $access_control;
    $this->free_plugin_menu = $free_plugin_menu;
    $this->renderer = $renderer;
    $this->wp = $wp;
    $this->woocommerce_helper = $woocommerce_helper;
  }

  function afterLists() {
    if ($this->access_control->validatePermission(AccessControl::PERMISSION_MANAGE_SEGMENTS)) {
      // Dynamic segments page
      // Only show this page in menu if the Premium plugin is activated
      $dynamic_segments_page = WPFunctions::get()->addSubmenuPage(
        \MailPoet\Config\Menu::MAIN_PAGE_SLUG,
        $this->free_plugin_menu->setPageTitle(__('Segments', 'mailpoet-premium')),
        WPFunctions::get()->__('Segments', 'mailpoet-premium'),
        AccessControl::PERMISSION_MANAGE_SEGMENTS,
        'mailpoet-dynamic-segments',
        [
          $this,
          'dynamicSegments',
        ]
      );

      // add limit per page to screen options
      WPFunctions::get()->addAction('load-' . $dynamic_segments_page, function() {
        WPFunctions::get()->addScreenOption('per_page', [
          'label' => WPFunctions::get()->x('Number of segments per page', 'segments per page (screen options)', 'mailpoet-premium'),
          'option' => 'mailpoet_dynamic_segments_per_page',
        ]);
      });
    }
  }

  function dynamicSegments() {
    $data = [];

    $data['page_name'] = 'dynamic_segments';

    $this->wp->addAction(
      'mailpoet_pages_dynamic_segments',
      [$this, 'renderDynamicSegments']
    );
    $this->wp->addAction(
      'mailpoet_pages_translations_dynamic_segments',
      [$this, 'renderDynamicSegmentsTranslations']
    );
    $this->free_plugin_menu->displayPage('blank.html', $data);
  }

  function renderDynamicSegments() {
    $data = [];

    $data['items_per_page'] = $this->free_plugin_menu->getLimitPerPage('dynamic_segments');
    $wp_roles = WPFunctions::get()->getEditableRoles();
    $data['wordpress_editable_roles_list'] = array_map(function($role_id, $role) {
      return [
        'role_id' => $role_id,
        'role_name' => $role['name'],
      ];
    }, array_keys($wp_roles), $wp_roles);
    $data['newsletters_list'] = Newsletter::select(['id', 'subject', 'sent_at'])
      ->whereNull('deleted_at')
      ->where('type', Newsletter::TYPE_STANDARD)
      ->orderByExpr('ISNULL(sent_at) DESC, sent_at DESC')->findArray();


    $data['product_categories'] = WPFunctions::get()->getCategories(['taxonomy' => 'product_cat']);
    usort($data['product_categories'], function ($a, $b) {
      return strcmp($a->cat_name, $b->cat_name);
    });
    $data['products'] = $this->getProducts();
    $data['is_woocommerce_active'] = $this->woocommerce_helper->isWooCommerceActive();

    echo $this->renderer->render('dynamicSegments.html', $data);
  }

  private function getProducts() {
    $args = ['post_type' => 'product', 'orderby' => 'title', 'order' => 'ASC', 'numberposts' => -1];
    $products = WPFunctions::get()->getPosts($args);
    return array_map(function ($product) {
      return [
        'title' => $product->post_title,
        'ID' => $product->ID,
      ];
    }, $products);
  }

  function renderDynamicSegmentsTranslations() {
    $data = [];

    echo $this->renderer->render('dynamicSegmentsTranslations.html', $data);
  }

}
