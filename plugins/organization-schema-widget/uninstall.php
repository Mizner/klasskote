<?php
/**
 * Uninstall code for the plugin Organization Schema Widget
 */
 
 
//* If unistall not called from WordPress, exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//* Delete plugin settings
delete_option( 'rvam_use_plugin_stylesheet' );
delete_option( 'widget_rvam_organization_schema_widget' );
unregister_setting( 'default', 'rvam_use_plugin_stylesheet' );

?>