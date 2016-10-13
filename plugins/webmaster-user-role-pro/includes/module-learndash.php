<?php
class TDWUR_LearnDash {
	public $parent;
	private $section;
	
	function __construct( $parent ) {
		$this->parent = $parent;
		
		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		// add_filter( 'td_webmaster_capabilities', array( $this, 'capabilities' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 99999 );
	} //end constructor

	function is_active() {
		return ( class_exists( 'SFWD_LMS' ) );
	}
	
	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'wp-menu-image dashicons learndash',
			'title'     => __('LearnDash LMS', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_learndash_caps',
					'type'      => 'checkbox',
					'title'     => __('LearnDash Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'edit_courses' => __('Manage Courses (also includes Lessons, Topics, Quizzes, and Certificates)', 'webmaster-user-role'),
						'edit_assignments' => __('Manage Assignments', 'webmaster-user-role'),
						'edit_groups' => __('Manage Groups', 'webmaster-user-role'),
						'group_leader' => __('Group Administration (list users, export progress & results)', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'edit_courses' => '1',
						'edit_assignments' => '1',
						'edit_groups' => '1',
						'group_leader' => '1',
					)
				),
			)
		);

		return $sections;
	} //end settings_section function
	
	function capabilities( $capabilities ) {
		
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;
		
		return $capabilities; 
	}

	function admin_menu() {
		if ( !$this->is_active() ) return;
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( isset( $webmaster_user_role_config['webmaster_caps_learndash_caps']['edit_courses'] ) && empty( $webmaster_user_role_config['webmaster_caps_learndash_caps']['edit_courses'] ) ) {
			global $menu;
			foreach ($menu as $key => $item) {
				$capabilities = array( 'edit_courses', 'edit_assignments', 'edit_groups', 'group_leader' );
				foreach ( $capabilities as $capability ) {
					if ( $item[1] == $capability && isset( $webmaster_user_role_config['webmaster_caps_learndash_caps'][$capability] ) && empty( $webmaster_user_role_config['webmaster_caps_learndash_caps'][$capability] ) ) {
						unset( $menu[$key] );
					}
				}
			}
		}
	}
		
} //end class TDWUR_Woocommerce
?>