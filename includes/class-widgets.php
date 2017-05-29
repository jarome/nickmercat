<?php
/**
 * Neat Widgets.
 */
if( !defined('ABSPATH') ) exit;

if( !function_exists( 'neat_widgets' ) ){
	function neat_widgets() {
		// profile widget
		register_widget('Neat_Profile');
		// register the Post widget.
		register_widget('Neat_Posts_Widget');
	}
	add_action( 'widgets_init', 'neat_widgets');
}

class Neat_Profile extends WP_Widget{
	
	function __construct(){
		$widget_ops = array( 'classname' => 'neat-profile', 'description' => esc_html__('Display the Profile Widget, Login form included.', 'neat') );
	
		parent::__construct( 'neat-profile' , esc_html__('NEAT Profiles', 'ashley') , $widget_ops);
	}	
	
	function widget($args, $instance){
		global $neattheme, $post;
		$output = null;
		extract( $args );
		$title = apply_filters('widget_title', isset( $instance['title'] ) ? $instance['title'] : null );
		$upload_url = !empty( $instance['upload_url'] ) ? get_permalink( $instance['upload_url'] ) : null;
		$output .=  $before_widget;
		if( !empty( $title ) ){
			$output .= $before_title . $title . $after_title;
		}
		
		$args = array(
	        'echo'           => false,
	        'redirect'       => home_url(),
	        'form_id'        => 'vt_loginform',
	        'label_username' => __( 'Username','mars' ),
	        'label_password' => __( 'Password','mars' ),
	        'label_remember' => __( 'Remember Me','mars' ),
	        'label_log_in'   => __( 'Log In','mars' ),
	        'id_username'    => 'user_login',
	        'id_password'    => 'user_pass',
	        'id_remember'    => 'rememberme',
	        'id_submit'      => 'wp-submit',
	        'remember'       => true,
	        'value_username' => NULL,
	        'value_remember' => false
		);
		if( !get_current_user_id() ){
			$output .= wp_login_form( apply_filters( 'neat_profile_widget_loginform_args' , $args) );
		}
		else{
			$user_data = get_user_by('id', get_current_user_id());
			$output .= '
				<div class="profile-widget-header">
					<div class="profile-widget-image">
						<a href="'.get_author_posts_url(get_current_user_id()).'">'.get_avatar( get_current_user_id(), 80 ).'</a>
					</div>					
					<div class="profile-widget-info">
						<h3>'.$user_data->display_name.'</h3>';
						if( !empty( $upload_url ) ){
							$output .= '<span class="profile-widget-info-item"><i class="fa fa-cloud"></i> <a href="'.$upload_url.'">'.__('Upload','neat').'</a></span>';
						}
						$output .= '<span class="profile-widget-info-item"><i class="fa fa-sign-out"></i> <a href="'.wp_logout_url( home_url() ).'">'.__('Sign out','neat').'</a></span>
					</div>
				</div>
			';
		}
		$output .= $after_widget;
		print $output;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['upload_url'] = strip_tags( $new_instance['upload_url'] );
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 
			'title' => __('Profile', 'neat'),
			'upload_url'	=>	''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$pages = get_pages(array('showposts'=>-1));
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'neat'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'upload_url' ); ?>"><?php _e('Upload URL:', 'neat'); ?></label>
			<?php 
				if( $pages ){
					print '<select id="'.$this->get_field_id( 'upload_url' ).'" name="'.$this->get_field_name( 'upload_url' ).'" class="regular-text" style="width: 100%">';
						print '<option value="-1">'.__('Choose One ...','neat').'</option>';
						foreach ( $pages as $page ){
							print '<option '.selected($page->ID, $instance['upload_url']).' value="'.$page->ID.'">'.$page->post_title.'</option>';
						}
					print '</select>';
				}
			?>
		</p>
	<?php		
	}	
}

