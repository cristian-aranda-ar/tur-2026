<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────────────────────
// TURISMO CORE HOME — opciones de la página principal
// ─────────────────────────────────────────────────────────────

add_action( 'admin_menu', function () {
    add_submenu_page(
        'turismo-core',
        'Home',
        'Home',
        'manage_options',
        'turismo-core-home',
        'tc_home_page'
    );
}, 6 );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'turismo-core-home' ) === false ) return;

    wp_enqueue_media();
    wp_enqueue_style(
        'tc-home-admin',
        get_template_directory_uri() . '/assets/css/home-admin.css',
        [],
        '1.0'
    );
    wp_enqueue_script(
        'tc-home-admin',
        get_template_directory_uri() . '/assets/js/home-admin.js',
        [ 'jquery' ],
        '1.0',
        true
    );

    $ru_posts = get_posts( [
        'post_type'      => 'registro-unico',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'fields'         => 'ids',
    ] );
    $ru_for_js = array_map( function ( $id ) {
        return [ 'id' => $id, 'title' => get_the_title( $id ) ];
    }, $ru_posts );

    $cat_terms = get_terms( [ 'taxonomy' => 'categoria-ru', 'hide_empty' => false ] );
    $cats_for_js = is_wp_error( $cat_terms ) ? [] : array_map( fn( $t ) => [
        'slug' => $t->slug,
        'name' => $t->name,
    ], $cat_terms );

    wp_localize_script( 'tc-home-admin', 'tcHomeData', [
        'ruPosts'    => $ru_for_js,
        'categoryRu' => $cats_for_js,
    ] );
} );

// ─────────────────────────────────────────────────────────────
// DATOS — getter con defaults
// ─────────────────────────────────────────────────────────────

function tc_home_get(): array {
    $defaults = [
        'hero_video_url'         => '',
        'quick_actions'          => [],
        'destinos_titulo'        => 'Destinos Imperdibles',
        'destinos_ver_todo_text' => 'Ver todo',
        'destinos_ver_todo_url'  => '#',
        'destinos_post_ids'      => [],
        'experiencias_titulo'    => 'Tu experiencia ideal',
        'experiencias'           => [],
        'noticias_count'         => 6,
        'instagram_titulo'       => '#TuMisiones',
        'instagram_url'          => '#',
        'instagram_boton'        => 'Ver todo',
        'instagram_shortcode'    => '',
    ];
    $saved = get_option( 'tc_home_settings', [] );
    return array_merge( $defaults, is_array( $saved ) ? $saved : [] );
}

// ─────────────────────────────────────────────────────────────
// GUARDAR
// ─────────────────────────────────────────────────────────────

