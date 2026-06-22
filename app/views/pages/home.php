<?php
/**
 * Страница: / — главная LEVADA SERVICE.
 * Доступно: $homeFaq (массив ['q'=>,'a'=>] из 4 элементов).
 * Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 *
 * DOM-контракт для assets/js/main.js:
 *  #hero (+ .hero-spotlight, data-parallax), #garage (.hotspot, #garage-panel),
 *  span[data-count], .reveal, details.faq-item, #zayavka form#lead-form (+#lead-status).
 */
$co       = company();
$city     = $co['address']['city'] ?? 'Минск';
$allSvc   = services();
$pillars  = pillar_services();
$map      = service_map();
$hotspots = $map['hotspots'] ?? [];
$accents  = $map['ACCENT_HEX'] ?? [];

// Услуга по slug — для статичной панели гаража и подписей хотспотов (no-JS дефолт).
$svcBySlug = [];
foreach ($allSvc as $s) { $svcBySlug[$s['slug']] = $s; }
$validHotspots = array_values(array_filter($hotspots, static fn ($h) => isset($svcBySlug[$h['slug']])));
$defaultHot = $validHotspots[0] ?? null;
$defaultSvc = $defaultHot ? $svcBySlug[$defaultHot['slug']] : null;

// Стат-полоса героя (числовая часть — для CountUp через data-count).
$heroStats = [
  ['count' => 2014, 'suffix' => '', 'value' => 'с 2014', 'label' => 'года на рынке'],
  ['count' => 3000, 'suffix' => '+', 'value' => '3000+', 'label' => 'довольных клиентов'],
  ['count' => 50,   'suffix' => '+', 'value' => '50+',   'label' => 'марок авто'],
  ['count' => 12,   'suffix' => ' мес', 'value' => '12 мес', 'label' => 'гарантия на работы'],
];

// Карта/локация — бесключевой OpenStreetMap-эмбед по координатам.
$lat = $co['geo']['lat'] ?? null;
$lng = $co['geo']['lng'] ?? null;
$hasGeo = is_numeric($lat) && is_numeric($lng);
$fullAddress = ($co['address']['street'] ?? '') . ', ' . $city;
$d = 0.012;
$osmEmbed = $hasGeo
  ? 'https://www.openstreetmap.org/export/embed.html?bbox=' . ($lng - $d) . '%2C' . ($lat - $d) . '%2C' . ($lng + $d) . '%2C' . ($lat + $d) . '&layer=mapnik&marker=' . $lat . '%2C' . $lng
  : null;
$mapsLink = $hasGeo
  ? 'https://yandex.ru/maps/?pt=' . $lng . ',' . $lat . '&z=17&l=map'
  : 'https://yandex.ru/maps/?text=' . rawurlencode($fullAddress);
