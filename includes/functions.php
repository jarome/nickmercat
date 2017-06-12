<?php

if( !defined('ABSPATH') ) exit;
if( !function_exists( 'neat_add_query_vars' ) ){
	/**
	 * Adding the query vars.
	 * @param unknown_type $vars
	 * @return string
	 */
	function neat_add_query_vars( $vars ) {
		$vars[] .= 'tape';
		$vars[] .= 'orderby';
		$vars[] .= 'order';
		$vars[] .= 'site';
		return $vars;
	}
	add_filter( 'query_vars' , 'neat_add_query_vars', 100, 1);
}

if( !function_exists( 'neat_pre_query' ) ){
	function neat_pre_query( $query ) {
		global $neattheme;
		$searchKey	=	'';
		$comment_system = isset( $neattheme['comment'] ) ?  $neattheme['comment'] : 'default';
		$site		=	get_query_var( 'site' );
		$orderby	=	get_query_var( 'orderby' );
		$order		=	get_query_var( 'order' );
		if( !is_admin() && $query->is_main_query() ){
			switch ( $orderby ) {
				// order by comment count.
				case 'comment':
					// check if default is activated.
					if( $comment_system == 'default' ){
						$query->set( 'orderby', 'comment_count' );
					}
					else{
						$query->set( 'meta_key', NEAT_TOTAL_COMMENT_COUNT_META );
						$query->set( 'orderby', 'meta_value_num' );
					}
				break;
				// like_count
				case 'like':
					if( $comment_system != 'default' ){
						$query->set( 'meta_key', NEAT_TOTAL_LIKE_COUNT_META );
						$query->set( 'orderby', 'meta_value_num' );
					}
				break;
				//share_count
				case 'share':
					if( !isset( $site ) || empty( $site ) ){
						$searchKey	=	NEAT_TOTAL_SHARE_COUNT_META;		
					}
					else{
						switch ( $site ) {
							case 'facebook':
								$searchKey	=	NEAT_FACEBOOK_SHARE_COUNT_META;
							break;
							
							case 'googleplus':
								$searchKey	=	NEAT_GOOGLEPLUS_SHARE_COUNT_META;
							break;
							
							case 'twitter':
								$searchKey	=	NEAT_TWITTER_SHARE_COUNT_META;
							break;
							
							case 'pinterest':
								$searchKey	=	NEAT_PINTEREST_SHARE_COUNT_META;
							break;
							
							case 'linkedin':
								$searchKey	=	NEAT_LINKEDIN_SHARE_COUNT_META;
							break;
						}
					}
					// proccess the query.
					$query->set( 'meta_key', $searchKey );
					$query->set( 'orderby', 'meta_value_num' );
				break;
				//view_count
				case 'view':
					$query->set( 'meta_key', NEAT_WP_VIEW_STAT_META );
					$query->set( 'orderby', 'meta_value_num' );
				break;					
			}
		}
		if( !empty( $order ) && in_array( $order , array( 'desc', 'asc' )) ){
			$query->set( 'order', $order );
		}
		return $query;
	}
	add_filter('pre_get_posts', 'neat_pre_query');
}

if( !function_exists( 'neat_thumbnail_size' ) ){
	function neat_thumbnail_size( $size ) {
		return 'large';
	}
	add_filter( 'neat_shortcode_thumbnail_size' , 'neat_thumbnail_size', 10, 1);
}

if( !function_exists( 'neat_include_video_homepage' ) ){
	function neat_include_video_homepage( $query ) {
		global $neattheme;
		$include_video_homepage	=	isset( $neattheme['include_video_homepage'] ) && $neattheme['include_video_homepage'] == 1 ? true : false;
		
		if( $query->is_home() && $query->is_main_query() && $include_video_homepage === true ){
			// this is blog page.
			$tax_query = array( array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => array( 'post-format-video' ),
				'operator' => 'IN',
			) );
			$query->set( 'tax_query', $tax_query );
		}
		return $query;
	}	
	add_action( 'pre_get_posts' , 'neat_include_video_homepage', 100, 1);
}

if( !function_exists( 'neat_exclude_video_blogpage' ) ){
	function neat_exclude_video_blogpage( $args ) {
		global $neattheme;
		$exclude_video_blogpage	=	isset( $neattheme['exclude_video_blogpage'] ) && $neattheme['exclude_video_blogpage'] == 1 ? true : false;
		if( $exclude_video_blogpage === true ){
			$args['tax_query'][]	=	array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => array( 'post-format-video' ),
				'operator' => 'NOT IN',
			);
		}
		return $args;
	}
	add_filter('neat_blogpage_args' , 'neat_exclude_video_blogpage', 10, 1);
}

if( !function_exists( 'neat_comments_number' ) ){
	/**
	 * Get comment count number.
	 * @param int $post_id
	 * return int.
	 */
	function neat_comments_number( $post_id ) {

		if( ! $post_id )
			return;
		global $neattheme;
		$comment_system = isset( $neattheme['comment'] ) ?  $neattheme['comment'] : 'default';
		switch ( $comment_system ) {
			case 'default':
				return get_comments_number( $post_id );
			break;
			case 'facebook':
				return neat_get_facebook_count( $post_id, 'comment_count' );
			break;
			default:
				$total_comment = get_comments_number( $post_id ) + neat_get_facebook_count( $post_id, 'comment_count' );
				$total_comment = (int)$total_comment;
				return $total_comment;
			break;
		}
	}
}

if( !function_exists( 'neat_comments_number_text' ) ){
	/**
	 * Get the comment number with the text
	 * @param unknown_type $post_id
	 */
	function neat_comments_number_text( $post_id ) {
		$output = '';
		$count = function_exists( 'neat_comments_number' ) ? neat_comments_number( $post_id ) : null;
		if( $count == 1 ){
			$output = __('1 comment','neat');
		}
		elseif( $count > 1 ){
			$output = sprintf(  __('%s comments','neat') , $count );
		}
		else{
			$output = __('No comment','neat');
		}
		return $output;
	}
}

if( !function_exists( 'neat_get_facebook_count' ) ){
	/**
	 * @param int $post_id
	 * @param string $type: comment_count, like_count, share_count
	 * return int.
	 */
	function neat_get_facebook_count( $post_id, $type = 'comment_count' ) {

		if( ! $post_id )
			return;
		$number = 0;
		if( false === ( $number = get_transient( $post_id . '_facebook_' . $type ) ) ){
			$url = get_permalink( $post_id );
			// Query in FQL
			$fql  = "SELECT share_count, like_count, comment_count ";
			$fql .= " FROM link_stat WHERE url = '$url'";
			
			$fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);
			//$fqlURL = "https://graph.facebook.com/fql?format=json&q=" . urlencode($fql);
			
			// Facebook Response is in JSON
			$args = array(
				'timeout'	=>	5
			);
			$args = apply_filters( 'neat_wp_remote_get_args' , $args);
			
			$response = wp_remote_get( $fqlURL, $args);

			if( is_wp_error( $response ) )
				return 0;
			if( $response['response']['code'] == 200 ){
				$response = json_decode($response['body']);
				$response	=	(array)$response;
				if( ! isset( $response[0] ) ){
					return;
				}
				
				switch ( $type ) {
					case 'comment_count':
						$number = isset( $response[0]->comment_count ) ? (int)$response[0]->comment_count : 0;
						break;
			
					case 'like_count':
						$number = isset( $response[0]->like_count ) ? (int)$response[0]->like_count : 0;
					break;
			
					default:
						$number = isset( $response[0]->share_count ) ? (int)$response[0]->share_count : 0;
					break;
				}
				
				set_transient( $post_id . '_facebook_' . $type , $number, apply_filters( 'transient_expiration' , 600));
			}
		}
		return $number;
	}
}

