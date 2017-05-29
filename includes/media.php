<?php
if( !defined( 'ABSPATH' ) ) exit();
if( !function_exists( 'mediapress_get_media_object' ) ){
	/**
	 * Get the media object, this can be a string, int or an array.
	 * @param unknown_type $post_id
	 * @return void|Ambigous <mixed, string, multitype:, boolean, unknown>
	 */
	function mediapress_get_media_object( $post_id ) {
		$tape = get_query_var( 'tape' ) ? absint( get_query_var( 'tape' ) ) : 1;
		$tape = $tape - 1; 
		$object = array();
		if( !$post_id ){
			return;	
		}
		
		if( !is_single() && has_post_thumbnail( $post_id ) && apply_filters( 'neat_media_post_format_thumbnail' , true) === true ){
			print get_the_post_thumbnail( $post_id, apply_filters( 'neat_shortcode_thumbnail_size' , 'large') );
			return;
		}
		
		$object = get_post_format( $post_id ) == 'video' ? get_post_meta( $post_id, '_format_video_embed', true ) : get_post_meta( $post_id, '_format_audio_embed', true ) ;
		$object	=	explode("\n", $object);
		$object	=	array_filter( $object );			
		if( is_array( $object ) ){
			if( count( $object ) > 1 ){
				if( isset( $object[ $tape ] ) ){
					print mediapress_get_embedcode( $object[ $tape ] , $post_id);
				}
				else{
					print mediapress_get_embedcode( $object[0] , $post_id);
				}
			}
			else{
				print mediapress_get_embedcode( $object[0] , $post_id);
			}
		}
		else{
			print mediapress_get_embedcode( $object , $post_id);
		}
	}
	add_action( 'mediapress_media' , 'mediapress_get_media_object', 10, 1);
}

if( !function_exists( 'mediapress_get_embedcode' ) ){
	/**
	 * Return embedcode/iframe
	 * @param string/int $media_object.
	 * return iframe html;
	 */
	function mediapress_get_embedcode( $media_object, $post_id ) {
		$output = $shortcode = $poster = '';	
		if(  !empty( $media_object )  ){
			// get the embed code.
			$mediapress_object_url_args = array(
				'width'	=>	'800',
				'height'	=>	'450'	
			);
			$mediapress_object_url_args = apply_filters( 'mediapress_media_object_url_args' , $mediapress_object_url_args);
			
			$embedcode = wp_oembed_get( $media_object, $mediapress_object_url_args );
			if( !$embedcode ){
				// I'm a iframe html, well, return me.
				$output .= $media_object;
			}
			else{
				// yeah, I'm a link.
				$output .= $embedcode;
			}
		}
		
		if( post_password_required( $post_id ) && is_single() ){
			return get_the_password_form( $post_id );
		}
		return do_shortcode( $output );
	}
}

if( !function_exists( 'mediapress_get_media_pagination' ) ){
	function mediapress_get_media_pagination( $post_id ) {
		if( !isset( $post_id ) )
			return; // something wrong, I dont know :D
		$tape = get_query_var( 'tape' ) ? absint( get_query_var( 'tape' ) ) : 1;
		$pagination_array = $temp = array();
		$media_object = get_post_format( $post_id ) == 'video' ? get_post_meta( $post_id, '_format_video_embed', true ) : get_post_meta( $post_id, '_format_audio_embed', true );
		$temp	=	explode("\r\n", $media_object);
		if( is_array( $temp ) && count( $temp ) > 1 ){
			$pagination_array	=	$temp;
		}
		$pagination_array	=	array_filter($pagination_array);
		if( is_array( $pagination_array ) && count( $pagination_array ) > 1 ){
			$prefix = get_option( 'permalink_structure' ) ? '?' : '&';
			print '<div class="pagination">';
				print '<ul class="pagination">';
					for ($i = 1; $i <= count( $pagination_array ); $i++) {
						$current_link = isset( $post_id ) ? get_permalink( $post_id ) . $prefix . 'tape=' . $i : null;
						if( !isset( $pagination_array[$tape-1] ) ){
							$tape = 1;
						}
						$current_item = ( $i == $tape ) ? 'class="active"' : null;
						print '<li '.$current_item.'><a href="'.$current_link.'">'.$i.'</a></li>';
					}
				print '</ul>';
			print '</div>';
		}
	}
	add_action( 'mediapress_media_pagination' , 'mediapress_get_media_pagination', 10, 1);
}