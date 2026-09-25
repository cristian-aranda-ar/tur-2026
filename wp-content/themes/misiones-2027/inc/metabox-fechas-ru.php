<?php
/**
 * Meta box: Fechas de Calendario
 * Post type: registro-unico
 * Muestra una UI para elegir tipo de fecha y sus valores.
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'ru_fechas_calendario',
        'Fechas de Calendario',
        'ru_fechas_render_metabox',
        'registro-unico',
        'side',
        'high'
    );
} );

function ru_fechas_render_metabox( WP_Post $post ): void {
    wp_nonce_field( 'ru_fechas_save', 'ru_fechas_nonce' );

    $tipo          = get_post_meta( $post->ID, '_ru_fecha_tipo', true ) ?: 'unica';
    $fecha_inicio  = get_post_meta( $post->ID, '_ru_fecha_inicio', true );
    $fecha_fin     = get_post_meta( $post->ID, '_ru_fecha_fin', true );
    $intermitentes = json_decode( get_post_meta( $post->ID, '_ru_fechas_intermitentes', true ), true ) ?: [];

    // Only show fields when post is in categoria-ru = calendario
    $is_calendar = has_term( 'calendario', 'categoria-ru', $post->ID );
    ?>

    <?php if ( ! $is_calendar ) : ?>
        <p style="color:#888;font-size:12px;margin:0;">
            Asigná la categoría <strong>Calendario</strong> al guardar para activar este panel.
        </p>
    <?php endif; ?>

    <style>
        .ru-fecha-mb { font-family: -apple-system, sans-serif; }
        .ru-fecha-mb label { display:block; font-size:12px; font-weight:600; color:#1d2327; margin-bottom:4px; }
        .ru-fecha-mb input[type=date], .ru-fecha-mb select { width:100%; box-sizing:border-box; }
        .ru-fecha-mb .ru-tipo-row { display:flex; gap:6px; margin-bottom:12px; flex-wrap:wrap; }
        .ru-fecha-mb .ru-tipo-btn {
            flex:1; min-width:60px; padding:6px 4px; border:1.5px solid #c3c4c7; border-radius:6px;
            background:#fff; cursor:pointer; font-size:11px; font-weight:600; color:#50575e;
            text-align:center; transition:all .15s;
        }
        .ru-fecha-mb .ru-tipo-btn.is-active {
            border-color:#1a5c4c; background:#eaf4f1; color:#1a5c4c;
        }
        .ru-fecha-mb .ru-field-group { margin-bottom:10px; }
        .ru-fecha-mb .ru-intermitente-list { display:flex; flex-direction:column; gap:6px; margin-top:6px; }
        .ru-fecha-mb .ru-chip-row { display:flex; align-items:center; gap:6px; }
        .ru-fecha-mb .ru-chip-row input[type=date] { flex:1; }
        .ru-fecha-mb .ru-chip-remove {
            border:none; background:none; cursor:pointer; color:#c0392b; font-size:16px;
            line-height:1; padding:0 2px; flex-shrink:0;
        }
        .ru-fecha-mb .ru-add-btn {
            width:100%; padding:6px; border:1.5px dashed #c3c4c7; border-radius:6px;
            background:none; cursor:pointer; color:#50575e; font-size:12px; margin-top:6px;
        }
        .ru-fecha-mb .ru-add-btn:hover { border-color:#1a5c4c; color:#1a5c4c; }
        .ru-fecha-mb .ru-section { display:none; }
        .ru-fecha-mb .ru-section.is-visible { display:block; }
    </style>

    <div class="ru-fecha-mb">

        <!-- Selector de tipo -->
        <div class="ru-tipo-row" id="ru-tipo-row">
            <button type="button" class="ru-tipo-btn <?php echo $tipo === 'unica' ? 'is-active' : ''; ?>" data-tipo="unica">Única</button>
            <button type="button" class="ru-tipo-btn <?php echo $tipo === 'rango' ? 'is-active' : ''; ?>" data-tipo="rango">Rango</button>
            <button type="button" class="ru-tipo-btn <?php echo $tipo === 'intermitente' ? 'is-active' : ''; ?>" data-tipo="intermitente">Intermitente</button>
        </div>
        <input type="hidden" name="ru_fecha_tipo" id="ru-fecha-tipo" value="<?php echo esc_attr( $tipo ); ?>">

        <!-- Fecha única -->
        <div class="ru-section <?php echo $tipo === 'unica' ? 'is-visible' : ''; ?>" id="ru-section-unica">
            <div class="ru-field-group">
                <label for="ru_fecha_inicio">Fecha</label>
                <input type="date" id="ru_fecha_inicio" name="ru_fecha_inicio"
                       value="<?php echo esc_attr( $fecha_inicio ); ?>">
            </div>
        </div>

        <!-- Rango -->
        <div class="ru-section <?php echo $tipo === 'rango' ? 'is-visible' : ''; ?>" id="ru-section-rango">
            <div class="ru-field-group">
                <label for="ru_fecha_inicio_rango">Desde</label>
                <input type="date" id="ru_fecha_inicio_rango" name="ru_fecha_inicio_rango"
                       value="<?php echo esc_attr( $fecha_inicio ); ?>">
            </div>
            <div class="ru-field-group">
                <label for="ru_fecha_fin">Hasta</label>
                <input type="date" id="ru_fecha_fin" name="ru_fecha_fin"
                       value="<?php echo esc_attr( $fecha_fin ); ?>">
            </div>
        </div>

        <!-- Intermitente -->
        <div class="ru-section <?php echo $tipo === 'intermitente' ? 'is-visible' : ''; ?>" id="ru-section-intermitente">
            <label>Fechas</label>
            <div class="ru-intermitente-list" id="ru-intermitente-list">
                <?php foreach ( $intermitentes as $i => $d ) : ?>
                    <div class="ru-chip-row">
                        <input type="date" name="ru_fechas_intermitentes[]" value="<?php echo esc_attr( $d ); ?>">
                        <button type="button" class="ru-chip-remove" title="Eliminar">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="ru-add-btn" id="ru-add-fecha">+ Agregar fecha</button>
        </div>

    </div>

    <script>
    (function () {
        const tipoInput = document.getElementById('ru-fecha-tipo');
        const tipoBtns  = document.querySelectorAll('#ru-tipo-row .ru-tipo-btn');
        const sections  = {
            unica:       document.getElementById('ru-section-unica'),
            rango:       document.getElementById('ru-section-rango'),
            intermitente: document.getElementById('ru-section-intermitente'),
        };

        function setTipo(tipo) {
            tipoInput.value = tipo;
            tipoBtns.forEach(b => b.classList.toggle('is-active', b.dataset.tipo === tipo));
            Object.entries(sections).forEach(([k, el]) => {
                el.classList.toggle('is-visible', k === tipo);
            });
        }

        tipoBtns.forEach(btn => btn.addEventListener('click', () => setTipo(btn.dataset.tipo)));

        // Intermitente: agregar / eliminar filas
        document.getElementById('ru-add-fecha').addEventListener('click', function () {
            const list = document.getElementById('ru-intermitente-list');
            const row  = document.createElement('div');
            row.className = 'ru-chip-row';
            row.innerHTML = '<input type="date" name="ru_fechas_intermitentes[]"><button type="button" class="ru-chip-remove" title="Eliminar">×</button>';
            list.appendChild(row);
            row.querySelector('input').focus();
        });

        document.getElementById('ru-intermitente-list').addEventListener('click', function (e) {
            if (e.target.classList.contains('ru-chip-remove')) {
                e.target.closest('.ru-chip-row').remove();
            }
        });
    })();
    </script>
    <?php
}

// ── Guardar ─────────────────────────────────────────────────────────
add_action( 'save_post_registro-unico', function ( int $post_id ): void {
    if (
        ! isset( $_POST['ru_fechas_nonce'] ) ||
        ! wp_verify_nonce( $_POST['ru_fechas_nonce'], 'ru_fechas_save' ) ||
        ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
        ! current_user_can( 'edit_post', $post_id )
    ) {
        return;
    }

    $tipo = sanitize_text_field( $_POST['ru_fecha_tipo'] ?? 'unica' );
    update_post_meta( $post_id, '_ru_fecha_tipo', $tipo );

    if ( $tipo === 'unica' ) {
        $inicio = sanitize_text_field( $_POST['ru_fecha_inicio'] ?? '' );
        update_post_meta( $post_id, '_ru_fecha_inicio', $inicio );
        delete_post_meta( $post_id, '_ru_fecha_fin' );
        delete_post_meta( $post_id, '_ru_fechas_intermitentes' );

    } elseif ( $tipo === 'rango' ) {
        $inicio = sanitize_text_field( $_POST['ru_fecha_inicio_rango'] ?? '' );
        $fin    = sanitize_text_field( $_POST['ru_fecha_fin'] ?? '' );
        update_post_meta( $post_id, '_ru_fecha_inicio', $inicio );
        update_post_meta( $post_id, '_ru_fecha_fin', $fin );
        delete_post_meta( $post_id, '_ru_fechas_intermitentes' );

    } elseif ( $tipo === 'intermitente' ) {
        $raw   = array_map( 'sanitize_text_field', (array) ( $_POST['ru_fechas_intermitentes'] ?? [] ) );
        $dates = array_filter( $raw );
        sort( $dates );
        update_post_meta( $post_id, '_ru_fechas_intermitentes', wp_json_encode( array_values( $dates ) ) );
        delete_post_meta( $post_id, '_ru_fecha_inicio' );
        delete_post_meta( $post_id, '_ru_fecha_fin' );
    }
} );
