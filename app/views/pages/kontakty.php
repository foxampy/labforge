<?php
/**
 * Страница: /kontakty — контакты LEVADA SERVICE.
 * Доступно: $crumbs (массив ['name'=>,'path'=>]).
 * Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 *
 * Форма заявки повторяет DOM-контракт home.php: form#lead-form, #lead-status,
 * honeypot company_website. Поля совпадают с lead.php (name, phone, car,
 * service, comment, source).
 */
$co       = company();
$city     = $co['address']['city'] ?? 'Минск';
$allSvc   = services();

$lat = $co['geo']['lat'] ?? null;
$lng = $co['geo']['lng'] ?? null;
$hasGeo = is_numeric($lat) && is_numeric($lng);
$fullAddress = ($co['address']['street'] ?? '') . ', ' . $city;
$d = 0.012;
$osmEmbed = $hasGeo
  ? 'https://www.openstreetmap.org/export/embed.html?bbox=' . ($lng - $d) . '%2C' . ($lat - $d) . '%2C' . ($lng + $d) . '%2C' . ($lat + $d) . '&layer=mapnik&marker=' . $lat . '%2C' . $lng
  : null;
$yandexUrl = $hasGeo
  ? 'https://yandex.ru/maps/?text=' . rawurlencode($co['name'] . ', ' . $fullAddress) . '&ll=' . $lng . ',' . $lat . '&z=17'
  : 'https://yandex.ru/maps/?text=' . rawurlencode($fullAddress);
$googleUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($fullAddress . ', ' . ($co['address']['countryName'] ?? ''));

// Хлебные крошки: helper ждёт href, $crumbs использует path.
echo breadcrumbs(array_map(
    static fn ($c) => ['name' => $c['name'], 'href' => $c['path']],
    $crumbs
));
?>

<section class="container-x mt-8 reveal">
  <?= section_heading([
      'eyebrow'  => 'Контакты',
      'title'    => 'Как с нами связаться',
      'subtitle' => 'Автосервис ' . $co['name'] . ' в ' . $city . '. Звоните, пишите в Telegram или оставьте заявку — поможем с ремонтом, диагностикой и записью.',
  ]) ?>
</section>