if( !function_exists( 'neat_get_googleplus_count' ) ){
	/**
	 * Get google plus count numner: share, like.
	 * @param unknown_type $type
	 * return int
	 */
	function neat_get_googleplus_count( $post_id, $type = 'share' ) {
		$number = 0;
		if( !$post_id )
			return $number;
		if( false === ( $number = get_transient( $post_id . '_google_' . $type ) ) ){
			$args = array(
				'method' => 'POST',
				'headers' => array(
					// setup content type to JSON
					'Content-Type' => 'application/json'
				),
				// setup POST options to Google API
				'body' => json_encode(array(
					'method' => 'pos.plusones.get',
					'id' => 'p',
					'jsonrpc' => '2.0',
					'key' => 'p',
					'apiVersion' => 'v1',
					'params' => array(
						'nolog'=>true,
						'id'=>  get_permalink( $post_id ),
						'source'=>'widget',
						'userId'=>'@viewer',
						'groupId'=>'@self'
					)
				)),
				// disable checking SSL sertificates
				'sslverify'=>false
			);
			// retrieves JSON with HTTP POST method for current URL
			$args = apply_filters( 'neat_wp_remote_get_args' , $args);
				
			$json_string = wp_remote_post("https://clients6.google.com/rpc", apply_filters( 'neat_get_googleplus_count_args' , $args));
				
			if (is_wp_error($json_string)){
				// return zero if response is error
				return "0";
			} else {
				$json = json_decode($json_string['body'], true);
				// return count of Google +1 for requsted URL
				$number = intval( $json['result']['metadata']['globalCounts']['count'] );
				set_transient( $post_id . '_google_' . $type , $number, apply_filters( 'transient_expiration' , 600));
			}
		}

		return absint( $number );
	}
}

if( !function_exists( 'neat_get_twitter_count' ) ){
	/**
	 * Get Twitter share count.
	 * @param unknown_type $post_id
	 */
	function neat_get_twitter_count( $post_id ) {
		$number = 0;
		if( !$post_id )
			return $number;
		if( false === ( $number = get_transient( $post_id . '_twitter' ) ) ){
			$args = array(
				'timeout'	=>	5
			);
			$args = apply_filters( 'neat_wp_remote_get_args' , $args);
			$apiurl = 'https://cdn.api.twitter.com/1/urls/count.json?url=' . get_permalink( $post_id );
			$response = wp_remote_get( apply_filters( 'neat_get_twitter_count_apiurl' , $apiurl), $args );
			if( is_wp_error( $response ) ){
				return $number;
			}
			if( $response['response']['code'] == 200 ){
				// valid request.
				$response = json_decode($response['body']);
				if( (int)$response->count > 0 ){
					$number = $response->count;
					set_transient( $post_id . '_twitter' , $number, apply_filters( 'transient_expiration' , 600));
				}
			}			
		}

		return $number;
	}
}

if( !function_exists( 'neat_get_pinterest_count' ) ){
	/**
	 * Get Pinterest share count.
	 * @param unknown_type $post_id
	 */
	function neat_get_pinterest_count( $post_id ) {
		$number = 0;
		if( !$post_id )
			return $number;
		if( false === ( $number = get_transient( $post_id . '_pinterest' ) ) ){
			$args = array(
				'timeout'	=>	5
			);
			$args = apply_filters( 'neat_wp_remote_get_args' , $args);
			
			$apiurl = 'http://api.pinterest.com/v1/urls/count.json?callback=share&url=' . get_permalink( $post_id );
			$response = wp_remote_get( apply_filters( 'neat_get_pinterest_count_apiurl' , $apiurl), $args );
			if( is_wp_error( $response ) )
				return 0;
			if( $response['response']['code'] == 200 ){
				// valid request.
				$response = str_replace('share(', '', $response['body']);
				$response = str_replace(')', '', $response);
				$response = json_decode($response);
				if( (int)$response->count > 0 ){
					$number = (int)$response->count;
					set_transient( $post_id . '_pinterest' , $number, apply_filters( 'transient_expiration' , 600) );
				}
			}
		}

		return $number;
	}
}

if( !function_exists( 'neat_get_linkedin_count' ) ){
	/**
	 * Get Linkedin share count.
	 * @param unknown_type $post_id
	 */
	function neat_get_linkedin_count( $post_id ) {
		$number = 0;
		if( !$post_id )
			return $number;
		
		if( false === ( $number = get_transient( $post_id . '_linkedin' ) ) ){
			$args = array(
				'timeout'	=>	5
			);
			$args = apply_filters( 'neat_wp_remote_get_args' , $args);
			
			$apiurl = 'http://www.linkedin.com/countserv/count/share?url='.get_permalink( $post_id ).'&format=json';
			$response = wp_remote_get( apply_filters( 'neat_get_linkedin_count_apiurl' , $apiurl), $args );
			if( is_wp_error( $response ) )
				return 0;
			if( $response['response']['code'] == 200 ){
				// valid request.
				$response = json_decode($response['body']);
				if( (int)$response->count > 0 ){
					$number = $response->count;
					set_transient( $post_id . '_linkedin' , $number, apply_filters( 'transient_expiration' , 600));
				}
			}			
		}
		return $number;		
	}
}

if( !function_exists( 'neat_get_view_count' ) ){
	/**
	 * Get count view from WP Stat.
	 * @param unknown_type $post_id
	 * @return number
	 */
	function neat_get_view_count( $post_id ) {

		$view_count = get_post_meta( $post_id, NEAT_WP_VIEW_STAT_META, true );
		$view_count = (int)$view_count;
		return $view_count;
	}
}

if( !function_exists( 'neat_get_socials_count' ) ){
	/**
	 * Get total share/like count number from metakey.
	 * @param unknown_type $post_id
	 * @param unknown_type $type
	 * @return void|number
	 */
	function neat_get_socials_count($post_id, $type = 'share' ) {
		$counter = 0;
		$key = '';
		if( !$post_id )
			return;
		switch ( $type ) {
			// get the total of share.
			case 'share':
				$key = NEAT_TOTAL_SHARE_COUNT_META;
			break;
			// otherwise, get the total of like.
			default:
				$key = NEAT_TOTAL_LIKE_COUNT_META;
			break;
		}
		$counter	=	get_post_meta( $post_id, $key, true );
		$counter	=	(int)$counter > 0 ? (int)$counter : 0;
		return $counter;
	}
}

if( !function_exists( 'neat_page_navigation' ) ){
	function neat_page_navigation() {
		if( has_nav_menu('homepage_navigation') && apply_filters( 'neat_wp_navigation' , true) === true ){
			print '<div class="nav">';
				wp_nav_menu(array(
					'theme_location'=>'homepage_navigation',
					'menu_class'=> 'nav navbar-nav list-inline menu',
					'menu_id'	=>	'navigation-menu',
					'container'	=>	null,
					'walker'	=>	new Neat_Walker_Nav_Menu()
				));
			print '</div>';
		}
		elseif( has_nav_menu('page_navigation') && apply_filters( 'neat_wp_navigation' , true) === false  ){
			print '<div class="nav">';
			wp_nav_menu(array(
				'theme_location'=>'page_navigation',
				'menu_class'=>'',
				'menu_id'	=>	'navigation-menu',
				'container'	=>	null,
				'walker'	=>	new Neat_Walker_Nav_Menu()
			));
			print '</div>';
		}
		else{
			?>
	    	<div class="nav">
	      		<ul id="navigation-menu">
	      			<li class="nav-link active"><a href="<?php print home_url();?>"><?php _e('Homepage','neat');?></a></li>
	      			<li class="nav-link"><a href="<?php print admin_url( 'nav-menus.php' )?>"><?php _e('Setup Menu','neat');?></a></li>
	      		</ul>
	    	</div>						
			<?php 
		}
	}
	add_action( 'neat_page_navigation' , 'neat_page_navigation', 10);
}

