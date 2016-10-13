<?php
class TDWUR_Tools {
	public $parent;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		// add_filter( 'td_webmaster_capabilities', array( $this, 'capabilities' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ), 1000 );
	}

	function is_active() {
		return true; // WP Core functionality, plugins is always present
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$this->section = array(
			'icon'      => 'wp-menu-image tools',
			'title'     => __('Tools & Settings', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_admin_menu_tools_settings',
					'type'      => 'checkbox',
					'title'     => __('Visible in Menu', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can view', 'webmaster-user-role'),
					
					'options'   => array(
						'tools.php' => __('Tools Menu', 'webmaster-user-role'),
						'options-general.php' => __('Settings Menu', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'tools.php' => '0',
						'options-general.php' => '0',
					)
				),

			)
		);

		if ( is_multisite() ) {

		}

		$sections[] = $this->section;

		return $sections;
	}

	

	function capabilities( $capabilities ) {
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();

		return $capabilities;
	}

	function admin_menu() {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( is_array( $webmaster_user_role_config ) && empty ( $webmaster_user_role_config['webmaster_admin_menu_tools_settings']['tools.php'] ) ) {
			remove_menu_page( 'tools.php' );
		}
		if ( is_array( $webmaster_user_role_config ) && empty ( $webmaster_user_role_config['webmaster_admin_menu_tools_settings']['options-general.php'] ) ) {
			remove_menu_page( 'options-general.php' );
		}
	}



}