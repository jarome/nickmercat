<?php
if( !defined('ABSPATH') ) exit; // Don't access me directly.
if( !function_exists( 'neat_custom_style' ) ){
	/**
	 * Adding the custom style in Styling.
	 */
	function neat_custom_style() {
		global $neattheme;
		$output = '';
		if( !empty( $neattheme['header-background'] ) && $neattheme['header-background'] != '#ffffff' ){
			$output .= 'header.navigation.headroom{background:'.esc_attr( $neattheme['header-background'] ).'}';
		}
		if( !empty( $neattheme['menu-color'] ) && $neattheme['menu-color'] != '#b3b3b3' ){
			$output .= 'header.navigation.headroom ul li.nav-link a{color:'.esc_attr( $neattheme['menu-color'] ).'}';
		}
		if( !empty( $neattheme['menu-hover-color'] ) && $neattheme['menu-hover-color'] != '#2ecc71' ){
			$output .= 'header.navigation.headroom ul li.nav-link a:hover{color:'.esc_attr($neattheme['menu-hover-color']).';}';
		}
		if( !empty( $neattheme['footer-background'] ) && $neattheme['footer-background'] != '#ffffff' ){
			$output .= 'footer.footer{background:'.esc_attr($neattheme['footer-background']).';}';
		}
		if( !empty( $neattheme['footer-color'] ) && $neattheme['footer-color'] != '#bfbfbf' ){
			$output .= 'footer.footer p,footer.footer a{color:'.esc_attr($neattheme['footer-color']).'!important;}';
		}
		if( !empty($output ) ){
			print '<style>'.$output.'</style>';
		}
	}
	add_action( 'wp_footer' , 'neat_custom_style');
}