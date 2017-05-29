<?php
/**
 * Create the default template in VC.
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists( 'neat_vc_template_blog_masonry_fullwidth' ) ){
	function neat_vc_template_blog_masonry_fullwidth($data) {
		$template               = array();
		$template['name']       = __( 'Blog Masonry Fullwidth', 'neat' );
		$template['image_path'] = get_template_directory_uri() .'/assets/img/masonry-fullwidth.png'; // always use preg replace to be sure that "space" will not break logic
		$template['custom_class'] = 'masonry-fullwidth';
		$template['content']    = <<<CONTENT
[vc_row ratio="1" id="section-1466" custom_overlay="no" el_class="blog" css=".vc_custom_1409456226427{background-color: #ffffff !important;}"][vc_column width="1/1" css=".vc_custom_1409463254958{margin-top: 50px !important;}"][neat_heading title="LATEST NEWS"]WE SHARE CRUNCHY DETAILS ON A REGULAR BASIS - HERE ARE OUR LATEST POSTS.[/neat_heading][neat_latest_post showposts="9" id="block-2125" link="http://marstheme.com/theme/neat/blog/" orderby="ID" order="DESC" type="main"][/vc_column][/vc_row]
CONTENT;
		array_unshift($data, $template);
		return $data;
	}	
	add_filter( 'vc_load_default_templates', 'neat_vc_template_blog_masonry_fullwidth', 110, 1 );
}