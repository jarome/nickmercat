<?php
/**
 * Don't access me directly.
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists( 'neat_remove_VC_default_templates' ) ){
	add_filter( 'vc_load_default_templates', 'neat_remove_VC_default_templates' );
	function neat_remove_VC_default_templates($data) {
		return array(); // This will remove all default templates
	}
}

// custom template
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/custom_templates/homepage-default.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/custom_templates/homepage-slider.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/custom_templates/homepage-video.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/custom_templates/blog-masonry-fullwidth.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/custom_templates/homepage-video-v2.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/custom_templates/homepage-video-v3.php');
// attribute
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/attribute/post-category.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/attribute/order.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/attribute/orderby.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/attribute/fontawesome.php');
// param
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/param/row.php');
require_once ( NEAT_THEME_DIR . '/page-templates/js_composer/param/vc_column_inner.php');