<?php /* ── Контакты + режим работы + форма ─────────────────────────── */ ?>
<section class="container-x mt-10 grid gap-6 lg:grid-cols-2">
  <div class="grid gap-5 reveal">
    <div class="glass rounded-2xl p-6 sm:p-8">
      <h3 class="font-display text-xl font-bold uppercase tracking-wide text-white">Контактная информация</h3>
      <dl class="mt-5 grid gap-5">
        <div class="flex items-start gap-3">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/5 text-neon-300" aria-hidden="true">
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </span>
          <div>
            <dt class="text-xs uppercase tracking-wide text-steel-500">Адрес</dt>
            <dd class="mt-1 text-sm text-steel-200"><?= e($co['address']['street']) ?><br><?= !empty($co['address']['district']) ? e($co['address']['district']) . ', ' : '' ?><?= e($city) ?>, <?= e($co['address']['countryName']) ?></dd>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/5 text-neon-300" aria-hidden="true">
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
          </span>
          <div>
            <dt class="text-xs uppercase tracking-wide text-steel-500">Телефон</dt>
            <dd class="mt-1 text-sm"><a href="<?= e($co['phoneHref']) ?>" class="font-semibold text-white hover:text-neon-300"><?= e($co['phone']) ?></a></dd>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/5 text-neon-300" aria-hidden="true">
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
          </span>
          <div>
            <dt class="text-xs uppercase tracking-wide text-steel-500">E-mail</dt>
            <dd class="mt-1 text-sm"><a href="mailto:<?= e($co['email']) ?>" class="text-steel-200 hover:text-neon-300"><?= e($co['email']) ?></a></dd>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/5 text-neon-300" aria-hidden="true">
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor"><path d="M21.94 4.3a1 1 0 0 0-1.05-.15L3.4 11.2c-.86.35-.82 1.6.06 1.9l4.3 1.43 1.66 5.27a1 1 0 0 0 1.62.43l2.45-2.2 4.3 3.16a1 1 0 0 0 1.57-.62l3-13.5a1 1 0 0 0-.42-1.02Z"/></svg>
          </span>
          <div>
            <dt class="text-xs uppercase tracking-wide text-steel-500">Telegram</dt>
            <dd class="mt-1 text-sm"><a href="<?= e($co['telegram']) ?>" target="_blank" rel="noopener" class="font-semibold text-white hover:text-neon-300">Написать в Telegram</a></dd>
          </div>
        </div>
      </dl>

      <div class="mt-6 flex flex-wrap gap-3">
        <a href="<?= e($co['phoneHref']) ?>" class="btn-primary">Позвонить</a>
        <a href="<?= e($co['telegram']) ?>" target="_blank" rel="noopener" class="btn-ghost inline-flex items-center gap-2">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M21.94 4.3a1 1 0 0 0-1.05-.15L3.4 11.2c-.86.35-.82 1.6.06 1.9l4.3 1.43 1.66 5.27a1 1 0 0 0 1.62.43l2.45-2.2 4.3 3.16a1 1 0 0 0 1.57-.62l3-13.5a1 1 0 0 0-.42-1.02Z"/></svg>
          Telegram
        </a>
        <?php if (!empty($co['whatsapp'])): ?>
          <a href="<?= e($co['whatsapp']) ?>" target="_blank" rel="noopener" class="btn-ghost">WhatsApp</a>
        <?php endif; ?>
        <?php if (!empty($co['social']['instagram'])): ?>
          <a href="<?= e($co['social']['instagram']) ?>" target="_blank" rel="noopener" class="btn-ghost">Instagram</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="glass rounded-2xl p-6 sm:p-8">
      <h3 class="font-display text-xl font-bold uppercase tracking-wide text-white">Режим работы</h3>
      <ul class="mt-4 grid gap-2">
        <?php foreach ($co['openingHours'] as $oh): ?>
          <li class="flex items-center justify-between border-b border-white/5 pb-2 text-sm last:border-0 last:pb-0">
            <span class="text-steel-400"><?= e($oh['days']) ?></span>
            <span class="font-semibold text-steel-100"><?= e($oh['time']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="mt-4 text-xs leading-relaxed text-steel-500">Работаем без выходных. В праздничные дни режим может отличаться — уточняйте по телефону.</p>
    </div>
  </div>

  <?php /* Форма заявки — тот же DOM-контракт, что на главной. */ ?>
  <div id="zayavka" class="scroll-mt-24 reveal">
    <form id="lead-form" action="/lead.php" method="post" class="glass rounded-2xl p-6 sm:p-8">
      <h3 class="font-display text-xl font-bold uppercase tracking-wide text-white">Оставьте заявку</h3>
      <p class="mt-1 text-sm text-steel-400">Перезвоним за 15 минут, рассчитаем стоимость и запишем на удобное время.</p>

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

      <input type="hidden" name="source" value="/kontakty">

      <div id="lead-status" role="status" aria-live="polite" class="mt-3 empty:hidden"></div>

      <button type="submit" class="btn-primary mt-4 w-full justify-center disabled:opacity-60">Отправить заявку</button>

      <p class="mt-3 text-center text-[11px] leading-relaxed text-steel-600">Нажимая «Отправить», вы соглашаетесь на обработку персональных данных.</p>
    </form>
  </div>
</section>

<?php /* ── Карта ────────────────────────────────────────────────────── */ ?>
<section id="lokaciya" class="container-x mt-16 reveal">
  <?= section_heading([
      'title'    => 'Как нас найти',
      'subtitle' => ($co['address']['district'] ?? '') . ' — ' . $fullAddress . '.',
  ]) ?>
  <div class="mt-8 glass overflow-hidden rounded-2xl reveal">
    <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_1.2fr] lg:items-center">
      <div>
        <span class="eyebrow">Адрес</span>
        <p class="mt-3 text-lg font-semibold text-white"><?= e($co['address']['street']) ?></p>
        <p class="mt-1 text-sm text-steel-400"><?= !empty($co['address']['district']) ? e($co['address']['district']) . ', ' : '' ?><?= e($city) ?>, <?= e($co['address']['postalCode']) ?>, <?= e($co['address']['countryName']) ?></p>
        <div class="mt-5 flex flex-wrap gap-3">
          <a href="<?= e($yandexUrl) ?>" target="_blank" rel="noopener" class="btn-primary">Открыть в Яндекс.Картах</a>
          <a href="<?= e($googleUrl) ?>" target="_blank" rel="noopener" class="btn-ghost">Открыть в Google Картах</a>
        </div>
        <?php if (!empty($co['areasServed'])): ?>
          <p class="mt-4 text-xs leading-relaxed text-steel-500">Обслуживаем <?= e($city) ?> и пригород: <?= e(implode(', ', array_slice($co['areasServed'], 1))) ?>.</p>
        <?php endif; ?>
      </div>
      <div class="overflow-hidden rounded-xl border border-white/12">
        <?php if ($osmEmbed): ?>
          <iframe title="Карта: <?= e($fullAddress) ?>" src="<?= e($osmEmbed) ?>" loading="lazy" class="h-[300px] w-full [color-scheme:dark]" style="border:0;filter:invert(0.92) hue-rotate(180deg) grayscale(0.35) contrast(1.05)"></iframe>
        <?php else: ?>
          <div class="grid min-h-[200px] place-items-center bg-white/[0.03] p-6 text-center"><p class="text-sm text-steel-400"><?= e($fullAddress) ?></p></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php /* ── Способы записи ──────────────────────────────────────────── */ ?>
<section class="container-x mt-20 reveal">
  <?= section_heading([
      'eyebrow'  => 'Запись',
      'title'    => 'Как записаться на ремонт',
      'subtitle' => 'Выберите удобный способ — ответим быстро и подберём время.',
  ]) ?>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach ($co['bookingMethods'] as $m): ?>
      <div class="card-3d p-6 reveal">
        <div class="grid h-12 w-12 place-items-center rounded-xl border border-neon-500/25 bg-neon-500/10 text-white" aria-hidden="true"><?= ui_icon($m['icon'], 'h-6 w-6') ?></div>
        <h3 class="mt-4 font-display text-lg font-bold uppercase tracking-wide text-white"><?= e($m['title']) ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-steel-400"><?= e($m['text']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?= cta_band() ?>
