<?php
/**
 * Template Part: Quick Actions — Bento 4-col
 */

$actions = [
  [
    'label' => 'Entradas',
    'desc'  => 'Comprá entradas a los principales atractivos de la provincia',
    'color' => '#1a5c4c',
    'bg'    => 'rgba(26,92,76,.08)',
    'href'  => '#entradas',
    'icon'  => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
  ],
  [
    'label' => 'Alojamiento',
    'desc'  => 'Encontrá alojamiento en toda la provincia de Misiones',
    'color' => '#1a5c4c',
    'bg'    => 'rgba(26,92,76,.08)',
    'href'  => '#alojamiento',
    'icon'  => '<path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/>',
  ],
  [
    'label' => 'Cómo llegar',
    'desc'  => 'Rutas, vuelos y opciones de transporte para tu viaje',
    'color' => '#1a5c4c',
    'bg'    => 'rgba(26,92,76,.08)',
    'href'  => '#llegar',
    'icon'  => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
  ],
  [
    'label' => 'Informes',
    'desc'  => 'Consultá con nuestros asesores de turismo',
    'color' => '#1a5c4c',
    'bg'    => 'rgba(26,92,76,.08)',
    'href'  => '#informes',
    'icon'  => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
  ],
];
?>

<section id="acciones" class="quick-actions" aria-label="<?php esc_attr_e( 'Acciones rápidas', 'misiones-2027' ); ?>">
  <div class="quick-actions__grid">
    <?php foreach ( $actions as $action ) : ?>
      <a href="<?php echo esc_url( $action['href'] ); ?>" class="quick-action">
        <div class="quick-action__icon" style="background:<?php echo esc_attr( $action['bg'] ); ?>; color:<?php echo esc_attr( $action['color'] ); ?>;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <?php echo $action['icon']; // PHPCS: XSS OK — hardcoded SVG paths ?>
          </svg>
        </div>
        <span class="quick-action__label"><?php echo esc_html( $action['label'] ); ?></span>
        <span class="quick-action__desc"><?php echo esc_html( $action['desc'] ); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
