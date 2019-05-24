<?php

if ( ! class_exists( 'rtTPGShortCode' ) ):

	class rtTPGShortCode {

		private $scA = array();
		private $l4toggle = false;

		function __construct() {
			add_shortcode( 'the-post-grid', array( $this, 'the_post_grid_short_code' ) );
			add_action( 'pre_get_posts', array( $this, 'make_sticky_work' ) );
		}

		function make_sticky_work( $q ) {
			if ( true === $q->get( 'wp_rtcl_is_home' ) ) {
				$q->is_home = true;
			}
		}

		function register_sc_scripts() {
			global $rtTPG;
			$caro   = $isSinglePopUp = false;
			$script = array();
			$style  = array();
			array_push( $script, 'jquery' );
			$ajaxurl = '';
			if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
				$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
			} else {
				$ajaxurl .= admin_url( 'admin-ajax.php' );
			}
			$variables = array(
				'nonceID' => $rtTPG->nonceId(),
				'nonce'   => wp_create_nonce( $rtTPG->nonceText() ),
				'ajaxurl' => $ajaxurl
			);
			foreach ( $this->scA as $sc ) {
				if ( isset( $sc ) && is_array( $sc ) ) {
					if ( $sc['isSinglePopUp'] ) {
						$isSinglePopUp = true;
					}
					if ( $sc['isWooCom'] ) {
						$variables['woocommerce_enable_ajax_add_to_cart'] = get_option( 'woocommerce_enable_ajax_add_to_cart' );
						$variables['woocommerce_cart_redirect_after_add'] = get_option( 'woocommerce_cart_redirect_after_add' );
					}
				}
			}
			if ( count( $this->scA ) ) {
				array_push( $script, 'jquery' );
				array_push( $script, 'rt-image-load-js' );
				array_push( $script, 'rt-isotope-js' );
				array_push( $style, 'rt-owl-carousel' );
				array_push( $style, 'rt-owl-carousel-theme' );
				array_push( $script, 'rt-pagination' );
				array_push( $script, 'rt-owl-carousel' );
				array_push( $style, 'rt-scrollbar' );
				array_push( $script, 'rt-scrollbar' );
				array_push( $style, 'rt-fontawsome' );
				array_push( $style, 'rt-magnific-popup' );
				array_push( $script, 'rt-magnific-popup' );
				array_push( $script, 'rt-actual-height-js' );
				array_push( $script, 'rt-tpg' );
				if ( class_exists( 'WooCommerce' ) ) {
					array_push( $script, 'rt-jzoom' );
				}
				if ( is_rtl() ) {
					array_push( $style, 'rt-tpg-rtl' );
				}
				wp_enqueue_style( $style );
				wp_enqueue_script( $script );
				wp_localize_script( 'rt-tpg', 'rttpg', $variables );


				if ( $isSinglePopUp ) {
					$html = null;
					$html .= '<div class="md-modal rt-md-effect" id="rt-modal">
							<div class="md-content">
								<div class="rt-md-content-holder">

								</div>
								<div class="md-cls-btn">
									<button class="md-close"><i class="fa fa-times" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>';
					$html .= "<div class='md-overlay'></div>";
					echo $html;
				}
			}
		}


		function the_post_grid_short_code( $atts, $content = null ) {
			$rand     = mt_rand();
			$layoutID = "rt-tpg-container-" . $rand;
			global $rtTPG;
			$html = null;
			$arg  = array();
			$atts = shortcode_atts( array(
				'id' => null
			), $atts, 'the-post-grid' );
			$scID = $atts['id'];
			if ( $scID && ! is_null( get_post( $scID ) ) ) {
				$scMeta = get_post_meta( $scID );

				$layout = ( isset( $scMeta['layout'][0] ) ? $scMeta['layout'][0] : 'layout1' );
				if ( ! in_array( $layout, array_keys( $rtTPG->rtTPGLayouts() ) ) ) {
					$layout = 'layout1';
				}

				$isIsotope  = preg_match( '/isotope/', $layout );
				$isCarousel = preg_match( '/carousel/', $layout );
				$isGrid     = preg_match( '/layout/', $layout );
				$isWooCom   = preg_match( '/wc/', $layout );
				$isOffset   = preg_match( '/offset/', $layout );

				$colStore = $dCol = ( isset( $scMeta['column'][0] ) ? absint( $scMeta['column'][0] ) : 3 );
				$tCol     = ( isset( $scMeta['tpg_tab_column'][0] ) ? absint( $scMeta['tpg_tab_column'][0] ) : 2 );
				$mCol     = ( isset( $scMeta['tpg_mobile_column'][0] ) ? absint( $scMeta['tpg_mobile_column'][0] ) : 1 );
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

				$fImg              = ( ! empty( $scMeta['feature_image'][0] ) ? true : false );
				$fImgSize          = ( isset( $scMeta['featured_image_size'][0] ) ? $scMeta['featured_image_size'][0] : "medium" );
				$mediaSource       = ( isset( $scMeta['media_source'][0] ) ? $scMeta['media_source'][0] : "feature_image" );
				$excerpt_type      = ( isset( $scMeta['tgp_excerpt_type'][0] ) ? $scMeta['tgp_excerpt_type'][0] : 'character' );
				$excerpt_limit     = ( isset( $scMeta['excerpt_limit'][0] ) ? absint( $scMeta['excerpt_limit'][0] ) : 0 );
				$excerpt_more_text = ( isset( $scMeta['tgp_excerpt_more_text'][0] ) ? $scMeta['tgp_excerpt_more_text'][0] : null );
				$read_more_text    = ( ! empty( $scMeta['tgp_read_more_text'][0] ) ? $scMeta['tgp_read_more_text'][0] : __( 'Read More',
					'the-post-grid-pro' ) );


				/* Argument create */
				$args     = array();
				$postType = ( isset( $scMeta['tpg_post_type'][0] ) ? $scMeta['tpg_post_type'][0] : 'post' );
				if ( $postType ) {
					$args['post_type'] = $postType;
				}

				// Common filter
				/* post__in */
				$post__in = ( isset( $scMeta['post__in'][0] ) ? $scMeta['post__in'][0] : null );
				if ( $post__in ) {
					$post__in         = explode( ',', $post__in );
					$args['post__in'] = $post__in;
				}
				/* post__not_in */
				$post__not_in = ( isset( $scMeta['post__not_in'][0] ) ? $scMeta['post__not_in'][0] : null );
				if ( $post__not_in ) {
					$post__not_in         = explode( ',', $post__not_in );
					$args['post__not_in'] = $post__not_in;
				}

				/* LIMIT */
				$limit                  = ( ( empty( $scMeta['limit'][0] ) || $scMeta['limit'][0] === '-1' ) ? 10000000 : (int) $scMeta['limit'][0] );
				$args['posts_per_page'] = $limit;
				$pagination             = ( ! empty( $scMeta['pagination'][0] ) ? true : false );
				$posts_loading_type     = ( ! empty( $scMeta['posts_loading_type'][0] ) ? $scMeta['posts_loading_type'][0] : "pagination" );
				if ( $pagination ) {
					$posts_per_page = ( isset( $scMeta['posts_per_page'][0] ) ? intval( $scMeta['posts_per_page'][0] ) : $limit );
					if ( $posts_per_page > $limit ) {
						$posts_per_page = $limit;
					}
					// Set 'posts_per_page' parameter
					$args['posts_per_page'] = $posts_per_page;

					$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;

					$offset        = $posts_per_page * ( (int) $paged - 1 );
					$args['paged'] = $paged;

					// Update posts_per_page
					if ( intval( $args['posts_per_page'] ) > $limit - $offset ) {
						$args['posts_per_page'] = $limit - $offset;
					}
					//if($args['paged'] == 1){unset($args['paged']);}
				}

				// Advance Filter
				$adv_filter      = get_post_meta( $scID, 'post_filter' );
				$taxFilter       = get_post_meta( $scID, 'tgp_filter_taxonomy', true );
				$taxHierarchical = get_post_meta( $scID, 'tgp_filter_taxonomy_hierarchical', true );
				$taxFilterTerms  = array();
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
								$operator = ( isset( $scMeta[ 'term_operator_' . $taxonomy ][0] ) ? $scMeta[ 'term_operator_' . $taxonomy ][0] : "IN" );
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
						$relation         = ( isset( $scMeta['taxonomy_relation'][0] ) ? $scMeta['taxonomy_relation'][0] : "AND" );
						$taxQ['relation'] = $relation;
					}
				}

				if ( ! empty( $taxQ ) ) {
					$args['tax_query'] = $taxQ;
				}


				// Order
				if ( in_array( 'order', $adv_filter ) ) {
					$order_by = ( isset( $scMeta['order_by'][0] ) ? $scMeta['order_by'][0] : null );
					$order    = ( isset( $scMeta['order'][0] ) ? $scMeta['order'][0] : null );
					if ( $order ) {
						$args['order'] = $order;
					}
					if ( $order_by ) {
						$args['orderby'] = $order_by;
						$meta_key        = ! empty( $scMeta['tpg_meta_key'][0] ) ? $scMeta['tpg_meta_key'][0] : null;
						if ( in_array( $order_by, array_keys( $rtTPG->rtMetaKeyType() ) ) && $meta_key ) {
							$args['orderby']  = $order_by;
							$args['meta_key'] = $meta_key;
						}
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
				$s = ( isset( $scMeta['s'][0] ) ? $scMeta['s'][0] : array() );
				if ( in_array( 's', $adv_filter ) && ! empty( $s ) ) {
					$args['s'] = $s;
				}

				// Date query
				if ( in_array( 'date_range', $adv_filter ) ) {
					$startDate = ( ! empty( $scMeta['date_range_start'][0] ) ? $scMeta['date_range_start'][0] : null );
					$endDate   = ( ! empty( $scMeta['date_range_end'][0] ) ? $scMeta['date_range_end'][0] : null );
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

				$settings        = get_option( $rtTPG->options['settings'] );
				$oLayoutTag      = ! empty( $settings['template_tag'] ) ? absint( $settings['template_tag'] ) : null;
				$oLayoutAuthor   = ! empty( $settings['template_author'] ) ? $settings['template_author'] : null;
				$oLayoutCategory = ! empty( $settings['template_category'] ) ? $settings['template_category'] : null;
				$oLayoutSearch   = ! empty( $settings['template_search'] ) ? $settings['template_search'] : null;
				$dataArchive     = null;
				if ( ( is_category() && $oLayoutCategory ) || ( is_search() && $oLayoutSearch ) || ( is_tag() && $oLayoutTag ) || ( is_author() && $oLayoutAuthor ) ) {

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
				$dCol              = $dCol == 5 ? '24' : round( 12 / $dCol );
				$tCol              = $dCol == 5 ? '24' : round( 12 / $tCol );
				$mCol              = $dCol == 5 ? '24' : round( 12 / $mCol );
				if ( $isCarousel ) {
					$dCol = $tCol = $mCol = 12;
				}
				$arg                   = array();
				$arg['read_more_text'] = $read_more_text;
				$arg['grid']           = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";
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
				$arg['class'] = null;
				$gridType     = ! empty( $scMeta['grid_style'][0] ) ? $scMeta['grid_style'][0] : 'even';
				if ( ! $isCarousel && ! $isOffset ) {
					$arg['class'] .= " " . $gridType . "-grid-item ";
				}
				$arg['class'] .= " rt-grid-item";
				if ( $isOffset ) {
					$arg['class'] .= " rt-offset-item ";
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
					$preLoader    = 'tpg-pre-loader';
				}
				if ( $isCarousel ) {
					$arg['class'] .= ' carousel-item';
					$preLoader    = 'tpg-pre-loader';
				}
				if ( $preLoader ) {
					$preLoaderHtml = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>';
				}

				$margin = ! empty( $scMeta['margin_option'][0] ) ? $scMeta['margin_option'][0] : 'default';
				if ( $margin == 'no' ) {
					$arg['class'] .= ' no-margin';
				}
				if ( ! empty( $scMeta['tpg_image_type'][0] ) && $scMeta['tpg_image_type'][0] == 'circle' ) {
					$arg['class'] .= ' tpg-img-circle';
				}

				$arg['anchorClass'] = null;
				$link               = ! empty( $scMeta['link_to_detail_page'][0] ) ? $scMeta['link_to_detail_page'][0] : 'yes';
				if ( $link != 'yes' ) {
					$arg['anchorClass'] = ' disabled';
				}
				$isSinglePopUp = false;
				$linkType      = ! empty( $scMeta['detail_page_link_type'][0] ) ? $scMeta['detail_page_link_type'][0] : 'popup';
				if ( $link == 'yes' && $linkType == 'popup' ) {
					$popupType = ! empty( $scMeta['popup_type'][0] ) ? $scMeta['popup_type'][0] : 'single';
					if ( $popupType == 'single' ) {
						$arg['anchorClass'] .= ' tpg-single-popup';
						$isSinglePopUp      = true;
					} else {
						$arg['anchorClass'] .= ' tpg-multi-popup';
					}
				}

				$parentClass   = ( ! empty( $scMeta['parent_class'][0] ) ? trim( $scMeta['parent_class'][0] ) : null );
				$defaultImgId  = ( ! empty( $scMeta['default_preview_image'][0] ) ? absint( $scMeta['default_preview_image'][0] ) : null );
				$customImgSize = ( ! empty( $scMeta['custom_image_size'] ) ? $scMeta['custom_image_size'] : array() );

				$arg['items'] = isset( $scMeta['item_fields'] ) ? ( $scMeta['item_fields'] ? $scMeta['item_fields'] : array() ) : array();
				if ( in_array( 'cf', $arg['items'] ) ) {
					$arg['cf_group'] = array();
					$arg['cf_group'] = get_post_meta( $scID, 'cf_group' );
					$arg['format']   = array(
						'hide_empty'       => get_post_meta( $scID, 'cf_hide_empty_value', true ),
						'show_value'       => get_post_meta( $scID, 'cf_show_only_value', true ),
						'hide_group_title' => get_post_meta( $scID, 'cf_hide_group_title', true )
					);
				}

				if ( ! empty( $scMeta['ignore_sticky_posts'][0] ) ) {
					$args['ignore_sticky_posts'] = true;
				} else {
					$args['wp_rtcl_is_home'] = true;
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
				$html              .= $rtTPG->layoutStyle( $layoutID, $scMeta, $layout, $scID );
				$containerDataAttr .= " data-sc-id='{$scID}'";
				$html              .= "<div class='rt-container-fluid rt-tpg-container {$parentClass}' id='{$layoutID}' {$dataArchive} {$containerDataAttr}>";
				if ( ! empty( $filters ) && ( $isGrid || $isOffset || $isWooCom ) ) {
					$html .= "<div class='rt-layout-filter-container rt-clear'><div class='rt-filter-wrap'>";
					if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter ) {
						$filterType     = ( ! empty( $scMeta['tgp_filter_type'][0] ) ? $scMeta['tgp_filter_type'][0] : null );
						$post_count     = ( ! empty( $scMeta['tpg_post_count'][0] ) ? $scMeta['tpg_post_count'][0] : null );
						$postCountClass = ( $post_count ? " has-post-count" : null );
						if ( $taxHierarchical ) {
							$terms = $rtTPG->rt_get_all_term_by_taxonomy( $taxFilter, true, 0 );
						} else {
							$terms = $rtTPG->rt_get_all_term_by_taxonomy( $taxFilter, true );
						}

						$allSelect      = " selected";
						$isTermSelected = false;
						if ( $action_term && $taxFilter ) {
							$isTermSelected = true;
							$allSelect      = null;
						}
						$hide_all_button = ( ! empty( $scMeta['tpg_hide_all_button'][0] ) ? false : true );
						if ( ! $filterType || $filterType == 'dropdown' ) {
							$html            .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-dropdown-wrap parent-dropdown-wrap{$postCountClass}' data-taxonomy='{$taxFilter}'>";
							$termDefaultText = __( "All", "the-post-grid-pro" );
							$dataTerm        = 'all';
							$htmlButton      = "";
							$htmlButton      .= '<span class="term-dropdown rt-filter-dropdown">';
							$pCount          = 0;
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$pCount = $pCount + $term['count'];
									$sT     = null;
									if ( $taxHierarchical ) {
										$subTerms = $rtTPG->rt_get_all_term_by_taxonomy( $taxFilter, true, $id );
										if ( ! empty( $subTerms ) ) {
											$count = 0;
											$item  = $allCount = null;
											foreach ( $subTerms as $stId => $t ) {
												$count       = $count + absint( $t['count'] );
												$sTPostCount = ( $post_count ? " (<span class='rt-post-count'>{$t['count']}</span>)" : null );
												$item        .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$stId}'>{$t['name']}{$sTPostCount}</span>";
											}
											if ( $post_count ) {
												$allCount = " (<span class='rt-post-count'>{$count}</span>)";
											}
											$sT .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-dropdown-wrap sub-dropdown-wrap{$postCountClass}'>";
											$sT .= "<span class='term-default rt-filter-dropdown-default' data-term='{$id}'>
								                        <span class='rt-text'>" . __( 'All', 'the-post-grid-pro' ) . "{$allCount}</span>
								                        <i class='fa fa-angle-down rt-arrow-angle' aria-hidden='true'></i>
								                    </span>";
											$sT .= '<span class="term-dropdown rt-filter-dropdown">';
											$sT .= $item;
											$sT .= '</span>';
											$sT .= "</div>";
										}
									}
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
												$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term['name']}{$postCount}{$sT}</span>";
											}
										}
									} else {
										if ( $action_term == $id ) {
											$termDefaultText = $term['name'] . $postCount;
											$dataTerm        = $id;
										} else {
											$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term['name']}{$postCount}{$sT}</span>";
										}
									}
								}
							}
							$pAllCount = null;
							if ( $post_count ) {
								$pAllCount = " (<span class='rt-post-count'>{$pCount}</span>)";
								if ( ! $action_term ) {
									$termDefaultText = $termDefaultText . $pAllCount;
								}
							}
							if ( $isTermSelected ) {
								$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='all'>" . __( "All",
										"the-post-grid-pro" ) . "{$pAllCount}</span>";
							}
							$htmlButton .= '</span>';

							$showAllhtml = '<span class="term-default rt-filter-dropdown-default" data-term="' . $dataTerm . '">
								                        <span class="rt-text">' . $termDefaultText . '</span>
								                        <i class="fa fa-angle-down rt-arrow-angle" aria-hidden="true"></i>
								                    </span>';

							$html .= $showAllhtml . $htmlButton;
							$html .= '</div>';
						} else {
							$bCount = 0;
							$bItems = null;
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$bCount = $bCount + absint( $term['count'] );
									$sT     = null;
									if ( $taxHierarchical ) {
										$subTerms = $rtTPG->rt_get_all_term_by_taxonomy( $taxFilter, true, $id );
										if ( ! empty( $subTerms ) ) {
											$sT .= "<div class='rt-filter-sub-tax sub-button-group'>";
											foreach ( $subTerms as $stId => $t ) {
												$sTPostCount = ( $post_count ? " (<span class='rt-post-count'>{$t['count']}</span>)" : null );
												$sT          .= "<span class='rt-filter-button-item' data-term='{$stId}'>{$t['name']}{$sTPostCount}</span>";
											}
											$sT .= "</div>";
										}
									}
									$postCount    = ( $post_count ? " (<span class='rt-post-count'>{$term['count']}</span>)" : null );
									$termSelected = null;
									if ( $isTermSelected && $id == $action_term ) {
										$termSelected = " selected";
									}
									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {
										if ( in_array( $id, $taxFilterTerms ) ) {
											$bItems .= "<span class='term-button-item rt-filter-button-item {$termSelected}' data-term='{$id}'>{$term['name']}{$postCount}{$sT}</span>";
										}
									} else {
										$bItems .= "<span class='term-button-item rt-filter-button-item {$termSelected}' data-term='{$id}'>{$term['name']}{$postCount}{$sT}</span>";
									}
								}
							}
							$html .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-button-wrap{$postCountClass}' data-taxonomy='{$taxFilter}'>";
							if ( $hide_all_button ) {
								$pCountH = ( $post_count ? " (<span class='rt-post-count'>{$bCount}</span>)" : null );
								$html    .= "<span class='term-button-item rt-filter-button-item {$allSelect}' data-term='all'>" . __( "All",
										"the-post-grid-pro" ) . "{$pCountH}</span>";
							}
							$html .= $bItems;
							$html .= "</div>";
						}
					}
					if ( in_array( '_sort_order', $filters ) ) {
						$action_order = ( ! empty( $args['order'] ) ? strtoupper( trim( $args['order'] ) ) : "DESC" );
						$html         .= '<div class="rt-filter-item-wrap rt-sort-order-action">';
						$html         .= "<span class='rt-sort-order-action-arrow' data-sort-order='{$action_order}'>&nbsp;<span></span></span>";
						$html         .= '</div>';
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
						$html .= '<div class="rt-filter-item-wrap rt-order-by-action rt-filter-dropdown-wrap">';
						$html .= "<span class='order-by-default rt-filter-dropdown-default' data-order-by='{$action_orderby}'>
							                        <span class='rt-text-order-by'>{$action_orderby_label}</span>
							                        <i class='fa fa-angle-down rt-arrow-angle' aria-hidden='true'></i>
							                    </span>";
						$html .= '<span class="order-by-dropdown rt-filter-dropdown">';

						foreach ( $orders as $orderKey => $order ) {
							$html .= '<span class="order-by-dropdown-item rt-filter-dropdown-item" data-order-by="' . $orderKey . '">' . $order . '</span>';
						}
						$html .= '</span>';
						$html .= '</div>';
					}
					if ( in_array( '_search', $filters ) ) {
						$html .= '<div class="rt-filter-item-wrap rt-search-filter-wrap">';
						$html .= "<input type='text' class='rt-search-input' placeholder='Search...'>";
						$html .= "<span class='rt-action'>&#128269;</span>";
						$html .= "<span class='rt-loading'></span>";
						$html .= '</div>';
					}
					$html .= "</div></div>";
				}

				$html .= "<div data-title='" . __( "Loading ...",
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
						$html            .= "<div class='rt-carousel-holder'  data-rtowl-options='{
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
						$isotope_filter          = isset( $scMeta['isotope_filter'][0] ) ? $scMeta['isotope_filter'][0] : null;
						$isotope_dropdown_filter = isset( $scMeta['isotope_filter_dropdown'][0] ) ? $scMeta['isotope_filter_dropdown'][0] : null;
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
						} else {
							$terms = get_terms( $isotope_filter, array(
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => false,
								'include'    => $selectedTerms
							) );
						}


						$html           .= '<div class="tpg-iso-filter">';
						$htmlButton     = $drop = null;
						$fSelectTrigger = false;
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$tItem   = ! empty( $scMeta['isotope_default_filter'][0] ) ? $scMeta['isotope_default_filter'][0] : null;
								$fSelect = $fSelected = null;
								if ( $tItem == $term->term_id ) {
									$fSelect        = 'class="selected"';
									$fSelected      = 'selected';
									$fSelectTrigger = true;
								}
								$htmlButton .= "<button data-filter='.iso_{$term->term_id}' {$fSelect}>" . $term->name . "</button>";
								$drop       .= "<option value='.iso_{$term->term_id}' {$fSelected}>{$term->name}</option>";
							}
						}
						if ( empty( $scMeta['isotope_filter_show_all'][0] ) ) {
							$fSelect    = ( $fSelectTrigger ? null : 'class="selected"' );
							$htmlButton = "<button data-filter='*' {$fSelect}>" . __( 'Show all',
									'the-post-grid-pro' ) . "</button>" . $htmlButton;
							$drop       = "<option value='*' {$fSelect}>" . __( 'Show all',
									'the-post-grid-pro' ) . "</option>" . $drop;
						}
						$filter_count = ! empty( $scMeta['isotope_filter_count'][0] ) ? true : false;
						$filter_url   = ! empty( $scMeta['isotope_filter_url'][0] ) ? true : false;
						$htmlButton   = "<div id='iso-button-{$rand}' class='rt-tpg-isotope-buttons button-group filter-button-group option-set' data-url='{$filter_url}' data-count='{$filter_count}'>{$htmlButton}</div>";

						if ( $isotope_dropdown_filter ) {
							$html .= "<select class='isotope-dropdown-filter'>{$drop}</select>";
						} else {
							$html .= $htmlButton;
						}
						if ( ! empty( $scMeta['isotope_search_filter'][0] ) ) {
							$html .= "<div class='iso-search'><input type='text' class='iso-search-input' placeholder='" . __( 'Search',
									'the-post-grid-pro' ) . "' /></div>";
						}
						$html .= '</div>';

						$html .= "<div class='rt-tpg-isotope' id='iso-tpg-{$rand}'>";
					}

					$l             = $offLoop = 0;
					$offsetBigHtml = $offsetSmallHtml = null;

					while ( $gridQuery->have_posts() ) : $gridQuery->the_post();
						if ( $colStore == $l ) {
							if ( $this->l4toggle ) {
								$this->l4toggle = false;
							} else {
								$this->l4toggle = true;
							}
							$l = 0;
						}
						$pID             = get_the_ID();
						$arg['pID']      = $pID;
						$arg['title']    = get_the_title();
						$arg['pLink']    = get_permalink();
						$arg['toggle']   = $this->l4toggle;
						$arg['layoutID'] = $layoutID;
						$arg['author']   = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a>';
						$cc              = wp_count_comments( $pID );
						$arg['date']     = get_the_date();
						ob_start();
						the_excerpt();
						$arg['excerpt'] = ob_get_contents();
						ob_end_clean();
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
								$arg['imgSrc'] = ! $fImg ? $rtTPG->getFeatureImageSrc( $pID, $fImgSize, $mediaSource,
									$defaultImgId,
									$customImgSize ) : null;
								$arg['offset'] = 'big';
								$offsetBigHtml = $rtTPG->render( 'layouts/' . $layout, $arg, true );
							} else {
								$arg['offset']    = 'small';
								$arg['offsetCol'] = array( $dCol, $tCol, $mCol );
								$arg['imgSrc']    = ! $fImg ? $rtTPG->getFeatureImageSrc( $pID, 'thumbnail',
									$mediaSource,
									$defaultImgId,
									$customImgSize ) : null;
								$offsetSmallHtml  .= $rtTPG->render( 'layouts/' . $layout, $arg, true );
							}
						} else {
							$arg['imgSrc'] = ! $fImg ? $rtTPG->getFeatureImageSrc( $pID, $fImgSize, $mediaSource,
								$defaultImgId,
								$customImgSize ) : null;
							$html          .= $rtTPG->render( 'layouts/' . $layout, $arg, true );
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
						$html .= "<div class='rt-col-md-{$oDCol['big']} rt-col-sm-{$oTCol['big']} rt-col-xs-{$oMCol['big']}'><div class='rt-row'>{$offsetBigHtml}</div></div>";
						$html .= "<div class='rt-col-md-{$oDCol['small']} rt-col-sm-{$oTCol['small']} rt-col-xs-{$oMCol['small']}'><div class='rt-row offset-small-wrap'>{$offsetSmallHtml}</div></div>";
					}
					if ( $isIsotope || $isCarousel ) {
						$html .= '</div>'; // End isotope / Carousel item holder
					}

				} else {
					$html .= "<p>" . __( 'No post found', 'the-post-grid-pro' ) . "</p>";
				}
				$html        .= $preLoaderHtml;
				$html        .= "</div>"; // End row
				$htmlUtility = null;
				if ( $pagination && ! $isCarousel ) {
					if ( $isOffset ) {
						$posts_loading_type = "page_prev_next";
						$htmlUtility        .= "<div class='rt-cb-page-prev-next'>
											<span class='rt-cb-prev-btn'><i class='fa fa-angle-left' aria-hidden='true'></i></span>
											<span class='rt-cb-next-btn'><i class='fa fa-angle-right' aria-hidden='true'></i></span>
										</div>";
					} else {
						$hide = ( $gridQuery->max_num_pages < 2 ? " rt-hidden-elm" : null );
						if ( $posts_loading_type == "pagination" ) {
							if ( $isGrid && empty( $filters ) ) {
								$htmlUtility .= $rtTPG->rt_pagination( $gridQuery,
									$args['posts_per_page'] );
							}
						} elseif ( $posts_loading_type == "pagination_ajax" && ! $isIsotope ) {
							$htmlUtility .= "<div class='rt-page-numbers'></div>";
						} elseif ( $posts_loading_type == "load_more" ) {
							$htmlUtility .= "<div class='rt-loadmore-btn rt-loadmore-action rt-loadmore-style{$hide}'>
											<span class='rt-loadmore-text'>" . __( 'Load More', 'the-post-grid-pro' ) . "</span>
											<div class='rt-loadmore-loading rt-ball-scale-multiple rt-2x'><div></div><div></div><div></div></div>
										</div>";

						} elseif ( $posts_loading_type == "load_on_scroll" ) {
							$htmlUtility .= "<div class='rt-infinite-action'>	
												<div class='rt-infinite-loading la-fire la-2x'>
													<div></div>
													<div></div>
													<div></div>
												</div>
											</div>";
						}
					}
				}

				if ( $htmlUtility ) {
					$l4toggle = null;
					if ( $layout == "layout4" ) {
						$l4toggle = "data-l4toggle='{$this->l4toggle}'";
					}
					$html .= "<div class='rt-pagination-wrap' data-total-pages='{$gridQuery->max_num_pages}' data-posts-per-page='{$args['posts_per_page']}' data-type='{$posts_loading_type}' {$l4toggle} >" . $htmlUtility . "</div>";
				}

				$html .= "</div>"; // container rt-tpg

				wp_reset_postdata();

				$scriptGenerator                  = array();
				$scriptGenerator['layout']        = $layoutID;
				$scriptGenerator['rand']          = $rand;
				$scriptGenerator['scMeta']        = $scMeta;
				$scriptGenerator['isCarousel']    = $isCarousel;
				$scriptGenerator['isSinglePopUp'] = $isSinglePopUp;
				$scriptGenerator['isWooCom']      = $isWooCom;
				$this->scA[]                      = $scriptGenerator;
				add_action( 'wp_footer', array( $this, 'register_sc_scripts' ) );
			} else {
				$html .= "<p>" . __( "No shortCode found", "the-post-grid-pro" ) . "</p>";
			}

			//restriction issue
			$restriction = ( ! empty( $scMeta['restriction_user_role'] ) ? $scMeta['restriction_user_role'] : array() );
			if ( ! empty( $restriction ) ) {
				if ( is_user_logged_in() ) {
					$currentUserRoles = $rtTPG->getCurrentUserRoles();
					if ( in_array( 'administrator', $currentUserRoles ) ) {
						$html = $html;
					} else {
						if ( count( array_intersect( $restriction, $currentUserRoles ) ) ) {
							$html = $html;
						} else {
							$html = "<p>" . __( "You are not permitted to view this content.",
									"the-post-grid-pro" ) . "</p>";
						}
					}

				} else {
					$html = "<p>" . __( "This is a restricted content, you need to logged in to view this content.",
							"the-post-grid-pro" ) . "</p>";
				}
			}

			return $html;
		}

		public function order_by_popularity_post_clauses( $args ) {
			global $wpdb;
			$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";

			return $args;
		}
	}
endif;
