<div <?php post_class();?>>
	<div class="media">
		<div class="quote-format">
			<a href="<?php the_permalink()?>">
				<?php the_content();?>
			</a>
		</div>	
	</div>
	<div class="blog-teaser">
		<?php 
		/**
		 * neat_post_meta action.
		 * hooked neat_post_meta, 10
		 */
		do_action( 'neat_post_meta' );
		?>
	</div>
</div>