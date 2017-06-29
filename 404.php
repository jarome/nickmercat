<?php get_header();?>
<div class="blog-single">
  <div class="full-width-header full-width-header__contact"><h2>404</h2></div>

		<div class="container">
			<h2><?php _e('404','neat');?></h2>
			<!-- <h2>Whoops!</h2> -->
			<h3><?php _e('looks like you\'re lost.','neat')?></h3>
			<hr class="small">
			<?php 
			$blog_page = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_permalink( apply_filters( 'neat_blogpage_id' , null) );
			?>
			<p><a href="<?php echo home_url() ?>">Back to the homepage</a></p>
		</div>
</div>
<?php get_footer();?>