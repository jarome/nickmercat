<?php
/**
 * Author: Toan Nguyen
 * Version: 1.0
 * Main Functions.
 */
if( !defined('ABSPATH') ) exit; // Don't access me directly.
if ( !isset( $content_width ) ) $content_width = 900;
/**
 * Define Theme URI
 */
if( !defined('NEAT_THEME_URI') )
	define('NEAT_THEME_URI', get_template_directory_uri());
/**
 * Define Theme DIR.
 */
if( !defined('NEAT_THEME_DIR') )
	define('NEAT_THEME_DIR', get_template_directory());
/**
 * store the View count from WP Stat jetpack addon.
 */
if( !defined( 'NEAT_WP_VIEW_STAT_META' ) )
	define( 'NEAT_WP_VIEW_STAT_META', 'view_count');
/**
 * store the Facebook like count number
 */
if( !defined( 'NEAT_FACEBOOK_LIKE_COUNT_META' ) )
	define( 'NEAT_FACEBOOK_LIKE_COUNT_META', 'facebook_like_count');
/**
 * store the Facebook share count number
 */
if( !defined( 'NEAT_FACEBOOK_SHARE_COUNT_META' ) )
	define( 'NEAT_FACEBOOK_SHARE_COUNT_META', 'facebook_share_count');
/**
 * store the Facebook comment count number
 */
if( !defined( 'NEAT_FACEBOOK_COMMENT_COUNT_META' ) )
	define( 'NEAT_FACEBOOK_COMMENT_COUNT_META', 'facebook_comment_count');
/**
 * store the Twitter comment count number
 */
if( !defined( 'NEAT_TWITTER_SHARE_COUNT_META' ) )
	define( 'NEAT_TWITTER_SHARE_COUNT_META', 'twitter_share_count');
/**
 * store the Pinterest comment count number
 */
if( !defined( 'NEAT_PINTEREST_SHARE_COUNT_META' ) )
	define( 'NEAT_PINTEREST_SHARE_COUNT_META', 'pinterest_share_count');
/**
 * store the Linkedin comment count number
 */
if( !defined( 'NEAT_LINKEDIN_SHARE_COUNT_META' ) )
	define( 'NEAT_LINKEDIN_SHARE_COUNT_META', 'linkedin_share_count');
/**
 * store the Google Plus share count number
 */
if( !defined( 'NEAT_GOOGLEPLUS_SHARE_COUNT_META' ) )
	define( 'NEAT_GOOGLEPLUS_SHARE_COUNT_META', 'googleplus_share_count');
/**
 * store the total share count from all social sites.
 */
if( !defined( 'NEAT_TOTAL_SHARE_COUNT_META' ) )
	define( 'NEAT_TOTAL_SHARE_COUNT_META', 'total_share_count');
/**
 * store the total like count from all social sites.
 */
if( !defined( 'NEAT_TOTAL_LIKE_COUNT_META' ) )
	define( 'NEAT_TOTAL_LIKE_COUNT_META', 'total_like_count');
/**
 * store the total Comment count from WP and Facebook.
 */
if( !defined( 'NEAT_TOTAL_COMMENT_COUNT_META' ) )
	define( 'NEAT_TOTAL_COMMENT_COUNT_META', 'total_comment_count');
// =========== ending define =========== //

