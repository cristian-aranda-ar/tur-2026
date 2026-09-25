<?php
/**
 * Taxonomy archive: categoria-ru → calendario
 * URL: /categoria-ru/calendario/
 */

get_header();

// ── Filtros ─────────────────────────────────────────────────────────
$filtro_titulo = sanitize_text_field( $_GET['buscar'] ?? '' );
$filtro_mes    = sanitize_text_field( $_GET['mes'] ?? '' );   // formato YYYY-MM

// ── Meses disponibles: actual + 2 siguientes ──────────────────────
$now   = new DateTime();
$meses = [];
for ( $i = 0; $i <= 2; $i++ ) {
    $dt      = ( clone $now )->modify( "+$i months" );
    $key     = $dt->format( 'Y-m' );
    $label   = ucfirst( $dt->format( 'F Y' ) );
    $meses[] = [ 'key' => $key, 'label' => $label ];
}

// ── WP_Query con filtros ──────────────────────────────────────────
$args = [
    'post_type'      => 'registro-unico',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => [ [
        'taxonomy' => 'categoria-ru',
        'field'    => 'slug',
        'terms'    => 'calendario',
    ] ],
];
if ( $filtro_titulo ) {
    $args['s'] = $filtro_titulo;
}

$loop = new WP_Query( $args );
$posts_raw = $loop->posts;
wp_reset_postdata();

// ── Filtrar por mes si corresponde ────────────────────────────────
$months_es = [ 1=>'ENE',2=>'FEB',3=>'MAR',4=>'ABR',5=>'MAY',6=>'JUN',
               7=>'JUL',8=>'AGO',9=>'SEP',10=>'OCT',11=>'NOV',12=>'DIC' ];

function turs_post_dates_in_month( WP_Post $post, string $mes ): array {
    $tipo   = get_post_meta( $post->ID, '_ru_fecha_tipo', true );
    $result = [];
    if ( $tipo === 'unica' ) {
        $d = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        if ( $d && str_starts_with( $d, $mes ) ) $result[] = $d;
    } elseif ( $tipo === 'rango' ) {
        $ini = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        $fin = get_post_meta( $post->ID, '_ru_fecha_fin', true ) ?: $ini;
        if ( str_starts_with( $ini, $mes ) || str_starts_with( $fin, $mes ) ) {
            $result = [ $ini, $fin ];
        }
    } elseif ( $tipo === 'intermitente' ) {
        $arr = json_decode( get_post_meta( $post->ID, '_ru_fechas_intermitentes', true ), true ) ?: [];
        $result = array_filter( $arr, fn($d) => str_starts_with( $d, $mes ) );
    }
    return $result;
}

function turs_post_next_date( WP_Post $post ): ?string {
    $tipo  = get_post_meta( $post->ID, '_ru_fecha_tipo', true );
    $today = ( new DateTime() )->format( 'Y-m-d' );
    if ( $tipo === 'unica' ) {
        $d = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
        return $d >= $today ? $d : null;
    } elseif ( $tipo === 'rango' ) {
        $f = get_post_meta( $post->ID, '_ru_fecha_fin', true );
        return $f >= $today ? max( get_post_meta( $post->ID, '_ru_fecha_inicio', true ), $today ) : null;
    } elseif ( $tipo === 'intermitente' ) {
        $arr = array_filter(
            json_decode( get_post_meta( $post->ID, '_ru_fechas_intermitentes', true ), true ) ?: [],
            fn($d) => $d >= $today
        );
        return $arr ? min( $arr ) : null;
    }
    return null;
}

// Si hay filtro de mes, quedarse solo con posts que tengan fecha en ese mes
if ( $filtro_mes ) {
    $posts_raw = array_filter( $posts_raw, fn( $p ) => ! empty( turs_post_dates_in_month( $p, $filtro_mes ) ) );
}

// Ordenar por próxima fecha
usort( $posts_raw, function( $a, $b ) {
    $da = turs_post_next_date( $a );
    $db = turs_post_next_date( $b );
    if ( ! $da && ! $db ) return 0;
    if ( ! $da ) return 1;
    if ( ! $db ) return -1;
    return strcmp( $da, $db );
} );

?>

