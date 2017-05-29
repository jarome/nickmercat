<?php
/**
 * Neat VC Map Class.
 * V1.1
 * Author: Toan Nguyen
 */
if( !defined('ABSPATH') ) exit;
if( !class_exists( 'Neat_VC_Map' ) ){
	class Neat_VC_Map {
		function __construct() {
			if( !function_exists( 'vc_map' ) )
				return;
			add_action( 'init' , array( $this , 'neat_heading' ));
			add_action( 'init' , array( $this , 'neat_feature' ));
			add_action( 'init' , array( $this , 'neat_subscribeform' ));
			add_action( 'init' , array( $this , 'neat_twitter' ));
			add_action( 'init' , array( $this , 'neat_testimonial' ));
			add_action( 'init' , array( $this , 'neat_latest_post' ));
			add_action( 'init' , array( $this , 'neat_loginform' ));
			// widgets
			add_action( 'init' , array( $this, 'neat_profile' ));
			//add_action( 'init' , array( $this, 'neat_posts' ));
		}
		/** Widgets **/
		function neat_profile(){
			add_shortcode( 'neat_profile' , array( $this, 'neat_profile_widget' ));
			$args = array(
				'name'	=>	__('Neat Profile','neat'),
				'base'	=>	'neat_profile',
				'category'	=>	__('WordPress Widgets','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Profile Widget.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Upload Page','neat'),
						'param_name'	=>	'upload_url',
					)			
				)
			);
			vc_map( $args );			
		}
		function neat_profile_widget( $atts, $content = null  ) {
			$output = $title = $el_class = '';
			extract( shortcode_atts( array(
				'title' => '',
				'upload_url' => ''
			), $atts ) );
			
			ob_start();
			the_widget( 'Neat_Profile', $atts, array() );
			//$output = '<div class="neat-widget '.$el_class.'">';
				$output .= ob_get_clean();
			//$output .= '</div>';
			return $output;
		}
		
		function neat_posts(){
			add_shortcode( 'neat_posts' , array( $this, 'neat_posts_widget' ));
			global $_wp_additional_image_sizes;
			$image_size = array();
			if( is_array( $_wp_additional_image_sizes ) ){
				foreach ($_wp_additional_image_sizes as $k=>$v) {
					$image_size[]	=	$k;
				}
			}			
			$args = array(
				'name'	=>	__('Neat Post Widget','neat'),
				'base'	=>	'neat_posts',
				'category'	=>	__('WordPress Widgets','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Posts Widget.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'checkbox',
						'holder'	=>	'div',
						'class'	=>	'',
						'value'	=>	array( __( 'Related Posts','neat' ) => 'on' ),
						'param_name'	=>	'related_posts',
					),
					array(
						'type'	=>	'checkbox',
						'holder'	=>	'div',
						'class'	=>	'',
						'value'	=>	array( __( 'Checking Author\'s Posts','neat' ) => 'on' ),
						'param_name'	=>	'author',
						'description'	=>	__('Detecting author\'s posts automatic.','neat')
					),						
					array(
						'type'	=>	'checkbox',
						'holder'	=>	'div',
						'class'	=>	'',
						'value'	=>	array( __( 'Ignore Sticky Posts','neat' ) => 'on'),
						'param_name'	=>	'ignore_sticky_posts'
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Post Format','neat'),
						'param_name'	=>	'post_format',
						'description'	=>	__('Specify Post Format to retrieve (post-format-standard, post-format-audio, post-format-gallery,post-format-image,post-format-video,post-format-quote), leave blank for all.','neat')
					),						
					array(
						'type'	=>	'post_category',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Category','neat'),
						'param_name'	=>	'category',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Category In','neat'),
						'param_name'	=>	'category__in',
						'description'	=>	__('Specify Categories to retrieve, example: cat_id1,cat_id2,cat_id3','neat')
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Category Not In','neat'),
						'param_name'	=>	'category__not_in',
						'description'	=>	__('Specify Categories NOT to retrieve, example: cat_id1,cat_id2,cat_id3','neat')
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Tags','neat'),
						'param_name'	=>	'post_tag',
						'description'	=>	__('Eg: tag1,tag2,tag3','neat')
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Tags Not In','neat'),
						'param_name'	=>	'tag__not_in',
						'description'	=>	__('Specify Tags Not to retrieve, example: tag_id1,tag_id2,tag_id3','neat')
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Post In','neat'),
						'param_name'	=>	'post__in',
						'description'	=>	__('Specify Posts to retrieve, example post_id1,post_id2,post_id3','neat')
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Post Not In','neat'),
						'param_name'	=>	'post__not_in',
						'description'	=>	__('Specify Posts NOT to retrieve, example post_id1,post_id2,post_id3','neat')
					),
					array(
						'type'	=>	'orderby',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Order by','neat'),
						'param_name'	=>	'orderby'
					),
					array(
						'type'	=>	'order',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Order','neat'),
						'param_name'	=>	'order'
					),
					array(
						'type'	=>	'checkbox',
						'holder'	=>	'div',
						'class'	=>	'',
						'value'	=>	array( __('Show post thumbnail','neat') => 'on'),
						'param_name'	=>	'thumbnail'
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Thumbnail Image Size','neat'),
						'param_name'	=>	'thumbnail_size',
						'description'	=>	sprintf(__('Enter image size. Example: <strong>%s , thumbnail, medium, large, full</strong> or other sizes defined by current theme. Alternatively enter image size in pixels: 200,100 (Width,Height). Leave empty to use "<strong>post-340-255</strong>" size.','neat'), implode(", ",$image_size))
					),	
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Show posts','neat'),
						'param_name'	=>	'showposts'
					)
				)
			);
			vc_map( $args );			
		}
		function neat_posts_widget( $atts, $content = null ){
			$output = $title = $el_class = '';
			extract( shortcode_atts( array(
				'title' 				=> '',
				'related_posts' 		=> '',
				'author'				=>	'',
				'ignore_sticky_posts'	=>	'',
				'post_format'			=>	'',
				'category'				=>	'',
				'category__in'			=>	'',
				'category__not_in'		=>	'',
				'post_tag'				=>	'',
				'tag__not_in'			=>	'',
				'post__in'				=>	'',
				'post__not_in'			=>	'',
				'orderby'				=>	'',
				'order'					=>	'',
				'thumbnail'				=>	'',
				'thumbnail_size'		=>	'',
				'showposts'				=>	''
			), $atts ) );
				
			ob_start();
			the_widget( 'Neat_Posts_Widget', $atts, array() );
			//$output = '<div class="neat-widget '.$el_class.'">';
				$output .= ob_get_clean();
			//$output .= '</div>';
			return $output;			
		}
		/** Shortcodes **/
		function neat_latest_post() {
			global $_wp_additional_image_sizes;
			$image_size = array();
			if( is_array( $_wp_additional_image_sizes ) ){
				foreach ($_wp_additional_image_sizes as $k=>$v) {
					$image_size[]	=	$k;
				}
			}			
			$args = array(
				'name'	=>	__('Posts','neat'),
				'base'	=>	'neat_latest_post',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Feature block.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
						'description'	=>	__('This title is not displayed at frontend.','neat')
					),
					array(
						'type'	=>	'dropdown',
						'holder'	=>	'div',
						'class'	=>	'Type',
						'param_name'	=>	'type',
						'value'	=>	array(
							__('Widget/Block','neat') => 'block',
							__('Main Content','neat') => 'main'
						)
					),					
					array(
						'type'	=>	'checkbox',
						'holder'	=>	'div',
						'class'	=>	'',
						'param_name'	=>	'ignore_sticky_posts',
						'value'	=>	array(
							__('Ignore Sticky Posts','neat')		=>	1	
						)
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Post Formats IN','neat'),
						'param_name'	=>	'post_format',
						'description'	=>	__('Specify Post Format to retrieve (post-format-standard, post-format-audio, post-format-gallery,post-format-image,post-format-video,post-format-quote), leave blank for all.','neat')
					),
					/**
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Columns','neat'),
						'param_name'	=>	'columns'
					),		
					**/				
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Post In','neat'),
						'param_name'	=>	'post__in',
						'description'	=>	__('Specify posts to retrieve, put the Post ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),		
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Post Not In','neat'),
						'param_name'	=>	'post__not_in',
						'description'	=>	__('Specify post NOT to retrieve, put the Post ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Category In','neat'),
						'param_name'	=>	'category__in',
						'description'	=>	__('Put the Category ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),						
						
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Category Not In','neat'),
						'param_name'	=>	'category__not_in',
						'description'	=>	__('Put the Category ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),						
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Tag In','neat'),
						'param_name'	=>	'tag__in',
						'description'	=>	__('Put the Tag ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),						
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Tag Not In','neat'),
						'param_name'	=>	'tag__not_in',
						'description'	=>	__('Put the Tag ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),
					array(
						'type'	=>	'orderby',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Order By','neat'),
						'param_name'	=>	'orderby'
					),	
					array(
						'type'	=>	'order',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Order','neat'),
						'param_name'	=>	'order'
					),	
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Show Posts','neat'),
						'param_name'	=>	'showposts',
						'value'	=>	get_option( 'posts_per_page' )
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Author In','neat'),
						'param_name'	=>	'author__in',
						'description'	=>	__('Put the Author ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),						
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Author Not In','neat'),
						'param_name'	=>	'author__not_in',
						'description'	=>	__('Put the Author ID(s), separated by commas(,), example: 1,4,7,8.','neat')
					),	
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('ID','neat'),
						'param_name'	=>	'id',
						'value'	=>	'block-' . rand(1000, 9999),
					),					
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('More Link','neat'),
						'param_name'	=>	'link',
						'value'	=>	get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : ''
					),						
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Class','neat'),
						'param_name'	=>	'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'neat' )
					)
				)
			);
			vc_map( $args );
		}
		
		/**
		 * Map the neat_feature shortcode.
		 */
		function neat_feature(){
			$args = array(
				'name'	=>	__('Feature','neat'),
				'base'	=>	'neat_feature',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Feature block.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'fontawesome',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Icon','neat'),
						'param_name'	=>	'icon'
					),						
					array(
						'type'	=>	'textarea_html',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Content','neat'),
						'param_name'	=>	'content',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Class','neat'),
						'param_name'	=>	'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'neat' )
					)
				)
			);
			vc_map( $args );			
		}
		/**
		 * Map the neat_heading shortcode.
		 */
		function neat_heading(){
			$args = array(
				'name'	=>	__('Heading','neat'),
				'base'	=>	'neat_heading',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Heading of the Section.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'textarea_html',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Content','neat'),
						'param_name'	=>	'content',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Class','neat'),
						'param_name'	=>	'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'neat' )
					)
				)
			);
			vc_map( $args );			
		}
		/**
		 * map the subscribeform shortcode.
		 */
		function neat_subscribeform(){
			$args = array(
				'name'	=>	__('Subscribe Form','neat'),
				'base'	=>	'neat_subscribeform',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Subscribe Form.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'dropdown',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('How it works?','neat'),
						'param_name'	=>	'type',
						'value'	=>	array(
							__('Save the Email as Subscriber User','neat') 		=>		'default',
							__('Forward the data to another site','neat') 		=>		'external'
						)
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('External Link','neat'),
						'description'	=>	__('This link will handle the action.','neat'),
						'param_name'	=>	'external_link',
						'dependency'	=>	array(
							'element'	=>	'type',
							'value'	=>	'external'
						),							
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Class','neat'),
						'param_name'	=>	'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'neat' )
					)
				)
			);
			vc_map( $args );			
		}
		function neat_twitter() {
			$args = array(
				'name'	=>	__('Twitter Feeds','neat'),
				'base'	=>	'neat_twitter',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Twitter Feeds.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Username','neat'),
						'param_name'	=>	'username',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Consumer Key','neat'),
						'param_name'	=>	'consumerkey',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Consumer Secret','neat'),
						'param_name'	=>	'consumersecret',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Access Token','neat'),
						'param_name'	=>	'accesstoken',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Access Token Secret','neat'),
						'param_name'	=>	'accesstokensecret',
					),	
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Shows','neat'),
						'description'	=>	__('How many feed will be shown?','neat'),
						'param_name'	=>	'shows',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Class','neat'),
						'param_name'	=>	'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'neat' )
					)						
				)
			);
			vc_map( $args );			
		}
		function neat_testimonial() {
			$args = array(
				'name'	=>	__('Testimonials','neat'),
				'base'	=>	'neat_testimonial',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Heading of the Section.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Show Posts','neat'),
						'param_name'	=>	'showposts',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Class','neat'),
						'param_name'	=>	'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'neat' )
					)
				)
			);
			vc_map( $args );			
		}
		function neat_loginform(){
			$args = array(
				'name'	=>	__('Login Form','neat'),
				'base'	=>	'neat_loginform',
				'category'	=>	__('NeatTheme','neat'),
				'class'	=>	'neat',
				'icon'	=>	'neat',
				'description'	=>	__('Display the Login Form.','neat'),
				'params'	=>	array(
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Title','neat'),
						'param_name'	=>	'title',
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Label Username','neat'),
						'param_name'	=>	'label_username',
						'value'		=>	__( 'Username','neat' )
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Label Password','neat'),
						'param_name'	=>	'label_password',
						'value'		=>	__( 'Password','neat' )
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Label Remeberme','neat'),
						'param_name'	=>	'label_remember',
						'value'		=>	__( 'Remember Me','neat' )
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Label Log In','neat'),
						'param_name'	=>	'label_log_in',
						'value'		=>	__( 'Log In','neat' )
					),
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Contact Form 7 ID','neat'),
						'param_name'	=>	'cf7_id',
						'description'	=>	__('Enter the CF 7 form ID if you want to make this page as upload form.','neat')
					),						
					array(
						'type'	=>	'textfield',
						'holder'	=>	'div',
						'class'	=>	'',
						'heading'	=>	__('Redirect','neat'),
						'description'	=>	__('Redirect to this page after logged, leave blank for current page.','neat'),
						'param_name'	=>	'redirect',
						'value'		=>	home_url()
					)
				)
			);
			vc_map( $args );			
		}
	}
	new Neat_VC_Map();
}