<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ════════════════════════════════════════════
     SITE HEADER
════════════════════════════════════════════ -->
<header class="site-header <?php echo is_front_page() ? 'site-header--transparent' : 'site-header--solid'; ?>" role="banner">
  <div class="site-header__inner">

  <!-- Logo -->
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" aria-label="<?php bloginfo( 'name' ); ?>">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mnes.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-header__logo-img">
  </a>

  <!-- Desktop nav (centred) -->
  <nav class="site-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Menú principal', 'misiones-2027' ); ?>">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="is-active"' : ''; ?>>Inicio</a>
    <a href="#">Explorar</a>
    <a href="#">Destinos</a>
    <a href="#">Planificá</a>
    <a href="#">Eventos</a>
    <a href="#">Institucional</a>
  </nav>

  <!-- Actions -->
  <div class="site-header__actions">
    <a href="#" class="icon-btn btn-ingresar" aria-label="<?php esc_attr_e( 'Ingresar', 'misiones-2027' ); ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
      </svg>
      <span class="btn-ingresar__label">Ingresar</span>
    </a>
    <div class="lang-dropdown">
      <button class="icon-btn btn-lang" aria-label="<?php esc_attr_e( 'Cambiar idioma', 'misiones-2027' ); ?>" aria-expanded="false">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20M2 12h20"/>
        </svg>
      </button>
      <div class="lang-dropdown__menu is-hidden" role="listbox" aria-label="<?php esc_attr_e( 'Idioma', 'misiones-2027' ); ?>">
        <?php
        $langs = [
          [ 'code' => 'ES', 'flag' => '🇦🇷', 'name' => 'Español',   'active' => true ],
          [ 'code' => 'EN', 'flag' => '🇺🇸', 'name' => 'English',   'active' => false ],
          [ 'code' => 'PT', 'flag' => '🇧🇷', 'name' => 'Português', 'active' => false ],
          [ 'code' => 'FR', 'flag' => '🇫🇷', 'name' => 'Français',  'active' => false ],
          [ 'code' => 'DE', 'flag' => '🇩🇪', 'name' => 'Deutsch',   'active' => false ],
          [ 'code' => 'IT', 'flag' => '🇮🇹', 'name' => 'Italiano',  'active' => false ],
          [ 'code' => 'ZH', 'flag' => '🇨🇳', 'name' => '中文',       'active' => false ],
        ];
        foreach ( $langs as $lang ) : ?>
          <a href="#" class="lang-dropdown__item <?php echo $lang['active'] ? 'is-active' : ''; ?>" role="option">
            <span class="lang-dropdown__flag" aria-hidden="true"><?php echo esc_html( $lang['flag'] ); ?></span>
            <span class="lang-dropdown__name"><?php echo esc_html( $lang['name'] ); ?></span>
            <?php if ( $lang['active'] ) : ?>
              <svg class="lang-dropdown__check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <button class="icon-btn btn-menu" aria-label="<?php esc_attr_e( 'Abrir menú', 'misiones-2027' ); ?>" aria-expanded="false">
      <!-- Hamburger icon -->
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M3 12h18M3 6h18M3 18h18"/>
      </svg>
    </button>
  </div>

  </div>
</header>
<!-- /SITE HEADER -->

<!-- ════════════════════════════════════════════
     MENU OVERLAY
════════════════════════════════════════════ -->
<div class="menu-overlay is-hidden" role="dialog" aria-label="<?php esc_attr_e( 'Menú de navegación', 'misiones-2027' ); ?>" aria-modal="true">
  <div class="menu-overlay__top">
    <span class="menu-overlay__brand">MISIONES</span>
    <button class="icon-btn btn-menu-close" aria-label="<?php esc_attr_e( 'Cerrar menú', 'misiones-2027' ); ?>">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>
  <div class="menu-overlay__content">

    <div class="menu-section">
      <span class="menu-section__label">Destinos Imperdibles</span>
      <?php foreach ( [ 'Iguazú', 'Moconá', 'Reducciones', 'Salto Encantado', 'De la Cruz', 'Posadas' ] as $item ) : ?>
        <a href="#" class="menu-section__item">
          <?php echo esc_html( $item ); ?>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="menu-section">
      <span class="menu-section__label">Experiencias</span>
      <?php foreach ( [ 'Aventura', 'Naturaleza', 'Cultura', 'Gastronomía', 'Aves', 'Rural' ] as $item ) : ?>
        <a href="#" class="menu-section__item">
          <?php echo esc_html( $item ); ?>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="menu-section">
      <span class="menu-section__label">Planificá tu viaje</span>
      <?php foreach ( [ 'Cómo llegar', 'Alojamiento', 'Transporte', 'Entradas', 'FAQ' ] as $item ) : ?>
        <a href="#" class="menu-section__item">
          <?php echo esc_html( $item ); ?>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="menu-section">
      <span class="menu-section__label">Institucional</span>
      <?php foreach ( [ 'Autoridades', 'Normativa', 'Estadísticas', 'Plan Estratégico' ] as $item ) : ?>
        <a href="#" class="menu-section__item">
          <?php echo esc_html( $item ); ?>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
