      </div>

      <footer class="footer">
        <?php $footer_logo = get_field( 'footer_logo', 'options' ); ?>
        <?php if ($footer_logo) : ?>
          <img class="footer__logo" src="<?php echo $footer_logo['sizes']['medium']; ?>" alt="<?php echo $footer_logo['alt']; ?>" />
        <?php endif; ?>
      </footer>
        
    </div>
    <?php wp_footer(); ?>
  </body>
</html>