function tc_home_save( string $tab, array $post_data ): void {
    $s = tc_home_get();

    switch ( $tab ) {
        case 'hero':
            $s['hero_video_url'] = esc_url_raw( wp_unslash( $post_data['hero_video_url'] ?? '' ) );
            break;

        case 'quick-actions':
            $json = wp_unslash( $post_data['quick_actions_json'] ?? '' );
            $arr  = json_decode( $json, true );
            if ( is_array( $arr ) ) {
                $valid_icons = [ 'ticket', 'hotel', 'plane', 'phone', 'map-pin', 'calendar', 'compass', 'star', 'info' ];
                $clean = [];
                foreach ( $arr as $item ) {
                    $icon = $item['icon'] ?? 'info';
                    $clean[] = [
                        'icon'  => in_array( $icon, $valid_icons, true ) ? $icon : 'info',
                        'label' => sanitize_text_field( $item['label'] ?? '' ),
                        'desc'  => sanitize_text_field( $item['desc'] ?? '' ),
                        'href'  => esc_url_raw( $item['href'] ?? '#' ),
                    ];
                }
                $s['quick_actions'] = $clean;
            }
            break;

        case 'destinos':
            $s['destinos_titulo']        = sanitize_text_field( wp_unslash( $post_data['destinos_titulo'] ?? '' ) );
            $s['destinos_ver_todo_text'] = sanitize_text_field( wp_unslash( $post_data['destinos_ver_todo_text'] ?? '' ) );
            $s['destinos_ver_todo_url']  = esc_url_raw( wp_unslash( $post_data['destinos_ver_todo_url'] ?? '' ) );
            $ids_arr = json_decode( wp_unslash( $post_data['destinos_post_ids_json'] ?? '' ), true );
            $s['destinos_post_ids'] = is_array( $ids_arr ) ? array_map( 'absint', $ids_arr ) : [];
            break;

        case 'experiencias':
            $s['experiencias_titulo'] = sanitize_text_field( wp_unslash( $post_data['experiencias_titulo'] ?? '' ) );
            $arr = json_decode( wp_unslash( $post_data['experiencias_json'] ?? '' ), true );
            if ( is_array( $arr ) ) {
                $clean = [];
                foreach ( $arr as $item ) {
                    $cats_raw = $item['categorias'] ?? [];
                    if ( is_string( $cats_raw ) ) {
                        $cats_raw = array_filter( array_map( 'sanitize_title', explode( ',', $cats_raw ) ) );
                    }
                    $color = sanitize_hex_color( $item['color'] ?? '#1a5c4c' );
                    $clean[] = [
                        'label'      => sanitize_text_field( $item['label'] ?? '' ),
                        'color'      => $color ?? '#1a5c4c',
                        'desc'       => sanitize_text_field( $item['desc'] ?? '' ),
                        'icon'       => wp_strip_all_tags( $item['icon'] ?? '' ),
                        'categorias' => array_values( array_map( 'sanitize_title', (array) $cats_raw ) ),
                    ];
                }
                $s['experiencias'] = $clean;
            }
            break;

        case 'noticias':
            $count = intval( $post_data['noticias_count'] ?? 6 );
            $s['noticias_count'] = in_array( $count, [ 3, 6, 9 ], true ) ? $count : 6;
            break;

        case 'instagram':
            $s['instagram_titulo']    = sanitize_text_field( wp_unslash( $post_data['instagram_titulo'] ?? '' ) );
            $s['instagram_url']       = esc_url_raw( wp_unslash( $post_data['instagram_url'] ?? '' ) );
            $s['instagram_boton']     = sanitize_text_field( wp_unslash( $post_data['instagram_boton'] ?? '' ) );
            $s['instagram_shortcode'] = sanitize_textarea_field( wp_unslash( $post_data['instagram_shortcode'] ?? '' ) );
            break;
    }

    update_option( 'tc_home_settings', $s );
}

// ─────────────────────────────────────────────────────────────
// PÁGINA PRINCIPAL
// ─────────────────────────────────────────────────────────────

