/**
 * Misiones 2027 — Main JS
 * Hero slider · Header scroll · Mobile menu · Language panel · Chat FAB · Experience profiles
 */
(function () {
  'use strict';

  // ─────────────────────────────────────────────
  // HERO SLIDER
  // ─────────────────────────────────────────────
  const heroSlides = document.querySelectorAll('.hero__slide');
  const heroDots   = document.querySelectorAll('.hero__dot');
  const heroContents = document.querySelectorAll('.hero__content-item');

  let currentSlide = 0;
  let sliderTimer  = null;

  function goToSlide(index) {
    if (!heroSlides.length) return;

    heroSlides[currentSlide].classList.remove('is-active');
    heroDots[currentSlide]?.classList.remove('is-active');
    heroContents[currentSlide] && (heroContents[currentSlide].style.display = 'none');

    currentSlide = (index + heroSlides.length) % heroSlides.length;

    heroSlides[currentSlide].classList.add('is-active');
    heroDots[currentSlide]?.classList.add('is-active');
    if (heroContents[currentSlide]) {
      heroContents[currentSlide].style.display = '';
      heroContents[currentSlide].style.animation = 'fadeInUp .5s ease';
    }
    // Update aria-selected on dots
    heroDots.forEach((d, i) => d.setAttribute('aria-selected', i === currentSlide ? 'true' : 'false'));
    // Update aria-hidden on slides
    heroSlides.forEach((s, i) => s.setAttribute('aria-hidden', i === currentSlide ? 'false' : 'true'));
  }

  function startSlider() {
    if (heroSlides.length < 2) return;
    sliderTimer = setInterval(() => goToSlide(currentSlide + 1), 4000);
  }

  if (heroSlides.length) {
    // Initialise first slide
    heroSlides[0].classList.add('is-active');
    heroDots[0]?.classList.add('is-active');

    // Dot clicks
    heroDots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        clearInterval(sliderTimer);
        goToSlide(i);
        startSlider();
      });
    });

    startSlider();
  }

  // ─────────────────────────────────────────────
  // HEADER SCROLL TRANSPARENCY
  // ─────────────────────────────────────────────
  const header = document.querySelector('.site-header');
  const isFrontPage = document.body.classList.contains('is-front-page');

  function updateHeader() {
    if (!header) return;
    if (isFrontPage) {
      if (window.scrollY > 60) {
        header.classList.remove('site-header--transparent');
        header.classList.add('site-header--solid');
      } else {
        header.classList.add('site-header--transparent');
        header.classList.remove('site-header--solid');
      }
    } else {
      header.classList.remove('site-header--transparent');
      header.classList.add('site-header--solid');
    }
  }

  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();

  // ─────────────────────────────────────────────
  // MOBILE MENU OVERLAY
  // ─────────────────────────────────────────────
  const btnMenu         = document.querySelector('.btn-menu');
  const menuOverlay     = document.querySelector('.menu-overlay');
  const btnMenuClose    = document.querySelector('.btn-menu-close');
  const backdrop        = document.querySelector('.overlay-backdrop');

  function openMenu() {
    menuOverlay?.classList.remove('is-hidden');
    backdrop?.classList.remove('is-hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    menuOverlay?.classList.add('is-hidden');
    backdrop?.classList.add('is-hidden');
    document.body.style.overflow = '';
  }

  btnMenu?.addEventListener('click', openMenu);
  btnMenuClose?.addEventListener('click', closeMenu);
  backdrop?.addEventListener('click', closeAll);

  // ─────────────────────────────────────────────
  // LOGIN MODAL
  // ─────────────────────────────────────────────
  const btnIngresar   = document.querySelector('.btn-ingresar');
  const loginModal    = document.querySelector('.login-modal');
  const btnLoginClose = document.querySelector('.login-modal__close');

  function openLogin() {
    loginModal?.classList.remove('is-hidden');
    backdrop?.classList.remove('is-hidden');
    document.body.style.overflow = 'hidden';
  }
  function closeLogin() {
    loginModal?.classList.add('is-hidden');
    backdrop?.classList.add('is-hidden');
    document.body.style.overflow = '';
  }

  window.tursOpenLogin = openLogin;

  // Si es botón de usuario logueado abre el panel; si es el link de login abre el modal
  const btnIngresarLogged = document.querySelector('.btn-ingresar--logged');
  if (btnIngresarLogged) {
    // No conectar el btn-ingresar genérico al login si hay uno de usuario
  } else {
    btnIngresar?.addEventListener('click', (e) => { e.preventDefault(); openLogin(); });
  }
  btnLoginClose?.addEventListener('click', closeLogin);

  // ─────────────────────────────────────────────
  // USER PANEL
  // ─────────────────────────────────────────────
  const userPanel      = document.getElementById('user-panel');
  const btnsOpenPanel  = document.querySelectorAll('.js-user-panel-open');
  const btnsClosePanel = document.querySelectorAll('.js-user-panel-close');

  function openUserPanel() {
    if (!userPanel) return;
    userPanel.classList.add('is-open');
    backdrop?.classList.remove('is-hidden');
    document.body.style.overflow = 'hidden';
    btnsOpenPanel.forEach(b => b.setAttribute('aria-expanded', 'true'));
  }
  function closeUserPanel() {
    if (!userPanel) return;
    userPanel.classList.remove('is-open');
    backdrop?.classList.add('is-hidden');
    document.body.style.overflow = '';
    btnsOpenPanel.forEach(b => b.setAttribute('aria-expanded', 'false'));
  }

  btnsOpenPanel.forEach(b => b.addEventListener('click', openUserPanel));
  btnsClosePanel.forEach(b => b.addEventListener('click', closeUserPanel));

  // Unlike individual desde el panel
  document.addEventListener('click', function (e) {
    const delBtn = e.target.closest('.js-panel-unlike');
    if (!delBtn) return;
    e.preventDefault();
    var d = window.tursData || {};
    if (!d.ajaxUrl) return;
    var postId = delBtn.dataset.postId;
    var fd = new FormData();
    fd.append('action',  'turs_toggle_like');
    fd.append('nonce',   d.nonceLike);
    fd.append('post_id', postId);
    delBtn.disabled = true;
    fetch(d.ajaxUrl, { method: 'POST', body: fd })
      .then(r => r.json())
      .then(res => {
        if (res.success && !res.data.liked) {
          var li = delBtn.closest('li');
          if (li) li.remove();
          // Si la lista quedó vacía, recargar para mostrar empty state
          var list = document.getElementById('panel-likes-list');
          if (list && !list.querySelector('li')) location.reload();
        }
      })
      .catch(() => { delBtn.disabled = false; });
  });

  // Vaciar toda la ruta de viaje
  var clearLikesBtn = document.querySelector('.js-panel-clear-likes');
  if (clearLikesBtn) {
    clearLikesBtn.addEventListener('click', function () {
      if (!confirm('¿Vaciar toda la ruta de viaje?')) return;
      var d = window.tursData || {};
      if (!d.ajaxUrl) return;
      var fd = new FormData();
      fd.append('action', 'turs_clear_likes');
      fd.append('nonce',  d.nonceLike);
      clearLikesBtn.disabled = true;
      fetch(d.ajaxUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => { if (res.success) location.reload(); })
        .catch(() => { clearLikesBtn.disabled = false; });
    });
  }

  // ─────────────────────────────────────────────
  // LANGUAGE PANEL / DROPDOWN
  // ─────────────────────────────────────────────
  const btnLang        = document.querySelector('.btn-lang');
  const langPanel      = document.querySelector('.lang-panel');
  const btnLangClose   = document.querySelector('.btn-lang-close');
  const langDropMenu   = document.querySelector('.lang-dropdown__menu');

  const isDesktop = () => window.innerWidth >= 1024;

  function openLang() {
    if (isDesktop()) {
      const isOpen = !langDropMenu?.classList.contains('is-hidden');
      if (isOpen) { closeLang(); return; }
      langDropMenu?.classList.remove('is-hidden');
      btnLang?.setAttribute('aria-expanded', 'true');
    } else {
      langPanel?.classList.remove('is-hidden');
      backdrop?.classList.remove('is-hidden');
      document.body.style.overflow = 'hidden';
    }
  }
  function closeLang() {
    langDropMenu?.classList.add('is-hidden');
    btnLang?.setAttribute('aria-expanded', 'false');
    langPanel?.classList.add('is-hidden');
    backdrop?.classList.add('is-hidden');
    document.body.style.overflow = '';
  }

  btnLang?.addEventListener('click', (e) => { e.stopPropagation(); openLang(); });
  btnLangClose?.addEventListener('click', closeLang);

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (langDropMenu && !langDropMenu.classList.contains('is-hidden')) {
      if (!document.querySelector('.lang-dropdown')?.contains(e.target)) {
        closeLang();
      }
    }
  });

  // ─────────────────────────────────────────────
  // CLOSE ALL OVERLAYS
  // ─────────────────────────────────────────────
  function closeAll() {
    closeMenu();
    closeLang();
    closeLogin();
    closeUserPanel();
    document.body.style.overflow = '';
  }

  // ─────────────────────────────────────────────
  // WHATSAPP FAB — enlace directo, sin lógica JS
  // ─────────────────────────────────────────────

  // ─────────────────────────────────────────────
  // EXPERIENCE PROFILES — color inicial
  // ─────────────────────────────────────────────
  document.querySelectorAll('.profile-btn').forEach(btn => {
    const color = btn.dataset.color || 'var(--c-primary)';
    btn.style.background = color;
    const icon  = btn.querySelector('.profile-btn__icon');
    const label = btn.querySelector('.profile-btn__label');
    const desc  = btn.querySelector('.profile-btn__desc');
    if (icon)  icon.style.color  = '#fff';
    if (label) label.style.color = '#fff';
    if (desc)  desc.style.color  = 'rgba(255,255,255,.75)';
  });

  // ─────────────────────────────────────────────
  // BOTTOM NAV — active state
  // ─────────────────────────────────────────────
  const bottomNavItems = document.querySelectorAll('.bottom-nav__item');
  const currentPath = window.location.pathname;

  bottomNavItems.forEach(item => {
    const href = item.getAttribute('href') || '';
    if (href && currentPath === href) {
      item.classList.add('is-active');
    }
    item.addEventListener('click', () => {
      bottomNavItems.forEach(i => i.classList.remove('is-active'));
      item.classList.add('is-active');
    });
  });

  // ─────────────────────────────────────────────
  // DESTINO CARD FAV TOGGLE — manejado por tur-social.js vía AJAX

  // ─────────────────────────────────────────────
  // DRAG-TO-SCROLL carousels
  // ─────────────────────────────────────────────
  document.querySelectorAll('.destinos-carousel, .scroll-x').forEach(el => {
    let isDown    = false;
    let startX    = 0;
    let scrollLeft = 0;
    let hasDragged = false;

    el.addEventListener('mousedown', e => {
      isDown     = true;
      hasDragged = false;
      startX     = e.pageX;
      scrollLeft = el.scrollLeft;
      el.style.cursor = 'grabbing';
      el.style.scrollSnapType = 'none';
    });

    document.addEventListener('mouseup', () => {
      if (!isDown) return;
      isDown = false;
      el.style.cursor = 'grab';
      el.style.scrollSnapType = '';
    });

    document.addEventListener('mousemove', e => {
      if (!isDown) return;
      e.preventDefault();
      const dx = e.pageX - startX;
      if (Math.abs(dx) > 4) hasDragged = true;
      el.scrollLeft = scrollLeft - dx;
    });

    // Evitar que un drag dispare un click en los links internos
    el.addEventListener('click', e => {
      if (hasDragged) e.preventDefault();
    }, true);

    el.style.cursor = 'grab';
  });

  // ─────────────────────────────────────────────
  // SEARCH BAR — typewriter placeholder
  // ─────────────────────────────────────────────
  const searchInput = document.querySelector('.search-bar__input');

  if (searchInput) {
    const phrases = [
      'Quiero hacer trekking',
      'Necesito alojamiento en Iguazú',
      '¿Qué actividades hay esta semana?',
      'Ruta para ir desde Iguazú a minas de Wanda',
      '¿Dónde ver yaguaretés en Misiones?',
      'Cabañas cerca de los Saltos del Moconá',
    ];

    let phraseIndex = 0;
    let charIndex   = 0;
    let isDeleting  = false;
    let timer       = null;

    function typeStep() {
      const phrase = phrases[phraseIndex];

      if (isDeleting) {
        charIndex--;
        searchInput.setAttribute('placeholder', phrase.slice(0, charIndex));
        const delay = charIndex === 0 ? 500 : 35 + Math.random() * 20;
        if (charIndex === 0) {
          isDeleting = false;
          phraseIndex = (phraseIndex + 1) % phrases.length;
        }
        timer = setTimeout(typeStep, delay);
      } else {
        charIndex++;
        searchInput.setAttribute('placeholder', phrase.slice(0, charIndex));
        const delay = charIndex === phrase.length
          ? 1800
          : 80 + Math.random() * 60;
        if (charIndex === phrase.length) {
          isDeleting = true;
        }
        timer = setTimeout(typeStep, delay);
      }
    }

    // Clear placeholder on focus, resume on blur
    searchInput.addEventListener('focus', () => {
      clearTimeout(timer);
      searchInput.setAttribute('placeholder', '');
    });
    searchInput.addEventListener('blur', () => {
      if (!searchInput.value) typeStep();
    });

    setTimeout(typeStep, 800);
  }

  // ─────────────────────────────────────────────
  // SMOOTH SCROLL for anchor links
  // ─────────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ─────────────────────────────────────────────
  // MAP FILTER — localities tag input
  // ─────────────────────────────────────────────
  const tagWrap  = document.getElementById('map-localities-wrap');
  const tagInput = document.getElementById('map-locality-input');
  const tagsList = document.getElementById('map-tags');
  const dropdown = document.getElementById('map-dropdown');

  if (tagWrap && tagInput) {
    const allLocalities = JSON.parse(tagWrap.dataset.localities || '[]');
    let selected = [];

    function renderTags() {
      tagsList.innerHTML = selected.map(loc =>
        `<span class="map-tag" data-loc="${loc}">
          ${loc}
          <button class="map-tag__remove" aria-label="Quitar ${loc}" data-remove="${loc}">×</button>
        </span>`
      ).join('');
      tagsList.querySelectorAll('.map-tag__remove').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          selected = selected.filter(l => l !== btn.dataset.remove);
          renderTags();
          renderDropdown(tagInput.value);
          applyMapFilters();
        });
      });
      applyMapFilters();
    }

    function renderDropdown(query) {
      const q = query.toLowerCase().trim();
      const filtered = allLocalities.filter(l =>
        l.toLowerCase().includes(q) && !selected.includes(l)
      );
      if (!filtered.length) {
        dropdown.innerHTML = `<li class="map-tag-input__option map-tag-input__option--empty">Sin resultados</li>`;
      } else {
        dropdown.innerHTML = filtered.map(l =>
          `<li class="map-tag-input__option" role="option" data-loc="${l}">${l}</li>`
        ).join('');
        dropdown.querySelectorAll('.map-tag-input__option').forEach(opt => {
          opt.addEventListener('mousedown', (e) => {
            e.preventDefault();
            selected.push(opt.dataset.loc);
            tagInput.value = '';
            renderTags();
            renderDropdown('');
            applyMapFilters();
          });
        });
      }
    }

    tagInput.addEventListener('focus', () => {
      tagWrap.classList.add('is-open');
      renderDropdown(tagInput.value);
    });
    tagInput.addEventListener('input', () => renderDropdown(tagInput.value));
    tagInput.addEventListener('blur', () => {
      setTimeout(() => tagWrap.classList.remove('is-open'), 150);
    });
    tagWrap.querySelector('.map-tag-input__inner').addEventListener('click', () => tagInput.focus());
  }

  // ─────────────────────────────────────────────
  // SINGLE RECORD MAP
  // ─────────────────────────────────────────────
  const singleMapEl = document.getElementById('ru-single-map');
  if (singleMapEl && typeof L !== 'undefined') {
    const lat   = parseFloat(singleMapEl.dataset.lat);
    const lon   = parseFloat(singleMapEl.dataset.lon);
    const name  = singleMapEl.dataset.name  || '';
    const color = singleMapEl.dataset.color || '#1a5c4c';

    const singleMap = L.map('ru-single-map', {
      center: [lat, lon],
      zoom: 10,
      zoomControl: true,
      scrollWheelZoom: false,
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19,
    }).addTo(singleMap);

    const icon = L.divIcon({
      className: '',
      html: `<div class="map-poi-marker" style="background:${color};"></div>`,
      iconSize: [14, 14],
      iconAnchor: [7, 7],
    });

    L.marker([lat, lon], { icon }).addTo(singleMap)
      .bindTooltip(name, { permanent: true, direction: 'top', offset: [0, -10], className: 'ru-map-tooltip' })
      .openTooltip();
  }

  // ─────────────────────────────────────────────
  // MAP POI PANEL
  // ─────────────────────────────────────────────
  const poiPanel      = document.getElementById('map-poi-panel');
  const poiPanelClose = poiPanel?.querySelector('.map-poi-panel__close');
  const poiBackdrop   = document.getElementById('map-poi-backdrop');
  const poiDataCache  = {};  // postId → poi data para actualizar el panel de favoritos

  function openPoiPanel(poi) {
    if (!poiPanel) return;

    // Cachear datos para la actualización en tiempo real del panel de favoritos
    if (poi.id) poiDataCache[poi.id] = poi;

    // Imagen
    const imgEl = poiPanel.querySelector('.map-poi-panel__img');
    const imgSrc = poi.image_md || poi.image || '';
    imgEl.style.backgroundImage = imgSrc ? `url('${imgSrc}')` : '';
    imgEl.classList.toggle('map-poi-panel__img--empty', !imgSrc);

    // Título
    poiPanel.querySelector('.map-poi-panel__name').textContent = poi.name;

    // Meta: localidad · Zona region
    const meta = poiPanel.querySelector('.map-poi-panel__meta');
    const parts = [];
    if (poi.localidad) parts.push(poi.localidad);
    if (poi.region)    parts.push('Zona ' + poi.region);
    meta.textContent = parts.join(' · ');
    meta.style.display = parts.length ? '' : 'none';

    // Info: acceso + prestaciones
    const infoEl = poiPanel.querySelector('.map-poi-panel__info');
    let infoHtml = '';
    if (poi.acceso) {
      infoHtml += `<div class="mpp-row"><span class="mpp-label">Acceso</span><span class="mpp-val">${poi.acceso}</span></div>`;
    }
    if (poi.prestaciones?.length) {
      const items = poi.prestaciones.map(p => `<li>${p}</li>`).join('');
      infoHtml += `<div class="mpp-row mpp-row--prest"><span class="mpp-label">Prestaciones</span><ul class="mpp-prest">${items}</ul></div>`;
    }
    infoEl.innerHTML = infoHtml;
    infoEl.style.display = infoHtml ? '' : 'none';

    // Links: whatsapp, web, dirección
    const linksEl = poiPanel.querySelector('.map-poi-panel__links');
    let linksHtml = '';
    if (poi.whatsapp) {
      const num = poi.whatsapp.replace(/\D/g, '');
      linksHtml += `<a class="mpp-link mpp-link--wsp" href="https://wa.me/${num}" target="_blank" rel="noopener">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
        WhatsApp</a>`;
    }
    if (poi.sitio_web) {
      linksHtml += `<a class="mpp-link" href="${poi.sitio_web}" target="_blank" rel="noopener">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20M2 12h20"/></svg>
        Sitio web</a>`;
    }
    if (poi.dir_url) {
      linksHtml += `<a class="mpp-link" href="${poi.dir_url}" target="_blank" rel="noopener">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
        ${poi.dir_label || 'Cómo llegar'}</a>`;
    }
    linksEl.innerHTML = linksHtml;
    linksEl.style.display = linksHtml ? '' : 'none';

    // CTA
    const cta = poiPanel.querySelector('.map-poi-panel__cta');
    cta.href = poi.url;
    cta.style.setProperty('--cta-color', poi.color || 'var(--c-primary)');

    // Botón fav — sincronizar post_id y estado liked
    const favBtn = poiPanel.querySelector('.map-poi-panel__fav');
    if (favBtn && poi.id) {
      favBtn.dataset.postId = poi.id;
      // Buscar estado en otros botones del mismo post ya cargados
      const existing = document.querySelector(`.js-turs-like[data-post-id="${poi.id}"]:not(.map-poi-panel__fav)`);
      const isLiked = existing ? existing.classList.contains('is-liked') : false;
      favBtn.dataset.liked = isLiked ? 'true' : 'false';
      favBtn.classList.toggle('is-liked', isLiked);
      favBtn.setAttribute('aria-label', isLiked ? 'Quitar de ruta de viaje' : 'Agregar a ruta de viaje');
    }

    // Abrir
    poiPanel.classList.add('is-open');
    poiPanel.setAttribute('aria-hidden', 'false');
    poiBackdrop?.classList.add('is-open');
  }

  function closePoiPanel() {
    if (!poiPanel) return;
    poiPanel.classList.remove('is-open');
    poiPanel.setAttribute('aria-hidden', 'true');
    poiBackdrop?.classList.remove('is-open');
  }

  poiPanelClose?.addEventListener('click', closePoiPanel);
  poiBackdrop?.addEventListener('click', closePoiPanel);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closePoiPanel(); });

  // Destino-cards abren el panel lateral
  document.addEventListener('click', function(e) {
    const card = e.target.closest('.destino-card[data-poi-id]');
    if (!card) return;
    // No interceptar el botón de fav ni el link directo si no hay panel disponible
    if (e.target.closest('.js-turs-like')) return;
    if (!poiPanel) return;
    e.preventDefault();
    const id = parseInt(card.dataset.poiId, 10);
    const poi = allPois.find(p => p.id === id);
    if (poi) {
      openPoiPanel(poi);
    } else {
      // POI no está en el mapa — construir datos mínimos desde el card
      const name  = card.querySelector('.destino-card__name')?.textContent || '';
      const img   = card.querySelector('.destino-card__img')?.src || '';
      const url   = card.tagName === 'A' ? card.href : card.querySelector('a')?.href || '';
      const color = card.style.background || '#1a5c4c';
      openPoiPanel({ id, name, image: img, image_md: img, url, color, lat: 0, lon: 0, has_map: false });
    }
  });

  // ─────────────────────────────────────────────
  // FAVORITES REAL-TIME UPDATE
  // ─────────────────────────────────────────────
  const DEL_SVG = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>`;

  document.addEventListener('turs:like-toggled', function(e) {
    const { postId, liked } = e.detail;
    const likesList  = document.getElementById('panel-likes-list');
    const emptyEl    = document.getElementById('panel-likes-empty');
    const clearBtn   = document.querySelector('.js-panel-clear-likes');
    if (!likesList) return;

    if (liked) {
      const poi = poiDataCache[postId];
      if (!poi) return;

      // Evitar duplicados
      if (likesList.querySelector(`[data-post-id="${postId}"]`)) return;

      const li = document.createElement('li');
      li.className = 'user-panel__list-item';
      li.dataset.postId = postId;
      const thumbHtml = poi.image
        ? `<img src="${poi.image}" alt="">`
        : '';
      li.innerHTML = `
        <a href="${poi.url}" class="user-panel__list-link">
          <span class="user-panel__list-thumb" style="background:${poi.color || '#1a5c4c'};">${thumbHtml}</span>
          <span class="user-panel__list-title">${poi.name}</span>
        </a>
        <button type="button" class="user-panel__list-del js-panel-unlike" data-post-id="${postId}" aria-label="Eliminar de favoritos">${DEL_SVG}</button>`;
      likesList.appendChild(li);

      likesList.style.display = '';
      if (emptyEl)  emptyEl.style.display  = 'none';
      if (clearBtn) clearBtn.style.display = '';

    } else {
      const item = likesList.querySelector(`li[data-post-id="${postId}"]`);
      if (item) item.remove();

      if (!likesList.querySelector('li')) {
        likesList.style.display  = 'none';
        if (emptyEl)  emptyEl.style.display  = '';
        if (clearBtn) clearBtn.style.display = 'none';
      }
    }
  });

  // ─────────────────────────────────────────────
  // LEAFLET MAP + POI MARKERS
  // ─────────────────────────────────────────────
  const mapEl = document.getElementById('misiones-leaflet-map');
  let leafletMarkers = [];
  let mapInstance = null;

  let allPois = [];   // todos los POIs (con y sin coords) para el conteo

  if (mapEl && typeof L !== 'undefined') {
    allPois = JSON.parse(mapEl.dataset.pois || '[]');

    mapInstance = L.map('misiones-leaflet-map', {
      center: [-26.75, -54.85],
      zoom: window.innerWidth >= 1024 ? 8 : 7.5,
      zoomSnap: 0.5,
      zoomDelta: 0.5,
      zoomControl: true,
      scrollWheelZoom: false,
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19,
    }).addTo(mapInstance);

    allPois.forEach(poi => {
      if (!poi.has_map) return;   // sin coords → no marker, sí se cuenta

      const icon = L.divIcon({
        className: '',
        html: `<div class="map-poi-marker" style="background:${poi.color};" data-profile="${poi.profile}"></div>`,
        iconSize: [14, 14],
        iconAnchor: [7, 7],
        tooltipAnchor: [7, -7],
      });

      const marker = L.marker([poi.lat, poi.lon], { icon }).addTo(mapInstance);
      marker.on('click', () => openPoiPanel(poi));
      marker._poi = poi;
      leafletMarkers.push(marker);
    });

    document.querySelectorAll('.map-filter__submit-label').forEach(el => {
      el.textContent = `Mostrar resultados (${allPois.length})`;
    });
  }

  // ─────────────────────────────────────────────
  // MAP ACTIVE FILTERS — chips encima del título
  // ─────────────────────────────────────────────
  function renderActiveFilters() {
    const container = document.getElementById('map-active-filters');
    if (!container) return;

    const chips = [];

    // Perfiles activos
    document.querySelectorAll('.map-profile-btn.is-active').forEach(btn => {
      chips.push({
        type: 'profile',
        label: btn.querySelector('.map-profile-btn__label')?.textContent || '',
        color: btn.dataset.color || 'var(--c-primary)',
        ref: btn.dataset.profile,
      });
    });

    // Regiones activas
    document.querySelectorAll('.map-region-btn.is-active').forEach(btn => {
      chips.push({
        type: 'region',
        label: btn.textContent.trim(),
        color: '#64748b',
        ref: btn.dataset.region,
      });
    });

    // Localidades activas
    document.querySelectorAll('#map-tags .map-tag').forEach(tag => {
      chips.push({
        type: 'locality',
        label: tag.dataset.loc,
        color: '#0369a1',
        ref: tag.dataset.loc,
      });
    });

    // Categoría inicial (filtro server-side desde single-registro-unico)
    const initCat = document.getElementById('explorar')?.dataset.initCategoria || '';
    if (initCat) {
      initCat.split(',').map(s => s.trim()).filter(Boolean).forEach(slug => {
        const btn = document.querySelector(`.map-profile-btn[data-categoria="${slug}"]`);
        // Si ya hay un chip de perfil activo para este slug, no duplicar
        const alreadyCovered = btn?.classList.contains('is-active');
        if (!alreadyCovered) {
          const label = btn?.querySelector('.map-profile-btn__label')?.textContent || slug;
          const color = btn?.dataset.color || '#1a5c4c';
          chips.push({ type: 'init-categoria', label, color, ref: slug });
        }
      });
    }

    if (!chips.length) {
      container.innerHTML = '';
      return;
    }

    container.innerHTML = chips.map(c => {
      const removable = c.type !== 'init-categoria';
      return `<button class="maf-chip${removable ? '' : ' maf-chip--fixed'}" data-type="${c.type}" data-ref="${c.ref}" aria-label="${removable ? 'Quitar filtro ' : 'Filtro aplicado: '}${c.label}"${removable ? '' : ' disabled'}>
        <span class="maf-chip__label">${c.label}</span>
        ${removable ? '<span class="maf-chip__remove" aria-hidden="true">×</span>' : ''}
      </button>`;
    }).join('');

    container.querySelectorAll('.maf-chip:not(.maf-chip--fixed)').forEach(chip => {
      chip.addEventListener('click', () => {
        const { type, ref } = chip.dataset;
        if (type === 'profile') {
          document.querySelector(`.map-profile-btn[data-profile="${ref}"]`)?.click();
        } else if (type === 'region') {
          document.querySelector(`.map-region-btn[data-region="${ref}"]`)?.click();
        } else if (type === 'locality') {
          document.querySelector(`.map-tag__remove[data-remove="${ref}"]`)?.click();
        }
        renderActiveFilters();
      });
    });
  }

  // ─────────────────────────────────────────────
  // MAP FILTER — lógica unificada
  // ─────────────────────────────────────────────
  function applyMapFilters() {
    if (!mapInstance) return;

    const searchText = (document.getElementById('map-search')?.value || '').toLowerCase().trim();

    const activeCategorias = [...document.querySelectorAll('.map-profile-btn.is-active')]
      .map(b => b.dataset.categoria).filter(Boolean);

    const activeRegions = [...document.querySelectorAll('.map-region-btn.is-active')]
      .map(b => b.dataset.region).filter(Boolean);

    const selectedLocalities = [...document.querySelectorAll('#map-tags .map-tag')]
      .map(t => t.dataset.loc).filter(Boolean);

    leafletMarkers.forEach(marker => {
      const poi = marker._poi;
      let visible = true;

      // Búsqueda por título o resumen
      if (searchText) {
        const haystack = (poi.name + ' ' + (poi.resumen || '')).toLowerCase();
        if (!haystack.includes(searchText)) visible = false;
      }

      // Filtro por perfil → categoria-ru
      if (visible && activeCategorias.length) {
        const cats = poi.categorias || [];
        if (!activeCategorias.some(c => cats.includes(c))) visible = false;
      }

      // Filtro por región
      if (visible && activeRegions.length) {
        if (!activeRegions.includes(poi.region)) visible = false;
      }

      // Filtro por localidad
      if (visible && selectedLocalities.length) {
        const locs = poi.localidades || [];
        if (!selectedLocalities.some(l => locs.includes(l))) visible = false;
      }

      if (visible) {
        if (!mapInstance.hasLayer(marker)) marker.addTo(mapInstance);
        marker.getElement()?.querySelector('.map-poi-marker')?.classList.remove('is-dimmed');
      } else {
        if (mapInstance.hasLayer(marker)) marker.remove();
      }
    });

    // Contar todos los POIs que pasan los filtros (con y sin coordenadas)
    const totalCount = allPois.filter(poi => {
      if (searchText) {
        const h = (poi.name + ' ' + (poi.resumen || '')).toLowerCase();
        if (!h.includes(searchText)) return false;
      }
      if (activeCategorias.length) {
        if (!activeCategorias.some(c => (poi.categorias || []).includes(c))) return false;
      }
      if (activeRegions.length) {
        if (!activeRegions.includes(poi.region)) return false;
      }
      if (selectedLocalities.length) {
        if (!selectedLocalities.some(l => (poi.localidades || []).includes(l))) return false;
      }
      return true;
    }).length;

    document.querySelectorAll('.map-filter__submit-label').forEach(el => {
      el.textContent = `Mostrar resultados (${totalCount})`;
    });

    renderActiveFilters();
  }

  // Botones de perfil
  document.querySelectorAll('.map-profile-btn').forEach(btn => {
    btn.style.setProperty('--btn-color', btn.dataset.color || 'var(--c-primary)');
    btn.addEventListener('click', () => {
      const active = btn.classList.toggle('is-active');
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
      applyMapFilters();
    });
  });

  // Botones de región
  document.querySelectorAll('.map-region-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const active = btn.classList.toggle('is-active');
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
      applyMapFilters();
    });
  });

  // Buscador — debounce 300ms
  const mapSearchInput = document.getElementById('map-search');
  if (mapSearchInput) {
    let _searchTimer;
    mapSearchInput.addEventListener('input', () => {
      clearTimeout(_searchTimer);
      _searchTimer = setTimeout(applyMapFilters, 300);
    });
  }

  // Toggle búsqueda avanzada (mobile)
  document.querySelector('.map-filter__advanced-toggle')?.addEventListener('click', function () {
    const expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', !expanded);
    document.querySelectorAll('.map-filter__field--advanced').forEach(f => f.classList.toggle('is-visible', !expanded));
  });

  // Botones filtrar (desktop + mobile)
  document.querySelectorAll('.map-filter__submit').forEach(btn => {
    btn.addEventListener('click', () => {
      applyMapFilters();
      showExplorararResults();
    });
  });

  // ── Mostrar y paginar explorar-results ──
  const PER_PAGE = 8;
  let explorPage = 0;
  let explorCards = [];
  let explorInited = false;

  function showExplorararResults() {
    const section = document.querySelector('.explorar-results');
    if (!section) return;

    section.classList.add('is-visible');

    if (!explorInited) {
      explorInited = true;
      explorCards = [...section.querySelectorAll('.explorar-results__grid .destino-card')];
      if (explorCards.length <= PER_PAGE) return; // sin paginación si caben todos

      // Insertar controles de paginación
      const nav = document.createElement('div');
      nav.className = 'explorar-pagination';
      nav.innerHTML = `
        <button class="explorar-pagination__btn" id="explor-prev" aria-label="Página anterior">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
          Anterior
        </button>
        <span class="explorar-pagination__info" id="explor-info"></span>
        <button class="explorar-pagination__btn" id="explor-next" aria-label="Página siguiente">
          Siguiente
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
        </button>
      `;
      section.appendChild(nav);

      document.getElementById('explor-prev').addEventListener('click', () => { explorPage--; renderExplorPage(); });
      document.getElementById('explor-next').addEventListener('click', () => { explorPage++; renderExplorPage(); });
    }

    explorPage = 0;
    renderExplorPage();
    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function renderExplorPage() {
    const total = explorCards.length;
    const totalPages = Math.ceil(total / PER_PAGE);
    const start = explorPage * PER_PAGE;
    const end   = start + PER_PAGE;

    explorCards.forEach((card, i) => {
      card.style.display = (i >= start && i < end) ? '' : 'none';
    });

    const info = document.getElementById('explor-info');
    const prev = document.getElementById('explor-prev');
    const next = document.getElementById('explor-next');
    if (info) info.textContent = `${explorPage + 1} / ${totalPages}`;
    if (prev) prev.disabled = explorPage === 0;
    if (next) next.disabled = explorPage >= totalPages - 1;
  }

  // Aplicar filtros iniciales desde data-* del section
  const explorarSection = document.getElementById('explorar');
  if (explorarSection) {
    const initBuscar = explorarSection.dataset.initBuscar || '';
    if (initBuscar) {
      const si = document.getElementById('map-search');
      if (si) { si.value = initBuscar; applyMapFilters(); }
    }

    const initCategoria = explorarSection.dataset.initCategoria || '';
    if (initCategoria) {
      initCategoria.split(',').map(s => s.trim()).filter(Boolean).forEach(slug => {
        const btn = document.querySelector(`.map-profile-btn[data-categoria="${slug}"]`);
        if (btn && !btn.classList.contains('is-active')) btn.click();
      });
    }

    renderActiveFilters();
  }

  // ─────────────────────────────────────────────
  // EVENTS CALENDAR
  // ─────────────────────────────────────────────
  const calEl = document.getElementById('events-calendar');
  if (calEl) {
    const eventsData = JSON.parse(calEl.dataset.events || '[]');

    const MONTHS = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const DAYS   = ['Lu','Ma','Mi','Ju','Vi','Sá','Do'];

    // Base: mes actual, máximo 2 meses adelante
    const _now       = new Date();
    const BASE_YEAR  = _now.getFullYear();
    const BASE_MONTH = _now.getMonth();
    const MAX_OFFSET = 2;

    let offset = 0;

    function getMonthYear(off) {
      const d = new Date(BASE_YEAR, BASE_MONTH + off, 1);
      return { month: d.getMonth(), year: d.getFullYear() };
    }

    function getEvent(year, month, day) {
      const d = `${year}-${String(month + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
      return eventsData.find(e => e.date === d) || null;
    }

    function renderCal() {
      const { month, year } = getMonthYear(offset);
      const first = new Date(year, month, 1).getDay();
      // Monday-start: 0=Mon … 6=Sun
      const startOffset = (first + 6) % 7;
      const days = new Date(year, month + 1, 0).getDate();
      const today = new Date();

      let h = `<div class="cal-header">
        <button class="cal-nav cal-prev"${offset === 0 ? ' disabled' : ''} aria-label="Mes anterior">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <span class="cal-title">${MONTHS[month]} ${year}</span>
        <button class="cal-nav cal-next"${offset === MAX_OFFSET ? ' disabled' : ''} aria-label="Mes siguiente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </button>
      </div>
      <div class="cal-grid">`;

      DAYS.forEach(d => { h += `<div class="cal-day-name">${d}</div>`; });

      for (let i = 0; i < startOffset; i++) h += `<div class="cal-day cal-day--empty"></div>`;

      for (let d = 1; d <= days; d++) {
        const ev    = getEvent(year, month, d);
        const isToday = today.getFullYear() === year && today.getMonth() === month && today.getDate() === d;
        const dateStr = `${year}-${String(month + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const classes = ['cal-day', isToday ? 'cal-day--today' : '', ev ? 'cal-day--event' : ''].filter(Boolean).join(' ');

        h += `<button class="${classes}"
          ${ev ? `data-date="${dateStr}" title="${ev.title}"` : ''}
          aria-label="${d} de ${MONTHS[month]}${ev ? ': ' + ev.title : ''}">
          <span class="cal-day__num">${d}</span>
          ${ev ? `<span class="cal-dot" style="background:${ev.color};"></span>` : ''}
        </button>`;
      }

      h += `</div>`;
      calEl.innerHTML = h;

      calEl.querySelector('.cal-prev')?.addEventListener('click', () => {
        if (offset > 0) { offset--; renderCal(); }
      });
      calEl.querySelector('.cal-next')?.addEventListener('click', () => {
        if (offset < MAX_OFFSET) { offset++; renderCal(); }
      });

      calEl.querySelectorAll('.cal-day--event').forEach(btn => {
        btn.addEventListener('click', () => {
          calEl.querySelectorAll('.cal-day').forEach(b => b.classList.remove('is-active'));
          btn.classList.add('is-active');
        });
      });
    }

    renderCal();
  }

  // ── RU Reviews Drawer ──────────────────────────────────
  const ruDrawer = document.getElementById('js-reviews-drawer');

  if (ruDrawer) {
    const drawerReviews = document.getElementById('js-drawer-reviews');
    const REVIEWS_PER_PAGE = 6;
    let drawerPage = 1;

    function openDrawer() {
      ruDrawer.classList.add('is-open');
      ruDrawer.setAttribute('aria-hidden', 'false');
      document.body.classList.add('ru-drawer-open');
      ruDrawer.querySelector('.ru-drawer__panel')?.focus();
    }

    function closeDrawer() {
      ruDrawer.classList.remove('is-open');
      ruDrawer.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('ru-drawer-open');
    }

    // Open triggers
    document.querySelectorAll('.js-open-drawer').forEach(btn => {
      btn.addEventListener('click', openDrawer);
    });

    // Close triggers: backdrop + X button
    ruDrawer.querySelectorAll('.js-close-drawer').forEach(el => {
      el.addEventListener('click', closeDrawer);
    });

    // ESC key
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && ruDrawer.classList.contains('is-open')) closeDrawer();
    });

    // Client-side pagination inside drawer
    if (drawerReviews) {
      const allCards = Array.from(drawerReviews.querySelectorAll('.ru-drawer__review'));
      const totalPages = Math.max(1, Math.ceil(allCards.length / REVIEWS_PER_PAGE));

      function renderDrawerPage(page) {
        drawerPage = page;
        allCards.forEach((card, i) => {
          card.hidden = (i < (page - 1) * REVIEWS_PER_PAGE || i >= page * REVIEWS_PER_PAGE);
        });
        const pag = ruDrawer.querySelector('.ru-drawer__pagination');
        if (!pag) return;
        pag.querySelector('.ru-drawer__pag-prev').disabled = page <= 1;
        pag.querySelector('.ru-drawer__pag-next').disabled = page >= totalPages;
        pag.querySelector('.ru-drawer__pag-info').textContent = `${page} / ${totalPages}`;
      }

      if (allCards.length > REVIEWS_PER_PAGE) {
        // Inject pagination controls
        const pag = document.createElement('div');
        pag.className = 'ru-drawer__pagination';
        pag.innerHTML = `
          <button type="button" class="ru-drawer__pag-btn ru-drawer__pag-prev" aria-label="Página anterior">&#8592;</button>
          <span class="ru-drawer__pag-info"></span>
          <button type="button" class="ru-drawer__pag-btn ru-drawer__pag-next" aria-label="Página siguiente">&#8594;</button>`;
        drawerReviews.after(pag);
        pag.querySelector('.ru-drawer__pag-prev').addEventListener('click', () => renderDrawerPage(drawerPage - 1));
        pag.querySelector('.ru-drawer__pag-next').addEventListener('click', () => renderDrawerPage(drawerPage + 1));
      }

      renderDrawerPage(1);
    }
  }

  // Instagram → copiar URL al clipboard
  document.querySelector('.js-share-ig')?.addEventListener('click', function () {
    const url = this.dataset.url;
    const tip = this.querySelector('.ru-action-bar__ig-tip');
    navigator.clipboard?.writeText(url).then(() => {
      if (!tip) return;
      tip.textContent = '¡Enlace copiado!';
      tip.classList.add('is-visible');
      setTimeout(() => tip.classList.remove('is-visible'), 2000);
    });
  });

})();
