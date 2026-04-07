<?php
/**
 * Main Index Template (fallback)
 */
get_header();
?>

<div class="inner-page-hero">
  <h1 class="inner-page-hero__title"><?php bloginfo( 'name' ); ?></h1>
  <p class="inner-page-hero__sub"><?php bloginfo( 'description' ); ?></p>
</div>

<main class="page-content" id="main" role="main">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2><?php the_title(); ?></h2>
        <?php the_excerpt(); ?>
      </article>
    <?php endwhile; ?>
    <?php the_posts_navigation(); ?>
  <?php else : ?>
    <p><?php esc_html_e( 'No se encontró contenido.', 'misiones-2027' ); ?></p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