if( !function_exists( 'neat_wp_navigation' ) ){
	function neat_wp_navigation( $is_true ) {
		if( is_front_page() ){
			return true;
		}
		else{
			return false;
		}
	}
	add_filter( 'neat_wp_navigation' , 'neat_wp_navigation', 10, 1);
}

if( !function_exists( 'neat_get_blog_layout' ) ){
	/**
	 * get the blog layout.
	 * @return mixed
	 */
	function neat_get_blog_layout() {
		global $neattheme;
		$layout	=	!empty( $neattheme['bloglayout'] ) ? esc_attr( $neattheme['bloglayout'] ) : 'r_sidebar';
		return apply_filters( 'neat_blog_layout' , $layout);		
	}
}

if( !function_exists( 'neat_get_blog_post_layout' ) ){
	function neat_get_blog_post_layout( $layout ) {
		global $post;
		if( !$post || !is_single() )
			return $layout;
		$post_layout = get_post_meta( $post->ID, 'post_template_sidebar', true ) ? get_post_meta( $post->ID, 'post_template_sidebar', true ) : $layout;
		$layout	=	$post_layout;
		return $layout;
	}
	add_filter( 'neat_blog_layout' , 'neat_get_blog_post_layout', 100, 1);
}

if( !function_exists( 'neat_search_form' ) ){
	function neat_search_form() {
		global $neattheme;
		$exclude_page_search	=	( isset( $neattheme['exclude_page_search'] ) && $neattheme['exclude_page_search'] == 1 ) ? true : false;
		if( $exclude_page_search === true ){
			print '<input type="hidden" name="post_type" value="post">';
		}
	}
	add_action( 'neat_search_form' , 'neat_search_form', 10);
}

if( !function_exists( 'neat_single_post_thumbnail_size' ) ){
	function neat_single_post_thumbnail_size( $size ) {
		global $post;
		if( get_post_meta( $post->ID, 'post_template_sidebar', true ) == 'fullwidth' )
			return 'full';
		return $size;
	}
	add_filter( 'neat_single_post_thumbnail_size' , 'neat_single_post_thumbnail_size', 10, 1);
}

if( !function_exists( 'neat_page_heading' ) ){
	function neat_page_heading() {
		global $wp_query;
		?>
			<div class="title">
				<h2>
					<?php if( is_category() ):?>
						<?php _e('Category:','neat');?>
					<?php elseif( is_tag() ):?>
						<?php _e('Tag:','neat');?>
					<?php elseif( is_search() ):?>
						<?php _e('Search:','neat');?>
					<?php elseif( is_author() ):?>
						<?php _e('Author:','neat');?>
					<?php elseif( is_archive() ):?>
						<?php _e('Archive:','neat');?>
					<?php else:?>
						<?php bloginfo( 'description' );?>
					<?php endif;?>
					
				    <?php if ( is_day() ) : ?>
				        <?php printf( __( 'Daily Archives: <span>%s</span>', 'neat' ), get_the_date() ); ?>
				    <?php elseif ( is_month() ) : ?>
				        <?php printf( __( 'Monthly Archives: <span>%s</span>', 'neat' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'neat' ) ) ); ?>
				    <?php elseif ( is_year() ) : ?>
				        <?php printf( __( 'Yearly Archives: <span>%s</span>', 'neat' ), get_the_date( _x( 'Y', 'yearly archives date format', 'neat' ) ) ); ?>
				    <?php elseif( is_search() ):?>
				    	<?php if( $wp_query->found_posts > 0 ):?>
				    		<?php printf( __('About %s results','neat') , $wp_query->found_posts )?>
				    	<?php else:?>
				    		<?php printf( __('Nothing found.','neat') , $wp_query->found_posts )?>
				    	<?php endif;?>
				    <?php elseif( is_author() && isset( $wp_query->queried_object->data->display_name ) ):?>
				    	<?php print $wp_query->queried_object->data->display_name;?>
				    <?php elseif( is_category() || is_tag() ):?>
				    	<?php print $wp_query->queried_object->name;?>
				    <?php endif; ?>				
				</h2>
				<hr class="small">
				<?php do_action( 'neat_page_subheading' );?>
			</div>		
		<?php 
	}
	add_action( 'neat_page_heading' , 'neat_page_heading', 10);
}

if( !function_exists( 'neat_yoast_breadcrumb' ) ){
	/**
	 * Add yoast_breadcrumb.
	 * required SEO By Joast installed.
	 */
	function neat_yoast_breadcrumb() {
		if( function_exists( 'yoast_breadcrumb' ) && apply_filters( 'neat_show_breadcrumb' , true) === true ){
			yoast_breadcrumb('<p id="breadcrumbs">','</p>');
		}
	}
	add_action( 'neat_page_heading' , 'neat_yoast_breadcrumb', 20);
}

if( !function_exists( 'neat_footer' ) ){
	function neat_footer() {
		global $neattheme;
		?>
			<?php if( !empty( $neattheme['footer_text'] ) ):?>
			<p class="copyright"><?php print $neattheme['footer_text'];?></p>
			<?php endif;?>
			<?php 
			$socials = neat_option_socials();
			if( is_array( $socials ) ):
			?>
			<ul class="social">
				<?php foreach ( $socials  as $key=>$value):?>
					<?php 
					$profile_link = !empty( $neattheme[ 'footer_social_' . $key ] ) ? esc_url( $neattheme[ 'footer_social_' . $key ] ) : null;
					if( !empty( $profile_link ) ):
					?>
					<li class="link-<?php print $value['class'];?>"><a href="<?php print $profile_link;?>"><i class="fa fa-<?php print $value['class'];?>"></i></a></li>
					<?php endif;?>
				<?php endforeach;?>
				<li class="link-rss"><a href="<?php bloginfo('rss_url'); ?>"><i class="fa fa-rss"></i></a></li>
			</ul>
			<?php endif;?>
		<?php 
	}
	add_action( 'neat_footer' , 'neat_footer');
}

