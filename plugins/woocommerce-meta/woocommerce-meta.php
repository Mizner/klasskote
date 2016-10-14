<?php
/**
 * Plugin Name: WooCommerce Meta
 * Plugin URI: http://mizner.io
 * Description:
 * Version: 1.0
 * Author: Michael Mizner
 * Author URI: http://mizner.io
 * License:
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'woocommerce_archive_description', 'wc_category_description' );
function wc_category_description() {
	if ( is_product_category() ) {

		$queried_object = get_queried_object();
		$taxonomy       = $queried_object->taxonomy;
		$term_id        = $queried_object->term_id;
		$woo_meta       = get_field( 'general', $taxonomy . '_' . $term_id );
		?>
		<div class="product-tax-meta">
			<?php echo $woo_meta; ?>
		</div>
		<?php
	}
}