<?php 
  $imgThumb = get_post_thumbnail_id();
  $imgSrc = wp_get_attachment_image_src($imgThumb, 'medium');
  $srcSet = wp_get_attachment_image_srcset($imgThumb, 'medium');
  
?>
<a class="post<?php if (!$imgSrc) { echo ' no-image'; } ?>" href="<?php the_permalink(); ?>">
  <!-- <time class="post__date"><?php the_date( 'F j, Y' ); ?></time> -->
  <h3 class="post__title"><?php the_title(); ?></h3>
  <div class="post__excerpt"><?php the_content(); ?></div> 
  <span class="post__read-more"><?php _e('Read More', 'cdhq'); ?></span>
  <!-- <?php if ($imgSrc) : ?>
    <div class="post__image-wrapper">
      <img class="post__image" src="<?php echo $imgSrc[0]; ?>"<?php if ($srcSet) : ?> srcSet="<?php echo $srcSet; ?>"<?php endif; ?> alt="<?php the_title(); ?>" />
    </div>
  <?php endif; ?> -->
</a>
