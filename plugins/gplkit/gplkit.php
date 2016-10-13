<?php
/**
 * Plugin Name: GPL Kit
 * Plugin URI: https://www.gplkit.com
 * Description: WooCommerce Plugin Manager
 * Version: 1.0.6
 * Tested up to: 4.5.1
 * Author: GPL Kit
 * Author URI: https://www.gplkit.com
 * Tet Domain: gplkit-plugin-manager
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'GplKit' ) ) {

	/**
	 * Main GplKit Class
	 *
	 * @class GplKit
	 * @version	2.3.0
	 */
	final class GplKit {
		
		protected static $_instance = null;

		public $program = null;
		
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		public function __construct() {
			
			$this->includes();
			$this->init_hooks();
			
			do_action( 'gk_loaded' );
		}

		public function init_hooks() {
			add_action( 'init', array( $this, 'init' ), 0 );
		}
		
		public function includes() {
			include_once( 'includes/class-gk-admin.php' );
			include_once( 'includes/class-gk-updates.php' );
			include_once( 'includes/class-gk-plugin.php' );
			include_once( 'includes/class-gk-license.php' );
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		public function init() {
			add_action( 'admin_enqueue_scripts', array($this,'gk_enqueue_scripts') );
		}

		public function install() {
			wp_schedule_event(time(), 'twicedaily', 'gplkit_twicedaily_update');
			GKL()->activation();
		}
		public function uninstall() {
			wp_clear_scheduled_hook('gplkit_twicedaily_update');
			GKL()->uninstall();
		}

		public function gk_enqueue_scripts($hook) {
			wp_enqueue_style( 'gplkit-admin-css', plugin_dir_url( __FILE__ ) . 'assets/css/admin-styles.css' );
			wp_enqueue_script( 'gplkit-admin-js', plugins_url('assets/js/jquery.mixitup.min.js',__FILE__) );
		}

		public function get_gplkit_installed_plugins() {
			return array(
				
			);
		}
		
	}

}
register_activation_hook( __FILE__, array( 'GplKit', 'install' ) );
register_deactivation_hook(__FILE__, array( 'GplKit', 'uninstall' ) );

function GK() {
	return GplKit::instance();
}

// Global for backwards compatibility.
$GLOBALS['gplkit'] = GK();