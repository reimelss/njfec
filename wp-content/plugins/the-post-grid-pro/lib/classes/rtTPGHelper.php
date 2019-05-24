<?php

if ( ! class_exists( 'rtTPGHelper' ) ):

	class rtTPGHelper {
		function verifyNonce() {
			global $rtTPG;
			$nonce     = isset( $_REQUEST[ $this->nonceId() ] ) ? $_REQUEST[ $this->nonceId() ] : null;
			$nonceText = $rtTPG->nonceText();
			if ( ! wp_verify_nonce( $nonce, $nonceText ) ) {
				return false;
			}

			return true;
		}

		function nonceText() {
			return "rttpg_nonce_secret";
		}

		function nonceId() {
			return "rttpg_nonce";
		}

		function rtAllOptionFields() {
			global $rtTPG;
			$fields = array_merge(
				$rtTPG->rtTPGCommonFilterFields(),
				$rtTPG->rtTPGLayoutSettingFields(),
				$rtTPG->rtTPGStyleFields(),
				$rtTPG->rtTPGPostType(),
				$rtTPG->rtTPAdvanceFilters(),
				$rtTPG->itemFields()
			);

			return $fields;
		}

		function rt_get_all_term_by_taxonomy( $taxonomy = null, $count = false, $parent = false ) {
			$terms = array();
			if ( $taxonomy ) {
				$temp_terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0 ) );
				if ( is_array( $temp_terms ) && ! empty( $temp_terms ) && empty( $temp_terms['errors'] ) ) {
					foreach ( $temp_terms as $term ) {
						$order = get_term_meta( $term->term_id, '_rt_order', true );
						if ( $order === "" ) {
							update_term_meta( $term->term_id, '_rt_order', 0 );
						}
					}
					global $wp_version;
					$args = array(
						'taxonomy'   => $taxonomy,
						'orderby'    => 'name',
						'order'      => 'ASC',
						'hide_empty' => false,
					);
					if ( $parent >= 0 && $parent !== false ) {
						$args['parent'] = absint( $parent );
					}
					if ( version_compare( $wp_version, '4.5', '>=' ) ) {
						$args['orderby']  = 'meta_value_num';
						$args['meta_key'] = '_rt_order';
					}

					$termObjs = get_terms( $args );

					foreach ( $termObjs as $term ) {
						if ( $count ) {
							$terms[ $term->term_id ] = array( 'name' => $term->name, 'count' => $term->count );
						} else {
							$terms[ $term->term_id ] = $term->name;
						}
					}
				}
			}

			return $terms;
		}

		function getAllTpgTaxonomyObject( $pt = 'post' ) {
			$taxonomy_objects = get_object_taxonomies( $pt, 'objects' );
			$taxonomy_list    = array();
			if ( ! empty( $taxonomy_objects ) ) {
				foreach ( $taxonomy_objects as $taxonomy ) {
					if ( ! in_array( $taxonomy->name, array( 'language', 'post_translations' ) ) ) {
						$taxonomy_list[] = $taxonomy;
					}
				}
			}

			return $taxonomy_list;
		}

		function getAllUserRoles() {
			global $wp_roles;
			$roles = array();
			if ( ! empty( $wp_roles->roles ) ) {
				foreach ( $wp_roles->roles as $roleID => $role ) {
					$roles[ $roleID ] = $role['name'];
				}
			}

			return $roles;
		}

		function getCurrentUserRoles() {
			global $current_user;

			return $current_user->roles;
		}

		function rt_get_taxonomy_for_filter( $post_type = null ) {

			if ( ! $post_type ) {
				$post_type = get_post_meta( get_the_ID(), 'tpg_post_type', true );
			}
			if ( ! $post_type ) {
				$post_type = 'post';
			}

			return $this->rt_get_all_taxonomy_by_post_type( $post_type );
		}

		function rt_get_all_taxonomy_by_post_type( $post_type = null ) {
			$taxonomies = array();
			if ( $post_type && post_type_exists( $post_type ) ) {
				$taxObj = get_object_taxonomies( $post_type, 'objects' );
				if ( is_array( $taxObj ) && ! empty( $taxObj ) ) {
					foreach ( $taxObj as $tKey => $taxonomy ) {
						$taxonomies[ $tKey ] = $taxonomy->label;
					}
				}
			}
			if ( $post_type == 'post' ) {
				unset( $taxonomies['post_format'] );
			}

			return $taxonomies;
		}

		function rt_get_users() {
			$users = array();
			$u     = get_users();
			if ( ! empty( $u ) ) {
				foreach ( $u as $user ) {
					$users[ $user->ID ] = $user->display_name;
				}
			}

			return $users;
		}

		function rtFieldGenerator( $fields = array() ) {
			$html = null;
			if ( is_array( $fields ) && ! empty( $fields ) ) {
				$tpgField = new rtTPGField();
				foreach ( $fields as $fieldKey => $field ) {
					$html .= $tpgField->Field( $fieldKey, $field );
				}
			}

			return $html;
		}

		/**
		 * Sanitize field value
		 *
		 * @param array $field
		 * @param null $value
		 *
		 * @return array|null
		 * @internal param $value
		 */
		function sanitize( $field = array(), $value = null ) {
			$newValue = null;
			if ( is_array( $field ) ) {
				$type = ( ! empty( $field['type'] ) ? $field['type'] : 'text' );
				if ( empty( $field['multiple'] ) ) {
					if ( $type == 'text' || $type == 'number' || $type == 'select' || $type == 'checkbox' || $type == 'radio' ) {
						$newValue = sanitize_text_field( $value );
					} else if ( $type == 'url' ) {
						$newValue = esc_url( $value );
					} else if ( $type == 'slug' ) {
						$newValue = sanitize_title_with_dashes( $value );
					} else if ( $type == 'textarea' ) {
						$newValue = wp_kses_post( $value );
					} else if ( $type == 'custom_css' ) {
						$newValue = esc_textarea( $value );
					} else if ( $type == 'colorpicker' ) {
						$newValue = $this->sanitize_hex_color( $value );
					} else if ( $type == 'image_size' ) {
						$newValue = array();
						foreach ( $value as $k => $v ) {
							$newValue[ $k ] = esc_attr( $v );
						}
					} else if ( $type == 'style' ) {
						$newValue = array();
						foreach ( $value as $k => $v ) {
							if ( $k == 'color' ) {
								$newValue[ $k ] = $this->sanitize_hex_color( $v );
							} else {
								$newValue[ $k ] = $this->sanitize( array( 'type' => 'text' ), $v );
							}
						}
					} else {
						$newValue = sanitize_text_field( $value );
					}

				} else {
					$newValue = array();
					if ( ! empty( $value ) ) {
						if ( is_array( $value ) ) {
							foreach ( $value as $key => $val ) {
								if ( $type == 'style' && $key == 0 ) {
									if ( function_exists( 'sanitize_hex_color' ) ) {
										$newValue = sanitize_hex_color( $val );
									} else {
										$newValue[] = $this->sanitize_hex_color( $val );
									}
								} else {
									$newValue[] = sanitize_text_field( $val );
								}
							}
						} else {
							$newValue[] = sanitize_text_field( $value );
						}
					}
				}
			}

			return $newValue;
		}

		function sanitize_hex_color( $color ) {
			if ( function_exists( 'sanitize_hex_color' ) ) {
				return sanitize_hex_color( $color );
			} else {
				if ( '' === $color ) {
					return '';
				}

				// 3 or 6 hex digits, or the empty string.
				if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
					return $color;
				}
			}
		}

		function rtFieldGeneratorBackup( $fields = array(), $multi = false ) {
			$html = null;
			if ( is_array( $fields ) && ! empty( $fields ) ) {
				$rtField = new rtTPGField();
				if ( $multi ) {
					foreach ( $fields as $field ) {
						$html .= $rtField->Field( $field );
					}
				} else {
					$html .= $rtField->Field( $fields );
				}
			}

			return $html;
		}

		function rtSmartStyle( $fields = array() ) {
			$h = null;
			if ( ! empty( $fields ) ) {
				global $rtTPG;
				foreach ( $fields as $key => $label ) {
					$h .= "<div class='field-holder'>";

					$h .= "<div class='field-label'><label>{$label}</label></div>";
					$h .= "<div class='field'>";
					// color
					$h      .= "<div class='field-inner col-4'>";
					$h      .= "<div class='field-inner-container size'>";
					$h      .= "<span class='label'>Color</span>";
					$cValue = get_post_meta( get_the_ID(), $key . "_color", true );
					$h      .= "<input type='text' value='{$cValue}' class='rt-color' name='{$key}_color'>";
					$h      .= "</div>";
					$h      .= "</div>";

					// Font size
					$h      .= "<div class='field-inner col-4'>";
					$h      .= "<div class='field-inner-container size'>";
					$h      .= "<span class='label'>Font size</span>";
					$h      .= "<select name='{$key}_size' class='rt-select2'>";
					$fSizes = $rtTPG->scFontSize();
					$sValue = get_post_meta( get_the_ID(), $key . "_size", true );
					$h      .= "<option value=''>Default</option>";
					foreach ( $fSizes as $size => $sizeLabel ) {
						$sSlt = ( $size == $sValue ? "selected" : null );
						$h    .= "<option value='{$size}' {$sSlt}>{$sizeLabel}</option>";
					}
					$h .= "</select>";
					$h .= "</div>";
					$h .= "</div>";

					// Weight

					$h       .= "<div class='field-inner col-4'>";
					$h       .= "<div class='field-inner-container weight'>";
					$h       .= "<span class='label'>Weight</span>";
					$h       .= "<select name='{$key}_weight' class='rt-select2'>";
					$h       .= "<option value=''>Default</option>";
					$weights = $rtTPG->scTextWeight();
					$wValue  = get_post_meta( get_the_ID(), $key . "_weight", true );
					foreach ( $weights as $weight => $weightLabel ) {
						$wSlt = ( $weight == $wValue ? "selected" : null );
						$h    .= "<option value='{$weight}' {$wSlt}>{$weightLabel}</option>";
					}
					$h .= "</select>";
					$h .= "</div>";
					$h .= "</div>";

					// Alignment

					$h      .= "<div class='field-inner col-4'>";
					$h      .= "<div class='field-inner-container alignment'>";
					$h      .= "<span class='label'>Alignment</span>";
					$h      .= "<select name='{$key}_alignment' class='rt-select2'>";
					$h      .= "<option value=''>Default</option>";
					$aligns = $rtTPG->scAlignment();
					$aValue = get_post_meta( get_the_ID(), $key . "_alignment", true );
					foreach ( $aligns as $align => $alignLabel ) {
						$aSlt = ( $align == $aValue ? "selected" : null );
						$h    .= "<option value='{$align}' {$aSlt}>{$alignLabel}</option>";
					}
					$h .= "</select>";
					$h .= "</div>";
					$h .= "</div>";

					$h .= "</div>";
					$h .= "</div>";
				}
			}

			return $h;
		}


		function custom_variation_price( $product ) {
			$price = '';

			if ( ! $product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) {
				$price .= woocommerce_price( $product->get_price() );
			}
			if ( $product->max_variation_price && $product->max_variation_price !== $product->min_variation_price ) {
				$price .= woocommerce_price( $product->max_variation_price );
			}

			return $price;
		}

		function getTPGShortCodeList() {
			global $rtTPG;
			$scList = null;
			$scQ    = get_posts( array(
				'post_type'      => $rtTPG->post_type,
				'order_by'       => 'title',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					array(
						'key'     => 'layout',
						'value'   => 'layout',
						'compare' => 'LIKE',
					),
				)
			) );
			if ( ! empty( $scQ ) ) {
				foreach ( $scQ as $sc ) {
					$scList[ $sc->ID ] = $sc->post_title;
				}
			}

			return $scList;
		}

		function getAllTPGShortCodeList() {
			global $rtTPG;
			$scList = null;
			$scQ    = get_posts( array(
				'post_type'      => $rtTPG->post_type,
				'order_by'       => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
				'posts_per_page' => - 1
			) );
			if ( ! empty( $scQ ) ) {
				foreach ( $scQ as $sc ) {
					$scList[ $sc->ID ] = $sc->post_title;
				}
			}

			return $scList;
		}

		function socialShare( $pLink ) {
			$html = null;
			$html .= "<div class='single-tpg-share'>
                        <div class='fb-share'>
                            <div class='fb-share-button' data-href='{$pLink}' data-layout='button_count'></div>
                        </div>
                        <div class='twitter-share'>
                            <a href='{$pLink}' class='twitter-share-button'{count} data-url='https://about.twitter.com/resources/buttons#tweet'>Tweet</a>
                        </div>
                        <div class='googleplus-share'>
                            <div class='g-plusone'></div>
                        </div>
                        <div class='linkedin-share'>
                            <script type='IN/Share' data-counter='right'></script>
                        </div>
                        <div class='linkedin-share'>
                            <a data-pin-do='buttonPin' data-pin-count='beside' href='https://www.pinterest.com/pin/create/button/?url=https%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=https%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest'><img src='//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png' /></a>
                        </div>
                   </div>";
			$html .= '<div id="fb-root"></div>
            <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, "script", "facebook-jssdk"));</script>';
			$html .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <script>window.___gcfg = { lang: 'en-US', parsetags: 'onload', };</script>";
			$html .= "<script src='https://apis.google.com/js/platform.js' async defer></script>";
			$html .= '<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>';
			$html .= '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';

			return $html;
		}

		function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes      = array();
			$interSizes = get_intermediate_image_sizes();
			if ( ! empty( $interSizes ) ) {
				foreach ( get_intermediate_image_sizes() as $_size ) {
					if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
						$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
						$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
						$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
					} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
						$sizes[ $_size ] = array(
							'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
							'height' => $_wp_additional_image_sizes[ $_size ]['height'],
							'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
						);
					}
				}
			}

			$imgSize = array();
			if ( ! empty( $sizes ) ) {
				foreach ( $sizes as $key => $img ) {
					$imgSize[ $key ] = ucfirst( $key ) . " ({$img['width']}*{$img['height']})";
				}
			}
			$imgSize['rt_custom'] = "Custom image size";

			return $imgSize;
		}

		function getFeatureImageSrc(
			$post_id = null,
			$fImgSize = 'medium',
			$mediaSource = 'feature_image',
			$defaultImgId = null,
			$customImgSize = array()
		) {
			$imgSrc = null;
			$cSize  = false;
			if ( $fImgSize == 'rt_custom' ) {
				$fImgSize = 'full';
				$cSize    = true;
			}

			if ( $mediaSource == 'feature_image' ) {
				if ( $aID = get_post_thumbnail_id( $post_id ) ) {
					$image  = wp_get_attachment_image_src( $aID, $fImgSize );
					$imgSrc = $image[0];
				}
			} else if ( $mediaSource == 'first_image' ) {
				if ( $img = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content( $post_id ),
					$matches )
				) {
					$imgSrc = $matches[1][0];
				}
			}
			if ( ! $imgSrc && $defaultImgId ) {
				$image  = wp_get_attachment_image_src( $defaultImgId, $fImgSize );
				$imgSrc = $image[0];
			}
			if ( $imgSrc && $cSize ) {
				global $rtTPG;
				$w = ( ! empty( $customImgSize[0] ) ? absint( $customImgSize[0] ) : null );
				$h = ( ! empty( $customImgSize[1] ) ? absint( $customImgSize[1] ) : null );
				$c = ( ! empty( $customImgSize[2] ) && $customImgSize[2] == 'soft' ? false : true );
				if ( $w && $h ) {
					$imgSrc = $rtTPG->rtImageReSize( $imgSrc, $w, $h, $c );
				}
			}

			return $imgSrc;
		}

		function strip_tags_content( $excerpt, $type = "character", $limit = 0, $more = null ) {
			$type       = ( $type == "character" || $type == "word" ? $type : "character" );
			$excerpt    = preg_replace( '`\[[^\]]*\]`', '', $excerpt );
			$excerpt    = strip_shortcodes( $excerpt );
			$excerpt    = preg_replace( '`[[^]]*]`', '', $excerpt );
			$excerpt    = str_replace( '…', '', $excerpt );

			if ( $limit ) {
				$excerpt    = wp_filter_nohtml_kses( $excerpt );
				$oldExcerpt = $excerpt;
				if ( $type == "word" ) {
					$limit   = $limit + 1;
					$excerpt = explode( ' ', $excerpt, $limit );
					if ( count( $excerpt ) >= $limit ) {
						array_pop( $excerpt );
						$excerpt = implode( " ", $excerpt );
					} else {
						$excerpt = $oldExcerpt;
					}
				} else {
					if ( $limit > 0 && strlen( $excerpt ) > $limit ) {
						$excerpt = substr( $excerpt, 0, $limit );
						$excerpt = preg_replace( '/\W\w+\s*(\W*)$/', '$1', $excerpt );
					}
				}
			}else{
				$allowed_html = array(
					'a' => array(
						'href' => array(),
						'title' => array()
					),
					'strong' => array(),
					'b' => array(),
					'br' => array(array()),
				);
				$excerpt = nl2br(wp_kses($excerpt, $allowed_html));
			}

			$excerpt = ( $more ? $excerpt . " " . $more : $excerpt );

			return $excerpt;
		}


		function rt_pagination( $postGrid, $range = 4, $ajax = false ) {

			$html      = $pages = null;
			$showitems = ( $range * 2 ) + 1;

			$wpQuery = $postGrid;
			if ( empty( $wpQuery ) ) {
				global $wp_query;
				$wpQuery = $wp_query;
			}
			$pages = ! empty( $wpQuery->max_num_pages ) ? $wpQuery->max_num_pages : 1;
			$paged = ! empty( $wpQuery->query['paged'] ) ? $wpQuery->query['paged'] : 1;

			$ajaxClass = null;
			$dataAttr  = null;

			if ( $ajax ) {
				$ajaxClass = ' rt-ajax';
				$dataAttr  = "data-paged='1'";
			}

			if ( 1 != $pages ) {

				$html .= '<div class="rt-pagination' . $ajaxClass . '" ' . $dataAttr . '>';
				$html .= '<ul class="pagination-list">';
				if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages && ! $ajax ) {
					$html .= "<li><a data-paged='1' href='" . get_pagenum_link( 1 ) . "' aria-label='First'>&laquo;</a></li>";
				}

				if ( $paged > 1 && $showitems < $pages && ! $ajax ) {
					$p    = $paged - 1;
					$html .= "<li><a data-paged='{$p}' href='" . get_pagenum_link( $p ) . "' aria-label='Previous'>&lsaquo;</a></li>";
				}

				if ( $ajax ) {
					for ( $i = 1; $i <= $pages; $i ++ ) {
						$html .= ( $paged == $i ) ? "<li class=\"active\"><span>" . $i . "</span>

    </li>" : "<li><a data-paged='{$i}' href='" . get_pagenum_link( $i ) . "'>" . $i . "</a></li>";
					}
				} else {

					for ( $i = 1; $i <= $pages; $i ++ ) {
						if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
							$html .= ( $paged == $i ) ? "<li class=\"active\"><span>" . $i . "</span>

    </li>" : "<li><a data-paged='{$i}' href='" . get_pagenum_link( $i ) . "'>" . $i . "</a></li>";

						}

					}
				}

				if ( $paged < $pages && $showitems < $pages && ! $ajax ) {
					$p    = $paged + 1;
					$html .= "<li><a data-paged='{$p}' href=\"" . get_pagenum_link( $paged + 1 ) . "\"  aria-label='Next'>&rsaquo;</a></li>";
				}

				if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages && ! $ajax ) {
					$html .= "<li><a data-paged='{$pages}' href='" . get_pagenum_link( $pages ) . "' aria-label='Last'>&raquo;</a></li>";
				}

				$html .= "</ul>";
				$html .= "</div>";
			}

			return $html;

		}


		function rt_pagination_ajax( $pages = '', $range = 4, $scID ) {
			$html = null;


			$html .= "<div class='rt-tpg-pagination-ajax' data-sc-id='{$scID}' data-paged='1'>";

			$html .= "</div>";

			return $html;
		}

		/**
		 *
		 * Call the Image resize model for resize function
		 *
		 * @param $url
		 * @param null $width
		 * @param null $height
		 * @param null $crop
		 * @param bool|true $single
		 * @param bool|false $upscale
		 *
		 * @return array|bool|string
		 * @throws Exception
		 * @throws Rt_Exception
		 */
		function rtImageReSize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
			$rtResize = new rtTPGReSizer();

			return $rtResize->process( $url, $width, $height, $crop, $single, $upscale );
		}


		/* Convert hexdec color string to rgb(a) string */
		function rtHex2rgba( $color, $opacity = .5 ) {

			$default = 'rgb(0,0,0)';

			//Return default if no color provided
			if ( empty( $color ) ) {
				return $default;
			}

			//Sanitize $color if "#" is provided
			if ( $color[0] == '#' ) {
				$color = substr( $color, 1 );
			}

			//Check if color has 6 or 3 characters and get values
			if ( strlen( $color ) == 6 ) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
				return $default;
			}

			//Convert hexadec to rgb
			$rgb = array_map( 'hexdec', $hex );

			//Check if opacity is set(rgba or rgb)
			if ( $opacity ) {
				if ( abs( $opacity ) > 1 ) {
					$opacity = 1.0;
				}
				$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
			} else {
				$output = 'rgb(' . implode( ",", $rgb ) . ')';
			}

			//Return rgb(a) color string
			return $output;
		}

		function rtShare( $pid ) {
			if ( ! $pid ) {
				return;
			}
			global $rtTPG;
			$settings  = get_option( $rtTPG->options['settings'] );
			$ssList    = ! empty( $settings['social_share_items'] ) ? $settings['social_share_items'] : array();
			$permalink = get_the_permalink( $pid );
			$html      = null;

			if ( in_array( 'facebook', $ssList ) ) {
				$html .= "<a class='facebook' title='" . __( "Share on facebook",
						"the-post-grid-pro" ) . "' target='_blank' href='https://www.facebook.com/sharer/sharer.php?u={$permalink}'><i class='fa fa-facebook' aria-hidden='true'></i></a>";
			}
			if ( in_array( 'twitter', $ssList ) ) {
				$html .= "<a class='twitter' title='" . __( "Share on twitter",
						"the-post-grid-pro" ) . "' target='_blank' href='http://www.twitter.com/intent/tweet?url={$permalink}'><i class='fa fa-twitter' aria-hidden='true'></i></a>";
			}
			if ( in_array( 'google-plus', $ssList ) ) {
				$html .= "<a class='google-plus' title='" . __( "Share on google+",
						"the-post-grid-pro" ) . "' target='_blank' href='https://plus.google.com/share?url={$permalink}'><i class='fa fa-google-plus' aria-hidden='true'></i></a>";
			}
			if ( in_array( 'linkedin', $ssList ) ) {
				$html .= "<a class='linkedin' title='" . __( "Share on linkedin",
						"the-post-grid-pro" ) . "' target='_blank' href='https://www.linkedin.com/shareArticle?mini=true&url={$permalink}'><i class='fa fa-linkedin' aria-hidden='true'></i></a>";
			}
			if ( in_array( 'pinterest', $ssList ) ) {
				$html .= "<a class='pinterest' title='" . __( "Share on pinterest",
						"the-post-grid-pro" ) . "' target='_blank' href='https://pinterest.com/pin/create/button/?url={$permalink}'><i class='fa fa-pinterest' aria-hidden='true'></i></a>";
			}

			if ( $html ) {
				$html = "<span class='rt-tpg-social-share'>{$html}</span>";
			}

			return $html;
		}


		function doFlush() {
			global $rtTPG;
			if ( get_option( $rtTPG->options['flash'] ) ) {
				$rtTPG->flush_rewrite();
				update_option( $rtTPG->options['flash'], false );
			}
		}

		function meta_exist( $post_id = null, $meta_key, $type = "post" ) {
			if ( ! $post_id ) {
				return false;
			}

			return metadata_exists( $type, $post_id, $meta_key );
		}


		function get_offset_col( $col ) {
			$return = array(
				'big'   => 6,
				'small' => 6
			);
			if ( $col ) {
				if ( $col == 12 ) {
					$return['big']   = 12;
					$return['small'] = 12;
				} elseif ( $col == 6 ) {
					$return['big']   = 6;
					$return['small'] = 6;
				} elseif ( $col == 4 ) {
					$return['big']   = 4;
					$return['small'] = 8;
				}
			}

			return $return;
		}


		public function layoutStyle( $layoutID, $scMeta, $layout, $scId = null ) {
			global $rtTPG;
			$css = null;
			$css .= "<style type='text/css' media='all'>";
			// primary color
			if ( $scId ) {
				$primaryColor            = ( isset( $scMeta['primary_color'][0] ) ? $scMeta['primary_color'][0] : null );
				$button_bg_color         = ( isset( $scMeta['button_bg_color'][0] ) ? $scMeta['button_bg_color'][0] : null );
				$button_active_bg_color  = ( isset( $scMeta['button_active_bg_color'][0] ) ? $scMeta['button_active_bg_color'][0] : null );
				$button_hover_bg_color   = ( isset( $scMeta['button_hover_bg_color'][0] ) ? $scMeta['button_hover_bg_color'][0] : null );
				$button_text_color       = ( isset( $scMeta['button_text_bg_color'][0] ) ? $scMeta['button_text_bg_color'][0] : null );
				$button_hover_text_color = ( isset( $scMeta['button_hover_text_color'][0] ) ? $scMeta['button_hover_text_color'][0] : null );
				$button_border_color     = ( isset( $scMeta['button_border_color'][0] ) ? $scMeta['button_border_color'][0] : null );
				$overlay_color           = ( ! empty( $scMeta['overlay_color'][0] ) ? $rtTPG->rtHex2rgba( $scMeta['overlay_color'][0],
					! empty( $scMeta['overlay_opacity'][0] ) ? absint( $scMeta['overlay_opacity'][0] ) / 10 : .8 ) : null );
				$overlay_padding         = ( ! empty( $scMeta['overlay_padding'][0] ) ? absint( $scMeta['overlay_padding'][0] ) : null );
				$gutter                  = ! empty( $scMeta['tgp_gutter'][0] ) ? absint( $scMeta['tgp_gutter'][0] ) : null;


				$title_color     = ( ! empty( $scMeta['title_color'][0] ) ? $scMeta['title_color'][0] : null );
				$title_size      = ( ! empty( $scMeta['title_size'][0] ) ? absint( $scMeta['title_size'][0] ) : null );
				$title_weight    = ( ! empty( $scMeta['title_weight'][0] ) ? $scMeta['title_weight'][0] : null );
				$title_alignment = ( ! empty( $scMeta['title_alignment'][0] ) ? $scMeta['title_alignment'][0] : null );


				$title_hover_color = ( ! empty( $scMeta['title_hover_color'][0] ) ? $scMeta['title_hover_color'][0] : null );


				$excerpt_color     = ( ! empty( $scMeta['excerpt_color'][0] ) ? $scMeta['excerpt_color'][0] : null );
				$excerpt_size      = ( ! empty( $scMeta['excerpt_size'][0] ) ? absint( $scMeta['excerpt_size'][0] ) : null );
				$excerpt_weight    = ( ! empty( $scMeta['excerpt_weight'][0] ) ? $scMeta['excerpt_weight'][0] : null );
				$excerpt_alignment = ( ! empty( $scMeta['excerpt_alignment'][0] ) ? $scMeta['excerpt_alignment'][0] : null );


				$meta_data_color     = ( ! empty( $scMeta['meta_data_color'][0] ) ? $scMeta['meta_data_color'][0] : null );
				$meta_data_size      = ( ! empty( $scMeta['meta_data_size'][0] ) ? absint( $scMeta['meta_data_size'][0] ) : null );
				$meta_data_weight    = ( ! empty( $scMeta['meta_data_weight'][0] ) ? $scMeta['meta_data_weight'][0] : null );
				$meta_data_alignment = ( ! empty( $scMeta['meta_data_alignment'][0] ) ? $scMeta['meta_data_alignment'][0] : null );

			} else {
				$primaryColor            = ( isset( $scMeta['primary_color'] ) ? $scMeta['primary_color'] : null );
				$button_bg_color         = ( isset( $scMeta['button_bg_color'] ) ? $scMeta['button_bg_color'] : null );
				$button_active_bg_color  = ( isset( $scMeta['button_active_bg_color'] ) ? $scMeta['button_active_bg_color'] : null );
				$button_hover_bg_color   = ( isset( $scMeta['button_hover_bg_color'] ) ? $scMeta['button_hover_bg_color'] : null );
				$button_text_color       = ( isset( $scMeta['button_text_color'] ) ? $scMeta['button_text_color'] : null );
				$button_border_color     = ( isset( $scMeta['button_border_color'] ) ? $scMeta['button_border_color'] : null );
				$button_hover_text_color = ( isset( $scMeta['button_hover_text_color'] ) ? $scMeta['button_hover_text_color'] : null );
				$overlay_color           = ( ! empty( $scMeta['overlay_color'] ) ? $rtTPG->rtHex2rgba( $scMeta['overlay_color'],
					! empty( $scMeta['overlay_opacity'] ) ? absint( $scMeta['overlay_opacity'] ) / 10 : .8 ) : null );
				$overlay_padding         = ( ! empty( $scMeta['overlay_padding'] ) ? absint( $scMeta['overlay_padding'] ) : null );
				$gutter                  = ! empty( $scMeta['tgp_gutter'] ) ? absint( $scMeta['tgp_gutter'] ) : null;


				$title_color     = ( ! empty( $scMeta['title_color'] ) ? $scMeta['title_color'] : null );
				$title_size      = ( ! empty( $scMeta['title_size'] ) ? absint( $scMeta['title_size'] ) : null );
				$title_weight    = ( ! empty( $scMeta['title_weight'] ) ? $scMeta['title_weight'] : null );
				$title_alignment = ( ! empty( $scMeta['title_alignment'] ) ? $scMeta['title_alignment'] : null );

				$title_hover_color = ( ! empty( $scMeta['title_hover_color'] ) ? $scMeta['title_hover_color'] : null );


				$excerpt_color     = ( ! empty( $scMeta['excerpt_color'] ) ? $scMeta['excerpt_color'] : null );
				$excerpt_size      = ( ! empty( $scMeta['excerpt_size'] ) ? absint( $scMeta['excerpt_size'] ) : null );
				$excerpt_weight    = ( ! empty( $scMeta['excerpt_weight'] ) ? $scMeta['excerpt_weight'] : null );
				$excerpt_alignment = ( ! empty( $scMeta['excerpt_alignment'] ) ? $scMeta['excerpt_alignment'] : null );


				$meta_data_color     = ( ! empty( $scMeta['meta_data_color'] ) ? $scMeta['meta_data_color'] : null );
				$meta_data_size      = ( ! empty( $scMeta['meta_data_size'] ) ? absint( $scMeta['meta_data_size'] ) : null );
				$meta_data_weight    = ( ! empty( $scMeta['meta_data_weight'] ) ? $scMeta['meta_data_weight'] : null );
				$meta_data_alignment = ( ! empty( $scMeta['meta_data_alignment'] ) ? $scMeta['meta_data_alignment'] : null );
			}


			$id = str_replace( 'rt-tpg-container-', '', $layoutID );
			if ( $primaryColor ) {
				$css .= "#{$layoutID} .rt-holder .rt-woo-info .price{";
				$css .= "color:" . $primaryColor . ";";
				$css .= "}";
				$css .= "body .rt-tpg-container .rt-tpg-isotope-buttons .selected, 
						#{$layoutID} .layout12 .rt-holder:hover .rt-detail, 
						#{$layoutID} .isotope8 .rt-holder:hover .rt-detail, 
						#{$layoutID} .carousel8 .rt-holder:hover .rt-detail,
				        #{$layoutID} .layout13 .rt-holder .overlay .post-info, 
				        #{$layoutID} .isotope9 .rt-holder .overlay .post-info, 
				        #{$layoutID}.rt-tpg-container .layout4 .rt-holder .rt-detail, 
				        .rt-modal-{$id} .md-content, 
				        .rt-modal-{$id} .md-content > .rt-md-content-holder .rt-md-content, 
				        .rt-popup-wrap-{$id}.rt-popup-wrap .rt-popup-navigation-wrap, 
				        #{$layoutID} .carousel9 .rt-holder .overlay .post-info{";
				$css .= "background-color:" . $primaryColor . ";";
				$css .= "}";
				$ocp = $rtTPG->rtHex2rgba( $primaryColor,
					! empty( $scMeta['overlay_opacity'][0] ) ? absint( $scMeta['overlay_opacity'][0] ) / 10 : .8 );
				$css .= "#{$layoutID} .layout5 .rt-holder .overlay, #{$layoutID} .isotope2 .rt-holder .overlay, #{$layoutID} .carousel2 .rt-holder .overlay,#{$layoutID} .layout15 .rt-holder h3, #{$layoutID} .isotope11 .rt-holder h3, #{$layoutID} .carousel11 .rt-holder h3, #{$layoutID} .layout16 .rt-holder h3,
					#{$layoutID} .isotope12 .rt-holder h3, #{$layoutID} .carousel12 .rt-holder h3 {";
				$css .= "background-color:" . $ocp . ";";
				$css .= "}";

			}

			// Button color
			if ( $button_border_color ) {
				$css .= "#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,
							#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item,
							#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-sort-order-action,
							#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item,
							#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap{";
				$css .= "border-color:" . $button_border_color . ";";
				$css .= "}";
			}
			if ( $button_bg_color ) {
				$css .= "#{$layoutID} .pagination li a,
							#{$layoutID} .rt-tpg-isotope-buttons button, 
							#{$layoutID} .rt-holder .read-more,
							#{$layoutID} .rt-tpg-utility .rt-tpg-load-more button,
							#{$layoutID} .owl-theme .owl-controls .owl-nav > div,
							#{$layoutID} .owl-theme .owl-controls .owl-dots .owl-dot span,
							#{$layoutID} .wc1 .rt-holder .rt-img-holder .overlay .product-more ul li a,
							#{$layoutID} .wc2 .rt-detail .rt-wc-add-to-cart,
							#{$layoutID} .wc3 .rt-detail .rt-wc-add-to-cart,
							#{$layoutID} .wc4 .rt-detail .rt-wc-add-to-cart,
							#{$layoutID} .wc-carousel2 .rt-detail .rt-wc-add-to-cart,
							#{$layoutID} .wc-isotope2 .rt-detail .rt-wc-add-to-cart,
							#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown,
							#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item,
							#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li>a,
							#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,
							#{$layoutID}.rt-tpg-container .rt-pagination-wrap  .rt-loadmore-btn,
							#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-cb-page-prev-next > *,
							#rt-tooltip-{$id}, #rt-tooltip-{$id} .rt-tooltip-bottom:after{";
				$css .= "background-color:" . $button_bg_color . ";";
				$css .= "}";
				$css .= "#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,
						#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item{";
				$css .= "border-color:" . $button_bg_color . ";";
				$css .= "}";
				$css .= "#{$layoutID}.rt-tpg-container .layout17 .rt-holder .overlay a.tpg-zoom .fa{";
				$css .= "color:" . $button_bg_color . ";";
				$css .= "}";
			}


			// button active color
			if ( $button_active_bg_color ) {
				$css .= "#{$layoutID} .pagination li.active span, 
						#{$layoutID} .rt-tpg-isotope-buttons button.selected,
						#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item.selected, 
						#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item.selected,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active>a, 
						#{$layoutID} .owl-theme .owl-controls .owl-dots .owl-dot.active span{";
				$css .= "background-color:" . $button_active_bg_color . ";";
				$css .= "}";

				$css .= "#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item.selected,
						#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item.selected,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active>a{";
				$css .= "border-color:" . $button_active_bg_color . ";";
				$css .= "}";
			}

			// Button hover bg color
			if ( $button_hover_bg_color ) {
				$css .= "#{$layoutID} .pagination li a:hover,
						#{$layoutID} .rt-tpg-isotope-buttons button:hover,
						#{$layoutID} .rt-holder .read-more:hover, 
						#{$layoutID} .rt-tpg-utility .rt-tpg-load-more button:hover,
						#{$layoutID} .owl-theme .owl-controls .owl-dots .owl-dot:hover span,
						#{$layoutID} .owl-theme .owl-controls .owl-nav > div:hover,
						#{$layoutID} .wc1 .rt-holder .rt-img-holder .overlay .product-more ul li a:hover,
						#{$layoutID} .wc2 .rt-detail .rt-wc-add-to-cart:hover,
						#{$layoutID} .wc3 .rt-detail .rt-wc-add-to-cart:hover,
						#{$layoutID} .wc4 .rt-detail .rt-wc-add-to-cart:hover,
						#{$layoutID} .wc-carousel2 .rt-detail .rt-wc-add-to-cart:hover,
						#{$layoutID} .wc-isotope2 .rt-detail .rt-wc-add-to-cart:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item:hover,
						#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item:hover,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li>a:hover,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-cb-page-prev-next > *:hover,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap  .rt-loadmore-btn:hover,
						#{$layoutID} .rt-tpg-utility .rt-tpg-load-more button:hover{";
				$css .= "background-color:" . $button_hover_bg_color . ";";
				$css .= "}";

				$css .= "#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item:hover,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li>a:hover{";
				$css .= "border-color:" . $button_hover_bg_color . ";";
				$css .= "}";
				$css .= "#{$layoutID}.rt-tpg-container .layout17 .rt-holder .overlay a.tpg-zoom:hover .fa{";
				$css .= "color:" . $button_hover_bg_color . ";";
				$css .= "}";
			}


			//Button text color
			if ( $button_text_color ) {
				$css .= "#{$layoutID} .pagination li a,
				#{$layoutID} .rt-tpg-isotope-buttons button,
				#{$layoutID} .rt-holder .read-more a,
				#{$layoutID} .rt-tpg-utility .rt-tpg-load-more button, 
				#{$layoutID} .owl-theme .owl-controls .owl-nav > div,
				#{$layoutID} .wc1 .rt-holder .rt-img-holder .overlay .product-more ul li a,
				#{$layoutID} .wc2 .rt-detail .rt-wc-add-to-cart,
				#{$layoutID} .wc3 .rt-detail .rt-wc-add-to-cart,
				#{$layoutID} .wc4 .rt-detail .rt-wc-add-to-cart,
				#{$layoutID} .wc-carousel2 .rt-detail .rt-wc-add-to-cart,
				#{$layoutID} .wc-isotope2 .rt-detail .rt-wc-add-to-cart,
				#{$layoutID} .rt-tpg-utility .rt-tpg-load-more button,
				#rt-tooltip-{$id}, 
				#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,
				#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item,
				#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-sort-order-action,
				#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item,
				#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li>a,
				#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-cb-page-prev-next > *,
				#{$layoutID}.rt-tpg-container .rt-pagination-wrap  .rt-loadmore-btn,
				#rt-tooltip-{$id} .rt-tooltip-bottom:after{";
				$css .= "color:" . $button_text_color . ";";
				$css .= "}";

			}

			if ( $button_hover_text_color ) {
				$css .= "#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-sub-tax.sub-button-group .rt-filter-button-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-sort-order-action:hover,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active>a:hover,
						#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item.selected,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-sort-order-action,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap  .rt-loadmore-btn:hover,
						#{$layoutID}.rt-tpg-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active>a{";
				$css .= "color:" . $button_hover_text_color . ";";
				$css .= "}";

			}

			if ( $overlay_color || $overlay_padding ) {
				if ( in_array( $layout, array( 'layout15', 'isotope11', 'carousel11' ) ) ) {
					$css .= "#{$layoutID} .{$layout} .rt-holder:hover .overlay .post-info{";
				} else if ( in_array( $layout,
					array( 'layout10', 'isotope7', 'carousel6', 'carousel7', 'layout9', 'offset04' ) ) ) {
					$css .= "#{$layoutID} .{$layout} .rt-holder .post-info{";
				} else if ( in_array( $layout, array( 'layout7', 'isotope4', 'carousel4' ) ) ) {
					$css .= "#{$layoutID} .{$layout} .rt-holder .overlay:hover{";
				} else if ( in_array( $layout, array( 'layout16', 'isotope12', 'carousel12' ) ) ) {
					$css .= "#{$layoutID} .{$layout} .rt-holder .overlay .post-info {";
				} else if ( in_array( $layout, array( 'offset03', 'carousel5' ) ) ) {
					$css .= "#{$layoutID} .{$layout} .rt-holder .overlay{";
				} else {
					$css .= "#{$layoutID} .rt-holder .overlay:hover{";
				}
				if ( $overlay_color ) {
					$css .= "background-color:" . $overlay_color . ";";
				}
				if ( $overlay_padding ) {
					$css .= "padding-top:" . $overlay_padding . "%;";
				}
				$css .= "}";
			}

			/* gutter */
			if ( $gutter ) {
				$css .= "#{$layoutID} [class*='rt-col-'] {";
				$css .= "padding-left : {$gutter}px;";
				$css .= "padding-right : {$gutter}px;";
				$css .= "margin-top : {$gutter}px;";
				$css .= "margin-bottom : {$gutter}px;";
				$css .= "}";
				$css .= "#{$layoutID} .rt-row{";
				$css .= "margin-left : -{$gutter}px;";
				$css .= "margin-right : -{$gutter}px;";
				$css .= "}";
				$css .= "#{$layoutID}.rt-container-fluid,#{$layoutID}.rt-container{";
				$css .= "padding-left : {$gutter}px;";
				$css .= "padding-right : {$gutter}px;";
				$css .= "}";
			}

			// Title decoration

			if ( $title_color || $title_size || $title_weight || $title_alignment ) {
				$css .= "#{$layoutID} .{$layout} .rt-holder h3.entry-title,#{$layoutID} .{$layout} .rt-holder h3.entry-title a,#{$layoutID} .rt-holder .rt-woo-info h3 a,#{$layoutID} .rt-holder .rt-woo-info h3{";
				if ( $title_color ) {
					$css .= "color:" . $title_color . ";";
				}
				if ( $title_size ) {
					$css .= "font-size:" . $title_size . "px;";
				}
				if ( $title_weight ) {
					$css .= "font-weight:" . $title_weight . ";";
				}
				if ( $title_alignment ) {
					$css .= "text-align:" . $title_alignment . ";";
				}
				$css .= "}";
			}

			// Title hover color
			if ( $title_hover_color ) {
				$css .= "#{$layoutID} .{$layout} .rt-holder h3.entry-title:hover,
						#{$layoutID} .{$layout} .rt-holder h3.entry-title a:hover,
						#{$layoutID} .rt-holder .rt-woo-info h3 a:hover,
						#{$layoutID} .rt-holder .rt-woo-info h3:hover{";
				$css .= "color:" . $title_hover_color . " !important;";
				$css .= "}";
			}

			// Excerpt decoration
			if ( $excerpt_color || $excerpt_size || $excerpt_weight || $excerpt_alignment ) {
				$css .= "#{$layoutID} .{$layout} .rt-holder .entry-content,#{$layoutID} .rt-holder .rt-woo-info p{";
				if ( $excerpt_color ) {
					$css .= "color:" . $excerpt_color . ";";
				}
				if ( $excerpt_size ) {
					$css .= "font-size:" . $excerpt_size . "px;";
				}
				if ( $excerpt_weight ) {
					$css .= "font-weight:" . $excerpt_weight . ";";
				}
				if ( $excerpt_alignment ) {
					$css .= "text-align:" . $excerpt_alignment . ";";
				}
				$css .= "}";
			}

			// Post meta decoration
			if ( $meta_data_color || $meta_data_size || $meta_data_weight || $meta_data_alignment ) {
				if ( $meta_data_color ) {
					$css .= "#{$layoutID} .rt-detail i{";
					$css .= "color:" . $meta_data_color . ";";
					$css .= "}";
				}

				$css .= "#{$layoutID} .{$layout} .rt-holder .post-meta-user, #{$layoutID} .{$layout} .rt-holder .post-meta-user a{";
				if ( $meta_data_color ) {
					$css .= "color:" . $meta_data_color . ";";
				}
				if ( $meta_data_size ) {
					$css .= "font-size:" . $meta_data_size . "px;";
				}
				if ( $meta_data_weight ) {
					$css .= "font-weight:" . $meta_data_weight . ";";
				}
				if ( $meta_data_alignment ) {
					$css .= "text-align:" . $meta_data_alignment . ";";
				}
				$css .= "}";
			}

			$css .= "</style>";

			return $css;
		}

		function rtProductGalleryImages() {
			$gallery = null;
			global $post, $product;
			$thumb_id       = get_post_thumbnail_id( $post->ID );
			$attachment_ids = $product->get_gallery_image_ids();
			if ( $thumb_id ) {
				array_unshift( $thumb_id, $attachment_ids );
			}

			if ( $attachment_ids && ! empty( $attachment_ids ) ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
					$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
					$thumbnail_post  = get_post( $attachment_id );
					$image_title     = $thumbnail_post->post_content;

					$attributes = array(
						'title'                   => $image_title,
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
					);

					$html = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="rt-product-img"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
					$html .= '</a></div>';

					$gallery .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html,
						$attachment_id );
				}
			}
			$galleryClass = 'hasImg';
			if ( ! $gallery ) {
				$galleryClass = "haNoImg";
				$gallery      = '<div class="rt-product-img-placeholder">';
				$gallery      .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />',
					esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
				$gallery      .= '</div>';
			}
			$a       = explode( ',', $attachment_ids );
			$gallery = "<div id='rt-product-gallery' class='{$galleryClass}'>{$gallery}</div>{$a}";

			return $gallery;
		}

		function get_meta_keys( $post_type ) {
//			$cache     = get_transient( 'tpg_' . $post_type . '_meta_keys' );
//			$meta_keys = $cache ? $cache : $this->generate_meta_keys( $post_type );
			$meta_keys = $this->generate_meta_keys( $post_type );

			return $meta_keys;
		}

		function generate_meta_keys( $post_type ) {
			$meta_keys = array();
			if ( $post_type ) {
				global $wpdb;
				$query     = "SELECT DISTINCT($wpdb->postmeta.meta_key) 
			        FROM $wpdb->posts 
			        LEFT JOIN $wpdb->postmeta 
			        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
			        WHERE $wpdb->posts.post_type = '%s' 
			        AND $wpdb->postmeta.meta_key != '' 
			        AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
			        AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'";
				$meta_keys = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );
//				set_transient( 'tpg_' . $post_type . '_meta_keys', $meta_keys, 60 * 60 * 24 ); # create 1 Day Expiration
			}

			return $meta_keys;
		}

		function remove_all_shortcode( $content ) {
			return preg_replace( '#\[[^\]]+\]#', '', $content );
		}

		function remove_divi_shortcodes( $content ) {
			$content = preg_replace( '/\[\/?et_pb.*?\]/', '', $content );

			return $content;
		}

		function checkWhichCustomMetaPluginIsInstalled() {
			$plugin = null;
			if ( class_exists( 'acf' ) ) {
				$plugin = 'acf';
			}

			return $plugin;
		}

		function get_groups_by_post_type( $post_type ) {
			$post_type = $post_type ? $post_type : "post";
			$groups    = array();
			$plugin    = $this->checkWhichCustomMetaPluginIsInstalled();
			switch ( $plugin ) {
				case 'acf':
					$groups = $this->get_groups_by_post_type_acf( $post_type );
					break;
			}

			return $groups;
		}

		function get_cf_formatted_fields( $groups, $format = array(), $post_id = null ) {
			$html = null;
			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group_id ) {
					$plugin = $this->checkWhichCustomMetaPluginIsInstalled();
					$fields = array();
					switch ( $plugin ) {
						case 'acf':
							$fields = $this->get_all_fields_by_group_id_acf( $group_id );
							break;
					}
					if ( ! empty( $fields ) ) {
						$titleHtml = $returnHtml = null;
						if ( empty( $format['hide_group_title'] ) ) {
							$title     = get_the_title( $group_id );
							$titleHtml = "<h4 class='tpg-cf-group-title'>{$title}</h4>";
						}
						foreach ( $fields as $field ) {
							$item  = $htmlValue = $htmlLabel = null;
							$value = get_field( $field['name'], $post_id );
							if ( $value ) {
								switch ( $field['type'] ) {
									case 'image':
										$value = "<img src='{$value['sizes']['thumbnail']}' />";
										break;
									case 'select':
										if ( ! empty( $field['choices'] ) ) {
											if ( is_array( $value ) ) {
												$nValue = array();
												foreach ( $value as $v ) {
													$nValue[] = $field['choices'][ $v ];
												}
												$value = implode( ', ', $nValue );
											} else {
												$value = $field['choices'][ $value ];
											}
										}
										break;
									case 'checkbox':
										if ( ! empty( $field['choices'] ) ) {
											if ( is_array( $value ) ) {
												$nValue = array();
												foreach ( $value as $v ) {
													$nValue[] = $field['choices'][ $v ];
												}
												$value = implode( ', ', $nValue );
											} else {
												$value = $field['choices'][ $value ];
											}
										}
										break;
									case 'radio':
										if ( ! empty( $field['choices'] ) ) {
											$value = $field['choices'][ $value ];
										}
										break;
									case 'true_false':
										$value = $value ? 1 : 0;
										break;
									case 'date_picker':
										$date        = new DateTime( $value );
										$date_format = get_option( 'date_format' );
										$date_format = $date_format ? $date_format : 'j M Y';
										$value       = $date->format( $date_format );
										break;
									case 'color_picker':
										$value = "<div class='tpg-cf-color' style='height:25px;width:25px;background:{$value};'></div>";
										break;
									case 'file':
										$value = "<a href='{$value['url']}'>" . __( "Download",
												'the-post-grid-pro' ) . " {$field['label']}</a>";
										break;
									default:
										break;
								}
							}

							$htmlLabel = "<span class='tgp-cf-field-label'>{$field['label']}</span>";
							$htmlValue = "<div class='tgp-cf-field-value'>{$value}</div>";
							$item      .= "<div class='tpg-cf-fields tgp-cf-{$plugin}-{$field['type']}'>";
							if ( ! empty( $format['show_value'] ) ) {
								$item .= $htmlValue;
							} else {
								$item .= $htmlLabel;
								$item .= $htmlValue;
							}
							$item .= "</div>";
							if ( ! empty( $format['hide_empty'] ) ) {
								if ( $value ) {
									$returnHtml .= $item;
								}
							} else {
								$returnHtml .= $item;
							}
						}

						$html .= "<div class='tpg-cf-wrap'>{$titleHtml}{$returnHtml}</div>";

					}
				}

			}

			return $html;
		}

		function get_groups_by_post_type_cpt( $post_type ) {

		}

		function get_groups_by_post_type_acf( $post_type ) {
			$groups = array();
			if ( class_exists( 'acf_pro' ) ) {
				$groups_q = get_posts( array( 'post_type' => 'acf-field-group', 'posts_per_page' => - 1 ) );
				if ( ! empty( $groups_q ) ) {
					foreach ( $groups_q as $group ) {
						$c    = $group->post_content ? unserialize( $group->post_content ) : array();
						$flag = false;
						if ( ! empty( $c['location'] ) ) {
							foreach ( $c['location'] as $rules ) {
								foreach ( $rules as $rule ) {
									if ( $post_type === 'all' ) {
										if ( ( ! empty( $rule['param'] ) && $rule['param'] == 'post_type' )
										     && ( ! empty( $rule['operator'] ) && $rule['operator'] == '==' )
										) {
											$flag = true;
										}
									} else {
										if ( ( ! empty( $rule['param'] ) && $rule['param'] == 'post_type' )
										     && ( ! empty( $rule['operator'] ) && $rule['operator'] == '==' )
										     && ( ! empty( $rule['value'] ) && $rule['value'] == $post_type )
										) {
											$flag = true;
										}
									}

								}
							}
						}
						if ( $flag ) {
							$groups[ $group->ID ] = $group->post_title;
						}
					}
				}
			} else {
				$groups_q = get_posts( array( 'post_type' => 'acf', 'posts_per_page' => - 1 ) );
				if ( ! empty( $groups_q ) ) {
					foreach ( $groups_q as $group ) {
						$rules = get_post_meta( $group->ID, 'rule' );
						$flag  = false;
						foreach ( $rules as $rule ) {
							if ( $post_type === 'all' ) {
								if ( ( ! empty( $rule['param'] ) && $rule['param'] == 'post_type' )
								     && ( ! empty( $rule['operator'] ) && $rule['operator'] == '==' )
								) {
									$flag = true;
								}
							} else {
								if ( ( ! empty( $rule['param'] ) && $rule['param'] == 'post_type' )
								     && ( ! empty( $rule['operator'] ) && $rule['operator'] == '==' )
								     && ( ! empty( $rule['value'] ) && $rule['value'] == $post_type )
								) {
									$flag = true;
								}
							}

						}
						if ( $flag ) {
							$groups[ $group->ID ] = $group->post_title;
						}
					}
				}
			}

			return $groups;
		}

		function get_all_fields_by_group_id_acf( $group_id ) {
			if ( class_exists( 'acf_pro' ) ) {
				$fields = acf_get_fields($group_id);
			} else {
				$fields = apply_filters( 'acf/field_group/get_fields', array(), $group_id );
			}

			return $fields;
		}



	}

endif;