if( !function_exists( 'neat_post_format_content' ) ){
	/**
	 * Get the content of the post format.
	 */
	function neat_post_format_content() {
		global $post;
		if( !isset( $post->ID ) )
			return;
		$post_format	=	get_post_format( $post->ID );
		
		switch ( $post_format ) {
			case 'video':
				$content = get_post_meta( $post->ID, '_format_video_embed', true );
				if( $content ):
				?>	
					<div class="media">
						<div class="fitvids">
							<?php 
							/**
							 * mediapress_media action.
							 * hooked mediapress_get_media_object, 10, 1
							 */
							do_action( 'mediapress_media', get_the_ID() );
							?>
						</div>
					</div>
					<?php do_action( 'mediapress_media_pagination', get_the_ID() );?>
					<?php do_action( 'mediapress_toolkit' );?>
				<?php endif;				
			break;
			
			case 'audio':
				$content = get_post_meta( $post->ID, '_format_audio_embed', true );
				if( $content ):
				?>
					<div class="media">
						<div class="fitvids">
							<?php 
							/**
							 * mediapress_media action.
							 * hooked mediapress_get_media_object, 10, 1
							 */
							do_action( 'mediapress_media', get_the_ID() );
							?>
						</div>
					</div>
					<?php do_action( 'mediapress_media_pagination', get_the_ID() );?>
					<?php do_action( 'mediapress_toolkit' );?>
				<?php endif;				
			break;
			
			case 'gallery':
				$content = get_post_meta( $post->ID, '_format_gallery_images', true );
				if( $content && is_array( $content ) ):				
				?>
				<div class="media">
					<div id="showcase-slider" class="owl-carousel owl-theme"> 
					  	<?php for ($i = 0; $i < count( $content ); $i++):?>
					  		<?php 
					  		/**
					  		 * hooked neat_single_post_thumbnail_size, 10, 1
					  		 * @var unknown_type
					  		 */
					  		$src = wp_get_attachment_image_src($content[$i], apply_filters( 'neat_single_post_thumbnail_size' , 'large') );
					  		?>
					  		<div class="item"><img src="<?php print $src[0];?>" alt="Slider Image 1"></div>
					  	<?php endfor;?>
					</div>
				</div>				
				<?php 	
				endif;			
			break;
			
			case 'quote':
				global $post;
				$source_name = get_post_meta( $post->ID, '_format_quote_source_name', true ) ? get_post_meta( $post->ID, '_format_quote_source_name', true ) : null;
				$source_url = get_post_meta( $post->ID, '_format_quote_source_url', true ) ? get_post_meta( $post->ID, '_format_quote_source_url', true ) : null;
				?>
					<div class="media">
						<div class="quote-format">
							<blockquote cite="<?php print $source_url;?>">
								<?php print wp_filter_nohtml_kses( $post->post_content );?>
								<?php if( !empty( $source_name ) ):?><cite><?php print $source_name;?></cite><?php endif;?>
							</blockquote>
						</div>	
					</div>			
				<?php 
			break;
			
			case 'image':
				if( has_post_thumbnail( $post->ID ) ):
				?>
					<div class="media">
						<?php print get_the_post_thumbnail( $post->ID, apply_filters( 'neat_single_post_thumbnail_size' , 'large') );?>
					</div>				
				<?php 
				endif;
			break;
		}
	}
	add_action( 'neat_post_format_content' , 'neat_post_format_content', 100);
}

if( !function_exists( 'neat_add_widget_before_media_post' ) ){
	function neat_add_widget_before_media_post( $post_id ) {
		if( is_single() ){
			dynamic_sidebar( 'before-post-sidebar' );
		}
	}
	add_action( 'mediapress_media' , 'neat_add_widget_before_media_post', 5, 1);
}

if( !function_exists( 'neat_add_widget_after_media_post' ) ){
	function neat_add_widget_after_media_post( $post_id ) {
		if( is_single() ){
			dynamic_sidebar( 'after-post-sidebar' );
		}
	}
	add_action( 'mediapress_media' , 'neat_add_widget_after_media_post', 20, 1);
}

if( !function_exists( 'neat_widget_tag_cloud_args' ) ){
	function neat_widget_tag_cloud_args( $args ) {
		$args['largest']	=	15;
		return $args;
	}
	add_filter( 'widget_tag_cloud_args' , 'neat_widget_tag_cloud_args', 10, 1);
}

if( !function_exists( 'neat_nav_menu_css_class' ) ){
	/**
	 * add "nav-link" to the menu items.
	 * @param unknown_type $classes
	 * @param unknown_type $item
	 * @return string
	 */
	function neat_nav_menu_css_class( $classes, $item ) {
		$classes[] = "nav-link";
		return $classes;
	}
	add_filter('nav_menu_css_class' , 'neat_nav_menu_css_class' , 10 , 2);
}

if( !function_exists( 'neat_add_blog_item_class' ) ){
	function neat_add_blog_item_class( $classes ) {
		if( !is_single() ){
			$classes[]	=	'blog-item';
		}
		if( is_single() ){
			$classes[]	=	'blog-single';
		}
		return $classes;
	}
	add_filter( 'post_class' , 'neat_add_blog_item_class', 100, 1);
}

if( !function_exists( 'neat_custom_excerpt_length' ) ){
	function neat_custom_excerpt_length( $length ) {
		return 15;
	}
	add_filter( 'excerpt_length', 'neat_custom_excerpt_length', 999, 1 );
}

if( !function_exists( 'neat_content_more' ) ){
	/**
	 * Change the More link in post content.
	 * @return string
	 */
	function neat_content_more() {
		global $post;
		$link = get_permalink( $post->IDs );
		if( get_post_format( $post->ID ) == 'video' ){
			$new_link = '<br/><i class="fa fa-play-circle"></i> <a href="' . esc_url($link) . '" class="read-more">'.__('Watch Video','neat').'</a>';
		}
		else{
			$new_link = '<br/><a href="' . esc_url($link) . '" class="read-more">'.__('Read More &raquo;','neat').'</a>';
		}
		
		return $new_link;
	}
	add_filter('the_content_more_link', 'neat_content_more');
}

if( !function_exists( 'neat_excerpt_more' ) ){
	function neat_excerpt_more() {
		global $post;
		return '<br/><a href="'.get_permalink( $post->ID ).'" class="read-more">'.__('Read More &raquo;','neat').'</a>';
	}
	add_filter('excerpt_more', 'neat_excerpt_more');
}

if( !function_exists( 'neat_post_meta' ) ){
	/**
	 * Show the post meta as Author, Tag, Category, Post time.
	 * @param string $type
	 */
	function neat_post_meta( $type = 'full' ) {
		global $post;
		if( !$post->ID )
			return;
		?>
			<div class="meta">	
				<span class="autor"><i class="fa fa-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php print get_the_author_meta('display_name');?></a></span>
				<?php if( $type != 'min' ):?>
					<?php if( has_category(null, $post->ID) ):?><span class="category"><i class="fa fa-folder-open"></i> <?php the_category(', ');?></span><?php endif;?>
				<?php endif;?>
				<span class="date"><i class="fa fa-clock-o"></i> <a href="<?php print neat_get_post_archive_link( $post->ID );?>"><?php print get_the_date('', $post->ID);?></a></span>
				<?php if( comments_open( $post->ID ) && neat_comments_number( $post->ID ) || apply_filters( 'neat_display_empty_counter' , false) === true ):?>
					<span class="comments"><i class="fa fa-comments"></i> <a href="<?php comments_link();?>"><?php print neat_comments_number( $post->ID );?></a></span>
				<?php endif;?>
				<?php if( apply_filters( 'neat_active_social_counter' , true) === true ):?>
					<?php if( get_post_meta( $post->ID, NEAT_FACEBOOK_LIKE_COUNT_META, true ) > 0 || apply_filters( 'neat_display_empty_counter' , false) === true ):?>
						<span class="heart"><i class="fa fa-heart"></i> <a href="<?php print get_permalink( $post->ID );?>"><?php print get_post_meta( $post->ID, NEAT_FACEBOOK_LIKE_COUNT_META, true )?></a></span>
					<?php endif;?>
					<?php if( neat_get_socials_count( $post->ID, 'share') > 0 || apply_filters( 'neat_display_empty_counter' , false) === true ):?>
						<span class="share"><i class="fa fa-share-alt"></i> <a href="<?php print get_permalink( $post->ID );?>"><?php print neat_get_socials_count( $post->ID, 'share');?></a></span>
					<?php endif;?>
				<?php endif;?>
				<?php if( function_exists( 'stats_get_csv' ) && (neat_get_view_count( $post->ID) > 0 || apply_filters( 'neat_display_empty_counter' , false) === true ) ):?>
					<span class="view"><i class="fa fa-eye"></i> <a href="<?php print get_permalink( $post->ID );?>"><?php print neat_get_view_count( $post->ID);?></a></span>
				<?php endif;?>
			</div>	
		<?php 
	}
	add_action( 'neat_post_meta' , 'neat_post_meta', 15, 1);
}

