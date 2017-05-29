<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists( 'Neat_Post_Type' ) ){
	class Neat_Post_Type {
		function __construct() {
			add_action( 'init' , array( $this ,'testimonial' ));
		}
		function testimonial() {
			register_post_type( 'testimonial' , array(
				'label' => __('Testimonials','neat'),
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'testimonial', 'with_front' => true),
				'query_var' => true,
				'exclude_from_search' => true,
				'supports' => array('title','editor','thumbnail'),
				'labels' => array (
					'name' => __('Testimonials','neat'),
					'singular_name' => __('Testimonials','neat'),
					'menu_name' => __('Testimonials','neat'),
					'add_new' => __('Add Testimonials','neat'),
					'add_new_item' => __('Add New Testimonials','neat'),
					'edit' => 'Edit',
					'edit_item' => __('Edit Testimonials','neat'),
					'new_item' => __('New Testimonials','neat'),
					'view' => __('View Testimonials','neat'),
					'view_item' => __('View Testimonials','neat'),
					'search_items' => __('Search Testimonials','neat'),
					'not_found' => __('No Testimonials Found','neat'),
					'not_found_in_trash' => __('No Testimonials Found in Trash','neat'),
					'parent' => __('Parent Testimonials','neat'),
					)
				)
			);
		}		
	}
	new Neat_Post_Type();
}
