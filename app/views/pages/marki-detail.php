<?php
/**
 * Страница марки — деталь.
 * Доступно: $b (assoc-массив марки), $crumbs (['name'=>..,'path'=>..]).
 * Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 */

$co   = company();
$name = $b['name'] ?? '';
$path = '/marki/' . ($b['slug'] ?? '');

// Хлебные крошки: helper ждёт href, $crumbs использует path.
echo breadcrumbs(array_map(
    static fn ($c) => ['name' => $c['name'], 'href' => $c['path']],
    $crumbs
));

// Бейджи спецспециализаций (только если флаг true в данных).
$badges = [];
if (!empty($b['ev']))         { $badges[] = 'Электромобиль'; }
if (!empty($b['antikor']))    { $badges[] = 'Антикор'; }
if (!empty($b['commercial'])) { $badges[] = 'Коммерческий транспорт'; }

// Услуги для перелинковки: сначала «столпы», добиваем до 6 общими.
$svc = pillar_services();
foreach (services() as $s) {
    if (count($svc) >= 6) { break; }
    $exists = false;
    foreach ($svc as $p) {
        if (($p['slug'] ?? null) === ($s['slug'] ?? null)) { $exists = true; break; }
    }
    if (!$exists) { $svc[] = $s; }
}
$svc = array_slice($svc, 0, 6);

// Прочие марки для перелинковки.
$others = array_values(array_filter(
    brands(),
    static fn ($x) => ($x['slug'] ?? null) !== ($b['slug'] ?? null)
));
?>

<article class="container-x mt-8">
  <div class="grid gap-10 lg:grid-cols-[1.6fr_1fr]">
    <div class="reveal">
      <?php // Заголовок-стекло с монограммой. ?>
      <div class="glass flex flex-wrap items-center gap-4 rounded-2xl p-5">
        <span class="group grid h-16 w-16 shrink-0 place-items-center rounded-2xl border border-white/10 bg-gradient-to-br from-white/10 to-white/0">
          <?= brand_logo_img($b, 'h-11 w-11', 'text-2xl') ?>
        </span>
        <div class="flex flex-wrap items-center gap-2">
          <?php if (!empty($b['country'])): ?>
            <span class="chip"><?= e($b['country']) ?></span>
          <?php endif; ?>
          <?php foreach ($badges as $bd): ?>
            <span class="chip is-selected"><?= e($bd) ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <h1 class="mt-6 font-display text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl">
        Ремонт и обслуживание <?= e($name) ?>
      </h1>

      <?php if (!empty($b['intro'])): ?>
        <p class="mt-4 text-lg leading-relaxed text-steel-300"><?= e($b['intro']) ?></p>
      <?php endif; ?>

      <?php // Кнопки записи/звонка. ?>
      <div class="mt-7 flex flex-wrap gap-3">
        <a href="/#zayavka" class="btn-primary text-base">Записаться на ремонт</a>
        <?php if (!empty($co['phoneHref']) && !empty($co['phone'])): ?>
          <a href="<?= e($co['phoneHref']) ?>" class="btn-ghost text-base" aria-label="Позвонить в LEVADA SERVICE">
            <?= e($co['phone']) ?>
          </a>
        <?php endif; ?>
      </div>

      <?php if (!empty($b['commonIssues'])): ?>
        <h2 class="mt-10 font-display text-2xl font-bold uppercase tracking-wide text-white">Частые неисправности <?= e($name) ?></h2>
        <ul class="mt-5 grid gap-2 sm:grid-cols-2">
          <?php foreach ($b['commonIssues'] as $w): ?>
            <li class="flex items-start gap-2.5 rounded-xl border border-white/5 bg-white/[0.03] p-3 text-sm text-steel-300">
              <svg viewBox="0 0 20 20" aria-hidden="true" class="mt-0.5 h-4 w-4 shrink-0 text-neon-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 2l8 14H2L10 2z"/><path d="M10 8v4M10 14.5v.5"/>
              </svg>
              <span><?= e($w) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if (!empty($b['popularModels'])): ?>
        <h2 class="mt-10 font-display text-2xl font-bold uppercase tracking-wide text-white">Популярные модели</h2>
        <div class="mt-4 flex flex-wrap gap-2">
          <?php foreach ($b['popularModels'] as $m): ?>
            <span class="chip"><?= e($name) ?> <?= e($m) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($svc)): ?>
        <h2 class="mt-10 font-display text-2xl font-bold uppercase tracking-wide text-white">Услуги для <?= e($name) ?></h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($svc as $s): ?>
            <?= service_card($s) ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php // Сайдбар: блок записи (форма строится отдельно, тут — CTA-карточка). ?>
    <aside class="lg:sticky lg:top-24 lg:self-start reveal">
      <div class="glass rounded-2xl p-6">
        <h2 class="font-display text-lg font-bold uppercase tracking-wide text-white">Запись на сервис</h2>
        <p class="mt-2 text-sm leading-relaxed text-steel-400">
          Оставьте заявку — перезвоним за 15 минут, рассчитаем стоимость ремонта <?= e($name) ?> и подберём время.
        </p>
        <div class="mt-5 flex flex-col gap-3">
          <a href="/#zayavka" class="btn-primary w-full justify-center text-base">Оставить заявку</a>
          <?php if (!empty($co['phoneHref']) && !empty($co['phone'])): ?>
            <a href="<?= e($co['phoneHref']) ?>" class="btn-ghost w-full justify-center text-base"><?= e($co['phone']) ?></a>
          <?php endif; ?>
          <?php if (!empty($co['telegram'])): ?>
            <a href="<?= e($co['telegram']) ?>" class="link-muted text-center text-sm" rel="noopener" target="_blank">Написать в Telegram</a>
          <?php endif; ?>
        </div>
      </div>
    </aside>
  </div>

  <?php if (!empty($others)): ?>
    <div class="mt-16 reveal">
      <h2 class="font-display text-xl font-bold uppercase tracking-wide text-white">Ремонт других марок</h2>
      <div class="mt-5 flex flex-wrap gap-2">
        <?php foreach ($others as $o): ?>
          <a href="/marki/<?= e($o['slug']) ?>" class="chip hover:text-white"><?= e($o['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</article>

<?= cta_band() ?>
