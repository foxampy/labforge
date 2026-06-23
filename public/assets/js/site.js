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
})();
