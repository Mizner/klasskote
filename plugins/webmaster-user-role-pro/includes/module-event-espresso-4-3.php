<?php
class TDWUR_Event_Espresso_4_3 {
	public $parent;
	public $caps;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		$this->caps = array(
			'management' => __('Manage Settings', 'webmaster-user-role'),
			'espresso_events' => __('Events', 'webmaster-user-role'),
			'espresso_registrations' => __('Registrations', 'webmaster-user-role'),
			'espresso_transactions' => __('Transactions', 'webmaster-user-role'),
			'espresso_messages' => __('Messages', 'webmaster-user-role'),
			'pricing' => 'Pricing',
			'espresso_registration_form' => __('Registration Form', 'webmaster-user-role'),
			'espresso_venues' => __('Venues', 'webmaster-user-role'),
			'espresso_general_settings' => __('General Settings', 'webmaster-user-role'),
			'espresso_support' => __('Help & Support', 'webmaster-user-role'),
			'espresso_payment_settings' => __('Payment Methods', 'webmaster-user-role'),
			'espresso_maintenance_settings' => __('Maintenance', 'webmaster-user-role'),
			'espresso_about' => __('About', 'webmaster-user-role'),
		);
		foreach ($this->caps as $cap => $label) {
			add_filter( 'FHEE_'.$cap.'_capability', array( $this, 'set_webmaster_cap' ) );
		}
	}

	function is_active() {
		if (
			function_exists( 'espresso_version' )
			&&
			version_compare( espresso_version(), '4.5' ) < 0
		) {
			return true;
		}

		return false;
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$this->section = array(
			'icon'      => 'wp-menu-image dashicons dashicons-calendar',
			'title'     => __( 'Event Espresso', 'webmaster-user-role' ),
			'fields'    => array(
				array(
					'id'        => 'webmaster_event_espresso_settings',
					'type'      => 'checkbox',
					'title'     => __( 'Event Espresso Capabilities', 'webmaster-user-role' ),
					'subtitle'  => __( 'Webmaster (Admin) users can access Event Espresso menu', 'webmaster-user-role' ).':',

					'options'   => $this->caps,

					'default'   => array_combine( array_keys( $this->caps ), array_fill( 0, count( $this->caps ), 1 ) )
				),
			)
		);

		$sections[] = $this->section;

		return $sections;
	}

	function set_webmaster_cap( $capability ) {
		if ( TD_WebmasterUserRole::current_user_is_webmaster() ) {
			$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
			if ( is_array( $webmaster_user_role_config ) ) {

				$current_filter = current_filter();
				$current_cap = str_replace( array( 'FHEE_', '_capability' ), '', $current_filter );
				if ( $current_cap == 'management' ) {
					$current_cap = 'espresso_events'; // The top-level "management cap" is the same as the Events submenu
				}

				if ( !isset( $webmaster_user_role_config['webmaster_event_espresso_settings'][$current_cap] ) ) {
					$webmaster_user_role_config['webmaster_event_espresso_settings'][$current_cap] = $this->section['fields']['0']['default'][$current_cap];
				}
				if ( (int)$webmaster_user_role_config['webmaster_event_espresso_settings'][$current_cap] ) {
					return 'webmaster';
				}
			}
		}
		return $capability;
	}

}
