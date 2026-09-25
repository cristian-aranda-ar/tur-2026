<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────────────────────
// MENÚ MOBILE (overlay) — usa el mismo menú que el header desktop
// (theme_location 'header-2026'), agrupando por ítems de primer nivel.
// ─────────────────────────────────────────────────────────────

function misiones2027_render_mobile_menu( $theme_location = 'header-2026' ) {
    $locations = get_nav_menu_locations();
    if ( empty( $locations[ $theme_location ] ) ) return;

    $menu = wp_get_nav_menu_object( $locations[ $theme_location ] );
    if ( ! $menu ) return;

    $items = wp_get_nav_menu_items( $menu->term_id );
    if ( empty( $items ) ) return;

    $children = [];
    foreach ( $items as $item ) {
        $children[ (int) $item->menu_item_parent ][] = $item;
    }

    $top_items = $children[0] ?? [];
    usort( $top_items, function ( $a, $b ) { return $a->menu_order <=> $b->menu_order; } );

    foreach ( $top_items as $top ) {
        $sub_items = $children[ $top->ID ] ?? [];
        usort( $sub_items, function ( $a, $b ) { return $a->menu_order <=> $b->menu_order; } );

        // Sin hijos: si el ítem tiene una URL real, lo mostramos como link directo.
        if ( empty( $sub_items ) ) {
            if ( empty( $top->url ) || '#' === $top->url ) continue;
            ?>
            <div class="menu-section">
                <a href="<?php echo esc_url( $top->url ); ?>" class="menu-section__item menu-section__item--single">
                    <?php echo esc_html( $top->title ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
            <?php
            continue;
        }
        ?>
        <div class="menu-section">
            <span class="menu-section__label"><?php echo esc_html( $top->title ); ?></span>
            <?php foreach ( $sub_items as $sub ) : ?>
                <a href="<?php echo esc_url( $sub->url ); ?>" class="menu-section__item">
                    <?php echo esc_html( $sub->title ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
