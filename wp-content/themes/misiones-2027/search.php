<?php
/**
 * Search Results — registro-unico
 */
get_header();

$search_term = get_search_query();
?>

<main class="ru-search-results" role="main">
  <div class="ru-search-results__header">
    <h1 class="ru-search-results__title">
      <?php if ( $search_term ) : ?>
        <?php printf( esc_html__( 'Resultados para "%s"', 'misiones-2027' ), $search_term ); ?>
      <?php else : ?>
        <?php esc_html_e( 'Resultados de búsqueda', 'misiones-2027' ); ?>
      <?php endif; ?>
    </h1>
    <?php if ( have_posts() ) : ?>
      <p class="ru-search-results__count"><?php printf( esc_html__( '%d resultado(s) encontrado(s)', 'misiones-2027' ), $wp_query->found_posts ); ?></p>
    <?php endif; ?>

    <!-- Nuevo buscador en la página de resultados -->
    <form class="ru-search-results__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="hidden" name="post_type" value="registro-unico">
      <input
        type="search"
        name="s"
        placeholder="<?php esc_attr_e( 'Buscar destinos, experiencias…', 'misiones-2027' ); ?>"
        value="<?php echo esc_attr( $search_term ); ?>"
        autocomplete="off"
      >
      <button type="submit"><?php esc_html_e( 'Buscar', 'misiones-2027' ); ?></button>
    </form>
  </div>

  <div class="ru-search-results__grid">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
      $resumen   = get_post_meta( get_the_ID(), 'resumen', true );
      $direccion = get_post_meta( get_the_ID(), 'direccion', true );
      $telefono  = get_post_meta( get_the_ID(), 'telefono', true );
      $sitio_web = get_post_meta( get_the_ID(), 'sitio_web', true );
    ?>
      <article class="ru-card">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="ru-card__img">
            <?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
          </div>
        <?php endif; ?>
        <div class="ru-card__body">
          <h2 class="ru-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <?php if ( $resumen ) : ?>
            <p class="ru-card__desc"><?php echo esc_html( wp_trim_words( $resumen, 20 ) ); ?></p>
          <?php endif; ?>
          <div class="ru-card__meta">
            <?php if ( $direccion ) : ?>
              <span class="ru-card__meta-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html( $direccion ); ?>
              </span>
            <?php endif; ?>
            <?php if ( $telefono ) : ?>
              <span class="ru-card__meta-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <?php echo esc_html( $telefono ); ?>
              </span>
            <?php endif; ?>
          </div>
          <?php if ( $sitio_web ) : ?>
            <a href="<?php echo esc_url( $sitio_web ); ?>" class="ru-card__link" target="_blank" rel="noopener noreferrer">
              <?php esc_html_e( 'Ver sitio web', 'misiones-2027' ); ?>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
          <?php endif; ?>
        </div>
      </article>
    <?php endwhile; else : ?>
      <div class="ru-search-results__empty">
        <p><?php printf( esc_html__( 'No se encontraron resultados para "%s".', 'misiones-2027' ), esc_html( $search_term ) ); ?></p>
        <p><?php esc_html_e( 'Intentá con otros términos de búsqueda.', 'misiones-2027' ); ?></p>
      </div>
    <?php endif; ?>
  </div>

  <?php if ( $wp_query->max_num_pages > 1 ) : ?>
    <nav class="ru-search-results__pagination" aria-label="<?php esc_attr_e( 'Paginación', 'misiones-2027' ); ?>">
      <?php echo paginate_links(); ?>
    </nav>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
