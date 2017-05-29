<?php
/**
 * Nothing found here.
 */ 
if( !defined('ABSPATH') ) exit;
$post_type = get_post_type() ? get_post_type() : 'post';
?>
<h3 class="page-title"><?php printf( __('Nothing found, you may add new post %s.','neat'), '<a href="'.admin_url( 'post-new.php?post_type=' . $post_type ).'">here</a>' );?></h3>