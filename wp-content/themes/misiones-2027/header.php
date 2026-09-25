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
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mnes-blanco.webp' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-header__logo-img">
  </a>

  <!-- Desktop nav (centred) -->
  <nav class="site-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Menú principal', 'misiones-2027' ); ?>">
    <?php wp_nav_menu( [
      'theme_location' => 'header-2026',
      'container'      => false,
      'menu_class'     => '',
      'fallback_cb'    => false,
      'depth'          => 2,
    ] ); ?>
  </nav>

  <!-- Actions -->
  <div class="site-header__actions">
    <?php if ( is_user_logged_in() ) :
      $__user       = wp_get_current_user();
      $__name_short = explode( ' ', $__user->display_name )[0];
      $__initial    = mb_strtoupper( mb_substr( $__user->display_name, 0, 1 ) );
      $__avatar     = get_user_meta( $__user->ID, 'turs_avatar', true );
    ?>
      <button type="button" class="icon-btn btn-ingresar btn-ingresar--logged js-user-panel-open"
              aria-label="<?php esc_attr_e( 'Mi perfil', 'misiones-2027' ); ?>"
              aria-expanded="false">
        <span class="btn-ingresar__avatar" aria-hidden="true">
          <?php if ( $__avatar ) : ?>
            <img src="<?php echo esc_url( $__avatar ); ?>" alt="">
          <?php else : ?>
            <?php echo esc_html( $__initial ); ?>
          <?php endif; ?>
        </span>
        <span class="btn-ingresar__label"><?php echo esc_html( $__name_short ); ?></span>
      </button>
    <?php else : ?>
      <a href="#" class="icon-btn btn-ingresar" aria-label="<?php esc_attr_e( 'Ingresar', 'misiones-2027' ); ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
        </svg>
        <span class="btn-ingresar__label">Ingresar</span>
      </a>
    <?php endif; ?>
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
    <?php misiones2027_render_mobile_menu( 'header-2026' ); ?>
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
     USER PANEL (sidebar izquierdo — solo logueados)
════════════════════════════════════════════ -->
<?php if ( is_user_logged_in() ) :
  $__user    = wp_get_current_user();
  $__avatar  = get_user_meta( $__user->ID, 'turs_avatar', true );
  $__initial = mb_strtoupper( mb_substr( $__user->display_name, 0, 1 ) );
  $__logout  = function_exists( 'turs_logout_url' ) ? turs_logout_url() : wp_logout_url( home_url() );

  // Rutas de viaje (posts que le dieron like)
  global $wpdb;
  $__likes = $wpdb->get_results( $wpdb->prepare(
      "SELECT post_id FROM {$wpdb->prefix}tur_likes WHERE user_id = %d ORDER BY created_at DESC LIMIT 5",
      $__user->ID
  ) );

  // Notificaciones: reseñas con respuesta del admin no vista
  $__notifs = $wpdb->get_results( $wpdb->prepare(
      "SELECT r.id, r.post_id, r.admin_reply, r.admin_reply_at, p.post_title
       FROM {$wpdb->prefix}tur_reviews r
       JOIN {$wpdb->posts} p ON p.ID = r.post_id
       WHERE r.user_id = %d AND r.status = 'approved' AND r.admin_reply IS NOT NULL
       ORDER BY r.admin_reply_at DESC LIMIT 5",
      $__user->ID
  ) );
