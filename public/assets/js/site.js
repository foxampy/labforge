/* ==========================================================================
   LEVADA SERVICE — клиентская интерактивность. Vanilla JS, без зависимостей,
   подключается через <script defer>. Каждый блок молча пропускается, если его
   хуков нет в разметке. Контракт классов — из public/assets/css/site.css.
   ========================================================================== */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  ready(function () {
    headerScroll();
    mobileNav();
    navMega();
    faqAccordion();
    revealOnScroll();
    leadForm();
    slider();
    prefillLead();
  });

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else {
      fn();
    }
  }

  /* --- 1. Шапка: .is-scrolled при прокрутке (rAF-троттлинг) --------------- */
  function headerScroll() {
    var header = document.querySelector('.site-header');
    if (!header) return;

    var ticking = false;
    function apply() {
      ticking = false;
      header.classList.toggle('is-scrolled', window.scrollY > 10);
    }
    apply();
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(apply); }
    }, { passive: true });
  }

  /* --- 2. Мобильное меню: бургер <-> body.nav-open ------------------------ */
  function mobileNav() {
    var btn = document.querySelector('.nav-toggle');
    var nav = document.querySelector('.main-nav');
    if (!btn || !nav) return;

    function close() {
      document.body.classList.remove('nav-open');
      btn.setAttribute('aria-expanded', 'false');
    }
    function toggle() {
      var open = document.body.classList.toggle('nav-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    btn.addEventListener('click', toggle);
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) close();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && document.body.classList.contains('nav-open')) {
        close(); btn.focus();
      }
    });
  }

  /* --- 2b. Мега-меню «Услуги»/«Марки»: hover на десктопе (CSS), tap на
            мобильном + закрытие по Escape/клику вне ------------------------ */
  function navMega() {
    var items = document.querySelectorAll('.nav-item.has-mega');
    if (!items.length) return;

    var mq = window.matchMedia('(max-width: 880px)');

    function closeAll(except) {
      items.forEach(function (it) {
        if (it === except) return;
        it.classList.remove('is-open');
        var l = it.querySelector('.nav-link');
        if (l) l.setAttribute('aria-expanded', 'false');
      });
    }

    items.forEach(function (item) {
      var link = item.querySelector('.nav-link');
      if (!link) return;

      // На мобильном (выпадающее меню-«шторка») тап по пункту раскрывает
      // каталог, а не уводит на страницу-индекс. На десктопе работает hover.
      link.addEventListener('click', function (e) {
        if (!mq.matches) return;
        e.preventDefault();
        var open = item.classList.toggle('is-open');
        link.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) closeAll(item);
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAll(null);
    });
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.nav-item.has-mega')) closeAll(null);
    });
  }

  /* --- 3. FAQ-аккордеон: .faq__q -> .faq__item.is-open + max-height ------- */
  function faqAccordion() {
    var items = document.querySelectorAll('.faq__item');
    if (!items.length) return;

    items.forEach(function (item) {
      var q = item.querySelector('.faq__q');
      var a = item.querySelector('.faq__a');
      if (!q || !a) return;

      q.setAttribute('aria-expanded', 'false');
      q.addEventListener('click', function () {
        var open = item.classList.toggle('is-open');
        q.setAttribute('aria-expanded', open ? 'true' : 'false');
        a.style.maxHeight = open ? a.scrollHeight + 'px' : '';
      });
    });

    // Пересчёт высоты открытого пункта при ресайзе.
    window.addEventListener('resize', function () {
      document.querySelectorAll('.faq__item.is-open .faq__a').forEach(function (a) {
        a.style.maxHeight = a.scrollHeight + 'px';
      });
    }, { passive: true });
  }

  /* --- 4. Reveal-on-scroll: .reveal -> .is-in ---------------------------- */
  function revealOnScroll() {
    var els = document.querySelectorAll('.reveal');
    if (!els.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      els.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.08 });

    els.forEach(function (el) { io.observe(el); });
  }

  /* --- 5. Форма заявки -> Telegram (через POST /lead) --------------------- */
  function leadForm() {
    var form = document.getElementById('lead-form');
    if (!form) return;

    var status = document.getElementById('lead-status');
    var btn = form.querySelector('[type="submit"]');
    var btnText = btn ? btn.textContent : '';

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (btn) { btn.disabled = true; btn.textContent = 'Отправляем…'; }
      showStatus('', '');

      fetch(form.action, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(form)
      })
        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
        .then(function (res) {
          if (res.ok && res.data && res.data.ok) {
            form.reset();
            showStatus('Спасибо! Заявка принята — перезвоним в ближайшее время.', 'ok');
          } else {
            var msg = (res.data && res.data.message) || 'Не удалось отправить. Позвоните нам.';
            showStatus(msg, 'err');
          }
        })
        .catch(function () {
          showStatus('Ошибка сети. Проверьте соединение или позвоните нам.', 'err');
        })
        .finally(function () {
          if (btn) { btn.disabled = false; btn.textContent = btnText; }
        });
    });

    function showStatus(text, kind) {
      if (!status) return;
      status.textContent = text;
      status.className = 'form-msg' + (kind ? ' is-' + kind : '');
    }
  }

  /* --- 6. Слайдер фото на странице услуги: авто-карусель + стрелки + свайп --- */
  function slider() {
    var roots = document.querySelectorAll('[data-slider]');
    if (!roots.length) return;

    roots.forEach(function (root) {
      var track = root.querySelector('[data-slider-track]');
      var prev = root.querySelector('[data-slider-prev]');
      var next = root.querySelector('[data-slider-next]');
      if (!track) return;

      function stepSize() {
        var slide = track.querySelector('.svc-slide');
        if (!slide) return track.clientWidth;
        var w = slide.getBoundingClientRect().width || slide.offsetWidth;
        var gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap);
        if (isNaN(gap)) gap = 16;
        return w + gap;
      }

      function scrollByStep(dir) {
        track.scrollBy({
          left: dir * stepSize(),
          behavior: reduceMotion ? 'auto' : 'smooth'
        });
      }

      var originals = Array.prototype.slice.call(track.querySelectorAll('.svc-slide'));
      var canAuto = !reduceMotion && originals.length > 1;

      /* --- авто-режим: бесконечная медленная «карусель» --- */
      if (canAuto) {
        // дублируем плитки один раз — для бесшовной петли
        originals.forEach(function (node) {
          var clone = node.cloneNode(true);
          clone.classList.add('is-clone');
          clone.setAttribute('aria-hidden', 'true');
          clone.setAttribute('tabindex', '-1');
          track.appendChild(clone);
        });
        // снап мешает попиксельной анимации — выключаем на время автопрокрутки
        track.style.scrollSnapType = 'none';
        if (prev) prev.disabled = false;
        if (next) next.disabled = false;

        var period = 0;
        function measure() {
          var firstClone = track.children[originals.length];
          period = firstClone
            ? (firstClone.offsetLeft - track.children[0].offsetLeft)
            : (track.scrollWidth / 2);
        }
        measure();
        window.addEventListener('resize', measure, { passive: true });

        var SPEED = 0.6;     // px/кадр — медленный, спокойный дрейф
        var carry = 0;       // дробный остаток (работает и при целочисленном scrollLeft)
        var paused = false;
        var resumeTimer = null;

        function pause() { paused = true; }
        function resume() { paused = false; }
        function nudgePause() {
          paused = true;
          clearTimeout(resumeTimer);
          resumeTimer = setTimeout(function () { paused = false; }, 1800);
        }

        function tick() {
          if (!paused && !document.hidden && period > 0) {
            carry += SPEED;
            var step = Math.floor(carry);
            if (step >= 1) {
              carry -= step;
              var pos = track.scrollLeft + step;
              if (pos >= period) pos -= period;
              track.scrollLeft = pos;
            }
          }
          requestAnimationFrame(tick);
        }

        root.addEventListener('mouseenter', pause);
        root.addEventListener('mouseleave', resume);
        root.addEventListener('focusin', pause);
        root.addEventListener('focusout', resume);
        root.addEventListener('touchstart', pause, { passive: true });
        root.addEventListener('touchend', resume, { passive: true });

        if (prev) prev.addEventListener('click', function () { scrollByStep(-1); nudgePause(); });
        if (next) next.addEventListener('click', function () { scrollByStep(1); nudgePause(); });

        requestAnimationFrame(tick);
        return;
      }

      /* --- статичный режим (reduce-motion / одна плитка): стрелки + disabled --- */
      function update() {
        if (prev) prev.disabled = track.scrollLeft <= 2;
        if (next) next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
      }

      if (prev) prev.addEventListener('click', function () { scrollByStep(-1); });
      if (next) next.addEventListener('click', function () { scrollByStep(1); });

      var ticking = false;
      track.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; requestAnimationFrame(function () { ticking = false; update(); }); }
      }, { passive: true });
      window.addEventListener('resize', update, { passive: true });

      update();
    });
  }

  /* --- 7. Префилл формы из ?service=&car= (переход с плитки слайдера) ------ */
  function prefillLead() {
    var form = document.getElementById('lead-form');
    if (!form) return;

    var params = new URLSearchParams(window.location.search);
    var car = params.get('car');
    var service = params.get('service');
    var applied = false;

    if (car) {
      var carInput = document.getElementById('lf-car');
      if (carInput) { carInput.value = car; applied = true; }
    }

    if (service) {
      var select = document.getElementById('lf-service');
      if (select) {
        for (var i = 0; i < select.options.length; i++) {
          if (select.options[i].value === service) {
            select.value = service;
            applied = true;
            break;
          }
        }
      }
    }

    if (!applied) return;

    form.classList.add('is-prefilled');
    setTimeout(function () {
      var target = document.getElementById('zayavka');
      if (target) {
        target.scrollIntoView({
          behavior: reduceMotion ? 'auto' : 'smooth',
          block: 'start'
        });
      }
    }, 60);
    setTimeout(function () { form.classList.remove('is-prefilled'); }, 2400);
  }
})();