<main id="main" class="cal-archive" role="main">

  <!-- Hero -->
  <div class="cal-archive__hero">
    <div class="cal-archive__hero-inner">
      <div class="cal-archive__hero-icon" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
      </div>
      <h1 class="cal-archive__title">Calendario de eventos</h1>
      <p class="cal-archive__sub">Descubrí los próximos eventos y experiencias en Misiones</p>
    </div>
  </div>

  <!-- Filtros -->
  <div class="cal-archive__filters">
    <form class="cal-filters-form" method="get" action="">
      <div class="cal-filter-group">
        <label class="cal-filter-label" for="cal-buscar">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          Buscar
        </label>
        <input type="text" id="cal-buscar" name="buscar" class="cal-filter-input"
               placeholder="Nombre del evento…"
               value="<?php echo esc_attr( $filtro_titulo ); ?>">
      </div>

      <div class="cal-filter-group">
        <label class="cal-filter-label" for="cal-mes">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Mes
        </label>
        <select id="cal-mes" name="mes" class="cal-filter-select">
          <option value="">Todos los meses</option>
          <?php foreach ( $meses as $m ) : ?>
            <option value="<?php echo esc_attr( $m['key'] ); ?>" <?php selected( $filtro_mes, $m['key'] ); ?>>
              <?php echo esc_html( $m['label'] ); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="cal-filter-actions">
        <button type="submit" class="cal-filter-btn cal-filter-btn--search">Filtrar</button>
        <?php if ( $filtro_titulo || $filtro_mes ) : ?>
          <a href="<?php echo esc_url( get_term_link( 'calendario', 'categoria-ru' ) ); ?>" class="cal-filter-btn cal-filter-btn--clear">Limpiar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Resultados -->
  <div class="cal-archive__body">

    <?php if ( empty( $posts_raw ) ) : ?>
      <div class="cal-archive__empty">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        <p>No se encontraron eventos<?php echo $filtro_mes ? ' para el mes seleccionado' : ''; ?>.</p>
        <a href="<?php echo esc_url( get_term_link( 'calendario', 'categoria-ru' ) ); ?>">Ver todos los eventos</a>
      </div>

    <?php else : ?>
      <div class="cal-archive__count">
        <?php echo count( $posts_raw ); ?> evento<?php echo count( $posts_raw ) !== 1 ? 's' : ''; ?> encontrado<?php echo count( $posts_raw ) !== 1 ? 's' : ''; ?>
      </div>

      <div class="cal-archive__grid">
        <?php foreach ( $posts_raw as $post ) :
          setup_postdata( $post );
          $color   = get_post_meta( $post->ID, 'color', true ) ?: '#1a5c4c';
          $color_s = misiones2027_hex_rgba( $color, 0.10 );
          $volanta = get_post_meta( $post->ID, 'hero_volanta', true );
          $tipo    = get_post_meta( $post->ID, '_ru_fecha_tipo', true );
          $thumb   = get_the_post_thumbnail_url( $post->ID, 'large' );
          $next    = turs_post_next_date( $post );

          // Etiqueta de fecha para la card
          $fecha_label = '';
          if ( $tipo === 'unica' ) {
            $d = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
            if ( $d ) {
              $dt = new DateTime( $d );
              $fecha_label = $dt->format( 'd' ) . ' ' . $months_es[ (int) $dt->format( 'm' ) ] . ' ' . $dt->format( 'Y' );
            }
          } elseif ( $tipo === 'rango' ) {
            $ini = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
            $fin = get_post_meta( $post->ID, '_ru_fecha_fin', true );
            if ( $ini && $fin ) {
              $dti = new DateTime( $ini );
              $dtf = new DateTime( $fin );
              $fecha_label = $dti->format( 'd' ) . ' – ' . $dtf->format( 'd ' ) . $months_es[ (int) $dtf->format( 'm' ) ] . ' ' . $dtf->format( 'Y' );
            }
          } elseif ( $tipo === 'intermitente' ) {
            $arr = json_decode( get_post_meta( $post->ID, '_ru_fechas_intermitentes', true ), true ) ?: [];
            if ( $arr ) {
              sort( $arr );
              $dt = new DateTime( $arr[0] );
              $fecha_label = count( $arr ) . ' fechas · desde ' . $dt->format( 'd ' ) . $months_es[ (int) $dt->format( 'm' ) ] . ' ' . $dt->format( 'Y' );
            }
          }
        ?>
          <a href="<?php the_permalink(); ?>" class="cal-event-card"
             style="--ev-color:<?php echo esc_attr( $color ); ?>;--ev-color-soft:<?php echo esc_attr( $color_s ); ?>;">

            <div class="cal-event-card__img" style="background:<?php echo esc_attr( $color ); ?>;">
              <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
              <?php endif; ?>
              <div class="cal-event-card__overlay"></div>
              <?php if ( $volanta ) : ?>
                <span class="cal-event-card__type" style="background:<?php echo esc_attr( $color ); ?>;">
                  <?php echo esc_html( $volanta ); ?>
                </span>
              <?php endif; ?>
              <?php if ( ! $next ) : ?>
                <span class="cal-event-card__past">Finalizado</span>
              <?php endif; ?>
            </div>

            <div class="cal-event-card__body" style="border-top:3px solid <?php echo esc_attr( $color ); ?>;">
              <h2 class="cal-event-card__title"><?php the_title(); ?></h2>
              <?php if ( $fecha_label ) : ?>
                <div class="cal-event-card__fecha" style="color:<?php echo esc_attr( $color ); ?>;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                  <?php echo esc_html( $fecha_label ); ?>
                </div>
              <?php endif; ?>
            </div>

          </a>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    <?php endif; ?>

  </div><!-- /.cal-archive__body -->

</main>

<?php get_footer(); ?>
