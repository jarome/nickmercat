<?php
/**
 * Neat Shortcode Class.
 * V1.1
 * Author: Toan Nguyen
 */
if( !defined('ABSPATH') ) exit;
if( !class_exists( 'Neat_Shortcodes' ) ){
	class Neat_Shortcodes {
		function __construct() {
			add_action( 'init' , array( $this , 'add_shortcodes' ));
		}
		function add_shortcodes(){
			// latest post
			add_shortcode( 'neat_latest_post' , array( $this ,'latest_post' ));
			// the heading.
			add_shortcode( 'neat_heading' ,  array( $this , 'heading' ) );
			// the feature block.
			add_shortcode( 'neat_feature' , array( $this , 'feature' ));
			// the subscribe form.
			add_shortcode( 'neat_subscribeform' , array( $this , 'subscribeform' ) );
			// testimonial
			add_shortcode( 'neat_testimonial' , array( $this , 'testimonial' ));
			// the twitter feed.
			add_shortcode( 'neat_twitter' , array( $this , 'twitter' ));
			// login form
			add_shortcode( 'neat_loginform' , array( $this, 'loginform' ));
			if( function_exists( 'wpcf7_add_shortcode' ) ){
				wpcf7_add_shortcode('categories',  array( $this, 'categories' ), true );
			}
		}
		function categories() {
			$output = '';
			$field_name = apply_filters( 'neat_dropdown_categories_field_name' , 'category');
			$args = array(
				'show_option_all'    => '',
				'show_option_none'   => __('Choose Category ...','neat'),
				'orderby'            => 'ID',
				'order'              => 'ASC',
				'show_count'         => 0,
				'hide_empty'         => 0,
				'child_of'           => 0,
				'exclude'            => '1',
				'echo'               => 0,
				'selected'           => isset( $_GET[$field_name] ) ? esc_attr( $_GET[$field_name] ) : '',
				'hierarchical'       => 0,
				'name'               => $field_name,
				'id'                 => '',
				'class'              => 'postform',
				'depth'              => 0,
				'tab_index'          => 0,
				'taxonomy'           => 'category',
				'hide_if_empty'      => false,
			);
			$args = apply_filters( 'neat_dropdown_categories_args' , $args);
			$output .= '<span class="wpcf7-form-control-wrap '.$args['name'].'">';
				$output .= wp_dropdown_categories( $args );
			$output .= '</span>';
			return $output;
		}
		function latest_post( $attr, $content = null ){
			$output = $el_class	=	'';
			//ob_start();
			wp_reset_postdata();wp_reset_query();
			extract(shortcode_atts(array(
				'title'					=>		'',
				'type'					=>		'block', // block or main content.
				'post_type'				=>		'post',
				'post_status'			=>		'publish',
				'post_format'		 	=>		'',
				'ignore_sticky_posts'	=>		1,
				'post__in'				=>		'',
				'post__not_in'			=>		'',
				'cat'					=>		'', // use cat ids.
				'category__in'			=>		'', // use cat ids.
				'category__not_in'		=>		'', // use cat ids.
				'tag'					=>		'', // use tag ids.
				'tag__in'				=>		'', // use tag ids.
				'tag__not_in'			=>		'', // use tag ids.
				'orderby'				=>		'ID',
				'order'					=>		'DESC',
				'showposts'				=>		10,
				'author'				=>		'', // use author id.
				'author__in'			=>		'',
				'author__not_in'		=>		'',
				'thisweek'				=>		'no',
				'thumbnail_size'		=>		'',
				'el_class'				=>		'',
				'columns'				=>		3,
				//'id'					=>		'block-' . rand(1000, 9999),
				'link'					=>		get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : ''
			), $attr));
			
			//$id	=	( $type == 'block' ) ? 'blog-teasers' : 'blog-teasers-main';

			$post_data = array(
				'post_type'				=>	$post_type,
				'post_status'			=>	$post_status,
				'ignore_sticky_posts'	=>	$ignore_sticky_posts,
				'showposts'				=>	$showposts,
				'orderby'				=>	$orderby,
				'order'					=>	$order					
			);
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
					
			// check post in
			if( !empty( $post__in ) ){
				$post__in	=	explode( "," , $post_in);
				if( is_array( $post__in ) ){
					$post_data['post__in']	=	$post__in;	
				}
			}
			if( !empty( $post__not_in ) ){
				$post__not_in	=	explode( "," , $post__not_in);
				if( is_array( $post__not_in ) ){
					$post_data['post__not_in']	=	$post__not_in;
				}
			}
			// check author.
			if( !empty( $author ) ){
				$author	=	(int)$author;
				if( is_int( $author ) ){
					$post_data['author']	=	$author;
				}
			}
				
			// check if multiple Author is putted, author__in.
			if( !empty( $author__in ) ){
				$author__in	=	explode( "," , $author__in);
				if( !empty( $author__in ) && is_array( $author__in ) ){
					$post_data['author__in']	=	$author__in;
				}
			}
				
			// check this week.
			if( $thisweek	==	'yes' ){
				$week = date( 'W' );
				$year = date( 'Y' );
				$post_data['date_query'][]	=	array(
					'year' => date( 'Y' ),
					'week' => date( 'W' )
				);
			}
				
			
			// check if multiple Author is putted, author__not_in.
			if( !empty( $author__not_in ) ){
				$author__not_in	=	explode( "," , $author__not_in);
				if( !empty( $author__not_in ) && is_array( $author__not_in ) ){
					$post_data['author__not_in']	=	$author__not_in;
				}
			}
			// check if this is regular post.
			if( $post_type == 'post' ){
				// check if single cat is putted.
				if( !empty( $cat ) ){
					$cat	=	(int)$cat;
					if( is_int( $cat ) ){
						$post_data['cat']	=	$cat;
					}
				}
				// check if multiple cat is putted, category__in.
				if( !empty( $category__in ) ){
					$category__in	=	explode( "," , $category__in);
					if( !empty( $category__in ) && is_array( $category__in ) ){
						$post_data['category__in']	=	$category__in;
					}
				}
				// check if multiple cat is putted, category__not_in.
				if( !empty( $category__not_in ) ){
					$category__not_in	=	explode( "," , $category__not_in);
					if( !empty( $category__not_in ) && is_array( $category__not_in ) ){
						$post_data['category__not_in']	=	$category__not_in;
					}
				}
				// check tag.
				if( !empty( $tag ) ){
					$tag	=	(int)$tag;
					if( is_int( $tag ) ){
						$post_data['tag_id']	=	$tag;
					}
				}
				// check multiple tag__in,
				if( !empty( $tag__in ) ){
					$tag__in	=	explode(",", $tag__in);
					if( !empty( $tag__in ) & is_array( $tag__in ) ){
						$post_data['tag__in']	=	$tag__in;
					}
				}
				// check multiple tag__not_in,
				if( !empty( $tag__not_in ) ){
					$tag__not_in	=	explode(",", $tag__not_in);
					if( !empty( $tag__not_in ) & is_array( $tag__not_in ) ){
						$post_data['tag__not_in']	=	$tag__not_in;
					}
				}
			}
			
			if( $type == 'main' ){
				$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
				$post_data['paged']	=	$paged;
			}
			$post_data	=	apply_filters( 'neat_recent_posts_args' , $post_data);
			
			$post_query = new WP_Query( $post_data );
			ob_start();

				?>
				<div id="blog-teasers" class="<?php if( $type == 'block' ):?>owl-carousel owl-theme block-blog-teasers<?php else:?>blog-masonry <?php endif;?> <?php print $el_class;?>">
					<?php if( $post_query->have_posts() ):?>
						<?php while ( $post_query->have_posts() ): $post_query->the_post();?>
							<?php get_template_part( 'page-templates/shortcode/content', get_post_format( get_the_ID() ));?>
						<?php endwhile;?>
					<?php else:?>
						<?php get_template_part( 'content','none');?>
					<?php endif;?>
				</div>
				
				<?php 
				if( $type == 'main' ):
				/**
				 * neat_pagination action.
				 * hooked neat_pagination, 10
				 */
				do_action( 'neat_pagination', $post_query );
				endif;
				?>				
				<?php if( $type == 'block' && !empty( $link ) ):?>
					<a href="<?php print $link;?>" class="button"><?php _e('View All Posts','neat');?></a>
				<?php endif;?>
				<?php 
			$output .= ob_get_clean();
			wp_reset_postdata();wp_reset_query();
			return $output;
			
		}
		/**
		 * Add neat_heading shortcode.
		 * @param unknown_type $attr
		 * @param unknown_type $content
		 * @return string
		 */
		function heading( $attr, $content = null ){
			ob_start();
			$output = $el_class	=	'';
			extract(shortcode_atts(array(
				'title'			=>	'',
				'el_class'		=>	''
			), $attr));
			?>
				<div class="title <?php print $el_class;?>">
					<?php if( !empty( $title ) ):?><h2><?php print $title;?></h2><?php endif;?>
					<hr class="small">
					<?php if( !empty( $content ) ):?>
						<p><?php print $content;?></p>
					<?php endif;?>
				</div>
			<?php 
			$output = ob_get_clean();
			return $output;
		}
		function feature( $attr, $content = null ){
			ob_start();
			$output = $el_class	= $icon = '';
			extract(shortcode_atts(array(
				'title'			=>	'',
				'icon'		=>	'',
				'el_class'		=>	''
			), $attr));
			?>
				<div class="<?php print $el_class;?>">
					<i class="fa <?php print $icon;?>"></i>
					<?php if( !empty( $title ) ):?><h4><?php print $title;?></h4><?php endif;?>
					<?php if( !empty( $content ) ):?>
						<p><?php print $content;?></p>
					<?php endif;?>	
				</div>			
			<?php 
			$output = ob_get_clean();
			return $output;			
		}
		function subscribeform( $attr, $content = null ) {
			ob_start();
			$output = $el_class	= $form_id =	'';
			extract(shortcode_atts(array(
				'title'			=>	'',
				'type'			=>	'default',
				'external_link'	=>	'',
				'el_class'		=>	''
			), $attr));
			
			$form_id	=	( isset( $type ) && $type == 'default' ) ? 'newsletter' : 'newsletter-external';
			$action_link	=	( isset( $external_link ) && !empty( $external_link ) && $type == 'external' ) ? 'action="'.esc_url( $external_link ).'"' : null;
			$action_blank	=	!empty( $action_link ) ? 'target="_blank"' : null;	
			?>
				<?php if( !empty( $title ) ):?>
				<div class="nl-text">
					<p><?php print $title;?></p>
				</div>
				<?php endif;?>
				<div class="nl-form <?php print $el_class;?>">
					<!-- newsletter form -->
					<form <?php print $action_blank;?> method="<?php print apply_filters( 'neat_subscribe_form_method' , 'get');?>" id="<?php print $form_id;?>" <?php print $action_link;?> novalidate>
						<input type="email" name="email" id="e-mail" placeholder="<?php _e('Your e-mail address','neat')?>" required>
						<input type="hidden" name="submit_label" value="<?php _e('Submit','neat');?>">
						<input type="hidden" name="submit_label_waiting" value="<?php _e('Waiting ...','neat');?>">
						<input type="hidden" name="submit_label_success" value="<?php _e('Success','neat');?>">
						<input type="hidden" name="submit_label_error" value="<?php _e('Error','neat');?>">
						<input type="hidden" name="action" value="neat_subscribeform">
						<input class="button" type="submit" value="<?php _e('Submit','neat');?>">
				    </form>
				    <div id="newsletter-error"></div>
				</div>
			<?php 
			$output = ob_get_clean();
			return $output;			
		}
		function testimonial( $attr, $content = null ){
			ob_start();
			$output = $el_class = '';
			wp_reset_postdata();wp_reset_query();
			$prefix = 'testimonial_';
			extract(shortcode_atts(array(
				'title'			=>	'',
				'showposts'		=>	5
			), $attr));
			$post_data = array(
				'post_type'		=>	'testimonial',
				'post_status'	=>	'publish',
				'showposts'		=>	$showposts
			);
			$post_query = new WP_Query( $post_data );
			if( $post_query->have_posts() ) :
			?>
				<div id="quote-slider" class="owl-carousel owl-theme <?php print $el_class;?>">
 					<?php
 					while ( $post_query->have_posts() ) : $post_query->the_post();
 					$position = get_post_meta( get_the_ID() ,$prefix . 'position', true ) ? get_post_meta( get_the_ID() ,$prefix . 'position', true ) : null;
 					$website = get_post_meta( get_the_ID() ,$prefix . 'website', true ) ? get_post_meta( get_the_ID() ,$prefix . 'website', true ) : '#';
 					?>
				   	<div class="item <?php the_ID();?>">
				  
				  		<!-- quote text -->
				  		<blockquote>
				  		
				  		<?php print get_the_content();?>
						<cite><?php the_title();?><?php if( $position ):?>, <a href="<?php print $website;?>"><?php print $position;?></a></cite><?php endif;?>
						
						</blockquote>

				  	</div>
					<?php endwhile;?>
				</div>
			<?php
			else:
				_e('Nothing found!','neat');
			endif;
			wp_reset_postdata();wp_reset_query();
			return ob_get_clean();
		}
		/**
		 * Display the twitter feed.
		 * @param unknown_type $attr
		 * @param unknown_type $content
		 * @return string
		 */
		function twitter( $attr, $content = null ){
			ob_start();
			$output = $el_class	= $form_id =	'';
			extract(shortcode_atts(array(
				'username'			=>	'',
				'consumerkey'		=>	'',
				'consumersecret'	=>	'',
				'accesstoken'		=>	'',
				'accesstokensecret'	=>	'',
				'shows'				=>	5,
				'el_class'			=>	''
			), $attr));	
			if( class_exists('TwitterOAuth') ){
				$connection = $this->getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$shows);
				
				if( empty( $tweets->errors[0]->message ) ):?>
						<div id="quote-slider" class="owl-carousel owl-theme <?php print $el_class;?>">
					 		<?php foreach ( $tweets as $tweet ):?>
								<div class="item">
								  	<blockquote><?php print neat_convert_string_to_string($tweet->text);?></blockquote>
								</div>
							<?php endforeach;?>
						</div>
					<?php else:
						print $tweets->errors[0]->message . ' :(';
				endif;
			}
			else{
				print '<h3>'.__('You may install Twitter Oauth plugin to use this feature.','neat').'</h3>';
			}

			$output = ob_get_clean();
			return $output;			
		}
		function loginform( $attr, $content = null ) {
			$output = $current_link= '';
			global $post;
			if( isset( $post->ID ) && $post->ID > 0 ){
				$current_link	=	get_permalink( $post->ID );
			}
			$current_userid	=	get_current_user_id();
			extract(shortcode_atts(array(
				'form_id'				=>	'loginform',
				'label_username'		=>	__( 'Username','neat' ),
				'label_password'		=>	__( 'Password','neat' ),
				'label_remember'		=>	__( 'Remember Me','neat' ),
				'label_log_in'			=>	__( 'Log In','neat' ),
				'id_username'			=>	'user_login',
				'id_password'			=>	'user_pass',
				'id_remember'			=>	'rememberme',
				'id_submit'				=>	'wp-submit',
				'cf7_id'				=>	'',
				'redirect'				=>	$current_link
			), $attr));
			$args = array(
				'echo'           => false,
				'redirect'       => $redirect,
				'form_id'        => 'loginform',
				'label_username' => $label_username,
				'label_password' => $label_password,
				'label_remember' => $label_remember,
				'label_log_in'   => $label_log_in,
				'id_username'    => 'user_login',
				'id_password'    => 'user_pass',
				'id_remember'    => 'rememberme',
				'id_submit'      => 'wp-submit',
				'remember'       => true,
				'value_username' => NULL,
				'value_remember' => false
			);
			if( !$current_userid ){
				$output	.= wp_login_form( apply_filters( 'neat_loginform_args' , $args) );
			}
			else{
				// already logged in.
				if( !empty( $cf7_id ) ){
					$output	.= do_shortcode( '[contact-form-7 id="'.$cf7_id.'" html_class="upload-form"]' );
				}
				else{
					$user_displayname = get_the_author_meta( 'display_name' , $current_userid );
					$output	.= apply_filters( 'neat_loginform_logged_message' ,  sprintf( __('Holla %s, Welcome back. %s','neat'), $user_displayname, '<a href="'.wp_logout_url( apply_filters( 'neat_logout_redirect_link' , home_url()) ).'">'.__('Logout?','neat').'</a>' ) );
				}
				$output	.=	$content;
			}
			return do_shortcode( $output );
		}
		function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
			$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
			return $connection;
		}		
	}
	new Neat_Shortcodes();
}