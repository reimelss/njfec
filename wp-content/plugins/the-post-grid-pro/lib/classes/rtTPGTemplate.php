<?php

if ( ! class_exists( 'rtTPGTemplate' ) ):

	/**
	 *
	 */
	class rtTPGTemplate {

		function __construct() {
			add_filter( 'template_include', array( $this, 'template_loader' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_rt_tpg_template_scripts' ) );
		}

		public static function template_loader( $template ) {
			global $rtTPG;
			$settings        = get_option( $rtTPG->options['settings'] );
			$oLayoutAuthor  = ! empty( $settings['template_author'] ) ? $settings['template_author'] : null;
			$oLayoutCategory = ! empty( $settings['template_category'] ) ? $settings['template_category'] : null;
			$oLayoutSearch   = ! empty( $settings['template_search'] ) ? $settings['template_search'] : null;
			$oLayoutTag      = ! empty( $settings['template_tag'] ) ? $settings['template_tag'] : null;

			$file = null;
			if ( is_tag() && $oLayoutTag ) {
				$file = 'tag-archive.php';
			} elseif ( is_category() && $oLayoutCategory ) {
				$file = 'category-archive.php';
			} elseif ( is_author() && $oLayoutAuthor ) {
				$file = 'author-archive.php';
			} elseif ( is_search() && $oLayoutSearch ) {
				$file = 'search.php';
			}

			if ( $file ) {
				$template = $rtTPG->templatePath . $file;
			}

			return $template;
		}

		public function load_rt_tpg_template_scripts() {
			global $rtTPG;
			if ( get_post_type() == $rtTPG->post_type || is_post_type_archive( $rtTPG->post_type ) ) {
				wp_enqueue_script( 'jquery' );
				array_push( $script, 'rt-image-load-js' );
				array_push( $script, 'rt-isotope-js' );
				array_push( $style, 'rt-owl-carousel' );
				array_push( $style, 'rt-owl-carousel-theme' );
				array_push( $script, 'rt-owl-carousel' );
				array_push( $style, 'rt-scrollbar' );
				array_push( $script, 'rt-scrollbar' );
				array_push( $style, 'rt-fontawsome' );
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
				$nonce = wp_create_nonce( $rtTPG->nonceText() );
				$ajaxurl = '';
				if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
					$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
				} else{
					$ajaxurl .= admin_url( 'admin-ajax.php');
				}
				wp_localize_script( 'rt-tpg', 'rttpg',
					array(
						'nonceID' => $rtTPG->nonceId(),
						'nonce'   => $nonce,
						'ajaxurl' => $ajaxurl
					) );
			}
		}


	}

endif;
