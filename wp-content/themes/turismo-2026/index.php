<?php get_header(); ?>

<?php if ( is_front_page() ) : ?>
<section id="hero"
    class="relative bg-tertiary bg-cover bg-center bg-no-repeat flex flex-col"
    style="max-height:450px; min-height:380px; background-image: url('<?php echo esc_url( TURISMO_URI . '/assets/images/bg-home.jpg' ); ?>')">

    <!-- Overlay degradado -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-tertiary/80"></div>

    <!-- Contenido hero -->
    <div class="relative z-10 flex-1 flex flex-col justify-end px-6 pb-10 md:justify-center md:pb-0 md:px-16 max-w-[1200px] mx-auto w-full">
        <p class="text-white text-lg md:text-2xl font-light tracking-widest uppercase ">Lanzarte a la</p>
        <h1 class="text-white text-5xl md:text-7xl font-black uppercase leading-none mb-8">Aventura</h1>
        <a href="#" class="inline-flex items-center gap-3 bg-secondary hover:bg-secondary/90 text-white font-semibold uppercase px-6 py-3 rounded-2xl w-fit transition-colors duration-200">
            <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/arrow.svg' ); ?>" alt="" width="16" height="16" aria-hidden="true">
            Ver video
        </a>
    </div>

</section>

<!-- Iconos acceso rápido -->
<div class="flex items-center justify-center gap-6 px-6 bg-gradient-to-b from-tertiary to-tertiary/0">
    <a href="#" class="hero-quick-icon" aria-label="Alojamiento">
        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/bed.svg' ); ?>" alt="Alojamiento" width="32" height="32">
    </a>
    <a href="#" class="hero-quick-icon" aria-label="Mapa">
        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/map.svg' ); ?>" alt="Mapa" width="32" height="32">
    </a>
    <a href="#" class="hero-quick-icon" aria-label="Agenda">
        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/calendar.svg' ); ?>" alt="Agenda" width="32" height="32">
    </a>
    <a href="#" class="hero-quick-icon" aria-label="Ubicación">
        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/location.svg' ); ?>" alt="Ubicación" width="32" height="32">
    </a>
</div>

<!-- Cards scroll horizontal -->
<section class="overflow-x-auto scrollbar-none py-6">
<div class="flex gap-4 pl-5 snap-x snap-mandatory w-max">

    <!-- Card -->
    <a href="#" class="home-card snap-start">
        <div class="absolute inset-0 bg-gray-400">
            <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/img-1.jpg' ); ?>" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        <div class="relative z-10 mt-auto">
            <h2 class="text-white font-bold text-xl leading-tight mb-3">Reducciones Jesuíticas Guaraní</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/gps.svg' ); ?>" alt="" width="18" height="18" aria-hidden="true">
                    <span class="text-white text-sm">San Ignacio</span>
                </div>
                <span class="home-card-arrow">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/arrow-right.svg' ); ?>" alt="" width="16" height="16" aria-hidden="true">
                </span>
            </div>
        </div>
    </a>

    <!-- Card 2 -->
    <a href="#" class="home-card snap-start">
        <div class="absolute inset-0 bg-gray-500">
            <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/img-2.jpg' ); ?>" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        <div class="relative z-10 mt-auto">
            <h2 class="text-white font-bold text-xl leading-tight mb-3">Cataratas del Iguazú</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/gps.svg' ); ?>" alt="" width="18" height="18" aria-hidden="true">
                    <span class="text-white text-sm">Puerto Iguazú</span>
                </div>
                <span class="home-card-arrow">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/arrow-right.svg' ); ?>" alt="" width="16" height="16" aria-hidden="true">
                </span>
            </div>
        </div>
    </a>

    <div style="width:0.1rem; flex-shrink:0" aria-hidden="true"></div>
</div>
</section>

<!-- Bloque imagen remo -->
<div style="padding: 0 1.25rem 1.5rem;">
    <div class="relative overflow-hidden flex items-center justify-center"
         style="background-image: url('<?php echo esc_url( TURISMO_URI . '/assets/images/remo.jpg' ); ?>'); background-size: cover; background-position: center; border-radius: 1.3rem; box-shadow: 0 8px 24px rgba(0,0,0,0.25); padding-top: 2rem; padding-bottom: 2rem;">
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.3); border-radius: 1.3rem;"></div>
        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/ra.png' ); ?>"
             alt="Logo"
             style="width:150px; height:auto; position:relative; z-index:10;">
    </div>
</div>

<!-- Scroll posts: Descubrí -->
<section class="py-6" id="descubri-section">
    <h2 style="color:#35C071; font-size:1.4rem; font-weight:800; padding: 0 1.25rem 0.4rem;">Descubrí</h2>

    <!-- Scroll container -->
    <div class="overflow-x-auto scrollbar-none" id="descubri-scroll">
        <div class="flex gap-4 snap-x snap-mandatory" style="padding-left:1.25rem; width:max-content;">

            <!-- Post card 1 -->
            <a href="#" class="descubri-card snap-start" style="flex-shrink:0;">
                <div class="absolute inset-0">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/img-1.jpg' ); ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 mt-auto flex items-end justify-between gap-4">
                    <h3 class="text-white font-black text-md leading-tight">Travesías por<br>Parques y Reservas</h3>
                    <span class="descubri-arrow" style="flex-shrink:0;">
                        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/arrow-right.svg' ); ?>" alt="" width="16" height="16" aria-hidden="true">
                    </span>
                </div>
            </a>

            <!-- Post card 2 -->
            <a href="#" class="descubri-card snap-start" style="flex-shrink:0;">
                <div class="absolute inset-0">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/img-2.jpg' ); ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 mt-auto flex items-end justify-between gap-4">
                    <h3 class="text-white font-black text-md leading-tight">Cataratas del Iguazú</h3>
                    <span class="descubri-arrow" style="flex-shrink:0;">
                        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/arrow-right.svg' ); ?>" alt="" width="16" height="16" aria-hidden="true">
                    </span>
                </div>
            </a>

            <!-- Post card 3 -->
            <a href="#" class="descubri-card snap-start" style="flex-shrink:0;">
                <div class="absolute inset-0">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/img-1.jpg' ); ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 mt-auto flex items-end justify-between gap-4">
                    <h3 class="text-white font-black text-md leading-tight">Reducciones Jesuíticas</h3>
                    <span class="descubri-arrow" style="flex-shrink:0;">
                        <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/arrow-right.svg' ); ?>" alt="" width="16" height="16" aria-hidden="true">
                    </span>
                </div>
            </a>

            <div style="width:0.1rem; flex-shrink:0;" aria-hidden="true"></div>
        </div>
    </div>

    <!-- Dots -->
    <div class="flex items-center gap-2" style="padding: 1rem 1.25rem 0;" id="descubri-dots">
        <span class="descubri-dot descubri-dot--active"></span>
        <span class="descubri-dot"></span>
        <span class="descubri-dot"></span>
    </div>
</section>

<?php endif; ?>

<main id="main" class="container mx-auto px-4 py-12">
    <?php if ( have_posts() && ! is_front_page() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-8' ); ?>>
            <h2 class="text-2xl font-bold mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
            </h2>
            <div class="entry-content text-gray-600"><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
