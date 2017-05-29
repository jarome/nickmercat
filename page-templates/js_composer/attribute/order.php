<?php
if( !function_exists( 'neat_order_attr' ) ){
	
	function neat_order_attr( $settings, $value ) {
		$html = null;
		$order_array = neat_option_order();
		$dependency = vc_generate_dependencies_attributes($settings);
		$html .= '<div class="order_attr">';
		$html .= '<select name="'.$settings['param_name'].'" id="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field">';
		foreach ( $order_array  as $k=>$v) {
			$html .= '<option '.selected( $value, $k, false ).' value="'.$k.'">'.$v.'</option>';
		}
		$html .= '</select>';
		$html .= '</div>';
		return $html;
	}	
}

if( function_exists( 'add_shortcode_param' ) ){
	add_shortcode_param( 'order' , 'neat_order_attr');
}