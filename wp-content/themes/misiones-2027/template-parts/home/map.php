<?php
/**
 * Template Part: Mapa Municipal — Explorar la provincia
 */

$localities = [
  'Posadas', 'Puerto Iguazú', 'Oberá', 'Eldorado', 'Apóstoles',
  'San Ignacio', 'El Soberbio', 'Wanda', 'Montecarlo', 'Jardín América',
  'Leandro N. Alem', 'Aristóbulo del Valle', 'Campo Grande', 'Dos de Mayo',
  'San Pedro', 'Puerto Rico', 'Garupá', 'Candelaria', 'Santa Ana',
];

$rutas = [
  [ 'num' => '12',  'tipo' => 'RN' ],
  [ 'num' => '14',  'tipo' => 'RN' ],
  [ 'num' => '115', 'tipo' => 'RN' ],
  [ 'num' => '2',   'tipo' => 'RP' ],
  [ 'num' => '17',  'tipo' => 'RP' ],
  [ 'num' => '19',  'tipo' => 'RP' ],
];

$profiles = [
  [ 'id' => 'adventure', 'label' => 'Aventura',   'color' => '#2d8653', 'icon' => '<path d="m8 3 4 8 5-5 5 15H2L8 3z"/>' ],
  [ 'id' => 'family',    'label' => 'Familia',    'color' => '#3b82f6', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>' ],
  [ 'id' => 'culture',   'label' => 'Cultura',    'color' => '#a855f7', 'icon' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>' ],
  [ 'id' => 'gastro',    'label' => 'Sabores',    'color' => '#f59e0b', 'icon' => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>' ],
  [ 'id' => 'nature',    'label' => 'Naturaleza', 'color' => '#10b981', 'icon' => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>' ],
  [ 'id' => 'sports',    'label' => 'Deportes',   'color' => '#ef4444', 'icon' => '<path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/>' ],
];

// Profile color map for POIs
$profile_colors = array_column( $profiles, 'color', 'id' );

// POIs — lat/lon hardcoded, profile links to filter
$pois = [
  [ 'name' => 'Cataratas del Iguazú',    'lat' => -25.69, 'lon' => -54.44, 'profile' => 'adventure' ],
  [ 'name' => 'Puerto Iguazú',            'lat' => -25.60, 'lon' => -54.57, 'profile' => 'family'    ],
  [ 'name' => 'Wanda',                    'lat' => -25.97, 'lon' => -54.56, 'profile' => 'nature'     ],
  [ 'name' => 'Eldorado',                 'lat' => -26.40, 'lon' => -54.64, 'profile' => 'nature'     ],
  [ 'name' => 'Saltos del Moconá',        'lat' => -27.15, 'lon' => -53.88, 'profile' => 'adventure'  ],
  [ 'name' => 'El Soberbio',              'lat' => -27.30, 'lon' => -54.18, 'profile' => 'nature'     ],
  [ 'name' => 'San Ignacio Mini',         'lat' => -27.26, 'lon' => -55.53, 'profile' => 'culture'    ],
  [ 'name' => 'Salto Encantado',          'lat' => -27.07, 'lon' => -55.00, 'profile' => 'adventure'  ],
  [ 'name' => 'Oberá',                    'lat' => -27.49, 'lon' => -55.12, 'profile' => 'gastro'     ],
  [ 'name' => 'Posadas',                  'lat' => -27.37, 'lon' => -55.90, 'profile' => 'culture'    ],
  [ 'name' => 'Apóstoles',                'lat' => -27.91, 'lon' => -55.76, 'profile' => 'gastro'     ],
  [ 'name' => 'San Pedro',                'lat' => -26.62, 'lon' => -54.12, 'profile' => 'nature'     ],
];

// Encode POIs for JS
$pois_json = json_encode( array_map( function( $poi ) use ( $profile_colors ) {
  return [
    'name'    => $poi['name'],
    'lat'     => $poi['lat'],
    'lon'     => $poi['lon'],
    'profile' => $poi['profile'],
    'color'   => $profile_colors[ $poi['profile'] ] ?? '#1a5c4c',
  ];
}, $pois ) );
?>

<section id="explorar" aria-label="<?php esc_attr_e( 'Explorar la provincia', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
      </span>
      <h2 class="section-title__heading">Explorá la provincia</h2>
    </div>
    <a href="#" class="section-title__link">
      Ver mapa
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </a>
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
          <div class="map-tag-input" id="map-localities-wrap" data-localities="<?php echo esc_attr( json_encode( $localities ) ); ?>" role="combobox" aria-expanded="false" aria-haspopup="listbox">
            <div class="map-tag-input__inner">
              <div class="map-tag-input__tags" id="map-tags" aria-live="polite"></div>
              <input type="text" id="map-locality-input" class="map-tag-input__input" placeholder="<?php esc_attr_e( 'Buscar localidad…', 'misiones-2027' ); ?>" autocomplete="off" aria-autocomplete="list" aria-controls="map-dropdown">
            </div>
            <ul class="map-tag-input__dropdown" id="map-dropdown" role="listbox" aria-label="Localidades"></ul>
          </div>
        </div>

        <!-- Rutas -->
        <div class="map-filter__field">
          <span class="map-filter__label"><?php esc_html_e( 'Rutas', 'misiones-2027' ); ?></span>
          <div class="map-filter__routes">
            <?php foreach ( $rutas as $ruta ) : ?>
              <button type="button" class="map-route-btn" data-ruta="<?php echo esc_attr( $ruta['num'] ); ?>" aria-pressed="false">
                <span class="map-route-btn__prefix"><?php echo esc_html( $ruta['tipo'] ); ?></span>
                <span class="map-route-btn__num"><?php echo esc_html( $ruta['num'] ); ?></span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Perfiles -->
        <div class="map-filter__field">
          <span class="map-filter__label"><?php esc_html_e( 'Tipo de experiencia', 'misiones-2027' ); ?></span>
          <div class="map-filter__profiles">
            <?php foreach ( $profiles as $p ) : ?>
              <button type="button" class="map-profile-btn" data-color="<?php echo esc_attr( $p['color'] ); ?>" data-profile="<?php echo esc_attr( $p['id'] ); ?>" aria-pressed="false">
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

        <!-- Filtrar -->
        <button type="button" class="map-filter__submit">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          <?php esc_html_e( 'Filtrar', 'misiones-2027' ); ?>
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

    </div>
  </div>

  <a href="#" class="section-cta">
    Ver mapa completo
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
  </a>

</section>
