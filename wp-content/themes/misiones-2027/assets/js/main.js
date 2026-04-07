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

  btnIngresar?.addEventListener('click', (e) => { e.preventDefault(); openLogin(); });
  btnLoginClose?.addEventListener('click', closeLogin);

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
    closeChat();
    closeLogin();
    document.body.style.overflow = '';
  }

  // ─────────────────────────────────────────────
  // CHAT FAB + PANEL
  // ─────────────────────────────────────────────
  const chatFab        = document.querySelector('.chat-fab');
  const chatPanel      = document.querySelector('.chat-panel');
  const btnChatClose   = document.querySelector('.btn-chat-close');

  function openChat() {
    chatFab?.classList.add('is-hidden');
    chatPanel?.classList.remove('is-hidden');
    backdrop?.classList.remove('is-hidden');
    document.body.style.overflow = 'hidden';
  }
  function closeChat() {
    chatFab?.classList.remove('is-hidden');
    chatPanel?.classList.add('is-hidden');
    backdrop?.classList.add('is-hidden');
    document.body.style.overflow = '';
  }

  chatFab?.addEventListener('click', openChat);
  btnChatClose?.addEventListener('click', closeChat);

  // Chat send (placeholder)
  const chatSendBtn = document.querySelector('.chat-send-btn');
  const chatInput   = document.querySelector('.chat-input');
  chatSendBtn?.addEventListener('click', () => {
    if (chatInput && chatInput.value.trim()) {
      chatInput.value = '';
    }
  });
  chatInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && chatInput.value.trim()) {
      chatInput.value = '';
    }
  });

  // Chip clicks
  document.querySelectorAll('.chat-suggestion-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      if (chatInput) chatInput.value = chip.textContent;
      chatInput?.focus();
    });
  });

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
  // DESTINO CARD FAV TOGGLE
  // ─────────────────────────────────────────────
  document.querySelectorAll('.destino-card__fav').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      e.preventDefault();
      btn.classList.toggle('is-faved');
      const svg = btn.querySelector('svg');
      if (svg) {
        svg.style.fill = btn.classList.contains('is-faved') ? '#ff4444' : 'none';
        svg.style.stroke = btn.classList.contains('is-faved') ? '#ff4444' : 'currentColor';
      }
    });
  });

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
        });
      });
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
  // LEAFLET MAP + POI MARKERS
  // ─────────────────────────────────────────────
  const mapEl = document.getElementById('misiones-leaflet-map');
  let leafletMarkers = [];

  if (mapEl && typeof L !== 'undefined') {
    const pois = JSON.parse(mapEl.dataset.pois || '[]');

    const map = L.map('misiones-leaflet-map', {
      center: [-26.75, -54.85],
      zoom: 8,
      zoomControl: true,
      scrollWheelZoom: false,
    });

    L.tileLayer('https://tiles.stadiamaps.com/tiles/outdoors/{z}/{x}/{y}{r}.png', {
      attribution: '© <a href="https://stadiamaps.com/">Stadia Maps</a> © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      maxZoom: 20,
    }).addTo(map);

    pois.forEach(poi => {
      const icon = L.divIcon({
        className: '',
        html: `<div class="map-poi-marker" style="background:${poi.color};" data-profile="${poi.profile}"></div>`,
        iconSize: [14, 14],
        iconAnchor: [7, 7],
        tooltipAnchor: [7, -7],
      });

      const marker = L.marker([poi.lat, poi.lon], { icon })
        .addTo(map)
        .bindTooltip(poi.name, { permanent: false, direction: 'top', offset: [0, -4] });

      marker._poiProfile = poi.profile;
      leafletMarkers.push(marker);
    });
  }

  function updatePinStates() {
    const active = [...document.querySelectorAll('.map-profile-btn.is-active')].map(b => b.dataset.profile);
    leafletMarkers.forEach(marker => {
      const el = marker.getElement()?.querySelector('.map-poi-marker');
      if (!el) return;
      if (!active.length) {
        el.classList.remove('is-dimmed', 'is-highlighted');
      } else if (active.includes(marker._poiProfile)) {
        el.classList.remove('is-dimmed');
        el.classList.add('is-highlighted');
      } else {
        el.classList.add('is-dimmed');
        el.classList.remove('is-highlighted');
      }
    });
  }

  // ─────────────────────────────────────────────
  // MAP FILTER — profile & route toggles
  // ─────────────────────────────────────────────
  document.querySelectorAll('.map-profile-btn').forEach(btn => {
    const color = btn.dataset.color || 'var(--c-primary)';
    btn.style.setProperty('--btn-color', color);
    btn.addEventListener('click', () => {
      const active = btn.classList.toggle('is-active');
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
      updatePinStates();
    });
  });

  document.querySelectorAll('.map-route-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const active = btn.classList.toggle('is-active');
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
  });

  // ─────────────────────────────────────────────
  // EVENTS CALENDAR
  // ─────────────────────────────────────────────
  const calEl = document.getElementById('events-calendar');
  if (calEl) {
    const eventsData = JSON.parse(calEl.dataset.events || '[]');

    const MONTHS = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const DAYS   = ['Lu','Ma','Mi','Ju','Vi','Sá','Do'];

    // Base: April 2026 (month 0-indexed = 3), max 2 months ahead
    const BASE_YEAR  = 2026;
    const BASE_MONTH = 3; // April
    const MAX_OFFSET = 2;

    let offset = 0;

    function getEvent(year, month, day) {
      const d = `${year}-${String(month + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
      return eventsData.find(e => e.date === d) || null;
    }

    function renderCal() {
      const month = BASE_MONTH + offset;
      const year  = BASE_YEAR;
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

})();
