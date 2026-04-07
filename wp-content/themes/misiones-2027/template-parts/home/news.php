<?php
/**
 * Template Part: Noticias
 */

$news = [
  [
    'title' => 'Cataratas del Iguazú lideran búsquedas en plataformas online',
    'tag'   => 'Tendencia',
    'color' => '#3b82f6',
    'img'   => '1.jpg',
  ],
  [
    'title' => 'Jardín América lanza programa "2x1" para la temporada',
    'tag'   => 'Oferta',
    'color' => '#22c55e',
    'img'   => '2.jpg',
  ],
  [
    'title' => 'Inmersión de Selva: edición especial en Salto Encantado',
    'tag'   => 'Evento',
    'color' => '#a855f7',
    'img'   => '3.jpg',
  ],
];
?>

<section id="noticias" aria-label="<?php esc_attr_e( 'Noticias de turismo', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
      </span>
      <h2 class="section-title__heading">Noticias</h2>
    </div>
    <a href="#" class="section-title__link">
      Ver todo
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="news-list">
    <?php foreach ( $news as $item ) : ?>
      <a href="#" class="news-card">

        <div class="news-card__img" style="--news-color:<?php echo esc_attr( $item['color'] ); ?>;">
          <?php if ( ! empty( $item['img'] ) ) : ?>
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $item['img'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
          <?php endif; ?>
          <span class="news-card__tag" style="background:<?php echo esc_attr( $item['color'] ); ?>;">
            <?php echo esc_html( $item['tag'] ); ?>
          </span>
        </div>

        <div class="news-card__body">
          <h3 class="news-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
          <span class="news-card__cta" aria-hidden="true">
            Leer más
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
          </span>
        </div>

      </a>
    <?php endforeach; ?>
  </div>

  <a href="#" class="section-cta">
    Ver todo
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
  </a>

</section>
