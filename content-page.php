<div <?php post_class();?>>
	<?php if( has_post_thumbnail() ):?>
		<div class="media">
			<a class="featured-image" href="<?php the_permalink();?>">
				<?php print get_the_post_thumbnail( null, apply_filters( 'neat_shortcode_thumbnail_size' , 'post-340-255') );?>
			</a>
		</div>
	<?php endif;?>
	<div class="blog-teaser">
		<h4 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
		<?php //the_content();?>
	</div>
</div>