?>
<div class="user-panel" id="user-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mi perfil', 'misiones-2027' ); ?>">

  <!-- Encabezado del panel -->
  <div class="user-panel__header">
    <div class="user-panel__identity">
      <div class="user-panel__avatar">
        <?php if ( $__avatar ) : ?>
          <img src="<?php echo esc_url( $__avatar ); ?>" alt="<?php echo esc_attr( $__user->display_name ); ?>">
        <?php else : ?>
          <span><?php echo esc_html( $__initial ); ?></span>
        <?php endif; ?>
      </div>
      <div class="user-panel__info">
        <strong class="user-panel__name"><?php echo esc_html( $__user->display_name ); ?></strong>
        <a href="<?php echo esc_url( $__logout ); ?>" class="user-panel__logout">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
          Cerrar sesión
        </a>
      </div>
    </div>
    <button type="button" class="user-panel__close js-user-panel-close" aria-label="<?php esc_attr_e( 'Cerrar', 'misiones-2027' ); ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
  </div>

  <!-- Ruta de viaje -->
  <div class="user-panel__section">
    <h3 class="user-panel__section-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      Ruta de viaje
    </h3>
    <div class="user-panel__empty" id="panel-likes-empty"<?php if ( ! empty( $__likes ) ) echo ' style="display:none"'; ?>>
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      <p>Todavía no guardaste ningún destino.</p>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="user-panel__empty-cta">Explorar destinos</a>
    </div>
    <ul class="user-panel__list" id="panel-likes-list"<?php if ( empty( $__likes ) ) echo ' style="display:none"'; ?>>
      <?php foreach ( $__likes as $like ) :
        $lp    = get_post( $like->post_id );
        if ( ! $lp ) continue;
        $lthumb = get_the_post_thumbnail_url( $lp->ID, 'thumbnail' );
        $lcolor = get_post_meta( $lp->ID, 'color', true ) ?: '#1a5c4c';
      ?>
        <li class="user-panel__list-item" data-post-id="<?php echo esc_attr( $lp->ID ); ?>">
          <a href="<?php echo esc_url( get_permalink( $lp->ID ) ); ?>" class="user-panel__list-link">
            <span class="user-panel__list-thumb" style="background:<?php echo esc_attr( $lcolor ); ?>;">
              <?php if ( $lthumb ) : ?>
                <img src="<?php echo esc_url( $lthumb ); ?>" alt="">
              <?php endif; ?>
            </span>
            <span class="user-panel__list-title"><?php echo esc_html( $lp->post_title ); ?></span>
          </a>
          <button type="button" class="user-panel__list-del js-panel-unlike" data-post-id="<?php echo esc_attr( $lp->ID ); ?>" aria-label="<?php esc_attr_e( 'Eliminar de favoritos', 'misiones-2027' ); ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>
    <button type="button" class="user-panel__clear-likes js-panel-clear-likes"<?php if ( empty( $__likes ) ) echo ' style="display:none"'; ?>>
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
      Vaciar ruta de viaje
    </button>
  </div>

  <!-- Notificaciones -->
  <div class="user-panel__section">
    <h3 class="user-panel__section-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      Notificaciones
      <?php if ( ! empty( $__notifs ) ) : ?>
        <span class="user-panel__badge"><?php echo count( $__notifs ); ?></span>
      <?php endif; ?>
    </h3>
    <?php if ( empty( $__notifs ) ) : ?>
      <div class="user-panel__empty">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <p>No tenés notificaciones nuevas.</p>
      </div>
    <?php else : ?>
      <ul class="user-panel__list">
        <?php foreach ( $__notifs as $notif ) : ?>
          <li>
            <a href="<?php echo esc_url( get_permalink( $notif->post_id ) ); ?>" class="user-panel__list-item user-panel__list-item--notif">
              <span class="user-panel__notif-dot"></span>
              <span class="user-panel__list-text">
                <span class="user-panel__list-title"><?php echo esc_html( $notif->post_title ); ?></span>
                <span class="user-panel__list-sub">El sitio respondió tu reseña</span>
              </span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

</div>
<?php endif; ?>
<!-- /USER PANEL -->

<!-- ════════════════════════════════════════════
     LOGIN MODAL
════════════════════════════════════════════ -->
<?php
$_turs_return     = home_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) ) );
$_turs_google_url = function_exists( 'turs_google_auth_url' ) ? turs_google_auth_url( $_turs_return ) : '#';
$_turs_meta_url   = function_exists( 'turs_meta_auth_url' )   ? turs_meta_auth_url( $_turs_return )   : '#';
?>
<div class="login-modal is-hidden" role="dialog" aria-label="<?php esc_attr_e( 'Ingresar', 'misiones-2027' ); ?>" aria-modal="true">
  <div class="login-modal__card">

    <button class="login-modal__close icon-btn icon-btn--plain" aria-label="<?php esc_attr_e( 'Cerrar', 'misiones-2027' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>

    <div class="login-modal__brand">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/tur-mis.webp' ); ?>" alt="Misiones Turismo" class="login-modal__logo">
    </div>

    <p class="login-modal__subtitle">Guardá favoritos, planificá tu viaje, compartí experiencias, enterate y accedé a ofertas exclusivas.</p>

    <a href="<?php echo esc_url( $_turs_google_url ); ?>" class="login-modal__google-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" fill="none">
        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
      </svg>
      Continuar con Google
    </a>

    <a href="<?php echo esc_url( $_turs_meta_url ); ?>" class="login-modal__meta-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff" aria-hidden="true">
        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
      </svg>
      Continuar con Meta
    </a>

    <p class="login-modal__terms">Al ingresar aceptás nuestros <a href="#">Términos</a> y <a href="#">Política de privacidad</a>.</p>

  </div>
</div>
<!-- /LOGIN MODAL -->

<!-- Backdrop -->
<div class="overlay-backdrop is-hidden" aria-hidden="true"></div>
