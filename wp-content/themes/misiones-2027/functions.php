<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Aumentar límite de subida de archivos
add_filter( 'upload_size_limit', fn() => 64 * MB_IN_BYTES );
@ini_set( 'upload_max_filesize', '64M' );
@ini_set( 'post_max_size', '64M' );

// ─────────────────────────────────────────────
// POST TYPE: registro-unico en raíz
// ─────────────────────────────────────────────
add_action( 'init', function () {
    add_rewrite_rule(
        '^([^/]+)/?$',
        'index.php?registro-unico=$matches[1]',
        'top'
    );
}, 30 );

// Si el slug no existe como registro-unico, cederlo a páginas/posts nativos
add_filter( 'request', function ( $query_vars ) {
    if ( ! isset( $query_vars['registro-unico'] ) ) {
        return $query_vars;
    }

    global $wpdb;
    $slug = $query_vars['registro-unico'];

    $id = $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_name = %s AND post_type = 'registro-unico' AND post_status = 'publish'
         LIMIT 1",
        $slug
    ) );
    if ( $id ) {
        return $query_vars;
    }

    $page_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_name = %s AND post_type = 'page' AND post_status = 'publish'
         LIMIT 1",
        $slug
    ) );
    if ( $page_id ) {
        unset( $query_vars['registro-unico'], $query_vars['post_type'], $query_vars['name'] );
        $query_vars['page_id'] = $page_id;
        return $query_vars;
    }

    $post_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_name = %s AND post_type = 'post' AND post_status = 'publish'
         LIMIT 1",
        $slug
    ) );
    if ( $post_id ) {
        unset( $query_vars['registro-unico'], $query_vars['post_type'], $query_vars['name'] );
        $query_vars['p'] = $post_id;
    }

    return $query_vars;
} );

// ─────────────────────────────────────────────
// INCLUDES
// ─────────────────────────────────────────────
require_once get_template_directory() . '/inc/hero-slides.php';
require_once get_template_directory() . '/inc/customizer-social.php';
require_once get_template_directory() . '/inc/customizer-contact.php';
require_once get_template_directory() . '/inc/turismo-core-menu.php';
require_once get_template_directory() . '/inc/home-settings.php';
require_once get_template_directory() . '/inc/fields-quick-actions.php';
require_once get_template_directory() . '/inc/metabox-fechas-ru.php';
require_once get_template_directory() . '/inc/geocoder-ru.php';
require_once get_template_directory() . '/inc/prestaciones-normalizer.php';
require_once get_template_directory() . '/inc/mobile-menu.php';

// ─────────────────────────────────────────────
// THEME SETUP
// ─────────────────────────────────────────────
function misiones2027_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );

    register_nav_menus( [
        'primary'        => __( 'Menú Principal', 'misiones-2027' ),
        'header-2026'    => __( 'Menú Header 2026', 'misiones-2027' ),
        'footer'         => __( 'Menú Footer', 'misiones-2027' ),
        'footer-col-1'   => __( 'Footer — Columna 1 (Destinos)', 'misiones-2027' ),
        'footer-col-2'   => __( 'Footer — Columna 2 (Planificá tu viaje)', 'misiones-2027' ),
        'footer-legal'   => __( 'Footer — Legal', 'misiones-2027' ),
    ] );

    load_theme_textdomain( 'misiones-2027', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'misiones2027_setup' );