require_once ( NEAT_THEME_DIR . '/includes/functions.php');
require_once ( NEAT_THEME_DIR . '/includes/class-post-table.php');
require_once ( NEAT_THEME_DIR . '/includes/class-social-counter.php');
require_once ( NEAT_THEME_DIR . '/includes/class-menu-walker.php');
require_once ( NEAT_THEME_DIR . '/includes/class-tgm-plugin-activation.php');
require_once ( NEAT_THEME_DIR . '/includes/class-shortcodes.php');
require_once ( NEAT_THEME_DIR . '/includes/class-widgets.php');
require_once ( NEAT_THEME_DIR . '/includes/class-post-type.php');
require_once ( NEAT_THEME_DIR . '/includes/class-metabox.php');
require_once ( NEAT_THEME_DIR . '/includes/class-theme-options.php');
require_once ( NEAT_THEME_DIR . '/includes/required-plugins.php');
require_once ( NEAT_THEME_DIR . '/includes/awesomeicon-array.php');
require_once ( NEAT_THEME_DIR . '/includes/custom-style.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/init.php');
require_once ( NEAT_THEME_DIR . '/includes/class-vc-map.php');
require_once ( NEAT_THEME_DIR . '/includes/awesomeicon-array.php');
require_once ( NEAT_THEME_DIR . '/includes/custom-header.php');
require_once ( NEAT_THEME_DIR . '/includes/media.php');
require_once ( NEAT_THEME_DIR . '/includes/importer/index.php');
if( !function_exists( 'neat_after_setup_theme' ) ){
	/**
	 * Hook into after_setup_theme action.
	 */
	function neat_after_setup_theme() {
		// do something here.
		load_theme_textdomain( 'neat', get_template_directory() . '/languages' );
		//------------------------------ Theme Support -----------------------------------------//
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );		
		add_theme_support( 'woocommerce' );
		add_theme_support('menus');
		add_theme_support('post-thumbnails');
		add_theme_support('custom-background', array(
			'default-color'          => '',
			'default-image'          => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		));
		// require Jetpack installed.
		add_theme_support( 'jetpack-responsive-videos' );
		// V4.1
		add_theme_support( 'title-tag' );
		add_theme_support( 'infinite-scroll', array(
		    'container' => 'content',
		    'footer' => 'page',
		    'posts_per_page' => get_option( 'posts_per_page' ),
		) );
		
		add_theme_support( 'post-formats', array(
			'audio', 'gallery', 'image', 'video','quote'
		) );
		add_theme_support( 'automatic-feed-links' );
		add_image_size( 'post-340-255', 340, 255 );
		// the thumbnail on the sidebar.
		//add_image_size( 'post-100-67', 100, 67 );
	}
	add_action('after_setup_theme', 'neat_after_setup_theme');
}

