<?php
/**
 * Geocodificador de Registros Únicos
 * Busca lat/lon por título o dirección usando Nominatim (OpenStreetMap).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RU_Geocoder {

	public function __construct() {
		add_action( 'admin_menu',              [ $this, 'register_menu' ] );
		add_action( 'wp_ajax_ru_geo_get_posts', [ $this, 'ajax_get_posts' ] );
		add_action( 'wp_ajax_ru_geo_single',    [ $this, 'ajax_geocode_single' ] );
		add_action( 'wp_ajax_ru_geo_save',      [ $this, 'ajax_save' ] );
	}

	// ── Menú ─────────────────────────────────────────────────────────────────

	public function register_menu() {
		add_submenu_page(
			'edit.php?post_type=registro-unico',
			'Geocodificador',
			'Geocodificador',
			'manage_options',
			'ru-geocoder',
			[ $this, 'render_page' ]
		);
	}

	// ── Página admin ──────────────────────────────────────────────────────────

	public function render_page() {
		$categories = get_terms( [ 'taxonomy' => 'categoria-ru', 'hide_empty' => false ] );
		$nonce      = wp_create_nonce( 'ru_geocoder' );
		?>
		<div class="wrap" id="ru-geocoder-wrap">
			<h1 style="display:flex;align-items:center;gap:10px;">
				<span class="dashicons dashicons-location" style="font-size:28px;color:#1a5c4c;"></span>
				Geocodificador de Registros Únicos
			</h1>
			<p style="color:#646970;margin-bottom:20px;">
				Completa los campos <strong>latitud</strong> y <strong>longitud</strong> buscando por título del registro en Nominatim (OpenStreetMap). Si no hay resultado, intenta con la dirección.
			</p>

			<!-- Configuración -->
			<div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:20px 24px;max-width:700px;margin-bottom:24px;">
				<table class="form-table" style="max-width:600px;">
					<tr>
						<th><label for="geo-cat">Categoría</label></th>
						<td>
							<select id="geo-cat" class="regular-text">
								<option value="">— Todas las categorías —</option>
								<?php if ( ! is_wp_error( $categories ) ) foreach ( $categories as $cat ) : ?>
									<option value="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Registros a procesar</th>
						<td>
							<label style="display:block;margin-bottom:6px;">
								<input type="radio" name="geo-scope" value="sin_coords" checked>
								Solo registros <strong>sin coordenadas</strong>
							</label>
							<label>
								<input type="radio" name="geo-scope" value="todos">
								Todos (reemplaza coordenadas existentes)
							</label>
						</td>
					</tr>
					<tr>
						<th>Contexto geográfico</th>
						<td>
							<input type="text" id="geo-context" value="Misiones, Argentina" class="regular-text">
							<p class="description">Se agrega al final de cada búsqueda para mejorar resultados.</p>
						</td>
					</tr>
				</table>
				<button type="button" id="geo-load-btn" class="button button-primary">
					Cargar registros
				</button>
			</div>

			<!-- Tabla de resultados -->
			<div id="geo-results" style="display:none;max-width:960px;">
				<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:12px;flex-wrap:wrap;">
					<div id="geo-summary" style="font-weight:600;color:#1d2327;"></div>
					<div style="display:flex;gap:8px;">
						<button type="button" id="geo-run-btn" class="button button-primary" disabled>
							▶ Geocodificar todos
						</button>
						<button type="button" id="geo-save-btn" class="button" disabled>
							💾 Guardar seleccionados
						</button>
						<button type="button" id="geo-stop-btn" class="button" style="display:none;">
							⏹ Detener
						</button>
					</div>
				</div>

				<div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;overflow:auto;">
					<table class="widefat" id="geo-table">
						<thead>
							<tr>
								<th style="width:32px;"><input type="checkbox" id="geo-check-all" title="Seleccionar todos"></th>
								<th>Registro</th>
								<th>Búsqueda usada</th>
								<th>Lat</th>
								<th>Lon</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody id="geo-tbody"></tbody>
					</table>
				</div>

				<p style="margin-top:12px;color:#646970;font-size:12px;">
					Solo se guardan las filas seleccionadas con resultado encontrado.
				</p>
			</div>

			<div id="geo-loading" style="display:none;padding:16px 0;color:#646970;">
				<span class="spinner is-active" style="float:none;margin:0 8px 0 0;"></span> Cargando registros…
			</div>
		</div>

		<script>
		(function() {
			var nonce   = <?php echo wp_json_encode( $nonce ); ?>;
			var ajaxUrl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
			var posts   = [];
			var running = false;
			var stopFlag = false;

			var loadBtn   = document.getElementById('geo-load-btn');
			var runBtn    = document.getElementById('geo-run-btn');
			var saveBtn   = document.getElementById('geo-save-btn');
			var stopBtn   = document.getElementById('geo-stop-btn');
			var tbody     = document.getElementById('geo-tbody');
			var summary   = document.getElementById('geo-summary');
			var results   = document.getElementById('geo-results');
			var loading   = document.getElementById('geo-loading');
			var checkAll  = document.getElementById('geo-check-all');

			// ── Cargar lista ──────────────────────────────────────────────────
			loadBtn.addEventListener('click', function() {
				var cat   = document.getElementById('geo-cat').value;
				var scope = document.querySelector('input[name="geo-scope"]:checked').value;

				loading.style.display = '';
				results.style.display = 'none';
				loadBtn.disabled = true;

				var fd = new FormData();
				fd.append('action',  'ru_geo_get_posts');
				fd.append('nonce',   nonce);
				fd.append('cat',     cat);
				fd.append('scope',   scope);

				fetch(ajaxUrl, { method: 'POST', body: fd })
					.then(function(r) { return r.json(); })
					.then(function(res) {
						loading.style.display = 'none';
						loadBtn.disabled = false;
						if (!res.success) { alert(res.data || 'Error al cargar.'); return; }
						posts = res.data;
						renderTable();
						results.style.display = '';
						runBtn.disabled = posts.length === 0;
						summary.textContent = posts.length + ' registro' + (posts.length !== 1 ? 's' : '') + ' encontrado' + (posts.length !== 1 ? 's' : '');
					})
					.catch(function() { loading.style.display = 'none'; loadBtn.disabled = false; alert('Error de red.'); });
			});

			// ── Render tabla ──────────────────────────────────────────────────
			function renderTable() {
				tbody.innerHTML = '';
				posts.forEach(function(p) {
					var tr = document.createElement('tr');
					tr.id  = 'geo-row-' + p.id;
					tr.innerHTML =
						'<td><input type="checkbox" class="geo-row-check" data-id="' + p.id + '"></td>' +
						'<td><strong>' + escHtml(p.title) + '</strong><br><small style="color:#646970">#' + p.id + '</small></td>' +
						'<td class="geo-query" style="font-size:12px;color:#646970;">—</td>' +
						'<td class="geo-lat" style="font-size:12px;">—</td>' +
						'<td class="geo-lon" style="font-size:12px;">—</td>' +
						'<td class="geo-status"><span style="color:#646970">Pendiente</span></td>';
					tbody.appendChild(tr);
				});
			}

			// ── Geocodificar todos ────────────────────────────────────────────
			runBtn.addEventListener('click', function() {
				if (running) return;
				running  = true;
				stopFlag = false;
				runBtn.disabled  = true;
				saveBtn.disabled = true;
				stopBtn.style.display = '';

				var context = document.getElementById('geo-context').value.trim();
				var idx = 0;

				function next() {
					if (stopFlag || idx >= posts.length) {
						running = false;
						runBtn.disabled  = false;
						saveBtn.disabled = false;
						stopBtn.style.display = 'none';
						var found = posts.filter(function(p) { return p.lat; }).length;
						summary.textContent = found + ' de ' + posts.length + ' geocodificados';
						return;
					}

					var p   = posts[idx];
					var row = document.getElementById('geo-row-' + p.id);
					row.querySelector('.geo-status').innerHTML = '<span style="color:#2271b1">Buscando…</span>';

					var fd = new FormData();
					fd.append('action',  'ru_geo_single');
					fd.append('nonce',   nonce);
					fd.append('post_id', p.id);
					fd.append('context', context);

					fetch(ajaxUrl, { method: 'POST', body: fd })
						.then(function(r) { return r.json(); })
						.then(function(res) {
							if (res.success && res.data.lat) {
								p.lat   = res.data.lat;
								p.lon   = res.data.lon;
								p.query = res.data.query;
								row.querySelector('.geo-query').textContent  = res.data.query;
								row.querySelector('.geo-lat').textContent    = parseFloat(res.data.lat).toFixed(6);
								row.querySelector('.geo-lon').textContent    = parseFloat(res.data.lon).toFixed(6);
								row.querySelector('.geo-status').innerHTML   = '<span style="color:#00a32a">✓ Encontrado</span>';
								row.querySelector('.geo-row-check').checked  = true;
							} else {
								row.querySelector('.geo-query').textContent  = res.data?.query || '—';
								row.querySelector('.geo-status').innerHTML   = '<span style="color:#d63638">✗ No encontrado</span>';
							}
							idx++;
							// Nominatim: mínimo 1.1s entre requests
							setTimeout(next, 1100);
						})
						.catch(function() {
							row.querySelector('.geo-status').innerHTML = '<span style="color:#d63638">Error</span>';
							idx++;
							setTimeout(next, 1100);
						});
				}

				next();
			});

			// ── Detener ───────────────────────────────────────────────────────
			stopBtn.addEventListener('click', function() { stopFlag = true; });

			// ── Guardar seleccionados ─────────────────────────────────────────
			saveBtn.addEventListener('click', function() {
				var toSave = [];
				document.querySelectorAll('.geo-row-check:checked').forEach(function(cb) {
					var id  = parseInt(cb.dataset.id, 10);
					var poi = posts.find(function(p) { return p.id === id; });
					if (poi && poi.lat) toSave.push({ id: poi.id, lat: poi.lat, lon: poi.lon });
				});
				if (!toSave.length) { alert('No hay registros seleccionados con coordenadas.'); return; }
				if (!confirm('Guardar coordenadas en ' + toSave.length + ' registro(s)?')) return;

				saveBtn.disabled = true;
				var fd = new FormData();
				fd.append('action',  'ru_geo_save');
				fd.append('nonce',   nonce);
				fd.append('data',    JSON.stringify(toSave));

				fetch(ajaxUrl, { method: 'POST', body: fd })
					.then(function(r) { return r.json(); })
					.then(function(res) {
						saveBtn.disabled = false;
						if (res.success) {
							alert('✓ ' + res.data + ' registro(s) actualizados.');
							toSave.forEach(function(item) {
								var row = document.getElementById('geo-row-' + item.id);
								if (row) row.style.background = '#f0fdf4';
							});
						} else {
							alert('Error: ' + (res.data || 'desconocido'));
						}
					})
					.catch(function() { saveBtn.disabled = false; alert('Error de red.'); });
			});

			// ── Check all ─────────────────────────────────────────────────────
			checkAll.addEventListener('change', function() {
				document.querySelectorAll('.geo-row-check').forEach(function(cb) {
					var id  = parseInt(cb.dataset.id, 10);
					var poi = posts.find(function(p) { return p.id === id; });
					if (poi && poi.lat) cb.checked = checkAll.checked;
				});
			});

			function escHtml(s) {
				return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
			}
		})();
		</script>
		<?php
	}

	// ── AJAX: cargar posts ────────────────────────────────────────────────────

	public function ajax_get_posts() {
		check_ajax_referer( 'ru_geocoder', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'No autorizado' );

		$cat   = sanitize_key( $_POST['cat']   ?? '' );
		$scope = sanitize_key( $_POST['scope'] ?? 'sin_coords' );

		$args = [
			'post_type'      => 'registro-unico',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'fields'         => 'ids',
		];

		if ( $scope === 'sin_coords' ) {
			$args['meta_query'] = [
				'relation' => 'OR',
				[ 'key' => 'latitud',  'value' => '', 'compare' => '=' ],
				[ 'key' => 'latitud',  'compare' => 'NOT EXISTS' ],
			];
		}

		if ( $cat ) {
			$args['tax_query'] = [ [
				'taxonomy' => 'categoria-ru',
				'field'    => 'slug',
				'terms'    => $cat,
			] ];
		}

		$ids  = get_posts( $args );
		$data = [];

		foreach ( $ids as $id ) {
			$dir_raw  = get_field( 'direccion', $id );
			$dir      = is_array( $dir_raw ) ? ( $dir_raw['url'] ?? '' ) : (string) $dir_raw;

			$data[] = [
				'id'        => $id,
				'title'     => get_the_title( $id ),
				'direccion' => $dir,
				'lat'       => null,
				'lon'       => null,
			];
		}

		wp_send_json_success( $data );
	}

	// ── AJAX: geocodificar un post ────────────────────────────────────────────

	public function ajax_geocode_single() {
		check_ajax_referer( 'ru_geocoder', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'No autorizado' );

		$post_id = (int) ( $_POST['post_id'] ?? 0 );
		$context = sanitize_text_field( $_POST['context'] ?? 'Misiones, Argentina' );
		if ( ! $post_id ) wp_send_json_error( 'ID inválido' );

		$title   = get_the_title( $post_id );
		$dir_raw = get_field( 'direccion', $post_id );
		$dir     = is_array( $dir_raw ) ? ( $dir_raw['url'] ?? '' ) : (string) $dir_raw;
		// Si la dirección es una URL de Google Maps, no sirve como query de texto
		if ( str_starts_with( $dir, 'http' ) ) $dir = '';

		// 1. Buscar por título
		$result = $this->geocode( $title, $context );

		// 2. Fallback: dirección
		if ( ! $result && $dir ) {
			$result = $this->geocode( $dir, $context );
			if ( $result ) $result['query'] = $dir . ' (dirección)';
		} else {
			if ( $result ) $result['query'] = $title . ' (título)';
		}

		if ( $result ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( [ 'query' => $title ] );
		}
	}

	// ── AJAX: guardar coordenadas ─────────────────────────────────────────────

	public function ajax_save() {
		check_ajax_referer( 'ru_geocoder', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'No autorizado' );

		$raw  = sanitize_text_field( $_POST['data'] ?? '' );
		$data = json_decode( stripslashes( $raw ), true );
		if ( ! is_array( $data ) ) wp_send_json_error( 'Datos inválidos' );

		$saved = 0;
		foreach ( $data as $item ) {
			$id  = (int) ( $item['id']  ?? 0 );
			$lat = (float) ( $item['lat'] ?? 0 );
			$lon = (float) ( $item['lon'] ?? 0 );
			if ( ! $id || ! $lat || ! $lon ) continue;
			update_post_meta( $id, 'latitud',  $lat );
			update_post_meta( $id, 'longitud', $lon );
			$saved++;
		}

		wp_send_json_success( $saved );
	}

	// ── Geocodificar vía Nominatim ────────────────────────────────────────────

	private function geocode( $query, $context = 'Misiones, Argentina' ) {
		$full = trim( $query . ', ' . $context );
		$url  = 'https://nominatim.openstreetmap.org/search?' . http_build_query( [
			'q'            => $full,
			'format'       => 'json',
			'limit'        => 1,
			'countrycodes' => 'ar',
			'addressdetails' => 0,
		] );

		$response = wp_remote_get( $url, [
			'timeout' => 10,
			'headers' => [ 'User-Agent' => 'Turismo-Misiones-Geocoder/1.0 (admin)' ],
		] );

		if ( is_wp_error( $response ) ) return null;

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body[0] ) ) return null;

		return [
			'lat'   => $body[0]['lat'],
			'lon'   => $body[0]['lon'],
			'query' => $query,
		];
	}
}

new RU_Geocoder();
