<?php
/**
 * Template Part: Eventos Próximos + Almanaque
 */

$events = [
  [ 'day' => '05', 'month' => 'ABR', 'month_num' => 4, 'year' => 2026, 'title' => 'Festival del Turista',          'type' => 'Festival',    'color' => '#f59e0b', 'img' => 'turistas.webp' ],
  [ 'day' => '12', 'month' => 'ABR', 'month_num' => 4, 'year' => 2026, 'title' => 'Trekking Selva Misionera',       'type' => 'Aventura',    'color' => '#10b981', 'img' => 'trekking.jpg' ],
  [ 'day' => '23', 'month' => 'ABR', 'month_num' => 4, 'year' => 2026, 'title' => 'Gran Premio Saltos del Moconá',  'type' => 'Deporte',     'color' => '#ef4444', 'img' => 'mocona.webp'  ],
  [ 'day' => '28', 'month' => 'ABR', 'month_num' => 4, 'year' => 2026, 'title' => 'Cataratas bajo Luna Llena',      'type' => 'Experiencia', 'color' => '#8b5cf6', 'img' => 'luna.jpg'     ],
  [ 'day' => '03', 'month' => 'MAY', 'month_num' => 5, 'year' => 2026, 'title' => 'Festival de las Aves',           'type' => 'Naturaleza',  'color' => '#22c55e', 'img' => '' ],
  [ 'day' => '17', 'month' => 'MAY', 'month_num' => 5, 'year' => 2026, 'title' => 'Fiesta del Inmigrante',          'type' => 'Cultura',     'color' => '#3b82f6', 'img' => '' ],
  [ 'day' => '07', 'month' => 'JUN', 'month_num' => 6, 'year' => 2026, 'title' => 'Circuito Jesuítico',             'type' => 'Historia',    'color' => '#f59e0b', 'img' => '' ],
];

$display = array_slice( $events, 0, 4 );

$cal_events = json_encode( array_map( function( $e ) {
  return [
    'date'  => sprintf( '%04d-%02d-%02d', $e['year'], $e['month_num'], (int) $e['day'] ),
    'title' => $e['title'],
    'color' => $e['color'],
    'type'  => $e['type'],
  ];
}, $events ) );
?>

<section id="eventos" aria-label="<?php esc_attr_e( 'Eventos y escapadas', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      </span>
      <h2 class="section-title__heading">Eventos y escapadas</h2>
    </div>
    <a href="#" class="section-title__link">
      Ver todo
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="events-layout">

    <!-- Left: próximos eventos -->
    <div class="events-panel">
      <div class="events-list">
        <?php foreach ( $display as $event ) :
          $date_str = sprintf( '%04d-%02d-%02d', $event['year'], $event['month_num'], (int) $event['day'] );
        ?>
          <a href="#" class="event-card">

            <div class="event-card__img" style="--event-color:<?php echo esc_attr( $event['color'] ); ?>;">
              <?php if ( ! empty( $event['img'] ) ) : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $event['img'] ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>" loading="lazy">
              <?php endif; ?>
              <div class="event-card__date">
                <strong><?php echo esc_html( $event['day'] ); ?></strong>
                <span><?php echo esc_html( $event['month'] ); ?></span>
              </div>
              <span class="event-card__type" style="background:<?php echo esc_attr( $event['color'] ); ?>;">
                <?php echo esc_html( $event['type'] ); ?>
              </span>
            </div>

            <div class="event-card__body">
              <h3 class="event-card__title"><?php echo esc_html( $event['title'] ); ?></h3>
            </div>

          </a>
        <?php endforeach; ?>
      </div>

      <a href="#" class="events-cta">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
        <?php esc_html_e( 'Ver calendario completo', 'misiones-2027' ); ?>
      </a>
    </div>

    <!-- Right: almanaque -->
    <div class="events-calendar" id="events-calendar"
      data-events="<?php echo esc_attr( $cal_events ); ?>"
      aria-label="<?php esc_attr_e( 'Almanaque de eventos', 'misiones-2027' ); ?>">
    </div>

  </div>

</section>
