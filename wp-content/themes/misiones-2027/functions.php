<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// INCLUDES
// ─────────────────────────────────────────────
require_once get_template_directory() . '/inc/hero-slides.php';

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
        'primary'  => __( 'Menú Principal', 'misiones-2027' ),
        'footer'   => __( 'Menú Footer', 'misiones-2027' ),
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
    wp_enqueue_style(
        'misiones2027-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [ 'misiones2027-fonts', 'leaflet' ],
        wp_get_theme()->get( 'Version' )
    );

    // Main JS
    wp_enqueue_script(
        'misiones2027-main',
        get_template_directory_uri() . '/assets/js/main.js',
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
