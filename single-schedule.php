<?php get_header();
$bloglayout	=	neat_get_blog_layout();
?>

    <div class="schedule-page">

      <div class="full-width-header full-width-header__schedule"><h2>SCHEDULE</h2></div>

      <div class="schedule-page-hero">
        <div class="container">

          <div id="blog-teasers" class="owl-carousel owl-theme block-blog-teasers schedule-carousel blog" data-show-nav>

            <?php
            // query events order
            $scheduleposts = get_posts(array(
                'posts_per_page'	=> -1,
                'post_type'			=> 'schedule',
                'post_status'	=>	'publish',
                'order'				=> 'ASC',
                'orderby'			=> 'meta_value',
                'meta_key'			=> 'time_of_event',
                'meta_type'			=> 'DATETIME'
            ));

            $current_post_id = $post->ID;

            $loop_counter = 1;

            if( $scheduleposts ): ?>


              <?php foreach( $scheduleposts as $p ): ?>

              <?php
              $date = get_field('time_of_event', $p->ID); // Pull your value
              $datetime = strtotime( $date ); // Convert to + seconds since unix epoch
              $date_now = date('Y-m-d H:i:s');
              $time_now = strtotime( $date_now ); // Convert today -1 day to seconds since unix epoch
              if ( $datetime < $time_now ) { // if date value pulled is today or later, we're overdue
                  $eventcompleted = true;
              } else {
                  $eventcompleted = false;
              }
              ?>

                <div class="blog-item">

                  <div class="media">
                    <div class="schedule-carousel__thumb">
                      <a href="<?php the_permalink($p->ID) ?>">


                        <div class="schedule-carousel__thumb-overlay <?php if( $eventcompleted === true ) { echo 'schedule-carousel__thumb-overlay-previous';} ?> <?php if( $current_post_id === $p->ID ) { echo 'schedule-carousel__thumb-overlay-current';} ?>"></div>
                          <?php
                          $circuitthumbnail = get_field( "circuit_image", $p->ID );
                          if( $circuitthumbnail ) { ?>
                            <div class="schedule-carousel__track-outline">
                              <img src="<?php echo $circuitthumbnail ?>" class="" alt="" srcset="" sizes="" />
                            </div>
                          <?php } ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/photos/headshot@2x.png" class="" alt="" srcset="" sizes="" />
                      </a>
                    </div>
                  </div>
                  <div class="blog-teaser schedule-carousel__information <?php if( $eventcompleted === true ) { echo 'schedule-carousel__information-previous';} ?> <?php if( $current_post_id === $p->ID ) { echo 'schedule-carousel__information-current';} ?>">
                    <div class="event-number">Event <?php echo $loop_counter ?></div>
                    <h4 class="post-title"><a href="<?php the_permalink($p->ID);?>"><?php echo $p->post_title; ?></a></h4>
                    <div class="meta"><?php the_field('event_period', $p->ID); ?></div>
                  </div>
                </div>
                <?php $loop_counter++; ?>
              <?php endforeach; ?>

            <?php endif; ?>

          </div>
        </div>
      </div>

      <?php if( have_posts() ) : the_post();?>

      <div class="schedule-content">

        <div class="container">


            <?php  $currentEventIndex = 1; ?>

            <div class="schedule-content__event-number">Event <?php if( $scheduleposts ): ?><?php foreach( $scheduleposts as $p ): ?><?php if( $current_post_id === $p->ID ) { echo $currentEventIndex; } ?><?php $currentEventIndex++; ?><?php endforeach; ?><?php endif; ?></div>
            <h3 class="schedule-content__title"><?php the_title();?></h3>
            <span class="schedule-content__description"><?php the_field('event_description') ?></span>
            <span class="schedule-content__period"><?php the_field('event_period') ?></span>

        </div>
      </div>


      <div class="circuit-detail">
        <!-- START Countdown Component -->
        <?php
        $eventdate = get_field('time_of_event'); // Pull your value
        ?>
        <div id="js-countdown-clock" class="countdown-clock" data-eventtime="<?php echo $eventdate ?>">
          <div class="countdown-clock__timer">
            <span class="countdown-clock__date">000</span>
            <span class="countdown-clock__date">00</span>
            <span class="countdown-clock__date">00</span>
            <span class="countdown-clock__date">00</span>
          </div>
          <div class="countdown-clock__labels">
            <span class="countdown-clock__label">Days</span>
            <span class="countdown-clock__label">Hours</span>
            <span class="countdown-clock__label">Mins</span>
            <span class="countdown-clock__label">Secs</span>
          </div>
        </div>
        <!-- END Countdown Component -->

        <div class="container">

          <div class="circuit-detail-container">
            <div class="circuit-detail__col">

              <h2 class="dual-size">
                The<span class="dual-size__large">Circuit</span>
              </h2>

              <div class="circuit-detail__description"><?php the_field('circuit_description') ?></div>

              <?php if( have_rows('circuit_results') ): ?>

              <h2 class="dual-size">
                  <?php the_field('circuit_results_year') ?><span class="dual-size__large">Results</span>
              </h2>

              <div class="circuit-detail__results">
                <?php while( have_rows('circuit_results') ): the_row();
                  $circuitresultslabel = get_sub_field('circuit_result_label');
                  $circuitresultplacement = get_sub_field('circuit_result_placement');
                ?>

                <div class="circuit-detail__result js-circuit-detail-result">
                  <div class="circuit-detail__result-placement"><?php echo $circuitresultplacement; ?></div>
                  <div class="circuit-detail__result-label"><?php echo $circuitresultslabel; ?></div>
                </div>

                <?php endwhile; ?>
              </div>
              <?php endif; ?>
            </div>

            <div class="circuit-detail__col">

              <div class="circuit-detail__track-outline">
                <?php $resultscircuit = get_field( "circuit_image" ); ?>
                <img src="<?php echo $resultscircuit; ?>" class="" alt="" srcset="" sizes="" />
              </div>
              <div class="circuit-detail__information">

                  <?php if( have_rows('circuit_stats') ): ?>

                    <ul class="circuit-detail__table">
                        <?php while( have_rows('circuit_stats') ): the_row();
                            $circuitstatskey = get_sub_field('stat_type');
                            $circuitstatslabel = get_sub_field('stat_value');
                            ?>

                          <li>
                            <div class="circuit-detail__key"><?php echo $circuitstatskey; ?></div>
                            <div class="circuit-detail__value"><?php echo $circuitstatslabel; ?></div>
                          </li>

                        <?php endwhile; ?>
                    </ul>
                  <?php endif; ?>

              </div>
            </div>
          </div>

        </div>

      </div>

      <?php endif;?>

    </div>

<?php get_footer();?>