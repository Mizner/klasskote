<?php
function the_project() {
	$project = "klasskote";

	return $project;
}

if ( ! function_exists( 'knoxweb_setup' ) ) :

	function knoxweb_setup() {

		load_theme_textdomain( 'knoxweb', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'top'     => esc_html__( 'Top Menu', 'knoxweb' ),
			'primary' => esc_html__( 'Primary Menu', 'knoxweb' ),
			'footer'  => esc_html__( 'Footer Menu', 'knoxweb' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'post-formats', array(
			// 'aside',
			// 'image',
			// 'video',
			// 'quote',
			// 'link',
		) );
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );
	}
endif; // knoxweb_setup

add_action( 'after_setup_theme', 'knoxweb_setup' );

add_action( 'after_setup_theme', 'knoxweb_content_width', 0 );
function knoxweb_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'knoxweb_content_width', 640 );
}

add_action( 'widgets_init', 'knoxweb_widgets_init' );
function knoxweb_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'knoxweb' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Copyright', 'knoxweb' ),
		'id'            => 'footer-copyright',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Home Testimonials', 'knoxweb' ),
		'id'            => 'home-testimonials',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Blog Category', 'knoxweb' ),
		'id'            => 'blogcategory',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Home Featured', 'knoxweb' ),
		'id'            => 'home-featured',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Mega Menu', 'knoxweb' ),
		'id'            => 'mega-menu',
		'before_widget' => '<aside id="megaMenu" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}


// Enqueue scripts and styles.

add_action( 'wp_enqueue_scripts', 'knoxweb_scripts' );
function knoxweb_scripts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Raleway' );

	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );

	wp_enqueue_style( 'knoxweb-style', get_template_directory_uri() . '/dist/' . the_project() . '.min.css' );

	wp_enqueue_style( 'knoxweb-theme', get_stylesheet_uri() );

	wp_enqueue_script( 'knoxweb-navigation', get_template_directory_uri() . '/dist/' . the_project() . '.min.js', array(), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}


// Implement the Custom Header feature.
// require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Custom functions that act independently of the theme templates.
// require get_template_directory() . '/inc/extras.php';

// Customizer additions.
require get_template_directory() . '/inc/customizer.php';

// Load Jetpack compatibility file.
require get_template_directory() . '/inc/jetpack.php';

// Social Media
require get_template_directory() . '/inc/social-media.php';

// Phone Number
require get_template_directory() . '/inc/phone-number.php';

// Divi Plugin Support
require get_template_directory() . '/inc/divi-support.php';

require get_template_directory() . '/inc/woocommerce.php';

include( 'inc/woocommerce-cart.php' );