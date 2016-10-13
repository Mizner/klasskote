<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Knoxweb
 */

?>
</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<div class="foot-left">
				<?php
				$nav_args = [
					'theme_location' => 'footer',
					'menu_id'        => 'footer-menu'
				];
				wp_nav_menu( $nav_args ); ?>
				<p class="copyright"><?php echo get_theme_mod( 'copyright' ); ?></p>
			</div>
			<div class="foot-right">
				<?php show_phone_number(); ?>
				<?php show_social_media_icons(); ?>
			</div>
		</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
