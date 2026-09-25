<?php
/**
 * Template Part: Hero Slider
 */

$hero_video_url = function_exists( 'tc_home_get' ) ? ( tc_home_get()['hero_video_url'] ?? '' ) : '';
?>

<section id="inicio" class="hero" aria-label="<?php esc_attr_e( 'Destacados de Misiones', 'misiones-2027' ); ?>">

  <!-- ── Video Hero ── -->
  <div class="hero__video-bg" aria-hidden="true">
    <?php if ( $hero_video_url ) : ?>
      <video autoplay muted loop playsinline>
        <source src="<?php echo esc_url( $hero_video_url ); ?>" type="video/mp4">
      </video>
    <?php else : ?>
      <iframe
        src="https://www.youtube-nocookie.com/embed/cxaJywr0JX8?autoplay=1&mute=1&loop=1&playlist=cxaJywr0JX8&controls=0&rel=0&modestbranding=1&playsinline=1&disablekb=1"
        title="Video promocional Misiones"
        frameborder="0"
        allow="autoplay; encrypted-media"
        allowfullscreen
        loading="lazy"
      ></iframe>
    <?php endif; ?>
  </div>

  <div class="hero__overlay"></div>

</section>
