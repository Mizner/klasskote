<?php
class TDWUR_Event_Espresso {
	public $parent;
	public $caps;
	private $section;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		$this->caps = array(
			// 'management' => __('Manage Settings', 'webmaster-user-role'),
			'ee_publish_events' => __('Events', 'webmaster-user-role'),
			'ee_edit_registrations' => __('Registrations', 'webmaster-user-role'),
			'ee_read_transactions' => __('Transactions', 'webmaster-user-role'),
			'ee_edit_messages' => __('Messages', 'webmaster-user-role'),
			'ee_edit_default_prices' => 'Pricing',
			'ee_edit_questions' => __('Registration Form', 'webmaster-user-role'),
			'ee_edit_venues' => __('Venues', 'webmaster-user-role'),
			'ee_manage_gateways' => __('Payment Methods', 'webmaster-user-role'),
		);
		foreach ($this->caps as $cap => $label) {
			add_filter( 'FHEE_'.$cap.'_capability', array( $this, 'set_webmaster_cap' ) );
		}

		add_filter( 'td_webmaster_capabilities', array( $this, 'capabilities' ) );
	}

	function is_active() {
		if (
			function_exists( 'espresso_version' )
			&&
			version_compare( espresso_version(), '4.5' ) >= 0
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
					'id'        => 'webmaster_caps_event_espresso',
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

	function capabilities( $capabilities ) {
		$webmaster_user_role_config = TD_WebmasterUserRole::get_config();
		if ( !is_array( $webmaster_user_role_config ) ) return;

		/* Payments */
		$capabilities['ee_read_payment_method'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_read_payment_methods'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_read_others_payment_methods'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_edit_payment_method'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_edit_payment_methods'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_edit_others_payment_methods'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_delete_payment_method'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];
		$capabilities['ee_delete_payment_methods'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_manage_gateways'];

		/* Transactions */
		$capabilities['ee_read_transaction'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_read_transactions'];
		$capabilities['ee_edit_payments'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_read_transactions'];
		$capabilities['ee_delete_payments'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_read_transactions'];

		/* Events */
		$capabilities['ee_read_private_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_read_others_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_read_event'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_read_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_edit_event'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_edit_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_edit_published_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_edit_others_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_edit_private_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_delete_published_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_delete_private_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_delete_event'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_delete_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_delete_others_events'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_manage_event_categories'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_edit_event_category'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_delete_event_category'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];
		$capabilities['ee_assign_event_category'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_publish_events'];

		/* Registrations */
		$capabilities['ee_read_registration'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];
		$capabilities['ee_read_registrations'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];
		$capabilities['ee_read_others_registrations'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];
		$capabilities['ee_edit_registration'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];
		$capabilities['ee_edit_others_registrations'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];
		$capabilities['ee_delete_registration'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];
		$capabilities['ee_delete_registrations'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_registrations'];

		/* Messages */
		$capabilities['ee_read_message'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_read_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_read_others_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_read_global_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_edit_global_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_edit_message'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_edit_others_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_delete_message'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_delete_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_delete_others_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_delete_global_messages'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];
		$capabilities['ee_send_message'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_messages'];

		/* Pricing */
		$capabilities['ee_edit_default_price'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_delete_default_price'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_delete_default_prices'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_edit_default_price_type'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_edit_default_price_types'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_delete_default_price_type'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_delete_default_price_types'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_read_default_prices'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];
		$capabilities['ee_read_default_price_types'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_default_prices'];

		/* Registration Form */
		$capabilities['ee_edit_question'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_edit_system_questions'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_read_questions'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_delete_question'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_delete_questions'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_edit_question_group'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_edit_question_groups'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_read_question_groups'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_edit_system_question_groups'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_delete_question_group'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];
		$capabilities['ee_delete_question_groups'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_questions'];

		/* Registration Form */
		$capabilities['ee_publish_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_read_venue'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_read_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_read_others_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_read_private_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_edit_venue'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_edit_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_edit_others_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_edit_published_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_edit_private_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_delete_venue'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_delete_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_delete_others_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_delete_private_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];
		$capabilities['ee_delete_published_venues'] = (int)$webmaster_user_role_config['webmaster_caps_event_espresso']['ee_edit_venues'];

		return $capabilities;
	}

}
