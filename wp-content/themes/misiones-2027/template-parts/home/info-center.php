<?php
/**
 * Template Part: Centro de Información Turística
 */

$items = [
  [
    'label' => 'Llamar',
    'sub'   => '(0376) 4447539',
    'href'  => 'tel:+543764447539',
    'icon'  => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
  ],
  [
    'label' => 'WhatsApp',
    'sub'   => '+54 9 3764 13-8114',
    'href'  => 'https://wa.me/5493764138114',
    'icon'  => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
  ],
  [
    'label' => 'Ubicación',
    'sub'   => 'Colón 1985, Posadas',
    'href'  => '#mapa',
    'icon'  => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
  ],
  [
    'label' => 'Lupita AI',
    'sub'   => 'Chat 24/7',
    'href'  => '#chat',
    'icon'  => '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>',
  ],
];
?>

<section id="informes" aria-label="<?php esc_attr_e( 'Centro de Información Turística', 'misiones-2027' ); ?>">
  <div class="info-center">
    <div class="info-center__card">

      <div class="info-center__heading">
        <span class="info-center__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        </span>
        <h2 class="info-center__title">Centro de Información Turística</h2>
      </div>
      <p class="info-center__hours">Lun–Sáb 7:30–20:00 · Dom y feriados 8:00–20:00</p>

      <div class="info-center__grid">
        <?php foreach ( $items as $item ) : ?>
          <a href="<?php echo esc_url( $item['href'] ); ?>" class="info-center__item">
            <div class="info-center__item-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <?php echo $item['icon']; // PHPCS: XSS OK — hardcoded SVG ?>
              </svg>
            </div>
            <div class="info-center__item-label"><?php echo esc_html( $item['label'] ); ?></div>
            <div class="info-center__item-sub"><?php echo esc_html( $item['sub'] ); ?></div>
          </a>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>
