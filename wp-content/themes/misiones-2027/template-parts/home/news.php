<?php
/**
 * Template Part: Noticias
 */

$hs             = function_exists( 'tc_home_get' ) ? tc_home_get() : [];
$noticias_count = in_array( intval( $hs['noticias_count'] ?? 6 ), [ 3, 6, 9 ], true )
    ? intval( $hs['noticias_count'] )
    : 6;

$news_query = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $noticias_count,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'ignore_sticky_posts' => true,
] );

$tag_colors = [ '#3b82f6', '#22c55e', '#a855f7', '#f59e0b', '#ef4444', '#10b981', '#0ea5e9', '#f97316', '#ec4899' ];
?>

<section id="noticias" aria-label="<?php esc_attr_e( 'Noticias de turismo', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
      </span>
      <h2 class="section-title__heading">Noticias</h2>
    </div>
    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="section-title__link">
      Ver todo
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="news-list">
    <?php
    $color_idx = 0;
    if ( $news_query->have_posts() ) :
        while ( $news_query->have_posts() ) : $news_query->the_post();
            $color    = $tag_colors[ $color_idx % count( $tag_colors ) ];
            $cats     = get_the_category();
            $tag_name = ! empty( $cats ) ? $cats[0]->name : __( 'Noticia', 'misiones-2027' );
            $thumb    = get_the_post_thumbnail_url( null, 'large' );
            $color_idx++;
    ?>
      <a href="<?php the_permalink(); ?>" class="news-card">

        <div class="news-card__img" style="--news-color:<?php echo esc_attr( $color ); ?>;">
          <?php if ( $thumb ) : ?>
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
          <?php endif; ?>
          <span class="news-card__tag" style="background:<?php echo esc_attr( $color ); ?>;">
            <?php echo esc_html( $tag_name ); ?>
          </span>
        </div>

        <div class="news-card__body">
          <h3 class="news-card__title"><?php the_title(); ?></h3>
          <span class="news-card__cta" aria-hidden="true">
            Leer más
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
          </span>
        </div>

      </a>
    <?php
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
  </div>

  <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="section-cta">
    Ver todo
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
  </a>

</section>