?>
<?php /* ── HERO ──────────────────────────────────────────────────── */ ?>
<section id="hero" class="relative isolate min-h-[100svh] overflow-hidden">
  <div class="absolute inset-0 -z-20 will-change-transform" data-parallax="0.2">
    <img src="<?= e(asset('img/hero.png')) ?>" alt="Сервисный бокс LEVADA SERVICE: каркасные кроссоверы на подъёмниках в неоновом свете" class="h-full w-full object-cover object-center" fetchpriority="high" decoding="async">
  </div>

  <?php /* Скримы и техно-сетка под читаемость поверх busy-картинки. */ ?>
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0" style="background:linear-gradient(90deg,rgba(0,0,0,0.85),rgba(0,0,0,0.35),rgba(0,0,0,0.75))"></div>
    <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-ink-950 via-ink-950/70 to-transparent"></div>
    <div class="absolute inset-x-0 top-0 h-36 bg-gradient-to-b from-ink-950/85 to-transparent"></div>
    <div class="absolute inset-0 bg-tech-grid opacity-[0.1]"></div>
  </div>
  <?php /* Курсор-реактивный спотлайт — JS выставляет --mx/--my. */ ?>
  <div class="hero-spotlight pointer-events-none absolute inset-0"></div>

  <div class="container-x relative z-10 flex min-h-[100svh] flex-col justify-center py-28">
    <div class="max-w-2xl">
      <span class="eyebrow reveal">Китайские авто · электромобили · все марки</span>

      <h1 class="reveal mt-5 font-display text-5xl font-bold uppercase leading-[0.95] tracking-wide text-white drop-shadow-[0_2px_30px_rgba(0,0,0,0.7)] sm:text-7xl xl:text-8xl">
        Ремонт авто в <?= e($city) ?> <span class="text-gradient">без переплат</span>
      </h1>

      <p class="reveal mt-5 max-w-xl text-xl font-medium uppercase tracking-wide text-white/90 drop-shadow-[0_1px_12px_rgba(0,0,0,0.6)] sm:text-2xl">
        Диагностика, ТО и ремонт — честный расчёт до начала работ
      </p>

      <p class="reveal mt-4 max-w-lg text-base leading-relaxed text-steel-300 drop-shadow-[0_1px_10px_rgba(0,0,0,0.6)]">
        Специалисты по китайским авто и электромобилям. Гарантия до 12&nbsp;месяцев, точная стоимость до начала ремонта и запись онлайн.
      </p>

      <div class="reveal mt-8 flex flex-wrap gap-4">
        <a href="/#zayavka" class="btn-primary text-base">
          Записаться
          <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h13"/><path d="M12 6l6 6-6 6"/></svg>
        </a>
        <?php if (!empty($co['phoneHref'])): ?>
          <a href="<?= e($co['phoneHref']) ?>" class="btn-ghost text-base" aria-label="Позвонить в LEVADA SERVICE: <?= e($co['phone']) ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
            <?= e($co['phone']) ?>
          </a>
        <?php endif; ?>
        <?php if (!empty($co['telegram'])): ?>
          <a href="<?= e($co['telegram']) ?>" target="_blank" rel="noopener" class="btn-ghost text-base" aria-label="Написать в Telegram">
            <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4" fill="currentColor"><path d="M21.94 4.3a1 1 0 0 0-1.05-.15L3.4 11.2c-.86.35-.82 1.6.06 1.9l4.3 1.43 1.66 5.27a1 1 0 0 0 1.62.43l2.45-2.2 4.3 3.16a1 1 0 0 0 1.57-.62l3-13.5a1 1 0 0 0-.42-1.02Z"/></svg>
            Telegram
          </a>
        <?php endif; ?>
      </div>

      <?php /* Мини-стат-полоса героя с CountUp. */ ?>
      <dl class="reveal mt-12 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <?php foreach ($heroStats as $st): ?>
          <div class="glass rounded-xl px-4 py-3">
            <dt class="font-display text-2xl font-bold text-white">
              <span data-count="<?= e((string) $st['count']) ?>" data-suffix="<?= e($st['suffix']) ?>"><?= e($st['value']) ?></span>
            </dt>
            <dd class="mt-0.5 text-xs text-steel-400"><?= e($st['label']) ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </div>
</section>

