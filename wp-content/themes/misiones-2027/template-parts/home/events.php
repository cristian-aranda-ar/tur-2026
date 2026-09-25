<?php
/**
 * Template Part: Eventos Próximos + Almanaque
 * Carga dinámicamente registro-unico con categoria-ru = "calendario".
 * Muestra los próximos 4 eventos y el almanaque para el mes actual + 2 meses.
 */

// ── Rango permitido: mes actual hasta el último día del mes+2 ──────
$now     = new DateTime();
$min_date = $now->format( 'Y-m-01' );
$max_date = ( clone $now )->modify( '+2 months' )->format( 'Y-m-t' );

// ── Query posts categoria "calendario" ────────────────────────────
$cal_query = new WP_Query( [
    'post_type'      => 'registro-unico',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => [ [
        'taxonomy' => 'categoria-ru',
        'field'    => 'slug',
        'terms'    => 'calendario',
    ] ],
] );

// ── Expandir fechas en entradas individuales para el calendario ───
function turs_expand_event_dates( WP_Post $post, string $min, string $max ): array {
    $tipo    = get_post_meta( $post->ID, '_ru_fecha_tipo', true );
    $color   = get_post_meta( $post->ID, 'color', true ) ?: '#1a5c4c';
    $volanta = get_post_meta( $post->ID, 'hero_volanta', true );
    $title   = $post->post_title;
    $url     = get_permalink( $post->ID );
    $img     = get_the_post_thumbnail_url( $post->ID, 'large' ) ?: '';

    $dates = [];

    if ( $tipo === 'unica' ) {
        $d = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        if ( $d >= $min && $d <= $max ) {
            $dates[] = [ 'date' => $d, 'title' => $title, 'color' => $color,
                         'type' => $volanta, 'url' => $url, 'img' => $img ];
        }
    } elseif ( $tipo === 'rango' ) {
        $inicio = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        $fin    = get_post_meta( $post->ID, '_ru_fecha_fin', true ) ?: $inicio;
        $cur    = new DateTime( $inicio );
        $end    = new DateTime( $fin );
        while ( $cur <= $end ) {
            $d = $cur->format( 'Y-m-d' );
            if ( $d >= $min && $d <= $max ) {
                $dates[] = [ 'date' => $d, 'title' => $title, 'color' => $color,
                             'type' => $volanta, 'url' => $url, 'img' => $img ];
            }
            $cur->modify( '+1 day' );
        }
    } elseif ( $tipo === 'intermitente' ) {
        $raw = get_post_meta( $post->ID, '_ru_fechas_intermitentes', true );
        $arr = json_decode( $raw, true ) ?: [];
        foreach ( $arr as $d ) {
            if ( $d >= $min && $d <= $max ) {
                $dates[] = [ 'date' => $d, 'title' => $title, 'color' => $color,
                             'type' => $volanta, 'url' => $url, 'img' => $img ];
            }
        }
    }

    return $dates;
}

$all_dates  = [];  // para el calendario (todas las fechas expandidas)
$next_events = []; // para el listado: primera fecha futura de cada post

$today_str = $now->format( 'Y-m-d' );

while ( $cal_query->have_posts() ) {
    $cal_query->the_post();
    $post = get_post();

    $expanded = turs_expand_event_dates( $post, $min_date, $max_date );
    foreach ( $expanded as $e ) {
        $all_dates[] = $e;
    }

    // Primera fecha futura (o hoy) para ordenar el listado
    $tipo    = get_post_meta( $post->ID, '_ru_fecha_tipo', true );
    $color   = get_post_meta( $post->ID, 'color', true ) ?: '#1a5c4c';
    $volanta = get_post_meta( $post->ID, 'hero_volanta', true );
    $img_url = get_the_post_thumbnail_url( $post->ID, 'large' ) ?: '';

    $next_date = null;
    if ( $tipo === 'unica' ) {
        $d = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        if ( $d >= $today_str ) $next_date = $d;
    } elseif ( $tipo === 'rango' ) {
        $d = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        $f = get_post_meta( $post->ID, '_ru_fecha_fin', true ) ?: $d;
        $next_date = $f >= $today_str ? max( $d, $today_str ) : null;
    } elseif ( $tipo === 'intermitente' ) {
        $raw = get_post_meta( $post->ID, '_ru_fechas_intermitentes', true );
        $arr = array_filter( json_decode( $raw, true ) ?: [], fn($x) => $x >= $today_str );
        if ( $arr ) $next_date = min( $arr );
    }

    if ( $next_date ) {
        $dt = new DateTime( $next_date );
        $next_events[] = [
            'date'      => $next_date,
            'day'       => $dt->format( 'd' ),
            'month_num' => (int) $dt->format( 'm' ),
            'year'      => (int) $dt->format( 'Y' ),
            'title'     => get_the_title(),
            'type'      => $volanta,
            'color'     => $color,
            'img'       => $img_url,
            'url'       => get_permalink(),
            'tipo'      => $tipo,
            'fin'       => $tipo === 'rango' ? get_post_meta( $post->ID, '_ru_fecha_fin', true ) : '',
        ];
    }
}
wp_reset_postdata();

