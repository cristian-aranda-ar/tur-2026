<?php
/**
 * Hero Slides — Custom Post Type, Meta Boxes & Admin JS
 *
 * Campos por slide:
 *   - Título      → post_title  (nativo)
 *   - Volanta     → _slide_volanta
 *   - Bajada      → _slide_bajada
 *   - Botón 1 texto → _slide_btn1_text
 *   - Botón 1 link  → _slide_btn1_link
 *   - Botón 2 texto → _slide_btn2_text  (opcional)
 *   - Botón 2 link  → _slide_btn2_link  (opcional)
 *   - Imagen/Poster → featured image    (nativo)
 *   - Video MP4   → _slide_video_url / _slide_video_id
 *   - Orden       → menu_order          (nativo — Page Attributes)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// 1. REGISTER CPT
// ─────────────────────────────────────────────
function misiones2027_register_slides_cpt() {
    $labels = [
        'name'               => 'Slides del Hero',
        'singular_name'      => 'Slide',
        'add_new'            => 'Agregar slide',
        'add_new_item'       => 'Nuevo slide',
        'edit_item'          => 'Editar slide',
        'new_item'           => 'Nuevo slide',
        'view_item'          => 'Ver slide',
        'search_items'       => 'Buscar slides',
        'not_found'          => 'No se encontraron slides',
        'not_found_in_trash' => 'No hay slides en la papelera',
        'all_items'          => 'Todos los slides',
        'menu_name'          => 'Slides del Hero',
    ];

    register_post_type( 'misiones_slide', [
        'labels'        => $labels,
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 20,
        'menu_icon'     => 'dashicons-slides',
        'supports'      => [ 'title', 'thumbnail', 'page-attributes' ],
        'rewrite'       => false,
        'query_var'     => false,
        'capability_type' => 'post',
    ] );
}
add_action( 'init', 'misiones2027_register_slides_cpt' );

// ─────────────────────────────────────────────
// 2. COLUMNS EN LISTADO
// ─────────────────────────────────────────────
function misiones2027_slides_columns( $cols ) {
    return [
        'cb'           => '<input type="checkbox">',
        'slide_thumb'  => 'Imagen',
        'title'        => 'Título',
        'slide_volanta'=> 'Volanta',
        'slide_order'  => 'Orden',
        'date'         => 'Fecha',
    ];
}
add_filter( 'manage_misiones_slide_posts_columns', 'misiones2027_slides_columns' );

function misiones2027_slides_column_content( $col, $post_id ) {
    switch ( $col ) {
        case 'slide_thumb':
            $thumb = get_the_post_thumbnail( $post_id, [ 60, 40 ] );
            echo $thumb ?: '<span style="color:#aaa;font-size:12px;">Sin imagen</span>';
            break;
        case 'slide_volanta':
            echo esc_html( get_post_meta( $post_id, '_slide_volanta', true ) ?: '—' );
            break;
        case 'slide_order':
            echo esc_html( get_post_field( 'menu_order', $post_id ) );
            break;
    }
}
add_action( 'manage_misiones_slide_posts_custom_column', 'misiones2027_slides_column_content', 10, 2 );

// Make order column sortable
function misiones2027_slides_sortable_columns( $cols ) {
    $cols['slide_order'] = 'menu_order';
    return $cols;
}
add_filter( 'manage_edit-misiones_slide_sortable_columns', 'misiones2027_slides_sortable_columns' );

// ─────────────────────────────────────────────
// 3. META BOX — CONTENIDO DEL SLIDE
// ─────────────────────────────────────────────
function misiones2027_register_slide_meta_boxes() {
    add_meta_box(
        'misiones_slide_content',
        'Contenido del Slide',
        'misiones2027_slide_content_callback',
        'misiones_slide',
        'normal',
        'high'
    );
    add_meta_box(
        'misiones_slide_media',
        'Imagen & Video',
        'misiones2027_slide_media_callback',
        'misiones_slide',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'misiones2027_register_slide_meta_boxes' );

// ── Contenido ──────────────────────────────
function misiones2027_slide_content_callback( $post ) {
    wp_nonce_field( 'misiones2027_slide_save', 'misiones2027_slide_nonce' );

    $volanta   = get_post_meta( $post->ID, '_slide_volanta',   true );
    $bajada    = get_post_meta( $post->ID, '_slide_bajada',    true );
    $btn1_text = get_post_meta( $post->ID, '_slide_btn1_text', true );
    $btn1_link = get_post_meta( $post->ID, '_slide_btn1_link', true );
    $btn2_text = get_post_meta( $post->ID, '_slide_btn2_text', true );
    $btn2_link = get_post_meta( $post->ID, '_slide_btn2_link', true );
    ?>
    <style>
        .slide-meta-table { width:100%; border-collapse:collapse; }
        .slide-meta-table th { text-align:left; padding:8px 0 4px; font-weight:600; font-size:13px; color:#1d2327; }
        .slide-meta-table td { padding:0 0 14px; }
        .slide-meta-table input[type=text],
        .slide-meta-table input[type=url],
        .slide-meta-table textarea { width:100%; padding:8px 10px; border:1px solid #c3c4c7; border-radius:4px; font-size:14px; box-sizing:border-box; }
        .slide-meta-table textarea { height:72px; resize:vertical; }
        .slide-meta-table .description { color:#646970; font-size:12px; margin-top:3px; display:block; }
        .slide-meta-sep { border:none; border-top:1px solid #dcdcde; margin:6px 0 16px; }
        .slide-meta-row-2col { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .slide-meta-optional { background:#f6f7f7; border-radius:4px; padding:10px 12px; }
        .slide-meta-optional .optional-label { font-size:11px; color:#646970; text-transform:uppercase; letter-spacing:.5px; font-weight:600; margin-bottom:8px; display:block; }
    </style>
    <table class="slide-meta-table">
        <tr>
            <th><label for="slide_volanta">Volanta</label></th>
        </tr>
        <tr>
            <td>
                <input type="text" id="slide_volanta" name="slide_volanta"
                       value="<?php echo esc_attr( $volanta ); ?>"
                       placeholder="Ej: Imperdible · UNESCO · Aventura">
                <span class="description">Etiqueta pequeña que aparece encima del título.</span>
            </td>
        </tr>
        <tr>
            <th><label for="post_title_override">Título</label></th>
        </tr>
        <tr>
            <td>
                <span class="description" style="color:#2271b1;">El título se edita en el campo "Título" nativo arriba de esta sección.</span>
            </td>
        </tr>
        <tr>
            <th><label for="slide_bajada">Bajada / Subtítulo</label></th>
        </tr>
        <tr>
            <td>
                <textarea id="slide_bajada" name="slide_bajada"
                          placeholder="Descripción breve del destino o atractivo…"><?php echo esc_textarea( $bajada ); ?></textarea>
            </td>
        </tr>
        <tr>
            <th>Botón Principal</th>
        </tr>
        <tr>
            <td>
                <div class="slide-meta-row-2col">
                    <div>
                        <input type="text" name="slide_btn1_text"
                               value="<?php echo esc_attr( $btn1_text ); ?>"
                               placeholder="Texto del botón (ej: Explorar)">
                        <span class="description">Texto</span>
                    </div>
                    <div>
                        <input type="url" name="slide_btn1_link"
                               value="<?php echo esc_attr( $btn1_link ); ?>"
                               placeholder="https://...">
                        <span class="description">URL destino</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <hr class="slide-meta-sep">
    <div class="slide-meta-optional">
        <span class="optional-label">Botón Secundario (opcional)</span>
        <div class="slide-meta-row-2col">
            <div>
                <input type="text" name="slide_btn2_text"
                       value="<?php echo esc_attr( $btn2_text ); ?>"
                       placeholder="Texto (ej: Ver video)">
                <span class="description" style="color:#646970;font-size:12px;">Texto</span>
            </div>
            <div>
                <input type="url" name="slide_btn2_link"
                       value="<?php echo esc_attr( $btn2_link ); ?>"
                       placeholder="https://...">
                <span class="description" style="color:#646970;font-size:12px;">URL destino</span>
            </div>
        </div>
    </div>

    <p style="margin-top:14px;color:#646970;font-size:12px;">
        <strong>Orden de aparición:</strong> usá el campo "Orden" en el panel lateral derecho (Page Attributes). Menor número = aparece primero.
    </p>
    <?php
}

// ── Imagen & Video ──────────────────────────
function misiones2027_slide_media_callback( $post ) {
    $video_url = get_post_meta( $post->ID, '_slide_video_url', true );
    $video_id  = get_post_meta( $post->ID, '_slide_video_id',  true );
    ?>
    <style>
        .slide-media-label { font-weight:600; font-size:13px; color:#1d2327; display:block; margin-bottom:6px; }
        .slide-media-desc  { color:#646970; font-size:12px; margin-top:4px; display:block; line-height:1.4; }
        .slide-video-preview { margin-top:8px; }
        .slide-video-preview video { width:100%; border-radius:4px; }
        .slide-video-name { font-size:12px; color:#2271b1; margin:4px 0 0; word-break:break-all; }
        .button.slide-media-btn { margin-top:6px; }
        .button.slide-remove-btn { margin-top:4px; color:#d63638 !important; }
    </style>

    <!-- Imagen de fondo (poster) -->
    <div style="margin-bottom:16px;">
        <span class="slide-media-label">Imagen de fondo (poster)</span>
        <?php
        $thumb_id = get_post_thumbnail_id( $post->ID );
        if ( $thumb_id ) {
            echo wp_get_attachment_image( $thumb_id, [ 200, 120 ], false, [ 'style' => 'width:100%;border-radius:4px;' ] );
        } else {
            echo '<p style="color:#aaa;font-size:12px;background:#f6f7f7;padding:20px;text-align:center;border-radius:4px;">Sin imagen</p>';
        }
        ?>
        <span class="slide-media-desc">Usá el botón "Imagen destacada" más abajo para establecer la foto de fondo. Se usa también como poster del video.</span>
    </div>

    <hr style="border:none;border-top:1px solid #dcdcde;margin:0 0 14px;">

    <!-- Video MP4 -->
    <span class="slide-media-label">Video MP4 (opcional)</span>
    <span class="slide-media-desc">Se reproduce automáticamente, en silencio y en loop. La imagen de fondo actúa como poster mientras carga.</span>

    <input type="hidden" id="slide_video_id"  name="slide_video_id"  value="<?php echo esc_attr( $video_id ); ?>">
    <input type="hidden" id="slide_video_url" name="slide_video_url" value="<?php echo esc_attr( $video_url ); ?>">

    <div id="slide-video-preview" class="slide-video-preview" style="<?php echo $video_url ? '' : 'display:none;'; ?>">
        <?php if ( $video_url ) : ?>
            <video src="<?php echo esc_url( $video_url ); ?>" muted playsinline style="width:100%;border-radius:4px;"></video>
            <p class="slide-video-name"><?php echo esc_html( basename( $video_url ) ); ?></p>
        <?php endif; ?>
    </div>

    <button type="button" class="button slide-media-btn" id="slide-video-upload-btn">
        <?php echo $video_url ? 'Cambiar video' : 'Subir / Seleccionar video'; ?>
    </button>
    <?php if ( $video_url ) : ?>
        <br>
        <button type="button" class="button slide-remove-btn" id="slide-video-remove-btn">Quitar video</button>
    <?php endif; ?>

    <script>
    (function($){
        var frame;
        $('#slide-video-upload-btn').on('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({
                title:    'Seleccioná el video MP4',
                button:   { text: 'Usar este video' },
                library:  { type: 'video' },
                multiple: false,
            });
            frame.on('select', function(){
                var att = frame.state().get('selection').first().toJSON();
                $('#slide_video_id').val(att.id);
                $('#slide_video_url').val(att.url);
                var prev = $('#slide-video-preview');
                prev.show().html('<video src="'+att.url+'" muted playsinline style="width:100%;border-radius:4px;"></video><p class="slide-video-name">'+att.filename+'</p>');
                $('#slide-video-upload-btn').text('Cambiar video');
            });
            frame.open();
        });

        $(document).on('click','#slide-video-remove-btn', function(e){
            e.preventDefault();
            $('#slide_video_id').val('');
            $('#slide_video_url').val('');
            $('#slide-video-preview').hide().html('');
            $('#slide-video-upload-btn').text('Subir / Seleccionar video');
            $(this).remove();
        });
    })(jQuery);
    </script>
    <?php
}

// ─────────────────────────────────────────────
// 4. ENQUEUE MEDIA SCRIPTS EN ADMIN (solo para este CPT)
// ─────────────────────────────────────────────
function misiones2027_enqueue_slide_admin_scripts( $hook ) {
    global $post;
    if ( ( $hook === 'post.php' || $hook === 'post-new.php' )
         && isset( $post ) && $post->post_type === 'misiones_slide' ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'misiones2027_enqueue_slide_admin_scripts' );

// ─────────────────────────────────────────────
// 5. SAVE META
// ─────────────────────────────────────────────
function misiones2027_save_slide_meta( $post_id ) {
    // Verify nonce
    if ( ! isset( $_POST['misiones2027_slide_nonce'] )
         || ! wp_verify_nonce( $_POST['misiones2027_slide_nonce'], 'misiones2027_slide_save' ) ) {
        return;
    }
    // Skip autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $text_fields = [
        '_slide_volanta'   => 'slide_volanta',
        '_slide_bajada'    => 'slide_bajada',
        '_slide_btn1_text' => 'slide_btn1_text',
        '_slide_btn2_text' => 'slide_btn2_text',
    ];
    foreach ( $text_fields as $meta_key => $field_name ) {
        if ( isset( $_POST[ $field_name ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $field_name ] ) );
        }
    }

    $url_fields = [
        '_slide_btn1_link' => 'slide_btn1_link',
        '_slide_btn2_link' => 'slide_btn2_link',
        '_slide_video_url' => 'slide_video_url',
    ];
    foreach ( $url_fields as $meta_key => $field_name ) {
        if ( isset( $_POST[ $field_name ] ) ) {
            update_post_meta( $post_id, $meta_key, esc_url_raw( $_POST[ $field_name ] ) );
        }
    }

    if ( isset( $_POST['slide_video_id'] ) ) {
        update_post_meta( $post_id, '_slide_video_id', absint( $_POST['slide_video_id'] ) );
    }
}
add_action( 'save_post_misiones_slide', 'misiones2027_save_slide_meta' );

// ─────────────────────────────────────────────
// 6. HELPER — get_hero_slides()
//    Devuelve los slides ordenados por menu_order ASC.
//    Si no hay slides publicados, devuelve los slides de demostración.
// ─────────────────────────────────────────────
function misiones2027_get_hero_slides() {
    $slides = get_posts( [
        'post_type'      => 'misiones_slide',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );

    if ( ! empty( $slides ) ) {
        $result = [];
        foreach ( $slides as $slide ) {
            $result[] = [
                'title'     => get_the_title( $slide ),
                'volanta'   => get_post_meta( $slide->ID, '_slide_volanta',   true ),
                'bajada'    => get_post_meta( $slide->ID, '_slide_bajada',    true ),
                'btn1_text' => get_post_meta( $slide->ID, '_slide_btn1_text', true ) ?: 'Explorar',
                'btn1_link' => get_post_meta( $slide->ID, '_slide_btn1_link', true ) ?: '#',
                'btn2_text' => get_post_meta( $slide->ID, '_slide_btn2_text', true ),
                'btn2_link' => get_post_meta( $slide->ID, '_slide_btn2_link', true ) ?: '#',
                'image_url' => get_the_post_thumbnail_url( $slide, 'full' ),
                'video_url' => get_post_meta( $slide->ID, '_slide_video_url', true ),
                'css_class' => '',
            ];
        }
        return $result;
    }

    // Fallback: slides de demostración (datos estáticos)
    return [
        [
            'title'     => 'Cataratas del Iguazú',
            'volanta'   => 'Imperdible',
            'bajada'    => 'Patrimonio Mundial UNESCO',
            'btn1_text' => 'Explorar',
            'btn1_link' => '#destinos',
            'btn2_text' => 'Ver video',
            'btn2_link' => '#',
            'image_url' => '',
            'video_url' => '',
            'css_class' => 'hero__slide--1',
        ],
        [
            'title'     => 'Saltos del Moconá',
            'volanta'   => 'Naturaleza',
            'bajada'    => 'Los saltos más largos del mundo',
            'btn1_text' => 'Explorar',
            'btn1_link' => '#destinos',
            'btn2_text' => '',
            'btn2_link' => '',
            'image_url' => '',
            'video_url' => '',
            'css_class' => 'hero__slide--2',
        ],
        [
            'title'     => 'Reducciones Jesuíticas',
            'volanta'   => 'Patrimonio',
            'bajada'    => 'Historia viva en la selva',
            'btn1_text' => 'Explorar',
            'btn1_link' => '#destinos',
            'btn2_text' => '',
            'btn2_link' => '',
            'image_url' => '',
            'video_url' => '',
            'css_class' => 'hero__slide--3',
        ],
    ];
}
