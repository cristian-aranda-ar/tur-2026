<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Overlay oscuro (toque fuera cierra el menu) -->
<div id="mobile-menu-overlay" aria-hidden="true"></div>

<!-- Mobile menu (slide desde izquierda) -->
<div id="mobile-menu" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu principal', 'turismo-2026' ); ?>">
    <?php wp_nav_menu( [
        'theme_location' => 'primary',
        'container'      => false,
        'fallback_cb'    => false,
    ] ); ?>
</div>

<!-- Search panel -->
<div id="search-panel" role="search">
    <?php get_search_form(); ?>
    <button id="search-close" class="header-icon-btn" aria-label="<?php esc_attr_e( 'Cerrar buscador', 'turismo-2026' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<header id="site-header">
    <div class="header-inner max-w-[1200px] mx-auto">

        <!-- Hamburger / X (solo mobile) -->
        <button id="menu-toggle" class="header-icon-btn md:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e( 'Abrir menu', 'turismo-2026' ); ?>">
            <!-- Hamburger -->
            <svg class="icon-open" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <!-- X -->
            <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Logo -->
        <div class="site-logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
                <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/tu-misiones.svg' ); ?>"
                     alt="<?php bloginfo( 'name' ); ?>"
                     class="h-10 md:h-12 w-auto"
                     width="160" height="48">
            </a>
        </div>

        <!-- Nav principal (solo desktop) -->
        <nav id="primary-nav" class="hidden md:block" aria-label="<?php esc_attr_e( 'Menu principal', 'turismo-2026' ); ?>">
            <?php wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
            ] ); ?>
        </nav>

        <!-- Search toggle -->
        <button id="search-toggle" class="header-icon-btn" aria-expanded="false" aria-controls="search-panel" aria-label="<?php esc_attr_e( 'Buscar', 'turismo-2026' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </button>

    </div>
</header>