add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'gallery',
        array(
            'labels' => array(
                'name' => __( 'Gallery' ),
                'singular_name' => __( 'Gallery' )
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
    register_post_type( 'schedule',
        array(
            'labels' => array(
                'name' => __( 'Schedule' ),
                'singular_name' => __( 'Schedule' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title')
        )
    );
}

if( !function_exists( 'neat_enqueue_scripts' ) ){
	/**
	 * Loading the JS/CSS.
	 */
	function neat_enqueue_scripts() {
		global $neattheme;
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		// Load the JS
		wp_enqueue_script('jquery.mb.YTPlayer.js', NEAT_THEME_URI . '/assets/js/jquery.mb.YTPlayer.js', array('jquery'), '', false);
		wp_enqueue_script('jquery.stellar.min.js', NEAT_THEME_URI . '/assets/js/jquery.stellar.min.js', array('jquery'), '', true);
		wp_enqueue_script('headroom.min.js', NEAT_THEME_URI . '/assets/js/headroom.min.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.headroom.js', NEAT_THEME_URI . '/assets/js/jquery.headroom.js', array('jquery'), '', true);
		wp_enqueue_script('owl.carousel.js', NEAT_THEME_URI . '/assets/js/owl.carousel.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.fitvids.js', NEAT_THEME_URI . '/assets/js/jquery.fitvids.js', array('jquery'), '', true);
		wp_enqueue_script('masonry.pkgd.min.js', NEAT_THEME_URI . '/assets/js/masonry.pkgd.min.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.flexslider.js', NEAT_THEME_URI . '/assets/js/jquery.flexslider.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.imagesloaded.min.js', NEAT_THEME_URI . '/assets/js/jquery.imagesloaded.min.js', array('jquery'), '', true);
		wp_enqueue_script('plugins-scroll.js', NEAT_THEME_URI . '/assets/js/plugins-scroll.js', array('jquery'), '', true);
		//wp_enqueue_script('smooth-scroll.js', NEAT_THEME_URI . '/assets/js/smooth-scroll.js', array(), '', true);
		wp_enqueue_script('waypoint.min.js', NEAT_THEME_URI . '/assets/js/waypoint.min.js', array('jquery'), '', true);
		wp_enqueue_script('scripts.js', NEAT_THEME_URI . '/assets/js/scripts.js', array('jquery'), '', true);
		wp_enqueue_script('custom.js', NEAT_THEME_URI . '/assets/js/custom.js', array('jquery'), '', true);
		
		wp_localize_script( 'custom.js' , 'jsvars', apply_filters( 'jsvars' , array(
			'ajaxurl'	=>	admin_url('admin-ajax.php')
		)) );		
		
		// Load the CSS.
		wp_enqueue_style( 'montserrat', neat_font_url(), array(), null );
		wp_enqueue_style('font-awesome-4.6.3', NEAT_THEME_URI . '/assets/css/font-awesome.min.css', array(), null);
		wp_enqueue_style('settings.css', NEAT_THEME_URI . '/assets/css/settings.css', array(), null);
		wp_enqueue_style('animate.css', NEAT_THEME_URI . '/assets/css/animate.css', array(), null);
		//wp_enqueue_style('YTPlayer.css', NEAT_THEME_URI . '/assets/css/YTPlayer.css', array(), null);
		// load Main style.
		$main_style = ( isset( $neattheme['main-style'] ) && !empty(  $neattheme['main-style'] ) ) ?  $neattheme['main-style'] : 'main';
		wp_enqueue_style( $main_style  , NEAT_THEME_URI . '/assets/css/' . $main_style . '.css', array(), null);
		wp_enqueue_style( 'style', get_bloginfo( 'stylesheet_url' ), array(), null );
	}
	add_action('wp_enqueue_scripts', 'neat_enqueue_scripts');
}
if( !function_exists( 'neat_font_url' ) ){
	function neat_font_url() {
		$font_url = '';
		if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'neat' ) ) {
			$font_url = add_query_arg( 'family', urlencode( 'Montserrat:400,700' ), "//fonts.googleapis.com/css" );
		}
	
		return $font_url;
	}	
}

if( !function_exists('neat_admin_enqueue_scripts') ){
	function neat_admin_enqueue_scripts() {
		wp_enqueue_style('redux-admin.css', NEAT_THEME_URI . '/assets/css/redux-admin.css');
		wp_enqueue_style('neat-admin.css', NEAT_THEME_URI . '/assets/css/neat-admin.css');
		wp_enqueue_style('font-awesome-4.6.3', NEAT_THEME_URI . '/assets/css/font-awesome.min.css', array(), null);
	}
	add_action('admin_enqueue_scripts', 'neat_admin_enqueue_scripts');
}
if( !function_exists( 'neat_widgets_init' ) ){
	/**
	 * Register the widgets.
	 */
	function neat_widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Left/Right Sidebar', 'neat' ),
			'description'	=>	__('Archive the Widgets on Left/Right sidebar.','neat'),
			'id'            => 'lr-sidebar',
			'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h4>',
			'after_title'   => '</h4></div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Before the Media Post', 'neat' ),
			'description'	=>	__('Archive the Widgets before the Video/Audio player.','neat'),
			'id'            => 'before-post-sidebar',
			'before_widget' => '<div id="%1$s" class="widget-inner-before %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="inner-title">',
			'after_title'   => '</h4>',
		) );
		register_sidebar( array(
			'name'          => __( 'After the Media Post', 'neat' ),
			'description'	=>	__('Archive the Widgets after the Video/Audio player, before the Title.','neat'),
			'id'            => 'after-post-sidebar',
			'before_widget' => '<div id="%1$s" class="widget-inner-after %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="inner-title">',
			'after_title'   => '</h4>',
		) );
	}
	add_action( 'widgets_init', 'neat_widgets_init' );
}

if( !function_exists('neat_register_menus') ){
	function neat_register_menus() {
		register_nav_menus(
			array(
				'homepage_navigation' => __('Homepage Navigation','neat'),
				'page_navigation' => __('Page Navigation','neat'),
			)
		);
	}
	add_action( 'init', 'neat_register_menus' );
}