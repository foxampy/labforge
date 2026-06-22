<?php
/**
 * Страница: /uslugi — каталог услуг автосервиса LEVADA SERVICE.
 * Доступные переменные: $crumbs (array of ['name'=>, 'path'=>]).
 * Выводит только тело страницы (оборачивается в <main> в layout.php).
 */
$company  = company();
$city     = $company['address']['city'] ?? 'Минск';
$allSvc   = services();
$groups   = service_groups();
?>
<?= breadcrumbs(array_map(fn ($c) => ['name' => $c['name'], 'href' => $c['path']], $crumbs)) ?>

<section class="container-x mt-8 reveal">
  <?= section_heading([
      'eyebrow'  => 'Направления',
      'title'    => 'Услуги автосервиса и цены',
      'subtitle' => 'Полный спектр работ по ремонту и обслуживанию автомобилей — от диагностики и ТО до чип-тюнинга, русификации китайских авто и антикора. Работаем со всеми системами авто, ' . $city . ' и пригород.',
  ]) ?>

  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($allSvc as $s): ?>
      <div class="reveal"><?= service_card($s) ?></div>
    <?php endforeach; ?>
  </div>
</section>

<?php /* Полный перечень работ, сгруппированный по направлениям. */ ?>
<section class="container-x mt-20 reveal">
  <?= section_heading([
      'title'    => 'Полный перечень работ',
      'subtitle' => 'Что именно мы выполняем по каждому направлению — с честными ценами в BYN.',
  ]) ?>

  <div class="mt-10 grid gap-6 lg:grid-cols-3">
    <?php foreach ($groups as $g): ?>
      <div class="glass card-3d reveal flex flex-col rounded-2xl p-6">
        <h3 class="font-display text-lg font-bold uppercase tracking-wide text-white">
          <?= e($g['title']) ?>
        </h3>
        <span class="eyebrow mt-1">Цены от 10 BYN · гарантия на работы</span>

        <ul class="mt-5 flex flex-col gap-5">
          <?php foreach ($g['items'] as $item): ?>
            <?php $svc = find_service($item['slug']); ?>
            <li>
              <a href="/uslugi/<?= e($item['slug']) ?>"
                 class="group flex items-center gap-3 text-white transition-colors hover:text-neon-300">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-neon-500/25 bg-neon-500/10 text-white transition-colors group-hover:border-neon-500/60">
                  <?= svc_icon($item['slug'], 'h-6 w-6') ?>
                </span>
                <span class="font-display text-base font-bold uppercase tracking-wide">
                  <?= e($item['title']) ?>
                </span>
              </a>
              <?php if (!empty($svc['works'])): ?>
                <ul class="mt-2.5 grid gap-1.5 pl-[3.25rem]">
                  <?php foreach (array_slice($svc['works'], 0, 5) as $w): ?>
                    <li class="flex items-start gap-2 text-sm leading-relaxed text-steel-400">
                      <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-neon-400"></span>
                      <span><?= e($w) ?></span>
                    </li>
                  <?php endforeach; ?>
                  <?php if (count($svc['works']) > 5): ?>
                    <li class="pl-3 text-sm text-steel-500">и другое…</li>
                  <?php endif; ?>
                </ul>
              <?php endif; ?>
              <a href="/uslugi/<?= e($item['slug']) ?>"
                 class="link-muted mt-3 inline-flex items-center gap-1 pl-13 text-sm font-semibold text-neon-400">
                Подробнее и цены
                <span aria-hidden="true">&rarr;</span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?= cta_band() ?>