// Ordenar por próxima fecha y tomar los primeros 4
usort( $next_events, fn( $a, $b ) => strcmp( $a['date'], $b['date'] ) );
$display = array_slice( $next_events, 0, 4 );

// JSON para el calendario (solo fechas únicas por día, priorizar el primer evento)
$cal_by_date = [];
foreach ( $all_dates as $e ) {
    if ( ! isset( $cal_by_date[ $e['date'] ] ) ) {
        $cal_by_date[ $e['date'] ] = $e;
    }
}
$cal_events = wp_json_encode( array_values( $cal_by_date ) );

$archive_url = get_term_link( 'calendario', 'categoria-ru' );
$archive_url = is_wp_error( $archive_url ) ? '#' : $archive_url;

$months_es = [ 1=>'ENE',2=>'FEB',3=>'MAR',4=>'ABR',5=>'MAY',6=>'JUN',
               7=>'JUL',8=>'AGO',9=>'SEP',10=>'OCT',11=>'NOV',12=>'DIC' ];
?>

<section id="eventos" aria-label="<?php esc_attr_e( 'Eventos y escapadas', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      </span>
      <h2 class="section-title__heading">Eventos y escapadas</h2>
    </div>
    <a href="<?php echo esc_url( $archive_url ); ?>" class="section-title__link">
      Ver todo
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="events-layout">

    <!-- Left: próximos eventos -->
    <div class="events-panel">
      <div class="events-list">
        <?php if ( empty( $display ) ) : ?>
          <p style="color:var(--c-gray-400);font-size:.875rem;padding:16px 0;">No hay eventos próximos.</p>
        <?php endif; ?>
        <?php foreach ( $display as $event ) : ?>
          <a href="<?php echo esc_url( $event['url'] ); ?>" class="event-card">

            <div class="event-card__img" style="--event-color:<?php echo esc_attr( $event['color'] ); ?>;">
              <?php if ( $event['img'] ) : ?>
                <img src="<?php echo esc_url( $event['img'] ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>" loading="lazy">
              <?php endif; ?>
              <div class="event-card__date">
                <?php if ( $event['tipo'] === 'rango' && $event['fin'] ) :
                  $fin_dt  = new DateTime( $event['fin'] );
                  $fin_day = $fin_dt->format( 'd' );
                  $fin_mon = $months_es[ (int) $fin_dt->format( 'm' ) ];
                ?>
                  <strong><?php echo esc_html( $event['day'] ); ?> – <?php echo esc_html( $fin_day ); ?></strong>
                  <span><?php echo esc_html( $months_es[ $event['month_num'] ] ); ?></span>
                <?php else : ?>
                  <strong><?php echo esc_html( $event['day'] ); ?></strong>
                  <span><?php echo esc_html( $months_es[ $event['month_num'] ] ); ?></span>
                <?php endif; ?>
              </div>
              <?php if ( $event['type'] ) : ?>
                <span class="event-card__type" style="background:<?php echo esc_attr( $event['color'] ); ?>;">
                  <?php echo esc_html( $event['type'] ); ?>
                </span>
              <?php endif; ?>
            </div>

            <div class="event-card__body">
              <h3 class="event-card__title"><?php echo esc_html( $event['title'] ); ?></h3>
            </div>

          </a>
        <?php endforeach; ?>
      </div>

      <a href="<?php echo esc_url( $archive_url ); ?>" class="events-cta">
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
