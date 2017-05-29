<?php
/**
 * Create the default template in VC.
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists( 'neat_vc_template_video_v2' ) ){
	function neat_vc_template_video_v2($data) {
		$template               = array();
		$template['name']       = __( 'Video Site V2', 'neat' );
		$template['image_path'] = get_template_directory_uri() .'/assets/img/video-v2.png'; // always use preg replace to be sure that "space" will not break logic
		$template['custom_class'] = 'video-v2';
		$template['content']    = <<<CONTENT
[vc_row el_class="blog" ratio="1" id="section-1682" custom_overlay="no"][vc_column width="1/1" css=".vc_custom_1410079853928{margin-top: 60px !important;}"][vc_row_inner][vc_column_inner width="1/1"][neat_heading title="LATEST VIDEOS"]WE SHARE CRUNCHY DETAILS ON A REGULAR BASIS – HERE ARE OUR LATEST VIDEOS.[/neat_heading][neat_latest_post type="main" showposts="12" id="block-4058" orderby="ID" order="DESC" post_format="post-format-video"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT;
		array_unshift($data, $template);
		return $data;
	}	
	add_filter( 'vc_load_default_templates', 'neat_vc_template_video_v2', 110, 1 );
}