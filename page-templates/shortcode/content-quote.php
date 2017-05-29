<div <?php post_class();?>>
<?php global $post;?>
	<div class="quote-format">
		<a href="<?php the_permalink();?>"><blockquote><?php print wp_filter_nohtml_kses( $post->post_content );?></blockquote></a>
	</div>
	<div class="blog-teaser">
		<?php 
		/**
		 * neat_post_meta action.
		 * hooked neat_post_meta, 10
		 */
		do_action( 'neat_post_meta', apply_filters( 'neat_shortcode_post_meta' , 'min') );
		?>
	</div>
</div>