if( !function_exists( 'neat_posts_widget_meta' ) ){
	/**
	 * Show the post widget meta.
	 */
	function neat_posts_widget_meta() {
		global $post;
		if( !$post->ID )
			return;
		?>
			<span class="date"><i class="fa fa-clock-o"></i> <a href="<?php print neat_get_post_archive_link( $post->ID );?>"><?php print get_the_date('', $post->ID);?></a></span>
			<?php if( comments_open( $post->ID ) && neat_comments_number( $post->ID ) || apply_filters( 'neat_display_empty_counter' , false) === true ):?>
				<span class="comments"><i class="fa fa-comments"></i> <a href="<?php comments_link();?>"> <?php print neat_comments_number( $post->ID );?></a></span>
			<?php endif;?>
			<?php if( function_exists( 'stats_get_csv' ) && ( neat_get_view_count( $post->ID) > 0 || apply_filters( 'neat_display_empty_counter' , false) === true )):?>
				<span class="view"><i class="fa fa-eye"></i> <a href="<?php comments_link();?>"> <?php print neat_get_view_count( $post->ID );?></a></span>
			<?php endif;?>			
		<?php 
	}
	add_action( 'neat_posts_widget_meta' , 'neat_posts_widget_meta', 10);
}

if( !function_exists( 'neat_get_post_archive_link' ) ){
	/**
	 * Get the post archive link.
	 * return the link.
	 * @param int $post_id
	 */
	function neat_get_post_archive_link( $post_id ) {
		if( !$post_id )
			return;
		$post_day = get_the_date('d', $post_id);
		$post_month = get_the_date('m', $post_id);
		$post_year = get_the_date('Y', $post_id);
		$date_archive_link	=	get_day_link($post_year, $post_month, $post_day);
		return $date_archive_link;		
	}
}

if( !function_exists( 'neat_get_comments_link' ) ){
	function neat_get_comments_link( $comment_link, $post_id ) {
		return get_permalink( $post_id ) . '#comment-form';
	}
	add_filter( 'get_comments_link' , 'neat_get_comments_link', 10, 2);
}

if( !function_exists( 'neat_wp_link_pages' ) ){
	function neat_wp_link_pages( $content ) {
		$output = wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'neat' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'echo'		  => 0
		) );
		
		return $content . $output;
			
	}
	add_action( 'the_content' , 'neat_wp_link_pages', 10);
}

if( !function_exists( 'neat_post_tags' ) ){
	function neat_post_tags( $content ) {
		ob_start();
		global $post;
		if( has_tag( null, $post->ID ) && is_single() ){
			?><div class="post-tags"><?php the_tags('<i class="fa fa-tags"></i><span> ',' </span> <span>', '</span>');?></div><?php  
		}
		return $content . ob_get_clean();
	}
	add_action( 'the_content' , 'neat_post_tags', 20, 1);
}

if( !function_exists( 'neat_author_box' ) ){
	function neat_author_box( $content ) {
		ob_start();
		global $post;
		if( !is_single() )
			return $content;
		$is_shows = get_post_meta( $post->ID, 'post_template_authorbox' ) ? get_post_meta( $post->ID, 'post_template_authorbox' ) : null;
		$is_shows = apply_filters( 'neat_show_authorbox' , $is_shows);
		if( !$is_shows )
			return $content;
		$author_page = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );
		?>
		<div class="author-box">
		    <div class="author-avatar">
		        <a href="<?php print $author_page;?>"><?php print get_avatar( get_the_author_meta( 'ID' ), 80 );?></a>
		    </div>
		    <div class="author-bio">
		    	<h3><?php printf( __('Author: %s', 'neat' ),  '<a href="'.$author_page.'">'.get_the_author_meta( 'display_name' ).'</a>' );?></h3>
		    	<?php if( get_the_author_meta( 'description' ) ):?>
		        	<p><?php print get_the_author_meta( 'description' );?></p>
		        <?php endif;?>
		    </div>
		</div>		
		<?php 
		return $content . ob_get_clean();
	}
	add_action( 'the_content' , 'neat_author_box', 30, 1);
}

if( !function_exists( 'neat_related_posts' ) ){
	function neat_related_posts( $content ) {
		if( !is_single() )
			return;
		ob_start();	
			$args = array( 
				'thumbnail'			=>	true,
				'thumbnail_size'	=>	'medium',
				'showposts'			=>	3,
				'el_class'			=>	'related-posts-lists'
			);
			the_widget( 'Neat_Posts_Widget', apply_filters( 'neat_related_posts_args' , $args), array() );
		return $content . '<div class="related-posts">' . ob_get_clean() . '</div>';
	}
	//add_action( 'the_content' , 'neat_related_posts', 40, 1);
}

if( !function_exists( 'neat_comments_callback' ) ){
	function neat_comments_callback(  $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);
		?>
		    <li class="comment" id="comment-<?php print $comment->comment_ID?>">
		        <div class="comment-image">
		            <?php print get_avatar( $comment, $args['avatar_size'] );?>
		        </div>
		        <div class="comment-content">
		            <h2><a href="<?php print get_comment_author_url();?>"><?php print $comment->comment_author;?></a></h2>
		            <?php comment_reply_link(array_merge( $args, array('add_below' => null, 'depth' => null, 'max_depth' => $args['max_depth'],'reply_text'=> $args['reply_text'] ))) ?>
		            <?php if( current_user_can('add_users') ):?>
		            	<a href="<?php print get_edit_comment_link( $comment->comment_ID );?>" class="edit"><i class="fa fa-edit"></i> <?php _e('Edit','neat');?></a>
		            <?php endif;?>		
		            <?php comment_text();?>       
		            <p class="comment-detail">
		            	<small><?php print human_time_diff( get_comment_time('U'), current_time('timestamp') ) . __(' ago','neat');?></small>
						<?php if ( '0' == $comment->comment_approved ) : ?>
							<br/><small><?php _e( 'Your comment is awaiting moderation.', 'neat' ); ?></small>
						<?php endif; ?>			            	
		            </p>
		        </div>	
		<?php 
	}
}

if( !function_exists( 'neat_add_facebook_comment' ) ){
	/**
	 * Hook into the_content action.
	 * Add the facebook content.
	 * @param html $content
	 */
	function neat_add_facebook_comment( $content ) {
		global $neattheme, $post;
		if( !$post->ID )
			return;
		$output = '';
		$comment_system = !empty( $neattheme['comment'] ) ? $neattheme['comment'] : 'default';
		// check if facebook or bold is choosed.
		if( comments_open($post->ID) && $comment_system != 'default' && (is_single() || is_page() )){
			$comment_colorscheme	=	!empty( $neattheme['data-colorscheme'] ) ? $neattheme['data-colorscheme'] : 'light';
			$data_orderby			=	!empty( $neattheme['data-orderby'] ) ? $neattheme['data-orderby'] : 'social';
			$data_numposts			=	!empty( $neattheme['data-numposts'] ) ? absint( $neattheme['data-numposts'] ) : 10;
			$output = '<div id="facebook-comments" class="fb-comments" data-order-by="'.$data_orderby.'" data-width="100%" data-href="'.get_permalink( $post->ID ).'" data-numposts="'.$data_numposts.'" data-colorscheme="'.$comment_colorscheme.'"></div>';
		}
		return $content . $output;
	}
	add_action( 'the_content' , 'neat_add_facebook_comment', 40, 1);
}

