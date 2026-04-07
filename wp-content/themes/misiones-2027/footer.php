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

      <!-- Brand -->
      <div class="site-footer__brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo" aria-label="<?php bloginfo( 'name' ); ?>">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mnes.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-footer__logo-img">
        </a>
        <p class="site-footer__tagline">Ministerio de Turismo de la Provincia de Misiones</p>
        <div class="site-footer__social" aria-label="<?php esc_attr_e( 'Redes sociales', 'misiones-2027' ); ?>">
          <a href="#" class="social-btn" aria-label="Instagram" rel="noopener noreferrer" target="_blank">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/></svg>
          </a>
          <a href="#" class="social-btn" aria-label="Facebook" rel="noopener noreferrer" target="_blank">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="social-btn" aria-label="YouTube" rel="noopener noreferrer" target="_blank">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22.54 6.42A2.78 2.78 0 0 0 20.59 4.44C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.41 19.3C5.12 19.75 12 19.75 12 19.75s6.88 0 8.59-.45a2.78 2.78 0 0 0 1.95-1.98A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
          </a>
          <a href="#" class="social-btn" aria-label="TikTok" rel="noopener noreferrer" target="_blank">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
          </a>
        </div>
      </div>

      <!-- Destinos -->
      <div class="site-footer__col">
        <h4 class="site-footer__col-title">Destinos</h4>
        <ul class="site-footer__col-list">
          <?php foreach ( [ 'Cataratas del Iguazú', 'Moconá', 'San Ignacio Mini', 'Posadas' ] as $d ) : ?>
            <li><a href="#"><?php echo esc_html( $d ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Planificá -->
      <div class="site-footer__col">
        <h4 class="site-footer__col-title">Planificá tu viaje</h4>
        <ul class="site-footer__col-list">
          <?php foreach ( [ 'Cómo llegar', 'Alojamiento', 'Entradas', 'Transporte' ] as $p ) : ?>
            <li><a href="#"><?php echo esc_html( $p ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Contacto -->
      <div class="site-footer__col site-footer__col--contact">
        <h4 class="site-footer__col-title">Contacto</h4>
        <address class="site-footer__address">
          <a href="https://maps.app.goo.gl/EEag9XtacRxQvpys7" rel="noopener noreferrer" target="_blank">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            Colón 1985, Posadas · Misiones
          </a>
          <p>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Lun–Sáb 7:30–20:00 · Dom 8:00–20:00
          </p>
          <a href="tel:+543764447539">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            (0376) 4447539
          </a>
          <a href="https://wa.me/5493764138114" rel="noopener noreferrer" target="_blank">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
            +54 9 3764 13-8114
          </a>
          <a href="mailto:turismo@misiones.gov.ar">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            turismo@misiones.gov.ar
          </a>
        </address>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="site-footer__bar">
      <p class="site-footer__copy">© 2026 Todos los derechos reservados.</p>
      <nav class="site-footer__legal" aria-label="<?php esc_attr_e( 'Links legales', 'misiones-2027' ); ?>">
        <a href="#">Institucional</a>
        <span aria-hidden="true">·</span>
        <a href="#">Accesibilidad</a>
        <span aria-hidden="true">·</span>
        <a href="#">Privacidad</a>
        <span aria-hidden="true">·</span>
        <a href="#">Contacto</a>
        <span aria-hidden="true">·</span>
        <a href="#">FAQ</a>
      </nav>
    </div>

  </div>
</footer>
<!-- /SITE FOOTER -->

<!-- ════════════════════════════════════════════
     BOTTOM NAVIGATION (mobile)
════════════════════════════════════════════ -->
<nav class="bottom-nav" role="navigation" aria-label="<?php esc_attr_e( 'Navegación principal', 'misiones-2027' ); ?>">

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bottom-nav__item <?php echo is_front_page() ? 'is-active' : ''; ?>">
    <span class="bottom-nav__indicator" aria-hidden="true"></span>
    <svg class="bottom-nav__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="<?php echo is_front_page() ? 'var(--c-primary)' : 'var(--c-gray-400)'; ?>" stroke-width="2" aria-hidden="true">
      <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    <span class="bottom-nav__label" style="color:<?php echo is_front_page() ? 'var(--c-primary)' : 'var(--c-gray-400)'; ?>">Inicio</span>
  </a>

  <a href="#explorar" class="bottom-nav__item">
    <span class="bottom-nav__indicator" aria-hidden="true"></span>
    <svg class="bottom-nav__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--c-gray-400)" stroke-width="2" aria-hidden="true">
      <circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
    </svg>
    <span class="bottom-nav__label">Explorar</span>
  </a>

  <a href="#eventos" class="bottom-nav__item">
    <span class="bottom-nav__indicator" aria-hidden="true"></span>
    <svg class="bottom-nav__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--c-gray-400)" stroke-width="2" aria-hidden="true">
      <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
    </svg>
    <span class="bottom-nav__label">Eventos</span>
  </a>

  <a href="#planificar" class="bottom-nav__item">
    <span class="bottom-nav__indicator" aria-hidden="true"></span>
    <svg class="bottom-nav__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--c-gray-400)" stroke-width="2" aria-hidden="true">
      <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
    </svg>
    <span class="bottom-nav__label">Planificar</span>
  </a>

</nav>
<!-- /BOTTOM NAVIGATION -->

<?php wp_footer(); ?>
</body>
</html>
