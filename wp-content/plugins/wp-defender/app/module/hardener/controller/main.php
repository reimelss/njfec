<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module\Hardener\Controller;

use Hammer\Base\Container;
use Hammer\Helper\HTTP_Helper;
use Hammer\Helper\Log_Helper;
use WP_Defender\Behavior\Utils;
use WP_Defender\Controller;
use WP_Defender\Module\Hardener;
use WP_Defender\Vendor\Email_Search;

class Main extends Controller {
	protected $slug = 'wdf-hardener';
	public $layout = 'layout';
	public $email_search;

	/**
	 * @return array
	 */
	public function behaviors() {
		return array(
			'utils' => '\WP_Defender\Behavior\Utils'
		);
	}

	/**
	 * Main constructor.
	 */
	public function __construct() {
		if ( $this->is_network_activate( wp_defender()->plugin_slug ) ) {
			$this->add_action( 'network_admin_menu', 'adminMenu' );
		} else {
			$this->add_action( 'admin_menu', 'adminMenu' );
		}

		if ( $this->isInPage() ) {
			$this->add_action( 'defender_enqueue_assets', 'scripts', 11 );
		}

		$this->add_ajax_action( 'processHardener', 'processHardener' );
		$this->add_ajax_action( 'processRevert', 'processRevert' );
		$this->add_ajax_action( 'ignoreHardener', 'ignoreHardener' );
		$this->add_ajax_action( 'restoreHardener', 'restoreHardener' );
		$this->add_ajax_action( 'updateHardener', 'updateHardener' );
		$this->add_ajax_action( 'saveTweaksSettings', 'saveTweaksSettings' );
//		if ( ! wp_next_scheduled( 'tweaksSendNotification' ) ) {
//			wp_schedule_event( time(), 'twicedaily', 'tweaksSendNotification' );
//		}

		$this->add_action( 'tweaksSendNotification', 'tweaksSendNotification' );

		$view = HTTP_Helper::retrieve_get( 'view' );
		$id   = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;
		if ( $view == 'notification' && $this->isInPage() || ( defined( 'DOING_AJAX' ) && $id == 'tweaksNotification' ) ) {
			$this->email_search           = new Email_Search();
			$this->email_search->eId      = 'tweaksNotification';
			$this->email_search->settings = Hardener\Model\Settings::instance();
			$this->email_search->add_hooks();
		}
	}

	public function restoreHardener() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		$slug = HTTP_Helper::retrieve_post( 'slug' );
		$rule = Hardener\Model\Settings::instance()->getRuleBySlug( $slug );
		if ( is_object( $rule ) ) {
			$rule->restore();
			wp_send_json_success( array(
				'message' => __( "Security tweak successfully restored.", wp_defender()->domain ),
				'issues'  => $this->getCount( 'issues' ),
				'fixed'   => $this->getCount( 'fixed' ),
				'ignore'  => $this->getCount( 'ignore' )
			) );
		}
	}

	public function tweaksSendNotification() {
		$settings = Hardener\Model\Settings::instance();
		//if last seen very near, do no thing
		if ( ! $settings->last_seen ) {
			//should not in here
			$settings->last_seen = time();
			$settings->save();
		}

		if ( strtotime( apply_filters( 'wd_tweaks_notification_interval', '+24 hours' ), apply_filters( 'wd_tweaks_last_action_time', $settings->last_seen ) ) > time() ) {
			return;
		}

		$tweaks = Hardener\Model\Settings::instance()->getIssues();
		if ( count( $tweaks ) == 0 ) {
			//no honey no email
			return;
		}

		if ( $settings->last_sent == null ) {
			//this is the case user install this and never check the page
			//send report
			foreach ( $settings->receipts as $receipt ) {
				$email = $receipt['email'];
				wp_mail( $email, 'update tweak subject', 'update tweak content' );
			}
			$settings->last_sent = time();
			$settings->save();
		} elseif ( strtotime( apply_filters( 'wd_tweaks_notification_interval', '+24 hours' ), apply_filters( 'wd_tweaks_last_notification_sent', $settings->last_sent ) ) < time() ) {
			//this is the case email already sent once last 24 hours
			if ( $settings->notification == false ) {
				//no repeat
				return;
			}

			foreach ( $settings->receipts as $receipt ) {
				$email = $receipt['email'];
				wp_mail( $email, 'update tweak subject', 'update tweak content' );
			}
			$settings->last_sent = time();
			$settings->save();
		}
	}

	public function saveTweaksSettings() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		if ( ! wp_verify_nonce( HTTP_Helper::retrieve_post( '_wpnonce' ), 'saveTweaksSettings' ) ) {
			return;
		}

		$settings = Hardener\Model\Settings::instance();
		$settings->import( $_POST );
		$settings->save();
