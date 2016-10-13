<?php
//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//
//// check for divi builder
//if ( is_plugin_active( 'divi-builder/divi-builder.php' ) ) {
//	$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );if ($is_page_builder_used ) {echo 'divi';};
//}

/**
 *	This will hide the Divi "Project" post type.
 *	Thanks to georgiee (https://gist.github.com/EngageWP/062edef103469b1177bc#gistcomment-1801080) for his improved solution.
 */
add_filter( 'et_project_posttype_args', 'knoxweb_et_project_posttype_args', 10, 1 );
function knoxweb_et_project_posttype_args( $args ) {
	return array_merge( $args, array(
		'public'              => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => false
	));
}


function is_divi_active() {
	$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
	if ( $is_page_builder_used ) {
		echo 'divi-builder-active';
	}
}

add_filter( 'body_class', function( $classes ) {
	$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
	if ( $is_page_builder_used ) {
		return array_merge( $classes, array( 'divi-builder-active' ) );
	}
	else {
		return array_merge( $classes, array( 'divi-builder-not-active' ) );
	}

} );


function deregister_script() {
	$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

	if ( ! $is_page_builder_used ) {
		wp_dequeue_script( 'et-builder-modules-global-functions-script' );
		wp_dequeue_script( 'google-maps-api' );
		wp_dequeue_script( 'divi-fitvids' );
		wp_dequeue_script( 'waypoints' );
		wp_dequeue_script( 'magnific-popup' );

		wp_dequeue_script( 'hashchange' );
		wp_dequeue_script( 'salvattore' );
		wp_dequeue_script( 'easypiechart' );

		wp_dequeue_script( 'et-jquery-visible-viewport' );

		wp_dequeue_script( 'magnific-popup' );
		wp_dequeue_script( 'et-jquery-touch-mobile' );
		wp_dequeue_script( 'et-builder-modules-script' );
	}
}

add_action( 'wp_print_scripts', 'deregister_script', 100 );

function deregister_styles() {
	$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

	if ( ! $is_page_builder_used ) {
		wp_dequeue_style( 'et-builder-modules-style' );
	}
}

add_action( 'wp_print_styles', 'deregister_styles', 100 );