if( !function_exists( 'neat_add_facebook_likeshare_button' ) ){
	function neat_add_facebook_likeshare_button() {
		$output = '';
		global $post;
		if( is_single() && isset( $post->ID ) ){
			$output = '
				<div class="fb-like" data-href="'.get_permalink( $post->ID ).'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			';
			print $output;
		}
	}
	add_action( 'neat_post_format_content' , 'neat_add_facebook_likeshare_button', 10);
}

if( !function_exists( 'neat_get_comment_link' ) ){
	function neat_get_comment_link(  $comments_link, $post_id ) {
		global $neattheme;
		$comment_system = !empty( $neattheme['comment'] ) ? $neattheme['comment'] : 'default';
		if( $comment_system == 'facebook' ){
			return get_permalink( $post_id ) . '#facebook-comments';
		}
		return $comments_link;
	}
	add_filter( 'get_comments_link' , 'neat_get_comment_link', 100, 2);
}

if( !function_exists( 'neat_hide_default_comment' ) ){
	function neat_hide_default_comment( $boolean ) {
		global $neattheme;
		$comment_system = !empty( $neattheme['comment'] ) ? $neattheme['comment'] : 'default';
		if( $comment_system == 'facebook' )
			return false;
		return $boolean;
	}	
	add_filter( 'neat_comment_system' , 'neat_hide_default_comment', 10, 1);
}

if( !function_exists( 'neat_comment_fields_wapper_before' ) ){
	/**
	 * Adding <div class="form-left"> before the fields.
	 */
	function neat_comment_fields_wapper_before() {
		if( !get_current_user_id() ){
			print '<div class="form-left">';
		}
	}
	//add_action( 'comment_form_before_fields' , 'neat_comment_fields_wapper_before', 10);
}

if( !function_exists( 'neat_comment_fields_wapper_after' ) ){
	/**
	 * Adding </div> after the fields.
	 */
	function neat_comment_fields_wapper_after() {
		if( !get_current_user_id() ){
			print '</div>';
		}
	}
	//add_action( 'comment_form_after_fields' , 'neat_comment_fields_wapper_after', 10);
}

if( !function_exists( 'neat_add_wrapper_all_fields_before' ) ){
	/**
	 * Add "<div class="form">" inside the form, before the fields, comment textarea.
	 */
	function neat_add_wrapper_all_fields_before() {
		print '<div class="form">';
	}
	//add_action( 'comment_form_top' , 'neat_add_wrapper_all_fields_before');
}

if( !function_exists( 'neat_add_wrapper_all_fields_after' ) ){
	/**
	 * Add "<div class="form">" inside the form, after the fields, comment textarea.
	 */
	function neat_add_wrapper_all_fields_after() {
		print '</div>';
	}
	//add_action( 'comment_form' , 'neat_add_wrapper_all_fields_after');
}

if( !function_exists( 'neat_pagination' ) ){
	function neat_pagination( $query ) {
		global $wp_query, $neattheme;
		
		if( apply_filters( 'neat_navigation_default' , false) === true ){
			return neat_nav_default();
		}

		if( empty( $query ) )
			$query = $wp_query;
		if ( $query->max_num_pages < 2 ) {
			return;
		}
		
		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );
		
		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}
		
		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
		
		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
		
		// Set up paginated links.
		$links = paginate_links( array(
			'base'     => $pagenum_link,
			'format'   => $format,
			'total'    => $query->max_num_pages,
			'current'  => $paged,
			'mid_size' => 3,
			'type'	=>	'list',
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => __( '&larr; Prev', 'neat' ),
			'next_text' => __( 'Next &rarr;', 'neat' ),
		) );
		
		if ( $links ) :
			echo '<div class="pagination">';
				echo $links;
			echo '</div>';
		endif;		

	}
	add_action( 'neat_pagination', 'neat_pagination', 10, 1 );
}

if( !function_exists('neat_nav_default') ){
	function neat_nav_default() {
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
		?>
			<div class="navigation">
				<div class="alignleft"><?php previous_posts_link( __('&laquo; Previous Entries','neat') ); ?></div>
				<div class="alignright"><?php next_posts_link( __('Next Entries &raquo;','neat'), '' ); ?></div>
			</div>
		<?php 		
	}
}

if( !function_exists('neat_add_span_cat_count') ){
	/**
	 * Add span tag to count number
	 * @param unknown_type $output
	 * @param unknown_type $args
	 * @return mixed
	 */
	function neat_add_span_cat_count( $output, $args ) {
		if( isset( $args['taxonomy'] ) && $args['taxonomy'] != 'category' )
			return $output;
		if( $args['show_count'] == 1 || $args['show_count'] == true ){
			$output = str_replace('</a> (', ' <span>', $output);
			$output = str_replace(')', '</span></a>', $output);
		}
		return $output;
	}
	//add_filter('wp_list_categories', 'neat_add_span_cat_count', 100, 2);
}

if( !function_exists( 'neat_subscribeform_action' ) ){
	function neat_subscribeform_action(){
		$email	=	$_POST['email'];
		// check if this is not a valid address.
		do_action( 'neat_before_subscribe_action', $email );
		if( !$email || !is_email( $email ) ){
			print json_encode( array(
				'resp'		=>	'error',
				'message'	=>	__('Please enter a valid e-mail address!','neat')			
			) );
			exit;
		}
		if( email_exists( $email ) || username_exists( $email ) ){
			print json_encode( array(
				'resp'		=>	'error',
				'message'	=>	sprintf( __('%s is already in our system, you may put another address.','neat'), $email ) 
			) );
			exit;
		}
		else{
			if( function_exists( 'register_new_user' ) ){
				$errors = register_new_user( $email , $email);
				if ( !is_wp_error($errors) ) {
					// success
					do_action( 'neat_subscribe_success_action', $email );
					print json_encode( array(
						'resp'		=>	'success',
						'message'	=>	__('Subscription sent!.','neat')
					) );
					exit;
				}
				else{
					// something went wrong???
					do_action( 'neat_subscribe_fail_action', $email );
					print json_encode( array(
						'resp'		=>	'error',
						'message'	=>	__('Something went wrong, please try again.','neat')
					) );
					exit;					
				}
			}
			else{
				print json_encode( array(
					'resp'		=>	'error',
					'message'	=>	sprintf( __('Couldn\'t find %s function.','neat'), 'register_new_user' )
				) );
				exit;		
			}
		}
	}
	add_action( 'wp_ajax_neat_subscribeform' , 'neat_subscribeform_action');
	add_action( 'wp_ajax_nopriv_neat_subscribeform' , 'neat_subscribeform_action');
}
if (!function_exists('neat_convert_string_to_string')) {
	function neat_convert_string_to_string($s) {
		return preg_replace('/https?:\/\/[\w\-\.!~?&+\*\'"(),\/]+/','<a target="_blank" href="$0">$0</a>',$s);
	}
}

