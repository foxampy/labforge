/* ==========================================================================
 *  LEVADA SERVICE — клиентская интерактивность (vanilla JS, без зависимостей).
 *  Грузится через <script defer>. Каждый блок «прощупывает» свои хуки и молча
 *  пропускается, если их нет в разметке. ES2019, без import/модулей.
 *  Порт поведения из React-компонентов: Header / SiteSearch / Hero /
 *  InteractiveGarage / LeadForm + reveal/count-up/smooth-scroll.
 * ========================================================================== */
(function () {
  'use strict';

  // Запускаемся после построения DOM (defer уже это гарантирует, но на всякий).
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }

  /** Уважает ли пользователь «уменьшить движение». */
  function prefersReducedMotion() {
    return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  function init() {
    initHeaderScroll();
    initMobileMenu();
    initSearch();
    initReveal();
    initCountUp();
    initHero();
    initGarage();
    initFaq();
    initLeadForm();
    initSmoothScroll();
  }

  /* ----------------------------------------------------------------------- *
   * 1. Шапка: класс is-scrolled при скролле > 10px (троттлинг через rAF).    *
   * ----------------------------------------------------------------------- */
  function initHeaderScroll() {
    var header = document.getElementById('site-header');
    if (!header) return;

    var ticking = false;
    function apply() {
      ticking = false;
      if (window.scrollY > 10) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
    }
    function onScroll() {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(apply);
      }
    }
    apply(); // начальное состояние (например, при перезагрузке на середине страницы)
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ----------------------------------------------------------------------- *
   * 2. Мобильное меню: бургер переключает #mobile-nav (класс hidden) и       *
   *    aria-expanded; закрытие по клику на ссылку и по Escape.               *
   * ----------------------------------------------------------------------- */
  function initMobileMenu() {
    var btn = document.getElementById('menu-btn');
    var nav = document.getElementById('mobile-nav');
    if (!btn || !nav) return;

    function open() {
      nav.classList.remove('hidden');
      btn.setAttribute('aria-expanded', 'true');
      btn.setAttribute('aria-label', 'Закрыть меню');
    }
    function close() {
      nav.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
      btn.setAttribute('aria-label', 'Открыть меню');
    }
    function toggle() {
      if (nav.classList.contains('hidden')) {
        open();
      } else {
        close();
      }
    }

    btn.addEventListener('click', toggle);

    // Клик по любой ссылке внутри меню — закрываем.
    nav.addEventListener('click', function (ev) {
      var link = ev.target && ev.target.closest ? ev.target.closest('a') : null;
      if (link) close();
    });

    // Escape закрывает меню.
    document.addEventListener('keydown', function (ev) {
      if ((ev.key === 'Escape' || ev.key === 'Esc') && !nav.classList.contains('hidden')) {
        close();
        btn.focus();
      }
    });
  }

  /* ----------------------------------------------------------------------- *
   * 3. Поиск по сайту: десктоп (#site-search/#search-results) и мобайл       *
   *    (#site-search-m/#search-results-m). Фильтрует window.__SEARCH_INDEX__ *
   *    по title/keywords (без регистра), рендерит ≤8 результатов.            *
   *    Клавиши Up/Down/Enter/Escape, закрытие по клику вне.                  *
   * ----------------------------------------------------------------------- */
  function initSearch() {
    var index = Array.isArray(window.__SEARCH_INDEX__) ? window.__SEARCH_INDEX__ : [];
    bindSearch(document.getElementById('site-search'), document.getElementById('search-results'), index);
    bindSearch(document.getElementById('site-search-m'), document.getElementById('search-results-m'), index);
  }

  function bindSearch(input, list, index) {
    if (!input || !list) return;

    var activeIndex = -1; // индекс подсвеченного пункта (-1 = нет)

    function hide() {
      list.classList.add('hidden');
      list.innerHTML = '';
      activeIndex = -1;
      input.setAttribute('aria-expanded', 'false');
    }

    function filter(term) {
      var q = term.trim().toLowerCase();
      if (!q) return [];
      var out = [];
      for (var i = 0; i < index.length && out.length < 8; i++) {
        var it = index[i];
        var title = (it.title || '').toLowerCase();
        var kw = (it.keywords || '').toLowerCase();
        if (title.indexOf(q) !== -1 || kw.indexOf(q) !== -1) {
          out.push(it);
        }
      }
      return out;
    }

    function render(results) {
      list.innerHTML = '';
      activeIndex = -1;
      if (!results.length) {
        // Пустой результат при непустом запросе — короткая подсказка.
        var liEmpty = document.createElement('li');
        liEmpty.className = 'px-3 py-2.5 text-sm text-steel-400';
        liEmpty.textContent = 'Ничего не найдено. Позвоните нам — поможем!';
        list.appendChild(liEmpty);
        list.classList.remove('hidden');
        input.setAttribute('aria-expanded', 'true');
        return;
      }
      for (var i = 0; i < results.length; i++) {
        var r = results[i];
        var li = document.createElement('li');
        li.setAttribute('role', 'option');

        var a = document.createElement('a');
        a.href = r.href || '#';
        a.className = 'flex items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-sm text-steel-100 transition-colors hover:bg-white/5 hover:text-white';

        var titleSpan = document.createElement('span');
        titleSpan.className = 'min-w-0 truncate';
        titleSpan.textContent = r.title || ''; // пользовательский текст — только textContent

        var typeSpan = document.createElement('span');
        typeSpan.className = 'shrink-0 rounded-md border border-white/12 bg-white/5 px-2 py-0.5 text-[11px] text-steel-400';
        typeSpan.textContent = r.type || '';

        a.appendChild(titleSpan);
        a.appendChild(typeSpan);
        li.appendChild(a);
        list.appendChild(li);
      }
      list.classList.remove('hidden');
      input.setAttribute('aria-expanded', 'true');
    }

    function items() {
      return list.querySelectorAll('a');
    }

    function highlight(next) {
      var links = items();
      if (!links.length) return;
      if (next < 0) next = links.length - 1;
      if (next >= links.length) next = 0;
      for (var i = 0; i < links.length; i++) {
        links[i].classList.toggle('bg-white/5', i === next);
        links[i].classList.toggle('text-white', i === next);
      }
      activeIndex = next;
      links[next].scrollIntoView({ block: 'nearest' });
    }

    input.addEventListener('input', function () {
      render(filter(input.value));
    });

    input.addEventListener('focus', function () {
      if (input.value.trim()) render(filter(input.value));
    });

    input.addEventListener('keydown', function (ev) {
      var visible = !list.classList.contains('hidden');
      if (ev.key === 'ArrowDown') {
        if (!visible) { render(filter(input.value)); }
        ev.preventDefault();
        highlight(activeIndex + 1);
      } else if (ev.key === 'ArrowUp') {
        if (!visible) return;
        ev.preventDefault();
        highlight(activeIndex - 1);
      } else if (ev.key === 'Enter') {
        var links = items();
        if (visible && activeIndex >= 0 && links[activeIndex]) {
          ev.preventDefault();
          window.location.href = links[activeIndex].getAttribute('href');
        }
      } else if (ev.key === 'Escape' || ev.key === 'Esc') {
        hide();
      }
    });

    // Клик вне поля/списка — закрываем.
    document.addEventListener('mousedown', function (ev) {
      if (ev.target === input) return;
      if (list.contains(ev.target)) return;
      hide();
    });
  }

  /* ----------------------------------------------------------------------- *
   * 4. Reveal: .reveal -> .is-visible через IntersectionObserver.            *
   *    reduced-motion -> показываем всё сразу.                               *
   * ----------------------------------------------------------------------- */
  function initReveal() {
    var nodes = document.querySelectorAll('.reveal');
    if (!nodes.length) return;

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
      for (var i = 0; i < nodes.length; i++) nodes[i].classList.add('is-visible');
      return;
    }

    var io = new IntersectionObserver(function (entries, obs) {
      for (var j = 0; j < entries.length; j++) {
        if (entries[j].isIntersecting) {
          entries[j].target.classList.add('is-visible');
          obs.unobserve(entries[j].target); // одноразово
        }
      }
    }, { threshold: 0.12 });

    for (var k = 0; k < nodes.length; k++) io.observe(nodes[k]);
  }

  /* ----------------------------------------------------------------------- *
   * 5. CountUp: [data-count] анимируется 0 -> значение при появлении.        *
   *    data-suffix добавляется к числу, data-duration (мс, по умолч. 1400),  *
   *    easeOutCubic. reduced-motion -> итог сразу.                           *
   * ----------------------------------------------------------------------- */
  function initCountUp() {
    var nodes = document.querySelectorAll('[data-count]');
    if (!nodes.length) return;

    function finalText(el) {
      return formatCount(parseTarget(el), el) + (el.getAttribute('data-suffix') || '');
    }
    function parseTarget(el) {
      var v = parseFloat(el.getAttribute('data-count'));
      return isNaN(v) ? 0 : v;
    }
    // Сохраняем дробную часть исходного значения (например, 4.9).
    function formatCount(value, el) {
      var raw = el.getAttribute('data-count') || '';
      var decimals = raw.indexOf('.') !== -1 ? (raw.split('.')[1] || '').length : 0;
      return decimals > 0 ? value.toFixed(decimals) : String(Math.round(value));
    }

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
      for (var i = 0; i < nodes.length; i++) nodes[i].textContent = finalText(nodes[i]);
      return;
    }

    function animate(el) {
      var target = parseTarget(el);
      var suffix = el.getAttribute('data-suffix') || '';
      var dur = parseInt(el.getAttribute('data-duration'), 10);
      if (isNaN(dur) || dur <= 0) dur = 1400;
      var start = 0;
      var startTime = 0;

      function tick(now) {
        if (!startTime) startTime = now;
        var p = Math.min((now - startTime) / dur, 1);
        var eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
        var current = start + (target - start) * eased;
        el.textContent = formatCount(current, el) + suffix;
        if (p < 1) window.requestAnimationFrame(tick);
      }
      window.requestAnimationFrame(tick);
    }

    var io = new IntersectionObserver(function (entries, obs) {
      for (var j = 0; j < entries.length; j++) {
        if (entries[j].isIntersecting) {
          animate(entries[j].target);
          obs.unobserve(entries[j].target);
        }
      }
    }, { threshold: 0.4 });

    for (var k = 0; k < nodes.length; k++) io.observe(nodes[k]);
  }

  /* ----------------------------------------------------------------------- *
   * 6. Hero: курсор задаёт CSS-переменные --mx/--my (%) для .hero-spotlight; *
   *    дети [data-parallax] получают лёгкий translateY при скролле (rAF).    *
   *    Отключено при reduced-motion / тач-устройствах.                       *
   * ----------------------------------------------------------------------- */
  function initHero() {
    var hero = document.getElementById('hero');
    if (!hero) return;

    var fine = window.matchMedia && window.matchMedia('(pointer: fine)').matches;
    if (prefersReducedMotion() || !fine) return;

    var spotlight = hero.querySelector('.hero-spotlight');
    var rafMove = 0;
    var mx = 50, my = 40;

    function applyMove() {
      rafMove = 0;
      var t = spotlight || hero;
      t.style.setProperty('--mx', mx + '%');
      t.style.setProperty('--my', my + '%');
    }
    hero.addEventListener('mousemove', function (ev) {
      var rect = hero.getBoundingClientRect();
      if (!rect.width || !rect.height) return;
      mx = ((ev.clientX - rect.left) / rect.width) * 100;
      my = ((ev.clientY - rect.top) / rect.height) * 100;
      if (!rafMove) rafMove = window.requestAnimationFrame(applyMove);
    }, { passive: true });

    // Параллакс по скроллу для [data-parallax] внутри hero.
    var layers = hero.querySelectorAll('[data-parallax]');
    if (layers.length) {
      var ticking = false;
      function applyScroll() {
        ticking = false;
        var rect = hero.getBoundingClientRect();
        var offset = -rect.top; // сколько проскроллили внутри hero
        for (var i = 0; i < layers.length; i++) {
          var speed = parseFloat(layers[i].getAttribute('data-parallax'));
          if (isNaN(speed)) speed = 0.15;
          layers[i].style.transform = 'translate3d(0,' + (offset * speed).toFixed(2) + 'px,0)';
        }
      }
      function onScroll() {
        if (!ticking) {
          ticking = true;
          window.requestAnimationFrame(applyScroll);
        }
      }
      applyScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
      window.addEventListener('resize', onScroll, { passive: true });
    }
  }

  /* ----------------------------------------------------------------------- *
   * 7. Гараж: #garage с button.hotspot[data-slug][data-accent] и             *
   *    #garage-panel. Hover/focus/click -> активируем хотспот (is-selected), *
   *    заполняем панель title/ссылкой услуги (по data-slug -> запись в       *
   *    __SEARCH_INDEX__ с href === /uslugi/{slug}), подкрашиваем data-accent.*
   *    Мобайл: тап переключает (повторный тап снимает выбор).                *
   * ----------------------------------------------------------------------- */
  function initGarage() {
    var garage = document.getElementById('garage');
    if (!garage) return;

    var hotspots = garage.querySelectorAll('button.hotspot[data-slug]');
    if (!hotspots.length) return;

    var panel = document.getElementById('garage-panel');
    var index = Array.isArray(window.__SEARCH_INDEX__) ? window.__SEARCH_INDEX__ : [];

    // Карта slug -> запись услуги из поискового индекса (url === /uslugi/{slug}).
    function lookup(slug) {
      var url = '/uslugi/' + slug;
      for (var i = 0; i < index.length; i++) {
        if (index[i].href === url) return index[i];
      }
      return null;
    }

    var fine = window.matchMedia && window.matchMedia('(pointer: fine)').matches;
    var currentSlug = '';

    function clearActive() {
      for (var i = 0; i < hotspots.length; i++) {
        hotspots[i].classList.remove('is-selected');
        hotspots[i].setAttribute('aria-pressed', 'false');
      }
    }

    function select(btn) {
      var slug = btn.getAttribute('data-slug');
      var accent = btn.getAttribute('data-accent') || '';
      clearActive();
      btn.classList.add('is-selected');
      btn.setAttribute('aria-pressed', 'true');
      currentSlug = slug;
      fillPanel(slug, accent);
    }

    function fillPanel(slug, accent) {
      if (!panel) return;
      var entry = lookup(slug);
      var title = entry ? entry.title : (slug || '');
      var href = entry ? entry.href : ('/uslugi/' + slug);

      // Тонируем панель акцентом (через CSS-переменную + inline-стиль рамки).
      if (accent) {
        panel.style.setProperty('--accent', accent);
        panel.style.borderColor = accent;
      }

      // Заполняем известные слоты, иначе — собираем минимальную карточку.
      var titleEl = panel.querySelector('[data-garage-title]');
      var linkEl = panel.querySelector('[data-garage-link]');
      if (titleEl || linkEl) {
        if (titleEl) titleEl.textContent = title;
        if (linkEl) linkEl.setAttribute('href', href);
      } else {
        panel.innerHTML = '';
        var h = document.createElement('h3');
        h.className = 'font-display text-xl font-bold uppercase tracking-wide text-white';
        h.setAttribute('data-garage-title', '');
        h.textContent = title;
        var a = document.createElement('a');
        a.className = 'btn-primary mt-4 inline-flex text-xs';
        a.setAttribute('data-garage-link', '');
        a.href = href;
        a.textContent = 'Подробнее об услуге';
        panel.appendChild(h);
        panel.appendChild(a);
      }
    }

    for (var i = 0; i < hotspots.length; i++) {
      (function (btn) {
        btn.addEventListener('mouseenter', function () { if (fine) select(btn); });
        btn.addEventListener('focus', function () { select(btn); });
        btn.addEventListener('click', function () {
          // Мобайл: повторный тап по активному снимает выбор.
          if (!fine && btn.getAttribute('data-slug') === currentSlug && btn.classList.contains('is-selected')) {
            clearActive();
            currentSlug = '';
            return;
          }
          select(btn);
        });
      })(hotspots[i]);
    }

    // Стартовое состояние: первый хотспот активен (как в React VALID[0]).
    select(hotspots[0]);
  }

  /* ----------------------------------------------------------------------- *
   * 8. FAQ: нативные <details class="faq-item"> уже работают. Опционально    *
   *    закрываем соседние при открытии — но только если задан data-faq-      *
   *    accordion на контейнере. По умолчанию пункты независимы.              *
   * ----------------------------------------------------------------------- */
  function initFaq() {
    var groups = document.querySelectorAll('[data-faq-accordion]');
    if (!groups.length) return;
    for (var g = 0; g < groups.length; g++) {
      (function (group) {
        var items = group.querySelectorAll('details.faq-item');
        for (var i = 0; i < items.length; i++) {
          items[i].addEventListener('toggle', function () {
            if (!this.open) return;
            for (var j = 0; j < items.length; j++) {
              if (items[j] !== this) items[j].open = false;
            }
          });
        }
      })(groups[g]);
    }
  }

  /* ----------------------------------------------------------------------- *
   * 9. Форма заявки: #lead-form (POST -> /lead.php). Honeypot                *
   *    company_website -> тихо прерываем. Валидация name>=2 и phone>=6 цифр  *
   *    -> ошибка в #lead-status. Иначе fetch с X-Requested-With: fetch,      *
   *    разбор JSON {ok}. Без JS — обычный POST всё равно работает.           *
   * ----------------------------------------------------------------------- */
  function initLeadForm() {
    var forms = document.querySelectorAll('#lead-form');
    if (!forms.length) return;
    for (var i = 0; i < forms.length; i++) bindLeadForm(forms[i]);
  }

  function bindLeadForm(form) {
    var status = form.querySelector('#lead-status') || document.getElementById('lead-status');
    var submitBtn = form.querySelector('[type="submit"]');
    var defaultBtnText = submitBtn ? submitBtn.textContent : '';

    function setStatus(text, kind) {
      if (!status) return;
      status.textContent = text;
      status.classList.remove('hidden');
      // kind: 'error' | 'success' | '' — переключаем цветовые классы.
      status.classList.toggle('text-red-300', kind === 'error');
      status.classList.toggle('text-neon-300', kind === 'success');
    }

    form.addEventListener('submit', function (ev) {
      // Берём значения полей.
      var honeypot = form.elements['company_website'] ? String(form.elements['company_website'].value || '') : '';
      var name = form.elements['name'] ? String(form.elements['name'].value || '').trim() : '';
      var phone = form.elements['phone'] ? String(form.elements['phone'].value || '') : '';
      var phoneDigits = phone.replace(/\D/g, '');

      // Honeypot заполнен ботом — тихо прерываем отправку (и блокируем сабмит).
      if (honeypot !== '') {
        ev.preventDefault();
        return;
      }

      // Валидация на клиенте.
      if (name.length < 2 || phoneDigits.length < 6) {
        ev.preventDefault();
        setStatus('Укажите имя и телефон (минимум 6 цифр).', 'error');
        return;
      }

      // fetch недоступен — отдаём браузеру обычный POST (не мешаем).
      if (typeof window.fetch !== 'function' || typeof FormData !== 'function') {
        return;
      }

      // Перехватываем и шлём через fetch.
      ev.preventDefault();
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправляем…';
      }
      setStatus('Отправляем заявку…', '');

      var action = form.getAttribute('action') || '/lead.php';
      var fd = new FormData(form);
      // Источник — текущий путь (как в LeadForm.tsx).
      if (!fd.get('source')) fd.append('source', window.location.pathname);

      window.fetch(action, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'fetch' }
      }).then(function (res) {
        return res.json().then(function (json) {
          return { ok: res.ok, json: json };
        }).catch(function () {
          return { ok: res.ok, json: {} };
        });
      }).then(function (r) {
        if (r.ok && r.json && r.json.ok) {
          setStatus('Заявка отправлена! Перезвоним в ближайшее время.', 'success');
          form.reset();
        } else {
          var msg = (r.json && r.json.message) ? r.json.message : 'Не удалось отправить. Позвоните нам.';
          setStatus(msg, 'error');
        }
      }).catch(function () {
        setStatus('Ошибка сети. Попробуйте ещё раз или позвоните нам.', 'error');
      }).then(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = defaultBtnText;
        }
      });
    });
  }

  /* ----------------------------------------------------------------------- *
   * 10. Плавный скролл для якорей a[href^="#"] и a[href^="/#"] с учётом       *
   *     фиксированной шапки (~80px). На «/#…» с другой страницы не мешаем.    *
   * ----------------------------------------------------------------------- */
  function initSmoothScroll() {
    var HEADER_OFFSET = 80;
    var smooth = prefersReducedMotion() ? 'auto' : 'smooth';

    document.addEventListener('click', function (ev) {
      var link = ev.target && ev.target.closest ? ev.target.closest('a[href]') : null;
      if (!link) return;
      var href = link.getAttribute('href');
      if (!href) return;

      var hash = '';
      if (href.charAt(0) === '#') {
        hash = href;
      } else if (href.indexOf('/#') === 0) {
        // «/#zayavka»: скроллим только если уже на главной, иначе обычный переход.
        if (window.location.pathname !== '/') return;
        hash = href.slice(1);
      } else {
        return;
      }
      if (hash === '#' || hash.length < 2) return;

      var target = document.getElementById(hash.slice(1));
      if (!target) return; // нет цели на странице — не вмешиваемся

      ev.preventDefault();
      var top = target.getBoundingClientRect().top + window.pageYOffset - HEADER_OFFSET;
      window.scrollTo({ top: top < 0 ? 0 : top, behavior: smooth });

      // Обновляем адресную строку без прыжка.
      if (window.history && window.history.pushState) {
        window.history.pushState(null, '', hash);
      }
    });
  }
})();
