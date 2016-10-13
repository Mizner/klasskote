<?php
/**
 * Plugin
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GK_Plugin Class
 */
class GK_Plugin {

	public function __construct() {
		
		add_action( 'wp_loaded', array( $this, 'init' ) );

	}

	/**
	 * Hook in methods.
	 */
	public static function init() {

		add_action( 'wp_ajax_gplkit_install_plugin', array( __CLASS__, 'gk_ajax_install_plugin' ) );
		add_action( 'init', array( __CLASS__, 'gk_disable_woothemes_updater_notice' ) );
		
		if (defined('GPLKIT_PLUGIN_RENAME')) {
			if (GPLKIT_PLUGIN_RENAME != false) {
				add_filter( 'all_plugins', array( __CLASS__, 'gk_override_plugin_names') );
			}
		}
		
	}

	public static function gk_install_plugins($plugins) {

		$plugins_path = ABSPATH.'wp-content/plugins/';
		if (defined('WP_PLUGIN_DIR')) {
			$plugins_path = WP_PLUGIN_DIR.'/';
		}

		$args = array(
            'path' => $plugins_path,
            'preserve_zip' => false
	    );

	    foreach($plugins as $plugin) {

	    	$gplkit_plugins = get_option('gplkit_plugins');
			$gplkit_licence_manager = get_option('gplkit_plugin_manager');
	    	$email = $gplkit_licence_manager['activation_email'];
			$licence_key = $gplkit_licence_manager['api_key'];
			$product_id = 'GplKit%20Plugin%20Manager';
			$instance = get_option('gplkit_plugin_manager_instance');

			$plugin_url = 'http://www.gplkit.com/?gk_plugin_download=get&plugin_id='.$gplkit_plugins[$plugin]['plugin_id'].'&email='.$email.'&licence_key='.$licence_key.'&product_id='.$product_id.'&instance='.$instance.'&request=gplkit_status';

	    	$url = $plugin_url;
	    	
	    	$path = $args['path'].$gplkit_plugins[$plugin]['plugin_id'].'.zip';

	    	if (file_exists($args['path'].$plugin)) {
		    	return "Error 1003"; // Plugin already existed
	    	}

	    	if ( $gplkit_plugins[$plugin]['free'] != 1 && get_option( 'gplkit_plugin_manager_activated' ) != 'Activated' ) {
	    		return "Error 1006";
	    	}

	    	$ch = curl_init($url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6');
		    $data = curl_exec($ch);

		    if(curl_errno($ch))
			{
		    	return "Error 1002"; // 1002 failed to download plugin from URL
			}

		    curl_close($ch);
		    
		    if(file_put_contents($path, $data)) {

		    	WP_Filesystem();
			    $unzipfile = unzip_file($path, $args['path']);
			    if (!$unzipfile) {
			    	return "Error WP unzip";
			    }
			    if($args['preserve_zip'] === false)
			    {
			            unlink($path);
			    }

		    } else {	
		    	return "Error 1001"; // 1001 failed to put zip file in directory
		    }
	    }
	    return 'Installed';
	}

	public static function gk_ajax_install_plugin() {
		$plugins = array($_POST['plugin']);
		echo GK_Plugin::gk_install_plugins($plugins);
		wp_die();
	}

	public function gk_override_plugin_names($plugins) {
		
		if ($gplkit_plugins = get_option('gplkit_plugins')) {
			foreach($plugins as $key => $plugin) {
				if (array_key_exists($key, $gplkit_plugins)) {
					if (!empty($gplkit_plugins[$key]['gplkit_name'])) {
						$plugins[$key]['Name'] = $gplkit_plugins[$key]['gplkit_name'];
					}
				}
			}
		}
		return $plugins;
	}

	public static function gk_disable_woothemes_updater_notice() {
		$gplkit_options = get_option('gplkit_other_settings_options');
		if ($gplkit_options['disable_woothemes_updater_notice'] == 1) {
			remove_action( 'admin_notices', 'woothemes_updater_notice' );
		}
	}

}

GK_Plugin::init();