if( !class_exists( 'Neat_Posts_Widget' ) ){
	class Neat_Posts_Widget extends WP_Widget{
		
		function __construct(){
			$widget_ops = array( 'classname' => 'posts-widget', 'description' => esc_html__('Displaying the posts on the sidebar.', 'neat') );
		
			parent::__construct( 'posts-widget' , esc_html__('NEAT Posts Widget', 'ashley') , $widget_ops);
		}

		function widget($args, $instance){
			global $post, $neattheme;
			$comment_system = isset( $neattheme['comment'] ) ?  $neattheme['comment'] : 'default';
			extract( $args );
			$get_thumbnail_size = '';
			wp_reset_postdata();wp_reset_query();
			$title 					= apply_filters('widget_title', esc_attr( $instance['title'] ) );
			$ignore_sticky_posts	= ( isset( $instance['ignore_sticky_posts'] ) && $instance['ignore_sticky_posts'] == 'on' ) ? true : false;
			$author					= ( isset( $instance['author'] ) && $instance['author'] == 'on' ) ? true : false;
			$related_posts			= ( isset( $instance['related_posts'] ) && $instance['related_posts'] == 'on') ? true : false;
			
			if( $related_posts === true && !is_single() )
				return;
			
			$post_format			= !empty( $instance['post_format'] ) ? strip_tags( $instance['post_format'] ) : null;
			$category				= !empty( $instance['category'] ) ? absint( $instance['category'] ) : null;
			$category__in			= !empty( $instance['category__in'] ) ? strip_tags( $instance['category__in'] ) : null;
			$category__not_in		= !empty( $instance['category__not_in'] ) ? strip_tags( $instance['category__not_in'] ) : null;
			$tag__not_in			= !empty( $instance['tag__not_in'] ) ? strip_tags( $instance['tag__not_in'] ) : null;
			$post__in				= !empty( $instance['post__in'] ) ? strip_tags( $instance['post__in'] ) : null;
			$post__not_in			= !empty( $instance['post__not_in'] ) ? strip_tags( $instance['post__not_in'] ) : null;
			$orderby				= !empty( $instance['orderby'] ) ? strip_tags( $instance['orderby'] ) : 'ID';
			$order					= !empty( $instance['order'] ) ? strip_tags( $instance['order'] ) : 'DESC';
			$thumbnail				= ( isset( $instance['thumbnail'] ) && $instance['thumbnail'] == 'on' ) ? true : false;
			$thumbnail_size 		= !empty( $instance['thumbnail_size'] ) ? strip_tags( $instance['thumbnail_size'] ) : 'post-340-255';
			$showposts				= !empty( $instance['showposts'] ) ? absint( $instance['showposts'] ) : null;
			$el_class				= !empty( $instance['el_class'] ) ? strip_tags( $instance['el_class'] ) : null;
			
			$get_thumbnail_size	=	explode( "," , $thumbnail_size);
			if( is_array( $get_thumbnail_size ) && count( $get_thumbnail_size ) == 2 ){
				// yeah, this is the custom size number.
			}
			else{
				$get_thumbnail_size	=	$thumbnail_size;
			}

			$post_data = array(
				'post_type'		=>	'post',
				'post_status'	=>	'publish',
				'showposts'		=>	$showposts,
				'ignore_sticky_posts'	=>	$ignore_sticky_posts,
				'orderby'		=>	$orderby,
				'order'			=>	$order
			);
			
			// check order by meta fields as like, share, view, comment_count
			// 1. check comment_count if facebook comment is activated.
			$comment_system = isset( $neattheme['comment'] ) ?  $neattheme['comment'] : 'default';
			if( $comment_system != 'default' && $orderby == 'comment_count' ){
				$post_data['meta_key']	=	NEAT_TOTAL_COMMENT_COUNT_META;
				$post_data['orderby']	=	'meta_value_num';
			}
			// 2. check like orderby.
			if( $orderby == 'like' ){
				$post_data['meta_key']	=	NEAT_TOTAL_LIKE_COUNT_META;
				$post_data['orderby']	=	'meta_value_num';
			}
			// 3. check share orderby
			if( $orderby == 'share' ){
				$post_data['meta_key']	=	NEAT_TOTAL_SHARE_COUNT_META;
				$post_data['orderby']	=	'meta_value_num';
			}
			// 4 view count.
			if( $orderby == 'view' && function_exists( 'stats_get_csv' ) ){
				// make sure you installed jetpack Stat addon.
				$post_data['meta_key']	=	NEAT_WP_VIEW_STAT_META;
				$post_data['orderby']	=	'meta_value_num';
			}			
	
			// check post format
 			if( !empty( $post_format ) ){
				$post_format	=	explode(",", $post_format);
				if( is_array( $post_format ) && !empty( $post_format ) ){
				$post_data['tax_query'][] = array(
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'terms'    => $post_format,
						'operator'	=>	'IN'
					);
				}
			}
			
			if( is_integer( $category ) ){
				$post_data['cat']	=	$category;
			}
			
			if( !empty( $category__in ) ){
				$category__in	=	explode( "," , $category__in);
				if( is_array( $category__in ) && !empty( $category__in ) ){
					$post_data['category__in']	=	$category__in;
				}
			}
			if( !empty( $category__not_in ) ){
				$category__not_in	=	explode( "," , $category__not_in);
				if( is_array( $category__not_in ) && !empty( $category__not_in ) ){
					$post_data['category__not_in']	=	$category__not_in;
				}
			}
			if( !empty( $tag__not_in ) ){
				$tag__not_in	=	explode( "," , $tag__not_in);
				if( is_array( $tag__not_in ) && !empty( $tag__not_in ) ){
					$post_data['tag__not_in']	=	$tag__not_in;
				}
			}
			if( !empty( $post__in ) ){
				$post__in	=	explode( "," , $post__in);
				if( is_array( $post__in ) && !empty( $post__in ) ){
					$post_data['post__in']	=	$post__in;
				}
			}			
			if( !empty( $post__not_in ) ){
				$post__not_in	=	explode( "," , $post__not_in);
				if( is_array( $post__not_in ) && !empty( $post__not_in ) ){
					$post_data['post__not_in']	=	$post__not_in;
				}
			}
			// Not in the current post.
			if( isset( $post->ID ) && is_integer( $post->ID ) ){
				$post_data['post__not_in'][]	=	$post->ID;
			}
			// check related post;
			if( $related_posts === true && isset( $post->ID )){
				// get current post categories.
				$categor_terms = wp_get_post_terms($post->ID, 'category' , array("fields" => "ids"));
				// get current post tags
				$tag_terms = wp_get_post_terms($post->ID, 'post_tag' , array("fields" => "ids"));
				// query the category
				if( !empty( $categor_terms ) && is_array( $categor_terms ) && apply_filters( 'neat_related_post_category' , true) === true ){
					$post_data['category__in'] = $categor_terms;
				}
				// query the tag.
				if( !empty( $tag_terms ) && is_array( $tag_terms ) && apply_filters( 'neat_related_post_tag' , true) === true ){
					$post_data['tag__in'] = $tag_terms;
				}				
			}
			// Check author.
			if( $author === true ){
				// check if this is the author page.
				$author_id = get_the_author_meta('ID');
				if( isset( $author_id ) ){
					$post_data['author']	= $author_id;
				}
			}
			// for developer.
			/**
			 * neat_posts_widget_args filter.
			 * array $post_data
			 */
			$post_data	=	apply_filters( 'neat_posts_widget_args' , $post_data, $this->id);
			$post_query = new WP_Query( $post_data );
			if( $post_query->have_posts() ):
				print  $before_widget;
				if( !empty( $title ) )
					print $before_title . $title . $after_title;			
				print '<ul class="posts-widget '.$el_class.'" id="'.$this->id.'">';
					while ( $post_query->have_posts() ): $post_query->the_post();	
						?>
							<li class="posts-widget-tem post-item<?php the_ID();?>">
								<?php if( has_post_thumbnail() && $thumbnail === true ):?>
									<?php if( get_post_format( get_the_ID() )  == 'video'):?>
										<div class="media">
											<div class="fitvids">									
									<?php endif;?>
											<a href="<?php the_permalink()?>" title="<?php the_title();?>">
												<?php print get_the_post_thumbnail( get_the_ID(), apply_filters( 'neat_posts_widget_thumbnail_size' ,  $get_thumbnail_size ) );?>
											</a>
									<?php if( get_post_format( get_the_ID() )  == 'video'):?>
											<a class="post-hover" href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
											</div>
										</div>						
									<?php endif;?>									
								<?php endif;?>
								<div class="post-header">
									<h3 class="post-title"><a href="<?php the_permalink()?>" title="<?php the_title();?>"><?php the_title();?></a></h3>	
									<?php 
									/**
									 * neat_posts_widget_meta action.
									 * hooked neat_posts_widget_meta, 10
									 */
									do_action( 'neat_posts_widget_meta' );
									?>							
								</div>
							</li>
						<?php 
					endwhile;
				print '</ul>';
			else:
				if( $related_posts !== true ){
					_e('Nothing found, make sure you have something in this conditions.','neat');
				}
				if( WP_DEBUG === true ){
					//_e('Here is the query we found: ','neat');
					//print_r( $post_data );
				}				
				print $after_widget;
			endif;
			wp_reset_postdata();wp_reset_query();
		}
		function update( $new_instance, $old_instance ){
			$instance = $old_instance;
			$instance['title'] 					= strip_tags( $new_instance['title'] );
			$instance['ignore_sticky_posts']	= esc_attr($new_instance['ignore_sticky_posts']);
			$instance['author']					= esc_attr( $new_instance['author'] );
			$instance['related_posts']			= esc_attr( $new_instance['related_posts'] );
			$instance['post_format'] 			= strip_tags( $new_instance['post_format'] );
			$instance['category'] 				= absint($new_instance['category'] );
			$instance['category__in'] 			= strip_tags( $new_instance['category__in'] );
			$instance['category__not_in']		= strip_tags( $new_instance['category__not_in'] );
			$instance['post_tag'] 				= strip_tags( $new_instance['post_tag'] );
			$instance['tag__not_in'] 			= strip_tags( $new_instance['tag__not_in'] );
			$instance['post__in']				= strip_tags( $new_instance['post__in'] );
			$instance['post__not_in'] 			= strip_tags( $new_instance['post__not_in'] );
			$instance['orderby']				= esc_attr( $new_instance['orderby'] );
			$instance['order']					= esc_attr( $new_instance['order'] );
			$instance['thumbnail']				= strip_tags( $new_instance['thumbnail'] );
			$instance['thumbnail_size']			= strip_tags( $new_instance['thumbnail_size'] );
			$instance['showposts'] 				= strip_tags( $new_instance['showposts'] );
			return $instance;
		}
		function form( $instance ) {
			global $_wp_additional_image_sizes;
			$image_size = array();
			if( is_array( $_wp_additional_image_sizes ) ){
				foreach ($_wp_additional_image_sizes as $k=>$v) {
					$image_size[]	=	$k;
				}
			}			
			$defaults = array(
				'title' 				=> __('Latest Posts', 'neat'),
				'related_posts'			=>	'',
				'author'				=>	'',
				'ignore_sticky_posts'	=>	false,
				'post_format'			=>	'',
				'category'				=>	'',
				'category__in'			=>	'',
				'category__not_in'		=>	'',
				'post_tag'				=>	'',
				'tag__not_in'			=>	'',
				'post__in'				=>	'',
				'post__not_in'			=>	'',
				'orderby'				=>	'ID',
				'order'					=>	'DESC',
				'thumbnail'				=>	'',
				'thumbnail_size'		=>	'',
				'showposts'				=>	3
			);
			$instance = wp_parse_args( (array) $instance, $defaults );
			?>
				<p>
					<label for="<?php echo $this->get_field_id( "title" ); ?>"><?php _e( 'Title','neat' ); ?></label>
					<input id="<?php echo $this->get_field_id( "title" ); ?>" name="<?php echo $this->get_field_name( "title" ); ?>" type="text" value="<?php echo esc_attr( $instance["title"] ); ?>" style="width:100%;"/>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "related_posts" ); ?>"><?php _e( 'Related Posts','neat' ); ?>:</label>
					<input <?php checked( 'on', $instance['related_posts'], true )?> id="<?php echo $this->get_field_id( "related_posts" ); ?>" name="<?php echo $this->get_field_name( "related_posts" ); ?>" type="checkbox"/>
					<small><?php _e('If you make this as Related Widget, it should be placed in the Single Page.','neat');?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "author" ); ?>"><?php _e( 'Checking Author\'s Posts','neat' ); ?>:</label>
					<input <?php checked( 'on', $instance['author'], true )?> id="<?php echo $this->get_field_id( "author" ); ?>" name="<?php echo $this->get_field_name( "author" ); ?>" type="checkbox"/>
					<small><?php _e('Detecting author\'s posts automatic.','neat');?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "ignore_sticky_posts" ); ?>"><?php _e( 'Ignore Sticky Posts','neat' ); ?>:</label>
					<input <?php checked( 'on', $instance['ignore_sticky_posts'], true )?> id="<?php echo $this->get_field_id( "ignore_sticky_posts" ); ?>" name="<?php echo $this->get_field_name( "ignore_sticky_posts" ); ?>" type="checkbox"/>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "post_format" ); ?>"><?php _e( 'Post Format','neat' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_format" ); ?>" name="<?php echo $this->get_field_name( "post_format" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_format"] ); ?>" style="width:100%;"/>
					 <small><?php _e('Specify Post Format to retrieve (post-format-standard, post-format-audio, post-format-gallery,post-format-image,post-format-video,post-format-quote), leave blank for all.','neat')?></small>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Category', 'neat'); ?></label>
				    	<?php 
							wp_dropdown_categories($args = array(
									'show_option_all'    => 'All',
									'orderby'            => 'ID', 
									'order'              => 'ASC',
									'show_count'         => 1,
									'hide_empty'         => 1, 
									'child_of'           => 0,
									'echo'               => 1,
									'selected'           => isset( $instance['category'] ) ? $instance['category'] : null,
									'hierarchical'       => 0, 
									'name'               => $this->get_field_name( 'category' ),
									'id'                 => $this->get_field_id( 'category' ),
									'taxonomy'           => 'category',
									'hide_if_empty'      => true,
									'class'              => 'dropdown',
					    		)
				    		);
				    	?>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'category__in' ); ?>"><?php _e('Category In', 'neat'); ?></label>
				    <input placeholder="<?php _e('Eg: cat_id1,cat_id2,cat_id3','neat');?>" id="<?php echo $this->get_field_id( 'category__in' ); ?>" name="<?php echo $this->get_field_name( 'category__in' ); ?>" value="<?php echo $instance['category__in']; ?>" style="width:100%;" />
				    <small><?php _e('Specify Categories to retrieve','neat')?></small>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'category__not_in' ); ?>"><?php _e('Category Not In', 'neat'); ?></label>
				    <input placeholder="<?php _e('Eg: cat_id1,cat_id2,cat_id3','neat');?>" id="<?php echo $this->get_field_id( 'category__not_in' ); ?>" name="<?php echo $this->get_field_name( 'category__not_in' ); ?>" value="<?php echo $instance['category__not_in']; ?>" style="width:100%;" />
				    <small><?php _e('Specify Categories Not to retrieve','neat')?></small>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'post_tag' ); ?>"><?php _e('Tags', 'neat'); ?></label>
				    <input placeholder="<?php _e('Eg: tag1,tag2,tag3','neat');?>" id="<?php echo $this->get_field_id( 'post_tag' ); ?>" name="<?php echo $this->get_field_name( 'post_tag' ); ?>" value="<?php echo $instance['post_tag']; ?>" style="width:100%;" />
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'tag__not_in' ); ?>"><?php _e('Tags Not In', 'neat'); ?></label>
				    <input placeholder="<?php _e('Eg: tag_id1,tag_id2,tag_id3','neat');?>" id="<?php echo $this->get_field_id( 'tag__not_in' ); ?>" name="<?php echo $this->get_field_name( 'tag__not_in' ); ?>" value="<?php echo $instance['tag__not_in']; ?>" style="width:100%;" />
				    <small><?php _e('Specify Tags Not to retrieve','neat')?></small>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'post__in' ); ?>"><?php _e('Post In', 'neat'); ?></label>
				    <input placeholder="<?php _e('Eg: post_id1,post_id2,post_id3','neat');?>" id="<?php echo $this->get_field_id( 'post__in' ); ?>" name="<?php echo $this->get_field_name( 'post__in' ); ?>" value="<?php echo $instance['post__in']; ?>" style="width:100%;" />
				    <small><?php _e('Specify Posts to retrieve','neat')?></small>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'post__not_in' ); ?>"><?php _e('Post Not In', 'neat'); ?></label>
				    <input placeholder="<?php _e('Eg: post_id1,post_id2,post_id3','neat');?>" id="<?php echo $this->get_field_id( 'post__not_in' ); ?>" name="<?php echo $this->get_field_name( 'post__not_in' ); ?>" value="<?php echo $instance['post__not_in']; ?>" style="width:100%;" />
				    <small><?php _e('Specify Posts Not to retrieve','neat')?></small>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order by', 'neat'); ?></label>
				    <select style="width:100%;" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				    	<?php 
				    		foreach ( neat_option_orderby() as $key=>$value ){
				    			?>
				    				<option <?php selected( $key, $instance['orderby'], true )?> value="<?php print $key;?>"><?php print $value;?></option>
				    			<?php 
				    		}
				    	?>
				    </select>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order', 'neat'); ?></label>
				    <select style="width:100%;" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				    	<?php 
				    		foreach ( neat_option_order() as $key=>$value ){
				    			?>
				    				<option <?php selected( $key, $instance['order'], true )?> value="<?php print $key;?>"><?php print $value;?></option>
				    			<?php 
				    		}
				    	?>
				    </select>  
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "thumbnail" ); ?>"><?php _e( 'Show post thumbnail','neat' ); ?>:</label>
					<input <?php checked( 'on', $instance['thumbnail'], true )?> id="<?php echo $this->get_field_id( "thumbnail" ); ?>" name="<?php echo $this->get_field_name( "thumbnail" ); ?>" type="checkbox"/>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>"><?php _e('Thumbnail Image Size', 'neat'); ?></label>
				    <input id="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>" value="<?php echo $instance['thumbnail_size']; ?>" style="width:100%;" />
				    <?php printf(__('Enter image size. Example: <strong>%s , thumbnail, medium, large, full</strong> or other sizes defined by current theme. Alternatively enter image size in pixels: 200,100 (Width,Height). Leave empty to use "<strong>post-340-255</strong>" size.','neat'), implode(", ",$image_size))?>
				</p>
				<p>  
				    <label for="<?php echo $this->get_field_id( 'showposts' ); ?>"><?php _e('Show posts', 'neat'); ?></label>
				    <input id="<?php echo $this->get_field_id( 'showposts' ); ?>" name="<?php echo $this->get_field_name( 'showposts' ); ?>" value="<?php echo $instance['showposts']; ?>" style="width:100%;" />
				</p>
			<?php 
		}
	}
}