<?php /* ── ИНТЕРАКТИВНЫЙ ГАРАЖ ─────────────────────────────────────── */ ?>
<section id="garage" class="relative isolate scroll-mt-24 overflow-hidden border-y border-white/10">
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-b from-ink-950/80 via-ink-950/45 to-ink-950/85"></div>
    <div class="absolute inset-0 bg-tech-grid opacity-[0.1]"></div>
  </div>

  <div class="container-x py-20 sm:py-24">
    <div class="max-w-2xl reveal">
      <span class="eyebrow">Интерактивный бокс</span>
      <h2 class="mt-4 font-display text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl">Наведи на авто — узнай об услуге</h2>
      <p class="mt-3 max-w-xl text-base leading-relaxed text-steel-300">Нажми на светящуюся точку на машине, чтобы увидеть перечень работ и цены. Каждая зона — отдельное направление сервиса.</p>
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-[1.45fr_1fr]">
      <?php /* Сцена с хотспотами поверх services.png. */ ?>
      <div class="relative aspect-[1583/994] overflow-hidden rounded-2xl border border-white/12 bg-ink-900/40 reveal">
        <img src="<?= e(asset('img/services.png')) ?>" alt="Сервисный бокс LEVADA SERVICE: три каркасных кроссовера на подъёмниках" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-ink-950/15"></div>

        <?php foreach ($validHotspots as $h): ?>
          <?php $hex = $accents[$h['accent']] ?? '#178bff'; $svc = $svcBySlug[$h['slug']]; ?>
          <button
            class="hotspot absolute grid h-7 w-7 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full border-2 border-white"
            type="button"
            data-slug="<?= e($h['slug']) ?>"
            data-accent="<?= e($hex) ?>"
            style="left:<?= e((string) $h['xPct']) ?>%;top:<?= e((string) $h['yPct']) ?>%;background:<?= e($hex) ?>;box-shadow:0 0 10px <?= e($hex) ?>"
            aria-label="<?= e($svc['title'] ?? $h['label']) ?> — показать услугу"
          ></button>
        <?php endforeach; ?>
      </div>

      <?php /* Панель: статичный дефолт (первая услуга) — работает без JS. */ ?>
      <div id="garage-panel" class="min-h-full">
        <?php if ($defaultSvc):
          $hex = $accents[$defaultHot['accent']] ?? '#178bff'; ?>
          <div class="glass is-selected h-full rounded-2xl p-6" style="border-color:<?= e($hex) ?>cc">
            <div class="flex items-center gap-3">
              <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl border" style="color:<?= e($hex) ?>;border-color:<?= e($hex) ?>66;background:<?= e($hex) ?>1a">
                <?= svc_icon($defaultSvc['slug'], 'h-7 w-7') ?>
              </span>
              <div>
                <h3 class="font-display text-xl font-bold uppercase tracking-wide text-white"><?= e($defaultSvc['title']) ?></h3>
                <p class="text-xs text-steel-400"><?= e($defaultSvc['excerpt'] ?? '') ?></p>
              </div>
            </div>
            <?php if (!empty($defaultSvc['works'])): ?>
              <div class="mt-4">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-steel-400">Что делаем</p>
                <ul class="mt-2 space-y-1.5">
                  <?php foreach (array_slice($defaultSvc['works'], 0, 5) as $w): ?>
                    <li class="flex items-start gap-2 text-sm leading-snug text-steel-200">
                      <span class="mt-1.5 inline-block h-1.5 w-1.5 shrink-0 rounded-full" style="background:<?= e($hex) ?>"></span>
                      <span><?= e($w) ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
            <div class="mt-5 flex flex-wrap gap-3">
              <a href="/uslugi/<?= e($defaultSvc['slug']) ?>" class="btn-ghost text-xs">Подробнее</a>
              <a href="/#zayavka" class="btn-primary text-xs">Записаться</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php /* ── УСЛУГИ ───────────────────────────────────────────────────── */ ?>
<section id="uslugi" class="container-x mt-24 reveal">
  <?= section_heading([
      'eyebrow'  => 'Направления',
      'title'    => 'Услуги автосервиса в ' . $city,
      'subtitle' => 'Полный спектр работ по ремонту и обслуживанию — от диагностики и ТО до чип-тюнинга, русификации китайских авто и антикора.',
  ]) ?>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($allSvc as $s): ?>
      <div class="reveal"><?= service_card($s) ?></div>
    <?php endforeach; ?>
  </div>
  <div class="mt-8 text-center">
    <a href="/uslugi" class="btn-ghost">Смотреть все услуги и прайс</a>
  </div>
</section>

<?php /* ── ПОЧЕМУ МЫ (USP) ─────────────────────────────────────────── */ ?>
<section class="container-x mt-24 reveal">
  <?= section_heading([
      'eyebrow' => 'Почему LEVADA SERVICE',
      'title'   => 'Ремонт без переплат и сюрпризов',
      'center'  => true,
  ]) ?>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach ($co['usp'] as $u): ?>
      <div class="card-3d h-full p-6 reveal">
        <h3 class="font-display text-lg font-bold uppercase tracking-wide text-white"><?= e($u['title']) ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-steel-400"><?= e($u['text']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php /* ── СТАТИСТИКА (CountUp) ─────────────────────────────────────── */ ?>
