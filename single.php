<!-- single.php -->

<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <a class="article-back-link" href="<?php echo get_permalink(get_option('page_for_posts')); ?>">< <?php _e('Back to Articles', 'cdhq'); ?></a>

  <div class="page-title">
    <h1><?php the_title(); ?></h1>
  </div>


  <div class="page-content">
    <?php the_content(); ?>
  </div>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
