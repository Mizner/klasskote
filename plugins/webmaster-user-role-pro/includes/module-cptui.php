<?php
/*
 * Module for Custom Post Type UI
 */
	
class TDWUR_CPTUI {
	public $parent;
	private $section;
	
	function __construct( $parent ) {
		$this->parent = $parent;
		
		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		add_action('admin_menu', array( $this, 'cptui_manage_menus' ) );
	}
	
	function is_active() {
		return ( defined( 'CPT_VERSION' ) || function_exists( 'cptui_plugin_menu' ) );
	}
	
	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'wp-menu-image dashicons-admin-generic',
			'title'     => __('Custom Post Type UI', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_cptui_caps',
					'type'      => 'checkbox',
					'title'     => __('Custom Post Type UI Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'edit_post_types' => __('Manage Custom Post Types', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'edit_post_types' => '0',						
					)
				),
			)
		);
		
		return $sections;
	}//end settings_section function
	
	function cptui_manage_menus() {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) || !TD_WebmasterUserRole::current_user_is_webmaster() ) {
			return;
		}
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_cptui_caps']['edit_post_types'] ) ) {
			remove_menu_page( 'cptui_main_menu' );
		}
	}
	
} //end class TDWUR_CPTUI
?>