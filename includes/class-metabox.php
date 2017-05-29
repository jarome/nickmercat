<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists( 'Neat_MetaBox' ) ){
	class Neat_MetaBox {
		function __construct() {
			add_action( 'init' , array( $this ,'load_cmb_Meta_Box' ), 9999);
			add_filter( 'cmb_meta_boxes', array( $this ,'metaboxes' ) );
		}
		function load_cmb_Meta_Box(){
			if ( ! class_exists( 'cmb_Meta_Box' ) )
				require_once ( NEAT_THEME_DIR . '/includes/metaboxs/init.php');		
		}
		function metaboxes( array $meta_boxes ){
			// testimonial.
			global $neattheme;
			$prefix = 'testimonial_';
			$meta_boxes['testimonial'] = array(
				'id'         => 'testimonial',
				'title'      => __( 'Testimonial', 'neat' ),
				'pages'      => array( 'testimonial' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields'     => array(					
					array(
						'name'       => __( 'Position', 'neat' ),
						'id'         => $prefix . 'position',
						'type'       => 'text'
					),
					array(
						'name'       => __( 'Website', 'neat' ),
						'id'         => $prefix . 'website',
						'type'       => 'text'
					)						
				)
			);	
			$meta_boxes['post_template'] = array(
				'id'         => 'post_template',
				'title'      => __( 'Template', 'neat' ),
				'pages'      => array( 'post' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields'     => array(
					array(
						'name'       => __( 'Sidebar', 'neat' ),
						'id'         => 'post_template_sidebar',
						'type'       => 'select',
						'options' => array(
							'r_sidebar'		=> __( 'Default - Right Sidebar', 'neat' ),
							'l_sidebar'		=> __( 'Left Sidebar', 'neat' ),
							'fullwidth'		=> __( 'Fullwidth', 'neat' )
						)
					),
					array(
						'name'       => __( 'Show Author Box', 'neat' ),
						'id'         => 'post_template_authorbox',
						'type'       => 'checkbox'
					)
				)
			);
			
			return $meta_boxes;
		}
	}
	new Neat_MetaBox();
}