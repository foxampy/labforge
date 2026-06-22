<?php
/**
 * Страница: /o-nas — о компании LEVADA SERVICE.
 * Доступно: $crumbs (массив ['name'=>,'path'=>]).
 * Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 */
$co        = company();
$city      = $co['address']['city'] ?? 'Минск';
$founded   = (int) ($co['foundedYear'] ?? 2014);
$years     = max(1, (int) date('Y') - $founded);

// Хлебные крошки: helper ждёт href, $crumbs использует path.
echo breadcrumbs(array_map(
    static fn ($c) => ['name' => $c['name'], 'href' => $c['path']],
    $crumbs
));
?>

<?php /* ── Интро ────────────────────────────────────────────────────── */ ?>
<section class="container-x mt-8 reveal">
  <?= section_heading([
      'eyebrow'  => 'О компании',
      'title'    => 'Автосервис ' . $co['name'] . ' в ' . $city,
      'subtitle' => 'Работаем с ' . $founded . ' года — это более ' . $years . ' лет ежедневной практики. Специализируемся на китайских автомобилях и электромобилях и обслуживаем все марки: от ТО и диагностики до чип-тюнинга, русификации и антикора.',
  ]) ?>

  <div class="mt-8 grid gap-5 lg:grid-cols-2">
    <div class="glass rounded-2xl p-6 sm:p-8 reveal">
      <p class="text-base leading-relaxed text-steel-300">
        <?= e($co['legalName']) ?> — это команда мастеров, которые знают современный автомобиль изнутри. Мы выросли вместе с рынком: когда в Беларуси появились первые Geely, Chery и Haval, а затем электромобили Tesla, Li Auto и Zeekr, мы первыми разобрались в их особенностях — турбомоторах, роботизированных коробках, тяговых батареях и закрытой электронике.
      </p>
      <p class="mt-4 text-base leading-relaxed text-steel-300">
        Сегодня в <?= e($co['name']) ?> можно обслужить практически любой автомобиль: китайский кроссовер, европейский премиум, японскую классику, корейскую марку, коммерческий фургон или электромобиль. Мы делаем честно и прозрачно — с письменной гарантией и согласованием цены до начала работ.
      </p>
    </div>
    <div class="glass rounded-2xl p-6 sm:p-8 reveal">
      <h3 class="font-display text-lg font-bold uppercase tracking-wide text-white">Чем мы занимаемся</h3>
      <ul class="mt-4 grid gap-2.5">
        <?php foreach ([
            'Слесарный ремонт: двигатель, ходовая, КПП, тормоза, рулевое',
            'Компьютерная диагностика и обслуживание (ТО)',
            'Чип-тюнинг и русификация китайских авто',
            'Обслуживание и диагностика электромобилей',
            'Антикоррозийная обработка кузова',
            'Подготовка автомобиля к сезону',
        ] as $item): ?>
          <li class="flex items-start gap-2.5 text-sm text-steel-300">
            <span class="mt-0.5 text-neon-400" aria-hidden="true">&#10003;</span>
            <span><?= e($item) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <a href="/uslugi" class="link-muted mt-5 inline-flex items-center gap-1 text-sm font-semibold text-neon-400">
        Все услуги и цены <span aria-hidden="true">&rarr;</span>
      </a>
    </div>
  </div>
</section>

<?php /* ── Показатели (CountUp) ─────────────────────────────────────── */ ?>
<section class="container-x mt-16 reveal">
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <?php foreach ($co['achievements'] as $a):
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

<?php /* ── Преимущества (USP) ───────────────────────────────────────── */ ?>
<section class="container-x mt-20 reveal">
  <?= section_heading([
      'eyebrow'  => 'Почему выбирают нас',
      'title'    => 'Преимущества ' . $co['name'],
      'subtitle' => 'Четыре причины, по которым нам доверяют ремонт и обслуживание автомобиля.',
  ]) ?>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach ($co['usp'] as $i => $u): ?>
      <div class="card-3d p-6 reveal">
        <div class="grid h-12 w-12 place-items-center rounded-xl border border-neon-500/25 bg-neon-500/10 font-display text-xl font-bold text-neon-300"><?= e((string) ($i + 1)) ?></div>
        <h3 class="mt-4 font-display text-lg font-bold uppercase tracking-wide text-white"><?= e($u['title']) ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-steel-400"><?= e($u['text']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php /* ── Процесс ──────────────────────────────────────────────────── */ ?>
<section class="container-x mt-20 reveal">
  <?= section_heading([
      'eyebrow'  => 'Процесс',
      'title'    => 'Как мы работаем',
      'subtitle' => 'Прозрачный путь от заявки до выдачи автомобиля — без скрытых работ и неожиданных счетов.',
  ]) ?>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach ($co['process'] as $i => $p): ?>
      <div class="glass rounded-2xl p-6 reveal">
        <span class="eyebrow">Шаг <?= e((string) ($i + 1)) ?></span>
        <h3 class="mt-3 font-display text-lg font-bold uppercase tracking-wide text-white"><?= e($p['step']) ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-steel-400"><?= e($p['text']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php /* ── Гарантии + оборудование ─────────────────────────────────── */ ?>
<section class="container-x mt-20 reveal">
  <div class="grid gap-5 lg:grid-cols-2">
    <div class="glass rounded-2xl p-6 sm:p-8 reveal">
      <span class="eyebrow">Гарантии</span>
      <h3 class="mt-3 font-display text-2xl font-bold uppercase tracking-wide text-white">Отвечаем за результат</h3>
      <ul class="mt-5 grid gap-3">
        <?php foreach ($co['guarantees'] as $g): ?>
          <li class="flex items-start gap-2.5 text-sm leading-relaxed text-steel-300">
            <span class="mt-0.5 text-neon-400" aria-hidden="true">&#10003;</span>
            <span><?= e($g) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="glass rounded-2xl p-6 sm:p-8 reveal">
      <span class="eyebrow">Опыт и оборудование</span>
      <h3 class="mt-3 font-display text-2xl font-bold uppercase tracking-wide text-white">Современный сервис</h3>
      <p class="mt-3 text-sm leading-relaxed text-steel-400">За <?= e((string) $years) ?>+ лет мы собрали оснащение, которое позволяет точно диагностировать и качественно ремонтировать даже сложные современные авто и электромобили.</p>
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

<?php /* ── FAQ (native details) ─────────────────────────────────────── */ ?>
<section class="container-x mt-20 reveal">
  <?= section_heading([
      'eyebrow'  => 'Вопросы и ответы',
      'title'    => 'Частые вопросы о сервисе',
      'subtitle' => 'Коротко о главном. Если остались вопросы — позвоните или напишите в Telegram.',
  ]) ?>
  <div class="mx-auto mt-8 grid max-w-3xl gap-3">
    <?php foreach (($co['aboutFaq'] ?? []) as $f): ?>
      <details class="faq-item glass group rounded-2xl p-5 open:bg-white/[0.07] reveal">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-semibold text-white">
          <span><?= e($f['q']) ?></span>
          <span class="text-neon-400 transition-transform group-open:rotate-45" aria-hidden="true">+</span>
        </summary>
        <div class="mt-3 text-sm leading-relaxed text-steel-400"><?= e($f['a']) ?></div>
      </details>
    <?php endforeach; ?>
  </div>
</section>

<?= cta_band() ?>
