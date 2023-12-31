<!-- page.php -->

<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <div class="page-title">
    <h1><?php the_title(); ?></h1>
  </div>

  <div class="page-content">
    <?php the_content(); ?>
    <?php $watermark = get_field('watermark_image'); ?>
    <img class="watermark-image" src="<?php echo $watermark['sizes']['large']; ?>" alt="" />
  </div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
