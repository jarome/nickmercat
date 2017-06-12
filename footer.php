		<!-- ///// FOOTER ///// -->

    <section class="partners">
      <h2>Partners</h2>
      <ul class="partners__partner-list">
        <li>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/luckydesign.png" width="185" alt="">
        </li>
        <li>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/coatshire.png" width="171" alt="">
        </li>
        <li>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/morris-finance.png" width="187" alt="">
        </li>
        <li>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/timken.png" width="177" alt="">
        </li>
        <li>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/partners/arai.png" width="187" alt="">
        </li>
      </ul>
    </section>
		<footer class="footer">
			<div class="container">
			  <div class="footer-logo"></div>
			  <ul class="footer-social-links">
          <li>
            <a class="footer-social-links__item footer-social-links__instagram">Instagram</a>
          </li>
          <li>
            <a class="footer-social-links__item footer-social-links__twitter">Twitter</a>
          </li>
          <li>
            <a class="footer-social-links__item footer-social-links__facebook">Facebook</a>
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
            <a href="#">Terms and Privacy</a>
          </li>
        </ul>
      </div>
		</footer>
		<?php wp_footer();?>	
	</body>
</html>