<?php
/**
 * Single Template: Registro Único
 */
get_header();

while ( have_posts() ) :
    the_post();

    $post_id = get_the_ID();
    $color   = get_field( 'color' )        ?: '#1a5c4c';
    $volanta = get_field( 'hero_volanta' )  ?: '';
    $detalle = get_field( 'hero_detalle' )  ?: '';
    $img_url = get_the_post_thumbnail_url( null, 'full' );

    $lat_val = (float) get_post_meta( $post_id, 'latitud',  true );
    $lon_val = (float) get_post_meta( $post_id, 'longitud', true );
    $tiene_coordenadas = $lat_val && $lon_val;

    $portada_video_raw = get_field( 'portada_video' );
    $portada_video_url = '';
    if ( is_array( $portada_video_raw ) ) {
        $portada_video_url = $portada_video_raw['url'] ?? '';
    } elseif ( is_string( $portada_video_raw ) && ! is_numeric( $portada_video_raw ) ) {
        $portada_video_url = $portada_video_raw;
    } elseif ( is_numeric( $portada_video_raw ) ) {
        $portada_video_url = wp_get_attachment_url( (int) $portada_video_raw ) ?: '';
    }
    $yt_hero = misiones2027_youtube_id( $portada_video_url );

    $btn_primary   = get_field( 'boton-primario' )   ?: [];
    $btn_secondary = get_field( 'boton-secundario' ) ?: [];

    $color_soft = misiones2027_hex_rgba( $color, 0.10 );
    $color_mid  = misiones2027_hex_rgba( $color, 0.50 );
    $tint_mid   = misiones2027_hex_rgba( $color, 0.50 );
    $tint_deep  = misiones2027_hex_rgba( $color, 0.88 );

    // Contacto
    $direccion_raw = get_field( 'direccion' ) ?: [];
    $direccion     = is_array( $direccion_raw ) ? ( $direccion_raw['url'] ?? '' ) : $direccion_raw;
    $direccion_lbl = is_array( $direccion_raw ) ? ( $direccion_raw['title'] ?: 'Ver en Google Maps' ) : $direccion_raw;
    $sitio_web = get_field( 'sitio_web' )    ?: '';
    $telefono  = get_field( 'telefono' )     ?: '';
    $whatsapp  = get_field( 'whatsapp' )     ?: '';
    $instagram = get_field( 'instagram' )    ?: '';
    $facebook  = get_field( 'facebook' )     ?: '';

    // Características
    $acceso           = get_field( 'acceso' ) ?: get_post_meta( $post_id, 'acceso', true ) ?: 'Indefinido';
    $prestaciones_raw = get_field( 'Prestaciones' );
    if ( empty( $prestaciones_raw ) ) $prestaciones_raw = get_post_meta( $post_id, 'Prestaciones', true );
    $pagos_raw        = get_field( 'pagos' ) ?: '';
    if ( is_array( $prestaciones_raw ) ) {
        $prestaciones = array_filter( array_map( function( $t ) {
            if ( is_object( $t ) ) return $t->name;
            if ( is_int( $t ) || ( is_string( $t ) && is_numeric( $t ) ) ) {
                $term = get_term( (int) $t );
                return ( $term && ! is_wp_error( $term ) ) ? $term->name : '';
            }
            return (string) $t;
        }, $prestaciones_raw ) );
    } elseif ( is_string( $prestaciones_raw ) && $prestaciones_raw ) {
        $prestaciones = array_filter( preg_split( '/[\r\n]+/', $prestaciones_raw ) );
    } else {
        $prestaciones = [];
    }
    $pagos  = ( is_string( $pagos_raw ) && $pagos_raw )
        ? array_filter( preg_split( '/[\r\n]+/', $pagos_raw ) )
        : [];
    $costos   = get_field( 'costos' )   ?: [];
    $horarios = get_field( 'horario' )  ?: [];

    // Destaques (repeater)
    $destaques = get_field( 'destaques' ) ?: [];

    // Audiovisual
    $galeria    = get_field( 'galeria_de_fotos' ) ?: [];

    // Galería como JSON para el lightbox JS
    $galeria_json = json_encode( array_values( array_filter( array_map( function ( $g ) {
        if ( is_array( $g ) ) return [ 'url' => $g['url'] ?? '', 'alt' => $g['alt'] ?? '' ];
        $url = wp_get_attachment_image_url( $g, 'full' );
        return $url ? [ 'url' => $url, 'alt' => '' ] : null;
    }, $galeria ) ) ) );

    // Relacionados — campo SCF "relacionados" o fallback a términos propios
    $rel_field = get_field( 'relacionados', $post_id );
    if ( ! empty( $rel_field ) ) {
        // SCF puede devolver objetos WP_Term o IDs según return format
        $terminos = array_map( fn( $t ) => is_object( $t ) ? (int) $t->term_id : (int) $t, (array) $rel_field );
        $terminos = array_filter( $terminos );
    } else {
        $terminos = wp_get_post_terms( $post_id, 'categoria-ru', [ 'fields' => 'ids' ] );
        $terminos = ! is_wp_error( $terminos ) ? $terminos : [];
    }

    // Slugs de categorías relacionadas (para el mapa explorar)
    $categorias_slug = [];
    foreach ( $terminos as $tid ) {
        $term = get_term( (int) $tid, 'categoria-ru' );
        if ( $term && ! is_wp_error( $term ) ) {
            $categorias_slug[] = $term->slug;
        }
    }

    // Modo explorar: checkbox SCF "explorar"
    $modo_explorar = (bool) get_field( 'explorar', $post_id );
