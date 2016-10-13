<?php
/*
 * Module for Custom Post Type UI
 */
	
class TDWUR_Envato {
	public $parent;
	private $section;
	
	function __construct( $parent ) {
		$this->parent = $parent;
		
		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		add_action('admin_menu', array( $this, 'admin_menu' ), 102 );
	}
	
	function is_active() {
		return ( defined( 'EWPT_PLUGIN_SLUG' ) );
	}
	
	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'wp-menu-image dashicons envato',
			'title'     => __('Envato Toolkit', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_admin_menu_envato',
					'type'      => 'checkbox',
					'title'     => __('Envato Toolkit', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'envato_toolkit_menu' => __('Access Envato Toolkit Menu', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'envato_toolkit_menu' => '0',						
					)
				),
			)
		);
		
		return $sections;
	}//end settings_section function
	
	function admin_menu() {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) || !TD_WebmasterUserRole::current_user_is_webmaster() ) {
			return;
		}
		
		if ( empty ( $webmaster_user_role_config['webmaster_admin_menu_envato']['envato_toolkit_menu'] ) ) {
			remove_menu_page( EWPT_PLUGIN_SLUG );
		}
	}
	
} //end class TDWUR_Envato
?>