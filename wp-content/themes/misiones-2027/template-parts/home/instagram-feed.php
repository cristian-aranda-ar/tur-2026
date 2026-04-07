<?php
/**
 * Template Part: Instagram Feed (#TuMisiones)
 */

$base = get_template_directory_uri() . '/assets/images/';
$posts = [
  [ 'img' => $base . '11.jpg', 'reel' => true  ],
  [ 'img' => $base . '22.jpg', 'reel' => false ],
  [ 'img' => $base . '33.jpg', 'reel' => false ],
  [ 'img' => $base . '44.jpg', 'reel' => false ],
  [ 'img' => $base . '55.jpg', 'reel' => false ],
  [ 'img' => $base . '66.jpg', 'reel' => false ],
];
?>

<section id="instagram" aria-label="<?php esc_attr_e( 'Feed de Instagram', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
      </span>
      <h2 class="section-title__heading">#TuMisiones</h2>
    </div>
    <a href="#" class="section-title__link" rel="noopener noreferrer" target="_blank">
      Ver todo
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="instagram-feed">

    <!-- Grid -->
    <div class="instagram-feed__grid" role="list" aria-label="<?php esc_attr_e( 'Fotos de Instagram', 'misiones-2027' ); ?>">
      <?php foreach ( $posts as $post ) : ?>
        <div class="instagram-feed__item" role="listitem">
          <img src="<?php echo esc_url( $post['img'] ); ?>" alt="<?php esc_attr_e( 'Foto de turismo en Misiones', 'misiones-2027' ); ?>" loading="lazy">
          <?php if ( $post['reel'] ) : ?>
            <span class="instagram-feed__reel" aria-label="Reel">REEL</span>
          <?php endif; ?>
          <div class="instagram-feed__item-overlay" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
              <rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/>
            </svg>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Follow CTA -->
    <div class="instagram-feed__cta">
      <video class="instagram-feed__cta-video" autoplay muted loop playsinline>
        <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/chango.mp4' ); ?>" type="video/mp4">
      </video>
      <div class="instagram-feed__cta-info">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" aria-hidden="true">
          <rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/>
        </svg>
        <div>
          <div class="instagram-feed__cta-handle">@misiones.turismo</div>
          <div class="instagram-feed__cta-followers">78.8K seguidores</div>
        </div>
      </div>
      <a href="#" class="instagram-feed__cta-btn" rel="noopener noreferrer" target="_blank">Ir a Instagram</a>
    </div>

  </div>

  <a href="#" class="section-cta" rel="noopener noreferrer" target="_blank">
    Ver en Instagram
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
  </a>

</section>
