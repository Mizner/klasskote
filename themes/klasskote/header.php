<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Knoxweb
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<base href="<?= home_url( '/' ); ?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'knoxweb' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php the_custom_logo(); ?>
			<button id="theShop">Shop Menu</button>
		</div><!-- .site-branding -->
		<div class="main-menu">
			<div class="client-info">
				<?php show_phone_number(); ?>
				<?php show_social_media_icons(); ?>
				<?php
				$top_args = [
					'theme_location' => 'top',
					'menu_id'        => 'topMenu'
				];
				wp_nav_menu($top_args) ?>

			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="primary-menu"
				        aria-expanded="false"><?php esc_html_e( 'Menu', 'knoxweb' ); ?></button>
				<?php
				$nav_args = [
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu'
				];
				wp_nav_menu( $nav_args ); ?>
			</nav><!-- #site-navigation -->
			<div class="search-container">
				<?php
				if ( function_exists( 'woocommerce_product_search' ) ) {
					echo woocommerce_product_search();
				}
				?>
			</div>
		</div><!--main-menu-->

		<?php
		dynamic_sidebar('mega-menu')
		?>

	</header><!-- #masthead -->


	<div id="content" class="site-content">

