<?php
/**
 * Template Name: Page Builder
 */
if( !defined('ABSPATH') ) exit;
// get the header.
get_header();

	if( have_posts() ) : the_post();
		// do the action here.
		// do not load the faceboo comment in this template
		remove_action( 'the_content' , 'neat_add_facebook_comment', 40, 1);
		the_content();
	endif;

get_footer();?>