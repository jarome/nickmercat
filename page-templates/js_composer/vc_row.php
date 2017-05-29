<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = $ratio_parallax ='';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'css' 			  => '',
    'id'			  => 'section-' . rand(1111, 9999),	
    'parallax'		  =>	'off',
    'ratio'			  => 1,
    'bg_video'		  => '',
    'custom_overlay'	=>	'no',
    'color_overlay'		=>	'',
    'opacity'			=>	apply_filters( 'neat_homepage_opacity' , '0.7')
), $atts));

// wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
// wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row '. ( $this->settings('base')==='vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);


if( $this->settings['base'] == 'vc_row' && $parallax == 'on' ){
	$ratio_parallax = 'data-stellar-background-ratio="'.$ratio.'"';
}

if( $this->settings['base'] == 'vc_row' ){
	$output .= '<section id="'.$id.'" '.$ratio_parallax.' class="'.$css_class.'"'.$style.'>';
		if( !empty( $custom_overlay ) && $custom_overlay == 'inherit' ) : $output .= '<div class="color-overlay"></div>'; endif;
		if( !empty( $custom_overlay ) && $custom_overlay == 'custom' && !empty( $color_overlay ) ) : $output .= '<div style="opacity:'.$opacity.';background:'.$color_overlay.'" class="color-overlay"></div>'; endif;
		// Background video.
		if( !empty( $bg_video ) ){
			$rand	=	rand(1111, 9999);
			//$output .= '<a id="bgndVideo" class="player" data-property="{videoURL:\''.$bg_video.'\',containment:\'#player-'.$rand.'\',autoPlay:true, mute:true, startAt:0, opacity:1}"></a>';
			$output .= '<div class="bg_player" id="player-'.$rand.'"></div>
				<a id="video" class="player" data-property="{videoURL:\''.$bg_video.'\',containment:\'#player-'.$rand.'\', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, addRaster:false, quality:\'default\'}"></a>
			';
		}
	$output .= wpb_js_remove_wpautop($content);
	$output .= '</section>'.$this->endBlockComment('row');	
}
else{
	$output .= '<div class="'.$css_class.'"'.$style.'>';
	$output .= wpb_js_remove_wpautop($content);
	$output .= '</div>'.$this->endBlockComment('row');	
}

echo $output;