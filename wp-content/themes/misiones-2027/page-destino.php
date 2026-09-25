<?php
/**
 * Template Name: Destino – Full Width
 * Template Post Type: page
 */
get_header();
while ( have_posts() ) :
    the_post();
    ?>
    <main id="main" class="destino-page" role="main">
        <?php the_content(); ?>
    </main>
    <?php
endwhile;
get_footer();
