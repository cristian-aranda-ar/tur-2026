<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// REDES SOCIALES — Customizer
// ─────────────────────────────────────────────

function misiones2027_social_networks() {
    return [
        'instagram' => [
            'label' => 'Instagram',
            'svg'   => '<rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/>',
        ],
        'facebook'  => [
            'label' => 'Facebook',
            'svg'   => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
        ],
        'youtube'   => [
            'label' => 'YouTube',
            'svg'   => '<path d="M22.54 6.42A2.78 2.78 0 0 0 20.59 4.44C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.41 19.3C5.12 19.75 12 19.75 12 19.75s6.88 0 8.59-.45a2.78 2.78 0 0 0 1.95-1.98A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/>',
        ],
        'tiktok'    => [
            'label' => 'TikTok',
            'svg'   => '<path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/>',
        ],
        'x'         => [
            'label' => 'X / Twitter',
            'svg'   => '<path d="M18 6 6 18M6 6l12 12"/>',
        ],
        'whatsapp'  => [
            'label' => 'WhatsApp',
            'svg'   => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
        ],
        'linkedin'  => [
            'label' => 'LinkedIn',
            'svg'   => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
        ],
    ];
}

function misiones2027_social_default() {
    $items = [];
    foreach ( misiones2027_social_networks() as $key => $net ) {
        $items[] = [ 'key' => $key, 'title' => $net['label'], 'url' => '' ];
    }
    return wp_json_encode( $items );
}

function misiones2027_social_sanitize( $value ) {
    $networks = misiones2027_social_networks();
    $data     = json_decode( $value, true );

    if ( ! is_array( $data ) ) {
        return misiones2027_social_default();
    }

    $sanitized    = [];
    $keys_present = [];

    foreach ( $data as $item ) {
        $key = sanitize_key( $item['key'] ?? '' );
        if ( ! isset( $networks[ $key ] ) || in_array( $key, $keys_present, true ) ) {
            continue;
        }
        $sanitized[]    = [
            'key'   => $key,
            'title' => sanitize_text_field( $item['title'] ?? $networks[ $key ]['label'] ),
            'url'   => esc_url_raw( $item['url'] ?? '' ),
        ];
        $keys_present[] = $key;
    }

    // Append networks not present yet (e.g. newly added in a theme update)
    foreach ( $networks as $key => $net ) {
        if ( ! in_array( $key, $keys_present, true ) ) {
            $sanitized[] = [ 'key' => $key, 'title' => $net['label'], 'url' => '' ];
        }
    }

    return wp_json_encode( $sanitized );
}


// ─────────────────────────────────────────────
// FRONTEND RENDERER
// ─────────────────────────────────────────────

function misiones2027_render_social_links() {
    $networks = misiones2027_social_networks();
    $data     = json_decode( get_theme_mod( 'social_networks', misiones2027_social_default() ), true );

    if ( ! is_array( $data ) ) {
        return '';
    }

    $output = '';
    foreach ( $data as $item ) {
        $key = $item['key'] ?? '';
        $url = $item['url'] ?? '';

        if ( empty( $url ) || ! isset( $networks[ $key ] ) ) {
            continue;
        }

        $title  = esc_attr( ! empty( $item['title'] ) ? $item['title'] : $networks[ $key ]['label'] );
        $output .= sprintf(
            '<a href="%s" class="social-btn" aria-label="%s" title="%s" rel="noopener noreferrer" target="_blank">'
            . '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>'
            . '</a>',
            esc_url( $url ),
            $title,
            $title,
            $networks[ $key ]['svg']
        );
    }

    return $output;
}
