<?php
function define_platforms() {
	$social_media_args = array(
		'facebook'  => 'fa-facebook', // fa-facebook-square
		'twitter'   => 'fa-twitter', // fa-twitter-square
		'linkedin'  => 'fa-linkedin', // fa-linkedin-square
		'google'    => 'fa-google', // fa-google-plus-square
		'youtube'   => 'fa-youtube-play', // fa-youtube-square
		'pinterest' => 'fa-pinterest-p', //  fa-pinterest-square
		'yelp'      => 'fa-yelp',
		'instagram' => 'fa-instagram',
	);

	return $social_media_args;
}

function get_social_media_icons( $mod_name, $fa_icon ) {
	$platform = get_theme_mod( $mod_name );

	return "<a href='{$platform}'><i class='fa {$fa_icon}' aria-hidden='true'></i></a>";
}

function show_social_media_icons() {
	$social_media_args = define_platforms();
	echo "<ul class='social-media-icons'>";
	foreach ( (array) $social_media_args as $platform => $icon ) {
		if ( get_theme_mod( $platform ) ) {
			echo '<li class="icon">' . get_social_media_icons( $platform, $icon ) . '</li>';
		}
	}
	echo "</ul>";
}

add_action( 'customize_register', 'knoxweb_customize_register_social_media', 10, 1 );
function knoxweb_customize_register_social_media( $wp_customize ) {
// Add Social Media Section
	$wp_customize->add_section( 'social-media', array(
		'title'       => __( 'Social Media', 'knoxweb' ),
		'priority'    => 30,
		'description' => __( 'Enter details here.', 'knoxweb' )
	) );

	$social_media_args = define_platforms();

	foreach ( (array) $social_media_args as $platform => $icon ) {
		$wp_customize->add_setting( $platform, array( 'default' => '' ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $platform, array(
			'label'    => __( ucfirst( $platform ), 'knoxweb' ),
			'section'  => 'social-media',
			'settings' => $platform,
		) ) );
	}
}


