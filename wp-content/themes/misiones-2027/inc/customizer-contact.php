<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// CONTACTO FOOTER — Customizer
// ─────────────────────────────────────────────

function misiones2027_contact_items() {
    return [
        'address'  => [
            'label' => 'Dirección',
            'svg'   => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        ],
        'hours'    => [
            'label' => 'Horarios',
            'svg'   => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        ],
        'phone'    => [
            'label' => 'Teléfono',
            'svg'   => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp',
            'svg'   => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
        ],
        'email'    => [
            'label' => 'Email',
            'svg'   => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
        ],
    ];
}

function misiones2027_contact_default() {
    return wp_json_encode( [
        [ 'key' => 'address',  'title' => 'Colón 1985, Posadas · Misiones', 'url' => 'https://maps.app.goo.gl/EEag9XtacRxQvpys7' ],
        [ 'key' => 'hours',    'title' => 'Lun–Sáb 7:30–20:00 · Dom 8:00–20:00', 'url' => '' ],
        [ 'key' => 'phone',    'title' => '(0376) 4447539', 'url' => 'tel:+543764447539' ],
        [ 'key' => 'whatsapp', 'title' => '+54 9 3764 13-8114', 'url' => 'https://wa.me/5493764138114' ],
        [ 'key' => 'email',    'title' => 'turismo@misiones.gov.ar', 'url' => 'mailto:turismo@misiones.gov.ar' ],
    ] );
}

function misiones2027_contact_sanitize( $value ) {
    $items = misiones2027_contact_items();
    $data  = json_decode( $value, true );

    if ( ! is_array( $data ) ) {
        return misiones2027_contact_default();
    }

    $sanitized    = [];
    $keys_present = [];

    foreach ( $data as $item ) {
        $key = sanitize_key( $item['key'] ?? '' );
        if ( ! isset( $items[ $key ] ) || in_array( $key, $keys_present, true ) ) {
            continue;
        }
        $sanitized[]    = [
            'key'   => $key,
            'title' => sanitize_text_field( $item['title'] ?? $items[ $key ]['label'] ),
            'url'   => esc_url_raw( $item['url'] ?? '' ),
        ];
        $keys_present[] = $key;
    }

    foreach ( $items as $key => $item ) {
        if ( ! in_array( $key, $keys_present, true ) ) {
            $sanitized[] = [ 'key' => $key, 'title' => $item['label'], 'url' => '' ];
        }
    }

    return wp_json_encode( $sanitized );
}


// ─────────────────────────────────────────────
// FRONTEND RENDERER
// ─────────────────────────────────────────────

function misiones2027_render_contact_items() {
    $items   = misiones2027_contact_items();
    $data    = json_decode( get_theme_mod( 'footer_contact', misiones2027_contact_default() ), true );
    $svgs    = [
        'address'  => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'hours'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'whatsapp' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
        'email'    => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
    ];

    if ( ! is_array( $data ) ) {
        return '';
    }

    $output = '';
    $icon_attrs = 'width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';

    foreach ( $data as $item ) {
        $key   = $item['key'] ?? '';
        $text  = $item['title'] ?? '';
        $url   = $item['url'] ?? '';

        if ( empty( $text ) || ! isset( $items[ $key ] ) ) {
            continue;
        }

        $svg = '<svg ' . $icon_attrs . '>' . $svgs[ $key ] . '</svg>';

        if ( ! empty( $url ) ) {
            $output .= sprintf(
                '<a href="%s"%s>%s %s</a>',
                esc_url( $url ),
                in_array( $key, [ 'address', 'whatsapp' ], true ) ? ' rel="noopener noreferrer" target="_blank"' : '',
                $svg,
                esc_html( $text )
            );
        } else {
            $output .= sprintf( '<p>%s %s</p>', $svg, esc_html( $text ) );
        }
    }

    return $output;
}
