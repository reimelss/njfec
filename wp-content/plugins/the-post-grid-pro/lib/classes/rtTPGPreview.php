<?php

if ( ! class_exists( 'rtTPGPreview' ) ):

	class rtTPGPreview {

		private $l4togglePreviewAjax = false;
		private $l4togglePreview = false;

		function __construct() {
			add_action( 'wp_ajax_tpgPreviewAjaxCall', array( $this, 'tpgPreviewAjaxCall' ) );
			add_action( 'wp_ajax_tpgLoadMorePreview', array( $this, 'tpgLoadMorePreview' ) );
		}

		function tpgLoadMorePreview() {
			global $rtTPG;
			$error = true;
			$msg   = $data = null;
			if ( $rtTPG->verifyNonce() ) {
				$scMeta = $_REQUEST;
				$layout = ( isset( $scMeta['layout'] ) ? $scMeta['layout'] : 'layout1' );
				if ( ! in_array( $layout, array_keys( $rtTPG->rtTPGLayouts() ) ) ) {
					$layout = 'layout1';
				}

				$isIsotope  = preg_match( '/isotope/', $layout );
				$isCarousel = preg_match( '/carousel/', $layout );
				$isGrid     = preg_match( '/layout/', $layout );
				$isWooCom   = preg_match( '/wc/', $layout );
				$isOffset   = preg_match( '/offset/', $layout );

				$dCol = ( isset( $scMeta['column'] ) ? absint( $scMeta['column'] ) : 3 );
				$tCol = ( isset( $scMeta['tpg_tab_column'] ) ? absint( $scMeta['tpg_tab_column'] ) : 2 );
				$mCol = ( isset( $scMeta['tpg_mobile_column'] ) ? absint( $scMeta['tpg_mobile_column'] ) : 1 );
				if ( ! in_array( $dCol, array_keys( $rtTPG->scColumns() ) ) ) {
					$dCol = 3;
				}
				if ( ! in_array( $tCol, array_keys( $rtTPG->scColumns() ) ) ) {
					$tCol = 2;
				}
				if ( ! in_array( $dCol, array_keys( $rtTPG->scColumns() ) ) ) {
					$mCol = 1;
				}

				if ( $isOffset ) {
					$dCol = ( $dCol < 3 ? 2 : $dCol );
					$tCol = ( $tCol < 3 ? 2 : $tCol );
					$mCol = ( $mCol < 3 ? 1 : $mCol );
				}
				$fImg              = ( ! empty( $scMeta['feature_image'] ) ? true : false );
				$fImgSize          = ( isset( $scMeta['featured_image_size'] ) ? $scMeta['featured_image_size'] : "medium" );
				$mediaSource       = ( isset( $scMeta['media_source'] ) ? $scMeta['media_source'] : "feature_image" );
				$excerpt_type      = ( isset( $scMeta['tgp_excerpt_type'] ) ? $scMeta['tgp_excerpt_type'] : 'character' );
				$excerpt_limit     = ( isset( $scMeta['excerpt_limit'] ) ? absint( $scMeta['excerpt_limit'] ) : 0 );
				$excerpt_more_text = ( isset( $scMeta['tgp_excerpt_more_text'] ) ? $scMeta['tgp_excerpt_more_text'] : null );
				$read_more_text = ( !empty( $scMeta['tgp_read_more_text'] ) ? $scMeta['tgp_read_more_text'] : __( 'Read More', 'the-post-grid-pro' ) );

				/* Argument create */
				$args     = array();
				$postType = ( isset( $scMeta['tpg_post_type'] ) ? $scMeta['tpg_post_type'] : null );

				if ( $postType ) {
					$args['post_type'] = $postType;
				}

				// Common filter
				/* post__in */
				$post__in = ( isset( $scMeta['post__in'] ) ? $scMeta['post__in'] : null );
				if ( $post__in ) {
					$post__in         = explode( ',', $post__in );
					$args['post__in'] = $post__in;
				}
				/* post__not_in */
				$post__not_in = ( isset( $scMeta['post__not_in'] ) ? $scMeta['post__not_in'] : null );
				if ( $post__not_in ) {
					$post__not_in         = explode( ',', $post__not_in );
					$args['post__not_in'] = $post__not_in;
				}

				/* LIMIT */
				$limit                  = ( ( empty( $scMeta['limit'] ) || $scMeta['limit'] === '-1' ) ? 10000000 : (int) $scMeta['limit'] );
				$args['posts_per_page'] = $limit;
				$pagination             = ( ! empty( $scMeta['pagination'] ) ? true : false );
				$posts_loading_type     = ( ! empty( $scMeta['posts_loading_type'] ) ? $scMeta['posts_loading_type'] : "pagination" );
				if ( $pagination ) {
					$posts_per_page = ( isset( $scMeta['posts_per_page'] ) ? intval( $scMeta['posts_per_page'] ) : $limit );
					if ( $posts_per_page > $limit ) {
						$posts_per_page = $limit;
					}
					// Set 'posts_per_page' parameter
					$args['posts_per_page'] = $posts_per_page;

					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

					$offset        = $posts_per_page * ( (int) $paged - 1 );
					$args['paged'] = $paged;

					// Update posts_per_page
					if ( intval( $args['posts_per_page'] ) > $limit - $offset ) {
						$args['posts_per_page'] = $limit - $offset;
					}

				}

				// Advance Filter
				$adv_filter     = ( isset( $scMeta['post_filter'] ) ? $scMeta['post_filter'] : array() );
				$taxFilter      = ( ! empty( $scMeta['tgp_filter_taxonomy'] ) ? $scMeta['tgp_filter_taxonomy'] : null );
				$taxFilterTerms = array();
				// Taxonomy
				$taxQ = array();
				if ( in_array( 'tpg_taxonomy', $adv_filter ) && isset( $scMeta['tpg_taxonomy'] ) ) {

					if ( is_array( $scMeta['tpg_taxonomy'] ) && ! empty( $scMeta['tpg_taxonomy'] ) ) {
						foreach ( $scMeta['tpg_taxonomy'] as $taxonomy ) {
							$terms = ( isset( $scMeta[ 'term_' . $taxonomy ] ) ? $scMeta[ 'term_' . $taxonomy ] : array() );
							if ( $taxonomy == $taxFilter ) {
								$taxFilterTerms = $terms;
							}
							if ( is_array( $terms ) && ! empty( $terms ) ) {
								$operator = ( isset( $scMeta[ 'term_operator_' . $taxonomy ] ) ? $scMeta[ 'term_operator_' . $taxonomy ] : "IN" );
								$taxQ[]   = array(
									'taxonomy' => $taxonomy,
									'field'    => 'term_id',
									'terms'    => $terms,
									'operator' => $operator,
								);
							}
						}
					}
					if ( count( $taxQ ) >= 2 ) {
						$relation         = ( isset( $scMeta['taxonomy_relation'] ) ? $scMeta['taxonomy_relation'] : "AND" );
						$taxQ['relation'] = $relation;
					}
				}

				if ( ! empty( $taxQ ) ) {
					$args['tax_query'] = $taxQ;
				}


				// Order
				if ( in_array( 'order', $adv_filter ) ) {
					$order_by = ( isset( $scMeta['order_by'] ) ? $scMeta['order_by'] : null );
					$order    = ( isset( $scMeta['order'] ) ? $scMeta['order'] : null );
					if ( $order ) {
						$args['order'] = $order;
					}
					if ( $order_by ) {
						$args['orderby'] = $order_by;
					}
				}
				if ( isset( $_REQUEST['orderby'] ) ) {
					$orderby = $_REQUEST['orderby'];
					switch ( $orderby ) {
						/*case 'popularity':
							$args['meta_key'] = 'total_sales';
							add_filter( 'posts_clauses', array( $this, 'order_by_popularity_post_clauses' ) );
							break;*/
						case 'menu_order':
							$args['orderby'] = 'menu_order title';
							$args['order']   = 'ASC';
							break;
						case 'date':
							$args['orderby'] = 'date';
							$args['order']   = 'DESC';
							break;
						case 'price':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_price';
							$args['order']    = 'ASC';
							break;
						case 'price-desc':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_price';
							$args['order']    = 'DESC';
							break;
						case 'rating' :
							// Sorting handled later though a hook
							add_filter( 'posts_clauses', array( $this, 'order_by_rating_post_clauses' ) );
							break;
						case 'title' :
							$args['orderby'] = 'title';
							$args['order']   = 'ASC';
							break;
					}
				}
				// Status
				if ( in_array( 'tpg_post_status', $adv_filter ) ) {
					$post_status = ( isset( $scMeta['tpg_post_status'] ) ? $scMeta['tpg_post_status'] : array() );
					if ( ! empty( $post_status ) ) {
						$args['post_status'] = $post_status;
					} else {
						$args['post_status'] = 'publish';
					}
				}
				// Author
				$author = ( isset( $scMeta['author'] ) ? $scMeta['author'] : array() );
				if ( in_array( 'author', $adv_filter ) && ! empty( $author ) ) {
					$args['author__in'] = $author;
				}
				// Search
				$s = ( isset( $scMeta['s'] ) ? $scMeta['s'] : array() );
				if ( in_array( 's', $adv_filter ) && ! empty( $s ) ) {
					$args['s'] = $s;
				}

				// Date query
				if ( in_array( 'date_range', $adv_filter ) ) {
					$startDate = ( ! empty( $scMeta['date_range_start'] ) ? $scMeta['date_range_start'] : null );
					$endDate   = ( ! empty( $scMeta['date_range_end'] ) ? $scMeta['date_range_end'] : null );
					if ( $startDate && $endDate ) {
						$args['date_query'] = array(
							array(
								'after'     => $startDate,
								'before'    => $endDate,
								'inclusive' => true,
							),
						);
					}
				}

				$settings       = get_option( $rtTPG->options['settings'] );
				$oLayoutTag    = ! empty( $settings['template_tag'] ) ? absint( $settings['template_tag'] ) : null;
				$oLayoutAuthor  = ! empty( $settings['template_author'] ) ? $settings['template_author'] : null;
				$oLayoutCategory = ! empty( $settings['template_category'] ) ? $settings['template_category'] : null;
				$oLayoutSearch   = ! empty( $settings['template_search'] ) ? $settings['template_search'] : null;
				$dataArchive    = null;
				if (  (is_category() && $oLayoutCategory) || (is_search() && $oLayoutSearch) || (is_tag() && $oLayoutTag) || (is_author() && $oLayoutAuthor) ) {

					unset( $args['post_type'] );
					unset( $args['tax_query'] );
					unset( $args['author__in'] );
					$obj   = get_queried_object();
					$aType = $aValue = null;
					if ( $oLayoutTag && is_tag() ) {
						if ( ! empty( $obj->slug ) ) {
							$aValue = $args['tag'] = $obj->slug;
							$aType  = 'tag';
						}
					} else if ( $oLayoutCategory && is_category() ) {
						if ( ! empty( $obj->slug ) ) {
							$aValue = $args['category_name'] = $obj->slug;
						}
						$aType = 'category';
					} else if ( $oLayoutAuthor && is_author() ) {
						$aValue = $args['author'] = $obj->ID;
						$aType  = 'author';
					} else if ( $oLayoutSearch && is_search() ) {
						$aValue = $args['s'] = get_search_query();
						$aType  = 'search';
					}
					$dataArchive                    = " data-archive='{$aType}' data-archive-value='{$aValue}'";
					$args['posts_per_archive_page'] = $args['posts_per_page'];
				}


				// Validation
				$containerDataAttr = null;
				$containerDataAttr .= " data-layout='{$layout}' data-desktop-col='{$dCol}'  data-tab-col='{$tCol}'  data-mobile-col='{$mCol}'";
				$dCol = $dCol == 5 ? '24' : round( 12 / $dCol );
				$tCol = $dCol == 5 ? '24' : round( 12 / $tCol );
				$mCol = $dCol == 5 ? '24' : round( 12 / $mCol );
				if ( $isCarousel ) {
					$dCol = $tCol = $mCol = 12;
				}
				$arg         = array();
				$arg['read_more_text'] = $read_more_text;
				$arg['grid'] = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";
				if ( ( $layout == 'layout2' ) || ( $layout == 'layout3' ) ) {
					if ( $dCol == 2 || $tCol == 2 || $mCol == 2 ) {
						$arg['image_area']   = "rt-col-lg-5 rt-col-md-5 rt-col-sm-6 rt-col-xs-12 ";
						$arg['content_area'] = "rt-col-lg-7 rt-col-md-7 rt-col-sm-6 rt-col-xs-12 ";
					} else {
						$arg['image_area']   = "rt-col-lg-4 rt-col-md-4 rt-col-sm-6 rt-col-xs-12 ";
						$arg['content_area'] = "rt-col-lg-8 rt-col-md-8 rt-col-sm-6 rt-col-xs-12 ";
					}
				}
				if ( $layout == 'layout4' ) {
					$arg['image_area']   = "rt-col-lg-6 rt-col-md-6 rt-col-sm-12 rt-col-xs-12 ";
					$arg['content_area'] = "rt-col-lg-6 rt-col-md-6 rt-col-sm-12 rt-col-xs-12 ";
				}
				$gridType     = ! empty( $scMeta['grid_style'] ) ? $scMeta['grid_style'] : 'even';
				$arg['class'] = null;
				if ( ! $isCarousel && ! $isOffset ) {
					$arg['class'] = $gridType . "-grid-item";
				}
				$arg['class'] .= " rt-grid-item";
				if ( $isOffset ) {
					$arg['class'] .= " rt-offset-item";
				}

				$masonryG = null;
				if ( $gridType == "even" ) {
					$masonryG = " tpg-even";
				} else if ( $gridType == "masonry" && ! $isIsotope && ! $isCarousel ) {
					$masonryG = " tpg-masonry";
				}
				$preLoader = $preLoaderHtml = null;
				if ( $isIsotope ) {
					$arg['class'] .= ' isotope-item';
					$preLoader = 'tpg-pre-loader';
				}
				if ( $isCarousel ) {
					$arg['class'] .= ' carousel-item';
					$preLoader = 'tpg-pre-loader';
				}
				if ( $preLoader ) {
					$preLoaderHtml = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>';
				}

				$margin = ! empty( $scMeta['margin_option'] ) ? $scMeta['margin_option'] : 'default';
				if ( $margin == 'no' ) {
					$arg['class'] .= ' no-margin';
				}
				if ( ! empty( $scMeta['tpg_image_type'] ) && $scMeta['tpg_image_type'] == 'circle' ) {
					$arg['class'] .= ' tpg-img-circle';
				}

				$arg['anchorClass'] = null;
				$link               = ! empty( $scMeta['link_to_detail_page'] ) ? $scMeta['link_to_detail_page'] : 'yes';
				if ( $link != 'yes' ) {
					$arg['anchorClass'] = ' disabled';
				}
				$isSinglePopUp = false;
				$linkType      = ! empty( $scMeta['detail_page_link_type'] ) ? $scMeta['detail_page_link_type'] : 'popup';
				if ( $link == 'yes' && $linkType == 'popup' ) {
					$popupType = ! empty( $scMeta['popup_type'] ) ? $scMeta['popup_type'] : 'single';
					if ( $popupType == 'single' ) {
						$arg['anchorClass'] .= ' tpg-single-popup';
						$isSinglePopUp = true;
					} else {
						$arg['anchorClass'] .= ' tpg-multi-popup';
					}
				}

				$parentClass   = ( ! empty( $scMeta['parent_class'] ) ? trim( $scMeta['parent_class'] ) : null );
				$defaultImgId  = ( ! empty( $scMeta['default_preview_image'] ) ? absint( $scMeta['default_preview_image'] ) : null );
				$customImgSize = ( ! empty( $scMeta['custom_image_size'] ) ? $scMeta['custom_image_size'] : array() );

				$arg['items'] = isset( $scMeta['item_fields'] ) ? ( $scMeta['item_fields'] ? $scMeta['item_fields'] : array() ) : array();

				if ( isset( $scMeta['ignore_sticky_posts'] ) ) {
					$args['ignore_sticky_posts'] = $scMeta['ignore_sticky_posts'];
				}

				$gridQuery = new WP_Query( $args );
				// Start layout
				$data .= $this->layoutStyle( $layoutID, $layout, $scMeta, $scID );
				$containerDataAttr .= " data-sc-id='{$scID}'";
				$data .= "<div class='rt-container-fluid rt-tpg-container {$parentClass}' id='{$layoutID}' {$dataArchive} {$containerDataAttr}>";
				$filters = ! empty( $scMeta['tgp_filter'] ) ? $scMeta['tgp_filter'] : array();
				if ( ! empty( $filters ) && ( $isGrid || $isOffset ) ) {
					$data .= "<div class='rt-layout-filter-container rt-clear'><div class='rt-filter-wrap'>";
					if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter ) {
						$filterType = ( ! empty( $scMeta['tgp_filter_type'] ) ? $scMeta['tgp_filter_type'] : null );
						$post_count = ( ! empty( $scMeta['tpg_post_count'] ) ? $scMeta['tpg_post_count'] : null );
						$terms      = $rtTPG->rt_get_all_term_by_taxonomy( $taxFilter, true );
						if ( ! $filterType || $filterType == 'dropdown' ) {
							$data .= '<div class="rt-filter-item-wrap rt-tax-filter rt-filter-dropdown-wrap" data-taxonomy="' . $taxFilter . '">';
							$data .= '<span class="term-default rt-filter-dropdown-default" data-term="all">
								                        <span class="rt-text">' . __( "All", "the-post-grid-pro" ) . '</span>
								                        <i class="fa fa-angle-down rt-arrow-angle" aria-hidden="true"></i>
								                    </span>';
							$data .= '<span class="term-dropdown rt-filter-dropdown">';
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$postCount = ( $post_count ? "<span class='rt-post-count'>({$term['count']})</span>" : null );
									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {
										if ( in_array( $id, $taxFilterTerms ) ) {
											$data .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term['name']}{$postCount}</span>";
										}
									} else {
										$data .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term['name']}{$postCount}</span>";
									}
								}
							}
							$data .= '</span>';
							$data .= '</div>';
						} else {
							$data .= '<div class="rt-filter-item-wrap rt-tax-filter rt-filter-button-wrap" data-taxonomy="' . $taxFilter . '">';
							$data .= "<span class='term-button-item rt-filter-button-item selected' data-term='all'>" . __( "All",
									"the-post-grid-pro" ) . "</span>";
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$postCount = ( $post_count ? "<span class='rt-post-count'>({$term['count']})</span>" : null );
									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {
										if ( in_array( $id, $taxFilterTerms ) ) {
											$data .= "<span class='term-button-item rt-filter-button-item' data-term='{$id}'>{$term['name']}{$postCount}</span>";
										}
									} else {
										$data .= "<span class='term-button-item rt-filter-button-item' data-term='{$id}'>{$term['name']}{$postCount}</span>";
									}
								}
							}
							$data .= "</div>";
						}
					}
					if ( in_array( '_sort_order', $filters ) ) {
						$action_order = ( ! empty( $args['order'] ) ? strtoupper( trim( $args['order'] ) ) : "DESC" );
						$data .= '<div class="rt-filter-item-wrap rt-sort-order-action">';
						$data .= "<span class='rt-sort-order-action-arrow' data-sort-order='{$action_order}'>&nbsp;<span></span></span>";
						$data .= '</div>';
					}
					if ( in_array( '_order_by', $filters ) ) {
						$orders               = $rtTPG->rtPostOrderBy();
						$action_orderby       = ( ! empty( $args['orderby'] ) ? trim( $args['orderby'] ) : "none" );
						$action_orderby_label = ( $action_orderby == 'none' ? __( "Sort By None",
							"the-post-grid-pro" ) : $orders[ $action_orderby ] );
						if ( $action_orderby !== 'none' ) {
							$orders['none'] = __( "Sort By None", "the-post-grid-pro" );
						}
						$data .= '<div class="rt-filter-item-wrap rt-order-by-action rt-filter-dropdown-wrap">';
						$data .= "<span class='order-by-default rt-filter-dropdown-default' data-order-by='{$action_orderby}'>
							                        <span class='rt-text-order-by'>{$action_orderby_label}</span>
							                        <i class='fa fa-angle-down rt-arrow-angle' aria-hidden='true'></i>
							                    </span>";
						$data .= '<span class="order-by-dropdown rt-filter-dropdown">';

						foreach ( $orders as $orderKey => $order ) {
							$data .= '<span class="order-by-dropdown-item rt-filter-dropdown-item" data-order-by="' . $orderKey . '">' . $order . '</span>';
						}
						$data .= '</span>';
						$data .= '</div>';
					}
					$data .= "</div></div>";
				}

				$data .= "<div data-title='" . __( "Loading ...",
						'the-post-grid-pro' ) . "' class='rt-row rt-content-loader {$layout}{$masonryG} {$preLoader}'>";
				if ( $gridQuery->have_posts() ) {

					if ( $isCarousel ) {
						$speed           = ! empty( $scMeta['tpg_carousel_speed'][0] ) ? absint( $scMeta['tpg_carousel_speed'][0] ) : 250;
						$autoPlatTimeOut = ! empty( $scMeta['tpg_carousel_autoplay_timeout'][0] ) ? absint( $scMeta['tpg_carousel_autoplay_timeout'][0] ) : 5000;
						$cOpt            = ! empty( $scMeta['carousel_property'] ) ? $scMeta['carousel_property'] : array();
						$autoPlay        = ( in_array( 'auto_play', $cOpt ) ? 'true' : 'false' );
						$stopOnHover     = ( in_array( 'stop_hover', $cOpt ) ? 'true' : 'false' );
						$navigation      = ( in_array( 'nav_button', $cOpt ) ? 'true' : 'false' );
						$dots            = ( in_array( 'pagination', $cOpt ) ? 'true' : 'false' );
						$loop            = ( in_array( 'loop', $cOpt ) ? 'true' : 'false' );
						$lazyLoad        = ( in_array( 'lazyLoad', $cOpt ) ? 'true' : 'false' );
						$autoHeight      = ( in_array( 'autoHeight', $cOpt ) ? 'true' : 'false' );
						$rtl             = ( in_array( 'rtl', $cOpt ) ? 'true' : 'false' );
						$data .= "<div class='rt-carousel-holder'  data-rtowl-options='{
																\"autoPlay\": {$autoPlay},
																\"stopOnHover\": {$stopOnHover},
																\"nav\":{$navigation},
																\"dots\":{$dots},
																\"loop\":{$loop},
																\"lazyLoad\":{$lazyLoad},
																\"autoHeight\":{$autoHeight},
																\"rtl\":{$rtl},
																\"autoPlayTimeOut\":{$autoPlatTimeOut},
																\"speed\":{$speed}
												}' >";
					}
					$isotope_filter = null;
					if ( $isIsotope ) {
						$isotope_filter          = isset( $scMeta['isotope_filter'] ) ? $scMeta['isotope_filter'] : null;
						$isotope_dropdown_filter = isset( $scMeta['isotope_filter_dropdown'] ) ? $scMeta['isotope_filter_dropdown'] : null;
						$selectedTerms           = array();
						if ( isset( $scMeta['post_filter'] ) && in_array( 'tpg_taxonomy',
								$scMeta['post_filter'] ) && isset( $scMeta['tpg_taxonomy'] ) && in_array( $isotope_filter,
								$scMeta['tpg_taxonomy'] )
						) {
							$selectedTerms = ( isset( $scMeta[ 'term_' . $isotope_filter ] ) ? $scMeta[ 'term_' . $isotope_filter ] : array() );
						}
						$terms = get_terms( $isotope_filter, array(
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => false,
							'include'    => $selectedTerms
						) );
						$data .= '<div class="tpg-iso-filter">';
						$htmlButton     = $drop = null;
						$fSelectTrigger = false;
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$tItem   = ! empty( $scMeta['isotope_default_filter'] ) ? $scMeta['isotope_default_filter'] : null;
								$fSelect = $fSelected = null;
								if ( $tItem == $term->term_id ) {
									$fSelect        = 'class="selected"';
									$fSelected      = 'selected';
									$fSelectTrigger = true;
								}
								$htmlButton .= "<button data-filter='.iso_{$term->term_id}' {$fSelect}>" . $term->name . "</button>";
								$drop .= "<option value='.iso_{$term->term_id}' {$fSelected}>{$term->name}</option>";
							}
						}
						if ( empty( $scMeta['isotope_filter_show_all'] ) ) {
							$fSelect    = ( $fSelectTrigger ? null : 'class="selected"' );
							$htmlButton = "<button data-filter='*' {$fSelect}>" . __( 'Show all',
									'the-post-grid-pro' ) . "</button>" . $htmlButton;
							$drop       = "<option value='*' {$fSelect}>" . __( 'Show all',
									'the-post-grid-pro' ) . "</option>" . $drop;
						}
						$filter_count = ! empty( $scMeta['isotope_filter_count'] ) ? true : false;
						$filter_url   = ! empty( $scMeta['isotope_filter_url'] ) ? true : false;
						$htmlButton   = "<div id='iso-button-{$rand}' class='rt-tpg-isotope-buttons button-group filter-button-group option-set' data-url='{$filter_url}' data-count='{$filter_count}'>{$htmlButton}</div>";

						if ( $isotope_dropdown_filter ) {
							$data .= "<select class='isotope-dropdown-filter'>{$drop}</select>";
						} else {
							$data .= $htmlButton;
						}
						if ( ! empty( $scMeta['isotope_search_filter'] ) ) {
							$data .= "<div class='iso-search'><input type='text' class='iso-search-input' placeholder='" . __( 'Search',
									'the-post-grid-pro' ) . "' /></div>";
						}
						$data .= '</div>';

						$data .= "<div class='rt-tpg-isotope' id='iso-tpg-{$rand}'>";
					}

					$l             = $offLoop = 0;
					$offsetBigHtml = $offsetSmallHtml = null;

					while ( $gridQuery->have_posts() ) : $gridQuery->the_post();
						if ( $dCol == $l ) {
							if ( $this->l4toggle ) {
								$this->l4toggle = false;
							} else {
								$this->l4toggle = true;
							}
							$l = 0;
						}
						$pID               = get_the_ID();
						$arg['pID']        = $pID;
						$arg['title']      = get_the_title();
						$arg['pLink']      = get_permalink();
						$arg['toggle']     = $this->l4toggle;
						$arg['author']     = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a>';
						$cc                = wp_count_comments( $pID );
						$arg['date']       = get_the_date();
						$arg['excerpt']  = get_the_excerpt();
						$arg['excerpt'] = $rtTPG->strip_tags_content( $arg['excerpt'], $excerpt_type, $excerpt_limit,
							$excerpt_more_text );
						$arg['categories'] = get_the_term_list( $pID, 'category', null, ', ' );
						$arg['tags']       = get_the_term_list( $pID, 'post_tag', null, ', ' );
						if ( $isIsotope ) {
							$termAs    = wp_get_post_terms( $pID, $isotope_filter, array( "fields" => "all" ) );
							$isoFilter = null;
							if ( ! empty( $termAs ) ) {
								foreach ( $termAs as $term ) {
									$isoFilter .= " " . "iso_" . $term->term_id;
								}
							}
							$arg['isoFilter'] = $isoFilter;
						}
						$deptClass = null;
						if ( ! empty( $deptAs ) ) {
							foreach ( $deptAs as $dept ) {
								$deptClass .= " " . $dept->slug;
							}
						}
						if ( comments_open() ) {
							$arg['comment'] = "<a href='" . get_comments_link( $pID ) . "'>{$cc->total_comments} </a>";
						} else {
							$arg['comment'] = "{$cc->total_comments}";
						}
						$imgSrc = null;

						if ( $isOffset ) {
							if ( $offLoop == 0 ) {
								$arg['imgSrc'] = !$fImg ? $rtTPG->getFeatureImageSrc( $pID, $fImgSize, $mediaSource,
									$defaultImgId,
									$customImgSize ) : null;
								$arg['offset'] = 'big';
								$offsetBigHtml = $rtTPG->render( 'layouts/' . $layout, $arg, true );
							} else {
								$arg['offset']    = 'small';
								$arg['offsetCol'] = array( $dCol, $tCol, $mCol );
								$arg['imgSrc']    = !$fImg ? $rtTPG->getFeatureImageSrc( $pID, 'thumbnail', $mediaSource,
									$defaultImgId,
									$customImgSize ) : null;
								$offsetSmallHtml .= $rtTPG->render( 'layouts/' . $layout, $arg, true );
							}
						} else {
							$arg['imgSrc'] = !$fImg ? $rtTPG->getFeatureImageSrc( $pID, $fImgSize, $mediaSource, $defaultImgId,
								$customImgSize ) : null;
							$data .= $rtTPG->render( 'layouts/' . $layout, $arg, true );
						}
						$offLoop ++;
						$l ++;
					endwhile;
					if ( $isOffset ) {
						$oDCol = $rtTPG->get_offset_col( $dCol );
						$oTCol = $rtTPG->get_offset_col( $tCol );
						$oMCol = $rtTPG->get_offset_col( $mCol );
						$data .= "<div class='rt-col-md-{$oDCol['big']} rt-col-sm-{$oTCol['big']} rt-col-xs-{$oMCol['big']}'><div class='rt-row'>{$offsetBigHtml}</div></div>";
						$data .= "<div class='rt-col-md-{$oDCol['small']} rt-col-sm-{$oTCol['small']} rt-col-xs-{$oMCol['small']}'><div class='rt-row offset-small-wrap'>{$offsetSmallHtml}</div></div>";
					}
					if ( $isIsotope || $isCarousel ) {
						$data .= '</div>'; // End isotope / Carousel item holder
					}

				} else {
					$data .= "<p>" . __( 'No post found', 'the-post-grid-pro' ) . "</p>";
				}
				$data .= $preLoaderHtml;
				$data .= "</div>"; // End row
				$htmlUtility = null;
				if ( $pagination && ! $isCarousel ) {
					if ( $isOffset ) {
						$posts_loading_type = "page_prev_next";
						$htmlUtility .= "<div class='rt-cb-page-prev-next'>
											<span class='rt-cb-prev-btn'><i class='fa fa-angle-left' aria-hidden='true'></i></span>
											<span class='rt-cb-next-btn'><i class='fa fa-angle-right' aria-hidden='true'></i></span>
										</div>";
					} else {
						if ( $posts_loading_type == "pagination" ) {
							if ( $isGrid && empty( $filters ) ) {
								$htmlUtility .= $rtTPG->rt_pagination( $gridQuery->max_num_pages,
									$args['posts_per_page'] );
							}
						} elseif ( $posts_loading_type == "pagination_ajax" && ! $isIsotope ) {
							if ( $isGrid ) {
								$htmlUtility .= "<div class='rt-page-numbers'></div>";
							} else {
								$htmlUtility .= $rtTPG->rt_pagination( $gridQuery->max_num_pages,
									$args['posts_per_page'], true, $scID );
							}
						} elseif ( $posts_loading_type == "load_more" ) {
							if ( $isGrid ) {
								$htmlUtility .= "<div class='rt-loadmore-btn rt-loadmore-action rt-loadmore-style'>
											<span class='rt-loadmore-text'>" . __( 'Load More', 'the-post-grid-pro' ) . "</span>
											<div class='rt-loadmore-loading rt-ball-scale-multiple rt-2x'><div></div><div></div><div></div></div>
										</div>";
							} else {
								$htmlUtility .= "<div class='rt-tpg-load-more'>
                                        <button data-sc-id='{$scID}' data-paged='2'>" . __( 'Load More',
										'the-post-grid-pro' ) . "</button>
                                    </div>";
							}

						} elseif ( $posts_loading_type == "load_on_scroll" ) {
							if ( $isGrid ) {
								$htmlUtility .= "<div class='rt-infinite-action'>	
													<div class='rt-infinite-loading la-fire la-2x'>
														<div></div>
														<div></div>
														<div></div>
													</div>
												</div>";
							} else {
								$htmlUtility .= "<div class='rt-tpg-scroll-load-more' data-trigger='1' data-sc-id='{$scID}' data-paged='2'></div>";
							}
						}
					}
				}

				if ( $htmlUtility ) {
					$l4toggle = null;
					if ( $layout == "layout4" ) {
						$l4toggle = "data-l4toggle='{$this->l4toggle}'";
					}
					if ( $isGrid || $isOffset ) {
						$data .= "<div class='rt-pagination-wrap' data-total-pages='{$gridQuery->max_num_pages}' data-posts-per-page='{$args['posts_per_page']}' data-type='{$posts_loading_type}' {$l4toggle} >" . $htmlUtility . "</div>";
					} else {
						$data .= "<div class='rt-tpg-utility' {$l4toggle}>" . $htmlUtility . "</div>";
					}
				}

				$data .= "</div>"; // container rt-tpg

				wp_reset_postdata();
			} else {
				$msg = __( 'Security error', 'the-post-grid-pro' );
			}
			wp_send_json( array(
				'error'    => $error,
				'msg'      => $msg,
				'data'     => $data,
				'l4toggle' => ( $this->l4togglePreview ? 1 : null )
			) );
			die();
		}

		/**
		 * Preview rendering
		 *
		 */
		function tpgPreviewAjaxCall() {
			global $rtTPG;
			$msg   = $data = null;
			$error = true;
			if ( $rtTPG->verifyNonce() ) {
				$error    = false;
				$scMeta   = $_REQUEST;
				$rand     = mt_rand();
				$layoutID = "rt-tpg-container-" . $rand;

				$layout = ( isset( $scMeta['layout'] ) ? $scMeta['layout'] : 'layout1' );
				if ( ! in_array( $layout, array_keys( $rtTPG->rtTPGLayouts() ) ) ) {
					$layout = 'layout1';
				}

				$isIsotope  = preg_match( '/isotope/', $layout );
				$isCarousel = preg_match( '/carousel/', $layout );
				$isGrid     = preg_match( '/layout/', $layout );
				$isWooCom   = preg_match( '/wc/', $layout );
				$isOffset   = preg_match( '/offset/', $layout );

				$dCol = ( isset( $scMeta['column'] ) ? absint( $scMeta['column'] ) : 3 );
				$tCol = ( isset( $scMeta['tpg_tab_column'] ) ? absint( $scMeta['tpg_tab_column'] ) : 2 );
				$mCol = ( isset( $scMeta['tpg_mobile_column'] ) ? absint( $scMeta['tpg_mobile_column'] ) : 1 );
				if ( ! in_array( $dCol, array_keys( $rtTPG->scColumns() ) ) ) {
					$dCol = 3;
				}
				if ( ! in_array( $tCol, array_keys( $rtTPG->scColumns() ) ) ) {
					$tCol = 2;
				}
				if ( ! in_array( $dCol, array_keys( $rtTPG->scColumns() ) ) ) {
					$mCol = 1;
				}

				if ( $isOffset ) {
					$dCol = ( $dCol < 3 ? 2 : $dCol );
					$tCol = ( $tCol < 3 ? 2 : $tCol );
					$mCol = ( $mCol < 3 ? 1 : $mCol );
				}
				$fImg              = ( ! empty( $scMeta['feature_image'] ) ? true : false );
				$fImgSize          = ( isset( $scMeta['featured_image_size'] ) ? $scMeta['featured_image_size'] : "medium" );
				$mediaSource       = ( isset( $scMeta['media_source'] ) ? $scMeta['media_source'] : "feature_image" );
				$excerpt_type      = ( isset( $scMeta['tgp_excerpt_type'] ) ? absint( $scMeta['tgp_excerpt_type'] ) : 'character' );
				$excerpt_limit     = ( isset( $scMeta['excerpt_limit'] ) ? absint( $scMeta['excerpt_limit'] ) : 0 );
				$excerpt_more_text = ( isset( $scMeta['tgp_excerpt_more_text'] ) ? $scMeta['tgp_excerpt_more_text'] : null );


				/* Argument create */
				$args     = array();
				$postType = ( isset( $scMeta['tpg_post_type'] ) ? $scMeta['tpg_post_type'] : null );

				if ( $postType ) {
					$args['post_type'] = $postType;
				}

				// Common filter
				/* post__in */
				$post__in = ( isset( $scMeta['post__in'] ) ? $scMeta['post__in'] : null );
				if ( $post__in ) {
					$post__in         = explode( ',', $post__in );
					$args['post__in'] = $post__in;
				}
				/* post__not_in */
				$post__not_in = ( isset( $scMeta['post__not_in'] ) ? $scMeta['post__not_in'] : null );
				if ( $post__not_in ) {
					$post__not_in         = explode( ',', $post__not_in );
					$args['post__not_in'] = $post__not_in;
				}

				/* LIMIT */
				$limit                  = ( ( empty( $scMeta['limit'] ) || $scMeta['limit'] === '-1' ) ? 10000000 : (int) $scMeta['limit'] );
				$args['posts_per_page'] = $limit;
				$pagination             = ( ! empty( $scMeta['pagination'] ) ? true : false );
				$posts_loading_type     = ( ! empty( $scMeta['posts_loading_type'] ) ? $scMeta['posts_loading_type'] : "pagination" );
				if ( $pagination ) {
					$posts_per_page = ( isset( $scMeta['posts_per_page'] ) ? intval( $scMeta['posts_per_page'] ) : $limit );
					if ( $posts_per_page > $limit ) {
						$posts_per_page = $limit;
					}
					// Set 'posts_per_page' parameter
					$args['posts_per_page'] = $posts_per_page;

					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

					$offset        = $posts_per_page * ( (int) $paged - 1 );
					$args['paged'] = $paged;

					// Update posts_per_page
					if ( intval( $args['posts_per_page'] ) > $limit - $offset ) {
						$args['posts_per_page'] = $limit - $offset;
					}

				}

				// Advance Filter
				$adv_filter     = ( isset( $scMeta['post_filter'] ) ? $scMeta['post_filter'] : array() );
				$taxFilter      = ( ! empty( $scMeta['tgp_filter_taxonomy'] ) ? $scMeta['tgp_filter_taxonomy'] : null );
				$taxFilterTerms = array();
				// Taxonomy
				$taxQ = array();
				if ( in_array( 'tpg_taxonomy', $adv_filter ) && isset( $scMeta['tpg_taxonomy'] ) ) {

					if ( is_array( $scMeta['tpg_taxonomy'] ) && ! empty( $scMeta['tpg_taxonomy'] ) ) {
						foreach ( $scMeta['tpg_taxonomy'] as $taxonomy ) {
							$terms = ( isset( $scMeta[ 'term_' . $taxonomy ] ) ? $scMeta[ 'term_' . $taxonomy ] : array() );
							if ( $taxonomy == $taxFilter ) {
								$taxFilterTerms = $terms;
							}
							if ( is_array( $terms ) && ! empty( $terms ) ) {
								$operator = ( isset( $scMeta[ 'term_operator_' . $taxonomy ] ) ? $scMeta[ 'term_operator_' . $taxonomy ] : "IN" );
								$taxQ[]   = array(
									'taxonomy' => $taxonomy,
									'field'    => 'term_id',
									'terms'    => $terms,
									'operator' => $operator,
								);
							}
						}
					}
					if ( count( $taxQ ) >= 2 ) {
						$relation         = ( isset( $scMeta['taxonomy_relation'] ) ? $scMeta['taxonomy_relation'] : "AND" );
						$taxQ['relation'] = $relation;
					}
				}

				if ( ! empty( $taxQ ) ) {
					$args['tax_query'] = $taxQ;
				}


				// Order
				if ( in_array( 'order', $adv_filter ) ) {
					$order_by = ( isset( $scMeta['order_by'] ) ? $scMeta['order_by'] : null );
					$order    = ( isset( $scMeta['order'] ) ? $scMeta['order'] : null );
					if ( $order ) {
						$args['order'] = $order;
					}
					if ( $order_by ) {
						$args['orderby'] = $order_by;
						$meta_key        = ! empty( $scMeta['tpg_meta_key'] ) ? $scMeta['tpg_meta_key'] : null;
						if ( in_array( $order_by, array_keys( $rtTPG->rtMetaKeyType() ) ) && $meta_key ) {
							$args['orderby']  = $order_by;
							$args['meta_key'] = $meta_key;
						}
					}
				}
				if ( isset( $_REQUEST['orderby'] ) ) {
					$orderby = $_REQUEST['orderby'];
					switch ( $orderby ) {
						/*case 'popularity':
							$args['meta_key'] = 'total_sales';
							add_filter( 'posts_clauses', array( $this, 'order_by_popularity_post_clauses' ) );
							break;*/
						case 'menu_order':
							$args['orderby'] = 'menu_order title';
							$args['order']   = 'ASC';
							break;
						case 'date':
							$args['orderby'] = 'date';
							$args['order']   = 'DESC';
							break;
						case 'price':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_price';
							$args['order']    = 'ASC';
							break;
						case 'price-desc':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_price';
							$args['order']    = 'DESC';
							break;
						case 'rating' :
							// Sorting handled later though a hook
							add_filter( 'posts_clauses', array( $this, 'order_by_rating_post_clauses' ) );
							break;
						case 'title' :
							$args['orderby'] = 'title';
							$args['order']   = 'ASC';
							break;
					}
				}
				// Status
				if ( in_array( 'tpg_post_status', $adv_filter ) ) {
					$post_status = ( isset( $scMeta['tpg_post_status'] ) ? $scMeta['tpg_post_status'] : array() );
					if ( ! empty( $post_status ) ) {
						$args['post_status'] = $post_status;
					} else {
						$args['post_status'] = 'publish';
					}
				}
				// Author
				$author = ( isset( $scMeta['author'] ) ? $scMeta['author'] : array() );
				if ( in_array( 'author', $adv_filter ) && ! empty( $author ) ) {
					$args['author__in'] = $author;
				}
				// Search
				$s = ( isset( $scMeta['s'] ) ? $scMeta['s'] : array() );
				if ( in_array( 's', $adv_filter ) && ! empty( $s ) ) {
					$args['s'] = $s;
				}

				// Date query
				if ( in_array( 'date_range', $adv_filter ) ) {
					$startDate = ( ! empty( $scMeta['date_range_start'] ) ? $scMeta['date_range_start'] : null );
					$endDate   = ( ! empty( $scMeta['date_range_end'] ) ? $scMeta['date_range_end'] : null );
					if ( $startDate && $endDate ) {
						$args['date_query'] = array(
							array(
								'after'     => $startDate,
								'before'    => $endDate,
								'inclusive' => true,
							),
						);
					}
				}

				$settings       = get_option( $rtTPG->options['settings'] );
				$override_items = ! empty( $settings['template_override_items'] ) ? $settings['template_override_items'] : array();
				$dataArchive    = null;
				if ( ( is_archive() || is_search() || is_tag() || is_author() ) && ! empty( $override_items ) ) {

					unset( $args['post_type'] );
					unset( $args['tax_query'] );
					unset( $args['author__in'] );
					$obj   = get_queried_object();
					$aType = $aValue = null;
					if ( in_array( 'tag-archive', $override_items ) && is_tag() ) {
						if ( ! empty( $obj->slug ) ) {
							$aValue = $args['tag'] = $obj->slug;
							$aType  = 'tag';
						}
					} else if ( in_array( 'category-archive', $override_items ) && is_category() ) {
						if ( ! empty( $obj->slug ) ) {
							$aValue = $args['category_name'] = $obj->slug;
						}
						$aType = 'category';
					} else if ( in_array( 'author-archive', $override_items ) && is_author() ) {
						$aValue = $args['author'] = $obj->ID;
						$aType  = 'author';
					} else if ( in_array( 'search', $override_items ) && is_search() ) {
						$aValue = $args['s'] = get_search_query();
						$aType  = 'search';
					}
					$dataArchive                    = " data-archive='{$aType}' data-archive-value='{$aValue}'";
					$args['posts_per_archive_page'] = $args['posts_per_page'];
				}


				// Validation
				$containerDataAttr = null;
				$containerDataAttr .= " data-layout='{$layout}' data-desktop-col='{$dCol}'  data-tab-col='{$tCol}'  data-mobile-col='{$mCol}'";
				$dCol = $dCol == 5 ? '24' : round( 12 / $dCol );
				$tCol = $dCol == 5 ? '24' : round( 12 / $tCol );
				$mCol = $dCol == 5 ? '24' : round( 12 / $mCol );
				if ( $isCarousel ) {
					$dCol = $tCol = $mCol = 12;
				}
				$arg         = array();
				$arg['grid'] = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";
				if ( ( $layout == 'layout2' ) || ( $layout == 'layout3' ) ) {
					$iCol                = ( isset( $scMeta['tgp_layout2_image_column'][0] ) ? absint( $scMeta['tgp_layout2_image_column'][0] ) : 4 );
					$iCol                = $iCol > 12 ? 4 : $iCol;
					$cCol                = 12 - $iCol;
					$arg['image_area']   = "rt-col-sm-{$iCol} rt-col-xs-12 ";
					$arg['content_area'] = "rt-col-sm-{$cCol} rt-col-xs-12 ";
				}
				if ( $layout == 'layout4' ) {
					$arg['image_area']   = "rt-col-lg-6 rt-col-md-6 rt-col-sm-12 rt-col-xs-12 ";
					$arg['content_area'] = "rt-col-lg-6 rt-col-md-6 rt-col-sm-12 rt-col-xs-12 ";
				}
				$gridType     = ! empty( $scMeta['grid_style'] ) ? $scMeta['grid_style'] : 'even';
				$arg['class'] = null;
				if ( ! $isCarousel && ! $isOffset ) {
					$arg['class'] = $gridType . "-grid-item";
				}
				$arg['class'] .= " rt-grid-item";
				if ( $isOffset ) {
					$arg['class'] .= " rt-offset-item";
				}

				$masonryG = null;
				if ( $gridType == "even" ) {
					$masonryG = " tpg-even";
				} else if ( $gridType == "masonry" && ! $isIsotope && ! $isCarousel ) {
					$masonryG = " tpg-masonry";
				}
				$preLoader = $preLoaderHtml = null;
				if ( $isIsotope ) {
					$arg['class'] .= ' isotope-item';
					$preLoader = 'tpg-pre-loader';
				}
				if ( $isCarousel ) {
					$arg['class'] .= ' carousel-item';
					$preLoader = 'tpg-pre-loader';
				}
				if ( $preLoader ) {
					$preLoaderHtml = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>';
				}

				$margin = ! empty( $scMeta['margin_option'] ) ? $scMeta['margin_option'] : 'default';
				if ( $margin == 'no' ) {
					$arg['class'] .= ' no-margin';
				}
				if ( ! empty( $scMeta['tpg_image_type'] ) && $scMeta['tpg_image_type'] == 'circle' ) {
					$arg['class'] .= ' tpg-img-circle';
				}

				$arg['anchorClass'] = null;
				$link               = ! empty( $scMeta['link_to_detail_page'] ) ? $scMeta['link_to_detail_page'] : 'yes';
				if ( $link != 'yes' ) {
					$arg['anchorClass'] = ' disabled';
				}
				$isSinglePopUp = false;
				$linkType      = ! empty( $scMeta['detail_page_link_type'] ) ? $scMeta['detail_page_link_type'] : 'popup';
				if ( $link == 'yes' && $linkType == 'popup' ) {
					$popupType = ! empty( $scMeta['popup_type'] ) ? $scMeta['popup_type'] : 'single';
					if ( $popupType == 'single' ) {
						$arg['anchorClass'] .= ' tpg-single-popup';
						$isSinglePopUp = true;
					} else {
						$arg['anchorClass'] .= ' tpg-multi-popup';
					}
				}

				$parentClass   = ( ! empty( $scMeta['parent_class'] ) ? trim( $scMeta['parent_class'] ) : null );
				$defaultImgId  = ( ! empty( $scMeta['default_preview_image'] ) ? absint( $scMeta['default_preview_image'] ) : null );
				$customImgSize = ( ! empty( $scMeta['custom_image_size'] ) ? $scMeta['custom_image_size'] : array() );

				$arg['items'] = isset( $scMeta['item_fields'] ) ? ( $scMeta['item_fields'] ? $scMeta['item_fields'] : array() ) : array();

				if ( isset( $scMeta['ignore_sticky_posts'] ) ) {
					$args['ignore_sticky_posts'] = $scMeta['ignore_sticky_posts'];
				}
				$filters     = ! empty( $scMeta['tgp_filter'] ) ? $scMeta['tgp_filter'] : array();
				$action_term = ! empty( $scMeta['tgp_default_filter'][0] ) ? absint( $scMeta['tgp_default_filter'][0] ) : 0;

				if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter && $action_term ) {

					$args['tax_query'] = array(
						array(
							'taxonomy' => $taxFilter,
							'field'    => 'term_id',
							'terms'    => array( $action_term ),
						)
					);
				}

				$gridQuery = new WP_Query( $args );
				// Start layout
				$data .= $rtTPG->layoutStyle( $layoutID, $scMeta, $layout );
				$containerDataAttr .= "";
				$data .= "<div class='rt-container-fluid rt-tpg-container {$parentClass}' id='{$layoutID}' {$dataArchive} {$containerDataAttr}>";
				$filters = ! empty( $scMeta['tgp_filter'] ) ? $scMeta['tgp_filter'] : array();
				if ( ! empty( $filters ) && ( $isGrid || $isOffset || $isWooCom ) ) {
					$data .= "<div class='rt-layout-filter-container rt-clear'><div class='rt-filter-wrap'>";
					if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter ) {
						$filterType     = ( ! empty( $scMeta['tgp_filter_type'] ) ? $scMeta['tgp_filter_type'] : null );
						$post_count     = ( ! empty( $scMeta['tpg_post_count'] ) ? $scMeta['tpg_post_count'] : null );
						$terms          = $rtTPG->rt_get_all_term_by_taxonomy( $taxFilter, true );
						$allSelect      = " selected";
						$isTermSelected = false;
						if ( $action_term && $taxFilter ) {
							$isTermSelected = true;
							$allSelect      = null;
						}
						$hide_all_button = ( ! empty( $scMeta['tpg_hide_all_button'][0] ) ? false : true );
						if ( ! $filterType || $filterType == 'dropdown' ) {
							$data .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-dropdown-wrap{$postCountClass}' data-taxonomy='{$taxFilter}'>";
							$termDefaultText = __( "All", "the-post-grid-pro" );
							$dataTerm        = 'all';
							$htmlButton      = "";
							$htmlButton .= '<span class="term-dropdown rt-filter-dropdown">';
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$postCount = ( $post_count ? " (<span class='rt-post-count'>{$term['count']}</span>)" : null );
									if ( $action_term == $id ) {
										$termDefaultText = $term['name'] . $postCount;
										$dataTerm        = $id;
									}
									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {

										if ( in_array( $id, $taxFilterTerms ) ) {
											if ( $action_term == $id ) {
												$termDefaultText = $term['name'] . $postCount;
												$dataTerm        = $id;
											} else {
												$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term['name']}{$postCount}</span>";
											}
										}
									} else {
										if ( $action_term == $id ) {
											$termDefaultText = $term['name'] . $postCount;
											$dataTerm        = $id;
										} else {
											$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term['name']}{$postCount}</span>";
										}
									}
								}
							}
							if ( $isTermSelected ) {
								$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='all'>" . __( "All",
										"the-post-grid-pro" ) . "</span>";
							}
							$htmlButton .= '</span>';

							$showAllhtml = '<span class="term-default rt-filter-dropdown-default" data-term="' . $dataTerm . '">
								                        <span class="rt-text">' . $termDefaultText . '</span>
								                        <i class="fa fa-angle-down rt-arrow-angle" aria-hidden="true"></i>
								                    </span>';

							$data .= $showAllhtml . $htmlButton;
							$data .= '</div>';
						} else {
							$data .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-button-wrap{$postCountClass}' data-taxonomy='{$taxFilter}'>";
							if ( $hide_all_button ) {
								$data .= "<span class='term-button-item rt-filter-button-item {$allSelect}' data-term='all'>" . __( "All",
										"the-post-grid-pro" ) . "</span>";
							}
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$postCount    = ( $post_count ? " (<span class='rt-post-count'>{$term['count']}</span>)" : null );
									$termSelected = null;
									if ( $isTermSelected && $id == $action_term ) {
										$termSelected = " selected";
									}
									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {
										if ( in_array( $id, $taxFilterTerms ) ) {
											$data .= "<span class='term-button-item rt-filter-button-item {$termSelected}' data-term='{$id}'>{$term['name']}{$postCount}</span>";
										}
									} else {
										$data .= "<span class='term-button-item rt-filter-button-item {$termSelected}' data-term='{$id}'>{$term['name']}{$postCount}</span>";
									}
								}
							}
							$data .= "</div>";
						}
					}
					if ( in_array( '_sort_order', $filters ) ) {
						$action_order = ( ! empty( $args['order'] ) ? strtoupper( trim( $args['order'] ) ) : "DESC" );
						$data .= '<div class="rt-filter-item-wrap rt-sort-order-action">';
						$data .= "<span class='rt-sort-order-action-arrow' data-sort-order='{$action_order}'>&nbsp;<span></span></span>";
						$data .= '</div>';
					}
					if ( in_array( '_order_by', $filters ) ) {
						$wooFeature     = ( $postType == "product" ? true : false );
						$orders         = $rtTPG->rtPostOrderBy( $wooFeature );
						$action_orderby = ( ! empty( $args['orderby'] ) ? trim( $args['orderby'] ) : "none" );
						if ( $action_orderby == 'none' ) {
							$action_orderby_label = __( "Sort By None", "the-post-grid-pro" );
						} elseif ( in_array( $action_orderby, array_keys( $rtTPG->rtMetaKeyType() ) ) ) {
							$action_orderby_label = __( "Meta value", "the-post-grid-pro" );
						} else {
							$action_orderby_label = $orders[ $action_orderby ];
						}
						if ( $action_orderby !== 'none' ) {
							$orders['none'] = __( "Sort By None", "the-post-grid-pro" );
						}
						$data .= '<div class="rt-filter-item-wrap rt-order-by-action rt-filter-dropdown-wrap">';
						$data .= "<span class='order-by-default rt-filter-dropdown-default' data-order-by='{$action_orderby}'>
							                        <span class='rt-text-order-by'>{$action_orderby_label}</span>
							                        <i class='fa fa-angle-down rt-arrow-angle' aria-hidden='true'></i>
							                    </span>";
						$data .= '<span class="order-by-dropdown rt-filter-dropdown">';

						foreach ( $orders as $orderKey => $order ) {
							$data .= '<span class="order-by-dropdown-item rt-filter-dropdown-item" data-order-by="' . $orderKey . '">' . $order . '</span>';
						}
						$data .= '</span>';
						$data .= '</div>';
					}
					if ( in_array( '_search', $filters ) ) {
						$data .= '<div class="rt-filter-item-wrap rt-search-filter-wrap">';
						$data .= "<input type='text' class='rt-search-input' placeholder='Search...'>";
						$data .= "<span class='rt-action'>&#128269;</span>";
						$data .= "<span class='rt-loading'></span>";
						$data .= '</div>';
					}
					$data .= "</div></div>";
				}

				$data .= "<div data-title='" . __( "Loading ...",
						'the-post-grid-pro' ) . "' class='rt-row rt-content-loader {$layout}{$masonryG} {$preLoader}'>";
				if ( $gridQuery->have_posts() ) {

					if ( $isCarousel ) {
						$speed           = ! empty( $scMeta['tpg_carousel_speed'][0] ) ? absint( $scMeta['tpg_carousel_speed'][0] ) : 250;
						$autoPlatTimeOut = ! empty( $scMeta['tpg_carousel_autoplay_timeout'][0] ) ? absint( $scMeta['tpg_carousel_autoplay_timeout'][0] ) : 5000;
						$cOpt        = ! empty( $scMeta['carousel_property'] ) ? $scMeta['carousel_property'] : array();
						$autoPlay    = ( in_array( 'auto_play', $cOpt ) ? 'true' : 'false' );
						$stopOnHover = ( in_array( 'stop_hover', $cOpt ) ? 'true' : 'false' );
						$navigation  = ( in_array( 'nav_button', $cOpt ) ? 'true' : 'false' );
						$dots = ( in_array( 'pagination', $cOpt ) ? 'true' : 'false' );
						$loop        = ( in_array( 'loop', $cOpt ) ? 'true' : 'false' );
						$lazyLoad    = ( in_array( 'lazyLoad', $cOpt ) ? 'true' : 'false' );
						$autoHeight  = ( in_array( 'autoHeight', $cOpt ) ? 'true' : 'false' );
						$rtl         = ( in_array( 'rtl', $cOpt ) ? 'true' : 'false' );
						$data .= "<div class='rt-carousel-holder'  data-rtowl-options='{
																\"autoPlay\": {$autoPlay},
																\"stopOnHover\": {$stopOnHover},
																\"nav\":{$navigation},
																\"dots\":{$dots},
																\"loop\":{$loop},
																\"lazyLoad\":{$lazyLoad},
																\"autoHeight\":{$autoHeight},
																\"rtl\":{$rtl},
																\"autoPlayTimeOut\":{$autoPlatTimeOut},
																\"speed\":{$speed}
												}' >";
					}
					$isotope_filter = null;
					if ( $isIsotope ) {
						$isotope_filter          = isset( $scMeta['isotope_filter'] ) ? $scMeta['isotope_filter'] : null;
						$isotope_dropdown_filter = isset( $scMeta['isotope_filter_dropdown'] ) ? $scMeta['isotope_filter_dropdown'] : null;
						$selectedTerms           = array();
						if ( isset( $scMeta['post_filter'] ) && in_array( 'tpg_taxonomy',
								$scMeta['post_filter'] ) && isset( $scMeta['tpg_taxonomy'] ) && in_array( $isotope_filter,
								$scMeta['tpg_taxonomy'] )
						) {
							$selectedTerms = ( isset( $scMeta[ 'term_' . $isotope_filter ] ) ? $scMeta[ 'term_' . $isotope_filter ] : array() );
						}
						global $wp_version;
						if ( version_compare( $wp_version, '4.5', '>=' ) ) {
							$terms = get_terms( $isotope_filter, array(
								'meta_key'   => '_rt_order',
								'orderby'    => 'meta_value_num',
								'order'      => 'ASC',
								'hide_empty' => false,
								'include'    => $selectedTerms
							) );
						}else{
							$terms          = get_terms( $isotope_filter, array(
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => false,
								'include'    => $selectedTerms
							) );
						}
						$data .= '<div class="tpg-iso-filter">';
						$htmlButton     = $drop = null;
						$fSelectTrigger = false;
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$tItem   = ! empty( $scMeta['isotope_default_filter'] ) ? $scMeta['isotope_default_filter'] : null;
								$fSelect = $fSelected = null;
								if ( $tItem == $term->term_id ) {
									$fSelect        = 'class="selected"';
									$fSelected      = 'selected';
									$fSelectTrigger = true;
								}
								$htmlButton .= "<button data-filter='.iso_{$term->term_id}' {$fSelect}>" . $term->name . "</button>";
								$drop .= "<option value='.iso_{$term->term_id}' {$fSelected}>{$term->name}</option>";
							}
						}
						if ( empty( $scMeta['isotope_filter_show_all'] ) ) {
							$fSelect    = ( $fSelectTrigger ? null : 'class="selected"' );
							$htmlButton = "<button data-filter='*' {$fSelect}>" . __( 'Show all',
									'the-post-grid-pro' ) . "</button>" . $htmlButton;
							$drop       = "<option value='*' {$fSelect}>" . __( 'Show all',
									'the-post-grid-pro' ) . "</option>" . $drop;
						}
						$filter_count = ! empty( $scMeta['isotope_filter_count'] ) ? true : false;
						$filter_url   = ! empty( $scMeta['isotope_filter_url'] ) ? true : false;
						$htmlButton   = "<div id='iso-button-{$rand}' class='rt-tpg-isotope-buttons button-group filter-button-group option-set' data-url='{$filter_url}' data-count='{$filter_count}'>{$htmlButton}</div>";

						if ( $isotope_dropdown_filter ) {
							$data .= "<select class='isotope-dropdown-filter'>{$drop}</select>";
						} else {
							$data .= $htmlButton;
						}
						if ( ! empty( $scMeta['isotope_search_filter'] ) ) {
							$data .= "<div class='iso-search'><input type='text' class='iso-search-input' placeholder='" . __( 'Search',
									'the-post-grid-pro' ) . "' /></div>";
						}
						$data .= '</div>';

						$data .= "<div class='rt-tpg-isotope' id='iso-tpg-{$rand}'>";
					}

					$l             = $offLoop = 0;
					$offsetBigHtml = $offsetSmallHtml = null;
					$tgCol         = 2;
					if ( $layout == 'layout4' ) {
						$tgCol = round( 12 / $dCol );
					}
					while ( $gridQuery->have_posts() ) : $gridQuery->the_post();
						if ( $tgCol == $l ) {
							if ( $this->l4toggle ) {
								$this->l4toggle = false;
							} else {
								$this->l4toggle = true;
							}
							$l = 0;
						}
						$pID               = get_the_ID();
						$arg['pID']        = $pID;
						$arg['title']      = get_the_title();
						$arg['pLink']      = get_permalink();
						$arg['toggle']     = $this->l4toggle;
						$arg['author']     = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a>';
						$cc                = wp_count_comments( $pID );
						$arg['date']       = get_the_date();
						$excerpt           = get_the_excerpt();
						$arg['excerpt']    = $rtTPG->strip_tags_content( $excerpt, $excerpt_type, $excerpt_limit,
							$excerpt_more_text );
						$arg['categories'] = get_the_term_list( $pID, 'category', null, ', ' );
						$arg['tags']       = get_the_term_list( $pID, 'post_tag', null, ', ' );
						if ( $isIsotope ) {
							$termAs    = wp_get_post_terms( $pID, $isotope_filter, array( "fields" => "all" ) );
							$isoFilter = null;
							if ( ! empty( $termAs ) ) {
								foreach ( $termAs as $term ) {
									$isoFilter .= " " . "iso_" . $term->term_id;
								}
							}
							$arg['isoFilter'] = $isoFilter;
						}
						$deptClass = null;
						if ( ! empty( $deptAs ) ) {
							foreach ( $deptAs as $dept ) {
								$deptClass .= " " . $dept->slug;
							}
						}
						if ( comments_open() ) {
							$arg['comment'] = "<a href='" . get_comments_link( $pID ) . "'>{$cc->total_comments} </a>";
						} else {
							$arg['comment'] = "{$cc->total_comments}";
						}
						$imgSrc = null;

						if ( $isOffset ) {
							if ( $offLoop == 0 ) {
								$arg['imgSrc'] = !$fImg ? $rtTPG->getFeatureImageSrc( $pID, $fImgSize, $mediaSource,
									$defaultImgId,
									$customImgSize ) : null;
								$arg['offset'] = 'big';
								$offsetBigHtml = $rtTPG->render( 'layouts/' . $layout, $arg, true );
							} else {
								$arg['offset']    = 'small';
								$arg['offsetCol'] = array( $dCol, $tCol, $mCol );
								$arg['imgSrc']    = !$fImg ? $rtTPG->getFeatureImageSrc( $pID, 'thumbnail', $mediaSource,
									$defaultImgId,
									$customImgSize ) : null;
								$offsetSmallHtml .= $rtTPG->render( 'layouts/' . $layout, $arg, true );
							}
						} else {
							$arg['imgSrc'] = !$fImg ? $rtTPG->getFeatureImageSrc( $pID, $fImgSize, $mediaSource, $defaultImgId,
								$customImgSize ) : null;
							$data .= $rtTPG->render( 'layouts/' . $layout, $arg, true );
						}
						$offLoop ++;
						$l ++;
					endwhile;
					if ( $isOffset ) {
						$oDCol = $rtTPG->get_offset_col( $dCol );
						$oTCol = $rtTPG->get_offset_col( $tCol );
						$oMCol = $rtTPG->get_offset_col( $mCol );
						if ( $layout == "offset03" || $layout == "offset04" ) {
							$oDCol['big'] = $oTCol['big'] = $oDCol['small'] = $oTCol['small'] = 6;
							$oMCol['big'] = $oMCol['small'] = 12;
						}
						$data .= "<div class='rt-col-md-{$oDCol['big']} rt-col-sm-{$oTCol['big']} rt-col-xs-{$oMCol['big']}'><div class='rt-row'>{$offsetBigHtml}</div></div>";
						$data .= "<div class='rt-col-md-{$oDCol['small']} rt-col-sm-{$oTCol['small']} rt-col-xs-{$oMCol['small']}'><div class='rt-row offset-small-wrap'>{$offsetSmallHtml}</div></div>";
					}
					if ( $isIsotope || $isCarousel ) {
						$data .= '</div>'; // End isotope / Carousel item holder
					}

				} else {
					$data .= "<p>" . __( 'No post found', 'the-post-grid-pro' ) . "</p>";
				}
				$data .= $preLoaderHtml;
				$data .= "</div>"; // End row
				$htmlUtility = null;
				if ( $pagination && ! $isCarousel ) {
					if ( $isOffset ) {
						$posts_loading_type = "page_prev_next";
						$htmlUtility .= "<div class='rt-cb-page-prev-next'>
											<span class='rt-cb-prev-btn'><i class='fa fa-angle-left' aria-hidden='true'></i></span>
											<span class='rt-cb-next-btn'><i class='fa fa-angle-right' aria-hidden='true'></i></span>
										</div>";
					} else {
						if ( $posts_loading_type == "pagination" ) {
							if ( $isGrid && empty( $filters ) ) {
								$htmlUtility .= $rtTPG->rt_pagination( $gridQuery->max_num_pages,
									$args['posts_per_page'] );
							}
						} elseif ( $posts_loading_type == "pagination_ajax" && ! $isIsotope ) {
							if ( $isGrid ) {
								$htmlUtility .= "<div class='rt-page-numbers'></div>";
							} else {
								$htmlUtility .= $rtTPG->rt_pagination( $gridQuery->max_num_pages,
									$args['posts_per_page'], true, $scID );
							}
						} elseif ( $posts_loading_type == "load_more" ) {
							if ( $isGrid ) {
								$htmlUtility .= "<div class='rt-loadmore-btn rt-loadmore-action rt-loadmore-style'>
											<span class='rt-loadmore-text'>" . __( 'Load More', 'the-post-grid-pro' ) . "</span>
											<div class='rt-loadmore-loading rt-ball-scale-multiple rt-2x'><div></div><div></div><div></div></div>
										</div>";
							} else {
								$htmlUtility .= "<div class='rt-tpg-load-more'>
                                        <button data-sc-id='{$scID}' data-paged='2'>" . __( 'Load More',
										'the-post-grid-pro' ) . "</button>
                                    </div>";
							}

						} elseif ( $posts_loading_type == "load_on_scroll" ) {
							if ( $isGrid ) {
								$htmlUtility .= "<div class='rt-infinite-action'>	
													<div class='rt-infinite-loading la-fire la-2x'>
														<div></div>
														<div></div>
														<div></div>
													</div>
												</div>";
							} else {
								$htmlUtility .= "<div class='rt-tpg-scroll-load-more' data-trigger='1' data-sc-id='{$scID}' data-paged='2'></div>";
							}
						}
					}
				}

				if ( $htmlUtility ) {
					$l4toggle = null;
					if ( $layout == "layout4" ) {
						$l4toggle = "data-l4toggle='{$this->l4toggle}'";
					}
					if ( $isGrid || $isOffset || $isWooCom ) {
						$data .= "<div class='rt-pagination-wrap' data-total-pages='{$gridQuery->max_num_pages}' data-posts-per-page='{$args['posts_per_page']}' data-type='{$posts_loading_type}' {$l4toggle} >" . $htmlUtility . "</div>";
					} else {
						$data .= "<div class='rt-tpg-utility' {$l4toggle}>" . $htmlUtility . "</div>";
					}
				}

				$data .= "</div>"; // container rt-tpg


			} else {
				$msg = __( 'Session Error !!', 'the-post-grid-pro' );
			}

			wp_send_json( array(
				'error' => $error,
				'msg'   => $msg,
				'data'  => $data
			) );
			die();

		}
	}

endif;