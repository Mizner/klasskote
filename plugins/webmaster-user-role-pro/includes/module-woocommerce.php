<?php
class TDWUR_WooCommerce {
	public $parent;
	private $section;
	
	function __construct( $parent ) {
		$this->parent = $parent;
		
		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		add_action( 'wp_user_dashboard_setup', array( $this, 'woocommerce_hide_orders_widget' ) );
		add_action('wp_dashboard_setup', array( $this, 'woocommerce_hide_orders_widget' ) );
		add_filter( 'td_webmaster_capabilities', array( $this, 'capabilities' ) );
	} //end constructor

	function is_active() {
		return ( class_exists( 'WooCommerce' ) );
	}
	
	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = $this->section = array(
			'icon'      => 'wp-menu-image dashicons dashicon-woocommerce',
			'title'     => __('WooCommerce', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_caps_woocommerce_caps',
					'type'      => 'checkbox',
					'title'     => __('WooCommerce Capabilities', 'webmaster-user-role'),
					'subtitle'  => __('Webmaster (Admin) users can', 'webmaster-user-role'),

					'options'   => array(
						'manage_woocommerce' => __('Manage WooCommerce Settings', 'webmaster-user-role'),
						'edit_view_products' => __('Edit & View Products', 'webmaster-user-role'),
						'edit_shop_coupons' => __('Edit & View Coupons', 'webmaster-user-role'),
						'edit_shop_orders' => __('Edit & View Orders', 'webmaster-user-role'),
						'view_woocommerce_reports' => __('View Reports', 'webmaster-user-role'),
					),
					
					'default'   => array(
						'manage_woocommerce' => '1',
						'edit_view_products' => '1',
						'edit_shop_coupons' => '1',
						'edit_shop_orders' => '1',
						'view_woocommerce_reports' => '1',
						
					)
				),
			)
		);

		return $sections;
	} //end settings_section function
	
	function capabilities( $capabilities ) {
		
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;
		
		//Update Product Editing Capabilities
		$currentProductCap = (int)$webmaster_user_role_config['webmaster_caps_woocommerce_caps']['edit_view_products'];
		
		$capabilities['edit_products'] = $currentProductCap;	
		$capabilities['manage_product_terms'] = $currentProductCap;
		$capabilities["edit_others_products"] = $currentProductCap;
		$capabilities["read_product"] = $currentProductCap;
		$capabilities["edit_published_products"] = $currentProductCap;
				
		return $capabilities; 
	}
	
	function woocommerce_hide_orders_widget() {
		
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) || !TD_WebmasterUserRole::current_user_is_webmaster() ) {
			return;
		}
		
		if ( 
			empty ( $webmaster_user_role_config['webmaster_caps_woocommerce_caps']['edit_shop_orders'] ) ||
			empty ( $webmaster_user_role_config['webmaster_caps_woocommerce_caps']['view_woocommerce_reports'] ) 
		) 
		{
			remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal');
		}

	} //end hide_orders_widget
	
} //end class TDWUR_Woocommerce
?>