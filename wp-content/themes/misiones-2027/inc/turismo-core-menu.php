<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────────────────────
// TURISMO CORE — Menú principal + sub-páginas de footer
// Prioridad 5: se registra antes que los plugins (prioridad 10)
// ─────────────────────────────────────────────────────────────

add_action( 'admin_menu', function () {

    // Menú padre — abre directamente en Redes Sociales (mismo slug)
    add_menu_page(
        'Turismo Core',
        'Turismo Core',
        'manage_options',
        'turismo-core',
        'turismo_core_social_page',
        'dashicons-location-alt',
        25
    );

    // Primer submenu con el mismo slug reemplaza el duplicado automático
    add_submenu_page( 'turismo-core', 'Redes Sociales — Footer', 'Redes Sociales', 'manage_options', 'turismo-core',         'turismo_core_social_page' );
    add_submenu_page( 'turismo-core', 'Contacto — Footer',        'Contacto',        'manage_options', 'turismo-core-contact', 'turismo_core_contact_page' );

}, 5 );

// ─────────────────────────────────────────────────────────────
// ASSETS — sólo en páginas de Turismo Core
// ─────────────────────────────────────────────────────────────

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'turismo-core' ) === false ) return;

    wp_enqueue_style(
        'turismo-core-social',
        get_template_directory_uri() . '/assets/css/customizer-social.css',
        [],
        '1.0'
    );
    wp_enqueue_script(
        'turismo-core-social',
        get_template_directory_uri() . '/assets/js/customizer-social.js',
        [ 'jquery' ],
        '1.0',
        true
    );
} );

// ─────────────────────────────────────────────────────────────
// HELPER — render cards (reutilizado en social y contacto)
// ─────────────────────────────────────────────────────────────

function turismo_core_render_cards( array $data, array $definitions, string $field_label_title = 'Título' ) {
    $total = count( $data );
    ob_start();
    foreach ( $data as $i => $item ) :
        $key = $item['key'];
        if ( ! isset( $definitions[ $key ] ) ) continue;
        $def   = $definitions[ $key ];
        $title = esc_attr( $item['title'] ?? $def['label'] );
        $url   = esc_attr( $item['url']   ?? '' );
    ?>
    <li class="social-item" data-key="<?php echo esc_attr( $key ); ?>">
        <div class="social-item__header">
            <svg class="social-item__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <?php echo $def['svg']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </svg>
            <span class="social-item__name"><?php echo esc_html( $def['label'] ); ?></span>
            <div class="social-item__arrows">
                <button type="button" class="social-move-up"   <?php disabled( $i === 0 );          ?> title="Subir">↑</button>
                <button type="button" class="social-move-down" <?php disabled( $i === $total - 1 ); ?> title="Bajar">↓</button>
            </div>
        </div>
        <div class="social-item__body">
            <div class="social-item__field">
                <span class="social-item__field-label"><?php echo esc_html( $field_label_title ); ?></span>
                <input type="text" class="social-item__title-input" value="<?php echo $title; ?>" placeholder="<?php echo esc_attr( $def['label'] ); ?>">
            </div>
            <div class="social-item__field">
                <span class="social-item__field-label">URL</span>
                <input type="url" class="social-item__url-input" value="<?php echo $url; ?>" placeholder="https://">
            </div>
        </div>
    </li>
    <?php endforeach;
    return ob_get_clean();
}

// ─────────────────────────────────────────────────────────────
// PÁGINA: Redes Sociales
// ─────────────────────────────────────────────────────────────

