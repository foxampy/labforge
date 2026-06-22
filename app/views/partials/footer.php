<?php
/** Подвал сайта. */
$c = company();
$year = date('Y');
?>
<footer class="mt-24 border-t border-white/10 bg-ink-950">
  <div class="container-x grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">

    <div class="sm:col-span-2 lg:col-span-1">
      <a href="/" class="inline-flex items-center gap-2.5">
        <img src="/assets/img/cat.png" alt="LEVADA SERVICE" class="h-9 w-9 object-contain" decoding="async">
        <span class="font-display text-lg font-bold uppercase leading-none tracking-[0.06em] text-white">LEV<span class="text-signal-500">A</span>D<span class="text-signal-500">A</span><span class="ml-2 text-white/85">SERVICE</span></span>
      </a>
      <p class="mt-4 max-w-xs text-sm leading-relaxed text-steel-400"><?= e($c['tagline']) ?></p>
      <div class="mt-5 flex gap-3">
        <a href="<?= e($c['telegram']) ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-lg border border-white/12 bg-white/5 text-steel-200 transition-colors hover:border-neon-500/60 hover:text-white" aria-label="Telegram">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M21.94 4.3a1 1 0 0 0-1.05-.15L3.4 11.2c-.86.35-.82 1.6.06 1.9l4.3 1.43 1.66 5.27a1 1 0 0 0 1.62.43l2.45-2.2 4.3 3.16a1 1 0 0 0 1.57-.62l3-13.5a1 1 0 0 0-.42-1.02ZM9.7 14.1l8.2-5.6-6.5 6.9-.2 2.9-1.5-4.2Z"/></svg>
        </a>
        <a href="<?= e($c['whatsapp']) ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-lg border border-white/12 bg-white/5 text-steel-200 transition-colors hover:border-neon-500/60 hover:text-white" aria-label="WhatsApp">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-6c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.2 0-.4 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3c-.2.3-.9.9-.9 2.2s.9 2.5 1 2.7c.2.2 1.8 2.8 4.4 3.9 1.6.7 2.2.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.2-.5-.3Z"/></svg>
        </a>
      </div>
    </div>

    <nav aria-label="Услуги">
      <p class="mb-4 text-xs font-bold uppercase tracking-wider text-steel-400">Услуги</p>
      <ul class="space-y-2 text-sm">
        <?php foreach (pillar_services() as $s): ?>
          <li><a href="/uslugi/<?= e($s['slug']) ?>" class="link-muted"><?= e($s['title']) ?></a></li>
        <?php endforeach; ?>
        <li><a href="/uslugi" class="link-muted font-semibold text-steel-200">Все услуги &rarr;</a></li>
      </ul>
    </nav>

    <nav aria-label="Разделы">
      <p class="mb-4 text-xs font-bold uppercase tracking-wider text-steel-400">Разделы</p>
      <ul class="space-y-2 text-sm">
        <li><a href="/marki" class="link-muted">Марки авто</a></li>
        <li><a href="/o-nas" class="link-muted">О нас</a></li>
        <li><a href="/kontakty" class="link-muted">Контакты</a></li>
        <li><a href="/#zayavka" class="link-muted">Оставить заявку</a></li>
      </ul>
    </nav>

    <div>
      <p class="mb-4 text-xs font-bold uppercase tracking-wider text-steel-400">Контакты</p>
      <address class="space-y-2 text-sm not-italic text-steel-300">
        <p><a href="<?= e($c['phoneHref']) ?>" class="link-muted"><?= e($c['phone']) ?></a></p>
        <p><a href="mailto:<?= e($c['email']) ?>" class="link-muted"><?= e($c['email']) ?></a></p>
        <p><?= e($c['address']['street']) ?>, <?= e($c['address']['city']) ?></p>
      </address>
      <p class="mb-2 mt-5 text-xs font-bold uppercase tracking-wider text-steel-400">Режим работы</p>
      <ul class="space-y-1 text-sm text-steel-400">
        <?php foreach ($c['openingHours'] as $oh): ?>
          <li class="flex justify-between gap-4"><span><?= e($oh['days']) ?></span><span class="text-steel-300"><?= e($oh['time']) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <div class="border-t border-white/10">
    <div class="container-x flex flex-col items-center justify-between gap-2 py-5 text-xs text-steel-500 sm:flex-row">
      <p>&copy; <?= e($year) ?> <?= e($c['legalName']) ?>. Все права защищены.</p>
      <p>Автосервис в <?= e($c['address']['city']) ?> &middot; ремонт всех марок авто</p>
    </div>
  </div>
</footer>
