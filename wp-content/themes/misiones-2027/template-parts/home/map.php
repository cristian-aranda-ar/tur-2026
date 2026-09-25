<?php
/**
 * Template Part: Mapa Municipal — Explorar la provincia
 *
 * $args opcionales:
 *   perfil    (string) — IDs separados por coma: 'nature,adventure'
 *   buscar    (string) — texto inicial en el buscador
 *   localidad (string) — localidades separadas por coma
 *   titulo    (string) — título alternativo de la sección
 */

// Defaults del template que llama, sobreescribibles por URL
$init_perfil    = sanitize_text_field( wp_unslash( $_GET['perfil']    ?? $args['perfil']    ?? '' ) );
$init_buscar    = sanitize_text_field( wp_unslash( $_GET['buscar']    ?? $args['buscar']    ?? '' ) );
$init_localidad = sanitize_text_field( wp_unslash( $_GET['localidad'] ?? $args['localidad'] ?? '' ) );
$init_categoria = sanitize_text_field( wp_unslash( $_GET['categoria'] ?? $args['categoria'] ?? '' ) );
$section_titulo = $args['titulo'] ?? '';

$localities = [
  'Posadas', 'Puerto Iguazú', 'Oberá', 'Eldorado', 'Apóstoles',
  'San Ignacio', 'El Soberbio', 'Wanda', 'Montecarlo', 'Jardín América',
  'Leandro N. Alem', 'Aristóbulo del Valle', 'Campo Grande', 'Dos de Mayo',
  'San Pedro', 'Puerto Rico', 'Garupá', 'Candelaria', 'Santa Ana',
];

$regiones_filter = [
  [ 'label' => 'Norte',        'slug' => 'Norte'        ],
  [ 'label' => 'Centro',       'slug' => 'Centro'       ],
  [ 'label' => 'Sur',          'slug' => 'Sur'          ],
  [ 'label' => 'Alto Uruguay', 'slug' => 'Alto Uruguay' ],
];

