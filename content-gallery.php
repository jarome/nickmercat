<div <?php post_class();?>>
	<?php 
	$content = get_post_meta( $post->ID, '_format_gallery_images', true );
	if( $content && is_array( $content ) ):
	?>
	<div class="media">
		<div class="fitvids">
			<div id="gallery-slider<?php the_ID();?>" class="flexslider">
				<ul class="slides">
					<?php for ($i = 0; $i < count( $content ); $i++):?>
				  		<?php
				  			$src = wp_get_attachment_image_src($content[$i], apply_filters( 'neat_shortcode_thumbnail_size' , 'full') );
				  			print '<li><img alt="" src="'.$src[0].'" /></li>';
				  		 ?>
				  	<?php endfor;?>
				</ul> 
			</div>
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