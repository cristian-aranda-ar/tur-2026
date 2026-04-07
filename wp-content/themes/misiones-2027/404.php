<?php
/**
 * 404 Template
 */
get_header();
?>

<main class="page-404" id="main" role="main">
  <p class="page-404__code" aria-hidden="true">404</p>
  <h1 class="page-404__title"><?php esc_html_e( 'Página no encontrada', 'misiones-2027' ); ?></h1>
  <p class="page-404__sub"><?php esc_html_e( 'La página que buscás no existe o fue movida. Volvé al inicio para seguir explorando Misiones.', 'misiones-2027' ); ?></p>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
    <?php esc_html_e( 'Volver al inicio', 'misiones-2027' ); ?>
  </a>
</main>

<?php get_footer(); ?>