$profiles = [
  [ 'id' => 'adventure', 'categoria' => 'aventura',  'label' => 'Aventura',   'color' => '#2d8653', 'icon' => '<path d="m8 3 4 8 5-5 5 15H2L8 3z"/>' ],
  [ 'id' => 'family',    'categoria' => 'familia',   'label' => 'Familia',    'color' => '#3b82f6', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>' ],
  [ 'id' => 'culture',   'categoria' => 'cultura',   'label' => 'Cultura',    'color' => '#a855f7', 'icon' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>' ],
  [ 'id' => 'gastro',    'categoria' => 'sabores',   'label' => 'Sabores',    'color' => '#f59e0b', 'icon' => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>' ],
  [ 'id' => 'nature',    'categoria' => 'naturaleza','label' => 'Naturaleza', 'color' => '#10b981', 'icon' => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>' ],
  [ 'id' => 'sports',    'categoria' => 'deportes',  'label' => 'Deportes',   'color' => '#ef4444', 'icon' => '<path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/>' ],
];

// POIs desde registro-unico con lat/lon válidos
$pois_query_args = [
  'post_type'      => 'registro-unico',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'no_found_rows'  => true,
];

// Filtrar por categoría si viene en args o URL
$categorias_slug = array_filter( array_map( 'sanitize_title', explode( ',', $init_categoria ) ) );
if ( $categorias_slug ) {
  $pois_query_args['tax_query'] = [ [
    'taxonomy' => 'categoria-ru',
    'field'    => 'slug',
    'terms'    => array_values( $categorias_slug ),
    'operator' => 'IN',
  ] ];
}

$pois_query = new WP_Query( $pois_query_args );
$pois_json  = '[]';
$pois       = [];

if ( $pois_query->have_posts() ) {
  while ( $pois_query->have_posts() ) {
    $pois_query->the_post();
    $lat = (float) get_post_meta( get_the_ID(), 'latitud',  true );
    $lon = (float) get_post_meta( get_the_ID(), 'longitud', true );

    $color   = get_post_meta( get_the_ID(), 'color',   true ) ?: '#1a5c4c';
    $resumen = get_post_meta( get_the_ID(), 'resumen', true ) ?: '';
    $ruta    = get_post_meta( get_the_ID(), 'ruta',    true ) ?: '';
    $acceso  = get_post_meta( get_the_ID(), 'acceso',  true ) ?: '';
    $region  = get_post_meta( get_the_ID(), 'region',  true ) ?: '';
    $wsp     = get_post_meta( get_the_ID(), 'whatsapp', true ) ?: '';
    $web     = get_post_meta( get_the_ID(), 'sitio_web', true ) ?: '';

    $dir_raw   = get_field( 'direccion', get_the_ID() );
    $dir_url   = is_array( $dir_raw ) ? ( $dir_raw['url'] ?? '' ) : (string) $dir_raw;
    $dir_label = is_array( $dir_raw ) ? ( $dir_raw['title'] ?: 'Ver en Maps' ) : (string) $dir_raw;

    $prest_raw = get_post_meta( get_the_ID(), 'Prestaciones', true );
    $prest_arr = [];
    if ( is_array( $prest_raw ) ) {
      foreach ( $prest_raw as $t ) {
        if ( is_object( $t ) ) { $prest_arr[] = $t->name; }
        elseif ( is_numeric( $t ) ) {
          $term = get_term( (int) $t );
          if ( $term && ! is_wp_error( $term ) ) $prest_arr[] = $term->name;
        } else { $prest_arr[] = (string) $t; }
      }
    } elseif ( is_string( $prest_raw ) && $prest_raw ) {
      $prest_arr = array_values( array_filter( preg_split( '/[\r\n]+/', $prest_raw ) ) );
    }

    $cat_terms = get_the_terms( get_the_ID(), 'categoria-ru' );
    $categorias = ( $cat_terms && ! is_wp_error( $cat_terms ) )
      ? wp_list_pluck( $cat_terms, 'slug' ) : [];

    $loc_terms   = get_the_terms( get_the_ID(), 'localidad' );
    $localidades = ( $loc_terms && ! is_wp_error( $loc_terms ) )
      ? wp_list_pluck( $loc_terms, 'name' ) : [];

    $rutas = $ruta
      ? array_values( array_filter( array_map( 'trim', explode( ',', $ruta ) ) ) )
      : [];

    $pois[] = [
      'id'          => get_the_ID(),
      'name'        => get_the_title(),
      'lat'         => $lat,
      'lon'         => $lon,
      'has_map'     => (bool) ( $lat && $lon ),
      'color'       => $color,
      'profile'     => $categorias[0] ?? '',
      'categorias'  => $categorias,
      'localidades' => $localidades,
      'rutas'       => $rutas,
      'resumen'     => wp_strip_all_tags( $resumen ),
      'url'         => get_permalink(),
      'image'       => get_the_post_thumbnail_url( null, 'thumbnail' ) ?: '',
      'image_md'    => get_the_post_thumbnail_url( null, 'medium' ) ?: '',
      'localidad'   => $localidades[0] ?? '',
      'region'      => $region,
      'acceso'      => ( $acceso && $acceso !== 'Indefinido' ) ? $acceso : '',
      'prestaciones'=> array_values( $prest_arr ),
      'whatsapp'    => $wsp,
      'sitio_web'   => $web,
      'dir_url'     => $dir_url,
      'dir_label'   => $dir_label,
    ];
  }
  wp_reset_postdata();
  $pois_json = wp_json_encode( $pois );
}
?>

<section id="explorar"
  aria-label="<?php esc_attr_e( 'Explorar la provincia', 'misiones-2027' ); ?>"
  data-init-perfil="<?php echo esc_attr( $init_perfil ); ?>"
  data-init-buscar="<?php echo esc_attr( $init_buscar ); ?>"
  data-init-localidad="<?php echo esc_attr( $init_localidad ); ?>"
  data-init-categoria="<?php echo esc_attr( $init_categoria ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
      </span>
      <h2 class="section-title__heading"><?php echo esc_html( $section_titulo ?: __( 'Explorá', 'misiones-2027' ) ); ?></h2>
    </div>
    <div id="map-active-filters" class="map-active-filters" aria-live="polite" aria-label="Filtros activos"></div>
  </div>

  <div class="municipal-map">
    <div class="municipal-map__card">

      <!-- ── Filtros ── -->
      <aside class="map-filter" aria-label="<?php esc_attr_e( 'Filtros', 'misiones-2027' ); ?>">

        <!-- Buscador -->
        <div class="map-filter__field">
          <label class="map-filter__label" for="map-search">Buscar</label>
          <div class="map-filter__search-wrap">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="search" id="map-search" class="map-filter__search" placeholder="<?php esc_attr_e( 'Nombre, actividad…', 'misiones-2027' ); ?>">
          </div>
        </div>

        <!-- Localidades -->
        <div class="map-filter__field">
          <label class="map-filter__label" for="map-locality-input"><?php esc_html_e( 'Localidades', 'misiones-2027' ); ?></label>
          <div class="map-filter__loc-row">
            <div class="map-tag-input" id="map-localities-wrap" data-localities="<?php echo esc_attr( json_encode( $localities ) ); ?>" role="combobox" aria-expanded="false" aria-haspopup="listbox">
              <div class="map-tag-input__inner">
                <div class="map-tag-input__tags" id="map-tags" aria-live="polite"></div>
                <input type="text" id="map-locality-input" class="map-tag-input__input" placeholder="<?php esc_attr_e( 'Buscar localidad…', 'misiones-2027' ); ?>" autocomplete="off" aria-autocomplete="list" aria-controls="map-dropdown">
              </div>
              <ul class="map-tag-input__dropdown" id="map-dropdown" role="listbox" aria-label="Localidades"></ul>
            </div>
            <button type="button" class="map-filter__advanced-toggle" aria-expanded="false" aria-label="<?php esc_attr_e( 'Búsqueda avanzada', 'misiones-2027' ); ?>">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            </button>
          </div>
        </div>

        <!-- Rutas -->
        <div class="map-filter__field map-filter__field--advanced">
          <span class="map-filter__label"><?php esc_html_e( 'Región', 'misiones-2027' ); ?></span>
          <div class="map-filter__regions">
            <?php foreach ( $regiones_filter as $reg ) : ?>
              <button type="button" class="map-region-btn" data-region="<?php echo esc_attr( $reg['slug'] ); ?>" aria-pressed="false">
                <?php echo esc_html( $reg['label'] ); ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Perfiles -->
        <div class="map-filter__field map-filter__field--advanced">
          <span class="map-filter__label"><?php esc_html_e( 'Tipo de experiencia', 'misiones-2027' ); ?></span>
          <div class="map-filter__profiles">
            <?php foreach ( $profiles as $p ) : ?>
              <button type="button" class="map-profile-btn" data-color="<?php echo esc_attr( $p['color'] ); ?>" data-profile="<?php echo esc_attr( $p['id'] ); ?>" data-categoria="<?php echo esc_attr( $p['categoria'] ); ?>" aria-pressed="false">
                <span class="map-profile-btn__icon" aria-hidden="true">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <?php echo $p['icon']; // PHPCS: XSS OK — hardcoded SVG paths ?>
                  </svg>
                </span>
                <span class="map-profile-btn__label"><?php echo esc_html( $p['label'] ); ?></span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Filtrar (desktop) -->
        <button type="button" class="map-filter__submit map-filter__submit--desktop">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
          <span class="map-filter__submit-label"><?php esc_html_e( 'Filtrar', 'misiones-2027' ); ?></span>
        </button>

      </aside>

      <!-- ── Mapa ── -->
      <div class="municipal-map__map">
        <div
          id="misiones-leaflet-map"
          data-pois="<?php echo esc_attr( $pois_json ); ?>"
          aria-label="<?php esc_attr_e( 'Mapa de la provincia de Misiones', 'misiones-2027' ); ?>"
        ></div>
      </div>

      <!-- ── Backdrop panel POI ── -->
      <div id="map-poi-backdrop" class="map-poi-backdrop"></div>

      <!-- ── Panel lateral POI ── -->
      <div id="map-poi-panel" class="map-poi-panel" aria-hidden="true" role="dialog" aria-label="Detalle del lugar">
        <button class="map-poi-panel__close" aria-label="Cerrar panel" type="button">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
        <?php if ( function_exists( 'turs_user_liked' ) ) : ?>
        <button class="map-poi-panel__fav js-turs-like" type="button"
          data-post-id="0" data-liked="false"
          aria-label="Agregar a ruta de viaje">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
        </button>
        <?php endif; ?>
        <div class="map-poi-panel__img"></div>
        <div class="map-poi-panel__body">
          <h3 class="map-poi-panel__name"></h3>
          <p class="map-poi-panel__meta"></p>
          <div class="map-poi-panel__info"></div>
          <div class="map-poi-panel__links"></div>
          <a class="map-poi-panel__cta" href="#" target="_self">Ver más
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
          </a>
        </div>
      </div>

      <!-- Filtrar (mobile) -->
      <div class="map-filter map-filter--mobile-submit">
        <button type="button" class="map-filter__submit map-filter__submit--mobile">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
          <span class="map-filter__submit-label"><?php esc_html_e( 'Filtrar', 'misiones-2027' ); ?></span>
        </button>
      </div>

    </div>
  </div>


</section>

<?php if ( ! empty( $pois ) ) : ?>
<section class="explorar-results" aria-label="<?php esc_attr_e( 'Resultados', 'misiones-2027' ); ?>">
  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </span>
      <h2 class="section-title__heading">
        <?php printf( esc_html__( '%d resultados', 'misiones-2027' ), count( $pois ) ); ?>
      </h2>
    </div>
  </div>
  <div class="explorar-results__grid">
    <?php foreach ( $pois as $poi ) :
      $poi_liked = function_exists( 'turs_user_liked' ) && turs_user_liked( $poi['id'] );
    ?>
    <div class="destino-card" data-poi-id="<?php echo esc_attr( $poi['id'] ); ?>" style="background:<?php echo esc_attr( $poi['color'] ); ?>;">
      <a href="<?php echo esc_url( $poi['url'] ); ?>" class="destino-card__link" aria-label="<?php echo esc_attr( $poi['name'] ); ?>"></a>
      <?php if ( $poi['image_md'] ) : ?>
        <img class="destino-card__img" src="<?php echo esc_url( $poi['image_md'] ); ?>" alt="<?php echo esc_attr( $poi['name'] ); ?>" loading="lazy">
      <?php endif; ?>
      <div class="destino-card__overlay"></div>
      <button
        class="destino-card__fav js-turs-like<?php echo $poi_liked ? ' is-liked' : ''; ?>"
        data-post-id="<?php echo esc_attr( $poi['id'] ); ?>"
        data-liked="<?php echo $poi_liked ? 'true' : 'false'; ?>"
        aria-label="<?php echo esc_attr( sprintf( __( 'Guardar %s en favoritos', 'misiones-2027' ), $poi['name'] ) ); ?>"
      >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/>
          <circle cx="12" cy="10" r="3"/>
        </svg>
      </button>
      <div class="destino-card__info">
        <h3 class="destino-card__name"><?php echo esc_html( $poi['name'] ); ?></h3>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
