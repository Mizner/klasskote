<?php
class TDWUR_Plugins {
	public $parent;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		add_filter( 'td_webmaster_capabilities', array( $this, 'capabilities' ) );
	}

	function is_active() {
		return true; // WP Core functionality, plugins is always present
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$this->section = array(
			'icon'      => 'wp-menu-image plugins',
			'title'     => __('Plugins', 'webmaster-plugin-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_plugins',
					'type'      => 'checkbox',
					'title'     => __('Plugin Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'install_plugins' => __('Install Plugins', 'webmaster-user-role'),
						'activate_plugins' => __('Activate/Deactivate Plugins', 'webmaster-user-role'),
						'update_plugins' => __('Update Plugins', 'webmaster-user-role'),
						'edit_plugins' => __('Edit Plugins (Plugins > Editor menu item)', 'webmaster-user-role'),
						'delete_plugins' => __('Delete Plugins', 'webmaster-user-role'),
					),

					'default'   => array(
						'install_plugins' => '0',
						'activate_plugins' => '0',
						'update_plugins' => '0',
						'edit_plugins' => '0',
						'delete_plugins' => '0',
					)
				),
			)
		);

		if ( is_multisite() ) {
			$this->section['fields']['0']['options'] = array(
				'activate_plugins' => __('Activate/Deactivate Plugins', 'webmaster-user-role'),
			);

			$this->section['fields']['0']['desc'] = '
			<p><strong>'. __( 'Notes for Multisite:', 'webmaster-user-role').'</strong></p>
			<p>'.__('WordPress core code only allows designated "Super Admins" to manage plugins for the entire network', 'webmaster-user-role').'</p>
			<p'.__('Blog/Site admins can only activate/deactivate plugins installed by the network administrator', 'webmaster-user-role').'</p>';
		}

		$sections[] = $this->section;

		return $sections;
	}

	

	function capabilities( $capabilities ) {
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();

		if ( is_multisite() ) {
			if ( is_array( $webmaster_user_role_config ) && (int)$webmaster_user_role_config['webmaster_caps_plugins']['activate_plugins'] ) {
				$capabilities['manage_network_plugins'] = 1;
			} else {
				$capabilities['manage_network_plugins'] = 0;
			}
		}

		return $capabilities;
	}



}