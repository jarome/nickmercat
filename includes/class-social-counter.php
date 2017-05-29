<?php
/**
 * Updating All socials counter.
 * */
if( !defined('ABSPATH') ) exit; // Don't access me directly.
if( !class_exists( 'Neat_Social_Counter' ) ){
	class Neat_Social_Counter {
		function __construct() {
			add_filter( 'cron_schedules', array( $this, 'cron_schedules_intervals' ) );
			add_action( 'wp' , array( $this, 'init' ), 10);
			//add_action( 'neat_update_counter', array( $this, 'do_update_counter' ) );
		}
		function init(){
			global $neattheme;
			$counter_up_interval	=	!empty( $neattheme['counter_up_interval'] ) ? $neattheme['counter_up_interval'] : null;

			if( !empty( $counter_up_interval ) &&  !wp_next_scheduled( 'neat_update_counter' ) ){
				// hourly
				// twicedaily
				// daily
				wp_schedule_event( time(),  apply_filters( 'neat_schedule_update_counter/times' , $counter_up_interval) , 'neat_update_counter');
			}
			else{
				if( wp_next_scheduled( 'neat_update_counter' ) ){
					wp_clear_scheduled_hook( 'neat_update_counter' );
				}
				$this->do_update_counter();
			}
		}
		// add the cron_schedules interval.
		function cron_schedules_intervals( $schedules ){
			// add a 'weekly' interval
			$schedules['5m'] = array(
				'interval' => 300,
				'display' => __('5 minutes','neat')
			);
			$schedules['10m'] = array(
				'interval' => 600,
				'display' => __('10 minutes','neat')
			);			
			$schedules['15m'] = array(
				'interval' => 900,
				'display' => __('15 minutes','neat')
			);
			$schedules['20m'] = array(
				'interval' => 1200,
				'display' => __('20 minutes','neat')
			);
			$schedules['25m'] = array(
				'interval' => 1500,
				'display' => __('25 minutes','neat')
			);
			$schedules['30m'] = array(
				'interval' => 1800,
				'display' => __('30 minutes','neat')
			);
			$schedules['45m'] = array(
				'interval' => 2700,
				'display' => __('45 minutes','neat')
			);			
			return $schedules;
		}
		function do_update_counter(){
			$this->view_counter();
			$this->share_like_counter();
			$this->googleplus_counter();
			$this->twitter_counter();
			$this->pinterest_counter();
			$this->linkedin_counter();
			$this->facebook_counter();
		}
		/**
		 * Updating total comment, facebook included.
		 */
		function comment_counter(){
			global $post;
			if( is_single() && isset( $post->ID ) && function_exists( 'neat_comments_number' ) ){
				$comment_number = neat_comments_number( $post->ID );
				$comment_number = (int)$comment_number;
				update_post_meta( $post->ID , NEAT_TOTAL_COMMENT_COUNT_META, $comment_number);
			}
		}
		/**
		 *  Updating the Post view count number from WP Stat, an addon of Jetpack.
		 */
		function view_counter(){
			global $post;
			// update view_count from wp stats
			if( is_single() && isset( $post->ID ) && function_exists( 'stats_get_csv' ) ){
				$random = mt_rand( 9999, 999999999 ); // hack to break cache bug
			
				$args = array(
					'days' => $random,
					'post_id' => $post->ID,
				);
			
				$stats = stats_get_csv( 'postviews', $args );
				$views = (int) $stats['0']['views'];
				if( $views > 0 )
					update_post_meta( $post->ID, NEAT_WP_VIEW_STAT_META, $views );
			}
		}
		/**
		 * Updating the Share, Like and Comment number.
		 */
		function share_like_counter() {
			global $post;
			if( is_single() && isset( $post->ID ) ){
				// currently, the share numner support all this sites.
				$facebook_share		=	neat_get_facebook_count( $post->ID, 'share_count' );
				$twitter_share		=	neat_get_twitter_count( $post->ID );
				$pinterest_share	=	neat_get_pinterest_count( $post->ID );
				$linkedin_share		=	neat_get_linkedin_count( $post->ID );
				$googleplus_share	=	neat_get_googleplus_count( $post->ID );
					
				$total_count = (int)$facebook_share + (int)$twitter_share + (int)$pinterest_share + (int)$linkedin_share + (int)$googleplus_share;
				$total_count	=	apply_filters( 'neat_update_total_count/plus-count' , $total_count);
				$total_count	=	$total_count > 0 ? $total_count : 0;
				// updating total share counter.
				update_post_meta( $post->ID , NEAT_TOTAL_SHARE_COUNT_META, $total_count);
				// updating total comment number.
				$total_comment		=	neat_comments_number( $post->ID );
				$total_comment		=	(int)$total_comment;
				update_post_meta( $post->ID , NEAT_TOTAL_COMMENT_COUNT_META, $total_comment);
				// upditing total like counter, first only use facebook like.
				$facebook_like		=	neat_get_facebook_count( $post->ID, 'like_count' );
				$facebook_like		=	(int)$facebook_like;
				update_post_meta( $post->ID , NEAT_TOTAL_LIKE_COUNT_META, $facebook_like);
			}			
		}
		function googleplus_counter(){
			global $post;
			if( is_single() && isset( $post->ID ) ){
				$googleplus_share	=	neat_get_googleplus_count( $post->ID );
				$googleplus_share	=	(int)$googleplus_share > 0 ? (int)$googleplus_share : 0;
				update_post_meta( $post->ID , NEAT_GOOGLEPLUS_SHARE_COUNT_META, $googleplus_share);
			}			
		}
		function twitter_counter(){
			global $post;
			if( is_single() && isset( $post->ID ) ){
				$twitter_count = neat_get_twitter_count($post->ID);
				$twitter_count	=	(int)$twitter_count > 0 ? (int)$twitter_count : 0;
				update_post_meta( $post->ID ,  NEAT_TWITTER_SHARE_COUNT_META , $twitter_count);
			}			
		}
		function pinterest_counter(){
			global $post;
			if( is_single() && isset( $post->ID ) ){
				$pinterest_count = neat_get_pinterest_count( $post->ID );
				$pinterest_count	=	(int)$pinterest_count ? (int)$pinterest_count : 0;
				update_post_meta( $post->ID ,  NEAT_PINTEREST_SHARE_COUNT_META , $pinterest_count);
			}			
		}
		function linkedin_counter(){
			global $post;
			if( is_single() && isset( $post->ID ) ){
				$pinterest_count = neat_get_pinterest_count( $post->ID );
				$pinterest_count	=	(int)$pinterest_count ? (int)$pinterest_count : 0;
				update_post_meta( $post->ID ,  NEAT_PINTEREST_SHARE_COUNT_META , $pinterest_count);
			}			
		}
		function facebook_counter() {
			global $post, $neattheme;
			$comment_number = $like_number = $share_number = 0;
			if( is_single() && isset( $post->ID ) ){
				// check what the comment system are using.
				$comment_number = neat_comments_number( $post->ID );
				$comment_number = (int)$comment_number;
				// Update comment number.
				update_post_meta( $post->ID ,  NEAT_FACEBOOK_COMMENT_COUNT_META , $comment_number);
				// update like counter.
				$like_number = neat_get_facebook_count( $post->ID, 'like_count');
				$like_number = (int)$like_number > 0 ? (int)$like_number : 0;
				update_post_meta( $post->ID ,  NEAT_FACEBOOK_LIKE_COUNT_META , $like_number);
				// update share counter.
				$share_number = neat_get_facebook_count( $post->ID, 'share_count');
				$share_number = (int)$share_number > 0 ? (int)$share_number : 0;
				update_post_meta( $post->ID ,  NEAT_FACEBOOK_SHARE_COUNT_META , $share_number);
			}
		}		
	}
	new Neat_Social_Counter();
}