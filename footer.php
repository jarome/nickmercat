		<!-- ///// FOOTER ///// -->
    <?php if (is_front_page()) : ?>

    <section class="schedule-information-section">
      <div class="circuit-detail circuit-detail-homepage">
        
        <div class="container">

          <div class="circuit-detail-container">
            <div class="circuit-detail__col">

            <div class="circuit-detail-homepage__logo-overlay"></div>


            <?php
            // find date time now
            $date_now = date('Y-m-d H:i:s');

            // query events
            $latestresult = get_posts(array(
                'posts_per_page'	=> 1,
                'post_type'			=> 'schedule',
                'meta_query' 		=> array(
                    array(
                        'key'			=> 'time_of_event',
                        'compare'		=> '<',
                        'value'			=> $date_now,
                        'type'			=> 'DATETIME'
                    )),
                'order'				=> 'DESC',
                'orderby'			=> 'meta_value',
                'meta_key'			=> 'time_of_event',
                'meta_type'			=> 'DATE'
            ));


            if( $latestresult ): ?>

            <?php foreach( $latestresult as $r ): ?>

            <?php
            $date = get_field('time_of_event', $r->ID); // Pull your value
            $datetime = strtotime( $date ); // Convert to + seconds since unix epoch
            $date_now = date('Y-m-d H:i:s');
            $time_now = strtotime( $date_now ); // Convert today -1 day to seconds since unix epoch
            if ( $datetime < $time_now ) { // if date value pulled is today or later, we're overdue
                $eventcompleted = true;
            } else {
                $eventcompleted = false;
            }
            ?>

              <div class="circuit-detail__information">

                <h2 class="dual-size">
                  Latest<span class="dual-size__large">Result</span>
                </h2>

                <h4 class="post-title"><?php echo $r->post_title; ?></h4>

                <div class="schedule-content__description"><?php the_field('event_description', $r->ID) ?></div>

                <div class="meta"><?php the_field('event_period', $r->ID); ?></div>

                <?php if( have_rows('circuit_results', $r->ID)): ?>

                  <div class="circuit-detail__results">
                    <?php while( have_rows('circuit_results', $r->ID) ): the_row();
                        $circuitresultslabel = get_sub_field('circuit_result_label', $r->ID);
                        $circuitresultplacement = get_sub_field('circuit_result_placement', $r->ID);
                        ?>

                      <div class="circuit-detail__result js-circuit-detail-result">
                        <div class="circuit-detail__result-placement"><?php echo $circuitresultplacement; ?></div>
                        <div class="circuit-detail__result-label"><?php echo $circuitresultslabel; ?></div>
                      </div>

                    <?php endwhile; ?>
                  </div>

                <?php endif; ?>

              </div>

            <?php endforeach; ?>

            <?php endif; ?>
            </div>

            <div class="circuit-detail__col">

              <?php

              // query events
              $upcomingposts = get_posts(array(
                  'posts_per_page'	=> 1,
                  'post_type'			=> 'schedule',
                  'meta_query' 		=> array(
                      array(
                          'key'			=> 'time_of_event',
                          'compare'		=> '>=',
                          'value'			=> $date_now,
                          'type'			=> 'DATETIME'
                      )),
                  'order'				=> 'ASC',
                  'orderby'			=> 'meta_value',
                  'meta_key'			=> 'time_of_event',
                  'meta_type'			=> 'DATE'
              ));

              // query events order

              $loop_counter = 1;

              if( $upcomingposts ): ?>


              <?php foreach( $upcomingposts as $p ): ?>

              <?php
              $upcomingdate = get_field('time_of_event', $p->ID); // Pull your value
              $upcomingdatetime = strtotime( $date ); // Convert to + seconds since unix epoch
              $upcomingdate_now = date('Y-m-d H:i:s');
              $upcomingtime_now = strtotime( $date_now ); // Convert today -1 day to seconds since unix epoch
              if ( $upcomingdatetime < $upcomingtime_now ) { // if date value pulled is today or later, we're overdue
                  $eventcompleted = true;
              } else {
                  $eventcompleted = false;
              }
              ?>


              <div class="circuit-detail__information">


                <h2 class="dual-size">
                  Next<span class="dual-size__large">Race</span>
                </h2>

                <h4 class="post-title"><?php echo $p->post_title; ?></h4>

                <div class="schedule-content__description"><?php the_field('event_description', $p->ID) ?></div>

                <div class="meta"><?php the_field('event_period', $p->ID); ?></div>

                  <!-- START Countdown Component -->
                  <?php

                  $eventdate = get_field('time_of_event', $p->ID); // Pull your value

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
                </div>


                <?php endforeach; ?>

              <?php else: ?>

              <div class="circuit-detail__information">

                <h2 class="dual-size">
                  Next<span class="dual-size__large">Race</span>
                </h2>

                <h4>No upcoming races announced.</h4>

              </div>

              <?php endif; ?>


          </div>

        </div>




      </div>
    </section>

    <?php endif; ?>

    <section class="partners">
      <h2>Partners</h2>
      <ul class="partners__partner-list">
        <li>
          <a href="https://www.coateshire.com.au/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/coatshire.png" width="171" alt=""></a>
        </li>
        <li>
          <a href="https://www.timken.com/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/timken.png" width="177" alt=""></a>
        </li>
        <li>
          <a href="http://www.araihelmets.net.au/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/arai.png" width="187" alt=""></a>
        </li>
        <li>
          <a href="http://www.luckydesign.it/en/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/luckydesign.png" width="185" alt=""></a>
        </li>

      </ul>
    </section>
		<footer class="footer">
			<div class="container">
			  <div class="footer-logo"></div>
			  <ul class="footer-social-links">
          <li>
            <a class="footer-social-links__item footer-social-links__instagram" href="https://www.instagram.com/nickpercat/">Instagram</a>
          </li>
          <li>
            <a class="footer-social-links__item footer-social-links__twitter" href="https://twitter.com/nickpercat">Twitter</a>
          </li>
          <li>
            <a class="footer-social-links__item footer-social-links__facebook" href="https://www.facebook.com/nickpercat/">Facebook</a>
          </li>
        </ul>
			</div>
      <div class="container footer-information">
        <div class="footer-information__copyright">
          &copy; <?php echo date("Y"); ?> Nick Percat
        </div>
        <ul class="footer-information__terms">
          <li>
            <a href="#">Contact</a>
          </li>
          <li>
            <a href="http://sixtwo.com.au/">Site by SIXTWO</a>
          </li>
        </ul>
      </div>
		</footer>
		<?php wp_footer();?>	
	</body>
</html>