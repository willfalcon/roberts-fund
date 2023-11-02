<!-- home.php -->

<?php get_header(); ?>

<?php 
  global $post;
  $page = get_page(get_option('page_for_posts'));
  setup_postdata($page);
?>

<div class="page-title">
  <h1><?php single_post_title(); ?></h1>
</div>


 <div class="page-content">
   <?php the_content(); ?>
 </div>
 

<?php if ( have_posts() ) : ?>
  <div class="articles">
    <?php while ( have_posts() ) : the_post(); ?>

      <?php get_template_part('parts/post'); ?>

    <?php endwhile; ?>
  </div>

<?php get_template_part('parts/pagination'); ?>

<?php endif; ?>

<?php get_footer(); ?>
