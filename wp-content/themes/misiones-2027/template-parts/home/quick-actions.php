<?php
/**
 * Template Part: Quick Actions — Bento 4-col
 */

$hs       = function_exists( 'tc_home_get' ) ? tc_home_get() : [];
$qa_saved = $hs['quick_actions'] ?? [];

if ( ! empty( $qa_saved ) ) {
    $cards = array_map( fn( $item ) => [
        'qa_label' => $item['label'] ?? '',
        'qa_desc'  => $item['desc']  ?? '',
        'qa_href'  => $item['href']  ?? '#',
        'qa_icon'  => $item['icon']  ?? 'info',
    ], $qa_saved );
} else {
    $cards = get_field( 'acciones_cards' ) ?: [
        [ 'qa_label' => 'Entradas',     'qa_desc' => 'Comprá entradas a los principales atractivos de la provincia', 'qa_href' => '#entradas',    'qa_icon' => 'ticket' ],
        [ 'qa_label' => 'Alojamiento',  'qa_desc' => 'Encontrá alojamiento en toda la provincia de Misiones',        'qa_href' => '#alojamiento', 'qa_icon' => 'hotel'  ],
        [ 'qa_label' => 'Cómo llegar',  'qa_desc' => 'Rutas, vuelos y opciones de transporte para tu viaje',         'qa_href' => '#llegar',      'qa_icon' => 'plane'  ],
        [ 'qa_label' => 'Informes',     'qa_desc' => 'Consultá con nuestros asesores de turismo',                    'qa_href' => '#informes',    'qa_icon' => 'phone'  ],
    ];
}
?>

<section id="acciones" class="quick-actions" aria-label="<?php esc_attr_e( 'Acciones rápidas', 'misiones-2027' ); ?>">
  <div class="quick-actions__grid">
    <?php foreach ( $cards as $card ) : ?>
      <a href="<?php echo esc_url( $card['qa_href'] ?? '#' ); ?>" class="quick-action">
        <div class="quick-action__icon" style="background:rgba(26,92,76,.08); color:#1a5c4c;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <?php echo misiones2027_qa_icon( $card['qa_icon'] ?? 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
          </svg>
        </div>
        <span class="quick-action__label"><?php echo esc_html( $card['qa_label'] ?? '' ); ?></span>
        <span class="quick-action__desc"><?php echo esc_html( $card['qa_desc'] ?? '' ); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
