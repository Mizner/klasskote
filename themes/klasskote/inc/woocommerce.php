<?php
add_theme_support( 'woocommerce' );

// Display 24 products per page. Goes in functions.php
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 100;' ), 20 );

// Change number or products per row
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 6;
	}
}

// Add action to hook into the approp
add_filter( 'woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder', 10 );
/**
 * Function to return new placeholder image URL.
 */
function custom_woocommerce_placeholder( $image_url ) {
	$image_url = get_template_directory_uri() . "/images/woo-product-default.jpg";  // change this to the URL to your custom placeholder
	return $image_url;
}