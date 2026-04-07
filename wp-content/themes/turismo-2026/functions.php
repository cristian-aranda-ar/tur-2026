<?php

defined( 'ABSPATH' ) || exit;

define( 'TURISMO_VERSION', '1.0.0' );
define( 'TURISMO_DIR', get_template_directory() );
define( 'TURISMO_URI', get_template_directory_uri() );

function turismo_setup() {
    load_theme_textdomain( 'turismo-2026', TURISMO_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'automatic-feed-links' );

    // Gutenberg: ancho del editor y colores del tema
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'editor-color-palette', [
        [ 'name' => __( 'Primario',   'turismo-2026' ), 'slug' => 'primary',   'color' => '#35C071' ],
        [ 'name' => __( 'Secundario', 'turismo-2026' ), 'slug' => 'secondary', 'color' => '#E87219' ],
        [ 'name' => __( 'Terciario',  'turismo-2026' ), 'slug' => 'tertiary',  'color' => '#0C3423' ],
    ] );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'turismo-2026' ),
        'footer'  => __( 'Menu footer',    'turismo-2026' ),
    ] );
}
add_action( 'after_setup_theme', 'turismo_setup' );

function turismo_enqueue() {
    $css_file = TURISMO_DIR . '/assets/css/app.css';
    $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : TURISMO_VERSION;

    wp_enqueue_style( 'turismo-style', TURISMO_URI . '/assets/css/app.css', [], $css_ver );
    wp_enqueue_script( 'turismo-main', TURISMO_URI . '/assets/js/main.js', [], TURISMO_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'turismo_enqueue' );

// Agregar aria-expanded="false" a los items de menú que tienen hijos
function turismo_nav_menu_link_atts( $atts, $item ) {
    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
        $atts['aria-expanded'] = 'false';
        $atts['aria-haspopup'] = 'true';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'turismo_nav_menu_link_atts', 10, 2 );

// Estilos del editor de Gutenberg (mismos colores que el front)
function turismo_editor_styles() {
    add_editor_style( 'assets/css/app.css' );
}
add_action( 'after_setup_theme', 'turismo_editor_styles' );
