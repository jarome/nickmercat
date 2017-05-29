<div <?php post_class();?>>
	<?php if( has_post_thumbnail() ):?>
		<div class="media">
			<div class="fitvids">	
				<a class="featured-image" href="<?php the_permalink();?>">
					<?php print get_the_post_thumbnail( null, apply_filters( 'neat_shortcode_thumbnail_size' , 'post-340-255') );?>
				</a>
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
		do_action( 'neat_post_meta', apply_filters( 'neat_shortcode_post_meta' , 'min') );
		?>
	</div>
</div>