<?php 
/**
 * Template Name: Page Meet Nick
 */
?>
<?php get_header();?>
	<div <?php post_class('blog-single');?>>
      <?php
      if (is_page( 'meet-nick' ) ): ?>
        <div class="full-width-header full-width-header__meetnick"></div>
      <?php endif;
      ?>


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
			
				if( comments_open() && apply_filters( 'neat_comment_system' , true) === true ){
					comments_template();
				}
				
				endif;?>
    <div class="career-stats">
      <div class="career-stats-col career-stats__about">
        <div class="career-stats__container">
          <h2 class="dual-size">
            Nick<span class="dual-size__large">Percat</span>
          </h2>

          <p>COATES HIRE AMBASSADOR <br/><span class="accent">SUPERCARS DRIVER</span></p>

          <ul class="career-stats__table">
            <li>
              <span class="career-stats__key">Nationality:</span>
              <span class="career-stats__value">Australian</span>
            </li>
            <li>
              <span class="career-stats__key">Age:</span>
              <span class="career-stats__value">28 years old</span>
            </li>
            <li>
              <span class="career-stats__key">Born:</span>
              <span class="career-stats__value">September 14, 1988 </span>
            </li>
            <li>
              <span class="career-stats__key">Hometown:</span>
              <span class="career-stats__value">Adelaide, South Australia</span>
            </li>
            <li>
              <span class="career-stats__key">Lives:</span>
              <span class="career-stats__value">Melbourne, Victoria</span>
            </li>
            <li>
              <span class="career-stats__key">Interests:</span>
              <span class="career-stats__value">I enjoy karting and cycling. Also I spend a bit of time with my 2 chocolate Labradors, Douglas and Nelson.</span>
            </li>
            <li>
              <span class="career-stats__key">Vehicle:</span>
              <span class="career-stats__value">2017 AMG CLA45 Mercedes</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="career-stats-col career-stats__facts">
        <div class="career-stats__container">
          <h2 class="dual-size">
            Fun<span class="dual-size__large">Facts</span>
          </h2>

          <p>NICK'S FAVOURITE</p>

          <ul class="career-stats__table">
            <li>
              <span class="career-stats__key">RACER:</span>
              <span class="career-stats__value">Growing up it was Michael Schumacher. In the current era it is Sebastian Vettel</span>
            </li>
            <li>
              <span class="career-stats__key">RACETRACK:</span>
              <span class="career-stats__value">Bathurst</span>
            </li>
            <li>
              <span class="career-stats__key">CAR:</span>
              <span class="career-stats__value">991 Porsche GT3 RS</span>
            </li>
            <li>
              <span class="career-stats__key">TV SHOW:</span>
              <span class="career-stats__value">Seinfeld</span>
            </li>
            <li>
              <span class="career-stats__key">VIDEO GAME:</span>
              <span class="career-stats__value">iRacing on my simulator or Call of Duty</span>
            </li>
            <li>
              <span class="career-stats__key">MOVIE:</span>
              <span class="career-stats__value">Anchorman or any comedy</span>
            </li>
            <li>
              <span class="career-stats__key">ACTOR:</span>
              <span class="career-stats__value">Will Ferrell</span>
            </li>
            <li>
              <span class="career-stats__key">FOOD:</span>
              <span class="career-stats__value">Smashed Avo on toast</span>
            </li>
            <li>
              <span class="career-stats__key">DRINK:</span>
              <span class="career-stats__value">Mango smoothy from my local cafe!</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="career-stats-col career-stats__stats">
        <div class="career-stats__container">
          <h2 class="dual-size">
            Career<span class="dual-size__large">Stats</span>
          </h2>

          <p><span class="accent">2009</span><br/>
            <strong>CHAMPION</strong> AUSTRALIAN<br/>
            FORMULA FORD</p>

          <p><span class="accent">2011</span><br/>
            <strong>CHAMPION</strong> BATHURST 1000</p>

          <p><span class="accent">2013</span><br/>
            PORSCHE ROOKIE OF THE YEAR<br/>
            <strong>RUNNER UP</strong> PORSCHE CARRERA CUP </p>

          <p><span class="accent">2014</span><br/>
            DEBUT INTO SUPERCARS<br/>
            <strong>2ND</strong> SYDNEY MOTORSPORT PARK<br/>
            <strong>3RD</strong> BATHURST 1000</p>

          <p><span class="accent">2016</span><br/>
            <strong>CHAMPION</strong> CLIPSAL 500<br/>
            <strong>3RD</strong> BATHURST 1000</p>

        </div>
      </div>
    </div>
</div>
<?php get_footer();?>