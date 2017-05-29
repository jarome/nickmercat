<?php
/**
 * Row shortcode param.
 */
if( !defined('ABSPATH') ) exit;

if( !function_exists( 'neat_add_column_css_animation' ) ){
	/**
	 * Parallax.
	 */
	function neat_add_column_css_animation() {
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' => 'dropdown',
			'param_name' => 'css_animation',
			'heading'	=>	__('CSS Animation','neat'),
			'value'	=>	array( 
				__('No','neat') 	=>	'',
				__('Fade In Up','neat')	=>	'fadeInUp',
				__('Fade In Down','neat')	=>	'fadeInDown',
				__('Fade In Left','neat')	=>	'fadeInLeft',
				__('Fade In Right','neat')	=>	'fadeInRight',
				__('Appear from center','neat')	=>	'bounceIn'
			)
		);
		vc_add_param('vc_column_inner', $attributes);
	}
	add_action( 'init' , 'neat_add_column_css_animation');
}