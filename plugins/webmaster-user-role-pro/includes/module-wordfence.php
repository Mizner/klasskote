<?php
/*
 * Module for Custom Post Type UI
 */
	
class TDWUR_Wordfence {
	public $parent;
	private $section;
	
	function __construct( $parent ) {
		$this->parent = $parent;
		
		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		add_action( 'wp_user_dashboard_setup', array( $this, 'manage_wordfence_widget' ) );
		add_action('wp_dashboard_setup', array( $this, 'manage_wordfence_widget' ) );
	}
	
	function is_active() {
		return ( class_exists( 'wordfence' ) );
	}
	
	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'wp-menu-image dashicons-admin-generic',
			'title'     => __('Wordfence', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_wordfence_caps',
					'type'      => 'checkbox',
					'title'     => __('Wordfence Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'view_wf_widget' => __('View Wordfence Dashboard Widget', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'view_wf_widget' => '0',						
					)
				),
			)
		);
		
		return $sections;
	}//end settings_section function
	
	function manage_wordfence_widget() {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) || !TD_WebmasterUserRole::current_user_is_webmaster() ) {
			return;
		}
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_wordfence_caps']['view_wf_widget'] ) ) {
			remove_meta_box( 'wordfence_activity_report_widget', 'dashboard', 'normal');
		}
	}
	
} //end class TDWUR_Wordfence
?>