//		if ( $this->hasMethod( 'scheduleReportTime' ) ) {
//			$this->scheduleReportTime( $settings );
//			$this->submitStatsToDev();
//		}
		wp_send_json_success( array(
			'message' => __( "Your settings have been updated.", wp_defender()->domain )
		) );
	}

	public function ignoreHardener() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		$slug = HTTP_Helper::retrieve_post( 'slug' );
		$rule = Hardener\Model\Settings::instance()->getRuleBySlug( $slug );
		if ( is_object( $rule ) ) {
			$rule->ignore();
			wp_send_json_success( array(
				'message' => __( "Security tweak successfully ignored.", wp_defender()->domain ),
				'issues'  => $this->getCount( 'issues' ),
				'fixed'   => $this->getCount( 'fixed' ),
				'ignore'  => $this->getCount( 'ignore' )
			) );
		}
	}

	public function processRevert() {
		if ( ! $this->checkPermission() ) {
			return;
		}
		$slug = HTTP_Helper::retrieve_post( 'slug' );
		do_action( "processRevert" . $slug );
		//fall back
		wp_send_json_success( array(
			'message' => __( "Security tweak successfully reverted.", wp_defender()->domain ),
			'issues'  => $this->getCount( 'issues' ),
			'fixed'   => $this->getCount( 'fixed' ),
			'ignore'  => $this->getCount( 'ignore' )
		) );
	}

	/**
	 * Ajax to process or ignore a rule
	 */
	public function processHardener() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		$slug = HTTP_Helper::retrieve_post( 'slug' );

		do_action( "processingHardener" . $slug );
		//fall back
		wp_send_json_success( array(
			'message' => __( "Security tweak successfully resolved.", wp_defender()->domain ),
			'issues'  => $this->getCount( 'issues' ),
			'fixed'   => $this->getCount( 'fixed' ),
			'ignore'  => $this->getCount( 'ignore' )
		) );
	}

	/**
	 * Update Hardener
	 * Update existing rules
	 */
	public function updateHardener() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		$slug = HTTP_Helper::retrieve_post( 'slug' );

		do_action( "processUpdate" . $slug );
		//fall back
		wp_send_json_success( array(
			'message' => __( "Security tweak successfully updated.", wp_defender()->domain ),
			'issues'  => $this->getCount( 'issues' ),
			'fixed'   => $this->getCount( 'fixed' ),
			'ignore'  => $this->getCount( 'ignore' ),
			'update'  => false
		) );
	}

	/**
	 * Add submit admin page
	 */
	public function adminMenu() {
		$cap = is_multisite() ? 'manage_network_options' : 'manage_options';
		add_submenu_page( 'wp-defender', esc_html__( "Security Tweaks", wp_defender()->domain ), esc_html__( "Security Tweaks", wp_defender()->domain ), $cap, $this->slug, array(
			&$this,
			'actionIndex'
		) );
	}

	/**
	 *
	 */
	public function actionIndex() {
		//update the last seen
		$settings            = Hardener\Model\Settings::instance();
		$settings->last_seen = time();
		$settings->save();
		switch ( HTTP_Helper::retrieve_get( 'view' ) ) {
			case 'issues':
			default:
				$this->_renderIssues();
				break;
			case 'resolved':
				$this->_renderResolved();
				break;
			case 'ignored':
				$this->_renderIgnored();
				break;
			case 'notification':
				$this->_renderNotification();
				break;
		}
	}

	private function _renderIssues() {
		$this->render( 'issues' );
	}

	private function _renderResolved() {
		$this->render( 'resolved' );
	}

	private function _renderIgnored() {
		$this->render( 'ignore' );
	}

	private function _renderNotification() {
		$this->render( 'notification', array(
			'setting' => Hardener\Model\Settings::instance(),
			'email'   => $this->email_search
		) );
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		wp_enqueue_script( 'wpmudev-sui' );
		wp_enqueue_script( 'defender' );
		wp_enqueue_script( 'hardener', wp_defender()->getPluginUrl() . 'app/module/hardener/js/scripts.js', array(
			'jquery-effects-core'
		) );
		wp_enqueue_style( 'wpmudev-sui' );
		wp_enqueue_style( 'defender' );
		$view = HTTP_Helper::retrieve_get( 'view' );
		if ( $view == 'notification' ) {
			$this->email_search->add_script();
		}
	}

	/**
	 *
	 * @param $type
	 *
	 * @return int
	 */
	public function getCount( $type ) {
		$settings = Hardener\Model\Settings::instance();
		switch ( $type ) {
			case 'issues':
				return count( $settings->issues );
				break;
			case 'fixed':
				return count( $settings->fixed );
				break;
			case 'ignore':
				return count( $settings->ignore );
				break;
			default:
				//param not from the button on frontend, log it
				error_log( sprintf( 'Unexpected value %s from IP %s', $type, Utils::instance()->getUserIp() ) );
				break;
		}
	}
}