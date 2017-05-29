<?php
if( !defined('ABSPATH') ) exit;
// don't access me directly.
if( !class_exists( 'Neat_Post_Table' ) ){
	class Neat_Post_Table {
			function __construct() {
			add_filter('manage_edit-post_columns' , array($this,'cpt_columns'));
			add_action( "manage_post_posts_custom_column", array($this,'modify_column'), 10, 2 );
		}
		function cpt_columns($columns){
			$new_columns = array(
				'view'	=>	__('Views','neat'),
				'like'	=>	__('Likes','neat'),
				'share'	=>	__('Shares','neat'),
			);
			return array_merge($columns, $new_columns);
		}	
		function modify_column($column, $post_id){
			switch ($column) {
				case 'view':
					$views = function_exists( 'neat_get_view_count' ) ? neat_get_view_count( $post_id ) : 0;
					if( $views > 0 ){
						print '<div class="social-counter">';
							printf( __('<span><i class="fa fa-eye"></i> %s</span>','neat'), $views );
						print '</div>';
					}
				break;
				case 'like':
					$likes = get_post_meta( $post_id, NEAT_TOTAL_LIKE_COUNT_META, true );
					if( $likes > 0 ){
						print '<div class="social-counter">';
							printf( __('<span><i class="fa fa-thumbs-up"></i> %s</span>','neat'), $likes );
						print '</div>';
					}
				break;
				case 'share':
					$total_shares = get_post_meta( $post_id, NEAT_TOTAL_SHARE_COUNT_META, true );
					if( $total_shares > 0 ){
						print '<div class="social-counter">';
							printf( __('<span><i class="fa fa-share-alt"></i> %s</span>','neat'), $total_shares );
							if( neat_get_facebook_count($post_id, 'share_count') > 0 ){
								printf( __('<span><i class="fa fa-facebook"></i> %s</span>','neat'), neat_get_facebook_count($post_id, 'share_count') );
							}
							if( get_post_meta( $post_id, NEAT_GOOGLEPLUS_SHARE_COUNT_META, true ) > 0 ){
								printf( __('<span><i class="fa fa-google-plus"></i> %s</span>','neat'), get_post_meta( $post_id, NEAT_GOOGLEPLUS_SHARE_COUNT_META, true ) );
							}
							if( get_post_meta( $post_id, NEAT_PINTEREST_SHARE_COUNT_META, true ) > 0 ){
								printf( __('<span><i class="fa fa-pinterest"></i> %s</span>','neat'), get_post_meta( $post_id, NEAT_PINTEREST_SHARE_COUNT_META, true ) );
							}
							if( get_post_meta( $post_id, NEAT_TWITTER_SHARE_COUNT_META, true ) > 0 ){
								printf( __('<span><i class="fa fa-twitter"></i> %s</span>','neat'), get_post_meta( $post_id, NEAT_TWITTER_SHARE_COUNT_META, true ) );
							}
							if( get_post_meta( $post_id, NEAT_LINKEDIN_SHARE_COUNT_META, true ) > 0 ){
								printf( __('<span><i class="fa fa-linkedin"></i> %s</span>','neat'), get_post_meta( $post_id, NEAT_LINKEDIN_SHARE_COUNT_META, true ) );
							}
						print '</div>';
					}
					
				break;				
			}
		}
	}
	new Neat_Post_Table();
}