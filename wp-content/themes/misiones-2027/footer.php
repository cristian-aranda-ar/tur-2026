<!-- ════════════════════════════════════════════
     CHAT FAB + PANEL
════════════════════════════════════════════ -->
<?php get_template_part( 'template-parts/global/chat-fab' ); ?>

<!-- ════════════════════════════════════════════
     SITE FOOTER
════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">
  <div class="site-footer__inner">

    <!-- Main grid -->
    <div class="site-footer__main">

      <!-- Brand + Redes -->
      <div class="site-footer__brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo" aria-label="<?php bloginfo( 'name' ); ?>">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mnes-blanco.webp' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-footer__logo-img">
        </a>
        <p class="site-footer__tagline">Ministerio de Turismo de la Provincia de Misiones</p>
        <div class="site-footer__social" aria-label="<?php esc_attr_e( 'Redes sociales', 'misiones-2027' ); ?>">
          <?php echo misiones2027_render_social_links(); ?>
        </div>
      </div>

      <!-- Columna 1 — menú footer-col-1 -->
      <?php
      $col1_items = wp_get_nav_menu_items( get_nav_menu_locations()['footer-col-1'] ?? 0 );
      if ( $col1_items ) : ?>
      <div class="site-footer__col">
        <h4 class="site-footer__col-title">Destinos</h4>
        <ul class="site-footer__col-list">
          <?php foreach ( $col1_items as $item ) : ?>
            <li><a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <!-- Columna 2 — menú footer-col-2 -->
      <?php
      $col2_items = wp_get_nav_menu_items( get_nav_menu_locations()['footer-col-2'] ?? 0 );
      if ( $col2_items ) : ?>
      <div class="site-footer__col">
        <h4 class="site-footer__col-title">Planificá tu viaje</h4>
        <ul class="site-footer__col-list">
          <?php foreach ( $col2_items as $item ) : ?>
            <li><a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <!-- Contacto -->
      <div class="site-footer__col site-footer__col--contact">
        <h4 class="site-footer__col-title">Contacto</h4>
        <address class="site-footer__address">
          <?php echo misiones2027_render_contact_items(); ?>
        </address>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="site-footer__bar">
      <p class="site-footer__copy">© <?php echo esc_html( date( 'Y' ) ); ?> Todos los derechos reservados.</p>
      <nav class="site-footer__legal" aria-label="<?php esc_attr_e( 'Links legales', 'misiones-2027' ); ?>">
        <?php
        $legal_items = wp_get_nav_menu_items( get_nav_menu_locations()['footer-legal'] ?? 0 );
        if ( $legal_items ) :
            $links = [];
            foreach ( $legal_items as $item ) {
                $links[] = '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
            }
            echo implode( '<span aria-hidden="true">·</span>', $links );
        endif;
        ?>
      </nav>
    </div>

  </div>
</footer>
<!-- /SITE FOOTER -->


<?php wp_footer(); ?>
</body>
</html>