if( !function_exists( 'neat_option_socials' ) ){
	function neat_option_socials() {
		$socials = array(
			'facebook'	=>	array(
				'name'	=>	'Facebook',
				'class'	=>	'facebook'
			),
			'twitter'	=>	array(
				'name'	=>	'Twitter',
				'class'	=>	'twitter'
			),
			'google'	=>	array(
				'name'	=>	'Google Plus',
				'class'	=>	'google-plus'
			),
			'linkedin'	=>	array(
				'name'	=>	'Linkedin',
				'class'	=>	'linkedin'
			),
			'pinterest'	=>	array(
				'name'	=>	'Pinterest',
				'class'	=>	'pinterest'
			)
		);
		return apply_filters( 'neat_option_socials' , $socials);
	}
}

if( !function_exists('neat_wp_title') && !function_exists( '_wp_render_title_tag' ) ){
	function neat_wp_title( $title, $sep ) {
		global $paged, $page;
		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'neat' ), max( $paged, $page ) );

		return $title;
	}
	add_filter( 'wp_title', 'neat_wp_title', 10, 2 );
}

if( !function_exists( 'neat_option_orderby' ) ){
	/**
	 * return an array of the post orderby.
	 */
	function neat_option_orderby() {
		return array(
			'ID'	=>	__('Order by Post ID','neat'),
			'author'	=>	__('Order by author','neat'),
			'title'	=>	__('Order by title','neat'),
			'name'	=>	__('Order by Post name (Post slug)','neat'),
			'date'	=>	__('Order by date','neat'),
			'modified'	=>	__('Order by last modified date','neat'),
			'rand'	=>	__('Order by Random','neat'),
			'comment_count'	=>	__('Order by number of comments (Facebook comment count support)','neat'),
			'like'		=>	__('Order by Facebook Likes','neat'),
			'share'		=>	__('Order by Shares','neat'),
			'view'		=>	__('Order by Views (Works if WP Stat plugin is activated - a Jetpack\'s addon.)','neat')
		);
	}
}

if( !function_exists( 'neat_option_order' ) ){
	/**
	 * return an array of the post order.
	 */
	function neat_option_order() {
		return array(
			'DESC'	=>	__('DESC','neat'),
			'ASC'	=>	__('ASC','neat'),
		);
	}
}

if( !function_exists('neat_add_custom_css') ){
	function neat_add_custom_css() {
		global $neattheme;
		$css = NULL;
		if( isset( $neattheme['custom_css'] ) && trim( $neattheme['custom_css'] ) != '' ){
			$css = '<style>'.$neattheme['custom_css'].'</style>';
		}
		print $css;
	}
	add_action('wp_head', 'neat_add_custom_css');
}

if( !function_exists('neat_add_custom_js') ){
	function neat_add_custom_js() {
		global $neattheme;
		$js = NULL;
		if( isset( $neattheme['custom_js'] ) && trim( $neattheme['custom_js'] ) != '' ){
			$js .= '<script>jQuery(document).ready(function(){'.$neattheme['custom_js'].'});</script>';
		}
		print $js;
	}
	add_action('wp_footer', 'neat_add_custom_js');
}

if( !function_exists( 'neat_add_facebook_js' ) ){
	function neat_add_facebook_js() {
		global $neattheme;
		$comment	=	!empty( $neattheme['comment'] ) ? $neattheme['comment'] : 'default';
		if( $comment != 'default' ){
			$appid = !empty( $neattheme['appid'] ) ? esc_attr( $neattheme['appid'] ) : '';
			$language = !empty( $neattheme['facebooklang'] ) ? esc_attr( $neattheme['facebooklang'] ) : 'en_US';
			?>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/<?php print $language;?>/sdk.js#xfbml=1&appId=<?php print $appid;?>&version=<?php print apply_filters( '' , 'v2.0')?>";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>			
			<?php 
		}
	}
	add_action( 'wp_head' , 'neat_add_facebook_js', 10, 1);
}

if( !function_exists('neat_show_favicon') ){
	function neat_show_favicon() {
		global $neattheme;
		if( isset( $neattheme['favicon']['url'] ) && !empty( $neattheme['favicon']['url'] ) ){
			print '<link rel="shortcut icon" href="'.$neattheme['favicon']['url'].'">';
		}
	}
	add_action('wp_head', 'neat_show_favicon');
}

if( !function_exists( 'neat_disable_modestbranding' ) ){
	/**
	 * Disable all youtube branding.
	 * @param unknown_type $html
	 * @param unknown_type $url
	 * @param unknown_type $args
	 * @return mixed|unknown
	 */
	function neat_disable_modestbranding( $html, $url, $args ) {
		if (strpos($url, 'youtube') !== FALSE && apply_filters( 'neat_disable_modestbranding' , true) === true ) {
			return str_replace("?feature=oembed", "?feature=oembed&modestbranding=1&rel=0&iv_load_policy=3&showinfo=0", $html);
		}
		return $html;
	}
	add_filter('oembed_result', 'neat_disable_modestbranding', 10, 3);
}

/**
 * Video Importer Compatible. https://refactored.co/plugins/video-importer
 */

if( !function_exists( 'neat_video_importer_post_array' ) ){
	/**
	 * Hooking into the refactored_video_importer/post_array.
	 * @param unknown_type $post
	 * @param unknown_type $provider
	 * @param unknown_type $video_array
	 * @param unknown_type $source_id
	 * @param unknown_type $import_options
	 */
	function neat_video_importer_post_array( $post, $provider, $video_array, $source_id, $import_options ) {
		$post['post_content']	=	$video_array['description'];
		return $post;
	}
	add_filter( 'refactored_video_importer/post_array' , 'neat_video_importer_post_array', 100, 5);
}

if( !function_exists( 'neat_video_importer_update_postfield_video' ) ){
	/**
	 * @param unknown_type $post_id
	 * @param unknown_type $provider
	 * @param unknown_type $video_array
	 * @param unknown_type $source_id
	 * @param unknown_type $import_options
	 */
	function neat_video_importer_update_postfield_video( $post_id, $provider, $video_array, $source_id, $import_options ) {
		if( isset( $post_id ) && !empty( $video_array['url'] ) ){
			update_post_meta( $post_id , '_format_video_embed', esc_url( $video_array['url'] ) );
			if( function_exists( 'get_video_thumbnail' ) ){
				// update the thumbnail.
				get_video_thumbnail( $post_id );
			}
			set_post_format( $post_id , 'video');
		}
	}
	add_action( 'refactored_video_importer/single_video_imported' , 'neat_video_importer_update_postfield_video', 100, 5);
}

