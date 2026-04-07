( function () {
    'use strict';

    const menuToggle   = document.getElementById( 'menu-toggle' );
    const mobileMenu   = document.getElementById( 'mobile-menu' );
    const menuOverlay  = document.getElementById( 'mobile-menu-overlay' );
    const searchToggle = document.getElementById( 'search-toggle' );
    const searchClose  = document.getElementById( 'search-close' );
    const searchPanel  = document.getElementById( 'search-panel' );
    const body         = document.body;

    function openMenu() {
        mobileMenu.classList.add( 'is-open' );
        menuOverlay.classList.add( 'is-open' );
        menuToggle.classList.add( 'is-open' );
        menuToggle.setAttribute( 'aria-expanded', 'true' );
        body.style.overflow = 'hidden';
    }

    function closeMenu() {
        mobileMenu.classList.remove( 'is-open' );
        menuOverlay.classList.remove( 'is-open' );
        menuToggle.classList.remove( 'is-open' );
        menuToggle.setAttribute( 'aria-expanded', 'false' );
        body.style.overflow = '';
    }

    function openSearch() {
        closeMenu(); // cerrar menu mobile si está abierto
        searchPanel.classList.add( 'is-open' );
        searchToggle.setAttribute( 'aria-expanded', 'true' );
        searchPanel.querySelector( 'input[type="search"]' )?.focus();
    }

    function closeSearch() {
        searchPanel.classList.remove( 'is-open' );
        searchToggle.setAttribute( 'aria-expanded', 'false' );
    }

    menuToggle?.addEventListener( 'click', function () {
        mobileMenu.classList.contains( 'is-open' ) ? closeMenu() : openMenu();
    } );

    // Cerrar al tocar el overlay
    menuOverlay?.addEventListener( 'click', closeMenu );

    searchToggle?.addEventListener( 'click', openSearch );
    searchClose?.addEventListener( 'click', closeSearch );

    // Cerrar con Escape
    document.addEventListener( 'keydown', function ( e ) {
        if ( e.key === 'Escape' ) {
            closeMenu();
            closeSearch();
        }
    } );

    // ── Submenus desktop: abrir/cerrar con click ──
    document.querySelectorAll( '#primary-nav .menu-item-has-children > a' ).forEach( function ( link ) {
        link.addEventListener( 'click', function ( e ) {
            if ( window.innerWidth < 768 ) return;

            e.preventDefault();
            const parent = link.parentElement;
            const isOpen = parent.classList.contains( 'is-open' );

            document.querySelectorAll( '#primary-nav .menu-item-has-children.is-open' ).forEach( function ( el ) {
                el.classList.remove( 'is-open' );
                el.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
            } );

            if ( ! isOpen ) {
                parent.classList.add( 'is-open' );
                link.setAttribute( 'aria-expanded', 'true' );
            }
        } );
    } );

    // Cerrar submenus al hacer click fuera
    document.addEventListener( 'click', function ( e ) {
        if ( ! e.target.closest( '#primary-nav' ) ) {
            document.querySelectorAll( '#primary-nav .menu-item-has-children.is-open' ).forEach( function ( el ) {
                el.classList.remove( 'is-open' );
                el.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
            } );
        }
    } );

    // ── Descubrí: dots de paginación ──
    const descubrScroll = document.getElementById( 'descubri-scroll' );
    const descubrDots   = document.querySelectorAll( '#descubri-dots .descubri-dot' );

    if ( descubrScroll && descubrDots.length ) {
        descubrScroll.addEventListener( 'scroll', function () {
            const cardWidth = descubrScroll.querySelector( '.descubri-card' )?.offsetWidth || 1;
            const gap = 16; // gap-4 = 1rem = 16px
            const index = Math.round( descubrScroll.scrollLeft / ( cardWidth + gap ) );
            descubrDots.forEach( function ( dot, i ) {
                dot.classList.toggle( 'descubri-dot--active', i === index );
            } );
        }, { passive: true } );
    }

    // Header con fondo al hacer scroll
    const header = document.getElementById( 'site-header' );
    if ( header ) {
        window.addEventListener( 'scroll', function () {
            header.classList.toggle( 'scrolled', window.scrollY > 50 );
        }, { passive: true } );
    }
} )();
