<?php
/**
 * Row shortcode param.
 */
if( !defined('ABSPATH') ) exit;

if( !function_exists( 'neat_add_row_param_parallax' ) ){
	/**
	 * Parallax.
	 */
	function neat_add_row_param_parallax() {
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' => 'checkbox',
			'param_name' => 'parallax',
			'value'	=>	array( __('Enable Parallax.','neat') 	=>	'on' )
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_parallax');
}

if( !function_exists( 'neat_add_row_param_parallax_ratio' ) ){
	function neat_add_row_param_parallax_ratio() {
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' => 'textfield',
			'heading' => __('Ratio','neat'),
			'param_name' => 'ratio',
			'value'	=>	1,
			'description'	=>	__('The ratio is relative to the natural scroll speed, so a ratio of 0.5 would cause the element to scroll at half-speed, a ratio of 1 would have no effect, and a ratio of 2 would cause the element to scroll at twice the speed','neat'),
			'dependency'	=>	array(
					'element'	=>	'parallax',
					'value'	=>	'on'
			)
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_parallax_ratio');
}

if( !function_exists( 'neat_add_row_param_id' ) ){
	/**
	 * Row ID.
	 */
	function neat_add_row_param_id(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' 			=> 'textfield',
			'heading' 		=> __('ID','neat'),
			'param_name' 	=> 'id',
			'description'	=>	__('Put an unique ID','neat'),
			'value'			=>	'section-' . rand(1111, 9999)
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_id');
}

if( !function_exists( 'neat_add_row_param_color_overlay_checkbox' ) ){
	// Background Image
	function neat_add_row_param_color_overlay_checkbox(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' 			=> 'dropdown',
			'param_name' 	=> 'custom_overlay',
			'heading'		=>	__('Overlay Color','neat'),
			'description'	=>	__('Set the Overlay color.','neat'),
			'value'		=>	array(
				__('No', 'neat') 	=>	'no',
				__('Inherit from main style','neat')	=>	'inherit',
				__('I want to set this color.','neat') 	=>	'custom'	
			)
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_color_overlay_checkbox');
}

if( !function_exists( 'neat_add_row_param_color_overlay' ) ){
	// Background Image
	function neat_add_row_param_color_overlay(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' 			=> 'colorpicker',
			'heading' 		=> __('Color Overlay','neat'),
			'param_name' 	=> 'color_overlay',
			'description' => __( 'Select Color Overlay for your row', 'neat' ),
			'dependency'	=>	array(
				'element'	=>	'custom_overlay',
				'value'	=>	'custom'
			)				
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_color_overlay');
}

if( !function_exists( 'neat_add_row_param_opacity' ) ){
	// Background Image
	function neat_add_row_param_opacity(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' 			=> 'textfield',
			'heading' 		=> __('Opacity','neat'),
			'param_name' 	=> 'opacity',
			'dependency'	=>	array(
				'element'	=>	'custom_overlay',
				'value'	=>	'custom'
			),
			'default'	=>	'0.5'
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_opacity');
}

if( !function_exists( 'neat_add_row_param_bg_video' ) ){
	// Background Video
	function neat_add_row_param_bg_video(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' 			=> 'textfield',
			'heading' 		=> __('Background Video','neat'),
			'param_name' 	=> 'bg_video',
			'description'	=>	__('Using this Youtube video link to set the background for this Section, this color will be converted to RGBA.','neat'),
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_bg_video');
}
/**
if( !function_exists( 'neat_add_row_param_bg_image' ) ){
	// Background Image
	function neat_add_row_param_bg_image(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' 			=> 'attach_image',
			'heading' 		=> __('Background Image','neat'),
			'param_name' 	=> 'bg_image',
			'description' => __( 'Select background image for your row', 'neat' )
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_bg_image');
}

if( !function_exists( 'neat_add_row_param_bg_color' ) ){
	function neat_add_row_param_bg_color(){
		if( !function_exists( 'vc_add_param' ) ) return;
		$attributes = array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Color', 'neat' ),
			'param_name' => 'bg_color',
			'description' => __( 'Select Background color', 'neat' )
		);
		vc_add_param('vc_row', $attributes);
	}
	add_action( 'init' , 'neat_add_row_param_bg_color');
}
**/