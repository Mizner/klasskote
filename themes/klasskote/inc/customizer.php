<?php
/**
 * Knoxweb Theme Customizer.
 *
 * @package Knoxweb
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
add_action( 'customize_register', 'knoxweb_customize_register' );
function knoxweb_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	$wp_customize->add_section( 'copyright-field' , array(
		'title' => __( 'Copyright', 'knoxweb' ),
		'priority' => 99,
		//'description' => __( 'Enter details here.', 'knoxweb' )
	) );

	$wp_customize->add_setting( 'copyright' , array( 'default' => '' ));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'copyright', array(
		'label' => __( 'Enter Copyright Info', 'knoxweb' ),
		'section' => 'copyright-field',
		'settings' => 'copyright',
	) ) );

}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
add_action( 'customize_preview_init', 'knoxweb_customize_preview_js' );
function knoxweb_customize_preview_js() {
	wp_enqueue_script( 'knoxweb_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}