function tc_home_page(): void {
    $valid_tabs = [ 'hero', 'quick-actions', 'destinos', 'experiencias', 'noticias', 'instagram' ];
    $tab        = sanitize_key( $_GET['tab'] ?? 'hero' );
    if ( ! in_array( $tab, $valid_tabs, true ) ) $tab = 'hero';

    $saved = false;

    if ( isset( $_POST['tc_home_nonce'] ) ) {
        check_admin_referer( 'tc_home_save', 'tc_home_nonce' );
        $posted_tab = sanitize_key( $_POST['tab'] ?? $tab );
        if ( in_array( $posted_tab, $valid_tabs, true ) ) {
            tc_home_save( $posted_tab, $_POST );
            $tab   = $posted_tab;
            $saved = true;
        }
    }

    $s        = tc_home_get();
    $page_url = admin_url( 'admin.php?page=turismo-core-home' );
    $tabs_labels = [
        'hero'          => 'Hero',
        'quick-actions' => 'Quick Actions',
        'destinos'      => 'Destinos',
        'experiencias'  => 'Experiencias',
        'noticias'      => 'Noticias',
        'instagram'     => 'Instagram',
    ];
    ?>
    <div class="wrap">
        <h1>Turismo Core — Home</h1>
        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible" style="margin-top:12px"><p>Cambios guardados.</p></div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper" style="margin-top:16px">
            <?php foreach ( $tabs_labels as $key => $label ) : ?>
                <a href="<?php echo esc_url( $page_url . '&tab=' . $key ); ?>"
                   class="nav-tab<?php echo $tab === $key ? ' nav-tab-active' : ''; ?>">
                    <?php echo esc_html( $label ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="tc-home-tab-content">
            <form method="post">
                <?php wp_nonce_field( 'tc_home_save', 'tc_home_nonce' ); ?>
                <input type="hidden" name="tab" value="<?php echo esc_attr( $tab ); ?>">
                <?php
                switch ( $tab ) {
                    case 'hero':          tc_home_tab_hero( $s );          break;
                    case 'quick-actions': tc_home_tab_quick_actions( $s ); break;
                    case 'destinos':      tc_home_tab_destinos( $s );      break;
                    case 'experiencias':  tc_home_tab_experiencias( $s );  break;
                    case 'noticias':      tc_home_tab_noticias( $s );      break;
                    case 'instagram':     tc_home_tab_instagram( $s );     break;
                }
                ?>
                <?php submit_button( 'Guardar cambios' ); ?>
            </form>
        </div>
    </div>
    <?php
}

// ─────────────────────────────────────────────────────────────
// TAB: HERO
// ─────────────────────────────────────────────────────────────

function tc_home_tab_hero( array $s ): void {
    $url = esc_attr( $s['hero_video_url'] );
    ?>
    <h2 class="tc-tab-title">Video de fondo del Hero</h2>
    <p class="description">Cargá un archivo MP4 para el video de fondo. Si está vacío, se usa el video de YouTube configurado en el tema.</p>
    <table class="form-table" style="margin-top:16px">
        <tr>
            <th style="width:160px"><label for="hero_video_url">URL del video</label></th>
            <td>
                <div class="tc-media-row">
                    <input type="url" id="hero_video_url" name="hero_video_url"
                           value="<?php echo $url; ?>"
                           class="regular-text" placeholder="https://...">
                    <button type="button" class="button" id="tc-hero-upload-btn">Subir / Elegir</button>
                    <?php if ( $s['hero_video_url'] ) : ?>
                        <button type="button" class="button-link tc-clear-field" data-target="hero_video_url" style="color:#b32d2e">Quitar</button>
                    <?php endif; ?>
                </div>
                <?php if ( $s['hero_video_url'] ) : ?>
                    <div style="margin-top:12px">
                        <video src="<?php echo esc_url( $s['hero_video_url'] ); ?>" controls muted style="max-width:480px;border-radius:4px;"></video>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

// ─────────────────────────────────────────────────────────────
// TAB: QUICK ACTIONS
// ─────────────────────────────────────────────────────────────

function tc_home_tab_quick_actions( array $s ): void {
    $qa = $s['quick_actions'];
    if ( empty( $qa ) ) {
        $qa = [
            [ 'icon' => 'ticket', 'label' => 'Entradas',     'desc' => 'Comprá entradas a los principales atractivos de la provincia', 'href' => '#entradas'    ],
            [ 'icon' => 'hotel',  'label' => 'Alojamiento',  'desc' => 'Encontrá alojamiento en toda la provincia de Misiones',        'href' => '#alojamiento' ],
            [ 'icon' => 'plane',  'label' => 'Cómo llegar',  'desc' => 'Rutas, vuelos y opciones de transporte para tu viaje',         'href' => '#llegar'      ],
            [ 'icon' => 'phone',  'label' => 'Informes',     'desc' => 'Consultá con nuestros asesores de turismo',                    'href' => '#informes'    ],
        ];
    }
    $icons = [ 'ticket', 'hotel', 'plane', 'phone', 'map-pin', 'calendar', 'compass', 'star', 'info' ];
    $total = count( $qa );
    ?>
    <h2 class="tc-tab-title">Quick Actions</h2>
    <p class="description">Administrá las acciones rápidas del home. Usá ↑↓ para reordenar.</p>
    <div class="tc-qa-control" style="max-width:600px;margin-top:16px">
        <ul class="tc-card-list">
            <?php foreach ( $qa as $i => $item ) : ?>
            <li class="tc-card-item">
                <div class="tc-card-item__header">
                    <span class="tc-card-item__title"><?php echo esc_html( $item['label'] ?? '' ); ?></span>
                    <div class="social-item__arrows">
                        <button type="button" class="social-move-up"   <?php disabled( $i === 0 );          ?> title="Subir">↑</button>
                        <button type="button" class="social-move-down" <?php disabled( $i === $total - 1 ); ?> title="Bajar">↓</button>
                    </div>
                </div>
                <div class="tc-card-item__body">
                    <div class="tc-field-row">
                        <span class="tc-field-label">Ícono</span>
                        <select class="tc-qa-icon">
                            <?php foreach ( $icons as $ico ) : ?>
                                <option value="<?php echo esc_attr( $ico ); ?>" <?php selected( $item['icon'] ?? 'info', $ico ); ?>>
                                    <?php echo esc_html( $ico ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">Título</span>
                        <input type="text" class="tc-qa-label" value="<?php echo esc_attr( $item['label'] ?? '' ); ?>" placeholder="Título">
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">Descripción</span>
                        <input type="text" class="tc-qa-desc" value="<?php echo esc_attr( $item['desc'] ?? '' ); ?>" placeholder="Descripción breve">
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">URL</span>
                        <input type="text" class="tc-qa-href" value="<?php echo esc_attr( $item['href'] ?? '' ); ?>" placeholder="#ancla o https://...">
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <textarea name="quick_actions_json" class="tc-json-store" style="display:none"><?php echo esc_textarea( wp_json_encode( $qa ) ); ?></textarea>
    </div>
    <?php
}

// ─────────────────────────────────────────────────────────────
// TAB: DESTINOS
// ─────────────────────────────────────────────────────────────

function tc_home_tab_destinos( array $s ): void {
    $ids = array_filter( array_map( 'absint', $s['destinos_post_ids'] ) );

    $selected_posts = [];
    if ( ! empty( $ids ) ) {
        $posts = get_posts( [
            'post__in'       => $ids,
            'post_type'      => 'registro-unico',
            'posts_per_page' => -1,
            'orderby'        => 'post__in',
        ] );
        foreach ( $posts as $p ) {
            $selected_posts[ $p->ID ] = $p->post_title;
        }
        // preserve order from $ids
        $ordered = [];
        foreach ( $ids as $id ) {
            if ( isset( $selected_posts[ $id ] ) ) $ordered[ $id ] = $selected_posts[ $id ];
        }
        $selected_posts = $ordered;
    }

    $ids_json = esc_textarea( wp_json_encode( array_keys( $selected_posts ) ) );
    $total    = count( $selected_posts );
    ?>
    <h2 class="tc-tab-title">Sección Destinos</h2>
    <table class="form-table" style="margin-top:16px">
        <tr>
            <th style="width:200px"><label for="destinos_titulo">Título del bloque</label></th>
            <td><input type="text" id="destinos_titulo" name="destinos_titulo"
                       value="<?php echo esc_attr( $s['destinos_titulo'] ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="destinos_ver_todo_text">Texto "Ver todo"</label></th>
            <td><input type="text" id="destinos_ver_todo_text" name="destinos_ver_todo_text"
                       value="<?php echo esc_attr( $s['destinos_ver_todo_text'] ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="destinos_ver_todo_url">URL "Ver todo"</label></th>
            <td><input type="url" id="destinos_ver_todo_url" name="destinos_ver_todo_url"
                       value="<?php echo esc_attr( $s['destinos_ver_todo_url'] ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th>Registros destacados</th>
            <td>
                <p class="description" style="margin-top:0">Seleccioná y ordená los Registros Únicos del carrusel. Vacío = muestra los marcados como "imperdibles".</p>
                <div class="tc-destinos-picker" style="margin-top:12px;max-width:520px">
                    <div class="tc-search-row">
                        <input type="text" id="tc-destinos-search" class="regular-text"
                               placeholder="Buscar registro único..." autocomplete="off" style="width:100%">
                        <ul id="tc-destinos-dropdown" class="tc-picker-dropdown" style="display:none"></ul>
                    </div>
                    <ul class="tc-card-list" id="tc-destinos-selected" style="margin-top:8px">
                        <?php
                        $idx = 0;
                        foreach ( $selected_posts as $pid => $ptitle ) :
                        ?>
                        <li class="tc-destino-item" data-id="<?php echo esc_attr( $pid ); ?>">
                            <span class="tc-destino-item__title"><?php echo esc_html( $ptitle ); ?></span>
                            <div class="social-item__arrows">
                                <button type="button" class="social-move-up"   <?php disabled( $idx === 0 ); ?> title="Subir">↑</button>
                                <button type="button" class="social-move-down" <?php disabled( $idx === $total - 1 ); ?> title="Bajar">↓</button>
                            </div>
                            <button type="button" class="tc-destino-remove button-link" title="Quitar">✕</button>
                        </li>
                        <?php $idx++; endforeach; ?>
                    </ul>
                    <textarea name="destinos_post_ids_json" id="tc-destinos-json" style="display:none"><?php echo $ids_json; ?></textarea>
                </div>
            </td>
        </tr>
    </table>
    <?php
}

// ─────────────────────────────────────────────────────────────
// TAB: EXPERIENCIAS
// ─────────────────────────────────────────────────────────────

function tc_home_tab_experiencias( array $s ): void {
    $exps = $s['experiencias'];
    if ( empty( $exps ) ) {
        $exps = [
            [ 'label' => 'Aventura',   'color' => '#2d8653', 'desc' => 'Kayak, trekking, rappel',    'icon' => '<path d="m8 3 4 8 5-5 5 15H2L8 3z"/>',                                                                                                                                  'categorias' => [ 'aventura' ]   ],
            [ 'label' => 'Familia',    'color' => '#3b82f6', 'desc' => 'Parques, playas, campings',  'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',                 'categorias' => [ 'familia' ]    ],
            [ 'label' => 'Cultura',    'color' => '#a855f7', 'desc' => 'Ruinas, museos, guaraní',    'icon' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',                                                            'categorias' => [ 'cultura' ]    ],
            [ 'label' => 'Sabores',    'color' => '#f59e0b', 'desc' => 'Té, yerba, gastronomía',     'icon' => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',                                                         'categorias' => [ 'sabores' ]    ],
            [ 'label' => 'Naturaleza', 'color' => '#10b981', 'desc' => 'Aves, selva, fauna',         'icon' => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',                              'categorias' => [ 'naturaleza' ] ],
        ];
    }
    $total = count( $exps );
    ?>
    <h2 class="tc-tab-title">Sección Experiencias</h2>
    <table class="form-table" style="margin-top:16px">
        <tr>
            <th style="width:200px"><label for="experiencias_titulo">Título del bloque</label></th>
            <td><input type="text" id="experiencias_titulo" name="experiencias_titulo"
                       value="<?php echo esc_attr( $s['experiencias_titulo'] ); ?>" class="regular-text"></td>
        </tr>
    </table>
    <p class="description" style="margin-top:4px">El número de cada experiencia se calcula automáticamente desde los Registros Únicos de esas categorías.</p>
    <div class="tc-exp-control" style="max-width:640px;margin-top:12px">
        <ul class="tc-card-list">
            <?php foreach ( $exps as $i => $exp ) :
                $cats = is_array( $exp['categorias'] ) ? implode( ', ', $exp['categorias'] ) : ( $exp['categorias'] ?? '' );
            ?>
            <li class="tc-exp-item">
                <div class="tc-card-item__header">
                    <span class="tc-exp-color-dot" style="background:<?php echo esc_attr( $exp['color'] ?? '#1a5c4c' ); ?>"></span>
                    <span class="tc-card-item__title"><?php echo esc_html( $exp['label'] ?? '' ); ?></span>
                    <div class="social-item__arrows">
                        <button type="button" class="social-move-up"   <?php disabled( $i === 0 );          ?> title="Subir">↑</button>
                        <button type="button" class="social-move-down" <?php disabled( $i === $total - 1 ); ?> title="Bajar">↓</button>
                    </div>
                </div>
                <div class="tc-card-item__body">
                    <div class="tc-field-row">
                        <span class="tc-field-label">Título</span>
                        <input type="text" class="tc-exp-label" value="<?php echo esc_attr( $exp['label'] ?? '' ); ?>">
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">Color</span>
                        <input type="color" class="tc-exp-color" value="<?php echo esc_attr( $exp['color'] ?? '#1a5c4c' ); ?>">
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">Descripción</span>
                        <input type="text" class="tc-exp-desc" value="<?php echo esc_attr( $exp['desc'] ?? '' ); ?>">
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">Ícono SVG</span>
                        <input type="text" class="tc-exp-icon" value="<?php echo esc_attr( $exp['icon'] ?? '' ); ?>" placeholder='&lt;path d="..."/&gt;'>
                    </div>
                    <div class="tc-field-row">
                        <span class="tc-field-label">Categorías RU</span>
                        <input type="text" class="tc-exp-cats" value="<?php echo esc_attr( $cats ); ?>" placeholder="aventura, familia">
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <textarea name="experiencias_json" class="tc-json-store" style="display:none"><?php echo esc_textarea( wp_json_encode( $exps ) ); ?></textarea>
    </div>
    <?php
}

// ─────────────────────────────────────────────────────────────
// TAB: NOTICIAS
// ─────────────────────────────────────────────────────────────

function tc_home_tab_noticias( array $s ): void {
    $count = intval( $s['noticias_count'] );
    ?>
    <h2 class="tc-tab-title">Sección Noticias</h2>
    <p class="description">Elegí cuántos posts recientes mostrar.</p>
    <fieldset style="margin-top:16px">
        <?php foreach ( [ 3, 6, 9 ] as $n ) : ?>
        <label style="display:inline-flex;align-items:center;gap:6px;margin-right:24px;cursor:pointer">
            <input type="radio" name="noticias_count" value="<?php echo $n; ?>" <?php checked( $count, $n ); ?>>
            <?php echo $n; ?> noticias
        </label>
        <?php endforeach; ?>
    </fieldset>
    <?php
}

// ─────────────────────────────────────────────────────────────
// TAB: INSTAGRAM
// ─────────────────────────────────────────────────────────────

function tc_home_tab_instagram( array $s ): void {
    ?>
    <h2 class="tc-tab-title">Sección Instagram</h2>
    <table class="form-table" style="margin-top:16px">
        <tr>
            <th style="width:200px"><label for="instagram_titulo">Título</label></th>
            <td><input type="text" id="instagram_titulo" name="instagram_titulo"
                       value="<?php echo esc_attr( $s['instagram_titulo'] ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="instagram_url">URL del botón</label></th>
            <td><input type="url" id="instagram_url" name="instagram_url"
                       value="<?php echo esc_attr( $s['instagram_url'] ); ?>" class="regular-text"
                       placeholder="https://instagram.com/misiones.turismo"></td>
        </tr>
        <tr>
            <th><label for="instagram_boton">Texto del botón</label></th>
            <td><input type="text" id="instagram_boton" name="instagram_boton"
                       value="<?php echo esc_attr( $s['instagram_boton'] ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="instagram_shortcode">Shortcode del feed</label></th>
            <td>
                <textarea id="instagram_shortcode" name="instagram_shortcode"
                          class="large-text code" rows="3"
                          placeholder="[instagram-feed ...]"><?php echo esc_textarea( $s['instagram_shortcode'] ); ?></textarea>
                <p class="description">Pegá el shortcode de tu plugin de Instagram. Reemplaza toda la galería. Dejá en blanco para usar el diseño por defecto.</p>
            </td>
        </tr>
    </table>
    <?php
}
