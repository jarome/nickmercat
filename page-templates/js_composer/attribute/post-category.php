<?php
if( !function_exists( 'neat_post_category_attr' ) ){
	
	/**
	 * post_category_attr
	 * @param array $settings
	 * @param string_type $value
	 * @return string
	 */
	function neat_post_category_attr( $settings, $value ){
		$html = null;
		$dependency = vc_generate_dependencies_attributes($settings);
		$html .= '<div class="post_category_attr">';
		$args = array(
				'show_option_all'    => __('All','neat'),
				'orderby'            => 'ID',
				'order'              => 'ASC',
				'show_count'         => 1,
				'hide_empty'         => 1,
				'child_of'           => 0,
				'echo'               => 0,
				'selected'           => $value,
				'hierarchical'       => 0,
				'name'               => $settings['param_name'],
				'id'                 => $settings['param_name'],
				'class'              => 'wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field',
				'taxonomy'           => 'category',
				'hide_if_empty'      => true,
		);
		$html .= wp_dropdown_categories( $args );
		$html .= '</div>';
		return $html;
	}
}
if( function_exists( 'add_shortcode_param' ) ){
	add_shortcode_param( 'post_category' , 'neat_post_category_attr');
}