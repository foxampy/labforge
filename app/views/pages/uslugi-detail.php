<?php
/**
 * Страница услуги — деталь: /uslugi/<slug>.
 * Доступно: $s (assoc-массив услуги), $crumbs (['name'=>.., 'path'=>..]).
 * Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 */

$co    = company();
$city  = $co['address']['city'] ?? 'Минск';
$slug  = $s['slug'] ?? '';
$title = $s['title'] ?? '';
$h1    = $s['h1'] ?? $title;

// Хлебные крошки: helper ждёт href, $crumbs использует path.
echo breadcrumbs(array_map(
    static fn ($c) => ['name' => $c['name'], 'href' => $c['path']],
    $crumbs
));

// Другие услуги для перелинковки: исключаем текущую, берём несколько.
$others = array_values(array_filter(
    services(),
    static fn ($x) => ($x['slug'] ?? null) !== $slug
));
$others = array_slice($others, 0, 4);
?>

<article class="container-x mt-8">
  <div class="grid gap-10 lg:grid-cols-[1.6fr_1fr]">
    <?php // ───────────────────────── Контент ───────────────────────── ?>
    <div class="reveal">
      <?php // Шапка-герой: бейдж-стекло с иконкой услуги + чип города. ?>
      <div class="flex flex-wrap items-center gap-4">
        <span class="glass grid h-16 w-16 shrink-0 place-items-center rounded-2xl border border-neon-500/25 text-white">
          <?= svc_icon($slug, 'h-10 w-10') ?>
        </span>
        <span class="chip">Автосервис &middot; <?= e($city) ?></span>
      </div>

      <h1 class="mt-6 font-display text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl">
        <?= e($h1) ?>
      </h1>

      <?php if (!empty($s['excerpt'])): ?>
        <p class="mt-4 text-lg leading-relaxed text-steel-200"><?= e($s['excerpt']) ?></p>
      <?php endif; ?>

      <?php if (!empty($s['intro'])): ?>
        <p class="mt-4 text-base leading-relaxed text-steel-300"><?= e($s['intro']) ?></p>
      <?php endif; ?>

      <?php // Кнопки записи/звонка. ?>
      <div class="mt-7 flex flex-wrap gap-3">
        <a href="/#zayavka" class="btn-primary text-base">Записаться на услугу</a>
        <?php if (!empty($co['phoneHref']) && !empty($co['phone'])): ?>
          <a href="<?= e($co['phoneHref']) ?>" class="btn-ghost text-base" aria-label="Позвонить в LEVADA SERVICE">
            <?= e($co['phone']) ?>
          </a>
        <?php endif; ?>
      </div>

      <?php // ───────────────────── Что входит в работы ───────────────────── ?>
      <?php if (!empty($s['works'])): ?>
        <h2 class="mt-10 font-display text-2xl font-bold uppercase tracking-wide text-white">Что входит в работы</h2>
        <ul class="mt-5 grid gap-2 sm:grid-cols-2">
          <?php foreach ($s['works'] as $w): ?>
            <li class="flex items-start gap-2.5 rounded-xl border border-white/5 bg-white/[0.03] p-3 text-sm text-steel-300">
              <svg viewBox="0 0 20 20" aria-hidden="true" class="mt-0.5 h-4 w-4 shrink-0 text-neon-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 10.5l4 4 8-9"/>
              </svg>
              <span><?= e($w) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php // ───────────────────────── Стоимость ───────────────────────── ?>
      <?php if (!empty($s['prices'])): ?>
        <h2 class="mt-10 font-display text-2xl font-bold uppercase tracking-wide text-white">Стоимость</h2>
        <div class="glass mt-5 overflow-hidden rounded-2xl border border-white/10">
          <table class="w-full text-sm">
            <caption class="sr-only">Цены на услугу «<?= e($title) ?>» в BYN</caption>
            <tbody>
              <?php foreach (array_values($s['prices']) as $i => $p): ?>
                <tr class="<?= $i % 2 ? 'bg-white/[0.02]' : '' ?>">
                  <td class="px-4 py-3 text-steel-300"><?= e($p['name'] ?? '') ?></td>
                  <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-neon-300"><?= e($p['price'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="mt-3 text-xs text-steel-500">
          Цены указаны ориентировочно. Точную стоимость рассчитаем после диагностики.
        </p>
      <?php endif; ?>

      <?php // ────────────────────────── Вопросы ────────────────────────── ?>
      <?php if (!empty($s['faq'])): ?>
        <h2 class="mt-10 font-display text-2xl font-bold uppercase tracking-wide text-white">Частые вопросы</h2>
        <div class="mt-5 space-y-3">
          <?php foreach ($s['faq'] as $f): ?>
            <details class="faq-item card-3d group p-5">
              <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-white">
                <span><?= e($f['q'] ?? '') ?></span>
                <span class="text-neon-400 transition-transform group-open:rotate-45" aria-hidden="true">+</span>
              </summary>
              <div class="mt-3 text-sm leading-relaxed text-steel-400"><?= e($f['a'] ?? '') ?></div>
            </details>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php // Ключевые слова — лёгкая семантика для перелинковки/SEO. ?>
      <?php if (!empty($s['keywords'])): ?>
        <div class="mt-10 flex flex-wrap gap-2">
          <?php foreach ($s['keywords'] as $kw): ?>
            <span class="chip"><?= e($kw) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php // ───────────────────── Сайдбар: запись ───────────────────── ?>
    <aside class="lg:sticky lg:top-24 lg:self-start reveal">
      <div class="glass rounded-2xl p-6">
        <h2 class="font-display text-lg font-bold uppercase tracking-wide text-white">Запись на услугу</h2>
        <p class="mt-2 text-sm leading-relaxed text-steel-400">
          Оставьте заявку — перезвоним за 15 минут, рассчитаем стоимость&nbsp;«<?= e($title) ?>» и подберём удобное время.
        </p>
        <div class="mt-5 flex flex-col gap-3">
          <a href="/#zayavka" class="btn-primary w-full justify-center text-base">Оставить заявку</a>
          <?php if (!empty($co['phoneHref']) && !empty($co['phone'])): ?>
            <a href="<?= e($co['phoneHref']) ?>" class="btn-ghost w-full justify-center text-base" aria-label="Позвонить в LEVADA SERVICE">
              <?= e($co['phone']) ?>
            </a>
          <?php endif; ?>
          <?php if (!empty($co['telegram'])): ?>
            <a href="<?= e($co['telegram']) ?>" class="link-muted text-center text-sm" rel="noopener" target="_blank">Написать в Telegram</a>
          <?php endif; ?>
        </div>
      </div>
    </aside>
  </div>

  <?php // ───────────────────────── Другие услуги ───────────────────────── ?>
  <?php if (!empty($others)): ?>
    <div class="mt-16 reveal">
      <h2 class="font-display text-xl font-bold uppercase tracking-wide text-white">Другие услуги</h2>
      <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($others as $o): ?>
          <div class="reveal"><?= service_card($o) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</article>

<?= cta_band() ?>
