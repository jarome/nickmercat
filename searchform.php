<form method="get" action="<?php print home_url();?>">
	<input class="search-widget" value="<?php print get_query_var('s');?>" type="text" name="s" id="search" placeholder="<?php _e('Type and hit enter...','neat');?>">
	<?php 
	/**
	 * neat_search_form action.
	 * hooked neat_search_form, 10
	 */
	do_action( 'neat_search_form' );
	?>	
</form>