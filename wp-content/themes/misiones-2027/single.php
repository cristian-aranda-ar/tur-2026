<?php
/**
 * Single Post Template
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

  <div class="inner-page-hero">
    <h1 class="inner-page-hero__title"><?php the_title(); ?></h1>
    <p class="inner-page-hero__sub">
      <?php the_date(); ?> &middot; <?php the_author(); ?>
    </p>
  </div>

  <main class="page-content" id="main" role="main">
    <?php the_content(); ?>
  </main>

<?php endwhile; ?>

<?php get_footer(); ?>
