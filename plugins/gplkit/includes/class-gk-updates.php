<?php
/**
 * Updates
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GK_Updates Class
 */
class GK_Updates {

	public function __construct() {
		
		add_action( 'wp_loaded', array( $this, 'init' ) );
		
	}

	/**
	 * Hook in methods.
	 */
	public static function init() {

		if (is_admin()) {

			add_filter('site_transient_update_plugins', array(__CLASS__, 'override_update_locations') );
			//add_filter( 'plugins_api', array( __CLASS__, 'get_plugin_info' ), 10, 3 );
		}
		
	}

	public static function override_update_locations($value) {

		if ( get_option( 'gplkit_plugin_manager_activated' ) == 'Activated' ) {

			$all_plugins = get_plugins();

			if ($gplkit_plugins = get_option('gplkit_plugins')) {

				foreach($all_plugins as $key => $plugin) {

					if (array_key_exists($key, $gplkit_plugins)) {

						$gplkit_licence_manager = get_option('gplkit_plugin_manager');

						$email = $gplkit_licence_manager['activation_email'];
						$licence_key = $gplkit_licence_manager['api_key'];
						$product_id = 'GplKit%20Plugin%20Manager';
						$instance = get_option('gplkit_plugin_manager_instance');

						$plugin_url = 'http://www.gplkit.com/?gk_plugin_download=get&plugin_id='.$gplkit_plugins[$key]['plugin_id'].'&email='.$email.'&licence_key='.$licence_key.'&product_id='.$product_id.'&instance='.$instance.'&request=gplkit_status';
						
				        $obj = new stdClass();
				        $obj->slug = $gplkit_plugins[$key]['plugin_id'];
						$obj->plugin = $key;
				        $obj->new_version = $gplkit_plugins[$key]['version'];
				        $obj->package = $plugin_url;
				        
				        // if new version is different to current version
				        if (version_compare($all_plugins[$key]['Version'], $obj->new_version) < 0) {
					        // add to transient
				    	    $value->response[$key] = $obj;
						}

					}
				
				}
			}
		}
		
		
		return $value;

	}

	public static function get_plugin_info($false, $action, $response) {

		if ($response->slug && $gplkit_plugins = get_option('gplkit_plugins')) {
			
			foreach($gplkit_plugins as $plugin) {
				
				if ($plugin['plugin_id'] == $response->slug) {
					$response->name = $plugin['gplkit_name'] ? $plugin['gplkit_name'] : $plugin['name'];
					$response->author = 'GPL Kit';
				    $response->sections = array(
				    	'description'	=> $plugin['description'],
					    'changelog' => $plugin['changelog_url'] ?  str_replace(array("\r\n", "\r", "\n", "&#13;", "&#10;"), "<p></p>",wp_remote_retrieve_body( wp_remote_get($plugin['changelog_url']))) : 'No changelog found.',
					);
				}
			}

		}
		return $response;
	}

	public static function get_plugin_catalogue() {

		$url = 'http://www.gplkit.com/?gk_plugin_repo=json';
		$request = wp_remote_post( $url, array('timeout' => 30) );

		if( !is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
	    	
	    	$json = json_decode( $request['body'], true ); // attempt decode

	    	if( $json !== null ) {
		    	update_option('gplkit_plugins', $json);
	    	} // return json
	    	
	    }
	}

	
}

GK_Updates::init();

add_action( 'gplkit_twicedaily_update', array('GK_Updates', 'get_plugin_catalogue'), 10 );