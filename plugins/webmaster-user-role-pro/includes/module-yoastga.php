<?php
/*
 * Module for Custom Post Type UI
 */
	
class TDWUR_YoastAnalytics {
	public $parent;
	private $section;
	
	function __construct( $parent ) {
		$this->parent = $parent;
		
		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		//add_action( 'wp_user_dashboard_setup', array( $this, 'manage_yga_menus' ), 99999999 );
		//add_action('wp_dashboard_setup', array( $this, 'manage_yga_menus' ), 99999999 );
		add_action( 'admin_menu', array( $this, 'manage_yga_menus' ), 99 );
	}
	
	function is_active() {
		return ( class_exists( 'Yoast_GA_Admin' ) );
	}
	
	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'wp-menu-image dashicons-admin-generic',
			'title'     => __('Google Analytics by Yoast', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_yoastga_caps',
					'type'      => 'checkbox',
					'title'     => __('Yoast Google Analytics Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'view_yga_dash' => __('View Analytics Dashboard (must be enabled to manage settings and extensions)', 'webmaster-user-role'),
						'manage_yga_settings' => __('Manage Settings', 'webmaster-user-role'),
						'manage_yga_extensions' => __('Manage Extensions', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'view_yga_dash' => '1',
						'manage_yga_settings' => '0',
						'manage_yga_extensions' => '0',						
					)
				),
			)
		);
		
		return $sections;
	}//end settings_section function
	
	function manage_yga_menus() {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) || !TD_WebmasterUserRole::current_user_is_webmaster() ) {
			return;
		}
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_yoastga_caps']['view_yga_dash'] ) ) {
			remove_menu_page('yst_ga_dashboard');
			remove_menu_page('yst_ga_settings');
		}
		if ( empty ( $webmaster_user_role_config['webmaster_caps_yoastga_caps']['manage_yga_settings'] ) ) {
			remove_submenu_page('yst_ga_dashboard', 'yst_ga_settings');
		}
		if ( empty ( $webmaster_user_role_config['webmaster_caps_yoastga_caps']['manage_yga_extensions'] ) ) {
			remove_submenu_page('yst_ga_dashboard','yst_ga_extensions');
		}
	}
	
} //end class TDWUR_YoastAnalytics
?>