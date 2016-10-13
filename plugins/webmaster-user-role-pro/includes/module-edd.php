<?php
class TDWUR_EDD {
	public $parent;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );

		add_action( 'add_meta_boxes', array( $this, 'hide_download_stats_metabox' ), 20 );

		add_filter( 'edd_download_columns', array( $this, 'edd_download_columns' ) );
		// add_filter( 'td_webmaster_capabilities', array( $this, 'capabilities' ) );
	}

	function is_active() {
		return ( class_exists( 'Easy_Digital_Downloads' ) );
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'dashicons dashicons-download',
			'title'     => __('Easy Digital Downloads', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_edd',
					'type'      => 'checkbox',
					'title'     => __('Easy Digital Downloads', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'edit_products' => __('Manage Products', 'webmaster-user-role'),
						'edit_shop_payments' => __('Manage Customer Payments', 'webmaster-user-role'),
						'manage_shop_discounts' => __('Manage Discounts', 'webmaster-user-role'),
						'view_shop_reports' => __('View Reports & Sales Data', 'webmaster-user-role'),
						'manage_shop_settings' => __('Manage Settings', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'edit_products' => '1',
						'edit_shop_payments' => '1',
						'manage_shop_discounts' => '1',
						'view_shop_reports' => '1',
						'manage_shop_settings' => '1',
					)
				),
			)
		);

		return $sections;
	}

	function edd_download_columns( $columns ) {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return $columns;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return $columns;

		if ( empty ( $webmaster_user_role_config['webmaster_caps_edd']['view_shop_reports'] ) ) {
			unset( $columns['sales'] );
			unset( $columns['earnings'] );
		}

		return $columns;
	}

	function hide_download_stats_metabox() {
		remove_meta_box( 'edd_product_stats', 'download', 'side' );
	}

	function capabilities( $capabilities ) {
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;

		return $capabilities;
	}
}