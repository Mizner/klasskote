<?php
class TDWUR_Ninja_Forms {
	public $parent;
	public $caps;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		add_filter( 'ninja_forms_admin_import_export_capabilities', array( $this, 'nf_import_export_filter' ) );
		add_filter( 'ninja_forms_admin_settings_capabilities', array( $this, 'nf_settings_filter' ) );
		add_filter( 'ninja_forms_admin_extend_capabilities', array( $this, 'nf_extensions_filter' ) );
		add_filter( 'ninja_forms_admin_status_capabilities', array( $this, 'nf_status_filter' ) );
		add_filter( 'ninja_forms_admin_submissions_capabilities', array( $this, 'nf_submissions_filter' ) );
		add_filter( 'ninja_forms_admin_all_forms_capabilities',	array( $this, 'nf_edit_forms_filter' ) );
		add_filter( 'ninja_forms_admin_add_new_capabilities', array( $this, 'nf_edit_forms_filter' ) );	
		add_action( 'admin_menu', array( $this, 'manageformssubmenus' ) );
	}

	function is_active() {
		return class_exists( 'Ninja_Forms' );
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;
		
		$sections[] = $this->section = array(
			'icon'      => '',
			'title'     => __( 'Ninja Forms', 'webmaster-user-role' ),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_ninjaforms',
					'type'      => 'checkbox',
					'title'     => __( 'Ninja Forms Capabilities', 'webmaster-user-role' ),
					'subtitle'  => __( 'Webmaster (Admin) users can', 'webmaster-user-role' ),

					'options'   => array(
						'nf_edit_forms' => __('View & Edit Forms', 'webmaster-user-role' ),
						'nf_view_submissions' => __('View Form Submissions', 'webmaster-user-role' ),
						'nf_import_export' => __('Import and Export Forms', 'webmaster-user-role' ),
						'nf_manage_settings' => __('Manage Ninja Forms Settings', 'webmaster-user-role' ),
						'nf_manage_extensions' => __('Manage Extensions', 'webmaster-user-role' ),
						'nf_view_status' => __('View System Status', 'webmaster-user-role' ),
					),

					'default'   => array(
						'nf_edit_forms' => '1',
						'nf_view_submissions' => '1',
						'nf_import_export' => '0',
						'nf_manage_settings' => '0',
						'nf_manage_extensions' => '0',
						'nf_view_status' => '0',
					)
				),
			)
		);
		
		return $sections;
	}
	
	/* Remove Import/Export Capabilities */
	function nf_import_export_filter($nfCaps) {
		
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return $nfCaps;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_import_export'] ) ) {
			$nfCaps = 'update_core';
		}
		
		return $nfCaps;
	}
	
	/* Remove Ninja Forms Settings Management Capability */
	function nf_settings_filter($nfCaps) {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return $nfCaps;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_manage_settings'] ) ) {
			$nfCaps = 'update_core';
		}
		
		return $nfCaps;
	}
	
	/* Remove Capability to add Extensions */
	function nf_extensions_filter($nfCaps) {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return $nfCaps;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_manage_extensions'] ) ) {
			$nfCaps = 'update_core';
		}
		
		return $nfCaps;
	}
	
	/* Remove Capability to view system status */
	function nf_status_filter($nfCaps) {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return $nfCaps;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_view_status'] ) ) {
			$nfCaps = 'update_core';
		}
		
		return $nfCaps;
	}
	
	/* Remove Capability to view submissions */
	function nf_submissions_filter($nfCaps) {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return $nfCaps;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_view_submissions'] ) ) {
			$nfCaps = 'update_core';
		}
		
		return $nfCaps;
	}
	
	/* Remove capabilties associated with editing forms */
	function nf_edit_forms_filter($nfCaps) {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return $nfCaps;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_edit_forms'] ) ) {
			$nfCaps = 'update_core';
		}
		
		return $nfCaps;
	}
	
	function manageformssubmenus() {
		global $webmaster_user_role_config;
		if ( !is_array( $webmaster_user_role_config ) ) return;
		
		if ( empty ( $webmaster_user_role_config['webmaster_caps_ninjaforms']['nf_edit_forms'] ) ) {
			remove_submenu_page('ninja-forms', 'ninja-forms');
		}
		
		/* if all capabilities are turned off, remove ninja forms from admin menu */
		if ( empty( $webmaster_user_role_config['webmaster_caps_ninjaforms'] ) ) return;
		
		$allunset = 1;
		foreach ( $webmaster_user_role_config['webmaster_caps_ninjaforms'] as $cap) {
			if ( ! empty($cap) ) {
				$allunset = 0;
				break;
			}
		}
		
		if ( $allunset == 1 ) {
			remove_menu_page('ninja-forms');
		}
	}
	
}// end Ninja Forms Class