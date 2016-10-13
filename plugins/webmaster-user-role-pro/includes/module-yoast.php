<?php
class TDWUR_Yoast {
	public $parent;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		
		add_action( 'plugins_loaded', array( $this, 'wpseo_admin_init' ), 16 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 999 );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
	}

	function is_active() {
		return ( function_exists( 'wpseo_init' ) );
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = array(
			'icon'      => 'wp-menu-image tools',
			'title'     => __('Yoast SEO', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_yoast_metabox_settings',
					'type'      => 'checkbox',
					'title'     => __('Yoast SEO Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'yoast_post_metabox' => __('Edit SEO values on individual posts/pages', 'webmaster-user-role'),
						'yoast_settings' => __('Use Yoast Settings Menu', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'yoast_post_metabox' => '1',
						'yoast_settings' => '0',
					)
				),
			)
		);

		return $sections;
	}

	function wpseo_admin_init() {
		if ( !$this->is_active() ) return;
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;
		if ( empty( $GLOBALS['wpseo_metabox'] ) ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( isset( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_post_metabox'] ) && empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_post_metabox'] ) ) {
			remove_action( 'add_meta_boxes', array( $GLOBALS['wpseo_metabox'], 'add_meta_box' ) );
			remove_action( 'admin_enqueue_scripts', array( $GLOBALS['wpseo_metabox'], 'enqueue' ) );
			remove_action( 'wp_insert_post', array( $GLOBALS['wpseo_metabox'], 'save_postdata' ) );
			remove_action( 'edit_attachment', array( $GLOBALS['wpseo_metabox'], 'save_postdata' ) );
			remove_action( 'add_attachment', array( $GLOBALS['wpseo_metabox'], 'save_postdata' ) );
			remove_action( 'admin_init', array( $GLOBALS['wpseo_metabox'], 'setup_page_analysis' ) );
			remove_action( 'admin_init', array( $GLOBALS['wpseo_metabox'], 'translate_meta_boxes' ) );
		}	
	}

	function admin_menu() {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( is_array( $webmaster_user_role_config ) && empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_settings'] ) ) {
			remove_menu_page( 'wpseo_dashboard' );
		}
	}

	function admin_bar_menu( $wp_admin_bar ) {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;

		if ( empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_settings'] ) ) {
			$wp_admin_bar->remove_node( 'wpseo-settings' );
		}

		if ( empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_post_metabox'] ) ) {
			$wp_admin_bar->remove_node( 'wpseo-analysis' );
		}

		if ( empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_post_metabox'] ) && 
			empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_settings'] )
		) {
			$wp_admin_bar->remove_node( 'wpseo-menu' );
		}
	}
}