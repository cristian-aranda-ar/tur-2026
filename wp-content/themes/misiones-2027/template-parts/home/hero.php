<?php
/**
 * Template Part: Hero Slider
 * Datos desde CPT misiones_slide (ordenados por menu_order).
 * Fallback a datos estáticos si no hay slides publicados.
 */

$slides = misiones2027_get_hero_slides();
?>

<section id="inicio" class="hero" aria-label="<?php esc_attr_e( 'Destacados de Misiones', 'misiones-2027' ); ?>">

  <!-- ── Video Hero ── -->
  <div class="hero__video-bg" aria-hidden="true">
    <iframe
      src="https://www.youtube-nocookie.com/embed/cxaJywr0JX8?autoplay=1&mute=1&loop=1&playlist=cxaJywr0JX8&controls=0&rel=0&modestbranding=1&playsinline=1&disablekb=1"
      title="Video promocional Misiones"
      frameborder="0"
      allow="autoplay; encrypted-media"
      allowfullscreen
      loading="lazy"
    ></iframe>
  </div>

  <div class="hero__overlay"></div>

  <?php /*
  ── SLIDER COMENTADO ──────────────────────────────────────

  <?php foreach ( $slides as $index => $slide ) : ?>

    <div
      class="hero__slide <?php echo esc_attr( $slide['css_class'] ); ?>"
      aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>"
    >
      <?php if ( ! empty( $slide['video_url'] ) ) : ?>
        <video
          class="hero__video"
          src="<?php echo esc_url( $slide['video_url'] ); ?>"
          <?php if ( ! empty( $slide['image_url'] ) ) : ?>poster="<?php echo esc_url( $slide['image_url'] ); ?>"<?php endif; ?>
          autoplay muted loop playsinline
          aria-hidden="true"
        ></video>
      <?php elseif ( ! empty( $slide['image_url'] ) ) : ?>
        <div class="hero__slide-img" style="background-image:url('<?php echo esc_url( $slide['image_url'] ); ?>');" role="img" aria-label="<?php echo esc_attr( $slide['title'] ); ?>"></div>
      <?php endif; ?>

      <div class="hero__overlay"></div>
    </div>

  <?php endforeach; ?>

  <div class="hero__content">
    <?php foreach ( $slides as $index => $slide ) : ?>
      <div
        class="hero__content-item"
        data-slide="<?php echo esc_attr( $index ); ?>"
        <?php echo $index !== 0 ? 'style="display:none;"' : ''; ?>
      >
        <?php if ( ! empty( $slide['volanta'] ) ) : ?>
          <span class="hero__badge"><?php echo esc_html( $slide['volanta'] ); ?></span>
        <?php endif; ?>

        <h1 class="hero__title"><?php echo esc_html( $slide['title'] ); ?></h1>

        <?php if ( ! empty( $slide['bajada'] ) ) : ?>
          <p class="hero__subtitle"><?php echo esc_html( $slide['bajada'] ); ?></p>
        <?php endif; ?>

        <div class="hero__cta">
          <a href="<?php echo esc_url( $slide['btn1_link'] ); ?>" class="btn btn--accent">
            <?php echo esc_html( $slide['btn1_text'] ); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>

          <?php if ( ! empty( $slide['btn2_text'] ) ) : ?>
            <a href="<?php echo esc_url( $slide['btn2_link'] ); ?>" class="btn btn--glass">
              <?php echo esc_html( $slide['btn2_text'] ); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ( count( $slides ) > 1 ) : ?>
    <div class="hero__indicators" role="tablist" aria-label="<?php esc_attr_e( 'Slides', 'misiones-2027' ); ?>">
      <?php foreach ( $slides as $index => $slide ) : ?>
        <button
          class="hero__dot <?php echo $index === 0 ? 'is-active' : ''; ?>"
          data-index="<?php echo esc_attr( $index ); ?>"
          role="tab"
          aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
          aria-label="<?php echo esc_attr( sprintf( 'Slide %d: %s', $index + 1, $slide['title'] ) ); ?>"
        ></button>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  ── FIN SLIDER ─────────────────────────────────────────── */ ?>

</section>
