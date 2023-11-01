<div class="image-link">
  <?php 
    $image = get_field('image'); 
    $link = get_field('link');
  ?>
 
  <div class="image-link__image-wrapper">
    <?php if ($image) : ?>
      <img class="image-link__image" src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
    <?php endif; ?>
  </div>

  <a class="image-link__link" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
    <span class="image-link__title">
      <?php echo $link['title']; ?>
    </span>
  </a>
</div>