<div <?php post_class( 'video-type' );?>>
	<?php 
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
		<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
		</div>
	</div>
	<?php endif;?>	
	<div class="blog-teaser">
		<h4 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
		<?php 
		/**
		 * neat_post_meta action.
		 * hooked neat_post_meta, 10
		 */
		do_action( 'neat_post_meta' );
		?>
		<?php the_content();?>
	</div>
</div>
