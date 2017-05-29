<?php
if( !function_exists( 'neat_vc_fontawesome_attr' ) ){
	function neat_vc_fontawesome_attr( $settings, $value ) {
		$html = null;
		if( !function_exists( 'ebor_icons_list' ) )
			return;
		$icon_array = function_exists( 'ebor_icons_list' ) ? ebor_icons_list() : null;
		$dependency = vc_generate_dependencies_attributes($settings);
		$html .= '<div class="vc_fontawesome_attr">';
			$html .= '<select name="'.$settings['param_name'].'" id="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field">';
				foreach ( $icon_array  as $k=>$v) {
					$html .= '<option '.selected( $value, $k, false ).' value="'.$k.'">'.$v.'</option>';
				}
			$html .= '</select>';
		$html .= '</div>';
		return $html;
	}
}
if( function_exists( 'add_shortcode_param' ) ){
	add_shortcode_param( 'fontawesome' , 'neat_vc_fontawesome_attr');
}