if( !function_exists( 'neat_cf7_save_post' ) ){
	/**
	 * 
	 * @param unknown_type $cf
	 */
	function neat_cf7_save_post( $cf ) {
		global $neattheme;
		$cf7id	=	isset( $neattheme['cf7id'] ) ? absint( $neattheme['cf7id'] ) : null;
		$post_status	=	isset( $neattheme['post_status'] ) ? esc_attr( $neattheme['post_status'] ) : 'pending';
		
		$submission = WPCF7_Submission::get_instance();
		$post_array = array();
		
		if( $submission ){
			$posted_data = $submission->get_posted_data();
			foreach ($posted_data as $key => $value) {
				$post_array[ $key ]	=	$value;
			}
		}
		//print_r( $post_array ); exit;
		if( !empty( $cf7id ) && $cf7id == $post_array['_wpcf7'] ){

			$post_title		=	esc_attr( $post_array['post_title'] );
			$post_content	=	$post_array['post_content'];
			$video_links	=	$post_array['video_links'];
			$category		=	absint( $post_array['category'] );
			$tags			=	esc_attr( $post_array['tags'] );
			$post_format	=	isset( $post_array['post_format'] ) ? esc_attr( $post_array['post_format'] ) : 'video';
			
			$post_data = array(
				'post_type'		=>		'post',
				'post_status'	=>		$post_status,
				'post_title'	=>		$post_title,
				'post_content'	=>		apply_filters( 'neat_cf7_save_post_content_html' , false) === true ? $post_content : wp_filter_kses( $post_content ),
				'post_author'	=>		get_current_user_id() ? get_current_user_id() : 1,
				'ping_status'	=>		'open',
				'comment_status'=>		'open',
				'post_category'	=>		array( $category ),
				'tags_input'	=>		$tags
			);
			$post_data	=	apply_filters( 'neat_cf7_save_post_args' , $post_data);
			
			do_action( 'neat_cf7_save_post/before_save', $post_data );
			
			$post_id = wp_insert_post( $post_data, true );
			
			if ( !is_wp_error( $post_id ) ){	
				// No error.
				// set post format
				if( !empty( $post_format ) ){
					set_post_format( $post_id , $post_format);
				}
				// update the post meta.
				if( !empty( $video_links ) ){
					update_post_meta( $post_id , '_format_video_embed', $video_links);
				}	
				// get the thumbnail
				if( function_exists( 'get_video_thumbnail' ) && apply_filters( 'neat_cf7_save_post/auto-thumbnail' , false) === true ){
					// update the thumbnail.
					get_video_thumbnail( $post_id );
				}
				do_action( 'neat_cf7_save_post/after_save', $post_id, $post_data );
			}
			// skip email sending.
			if( apply_filters( 'neat_cf7_save_post/skip-email' , false) === true ){
				$cf->skip_mail = true;
			}
		}
		return;
	}
	add_action( 'wpcf7_before_send_mail', 'neat_cf7_save_post', 100 );
}

if( !function_exists( 'neat_cf7_auto_response' ) ){
	/**
	 * 
	 * @param unknown_type $post_id
	 * @param unknown_type $post_data
	 */
	function neat_cf7_auto_response( $post_id, $post_data ) {
		global $neattheme;
		$auto_response	=	isset( $neattheme['response'] ) && $neattheme['response'] == 1 ? true : false;
		if( $auto_response === false)
			return;
		$current_user	=	get_current_user_id();
		$current_user_email = get_the_author_meta( 'user_email' , $current_user );
		$current_display_name = get_the_author_meta( 'display_name' , $current_user );
		
		if( !is_email( $current_user_email ) )
			return;
		$sender_name = !empty( $neattheme['response_name'] ) ? esc_attr( $neattheme['response_name'] ) : get_bloginfo( 'name' );
		$response_email = !empty( $neattheme['response_email'] ) ? esc_attr( $neattheme['response_email'] ) : get_bloginfo( 'admin_email' );
		$subject		= !empty( $neattheme['response_subject'] ) ? esc_attr( $neattheme['response_subject'] ) : sprintf( __('Congratulations from %s.','neat'), get_bloginfo( 'name' ) );
		$message		= !empty( $neattheme['response_content'] ) ? $neattheme['response_content'] : ( ( get_post_status( $post_id ) == 'publish' ) ? sprintf( __('Check your post here %s','neat'), get_permalink( $post_id ) ) : __('Your submission is awaiting for approval.','neat') );
		$tags = array(
			'[user]'		=>	$current_display_name,
			'[sitename]'	=>	get_bloginfo( 'name' ),
			'[site_desc]'	=>	get_bloginfo( 'description' )
		);
		if( get_post_status( $post_id ) == 'publish' ){
			$tags['[post_link]']	=	get_permalink( $post_id );
		}		
		foreach ($tags as $key=>$value) {
			$subject = str_ireplace( $key , $value, $subject);
		}
		foreach ($tags as $key=>$value) {
			$message = str_ireplace( $key , $value, $message);
		}

		$headers = 'From: '.$sender_name.' <'.$response_email.'>' . "\r\n";
		wp_mail( $current_user_email , $subject, $message, $headers);
	}
	add_action( 'neat_cf7_save_post/after_save' , 'neat_cf7_auto_response', 10, 2);
}

/**
 * Jetpack Social sharing button.
 */

if( !function_exists( 'neat_remove_jetpack_sharing_button' ) ){
	/**
	 * Remove the sharing button if this is a video post format
	 */
	function neat_remove_jetpack_sharing_button() {
		global $post;
		if( is_single() ){
			if( isset( $post->ID ) && get_post_format( $post->ID ) == 'video' ){
				remove_filter( 'the_content', 'sharing_display', 19 );
				remove_filter( 'the_excerpt', 'sharing_display', 19 );
			}
		}
	}
	add_action( 'wp' , 'neat_remove_jetpack_sharing_button', 9999);
}
if( !function_exists( 'neat_reorder_jetpack_sharing_button' ) ){
	/**
	 * Show the sharing button below the video player in single post.
	 */
	function neat_reorder_jetpack_sharing_button() {
		global $post;
		if( is_single() ){
			if( isset( $post->ID ) && get_post_format( $post->ID ) == 'video' ){
				return function_exists( 'sharing_display' ) ? sharing_display( null , true ) : '';
			}			
		}
	}
	add_action( 'mediapress_toolkit' , 'neat_reorder_jetpack_sharing_button', 20);
}

if( !function_exists('neat_get_user_role') ){
	/**
	 * get user's role
	 * @param unknown_type $user_id
	 * @return void|multitype:|NULL
	 */
	function neat_get_user_role( $user_id ) {
		if( !$user_id ){
			return;
		}
		$user = new WP_User( $user_id );
		if( isset( $user->roles[0] ) ){
			return $user->roles[0];
		}
		else{
			return null;
		}
	}
}

if( !function_exists( 'neat_videoformat_post_password_required' ) ){
	function neat_videoformat_post_password_required( $the_content ) {
		global $post;
		if( is_single() && post_password_required( $post ) && get_post_format( $post ) == 'video' ){
			return;
		}
		return $the_content;
	}
	add_action( 'the_content' , 'neat_videoformat_post_password_required', 100, 1);
}

if( !function_exists( 'neat_hide_js_composer_note' ) ){
	function neat_hide_js_composer_note() {
		if( !function_exists( 'vc_set_as_theme' ) )
			return;
		vc_set_as_theme(true);
	}
	add_action( 'init', 'neat_hide_js_composer_note' );
}
if( !function_exists( 'neat_set_vc_composer_template' ) ){
	function neat_set_vc_composer_template() {
		if( !function_exists( 'vc_set_shortcodes_templates_dir' ) )
			return;
		$dir = get_template_directory() . '/page-templates/js_composer/';
		vc_set_shortcodes_templates_dir($dir);
	}
	add_action( 'init' , 'neat_set_vc_composer_template');
}
if( !function_exists( 'neat_css_classes_for_vc_column' ) ){
	function neat_css_classes_for_vc_column($class_string, $tag) {
		if ($tag=='vc_column') {
			$class_string = preg_replace('/vc_col-sm-(\d{1,2})/', 'col-md-$1', $class_string);
		}
		return $class_string;
	}
	add_filter('vc_shortcodes_css_class', 'neat_css_classes_for_vc_column', 10, 2);
}
if( !function_exists( 'neat_remove_js_meta' ) ){
	function neat_remove_js_meta() {
		if( function_exists( 'visual_composer' ) ){
			remove_action('wp_head', array(visual_composer(), 'addMetaData'));
		}
		
	}
	add_action('init', 'neat_remove_js_meta', 100);
}