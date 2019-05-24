<?php

if ( ! class_exists( 'rtTPGOptions' ) ):

	class rtTPGOptions {

		function rtPostTypes() {
			$post_types = get_post_types(
				array(
					'public' => true,
//                    '_builtin' => true
				)
			);
			$exclude    = array( 'attachment', 'revision', 'nav_menu_item' );
			foreach ( $exclude as $ex ) {
				unset( $post_types[ $ex ] );
			}

			return $post_types;
		}

		function rtPostOrders() {
			return array(
				"ASC"  => __( "Ascending", 'the-post-grid-pro' ),
				"DESC" => __( "Descending", 'the-post-grid-pro' )
			);
		}

		function rtTermOperators() {
			return array(
				'IN'     => __( "IN — show posts which associate with one or more of selected terms",
					'the-post-grid-pro' ),
				'NOT IN' => __( "NOT IN — show posts which do not associate with any of selected terms",
					'the-post-grid-pro' ),
				'AND'    => __( "AND — show posts which associate with all of selected terms", 'the-post-grid-pro' )
			);
		}

		function rtTermRelations() {
			return array(
				'AND' => __( "AND — show posts which match all settings", 'the-post-grid-pro' ),
				'OR'  => __( "OR — show posts which match one or more settings", 'the-post-grid-pro' )
			);
		}

		function rtMetaKeyType() {
			return array(
				'meta_value'          => __( 'Meta value', 'the-post-grid-pro' ),
				'meta_value_num'      => __( 'Meta value number', 'the-post-grid-pro' ),
				'meta_value_datetime' => __( 'Meta value datetime', 'the-post-grid-pro' ),
			);
		}

		function rtPostOrderBy( $isWoCom = false, $metaOrder = false ) {
			$orderBy  = array(
				"ID"            => __( "ID", 'the-post-grid-pro' ),
				"title"         => __( "Title", 'the-post-grid-pro' ),
				"date"          => __( "Created date", 'the-post-grid-pro' ),
				"modified"      => __( "Modified date", 'the-post-grid-pro' ),
				"rand"          => __( "Random", 'the-post-grid-pro' ),
				"comment_count" => __( "Number of comments", 'the-post-grid-pro' ),
				"menu_order"    => __( "Menu Order", 'the-post-grid-pro' )
			);
			$wooOrder = array(
				"price"  => __( "Price", 'the-post-grid-pro' ),
				"rating" => __( "AVG Rating", 'the-post-grid-pro' )
			);
			$orderBy  = $isWoCom ? array_merge( $orderBy, $wooOrder ) : $orderBy;
			$orderBy  = $metaOrder ? array_merge( $orderBy, $this->rtMetaKeyType() ) : $orderBy;

			return $orderBy;
		}

		function rtTPGSettingsCustomCssFields() {
			global $rtTPG;
			$settings = get_option( $rtTPG->options['settings'] );

			return array(
				"custom_css" => array(
					'type'        => 'custom_css',
					'holderClass' => 'full',
					'id'          => 'custom-css',
					'value'       => isset( $settings['custom_css'] ) ? trim( $settings['custom_css'] ) : null
				)
			);
		}

		function rtTPGSettingsOtherSettingsFields() {
			global $rtTPG;
			$settings = get_option( $rtTPG->options['settings'] );

			return array(
				'template_author'   => array(
					'type'    => 'select',
					'name'    => 'template_author',
					'label'   => 'Template Author',
					'id'      => 'template_author',
					'class'   => 'select2',
					'blank'   => 'Select a layout',
					'options' => $rtTPG->getTPGShortCodeList(),
					'value'   => isset( $settings['template_author'] ) ? $settings['template_author'] : array(),
				),
				'template_category' => array(
					'type'    => 'select',
					'name'    => 'template_category',
					'label'   => 'Template Category',
					'id'      => 'template_category',
					'class'   => 'select2',
					'blank'   => 'Select a layout',
					'options' => $rtTPG->getTPGShortCodeList(),
					'value'   => isset( $settings['template_category'] ) ? $settings['template_category'] : array(),
				),
				'template_search'   => array(
					'type'    => 'select',
					'name'    => 'template_search',
					'label'   => 'Template Search',
					'id'      => 'template_search',
					'class'   => 'select2',
					'blank'   => 'Select a layout',
					'options' => $rtTPG->getTPGShortCodeList(),
					'value'   => isset( $settings['template_search'] ) ? $settings['template_search'] : array(),
				),
				'template_tag'      => array(
					'type'    => 'select',
					'name'    => 'template_tag',
					'label'   => 'Template Tag',
					'id'      => 'template_tag',
					'class'   => 'select2',
					'blank'   => 'Select a layout',
					'options' => $rtTPG->getTPGShortCodeList(),
					'value'   => isset( $settings['template_tag'] ) ? $settings['template_tag'] : array(),
				),
				'template_class'    => array(
					'type'  => 'text',
					'name'  => 'template_class',
					'label' => 'Template class',
					'id'    => 'template_class',
					'value' => isset( $settings['template_class'] ) ? $settings['template_class'] : '',
				)
			);
		}

		function rtTPGLicenceField() {
			global $rtTPG;
			$settings = get_option( $rtTPG->options['settings'] );

			return array(
				"license_key" => array(
					'type'        => 'text',
					'name'        => 'license_key',
					'attr'        => 'style="min-width:300px;"',
					'label'       => 'Enter your license key',
					'description' => "<span style='color:red'>After saving licence key, you need to active your licence by clicking the Active licence button</span>",
					'id'          => 'license_key',
					'value'       => isset( $settings['license_key'] ) ? $settings['license_key'] : ''
				)
			);
		}

		function rtTPGSettingsSocialShareFields() {
			global $rtTPG;
			$settings = get_option( $rtTPG->options['settings'] );

			return array(
				"social_share_items" => array(
					'type'      => 'checkbox',
					'name'      => 'social_share_items',
					'label'     => 'Social share items',
					'id'        => 'social_share_items',
					'alignment' => 'vertical',
					'multiple'  => true,
					'options'   => $rtTPG->socialShareItemList(),
					'value'     => isset( $settings['social_share_items'] ) ? $settings['social_share_items'] : array()
				)
			);
		}

		function socialShareItemList() {
			return array(
				'facebook'    => 'Facebook',
				'twitter'     => 'Twitter',
				'google-plus' => 'Google +',
				'linkedin'    => 'LinkedIn',
				'pinterest'   => 'Pinterest',
			);
		}

		function templateOverrideItemList() {
			return array(
				'category-archive' => "Category archive",
				'tag-archive'      => "Tag archive",
				'author-archive'   => "Author archive",
				'search'           => "Search page",
			);
		}

		function rtTPGCommonFilterFields() {
			return array(
				'post__in'     => array(
					"name"        => "post__in",
					"label"       => "Include only",
					"type"        => "text",
					"class"       => "full",
					"description" => 'List of post IDs to show (comma-separated values, for example: 1,2,3)'
				),
				'post__not_in' => array(
					"name"        => "post__not_in",
					"label"       => "Exclude",
					"type"        => "text",
					"class"       => "full",
					"description" => 'List of post IDs to hide (comma-separated values, for example: 1,2,3)'
				),
				'limit'        => array(
					"name"        => "limit",
					"label"       => "Limit",
					"type"        => "number",
					"class"       => "full",
					"description" => 'The number of posts to show. Set empty to show all found posts.'
				)
			);
		}

		function rtTPGPostType() {
			return array(
				'tpg_post_type' => array(
					"label"   => "Post Type",
					"type"    => "select",
					"id"      => "rt-sc-post-type",
					"class"   => "-rt-select2",
					"options" => $this->rtPostTypes()
				)
			);
		}

		function rtTPAdvanceFilters() {
			return array(
				'post_filter' => array(
					'type'      => "checkbox",
					'name'      => "post_filter",
					'label'     => "Advanced filters",
					"alignment" => "vertical",
					"multiple"  => true,
					"options"   => array(
						'tpg_taxonomy'    => "Taxonomy",
						'order'           => "Order",
						'author'          => "Author",
						'tpg_post_status' => "Status",
						's'               => "Search",
						'date_range'      => "Date Range"
					),
				)
			);
		}

		function rtTPGPostStatus() {
			return array(
				'publish'    => 'Publish',
				'pending'    => 'Pending',
				'draft'      => 'Draft',
				'auto-draft' => 'Auto draft',
				'future'     => 'Future',
				'private'    => 'Private',
				'inherit'    => 'Inherit',
				'trash'      => 'Trash',
			);
		}

		function owl_property() {
			return array(
				'auto_play'   => 'Auto Play',
				'loop'        => 'Loop',
				'nav_button'  => 'Nav Button',
				'pagination'  => 'Pagination',
				'stop_hover'  => 'Stop Hover',
				'auto_height' => 'Auto Height',
				'lazy_load'   => 'Lazy Load',
				'rtl'         => 'Right to left (RTL)'
			);
		}

		function rtTPGLayoutSettingFields() {
			global $rtTPG;

			return array(
				'layout'                           => array(
					"type"    => "select",
					"name"    => "layout",
					"label"   => "Layout",
					"id"      => "rt-tpg-sc-layout",
					"class"   => "rt-select2",
					"options" => $this->rtTPGLayouts()
				),
				'tgp_filter'                       => array(
					"type"        => "checkbox",
					"label"       => "Filter",
					'holderClass' => "sc-tpg-grid-filter tpg-hidden",
					"multiple"    => true,
					"alignment"   => 'vertical',
					"options"     => $rtTPG->tgp_filter_list()
				),
				'tgp_filter_taxonomy'              => array(
					"type"        => "select",
					"label"       => "Taxonomy Filter",
					'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
					"class"       => "rt-select2",
					"options"     => $rtTPG->rt_get_taxonomy_for_filter()
				),
				'tgp_filter_taxonomy_hierarchical' => array(
					"type"        => "checkbox",
					"label"       => "Display as sub category",
					'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
					"option"      => "Active"
				),
				'tgp_filter_type'                  => array(
					"type"        => "select",
					"label"       => "Taxonomy filter type",
					'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
					"class"       => "rt-select2",
					"options"     => $rtTPG->rt_filter_type()
				),
				'tgp_default_filter'               => array(
					"type"        => "select",
					"label"       => "Selected filter term (Selected item)",
					'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
					"class"       => "rt-select2",
					"attr"        => "data-selected='" . get_post_meta( get_the_ID(), 'tgp_default_filter',
							true ) . "'",
					"options"     => array( '' => __( 'All', 'the-post-grid-pro' ) )
				),
				'tpg_hide_all_button'              => array(
					"type"        => "checkbox",
					"label"       => "Hide All (Show all) button",
					'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
					"option"      => 'Hide'
				),
				'tpg_post_count'                   => array(
					"type"        => "checkbox",
					"label"       => "Show post count",
					'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
					"option"      => 'Enable'
				),
				'isotope_filter'                   => array(
					"type"        => "select",
					"label"       => "Isotope Filter",
					'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
					"id"          => "rt-tpg-sc-isotope-filter",
					"class"       => "rt-select2",
					"options"     => $rtTPG->rt_get_taxonomy_for_filter()
				),
				'isotope_default_filter'           => array(
					"type"        => "select",
					"label"       => "Isotope filter (Selected item)",
					'holderClass' => "isotope-item sc-isotope-default-filter tpg-hidden",
					"id"          => "rt-tpg-sc-isotope-default-filter",
					"class"       => "rt-select2",
					"attr"        => "data-selected='" . get_post_meta( get_the_ID(), 'isotope_default_filter',
							true ) . "'",
					"options"     => array( '' => __( 'Show All', 'the-post-grid-pro' ) )
				),
				'isotope_filter_dropdown'          => array(
					"type"        => "checkbox",
					"label"       => "Isotope dropdown filter",
					'holderClass' => "isotope-item sc-isotope-filter sc-isotope-filter-dropdown tpg-hidden",
					"option"      => 'Enable'
				),
				'isotope_filter_show_all'          => array(
					"type"        => "checkbox",
					"name"        => "isotope_filter_show_all",
					"label"       => "Isotope filter (Show All item)",
					'holderClass' => "isotope-item sc-isotope-filter-show-all tpg-hidden",
					"id"          => "rt-tpg-sc-isotope-filter-show-all",
					"option"      => 'Disable'
				),
				'isotope_filter_count'             => array(
					"type"        => "checkbox",
					"label"       => "Isotope filter count number",
					'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
					"option"      => 'Enable'
				),
				'isotope_filter_url'               => array(
					"type"        => "checkbox",
					"label"       => "Isotope filter URL",
					'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
					"option"      => 'Enable'
				),
				'isotope_search_filter'            => array(
					"type"        => "checkbox",
					"label"       => "Isotope search filter",
					'holderClass' => "isotope-item sc-isotope-search-filter tpg-hidden",
					"id"          => "rt-tpg-sc-isotope-search-filter",
					"option"      => 'Enable'
				),
				'carousel_property'                => array(
					"type"        => "checkbox",
					"label"       => "Carousel property",
					"multiple"    => true,
					"alignment"   => 'vertical',
					'holderClass' => "carousel-item carousel-property tpg-hidden",
					"id"          => "carousel-property",
					"default"     => array( 'pagination' ),
					"options"     => $this->owl_property()
				),
				'tpg_carousel_speed'               => array(
					"label"       => __( "Speed", 'the-post-grid-pro' ),
					"holderClass" => "tpg-hidden carousel-item",
					"type"        => "number",
					'default'     => 250,
					"description" => __( 'Auto play Speed in milliseconds', 'the-post-grid-pro' ),
				),
				'tpg_carousel_autoplay_timeout'    => array(
					"label"       => __( "Autoplay timeout", 'the-post-grid-pro' ),
					"holderClass" => "tpg-hidden carousel-item tpg-carousel-auto-play-timeout",
					"type"        => "number",
					'default'     => 5000,
					"description" => __( 'Autoplay interval timeout', 'the-post-grid-pro' ),
				),
				'tgp_layout2_image_column'         => array(
					'type'        => 'select',
					'label'       => __( 'Image column', 'the-post-grid-pro' ),
					'class'       => 'rt-select2',
					'holderClass' => "holder-layout2-image-column tpg-hidden",
					'default'     => 4,
					'options'     => $this->scColumns(),
					"description" => "Content column will calculate automatically"
				),
				'column'                           => array(
					'type'        => 'select',
					'label'       => __( 'Desktop column', 'the-post-grid-pro' ),
					'class'       => 'rt-select2',
					'holderClass' => "offset-column-wrap",
					'default'     => 3,
					'options'     => $this->scColumns()
				),
				'tpg_tab_column'                   => array(
					'type'        => 'select',
					'label'       => __( 'Tab column', 'the-post-grid-pro' ),
					'class'       => 'rt-select2',
					'holderClass' => "offset-column-wrap",
					'default'     => 2,
					'options'     => $this->scColumns()
				),
				'tpg_mobile_column'                => array(
					'type'        => 'select',
					'label'       => __( 'Mobile column', 'the-post-grid-pro' ),
					'class'       => 'rt-select2',
					'holderClass' => "offset-column-wrap",
					'default'     => 1,
					'options'     => $this->scColumns()
				),
				'ignore_sticky_posts'              => array(
					"type"      => "radio",
					"label"     => "Show sticky posts at the top",
					"alignment" => "vertical",
					"default"   => true,
					"options"   => array(
						false => "Yes",
						true  => "No",
					)
				),
				'pagination'                       => array(
					"type"        => "checkbox",
					"label"       => "Pagination",
					'holderClass' => "pagination",
					"id"          => "rt-tpg-pagination",
					"option"      => 'Enable'
				),
				'posts_per_page'                   => array(
					"type"        => "number",
					"label"       => "Display per page",
					'holderClass' => "pagination-item posts-per-page tpg-hidden",
					"default"     => 5,
					"description" => "If value of Limit setting is not blank (empty), this value should be smaller than Limit value."
				),
				'posts_loading_type'               => array(
					"type"        => "radio",
					"label"       => "Post Loading Type",
					'holderClass' => "pagination-item posts-loading-type tpg-hidden",
					"alignment"   => "vertical",
					"default"     => 'pagination',
					"options"     => $this->postLoadingType(),
				),
				'feature_image'                    => array(
					"type"   => "checkbox",
					"label"  => "Feature Image",
					"id"     => "rt-tpg-feature-image",
					"option" => 'Disable'
				),
				'featured_image_size'              => array(
					"type"        => "select",
					"label"       => "Feature Image Size",
					"class"       => "rt-select2",
					'holderClass' => "rt-feature-image-option tpg-hidden",
					"options"     => $rtTPG->get_image_sizes()
				),
				'custom_image_size'                => array(
					"type"        => "image_size",
					"label"       => "Custom Image Size",
					'holderClass' => "rt-feature-image-option rt-sc-custom-image-size-holder tpg-hidden",
					"multiple"    => true
				),
				'media_source'                     => array(
					"type"        => "radio",
					"label"       => "Media Source",
					"default"     => 'feature_image',
					"alignment"   => "vertical",
					'holderClass' => "rt-feature-image-option tpg-hidden",
					"options"     => $this->rtMediaSource()
				),
				'tpg_image_type'                   => array(
					"type"        => "radio",
					"label"       => __( "Image Type", 'the-post-grid-pro' ),
					"alignment"   => "vertical",
					'holderClass' => "rt-feature-image-option tpg-hidden",
					"default"     => 'normal',
					"options"     => $this->get_image_types()
				),
				'tgp_excerpt_type'                 => array(
					"type"      => "radio",
					"label"     => "Excerpt Type",
					"alignment" => "vertical",
					"default"   => 'character',
					"options"   => $this->get_excerpt_type(),
				),
				'excerpt_limit'                    => array(
					"type"        => "number",
					"label"       => "Excerpt limit",
					"description" => "Excerpt limit only integer number is allowed, Leave it blank for full excerpt."
				),
				'tgp_excerpt_more_text'            => array(
					"type"  => "text",
					"label" => "Excerpt more text"
				),
				'tgp_read_more_text'               => array(
					"type"  => "text",
					"label" => "Read more text"
				),
				'margin_option'                    => array(
					"type"        => "radio",
					"label"       => "Margin",
					"alignment"   => "vertical",
					"description" => "Select the margin for layout",
					"default"     => "default",
					"options"     => $this->scMarginOpt()
				),
				'grid_style'                       => array(
					"type"        => "radio",
					"label"       => "Grid style",
					"alignment"   => "vertical",
					"description" => "Select grid style for layout",
					"default"     => "even",
					"options"     => $this->scGridOpt()
				),
				'link_to_detail_page'              => array(
					"type"      => "radio",
					"label"     => "Link To Detail Page",
					"alignment" => "vertical",
					"default"   => 'yes',
					"options"   => array(
						'yes' => 'Yes',
						'no'  => 'No'
					)
				),
				'detail_page_link_type'            => array(
					"type"        => "radio",
					"label"       => "Detail page link Type",
					'holderClass' => "detail-page-link-type tpg-hidden",
					"alignment"   => "vertical",
					"default"     => "popup",
					"options"     => array(
						'popup'    => "PopUp",
						'new_page' => "New Page"
					)
				),
				'popup_type'                       => array(
					"type"        => "radio",
					"label"       => "PopUp Type",
					'holderClass' => "popup-type tpg-hidden",
					"alignment"   => "vertical",
					"default"     => "single",
					"options"     => array(
						'single' => "Single PopUp",
						'multi'  => "Multi PopUp",
					)
				),
				'restriction_user_role'            => array(
					"type"        => "select",
					"label"       => "Content will be visible for",
					"class"       => "rt-select2",
					"multiple"    => true,
					"blank"       => "Allowed for all",
					"description" => "Leave it blank for all",
					"options"     => $rtTPG->getAllUserRoles()
				),
				'default_preview_image'            => array(
					"type"        => "image",
					"label"       => "Default preview  image",
					"description" => "Add an image for default preview"
				)
			);
		}


		function scMarginOpt() {
			return array(
				'default' => "Bootstrap default",
				'no'      => "No Margin"
			);
		}

		function scGridType() {
			return array(
				'even'    => "Even Grid",
				'masonry' => "Masonry"
			);
		}

		function rtTpgSettingsDetailFieldSelection() {
			global $rtTPG;
			$settings = get_option( $rtTPG->options['settings'] );

			$fields = array(
				"popup_fields" => array(
					'type'      => 'checkbox',
					'label'     => 'Field Selection',
					'id'        => 'popup-fields',
					'alignment' => 'vertical',
					'multiple'  => true,
					'options'   => $rtTPG->detailAvailableFields(),
					'value'     => isset( $settings['popup_fields'] ) ? $settings['popup_fields'] : array()
				)
			);
			$cf = $rtTPG->checkWhichCustomMetaPluginIsInstalled();
			if ( $cf ) {
				$plist = $rtTPG->getCFPluginList();
				$pName = !empty($plist[$cf])? $plist[$cf] : " - ";
				$fields['cf_group']            = array(
					"type"        => "checkbox",
					"name"        => "cf_group",
					"holderClass" => "tpg-hidden cfs-fields cf-group",
					"label"       => "Custom Field group " . " ({$pName})",
					"multiple"    => true,
					"alignment"   => "vertical",
					"id"          => "cf_group",
					"options"     => $rtTPG->get_groups_by_post_type( 'all' ),
					"value"       => isset( $settings['cf_group'] ) ? $settings['cf_group'] : array()
				);
				$fields['cf_hide_empty_value'] = array(
					"type"        => "checkbox",
					"name"        => "cf_hide_empty_value",
					"holderClass" => "tpg-hidden cfs-fields",
					"label"       => "Hide field with empty value",
					"value"       => ! empty( $settings['cf_hide_empty_value'] ) ? 1 : 0
				);
				$fields['cf_show_only_value']  = array(
					"type"        => "checkbox",
					"name"        => "cf_show_only_value",
					"holderClass" => "tpg-hidden cfs-fields",
					"label"       => "Show only value of field",
					"description" => "By default both name & value of field is shown",
					"value"       => ! empty( $settings['cf_show_only_value'] ) ? 1 : 0
				);
				$fields['cf_hide_group_title'] = array(
					"type"        => "checkbox",
					"name"        => "cf_hide_group_title",
					"holderClass" => "tpg-hidden cfs-fields",
					"label"       => "Hide group title",
					"value"       => ! empty( $settings['cf_hide_group_title'] ) ? 1 : 0
				);
			}

			return $fields;
		}

		function detailAvailableFields() {

			$fields   = $this->rtTPGItemFields();
			$inserted = array(
				'feature_img' => 'Feature Image',
				'content'     => 'Content'
			);
			unset( $fields['excerpt'] );
			unset( $fields['read_more'] );
			unset( $fields['comment_count'] );
			$offset                    = array_search( 'title', array_keys( $fields ) ) + 1;
			$newFields                 = array_slice( $fields, 0, $offset, true ) + $inserted + array_slice( $fields,
					$offset, null, true );
			$newFields['social_share'] = "Social Share";

			return $newFields;
		}

		function rtTPGStyleFields() {
			global $rtTPG;

			return array(
				'parent_class'            => array(
					"type"        => "text",
					"label"       => "Parent class",
					"class"       => "medium-text",
					"description" => "Parent class for adding custom css"
				),
				'primary_color'           => array(
					"type"    => "text",
					"label"   => "Primary Color",
					"class"   => "rt-color",
					"default" => "#0367bf"
				),
				'button_bg_color'         => array(
					"type"  => "text",
					"name"  => "button_bg_color",
					"label" => "Button background color",
					"class" => "rt-color"
				),
				'button_hover_bg_color'   => array(
					"type"  => "text",
					"name"  => "button_hover_bg_color",
					"label" => "Button hover background color",
					"class" => "rt-color"
				),
				'button_active_bg_color'  => array(
					"type"  => "text",
					"label" => "Button active background color",
					"class" => "rt-color"
				),
				'button_border_color'     => array(
					"type"  => "text",
					"label" => "Button border color",
					"class" => "rt-color"
				),
				'button_text_bg_color'    => array(
					"type"  => "text",
					"label" => "Button text color",
					"class" => "rt-color"
				),
				'button_hover_text_color' => array(
					"type"  => "text",
					"label" => "Button hover text color",
					"class" => "rt-color"
				),
				'tgp_gutter'              => array(
					'type'        => 'number',
					'label'       => __( 'Gutter / Padding', 'the-post-grid-pro' ),
					'description' => __( "Unit will be pixel, No need to give any unit. Only integer value will be valid.<br> Leave it blank for default",
						'the-post-grid-pro' )
				),
				'overlay_color'           => array(
					"type"  => "text",
					"label" => "Overlay color",
					"class" => "rt-color"
				),
				'overlay_opacity'         => array(
					"type"        => "select",
					"label"       => "Overlay opacity",
					"class"       => "rt-select2",
					"default"     => .8,
					"options"     => $rtTPG->overflowOpacity(),
					"description" => __( "Overlay opacity use only positive integer value", 'the-post-grid-pro' )
				),
				'overlay_padding'         => array(
					"type"        => "number",
					"label"       => "Overlay top padding",
					"class"       => "small-text",
					"description" => __( "Overlay top padding use only positive integer value, e.g : 20 (with out postfix like px, em, % etc). it will displayed by %",
						'the-post-grid-pro' )
				)
			);

		}

		function itemFields() {
			global $rtTPG;

			$fields = array(
				'item_fields' => array(
					"type"      => "checkbox",
					"name"      => "item_fields",
					"label"     => "Field selection",
					"id"        => "item-fields",
					"multiple"  => true,
					"alignment" => "vertical",
					"default"   => array_keys( $this->rtTPGItemFields() ),
					"options"   => $this->rtTPGItemFields()
				)
			);
			if ( $cf = $rtTPG->checkWhichCustomMetaPluginIsInstalled() ) {
				global $post;
				$post_type                     = get_post_meta( $post->ID, 'tpg_post_type', true );
				$fields['cf_group']            = array(
					"type"        => "checkbox",
					"name"        => "cf_group",
					"holderClass" => "tpg-hidden cf-fields cf-group",
					"label"       => "Custom Field group " . " ({$rtTPG->getCFPluginList()[$cf]})",
					"multiple"    => true,
					"alignment"   => "vertical",
					"id"          => "cf_group",
					"options"     => $rtTPG->get_groups_by_post_type( $post_type, $cf )
				);
				$fields['cf_hide_empty_value'] = array(
					"type"        => "checkbox",
					"name"        => "cf_hide_empty_value",
					"holderClass" => "tpg-hidden cf-fields",
					"label"       => "Hide field with empty value",
					"default"     => 1
				);
				$fields['cf_show_only_value']  = array(
					"type"        => "checkbox",
					"name"        => "cf_show_only_value",
					"holderClass" => "tpg-hidden cf-fields",
					"label"       => "Show only value of field",
					"description" => "By default both name & value of field is shown"
				);
				$fields['cf_hide_group_title'] = array(
					"type"        => "checkbox",
					"name"        => "cf_hide_group_title",
					"holderClass" => "tpg-hidden cf-fields",
					"label"       => "Hide group title"
				);
			}

			return $fields;
		}

		function getCFPluginList() {
			return array(
				'acf' => "Advanced Custom Field"
			);
		}

		function rtMediaSource() {
			return array(
				"feature_image" => "Feature Image",
				"first_image"   => "First Image from content"
			);
		}

		function get_image_types() {
			return array(
				'normal' => "Normal",
				'circle' => "Circle"
			);
		}

		function get_excerpt_type() {
			return array(
				'character' => "Character",
				'word'      => "Word"
			);
		}

		function scColumns() {
			return array(
				1 => "Column 1",
				2 => "Column 2",
				3 => "Column 3",
				4 => "Column 4",
				5 => "Column 5",
				6 => "Column 6"
			);
		}

		function tgp_filter_list() {
			return array(
				'_taxonomy_filter' => 'Taxonomy filter',
				'_order_by'        => 'Order - Sort retrieved posts by parameter',
				'_sort_order'      => 'Sort Order - Designates the ascending or descending order of the "orderby" parameter',
				'_search'          => "Search filter",
			);
		}

		function overflowOpacity() {
			return array(
				1 => '10%',
				2 => '20%',
				3 => '30%',
				4 => '40%',
				5 => '50%',
				6 => '60%',
				7 => '70%',
				8 => '80%',
				9 => '90%',
			);
		}

		function rtTPGLayouts() {
			$layouts               = array();
			$layouts['layout1']    = "Layout 1";
			$layouts['layout2']    = "Layout 2";
			$layouts['layout3']    = "Layout 3";
			$layouts['layout4']    = "Layout 4";
			$layouts['layout5']    = "Layout 5";
			$layouts['layout6']    = "Layout 6";
			$layouts['layout7']    = "Layout 7";
			$layouts['layout8']    = "Layout 8";
			$layouts['layout9']    = "Layout 9";
			$layouts['layout10']   = "Layout 10";
			$layouts['layout11']   = "Layout 11";
			$layouts['layout12']   = "Layout 12";
			$layouts['layout13']   = "Layout 13";
			$layouts['layout14']   = "Layout 14";
			$layouts['layout15']   = "Layout 15";
			$layouts['layout16']   = "Layout 16";
			$layouts['layout17']   = "Layout 17 Gallery layout";
			$layouts['offset01']   = "Offset 01";
			$layouts['offset02']   = "Offset 02";
			$layouts['offset03']   = "Offset 03";
			$layouts['offset04']   = "Offset 04";
			$layouts['isotope1']   = "Isotope Layout 1";
			$layouts['isotope2']   = "Isotope Layout 2";
			$layouts['isotope3']   = "Isotope Layout 3";
			$layouts['isotope4']   = "Isotope Layout 4";
			$layouts['isotope5']   = "Isotope Layout 5";
			$layouts['isotope6']   = "Isotope Layout 6";
			$layouts['isotope7']   = "Isotope Layout 7";
			$layouts['isotope8']   = "Isotope Layout 8";
			$layouts['isotope9']   = "Isotope Layout 9";
			$layouts['isotope10']  = "Isotope Layout 10";
			$layouts['isotope11']  = "Isotope Layout 11";
			$layouts['isotope12']  = "Isotope Layout 12";
			$layouts['carousel1']  = "Carousel Layout 1";
			$layouts['carousel2']  = "Carousel Layout 2";
			$layouts['carousel3']  = "Carousel Layout 3";
			$layouts['carousel4']  = "Carousel Layout 4";
			$layouts['carousel5']  = "Carousel Layout 5";
			$layouts['carousel6']  = "Carousel Layout 6";
			$layouts['carousel7']  = "Carousel Layout 7";
			$layouts['carousel8']  = "Carousel Layout 8";
			$layouts['carousel9']  = "Carousel Layout 9";
			$layouts['carousel10'] = "Carousel Layout 10";
			$layouts['carousel11'] = "Carousel Layout 11";
			$layouts['carousel12'] = "Carousel Layout 12";

			if ( class_exists( 'WooCommerce' ) ) {
				$layouts['wc1']          = "WooCommerce Layout 1";
				$layouts['wc2']          = "WooCommerce Layout 2";
				$layouts['wc3']          = "WooCommerce Layout 3";
				$layouts['wc4']          = "WooCommerce Layout 4";
				$layouts['wc-carousel1'] = "WooCommerce Carousel Layout 1";
				$layouts['wc-carousel2'] = "WooCommerce Carousel Layout 2";
				$layouts['wc-isotope1']  = "WooCommerce Isotope Layout 1";
				$layouts['wc-isotope2']  = "WooCommerce Isotope Layout 2";
			}

			return $layouts;
		}

		function rtTPGItemFields() {
			global $rtTPG;
			$items = array(
				'title'         => "Title",
				'excerpt'       => "Excerpt",
				'read_more'     => "Read More",
				'post_date'     => "Post Date",
				'author'        => "Author",
				'categories'    => "Categories",
				'tags'          => "Tags",
				'comment_count' => "Comment count",
				'social_share'  => "Social share"
			);
			if ( $cf = $rtTPG->checkWhichCustomMetaPluginIsInstalled() ) {
				$items['cf'] = "Custom Fields";
			}

			return $items;
		}

		function postLoadingType() {
			return array(
				'pagination'      => "Pagination",
				'pagination_ajax' => "Ajax Number Pagination ( Only for Grid )",
				'load_more'       => "Load more button (by ajax loading)",
				'load_on_scroll'  => "Load more on scroll (by ajax loading)",
			);
		}

		function scGridOpt() {
			return array(
				'even'    => "Even",
				'masonry' => "Masonry"
			);
		}

		function extraStyle() {
			return array(
				'title'       => "Title",
				'title_hover' => "Title hover",
				'excerpt'     => "Excerpt",
				'meta_data'   => "Meta Data"
			);
		}

		function scFontSize() {
			$num = array();
			for ( $i = 10; $i <= 50; $i ++ ) {
				$num[ $i ] = $i . "px";
			}

			return $num;
		}

		function scAlignment() {
			return array(
				'left'    => "Left",
				'right'   => "Right",
				'center'  => "Center",
				'justify' => "Justify"
			);
		}


		function scTextWeight() {
			return array(
				'normal'  => "Normal",
				'bold'    => "Bold",
				'bolder'  => "Bolder",
				'lighter' => "Lighter",
				'inherit' => "Inherit",
				'initial' => "Initial",
				'unset'   => "Unset",
				100       => '100',
				200       => '200',
				300       => '300',
				400       => '400',
				500       => '500',
				600       => '600',
				700       => '700',
				800       => '800',
				900       => '900',
			);
		}

		function imageCropType() {
			return array(
				'soft' => "Soft Crop",
				'hard' => "Hard Crop",
			);
		}

		function rt_filter_type() {
			return array(
				'dropdown' => "Dropdown",
				'button'   => "Button"
			);
		}


	}

endif;