<section class="container-x mt-24 reveal">
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <?php foreach ($co['achievements'] as $a):
      // Числовое значение для data-count; нечисловое (напр. «12 мес») — суффиксом.
      $num = (int) preg_replace('/\D/', '', (string) $a['value']);
      $suffix = trim((string) preg_replace('/[\d\s]/', '', (string) $a['value']));
      if (str_contains((string) $a['value'], '+')) { $suffix = '+'; }
      if (str_contains((string) $a['value'], 'мес')) { $suffix = ' мес'; }
    ?>
      <div class="glass rounded-2xl p-6 text-center reveal">
        <div class="font-display text-3xl font-bold text-white sm:text-4xl">
          <span data-count="<?= e((string) $num) ?>" data-suffix="<?= e($suffix) ?>"><?= e($a['value']) ?></span>
        </div>
        <div class="mt-2 text-sm leading-snug text-steel-400"><?= e($a['label']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php /* ── ПРОЦЕСС ──────────────────────────────────────────────────── */ ?>
<section class="container-x mt-24 reveal">
  <?= section_heading(['eyebrow' => 'Как мы работаем', 'title' => 'Четыре простых шага']) ?>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach ($co['process'] as $i => $p): ?>
      <div class="card-3d h-full p-6 reveal">
        <span class="font-display text-3xl font-bold text-neon-500/50"><?= e(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
        <h3 class="mt-2 font-display text-lg font-bold uppercase tracking-wide text-white"><?= e($p['step']) ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-steel-400"><?= e($p['text']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php /* ── ГАРАНТИИ + ОБОРУДОВАНИЕ ─────────────────────────────────── */ ?>
<section class="container-x mt-24 reveal">
  <div class="grid gap-5 lg:grid-cols-2">
    <div class="glass rounded-2xl p-6 sm:p-8 reveal">
      <span class="eyebrow">Гарантии</span>
      <h3 class="mt-3 font-display text-2xl font-bold uppercase tracking-wide text-white">Отвечаем за результат</h3>
      <ul class="mt-5 grid gap-3">
        <?php foreach ($co['guarantees'] as $g): ?>
          <li class="flex items-start gap-2.5 text-sm leading-relaxed text-steel-300">
            <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full border border-neon-500/40 bg-neon-500/15 text-xs text-neon-300" aria-hidden="true">&#10003;</span>
            <span><?= e($g) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="glass rounded-2xl p-6 sm:p-8 reveal">
      <span class="eyebrow">Опыт и оборудование</span>
      <h3 class="mt-3 font-display text-2xl font-bold uppercase tracking-wide text-white">Современный сервис</h3>
      <ul class="mt-5 grid gap-3">
        <?php foreach ($co['equipment'] as $eq): ?>
          <li class="flex items-start gap-2.5 text-sm leading-relaxed text-steel-300">
            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-neon-400" aria-hidden="true"></span>
            <span><?= e($eq) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<?php /* ── МАРКИ ────────────────────────────────────────────────────── */ ?>
<section id="marki" class="container-x mt-24 reveal">
  <?= section_heading([
      'eyebrow'  => 'Марки авто',
      'title'    => 'Обслуживаем большинство марок',
      'subtitle' => 'Знаем особенности каждой марки и используем дилерское диагностическое оборудование.',
  ]) ?>
  <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
    <?php foreach (array_slice(brands(), 0, 12) as $b): ?>
      <?= brand_card($b) ?>
    <?php endforeach; ?>
  </div>
  <div class="mt-8 text-center">
    <a href="/marki" class="btn-ghost">Все марки авто</a>
  </div>
</section>

<?php /* ── ЗАЯВКА (форма + способы записи) ─────────────────────────── */ ?>
<section id="zayavka" class="container-x mt-24 scroll-mt-24 reveal">
  <div class="grid items-start gap-10 lg:grid-cols-2">
    <div class="reveal">
      <?= section_heading([
          'eyebrow'  => 'Запись онлайн',
          'title'    => 'Оставьте заявку — перезвоним за 15 минут',
          'subtitle' => 'Опишите проблему, и мы рассчитаем стоимость ремонта и предложим удобное время.',
      ]) ?>
      <ul class="mt-6 space-y-3">
        <?php foreach (['Бесплатная консультация по телефону', 'Честный расчёт до начала работ', 'Гарантия на все виды работ'] as $t): ?>
          <li class="flex items-center gap-3 text-steel-300">
            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full border border-neon-500/40 bg-neon-500/15 text-sm text-neon-300" aria-hidden="true">&#10003;</span>
            <span><?= e($t) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>

      <?php /* Способы записи (bookingMethods). */ ?>
      <div class="mt-8 grid gap-4 sm:grid-cols-2">
        <?php foreach ($co['bookingMethods'] as $m): ?>
          <div class="glass rounded-2xl p-5 reveal">
            <div class="grid h-11 w-11 place-items-center rounded-xl border border-neon-500/25 bg-neon-500/10 text-white" aria-hidden="true"><?= ui_icon($m['icon'], 'h-6 w-6') ?></div>
            <h3 class="mt-3 font-display text-base font-bold uppercase tracking-wide text-white"><?= e($m['title']) ?></h3>
            <p class="mt-1.5 text-sm leading-relaxed text-steel-400"><?= e($m['text']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php /* Форма заявки — DOM-контракт main.js. action=/lead.php. */ ?>
    <div class="reveal">
      <form id="lead-form" action="/lead.php" method="post" class="glass rounded-2xl p-6 sm:p-8">
        <h3 class="font-display text-xl font-bold uppercase tracking-wide text-white">Оставьте заявку</h3>
        <p class="mt-1 text-sm text-steel-400">Перезвоним за 15 минут, рассчитаем стоимость и запишем на удобное время.</p>

        <?php /* honeypot — скрыт от людей, ловит ботов. */ ?>
        <input type="text" name="company_website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

        <div class="mt-4 grid gap-3">
          <div>
            <label for="lead-name" class="mb-1 block text-sm font-medium text-steel-300">Ваше имя <span class="text-signal-500">*</span></label>
            <input id="lead-name" name="name" required placeholder="Например, Иван" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-steel-500 outline-none focus:border-neon-500/60">
          </div>
          <div>
            <label for="lead-phone" class="mb-1 block text-sm font-medium text-steel-300">Телефон <span class="text-signal-500">*</span></label>
            <input id="lead-phone" name="phone" type="tel" required placeholder="+375 (__) ___-__-__" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-steel-500 outline-none focus:border-neon-500/60">
          </div>
          <div>
            <label for="lead-car" class="mb-1 block text-sm font-medium text-steel-300">Марка и модель авто</label>
            <input id="lead-car" name="car" placeholder="Geely Coolray, Tesla Model 3…" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-steel-500 outline-none focus:border-neon-500/60">
          </div>
          <div>
            <label for="lead-service" class="mb-1 block text-sm font-medium text-steel-300">Услуга</label>
            <select id="lead-service" name="service" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-neon-500/60">
              <option value="" class="bg-ink-900 text-white">Выберите услугу (необязательно)</option>
              <?php foreach ($allSvc as $s): ?>
                <option value="<?= e($s['title']) ?>" class="bg-ink-900 text-white"><?= e($s['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="lead-comment" class="mb-1 block text-sm font-medium text-steel-300">Комментарий</label>
            <textarea id="lead-comment" name="comment" rows="3" placeholder="Опишите проблему (необязательно)" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-steel-500 outline-none focus:border-neon-500/60"></textarea>
          </div>
        </div>

        <input type="hidden" name="source" value="/#zayavka">

        <div id="lead-status" role="status" aria-live="polite" class="mt-3 empty:hidden"></div>

        <button type="submit" class="btn-primary mt-4 w-full justify-center disabled:opacity-60">Отправить заявку</button>

        <div class="mt-3 flex items-center justify-center gap-3 text-xs text-steel-500">
          <span>Или сразу:</span>
          <?php if (!empty($co['phoneHref'])): ?><a href="<?= e($co['phoneHref']) ?>" class="text-neon-300 hover:text-neon-200">Позвонить</a><?php endif; ?>
          <?php if (!empty($co['telegram'])): ?><span>·</span><a href="<?= e($co['telegram']) ?>" target="_blank" rel="noopener" class="text-neon-300 hover:text-neon-200">Telegram</a><?php endif; ?>
        </div>
        <p class="mt-3 text-center text-[11px] leading-relaxed text-steel-600">Нажимая «Отправить», вы соглашаетесь на обработку персональных данных.</p>
      </form>
    </div>
  </div>
</section>

<?php /* ── FAQ (native details) ─────────────────────────────────────── */ ?>
<section class="container-x mt-24 reveal">
  <?= section_heading(['eyebrow' => 'Вопросы и ответы', 'title' => 'Частые вопросы', 'center' => true]) ?>
  <div class="mx-auto mt-10 max-w-3xl space-y-3">
    <?php foreach (($homeFaq ?? []) as $f): ?>
      <details class="faq-item card-3d group p-5 reveal">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-white">
          <span><?= e($f['q']) ?></span>
          <span class="text-neon-400 transition-transform group-open:rotate-45" aria-hidden="true">+</span>
        </summary>
        <div class="mt-3 text-sm leading-relaxed text-steel-400"><?= e($f['a']) ?></div>
      </details>
    <?php endforeach; ?>
  </div>
</section>

<?php /* ── ЛОКАЦИЯ ──────────────────────────────────────────────────── */ ?>
<section id="lokaciya" class="relative isolate mt-24 overflow-hidden border-t border-white/10 bg-ink-950 py-20 sm:py-24 reveal">
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-tech-grid opacity-[0.08]"></div>
  </div>
  <div class="container-x">
    <div class="grid items-center gap-10 lg:grid-cols-[1fr_1.25fr]">
      <div class="reveal">
        <span class="eyebrow">Как нас найти</span>
        <h2 class="mt-4 font-display text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl"><?= e($co['name']) ?> в <?= e($city) ?></h2>
        <p class="mt-3 max-w-md text-base leading-relaxed text-steel-300">Заезжайте на диагностику и ремонт — встретим, проведём по боксу и честно оценим стоимость работ.</p>

        <dl class="mt-7 space-y-4 text-sm">
          <div class="flex gap-3">
            <dt class="mt-0.5 shrink-0 text-neon-400" aria-hidden="true">
              <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </dt>
            <dd>
              <p class="font-semibold text-white">Адрес</p>
              <p class="text-steel-300"><?= e($co['address']['street']) ?><?= !empty($co['address']['district']) ? ', ' . e($co['address']['district']) : '' ?></p>
              <p class="text-steel-300"><?= e($city) ?>, <?= e($co['address']['countryName']) ?></p>
            </dd>
          </div>
          <div class="flex gap-3">
            <dt class="mt-0.5 shrink-0 text-neon-400" aria-hidden="true">
              <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 2"/></svg>
            </dt>
            <dd>
              <p class="font-semibold text-white">Режим работы</p>
              <?php foreach ($co['openingHours'] as $oh): ?>
                <p class="text-steel-300"><?= e($oh['days']) ?>: <span class="text-white"><?= e($oh['time']) ?></span></p>
              <?php endforeach; ?>
            </dd>
          </div>
        </dl>

        <div class="mt-7 flex flex-wrap gap-3">
          <?php if (!empty($co['phoneHref'])): ?><a href="<?= e($co['phoneHref']) ?>" class="btn-primary text-sm"><?= e($co['phone']) ?></a><?php endif; ?>
          <a href="<?= e($mapsLink) ?>" target="_blank" rel="noopener noreferrer" class="btn-ghost text-sm">Открыть в картах</a>
        </div>
      </div>

      <div class="perspective reveal">
        <div class="relative mx-auto max-w-2xl">
          <div class="relative overflow-hidden rounded-2xl border border-white/12" style="transform:rotateX(38deg) rotateZ(-2deg);transform-origin:center bottom">
            <?php if ($osmEmbed): ?>
              <iframe title="Карта: <?= e($fullAddress) ?>" src="<?= e($osmEmbed) ?>" loading="lazy" class="h-[340px] w-full [color-scheme:dark]" style="border:0;filter:invert(0.92) hue-rotate(180deg) grayscale(0.35) contrast(1.05)"></iframe>
            <?php else: ?>
              <div class="grid h-[340px] w-full place-items-center bg-ink-900 bg-tech-grid"><p class="text-sm text-steel-400"><?= e($fullAddress) ?></p></div>
            <?php endif; ?>
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink-950/70 via-transparent to-ink-950/20"></div>
          </div>
          <div class="mt-5 glass rounded-xl px-4 py-3 text-center">
            <p class="font-display text-sm font-semibold uppercase tracking-wide text-white"><?= e($fullAddress) ?></p>
            <p class="mt-0.5 text-xs text-steel-400"><?= e($co['legalName']) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
