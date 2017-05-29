<?php
if( !defined('ABSPATH') ) exit;
/**
 * Required Plugins.
 * Version: 1.0
 * Author: Toan Nguyen
 * URL: http://themeforest.net/user/phpface
 */

if( !function_exists( 'neat_required_plugins' ) ){

	function neat_required_plugins() {
	
		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'					=>	'Redux Framework',
				'slug'					=>	'redux-framework',
				'required'				=>	true
			),
			array(
				'name'					=>	'WPBakery Visual Composer',
				'slug'					=>	'js_composer',
				'source'				=>	get_template_directory() . '/plugins/js_composer.zip',
				'version'				=>	'4.12',
				'required'				=>	true
			),
			array(
				'name'					=>	'Revolution Slider',
				'slug'					=>	'revslider',
				'source'				=>	get_template_directory() . '/plugins/revslider.zip',
				'version'				=>	'5.2.5',
				'required'				=>	true
			),
			array(
				'name'					=>	'Vafpress Post Formats UI',
				'slug'					=>	'vafpress-post-formats-ui-develop',
				'source'				=>	get_template_directory() . '/plugins/vafpress-post-formats-ui-develop.zip',
				'required'				=>	false,
			),	
			array(
				'name'					=>	'Twitter Oauth',
				'slug'					=>	'TwitterOauth',
				'source'				=>	get_template_directory() . '/plugins/TwitterOauth.zip',
				'required'				=>	false
			),
			array(
				'name'					=>	'Contact Form 7',
				'slug'					=>	'contact-form-7',
				'required'				=>	false
			)		
		);
		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                   // Automatically activate plugins after installation or not.
			'message'      => ''
		);
		
		tgmpa( $plugins, $config );		
	
	}
	add_action( 'tgmpa_register', 'neat_required_plugins' );
}
if( function_exists( 'vc_set_as_theme' ) ){
	vc_set_as_theme( apply_filters( 'neat_vc_set_as_theme' , true) );
}