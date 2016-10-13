<?php
add_action( 'customize_register', 'knoxweb_customize_register_phone' );
function knoxweb_customize_register_phone( $wp_customize ) {
	$wp_customize->add_section( 'phone-field', array(
		'title'    => __( 'Phone Number', 'knoxweb' ),
		'priority' => 30,
		//'description' => __( 'Enter details here.', 'knoxweb' )
	) );

	$wp_customize->add_setting( 'phone', array( 'default' => '' ) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'phone', array(
		'label'    => __( 'Enter Phone Number', 'knoxweb' ),
		'section'  => 'phone-field',
		'settings' => 'phone',
	) ) );
}

function show_phone_number() {
	if ( get_theme_mod( 'phone' ) ) {
		$phone_number = get_theme_mod( 'phone' );
		echo "<a class='phoneNumber' href='tel:+1{$phone_number}'><i class='fa fa-phone'></i> {$phone_number}</a>";
	}
}