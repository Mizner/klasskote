<?php
class TDWUR_Exchange {
	public $parent;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );

		add_action( 'init', array( $this, 'it_exchange_admin_menu_capability' ) );
		add_action( 'init', array( $this, 'remove_dashboard_widget' ) );
		add_filter( 'manage_edit-it_exchange_prod_columns', array( $this, 'it_exchange_product_columns' ), 1000 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 100 );
	}

	function is_active() {
		return ( class_exists( 'IT_Exchange' ) );
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'dashicons it-icon-exchange-e',
			'title'     => __('Exchange', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'it_exchange_admin_menu',
					'type'      => 'checkbox',
					'title'     => __('Exchange - Admin Access', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'admin_menu' => __('Manage Exchange (unchecking removes all access to Exchange (making other settings on this page irrelevant)', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'admin_menu' => '1',
					)
				),

				array(
					'id'        => 'it_exchange_caps',
					'type'      => 'checkbox',
					'title'     => __('Exchange - Restricted Access', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'manage_purchases_payments' => __('Manage/View purchases & payments (this also shows/hides the dashboard widget displaying recent sales)', 'webmaster-user-role'),
						'manage_settings' => __('Manage settings', 'webmaster-user-role'),
						'manage_addons' => __('Manage add-ons', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'manage_purchases_payments' => '1',
						'manage_settings' => '1',
						'manage_addons' => '1',
					)
				),
			)
		);

		return $sections;
	}

	function remove_dashboard_widget() {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;
		if ( !$this->is_active() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;

		if ( empty ( $webmaster_user_role_config['it_exchange_caps']['manage_purchases_payments'] )
			|| empty ( $webmaster_user_role_config['it_exchange_admin_menu']['admin_menu'] )
		) {
			remove_action( 'wp_dashboard_setup', 'it_exchange_basic_reporting_register_dashboard_widget' );
		}

	}

	function it_exchange_admin_menu_capability() {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;
		if ( !$this->is_active() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;

		if ( empty ( $webmaster_user_role_config['it_exchange_admin_menu']['admin_menu'] ) ) {
			$GLOBALS['IT_Exchange_Admin']->admin_menu_capability = 'administrator';
		}
	}

	function admin_menu() {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;
		if ( !$this->is_active() ) return;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;

		if ( empty ( $webmaster_user_role_config['it_exchange_caps']['manage_purchases_payments'] ) ) {
			remove_submenu_page( 'it-exchange', 'edit.php?post_type=it_exchange_tran' );
		}
		if ( empty ( $webmaster_user_role_config['it_exchange_caps']['manage_settings'] ) ) {
			remove_submenu_page( 'it-exchange', 'it-exchange-settings' );
		}
		if ( empty ( $webmaster_user_role_config['it_exchange_caps']['manage_addons'] ) ) {
			remove_submenu_page( 'it-exchange', 'it-exchange-addons' );
		}
	}

	function it_exchange_product_columns( $columns ) {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return $columns;
		if ( !$this->is_active() ) $columns;

		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return $columns;

		if ( empty ( $webmaster_user_role_config['it_exchange_caps']['manage_purchases_payments'] ) ) {
			unset( $columns['it_exchange_product_purchases'] );
		}

		return $columns;
	}

}