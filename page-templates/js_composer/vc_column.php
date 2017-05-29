<?php
$output = $font_color = $el_class = $width = $offset = $css_animation = $css_animation_class = $css_animation_data = '';
extract(shortcode_atts(array(
	'font_color'      => '',
    'el_class' => '',
    'width' => '1/1',
    'css' => '',
	'offset' => '',
	'css_animation'	=>	''
), $atts));

if( !empty( $css_animation ) ){
	$css_animation_class	=	'triggerAnimation animated';
	$css_animation_data		=	'data-animate="'.$css_animation.'"';
}

$el_class = $this->getExtraClass($el_class);
$width = wpb_translateColumnWidthToSpan($width);
$width = vc_column_offset_class_merge($offset, $width);
$el_class .= ' wpb_column container ' . $css_animation_class;
$style = $this->buildStyle( $font_color );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
//print $this->settings['base'];
$output .= "\n\t".'<div '.$css_animation_data.' class="'.$css_class.'"'.$style.'>';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment($el_class) . "\n";

echo $output;