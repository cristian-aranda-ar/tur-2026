<form role="search" method="get" class="flex flex-1 items-center gap-4" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search"
           name="s"
           placeholder="<?php esc_attr_e( 'Buscar...', 'turismo-2026' ); ?>"
           value="<?php echo get_search_query(); ?>"
           class="flex-1 bg-transparent border-b-2 border-primary text-white placeholder-white/60 text-lg outline-none py-2">
    <button type="submit" class="header-icon-btn" aria-label="<?php esc_attr_e( 'Buscar', 'turismo-2026' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
    </button>
</form>