</div>
<!-- /MENU OVERLAY -->

<!-- ════════════════════════════════════════════
     LANGUAGE PANEL
════════════════════════════════════════════ -->
<div class="lang-panel is-hidden" role="dialog" aria-label="<?php esc_attr_e( 'Selector de idioma', 'misiones-2027' ); ?>" aria-modal="true">
  <div class="lang-panel__header">
    <h2 class="lang-panel__title">Idioma / Language</h2>
    <button class="icon-btn icon-btn--plain btn-lang-close" aria-label="<?php esc_attr_e( 'Cerrar', 'misiones-2027' ); ?>" style="color:var(--c-gray-400);">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>
  <div class="lang-panel__grid">
    <?php
    $langs = [
      [ 'code' => 'ES', 'name' => 'Español',    'flag' => '🇦🇷', 'active' => true ],
      [ 'code' => 'EN', 'name' => 'English',     'flag' => '🇺🇸', 'active' => false ],
      [ 'code' => 'PT', 'name' => 'Português',   'flag' => '🇧🇷', 'active' => false ],
      [ 'code' => 'FR', 'name' => 'Français',    'flag' => '🇫🇷', 'active' => false ],
      [ 'code' => 'DE', 'name' => 'Deutsch',     'flag' => '🇩🇪', 'active' => false ],
      [ 'code' => 'IT', 'name' => 'Italiano',    'flag' => '🇮🇹', 'active' => false ],
      [ 'code' => 'ZH', 'name' => '中文',         'flag' => '🇨🇳', 'active' => false ],
    ];
    foreach ( $langs as $lang ) : ?>
      <a href="#" class="lang-item <?php echo $lang['active'] ? 'is-active' : ''; ?>">
        <span class="lang-item__flag" aria-hidden="true"><?php echo esc_html( $lang['flag'] ); ?></span>
        <div>
          <div class="lang-item__name"><?php echo esc_html( $lang['name'] ); ?></div>
          <div class="lang-item__code"><?php echo esc_html( $lang['code'] ); ?></div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <p class="lang-panel__note">Traducción profesional · No se usa Google Translate</p>
</div>
<!-- /LANGUAGE PANEL -->

<!-- ════════════════════════════════════════════
     LOGIN MODAL
════════════════════════════════════════════ -->
<div class="login-modal is-hidden" role="dialog" aria-label="<?php esc_attr_e( 'Ingresar', 'misiones-2027' ); ?>" aria-modal="true">
  <div class="login-modal__card">

    <button class="login-modal__close icon-btn icon-btn--plain" aria-label="<?php esc_attr_e( 'Cerrar', 'misiones-2027' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>

    <div class="login-modal__brand">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/misiones-tur.svg' ); ?>" alt="Misiones Turismo" class="login-modal__logo">
    </div>

<p class="login-modal__subtitle">Guardá favoritos, planificá tu viaje, compartí experiencias, enterate y accedé a ofertas exclusivas.</p>

    <a href="#" class="login-modal__google-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" fill="none">
        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
      </svg>
      Continuar con Google
    </a>

    <p class="login-modal__terms">Al ingresar aceptás nuestros <a href="#">Términos</a> y <a href="#">Política de privacidad</a>.</p>

  </div>
</div>
<!-- /LOGIN MODAL -->

<!-- Backdrop -->
<div class="overlay-backdrop is-hidden" aria-hidden="true"></div>
