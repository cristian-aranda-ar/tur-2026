<?php
/**
 * Normalizador de Prestaciones
 * Convierte JSON arrays en texto limpio (una prestación por línea, primera letra mayúscula).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RU_Prestaciones_Normalizer {

	public function __construct() {
		add_action( 'admin_menu',                      [ $this, 'register_menu' ] );
		add_action( 'wp_ajax_ru_prest_get_posts',      [ $this, 'ajax_get_posts' ] );
		add_action( 'wp_ajax_ru_prest_save',           [ $this, 'ajax_save' ] );
	}

	// ── Menú ─────────────────────────────────────────────────────────────────

	public function register_menu() {
		add_submenu_page(
			'edit.php?post_type=registro-unico',
			'Normalizar Prestaciones',
			'Normalizar Prestaciones',
			'manage_options',
			'ru-prestaciones',
			[ $this, 'render_page' ]
		);
	}

	// ── Normalización ─────────────────────────────────────────────────────────

	private function normalize( $raw ) {
		if ( ! is_string( $raw ) || ! trim( $raw ) ) return '';

		$trimmed = trim( $raw );
		$items   = [];

		// Intentar decodificar como JSON array
		if ( str_starts_with( $trimmed, '[' ) ) {
			$decoded = json_decode( $trimmed, true );
			if ( is_array( $decoded ) ) {
				$items = $decoded;
			}
		}

		// Fallback: separar por saltos de línea o comas
		if ( empty( $items ) ) {
			$items = array_filter( preg_split( '/[\r\n,]+/', $trimmed ) );
		}

		$normalized = [];
		foreach ( $items as $item ) {
			$item = trim( $item, " \t\n\r\0\x0B\"'[]" );
			if ( ! $item ) continue;

			// Convertir escapes unicode sin backslash: u00f1 → ñ
			$item = preg_replace_callback(
				'/(?<!\\\\)u([0-9a-fA-F]{4})/',
				function ( $m ) {
					return mb_chr( hexdec( $m[1] ), 'UTF-8' );
				},
				$item
			);

			// Primera letra mayúscula (multibyte)
			$item = mb_strtoupper( mb_substr( $item, 0, 1 ) ) . mb_substr( $item, 1 );

			$normalized[] = $item;
		}

		// Eliminar duplicados conservando orden
		$normalized = array_values( array_unique( $normalized ) );

		return implode( "\n", $normalized );
	}

	// ── Página admin ──────────────────────────────────────────────────────────

	public function render_page() {
		$categories = get_terms( [ 'taxonomy' => 'categoria-ru', 'hide_empty' => false ] );
		$nonce      = wp_create_nonce( 'ru_prestaciones' );
		?>
		<div class="wrap" id="ru-prest-wrap">
			<h1 style="display:flex;align-items:center;gap:10px;">
				<span class="dashicons dashicons-tag" style="font-size:28px;color:#1a5c4c;"></span>
				Normalizar Prestaciones
			</h1>
			<p style="color:#646970;margin-bottom:20px;">
				Convierte los valores del campo <strong>Prestaciones</strong> de formato JSON array a texto limpio,
				sin corchetes ni comillas, una prestación por línea con la primera letra en mayúscula.
			</p>

			<!-- Config -->
			<div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:20px 24px;max-width:600px;margin-bottom:24px;">
				<table class="form-table" style="max-width:560px;">
					<tr>
						<th><label for="prest-cat">Categoría</label></th>
						<td>
							<select id="prest-cat" class="regular-text">
								<option value="">— Todas las categorías —</option>
								<?php if ( ! is_wp_error( $categories ) ) foreach ( $categories as $cat ) : ?>
									<option value="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Filtro</th>
						<td>
							<label style="display:block;margin-bottom:6px;">
								<input type="radio" name="prest-scope" value="solo_malformados" checked>
								Solo registros con formato <strong>JSON array</strong> (necesitan normalización)
							</label>
							<label>
								<input type="radio" name="prest-scope" value="todos">
								Todos los que tengan prestaciones
							</label>
						</td>
					</tr>
				</table>
				<button type="button" id="prest-load-btn" class="button button-primary">Cargar registros</button>
			</div>

			<!-- Tabla -->
			<div id="prest-results" style="display:none;max-width:1100px;">
				<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:12px;flex-wrap:wrap;">
					<div id="prest-summary" style="font-weight:600;color:#1d2327;"></div>
					<div style="display:flex;gap:8px;">
						<button type="button" id="prest-select-all-btn" class="button">Seleccionar todos</button>
						<button type="button" id="prest-save-btn" class="button button-primary" disabled>💾 Aplicar seleccionados</button>
					</div>
				</div>

				<div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;overflow:auto;">
					<table class="widefat" id="prest-table">
						<thead>
							<tr>
								<th style="width:32px;"><input type="checkbox" id="prest-check-all"></th>
								<th style="width:200px;">Registro</th>
								<th>Valor actual</th>
								<th>Resultado normalizado</th>
							</tr>
						</thead>
						<tbody id="prest-tbody"></tbody>
					</table>
				</div>
				<p style="margin-top:12px;color:#646970;font-size:12px;">
					Solo se guardan las filas seleccionadas.
				</p>
			</div>

			<div id="prest-loading" style="display:none;padding:16px 0;color:#646970;">
				<span class="spinner is-active" style="float:none;margin:0 8px 0 0;"></span> Cargando registros…
			</div>
		</div>

		<script>
		(function() {
			var nonce   = <?php echo wp_json_encode( $nonce ); ?>;
			var ajaxUrl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
			var posts   = [];

			var loadBtn      = document.getElementById('prest-load-btn');
			var saveBtn      = document.getElementById('prest-save-btn');
			var selectAllBtn = document.getElementById('prest-select-all-btn');
			var tbody        = document.getElementById('prest-tbody');
			var summary      = document.getElementById('prest-summary');
			var results      = document.getElementById('prest-results');
			var loading      = document.getElementById('prest-loading');
			var checkAll     = document.getElementById('prest-check-all');

			// ── Cargar ────────────────────────────────────────────────────────
			loadBtn.addEventListener('click', function() {
				var cat   = document.getElementById('prest-cat').value;
				var scope = document.querySelector('input[name="prest-scope"]:checked').value;

				loading.style.display = '';
				results.style.display = 'none';
				loadBtn.disabled = true;

				var fd = new FormData();
				fd.append('action', 'ru_prest_get_posts');
				fd.append('nonce',  nonce);
				fd.append('cat',    cat);
				fd.append('scope',  scope);

				fetch(ajaxUrl, { method: 'POST', body: fd })
					.then(function(r) { return r.json(); })
					.then(function(res) {
						loading.style.display = 'none';
						loadBtn.disabled = false;
						if (!res.success) { alert(res.data || 'Error.'); return; }
						posts = res.data;
						renderTable();
						results.style.display = '';
						saveBtn.disabled = posts.length === 0;
						summary.textContent = posts.length + ' registro' + (posts.length !== 1 ? 's' : '') + ' encontrado' + (posts.length !== 1 ? 's' : '');
					})
					.catch(function() { loading.style.display = 'none'; loadBtn.disabled = false; alert('Error de red.'); });
			});

			// ── Render tabla ──────────────────────────────────────────────────
			function renderTable() {
				tbody.innerHTML = '';
				posts.forEach(function(p) {
					var tr = document.createElement('tr');
					tr.id  = 'prest-row-' + p.id;

					var rawHtml  = '<code style="font-size:11px;white-space:pre-wrap;word-break:break-all;">' + escHtml(p.raw) + '</code>';
					var normHtml = '<code style="font-size:12px;white-space:pre-wrap;color:#00a32a;">' + escHtml(p.normalized) + '</code>';

					tr.innerHTML =
						'<td><input type="checkbox" class="prest-row-check" data-id="' + p.id + '" checked></td>' +
						'<td><strong>' + escHtml(p.title) + '</strong><br><small style="color:#646970">#' + p.id + '</small></td>' +
						'<td style="max-width:300px;">' + rawHtml + '</td>' +
						'<td>' + normHtml + '</td>';
					tbody.appendChild(tr);
				});
			}

			// ── Seleccionar todos ─────────────────────────────────────────────
			checkAll.addEventListener('change', function() {
				document.querySelectorAll('.prest-row-check').forEach(function(cb) { cb.checked = checkAll.checked; });
			});
			selectAllBtn.addEventListener('click', function() {
				document.querySelectorAll('.prest-row-check').forEach(function(cb) { cb.checked = true; });
				checkAll.checked = true;
			});

			// ── Guardar ───────────────────────────────────────────────────────
			saveBtn.addEventListener('click', function() {
				var toSave = [];
				document.querySelectorAll('.prest-row-check:checked').forEach(function(cb) {
					var id  = parseInt(cb.dataset.id, 10);
					var poi = posts.find(function(p) { return p.id === id; });
					if (poi) toSave.push({ id: poi.id, value: poi.normalized });
				});
				if (!toSave.length) { alert('No hay registros seleccionados.'); return; }
				if (!confirm('Aplicar normalización en ' + toSave.length + ' registro(s)?')) return;

				saveBtn.disabled = true;
				var fd = new FormData();
				fd.append('action', 'ru_prest_save');
				fd.append('nonce',  nonce);
				fd.append('data',   JSON.stringify(toSave));

				fetch(ajaxUrl, { method: 'POST', body: fd })
					.then(function(r) { return r.json(); })
					.then(function(res) {
						saveBtn.disabled = false;
						if (res.success) {
							alert('✓ ' + res.data + ' registro(s) actualizados.');
							toSave.forEach(function(item) {
								var row = document.getElementById('prest-row-' + item.id);
								if (row) row.style.background = '#f0fdf4';
							});
						} else {
							alert('Error: ' + (res.data || 'desconocido'));
						}
					})
					.catch(function() { saveBtn.disabled = false; alert('Error de red.'); });
			});

			function escHtml(s) {
				return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
			}
		})();
		</script>
		<?php
	}

	// ── AJAX: cargar posts ────────────────────────────────────────────────────

	public function ajax_get_posts() {
		check_ajax_referer( 'ru_prestaciones', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'No autorizado' );

		$cat   = sanitize_key( $_POST['cat']   ?? '' );
		$scope = sanitize_key( $_POST['scope'] ?? 'solo_malformados' );

		$args = [
			'post_type'      => 'registro-unico',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'fields'         => 'ids',
			'meta_query'     => [
				[ 'key' => 'Prestaciones', 'value' => '', 'compare' => '!=' ],
				[ 'key' => 'Prestaciones', 'compare' => 'EXISTS' ],
			],
		];

		if ( $scope === 'solo_malformados' ) {
			$args['meta_query'][] = [ 'key' => 'Prestaciones', 'value' => '[', 'compare' => 'LIKE' ];
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
			$raw = get_post_meta( $id, 'Prestaciones', true );
			if ( ! $raw ) continue;

			$normalized = $this->normalize( $raw );
			if ( ! $normalized ) continue;

			// Solo incluir si el normalizado difiere del original
			if ( $scope === 'solo_malformados' && trim( $raw ) === $normalized ) continue;

			$data[] = [
				'id'         => $id,
				'title'      => get_the_title( $id ),
				'raw'        => $raw,
				'normalized' => $normalized,
			];
		}

		wp_send_json_success( $data );
	}

	// ── AJAX: guardar ─────────────────────────────────────────────────────────

	public function ajax_save() {
		check_ajax_referer( 'ru_prestaciones', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'No autorizado' );

		$raw  = sanitize_text_field( $_POST['data'] ?? '' );
		$data = json_decode( stripslashes( $raw ), true );
		if ( ! is_array( $data ) ) wp_send_json_error( 'Datos inválidos' );

		$saved = 0;
		foreach ( $data as $item ) {
			$id    = (int) ( $item['id']    ?? 0 );
			$value = $item['value'] ?? '';
			if ( ! $id || ! $value ) continue;
			update_post_meta( $id, 'Prestaciones', $value );
			$saved++;
		}

		wp_send_json_success( $saved );
	}
}

new RU_Prestaciones_Normalizer();
