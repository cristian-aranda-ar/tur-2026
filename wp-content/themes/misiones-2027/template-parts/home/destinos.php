<?php
/**
 * Template Part: Destinos Imperdibles Carousel
 */

$hs                   = function_exists( 'tc_home_get' ) ? tc_home_get() : [];
$destinos_titulo      = $hs['destinos_titulo']        ?? 'Destinos Imperdibles';
$destinos_vt_text     = $hs['destinos_ver_todo_text'] ?? 'Ver todo';
$destinos_vt_url      = $hs['destinos_ver_todo_url']  ?? '#';
$destinos_ids         = array_filter( array_map( 'absint', $hs['destinos_post_ids'] ?? [] ) );

if ( ! empty( $destinos_ids ) ) {
    $destinos_query = new WP_Query( [
        'post_type'      => 'registro-unico',
        'post_status'    => 'publish',
        'post__in'       => $destinos_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count( $destinos_ids ),
    ] );
} else {
    $destinos_query = new WP_Query( [
        'post_type'      => 'registro-unico',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'tax_query'      => [ [
            'taxonomy' => 'categoria-ru',
            'field'    => 'slug',
            'terms'    => 'imperdibles',
        ] ],
    ] );
}
?>

<section id="destinos" aria-label="<?php esc_attr_e( 'Destinos Imperdibles', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      </span>
      <h2 class="section-title__heading"><?php echo esc_html( $destinos_titulo ); ?></h2>
    </div>
    <a href="<?php echo esc_url( $destinos_vt_url ); ?>" class="section-title__link">
      <?php echo esc_html( $destinos_vt_text ); ?>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="destinos-carousel" role="list">
    <?php if ( $destinos_query->have_posts() ) : ?>
      <?php while ( $destinos_query->have_posts() ) : $destinos_query->the_post(); ?>
        <?php
        $color   = get_field( 'color' )       ?: '#1a5c4c';
        $volanta = get_field( 'hero_volanta' ) ?: '';
        $img_url = get_the_post_thumbnail_url( null, 'large' );
        $__liked = function_exists( 'turs_user_liked' ) && turs_user_liked( get_the_ID() );
        ?>
        <div class="destino-card" role="listitem" style="background:<?php echo esc_attr( $color ); ?>;">

          <a href="<?php the_permalink(); ?>" class="destino-card__link" aria-label="<?php the_title_attribute(); ?>"></a>

          <?php if ( $img_url ) : ?>
            <img class="destino-card__img" src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
          <?php endif; ?>

          <div class="destino-card__overlay"></div>

          <?php if ( $volanta ) : ?>
            <div class="destino-card__tag" style="background:<?php echo esc_attr( $color ); ?>;">
              <span><?php echo esc_html( $volanta ); ?></span>
            </div>
          <?php endif; ?>

          <button
            class="destino-card__fav js-turs-like<?php echo $__liked ? ' is-liked' : ''; ?>"
            data-post-id="<?php the_ID(); ?>"
            data-liked="<?php echo $__liked ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr( sprintf( __( 'Guardar %s en favoritos', 'misiones-2027' ), get_the_title() ) ); ?>"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
          </button>

          <div class="destino-card__info">
            <h3 class="destino-card__name"><?php the_title(); ?></h3>
            <div class="destino-card__rating">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="<?php echo esc_attr( $color ); ?>" stroke="<?php echo esc_attr( $color ); ?>" stroke-width="2" aria-hidden="true">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
              <span class="destino-card__rating-val">4.8</span>
              <span class="destino-card__rating-count">(2.4K)</span>
            </div>
          </div>

        </div>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>

  <a href="<?php echo esc_url( $destinos_vt_url ); ?>" class="section-cta">
    <?php echo esc_html( $destinos_vt_text ); ?>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
  </a>

</section>
