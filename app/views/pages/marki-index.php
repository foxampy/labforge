<?php
/**
 * Страница «Марки авто» — листинг всех 43 марок.
 * Доступно: $crumbs (массив ['name'=>..,'path'=>..]).
 * Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 */

// Хлебные крошки: helper ждёт href, $crumbs использует path — мапим.
echo breadcrumbs(array_map(
    static fn ($c) => ['name' => $c['name'], 'href' => $c['path']],
    $crumbs
));

// Короткие интро под каждую группу для перелинковки/SEO.
$group_intro = [
    'china' => 'Наша специализация: Geely, Chery, Haval, Changan и другие — турбомоторы, роботы DCT, антикор.',
    'ev'    => 'Электромобили и гибриды: ТО ходовой, защита батареи, комплексная русификация интерфейса.',
    'euro'  => 'Немецкая и европейская техника: TSI/TDI, DSG, пневмоподвеска, диагностика с дилерским ПО.',
    'asia'  => 'Японские и корейские авто: надёжные моторы, вариаторы, полный привод, антикор кузова.',
];
?>

<section class="container-x mt-8 reveal">
  <?= section_heading([
      'eyebrow'  => 'Марки авто',
      'title'    => 'Ремонт по маркам автомобилей в Минске',
      'subtitle' => 'Знаем типичные неисправности каждой марки и используем дилерское диагностическое оборудование и ПО.',
  ]) ?>

  <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
    <?php foreach (brands() as $b): ?>
      <?= brand_card($b) ?>
    <?php endforeach; ?>
  </div>
</section>

<?php // Группы по происхождению — внутренняя перелинковка. ?>
<section class="container-x mt-20 reveal">
  <div class="grid gap-6 lg:grid-cols-2">
    <?php foreach (brand_groups() as $g): ?>
      <?php if (empty($g['items'])) { continue; } ?>
      <?php
        // Определяем ключ группы по первой марке, чтобы подобрать интро.
        $first   = find_brand($g['items'][0]['slug']);
        $gkey    = $first['group'] ?? '';
        $intro   = $group_intro[$gkey] ?? '';
      ?>
      <div class="glass rounded-2xl p-6 reveal">
        <h2 class="font-display text-lg font-bold uppercase tracking-wide text-white"><?= e($g['title']) ?></h2>
        <?php if ($intro !== ''): ?>
          <p class="mt-2 text-sm leading-relaxed text-steel-400"><?= e($intro) ?></p>
        <?php endif; ?>
        <div class="mt-4 flex flex-wrap gap-2">
          <?php foreach ($g['items'] as $bi): ?>
            <?php $bf = find_brand($bi['slug']); ?>
            <a href="/marki/<?= e($bi['slug']) ?>" class="chip hover:text-white">
              <?= e($bi['name']) ?>
              <?php if (!empty($bf['ev'])): ?>
                <span class="ml-1 text-[10px] uppercase tracking-wide text-neon-300">EV</span>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?= cta_band() ?>
