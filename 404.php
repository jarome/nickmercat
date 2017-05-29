<?php get_header();?>
	<div class="error-404">
		<div class="container">
			<h1><?php _e('404','neat');?></h1>
			<!-- <h2>Whoops!</h2> -->
			<h3><?php _e('looks like you\'re lost.','neat')?></h3>
			<hr class="small">
			<?php 
			$blog_page = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_permalink( apply_filters( 'neat_blogpage_id' , null) );
			?>
			<p><?php printf(  __('How about %s? You may also want to read some of %s!','neat') , '<a href="'.home_url().'">'.__('a trip back to the homepage','neat').'</a>', '<a href="'.$blog_page.'">'.__('our latest blog posts','neat').'</a>' );?></p>
		</div>
	</div>
<?php get_footer();?>