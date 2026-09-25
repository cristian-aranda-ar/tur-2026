<?php
/**
 * Template Name: Explorar – Mapa provincial
 * Template Post Type: page
 */
get_header();

$categorias = array_filter(
    array_map( 'sanitize_text_field', explode( ',', wp_unslash( $_GET['categoria'] ?? '' ) ) )
);
?>

<div class="page-hero">
  <div class="page-hero__inner">
    <h1 class="page-hero__title"><?php the_title(); ?></h1>
    <?php if ( $categorias ) : ?>
      <div class="page-hero__cats">
        <?php foreach ( $categorias as $cat ) : ?>
          <span class="page-hero__cat"><?php echo esc_html( $cat ); ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<main id="main" class="explorar-page" role="main">

  <?php get_template_part( 'template-parts/home/map', null, [] ); ?>

  <?php if ( $categorias ) :
    $ru_query = new WP_Query( [
      'post_type'      => 'registro-unico',
      'post_status'    => 'publish',
      'posts_per_page' => 48,
      'no_found_rows'  => true,
      'tax_query'      => [ [
        'taxonomy' => 'categoria-ru',
        'field'    => 'slug',
        'terms'    => array_values( $categorias ),
        'operator' => 'IN',
      ] ],
    ] );
  ?>
  <?php if ( $ru_query->have_posts() ) : ?>
  <section class="explorar-results" aria-label="<?php esc_attr_e( 'Resultados', 'misiones-2027' ); ?>">

    <div class="section-title">
      <div class="section-title__text">
        <span class="section-title__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </span>
        <h2 class="section-title__heading">
          <?php printf( esc_html__( '%d resultados', 'misiones-2027' ), $ru_query->post_count ); ?>
        </h2>
      </div>
    </div>

    <div class="explorar-results__grid">
      <?php while ( $ru_query->have_posts() ) : $ru_query->the_post();
        $color   = get_field( 'color' )       ?: '#1a5c4c';
        $volanta = get_field( 'hero_volanta' ) ?: '';
        $img_url = get_the_post_thumbnail_url( null, 'large' );
        $liked   = function_exists( 'turs_user_liked' ) && turs_user_liked( get_the_ID() );
      ?>
        <div class="destino-card" style="background:<?php echo esc_attr( $color ); ?>;">
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
            class="destino-card__fav js-turs-like<?php echo $liked ? ' is-liked' : ''; ?>"
            data-post-id="<?php the_ID(); ?>"
            data-liked="<?php echo $liked ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr( sprintf( __( 'Guardar %s en favoritos', 'misiones-2027' ), get_the_title() ) ); ?>"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
            </svg>
          </button>
          <div class="destino-card__info">
            <h3 class="destino-card__name"><?php the_title(); ?></h3>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

  </section>
  <?php endif; ?>
  <?php endif; ?>

</main>
<?php
get_footer();
