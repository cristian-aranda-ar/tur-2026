<?php
/**
 * Template Part: Destinos Imperdibles Carousel
 */

$destinos = [
  [ 'name' => 'Cataratas del Iguazú',    'tag' => 'UNESCO',       'rating' => '4.9', 'reviews' => '12.4K', 'color' => '#1a5c4c', 'accent' => '#f5a623', 'image' => 'iguazu.webp'     ],
  [ 'name' => 'Saltos del Moconá',       'tag' => 'Naturaleza',   'rating' => '4.8', 'reviews' => '3.2K',  'color' => '#0e4a3c', 'accent' => '#22c55e', 'image' => 'mocona.JPG'      ],
  [ 'name' => 'Reducciones Jesuíticas',  'tag' => 'Historia',     'rating' => '4.7', 'reviews' => '5.1K',  'color' => '#3a2a1a', 'accent' => '#f5a623', 'image' => 'sanignacio.jpg'  ],
  [ 'name' => 'Salto Encantado',         'tag' => 'Aventura',     'rating' => '4.8', 'reviews' => '2.8K',  'color' => '#1a4a3c', 'accent' => '#06b6d4', 'image' => 'encantado.jpg'   ],
  [ 'name' => 'Parque De la Cruz',       'tag' => 'Panorama',     'rating' => '4.6', 'reviews' => '1.9K',  'color' => '#2a3a1a', 'accent' => '#a855f7', 'image' => 'cruz.JPG'        ],
  [ 'name' => 'Posadas',                 'tag' => 'Capital',      'rating' => '4.5', 'reviews' => '4.7K',  'color' => '#1a2a4a', 'accent' => '#ec4899', 'image' => 'posadas.png'     ],
];
?>

<section id="destinos" aria-label="<?php esc_attr_e( 'Destinos Imperdibles', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      </span>
      <h2 class="section-title__heading">Destinos Imperdibles</h2>
    </div>
    <a href="#" class="section-title__link">
      Ver todo
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="destinos-carousel" role="list">
    <?php foreach ( $destinos as $destino ) : ?>
      <article class="destino-card" role="listitem" style="background:linear-gradient(180deg, <?php echo esc_attr( $destino['color'] ); ?> 0%, <?php echo esc_attr( $destino['color'] ); ?>ee 100%);">

        <!-- Background image -->
        <img
          class="destino-card__img"
          src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $destino['image'] ); ?>"
          alt="<?php echo esc_attr( $destino['name'] ); ?>"
          loading="lazy"
        >

        <!-- Gradient overlay -->
        <div class="destino-card__overlay"></div>

        <!-- Category tag -->
        <div class="destino-card__tag" style="background:<?php echo esc_attr( $destino['accent'] ); ?>;">
          <span><?php echo esc_html( $destino['tag'] ); ?></span>
        </div>

        <!-- Favourite button -->
        <button class="destino-card__fav" aria-label="<?php echo esc_attr( sprintf( __( 'Guardar %s en favoritos', 'misiones-2027' ), $destino['name'] ) ); ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" aria-hidden="true">
            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
          </svg>
        </button>

        <!-- Info -->
        <div class="destino-card__info">
          <h3 class="destino-card__name"><?php echo esc_html( $destino['name'] ); ?></h3>
          <div class="destino-card__rating">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="<?php echo esc_attr( $destino['accent'] ); ?>" stroke="<?php echo esc_attr( $destino['accent'] ); ?>" stroke-width="2" aria-hidden="true">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <span class="destino-card__rating-val"><?php echo esc_html( $destino['rating'] ); ?></span>
            <span class="destino-card__rating-count">(<?php echo esc_html( $destino['reviews'] ); ?>)</span>
          </div>
        </div>

      </article>
    <?php endforeach; ?>
  </div>

  <a href="#" class="section-cta">
    Ver todo
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
  </a>

</section>
