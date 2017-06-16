<?php 
/**
 * Template Name: Contact
 */
?>
<?php get_header();?>
  <div class="contact-page-wrapper">
	  <div <?php post_class('blog-single');?>>

      <div class="full-width-header full-width-header__contact"><h2>Contact</h2></div>

      <div class="vc_row wpb_row vc_row-fluid container">
        <div class="col-md-12 wpb_column container ">

          <div class="vc_row wpb_row vc_inner vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-6 contact-image-container">
              <img class="hide-for-mobile" src="<?php echo get_template_directory_uri(); ?>/assets/img/photos/nick-contact.png" alt="<?php bloginfo('description');?>">
            </div>

            <div class="wpb_column vc_column_container vc_col-sm-6">
                <?php neat_yoast_breadcrumb();?>
                <?php if( have_posts() ) : the_post();?>
                    <?php
                    /**
                     * neat_post_format_content action.
                     * hooked neat_post_format_content, 10;
                     */
                    do_action( 'neat_post_format_content' );
                    ?>

                    <?php
                    /**
                     * hooked, neat_wp_link_pages, 10
                     * hooked, neat_post_tags, 20
                     * hooked, neat_author_box, 30
                     */
                    the_content();


                endif;?>

            </div>
          </div>

        </div>
      </div>
    </div>



</div>
<?php get_footer();?>