function turismo_core_social_page() {
    $saved = false;

    if ( isset( $_POST['tc_social_nonce'] ) ) {
        check_admin_referer( 'tc_social_save', 'tc_social_nonce' );
        $json  = wp_unslash( $_POST['social_networks'] ?? '' );
        set_theme_mod( 'social_networks', misiones2027_social_sanitize( $json ) );
        $saved = true;
    }

    $networks = misiones2027_social_networks();
    $raw      = json_decode( get_theme_mod( 'social_networks', misiones2027_social_default() ), true );

    $data = $keys = [];
    if ( is_array( $raw ) ) {
        foreach ( $raw as $item ) {
            $k = $item['key'] ?? '';
            if ( isset( $networks[ $k ] ) && ! in_array( $k, $keys, true ) ) {
                $data[] = $item; $keys[] = $k;
            }
        }
    }
    foreach ( $networks as $k => $net ) {
        if ( ! in_array( $k, $keys, true ) ) {
            $data[] = [ 'key' => $k, 'title' => $net['label'], 'url' => '' ];
        }
    }
    ?>
    <div class="wrap">
        <h1>Redes Sociales — Footer</h1>
        <p class="description" style="margin-top:4px">Completá la URL de cada red para mostrarla. Usá ↑↓ para reordenar. Las redes sin URL no aparecen.</p>
        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible" style="margin-top:12px"><p>Cambios guardados.</p></div>
        <?php endif; ?>
        <form method="post" style="margin-top:16px">
            <?php wp_nonce_field( 'tc_social_save', 'tc_social_nonce' ); ?>
            <div class="misiones2027-social-control" style="max-width:440px">
                <ul class="social-items">
                    <?php echo turismo_core_render_cards( $data, $networks, 'Título' ); // phpcs:ignore ?>
                </ul>
                <textarea name="social_networks" class="social-json-input" style="display:none"><?php echo esc_textarea( get_theme_mod( 'social_networks', misiones2027_social_default() ) ); ?></textarea>
            </div>
            <?php submit_button( 'Guardar cambios' ); ?>
        </form>
    </div>
    <?php
}

// ─────────────────────────────────────────────────────────────
// PÁGINA: Contacto
// ─────────────────────────────────────────────────────────────

function turismo_core_contact_page() {
    $saved = false;

    if ( isset( $_POST['tc_contact_nonce'] ) ) {
        check_admin_referer( 'tc_contact_save', 'tc_contact_nonce' );
        $json  = wp_unslash( $_POST['footer_contact'] ?? '' );
        set_theme_mod( 'footer_contact', misiones2027_contact_sanitize( $json ) );
        $saved = true;
    }

    $items = misiones2027_contact_items();
    $raw   = json_decode( get_theme_mod( 'footer_contact', misiones2027_contact_default() ), true );

    $data = $keys = [];
    if ( is_array( $raw ) ) {
        foreach ( $raw as $item ) {
            $k = $item['key'] ?? '';
            if ( isset( $items[ $k ] ) && ! in_array( $k, $keys, true ) ) {
                $data[] = $item; $keys[] = $k;
            }
        }
    }
    foreach ( $items as $k => $item ) {
        if ( ! in_array( $k, $keys, true ) ) {
            $data[] = [ 'key' => $k, 'title' => $item['label'], 'url' => '' ];
        }
    }
    ?>
    <div class="wrap">
        <h1>Contacto — Footer</h1>
        <p class="description" style="margin-top:4px">Editá los datos de contacto del footer. Los ítems sin texto no se muestran.</p>
        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible" style="margin-top:12px"><p>Cambios guardados.</p></div>
        <?php endif; ?>
        <form method="post" style="margin-top:16px">
            <?php wp_nonce_field( 'tc_contact_save', 'tc_contact_nonce' ); ?>
            <div class="misiones2027-contact-control" style="max-width:440px">
                <ul class="social-items">
                    <?php echo turismo_core_render_cards( $data, $items, 'Texto' ); // phpcs:ignore ?>
                </ul>
                <textarea name="footer_contact" class="social-json-input" style="display:none"><?php echo esc_textarea( get_theme_mod( 'footer_contact', misiones2027_contact_default() ) ); ?></textarea>
            </div>
            <?php submit_button( 'Guardar cambios' ); ?>
        </form>
    </div>
    <?php
}