// ─────────────────────────────────────────────
// ENQUEUE STYLES & SCRIPTS
// ─────────────────────────────────────────────
function misiones2027_enqueue_assets() {
    // Google Fonts — DM Sans
    wp_enqueue_style(
        'misiones2027-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap',
        [],
        null
    );

    // Leaflet
    wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4' );
    wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true );

    // Main stylesheet
    $main_css_path = get_template_directory() . '/assets/css/main.css';
    $main_css_ver  = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'misiones2027-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [ 'misiones2027-fonts', 'leaflet' ],
        $main_css_ver
    );

    // Main JS
    wp_enqueue_script(
        'misiones2027-main',
        get_template_directory_uri() . '/assets/js/main.js?v=12',
        [ 'leaflet' ],
        wp_get_theme()->get( 'Version' ),
        true
    );

    // Pass theme URI to JS
    wp_localize_script( 'misiones2027-main', 'misiones2027', [
        'themeUrl' => get_template_directory_uri(),
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'misiones2027_enqueue_assets' );

// ─────────────────────────────────────────────
// BODY CLASSES
// ─────────────────────────────────────────────
function misiones2027_body_classes( $classes ) {
    if ( is_front_page() ) {
        $classes[] = 'is-front-page';
    }
    return $classes;
}
add_filter( 'body_class', 'misiones2027_body_classes' );

// ─────────────────────────────────────────────
// EXCERPT LENGTH
// ─────────────────────────────────────────────
function misiones2027_excerpt_length() {
    return 20;
}
add_filter( 'excerpt_length', 'misiones2027_excerpt_length' );

// ─────────────────────────────────────────────
// SEARCH: registro-unico — todos los campos
// ─────────────────────────────────────────────
add_filter( 'posts_search', 'misiones2027_ru_extend_search', 10, 2 );
function misiones2027_ru_extend_search( $search, $wp_query ) {
    global $wpdb;

    if ( is_admin() || ! $wp_query->is_search() || ! $wp_query->is_main_query() ) {
        return $search;
    }
    if ( $wp_query->get( 'post_type' ) !== 'registro-unico' ) {
        return $search;
    }
    $term = $wp_query->get( 's' );
    if ( ! $term || empty( $search ) ) {
        return $search;
    }

    $like = '%' . $wpdb->esc_like( $term ) . '%';
    $meta_keys = [ 'resumen', 'direccion', 'telefono', 'whatsapp', 'instagram', 'facebook', 'sitio_web' ];

    $meta_conditions = [];
    foreach ( $meta_keys as $key ) {
        $meta_conditions[] = $wpdb->prepare(
            "({$wpdb->posts}.ID IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value LIKE %s))",
            $key,
            $like
        );
    }

    // Append OR conditions before the final closing paren of the search clause
    $search = preg_replace( '/\)\)\s*$/', ' OR ' . implode( ' OR ', $meta_conditions ) . '))', $search );

    return $search;
}

// ─────────────────────────────────────────────
// QUICK ACTIONS: SVG icon helper
// ─────────────────────────────────────────────
// Permalink raíz para registro-unico
add_filter( 'post_type_link', function ( $url, $post ) {
    if ( $post->post_type !== 'registro-unico' ) return $url;
    return home_url( '/' . $post->post_name . '/' );
}, 10, 2 );

function misiones2027_youtube_id( $value ) {
    if ( empty( $value ) || ! is_string( $value ) ) return '';
    $value = trim( $value );
    if ( preg_match( '/^[a-zA-Z0-9_-]{11}$/', $value ) ) return $value;
    preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $value, $m );
    return $m[1] ?? '';
}

function misiones2027_hex_rgba( $hex, $alpha ) {
    $hex = ltrim( $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    list( $r, $g, $b ) = array_map( 'hexdec', str_split( $hex, 2 ) );
    return "rgba($r,$g,$b,$alpha)";
}

function misiones2027_qa_icon( $name ) {
    $icons = [
        'ticket'   => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'hotel'    => '<path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/>',
        'plane'    => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
        'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'map-pin'  => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'calendar' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
        'compass'  => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
        'star'     => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'info'     => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
    ];
    return $icons[ $name ] ?? $icons['info'];
}

add_action('init', function () {

    global $wp_post_types;

    if (isset($wp_post_types['registro-unico'])) {

        $wp_post_types['registro-unico']->public = true;

        $wp_post_types['registro-unico']->show_ui = true;

        $wp_post_types['registro-unico']->show_in_nav_menus = true;

    }

}, 100);