?>

<main id="main" class="ru-page" role="main"
      style="--ru-color:<?php echo esc_attr( $color ); ?>;--ru-color-soft:<?php echo esc_attr( $color_soft ); ?>;--ru-color-mid:<?php echo esc_attr( $color_mid ); ?>;">

  <!-- ══════════════════════════════════════════
       HERO
  ══════════════════════════════════════════ -->
  <section class="ru-hero" aria-label="<?php the_title_attribute(); ?>">

    <?php if ( $portada_video_url && ! $yt_hero ) : ?>
      <div class="ru-hero__video" aria-hidden="true">
        <video autoplay muted loop playsinline>
          <source src="<?php echo esc_url( $portada_video_url ); ?>" type="video/mp4">
        </video>
      </div>
    <?php elseif ( $yt_hero ) : ?>
      <div class="ru-hero__video" aria-hidden="true">
        <iframe
          src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr( $yt_hero ); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo esc_attr( $yt_hero ); ?>&controls=0&rel=0&modestbranding=1&playsinline=1"
          frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
        ></iframe>
      </div>
    <?php elseif ( $img_url ) : ?>
      <div class="ru-hero__bg"
           style="background-image:url('<?php echo esc_url( $img_url ); ?>');"
           role="img" aria-label="<?php the_title_attribute(); ?>"></div>
    <?php endif; ?>

    <div class="ru-hero__overlay"></div>

    <div class="ru-hero__content">
      <?php if ( $volanta ) : ?>
        <span class="ru-hero__badge" style="background:<?php echo esc_attr( $color ); ?>;"><?php echo esc_html( $volanta ); ?></span>
      <?php endif; ?>
      <h1 class="ru-hero__title"><?php the_title(); ?></h1>
      <?php if ( $detalle ) : ?>
        <p class="ru-hero__detail"><?php echo esc_html( $detalle ); ?></p>
      <?php endif; ?>
      <div class="ru-hero__cta">
        <?php if ( ! empty( $btn_primary['url'] ) ) : ?>
          <a href="<?php echo esc_url( $btn_primary['url'] ); ?>"
             class="btn btn--accent"
             style="background:<?php echo esc_attr( $color ); ?>;"
             <?php echo ! empty( $btn_primary['target'] ) ? 'target="' . esc_attr( $btn_primary['target'] ) . '"' : ''; ?>>
            <?php echo esc_html( $btn_primary['title'] ); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        <?php endif; ?>
        <?php if ( ! empty( $btn_secondary['url'] ) ) : ?>
          <a href="<?php echo esc_url( $btn_secondary['url'] ); ?>"
             class="btn btn--glass"
             <?php echo ! empty( $btn_secondary['target'] ) ? 'target="' . esc_attr( $btn_secondary['target'] ) . '"' : ''; ?>>
            <?php echo esc_html( $btn_secondary['title'] ); ?>
          </a>
        <?php endif; ?>
      </div>
    </div>

    <?php if ( $tiene_coordenadas && function_exists( 'turs_user_liked' ) ) :
      $_turs_liked = turs_user_liked( $post_id );
    ?>
      <button
        class="ru-hero__fav js-turs-like<?php echo $_turs_liked ? ' is-liked' : ''; ?>"
        type="button"
        data-post-id="<?php echo esc_attr( $post_id ); ?>"
        data-liked="<?php echo $_turs_liked ? 'true' : 'false'; ?>"
        aria-label="<?php echo $_turs_liked ? esc_attr__( 'Quitar de ruta de viaje', 'misiones-2027' ) : esc_attr__( 'Agregar a ruta de viaje', 'misiones-2027' ); ?>"
      >
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/>
          <circle cx="12" cy="10" r="3"/>
        </svg>
      </button>
    <?php endif; ?>

  </section>

  <!-- ══════════════════════════════════════════
       BODY
  ══════════════════════════════════════════ -->
  <div class="ru-body">

    <!-- ── MAIN ───────────────────────────── -->
    <div class="ru-main">

      <?php if ( ! empty( $destaques ) ) : ?>
        <section class="ru-section" id="destaques">
          <div class="ru-destaques-grid">
            <?php foreach ( $destaques as $i => $d ) :
              $dp    = $d['Portada'] ?? $d['portada'] ?? null;
              $d_img = '';
              if ( $dp ) {
                  if ( is_array( $dp ) ) {
                      $d_img = $dp['url'] ?? $dp['sizes']['medium_large'] ?? '';
                  } elseif ( is_string( $dp ) && ! is_numeric( $dp ) ) {
                      $d_img = $dp; // return_format = Image URL
                  } else {
                      $d_img = wp_get_attachment_image_url( (int) $dp, 'medium_large' ) ?: '';
                  }
              }
              $d_gal_raw  = is_array( $d['destaque_galeria'] ?? null ) ? $d['destaque_galeria'] : [];
              $d_gal_urls = array_values( array_filter( array_map( function ( $g ) {
                  if ( is_array( $g ) ) return $g['url'] ?? '';
                  if ( is_string( $g ) && ! is_numeric( $g ) ) return $g;
                  return wp_get_attachment_image_url( (int) $g, 'large' ) ?: '';
              }, $d_gal_raw ) ) );
            ?>
              <button class="ru-destaque-card" type="button"
                      data-modal="destaque"
                      data-titulo="<?php echo esc_attr( $d['destaque_titulo'] ?? '' ); ?>"
                      data-detalle="<?php echo esc_attr( $d['destaque_detalle'] ?? '' ); ?>"
                      data-desc="<?php echo esc_attr( $d['descripcion'] ?? '' ); ?>"
                      data-video="<?php echo esc_attr( misiones2027_youtube_id( $d['destaque_video'] ?? '' ) ); ?>"
                      data-portada="<?php echo esc_attr( $d_img ); ?>"
                      data-galeria="<?php echo esc_attr( json_encode( $d_gal_urls ) ); ?>">
                <div class="ru-destaque-card__img <?php echo $d_img ? '' : 'ru-destaque-card__img--empty'; ?>"
                     <?php if ( $d_img ) : ?>style="background-image:url('<?php echo esc_url( $d_img ); ?>');"<?php endif; ?>></div>
                <div class="ru-destaque-card__body">
                  <span class="ru-destaque-card__title"><?php echo esc_html( $d['destaque_titulo'] ?? '' ); ?></span>
                  <svg class="ru-destaque-card__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </div>
              </button>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>


      <?php $content = apply_filters( 'the_content', get_the_content() ); ?>
      <?php if ( $content ) : ?>
        <section class="ru-section ru-content">
          <?php echo $content; ?>
        </section>
      <?php endif; ?>


      <?php if ( $tiene_coordenadas ) : ?>
        <section class="ru-section ru-section--mapa">
          <div class="ru-mapa">
            <div
              id="ru-single-map"
              data-lat="<?php echo esc_attr( $lat_val ); ?>"
              data-lon="<?php echo esc_attr( $lon_val ); ?>"
              data-name="<?php the_title_attribute(); ?>"
              data-color="<?php echo esc_attr( $color ); ?>"
              aria-label="<?php the_title_attribute(); ?> — Ubicación en el mapa"
            ></div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ( function_exists( 'turs_get_reviews' ) ) :
        $_turs_reviews = turs_get_reviews( $post_id );
        $_turs_avg     = turs_avg_rating( $post_id );
        $_turs_count   = turs_review_count( $post_id );
        $_turs_my_rev  = turs_user_review( $post_id );
      ?>
        <section class="ru-section turs-reviews-section">

          <?php
            $_share_url   = urlencode( get_permalink() );
            $_share_title = urlencode( get_the_title() );
          ?>

          <!-- ── Barra de acciones ── -->
          <div class="ru-action-bar">

            <div class="ru-action-bar__left">

              <!-- Rating (solo visual, no interactivo) -->
              <div class="ru-action-bar__pill ru-action-bar__pill--rating">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <?php if ( $_turs_avg > 0 ) : ?>
                  <strong><?php echo number_format( $_turs_avg, 1 ); ?></strong>
                  <span class="ru-action-bar__pill-sub"><?php echo $_turs_count; ?> reseña<?php echo $_turs_count !== 1 ? 's' : ''; ?></span>
                <?php else : ?>
                  <span class="ru-action-bar__pill-sub">Sin reseñas</span>
                <?php endif; ?>
              </div>

              <!-- Escribir reseña → abre drawer -->
              <button type="button" class="ru-action-bar__pill ru-action-bar__pill--review js-open-drawer" aria-expanded="false" aria-controls="js-reviews-drawer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Escribir una reseña
              </button>

            </div>

            <div class="ru-action-bar__right">

              <a href="https://twitter.com/intent/tweet?url=<?php echo $_share_url; ?>&text=<?php echo $_share_title; ?>"
                 target="_blank" rel="noopener" class="ru-action-bar__share" aria-label="Compartir en X">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.259 5.631 5.905-5.631Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>

              <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_share_url; ?>"
                 target="_blank" rel="noopener" class="ru-action-bar__share" aria-label="Compartir en Facebook">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
              </a>

              <button type="button" class="ru-action-bar__share js-share-ig" aria-label="Copiar enlace para Instagram"
                      data-url="<?php echo esc_attr( get_permalink() ); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                <span class="ru-action-bar__ig-tip" aria-live="polite"></span>
              </button>

            </div>
          </div>

          <!-- ── Drawer de reseñas ── -->
          <div class="ru-drawer" id="js-reviews-drawer" role="dialog" aria-label="Reseñas" aria-hidden="true">

            <div class="ru-drawer__backdrop js-close-drawer"></div>

            <aside class="ru-drawer__panel">

              <div class="ru-drawer__header">
                <div class="ru-drawer__header-info">
                  <h2 class="ru-drawer__title">Reseñas</h2>
                  <?php if ( $_turs_avg > 0 ) : ?>
                    <div class="ru-drawer__avg">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                      <strong><?php echo number_format( $_turs_avg, 1 ); ?></strong>
                      <span><?php echo $_turs_count; ?> reseña<?php echo $_turs_count !== 1 ? 's' : ''; ?></span>
                    </div>
                  <?php endif; ?>
                </div>
                <button type="button" class="ru-drawer__close js-close-drawer" aria-label="Cerrar reseñas">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
              </div>

              <div class="ru-drawer__body">

                <!-- Reseñas paginadas -->
                <div class="ru-drawer__reviews" id="js-drawer-reviews">
                  <?php if ( empty( $_turs_reviews ) ) : ?>
                    <p class="ru-drawer__empty">Todavía no hay reseñas. ¡Sé el primero!</p>
                  <?php else : ?>
                    <?php foreach ( $_turs_reviews as $rev ) :
                      $rev_user = get_userdata( $rev->user_id );
                      $rev_name = $rev_user ? $rev_user->display_name : 'Usuario';
                    ?>
                      <div class="turs-review-card">
                        <div class="turs-review-card__top">
                          <span class="turs-review-card__user"><?php echo esc_html( $rev_name ); ?></span>
                          <time class="turs-review-card__date"><?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $rev->created_at ) ) ); ?></time>
                        </div>
                        <div class="turs-review-card__stars">
                          <?php for ( $i = 1; $i <= 5; $i++ ) echo $i <= $rev->rating ? '★' : '☆'; ?>
                        </div>
                        <?php if ( $rev->comment ) : ?>
                          <p class="turs-review-card__text"><?php echo esc_html( $rev->comment ); ?></p>
                        <?php endif; ?>
                        <?php if ( $rev->admin_reply ) : ?>
                          <div class="turs-review-card__reply">
                            <strong>Respuesta del sitio</strong>
                            <p><?php echo esc_html( $rev->admin_reply ); ?></p>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>

                <!-- Formulario / CTA -->
                <div class="ru-drawer__form-area">
                  <?php if ( is_user_logged_in() && ! $_turs_my_rev ) : ?>
                    <div class="turs-review-form">
                      <h3 class="turs-review-form__title">Dejá tu reseña</h3>
                      <form id="js-turs-review-form">
                        <input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">
                        <input type="hidden" name="rating" class="js-turs-rating-input" value="5">
                        <div class="turs-stars js-turs-stars" aria-label="Puntuación">
                          <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <button type="button" class="turs-star js-turs-star <?php echo $i <= 5 ? 'is-active' : ''; ?>" data-val="<?php echo $i; ?>" aria-label="<?php echo $i; ?> estrellas">★</button>
                          <?php endfor; ?>
                        </div>
                        <textarea name="comment" placeholder="Contá tu experiencia (opcional)" rows="3"></textarea>
                        <button type="submit" class="turs-review-form__submit">Enviar reseña</button>
                        <p id="js-turs-review-msg" class="turs-review-msg"></p>
                      </form>
                    </div>
                  <?php elseif ( is_user_logged_in() && $_turs_my_rev ) : ?>
                    <div class="turs-review-cta">
                      Ya dejaste una reseña para este lugar.
                      <?php if ( $_turs_my_rev->status === 'pending' ) echo ' <span>(pendiente de aprobación)</span>'; ?>
                    </div>
                  <?php else : ?>
                    <div class="turs-review-cta">
                      <button type="button" class="turs-review-login-btn js-turs-login" aria-label="Ingresar para comentar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        Ingresar para dejar tu reseña
                      </button>
                    </div>
                  <?php endif; ?>
                </div>

              </div><!-- /.ru-drawer__body -->
            </aside>
          </div><!-- /.ru-drawer -->

        </section>
      <?php endif; ?>

    </div>

    <!-- ── SIDEBAR ─────────────────────────── -->
    <aside class="ru-sidebar" aria-label="<?php esc_attr_e( 'Información adicional', 'misiones-2027' ); ?>">


      <!-- ══ MOCKUP FECHAS — HARDCODED ══ -->
      <?php $_turs_es_calendario = has_term( 'calendario', 'categoria-ru', $post_id ); ?>
      <?php if ( $_turs_es_calendario ) : ?>
      <div class="ru-card">
        <h3 class="ru-card__title">Fechas</h3>
        <div class="events-panel ru-dates-panel">

          <!-- Tipo 1: Fecha única -->
          <div class="ru-date-entry">
            <span class="ru-date-tag">Fecha única</span>
            <div class="ru-date-row">
              <div class="ru-date-badge" style="background:<?php echo esc_attr( $color ); ?>;">
                <strong>25</strong>
                <span>JUL</span>
              </div>
              <div class="ru-date-meta">
                <span class="ru-date-year">2026</span>
                <span class="ru-date-desc">Festival de la Selva</span>
              </div>
            </div>
          </div>

          <!-- Tipo 2: Rango de fechas (corrido) -->
          <div class="ru-date-entry">
            <span class="ru-date-tag">Rango de fechas</span>
            <div class="ru-date-range">
              <div class="ru-date-badge" style="background:<?php echo esc_attr( $color ); ?>;">
                <strong>15</strong>
                <span>AGO</span>
              </div>
              <div class="ru-date-range__connector">
                <svg width="28" height="10" viewBox="0 0 28 10" fill="none" aria-hidden="true">
                  <path d="M0 5h24M20 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>6 días</span>
              </div>
              <div class="ru-date-badge" style="background:<?php echo esc_attr( $color ); ?>;">
                <strong>20</strong>
                <span>AGO</span>
              </div>
              <div class="ru-date-meta">
                <span class="ru-date-year">2026</span>
              </div>
            </div>
          </div>

          <!-- Tipo 3: Fechas intermitentes -->
          <div class="ru-date-entry">
            <span class="ru-date-tag">Fechas intermitentes</span>
            <div class="ru-date-chips">
              <span class="ru-date-chip" style="--chip-color:<?php echo esc_attr( $color ); ?>;">1 MAY</span>
              <span class="ru-date-chip" style="--chip-color:<?php echo esc_attr( $color ); ?>;">8 MAY</span>
              <span class="ru-date-chip" style="--chip-color:<?php echo esc_attr( $color ); ?>;">15 MAY</span>
              <span class="ru-date-chip" style="--chip-color:<?php echo esc_attr( $color ); ?>;">22 MAY</span>
              <span class="ru-date-chip" style="--chip-color:<?php echo esc_attr( $color ); ?>;">29 MAY</span>
              <span class="ru-date-chip ru-date-chip--year">2026</span>
            </div>
          </div>

        </div>
      </div>
      <?php endif; ?>
      <!-- /MOCKUP FECHAS -->

      <?php if ( $direccion || $sitio_web || $telefono || $whatsapp || $instagram || $facebook ) : ?>
        <div class="ru-card">
          <h3 class="ru-card__title">Información de Contacto</h3>
          <ul class="ru-contact__list">
            <?php
            $contactos = [
              [ 'val' => $direccion, 'href' => $direccion, 'label' => $direccion_lbl, 'target' => true,
                'icon' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>' ],
              [ 'val' => $telefono, 'href' => 'tel:' . preg_replace( '/\D/', '', $telefono ), 'label' => $telefono,
                'icon' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>' ],
              [ 'val' => $whatsapp, 'href' => 'https://wa.me/' . preg_replace( '/\D/', '', $whatsapp ), 'label' => $whatsapp, 'target' => true, 'fill' => true,
                'icon' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>' ],
              [ 'val' => $instagram, 'href' => $instagram, 'label' => trim( parse_url( $instagram, PHP_URL_PATH ) ?: $instagram, '/' ), 'target' => true,
                'icon' => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>' ],
              [ 'val' => $facebook, 'href' => $facebook, 'label' => trim( parse_url( $facebook, PHP_URL_PATH ) ?: $facebook, '/' ), 'target' => true,
                'icon' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>' ],
              [ 'val' => $sitio_web, 'href' => $sitio_web, 'label' => preg_replace( '#^https?://#', '', rtrim( $sitio_web, '/' ) ), 'target' => true,
                'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20M2 12h20"/>' ],
            ];
            foreach ( $contactos as $c ) :
              if ( ! $c['val'] ) continue;
            ?>
              <li class="ru-contact__item">
                <span class="ru-contact__icon" style="color:<?php echo esc_attr( $color ); ?>;">
                  <?php if ( ! empty( $c['fill'] ) ) : ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><?php echo $c['icon']; ?></svg>
                  <?php else : ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $c['icon']; ?></svg>
                  <?php endif; ?>
                </span>
                <?php if ( $c['href'] ) : ?>
                  <a href="<?php echo esc_url( $c['href'] ); ?>" <?php echo ! empty( $c['target'] ) ? 'target="_blank" rel="noopener"' : ''; ?>>
                    <?php echo esc_html( $c['label'] ); ?>
                  </a>
                <?php else : ?>
                  <span><?php echo esc_html( $c['label'] ); ?></span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ( ( $acceso && $acceso !== 'Indefinido' ) || ! empty( $prestaciones ) || ! empty( $pagos ) ) : ?>
        <div class="ru-card">
          <h3 class="ru-card__title">Características</h3>

          <?php if ( ! empty( $prestaciones ) ) : ?>
            <div class="ru-chars__group">
              <span class="ru-chars__label">Prestaciones</span>
              <ul class="ru-chars__list">
                <?php foreach ( $prestaciones as $p ) : ?>
                  <li>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="color:<?php echo esc_attr( $color ); ?>;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php echo esc_html( trim( $p ) ); ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ( $acceso && $acceso !== 'Indefinido' ) : ?>
            <div class="ru-chars__group">
              <span class="ru-chars__label">Acceso</span>
              <span class="ru-chars__badge" style="background:<?php echo esc_attr( $color_soft ); ?>;color:<?php echo esc_attr( $color ); ?>;">
                <?php echo esc_html( $acceso ); ?>
              </span>
            </div>
          <?php endif; ?>

          <?php if ( ! empty( $pagos ) ) : ?>
            <div class="ru-chars__group">
              <span class="ru-chars__label">Medios de Pago</span>
              <ul class="ru-chars__list">
                <?php foreach ( $pagos as $pago ) : ?>
                  <li>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="color:<?php echo esc_attr( $color ); ?>;flex-shrink:0;"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    <?php echo esc_html( trim( $pago ) ); ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

        </div>
      <?php endif; ?>

      <?php if ( ! empty( $horarios ) ) : ?>
        <div class="ru-card">
          <h3 class="ru-card__title">Horarios</h3>
          <ul class="ru-costos__list">
            <?php foreach ( $horarios as $h ) :
              if ( empty( $h['titulo_horario'] ) && empty( $h['horario'] ) ) continue; ?>
              <li class="ru-costos__item">
                <span class="ru-costos__titulo"><?php echo esc_html( $h['titulo_horario'] ?? '' ); ?></span>
                <span class="ru-costos__precio" style="color:<?php echo esc_attr( $color ); ?>;"><?php echo esc_html( $h['horario'] ?? '' ); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ( ! empty( $costos ) ) : ?>
        <div class="ru-card">
          <h3 class="ru-card__title">Tarifario</h3>
          <ul class="ru-costos__list">
            <?php foreach ( $costos as $c ) :
              if ( empty( $c['titulo_costo'] ) && empty( $c['precio_costo'] ) ) continue; ?>
              <li class="ru-costos__item">
                <span class="ru-costos__titulo"><?php echo esc_html( $c['titulo_costo'] ?? '' ); ?></span>
                <span class="ru-costos__precio" style="color:<?php echo esc_attr( $color ); ?>;"><?php echo esc_html( $c['precio_costo'] ?? '' ); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

    </aside>

  </div><!-- /.ru-body -->

  <?php if ( ! empty( $galeria ) ) : ?>
  <section class="ru-gallery-section" id="galeria">
    <div class="ru-gallery">
      <?php foreach ( $galeria as $gi => $gimg ) :
        $g_url = is_array( $gimg ) ? ( $gimg['url'] ?? '' ) : wp_get_attachment_image_url( $gimg, 'large' );
        $g_alt = is_array( $gimg ) ? ( $gimg['alt'] ?? '' ) : '';
        if ( ! $g_url ) continue;
      ?>
        <button class="ru-gallery__item" type="button" data-lb="<?php echo esc_attr( $gi ); ?>" aria-label="<?php esc_attr_e( 'Ver imagen', 'misiones-2027' ); ?>">
          <img src="<?php echo esc_url( $g_url ); ?>" alt="<?php echo esc_attr( $g_alt ); ?>" loading="lazy">
        </button>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- ══════════════════════════════════════════
       RELACIONADOS / EXPLORAR
  ══════════════════════════════════════════ -->
  <?php if ( $modo_explorar && ! empty( $categorias_slug ) ) : ?>

    <?php get_template_part( 'template-parts/home/map', null, [
        'categoria' => implode( ',', $categorias_slug ),
        'titulo'    => 'Explorá',
    ] ); ?>

    <?php
      $explorar_query = new WP_Query( [
          'post_type'      => 'registro-unico',
          'post_status'    => 'publish',
          'posts_per_page' => 800,
          'post__not_in'   => [ $post_id ],
          'no_found_rows'  => true,
          'tax_query'      => [ [
              'taxonomy' => 'categoria-ru',
              'field'    => 'term_id',
              'terms'    => array_values( $terminos ),
              'operator' => 'IN',
          ] ],
      ] );
    ?>
    <?php if ( $explorar_query->have_posts() ) : ?>
    <section class="explorar-results" aria-label="<?php esc_attr_e( 'Resultados', 'misiones-2027' ); ?>">
      <div class="section-title">
        <div class="section-title__text">
          <span class="section-title__icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          </span>
          <h2 class="section-title__heading">
            <?php printf( esc_html__( '%d resultados', 'misiones-2027' ), $explorar_query->post_count ); ?>
          </h2>
        </div>
      </div>
      <div class="explorar-results__grid">
        <?php while ( $explorar_query->have_posts() ) : $explorar_query->the_post();
          $erc     = get_field( 'color' )       ?: '#1a5c4c';
          $erv     = get_field( 'hero_volanta' ) ?: '';
          $eri     = get_the_post_thumbnail_url( null, 'large' );
          $er_liked = function_exists( 'turs_user_liked' ) && turs_user_liked( get_the_ID() );
        ?>
          <div class="destino-card" data-poi-id="<?php the_ID(); ?>" style="background:<?php echo esc_attr( $erc ); ?>;">
            <a href="<?php the_permalink(); ?>" class="destino-card__link" aria-label="<?php the_title_attribute(); ?>"></a>
            <?php if ( $eri ) : ?>
              <img class="destino-card__img" src="<?php echo esc_url( $eri ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
            <?php endif; ?>
            <div class="destino-card__overlay"></div>
            <?php if ( $erv ) : ?>
              <div class="destino-card__tag" style="background:<?php echo esc_attr( $erc ); ?>;"><span><?php echo esc_html( $erv ); ?></span></div>
            <?php endif; ?>
            <button
              class="destino-card__fav js-turs-like<?php echo $er_liked ? ' is-liked' : ''; ?>"
              data-post-id="<?php the_ID(); ?>"
              data-liked="<?php echo $er_liked ? 'true' : 'false'; ?>"
              aria-label="<?php echo esc_attr( sprintf( __( 'Guardar %s en favoritos', 'misiones-2027' ), get_the_title() ) ); ?>"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
              </svg>
            </button>
            <div class="destino-card__info">
              <h3 class="destino-card__name"><?php the_title(); ?></h3>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </section>
    <?php endif; ?>

  <?php elseif ( ! empty( $terminos ) ) :
    $relacionados = new WP_Query( [
        'post_type'      => 'registro-unico',
        'posts_per_page' => 6,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
        'tax_query'      => [ [
            'taxonomy' => 'categoria-ru',
            'field'    => 'term_id',
            'terms'    => $terminos,
        ] ],
    ] );
    if ( $relacionados->have_posts() ) :
  ?>
    <section class="ru-related" aria-label="<?php esc_attr_e( 'También te puede interesar', 'misiones-2027' ); ?>">
      <div class="ru-related__head">
        <div class="section-title">
          <div class="section-title__text">
            <span class="section-title__icon" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            <h2 class="section-title__heading">También te puede interesar</h2>
          </div>
        </div>
      </div>
      <div class="destinos-carousel" role="list">
        <?php while ( $relacionados->have_posts() ) : $relacionados->the_post();
          $rc  = get_field( 'color' )        ?: '#1a5c4c';
          $rv  = get_field( 'hero_volanta' ) ?: '';
          $ri  = get_the_post_thumbnail_url( null, 'large' );
        ?>
          <a href="<?php the_permalink(); ?>" class="destino-card" role="listitem"
             data-poi-id="<?php the_ID(); ?>"
             style="background:<?php echo esc_attr( $rc ); ?>;"
             aria-label="<?php the_title_attribute(); ?>">
            <?php if ( $ri ) : ?>
              <img class="destino-card__img" src="<?php echo esc_url( $ri ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
            <?php endif; ?>
            <div class="destino-card__overlay"></div>
            <?php if ( $rv ) : ?>
              <div class="destino-card__tag" style="background:<?php echo esc_attr( $rc ); ?>;"><span><?php echo esc_html( $rv ); ?></span></div>
            <?php endif; ?>
            <div class="destino-card__info">
              <h3 class="destino-card__name"><?php the_title(); ?></h3>
              <div class="destino-card__rating">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="<?php echo esc_attr( $rc ); ?>" stroke="<?php echo esc_attr( $rc ); ?>" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="destino-card__rating-val">4.8</span>
              </div>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </section>
  <?php endif; endif; ?>

  <!-- ══════════════════════════════════════════
       MODAL DESTAQUES
  ══════════════════════════════════════════ -->
  <div class="ru-modal" id="ru-modal" role="dialog" aria-modal="true" aria-labelledby="ru-modal-title" hidden>
    <div class="ru-modal__backdrop"></div>
    <div class="ru-modal__box">
      <button class="ru-modal__close" type="button" aria-label="<?php esc_attr_e( 'Cerrar', 'misiones-2027' ); ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <div id="ru-modal-video" class="ru-modal__video"></div>
      <div class="ru-modal__body">
        <h3 class="ru-modal__title" id="ru-modal-title"></h3>
        <div id="ru-modal-gallery" class="ru-modal__gallery"></div>
        <p class="ru-modal__detalle" id="ru-modal-detalle"></p>
        <p class="ru-modal__desc" id="ru-modal-desc"></p>
      </div>
    </div>
  </div>

  <!-- ══════════════════════════════════════════
       FAB FAVORITO (mobile)
  ══════════════════════════════════════════ -->
  <?php if ( $tiene_coordenadas && function_exists( 'turs_user_liked' ) ) :
    $_turs_liked_fab = turs_user_liked( $post_id );
  ?>
    <button
      class="ru-fav-fab js-turs-like<?php echo $_turs_liked_fab ? ' is-liked' : ''; ?>"
      type="button"
      data-post-id="<?php echo esc_attr( $post_id ); ?>"
      data-liked="<?php echo $_turs_liked_fab ? 'true' : 'false'; ?>"
      aria-label="<?php echo $_turs_liked_fab ? esc_attr__( 'Quitar de ruta de viaje', 'misiones-2027' ) : esc_attr__( 'Agregar a ruta de viaje', 'misiones-2027' ); ?>"
    >
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/>
        <circle cx="12" cy="10" r="3"/>
      </svg>
    </button>
  <?php endif; ?>

  <!-- ══════════════════════════════════════════
       LIGHTBOX GALERÍA
  ══════════════════════════════════════════ -->
  <div class="ru-lightbox" id="ru-lightbox" hidden>
    <div class="ru-lightbox__backdrop"></div>
    <button class="ru-lightbox__close" type="button" aria-label="<?php esc_attr_e( 'Cerrar', 'misiones-2027' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <button class="ru-lightbox__prev" type="button" aria-label="<?php esc_attr_e( 'Anterior', 'misiones-2027' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    <button class="ru-lightbox__next" type="button" aria-label="<?php esc_attr_e( 'Siguiente', 'misiones-2027' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    </button>
    <img class="ru-lightbox__img" id="ru-lightbox-img" src="" alt="">
  </div>

</main>

<script>
(function () {
  var COLOR = '<?php echo esc_js( $color ); ?>';

  /* ── Galería lightbox ─────────────────────── */
  var gallery = <?php echo $galeria_json; ?>;
  var lb      = document.getElementById('ru-lightbox');
  var lbImg   = document.getElementById('ru-lightbox-img');
  var lbIdx   = 0;

  function openLB(i) {
    lbIdx = i; lbImg.src = gallery[i].url; lbImg.alt = gallery[i].alt || '';
    lb.hidden = false; document.body.style.overflow = 'hidden';
  }
  function closeLB() { lb.hidden = true; lbImg.src = ''; document.body.style.overflow = ''; }
  function moveLB(d) { lbIdx = (lbIdx + d + gallery.length) % gallery.length; lbImg.src = gallery[lbIdx].url; }

  document.querySelectorAll('.ru-gallery__item').forEach(function (btn) {
    btn.addEventListener('click', function () { openLB(parseInt(btn.dataset.lb)); });
  });
  if (lb) {
    lb.querySelector('.ru-lightbox__backdrop').addEventListener('click', closeLB);
    lb.querySelector('.ru-lightbox__close').addEventListener('click', closeLB);
    lb.querySelector('.ru-lightbox__prev').addEventListener('click', function () { moveLB(-1); });
    lb.querySelector('.ru-lightbox__next').addEventListener('click', function () { moveLB(1); });
  }

  /* ── Modal destaques ─────────────────────── */
  var modal    = document.getElementById('ru-modal');
  var mTitle   = document.getElementById('ru-modal-title');
  var mVideo   = document.getElementById('ru-modal-video');
  var mGal     = document.getElementById('ru-modal-gallery');
  var mDetalle = document.getElementById('ru-modal-detalle');
  var mDesc    = document.getElementById('ru-modal-desc');
  var mGalData = [];
  var mGalIdx  = 0;

  function renderGalSlide() {
    if (!mGalData.length) { mGal.innerHTML = ''; return; }
    var u = mGalData[mGalIdx];
    var multi = mGalData.length > 1;
    mGal.innerHTML =
      '<img src="' + u + '" alt="">' +
      (multi ? '<button class="ru-modal__gal-btn ru-modal__gal-btn--prev js-gal-prev" type="button"' + (mGalIdx > 0 ? '' : ' disabled') + ' aria-label="Anterior">' +
        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg></button>' : '') +
      (multi ? '<button class="ru-modal__gal-btn ru-modal__gal-btn--next js-gal-next" type="button"' + (mGalIdx < mGalData.length - 1 ? '' : ' disabled') + ' aria-label="Siguiente">' +
        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg></button>' : '') +
      (multi ? '<span class="ru-modal__gal-count">' + (mGalIdx + 1) + ' / ' + mGalData.length + '</span>' : '');
    var bp = mGal.querySelector('.js-gal-prev');
    var bn = mGal.querySelector('.js-gal-next');
    if (bp) bp.addEventListener('click', function () { mGalIdx--; renderGalSlide(); });
    if (bn) bn.addEventListener('click', function () { mGalIdx++; renderGalSlide(); });
  }

  function openModal(btn) {
    mTitle.textContent   = btn.dataset.titulo  || '';
    mDetalle.textContent = btn.dataset.detalle || '';
    mDesc.textContent    = btn.dataset.desc    || '';
    var yt = btn.dataset.video || '';
    mGalData = [];
    mGalIdx  = 0;
    try { mGalData = JSON.parse(btn.dataset.galeria || '[]'); } catch(e) {}
    if (btn.dataset.portada) {
      mGalData = mGalData.filter(function(u){ return u !== btn.dataset.portada; });
      mGalData.unshift(btn.dataset.portada);
    }

    mVideo.innerHTML = yt
      ? '<div class="ru-modal__video-wrap"><iframe src="https://www.youtube-nocookie.com/embed/' + yt + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>'
      : '';

    renderGalSlide();

    modal.hidden = false;
    modal.scrollTop = 0;
    document.body.style.overflow = 'hidden';
    modal.querySelector('.ru-modal__close').focus();
  }

  function closeModal() {
    modal.hidden = true; mVideo.innerHTML = ''; mGalData = []; document.body.style.overflow = '';
  }

  document.querySelectorAll('[data-modal="destaque"]').forEach(function (btn) {
    btn.addEventListener('click', function () { openModal(btn); });
  });
  if (modal) {
    modal.querySelector('.ru-modal__backdrop').addEventListener('click', closeModal);
    modal.querySelector('.ru-modal__close').addEventListener('click', closeModal);
  }

  /* ── Galería: drag-to-scroll ─────────────────────────────────────── */
  (function () {
    var el = document.querySelector('.ru-gallery-section .ru-gallery');
    if (!el) return;
    var isDown = false, startX, scrollLeft;
    el.addEventListener('mousedown', function (e) {
      isDown = true; startX = e.pageX - el.offsetLeft; scrollLeft = el.scrollLeft;
      el.style.scrollSnapType = 'none';
    });
    el.addEventListener('mouseleave', function () { isDown = false; el.style.scrollSnapType = ''; });
    el.addEventListener('mouseup',    function () { isDown = false; el.style.scrollSnapType = ''; });
    el.addEventListener('mousemove',  function (e) {
      if (!isDown) return;
      e.preventDefault();
      el.scrollLeft = scrollLeft - (e.pageX - el.offsetLeft - startX) * 1.5;
    });
  })();

  /* ── FAV FAB: mostrar en desktop al salir del hero ─────────────── */
  (function () {
    var hero    = document.querySelector('.ru-hero');
    var favFab  = document.querySelector('.ru-fav-fab');
    var heroFav = document.querySelector('.ru-hero__fav');

    // En mobile: quitar el botón del hero del DOM definitivamente
    if (heroFav && window.innerWidth < 1024) {
      heroFav.remove();
      heroFav = null;
    }

    if (!hero || !favFab) return;
    var obs = new IntersectionObserver(function (entries) {
      favFab.classList.toggle('is-visible', !entries[0].isIntersecting);
    }, { threshold: 0 });
    obs.observe(hero);
  })();

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (!modal.hidden) closeModal();
      if (!lb.hidden)    closeLB();
    }
    if (!modal.hidden && e.key === 'ArrowLeft'  && mGalIdx > 0)                  { mGalIdx--; renderGalSlide(); }
    if (!modal.hidden && e.key === 'ArrowRight' && mGalIdx < mGalData.length - 1) { mGalIdx++; renderGalSlide(); }
    if (!lb.hidden && e.key === 'ArrowLeft')  moveLB(-1);
    if (!lb.hidden && e.key === 'ArrowRight') moveLB(1);
  });
})();
</script>

<?php
endwhile;
get_footer();
