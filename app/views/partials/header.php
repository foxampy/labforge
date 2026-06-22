<?php
/** Шапка сайта: лого, десктоп-навигация с мега-меню, поиск, телефон, бургер. */
$c   = company();
$nav = main_nav();
$svcGroups   = service_groups();
$brandGroups = brand_groups();

/** Вордмарк LEVADA SERVICE (монохром: «A» через text-signal-500 = белый). */
function wordmark(string $size = 'text-lg'): string
{
    return '<span class="font-display font-bold uppercase leading-none tracking-[0.06em] text-white ' . $size . '">'
        . 'LEV<span class="text-signal-500">A</span>D<span class="text-signal-500">A</span>'
        . '<span class="ml-2 text-white/85">SERVICE</span></span>';
}
?>
<style>
  /* Шапка: прозрачная сверху (градиент-подложка), матовая при скролле. */
  #site-header{background:linear-gradient(to bottom,rgba(5,5,5,.6),transparent);}
  #site-header.is-scrolled{
    background:rgba(5,5,5,.72);
    border-bottom-color:rgba(255,255,255,.10);
    -webkit-backdrop-filter:blur(24px);backdrop-filter:blur(24px);
  }
  /* Анимированный бургер: три полоски -> крест по aria-expanded. */
  #menu-btn .bar{transition:transform .25s ease,opacity .2s ease;transform-origin:center;}
  #menu-btn[aria-expanded="true"] .bar-1{transform:translateY(6px) rotate(45deg);}
  #menu-btn[aria-expanded="true"] .bar-2{opacity:0;}
  #menu-btn[aria-expanded="true"] .bar-3{transform:translateY(-6px) rotate(-45deg);}
  /* Подчёркивание пунктов десктоп-навигации (как after: в оригинале). */
  .nav-link{position:relative;}
  .nav-link::after{content:"";position:absolute;left:.75rem;right:.75rem;bottom:.125rem;height:2px;background:#fff;transform:scaleX(0);transform-origin:left;transition:transform .25s ease;}
  .nav-link:hover::after,.nav-link.is-current::after{transform:scaleX(1);}
</style>
<header id="site-header" class="fixed inset-x-0 top-0 z-50 border-b border-transparent transition-all duration-300">
  <div class="container-x flex h-20 items-center justify-between gap-4">

    <!-- Лого -->
    <a href="/" aria-label="LEVADA SERVICE — на главную" class="group inline-flex items-center gap-2.5">
      <span class="relative grid place-items-center">
        <span class="absolute inset-0 -z-10 rounded-full bg-neon-500/0 blur-md transition-all duration-300 group-hover:bg-neon-500/30"></span>
        <img src="/assets/img/cat.png" alt="Эмблема LEVADA SERVICE — кот" class="h-9 w-9 select-none object-contain" draggable="false" decoding="async">
      </span>
      <?= wordmark('text-lg') ?>
    </a>

    <!-- Десктоп-навигация -->
    <nav class="hidden items-center gap-1 lg:flex" aria-label="Основная навигация">
      <?php foreach ($nav as $item): ?>
        <?php if (!empty($item['mega'])): ?>
          <div class="group relative">
            <a href="<?= e($item['href']) ?>" class="nav-link inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-semibold uppercase tracking-wide transition-colors hover:text-white <?= is_active($item['href']) ? 'is-current text-white' : 'text-steel-200' ?>">
              <?= e($item['label']) ?>
              <svg class="h-3.5 w-3.5 transition-transform group-hover:rotate-180" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 4.5L6 7.5L9 4.5"/></svg>
            </a>
            <!-- Мега-меню -->
            <div class="invisible absolute left-1/2 top-full z-50 w-[min(92vw,56rem)] -translate-x-1/2 translate-y-2 pt-3 opacity-0 transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 focus-within:visible focus-within:translate-y-0 focus-within:opacity-100">
              <div class="rounded-2xl border border-white/10 bg-ink-900/95 p-6 shadow-card backdrop-blur-xl">
                <?php if ($item['mega'] === 'services'): ?>
                  <div class="grid gap-6 sm:grid-cols-3">
                    <?php foreach ($svcGroups as $g): ?>
                      <div>
                        <p class="mb-3 text-xs font-bold uppercase tracking-wider text-steel-400"><?= e($g['title']) ?></p>
                        <ul class="space-y-1">
                          <?php foreach ($g['items'] as $it): ?>
                            <li>
                              <a href="/uslugi/<?= e($it['slug']) ?>" class="flex items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm text-steel-200 transition-colors hover:bg-white/5 hover:text-white">
                                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md border border-neon-500/20 bg-neon-500/10 text-white"><?= svc_icon($it['slug'], 'h-4 w-4') ?></span>
                                <?= e($it['title']) ?>
                              </a>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="mt-5 border-t border-white/10 pt-4">
                    <a href="/uslugi" class="inline-flex items-center gap-1 text-sm font-semibold text-neon-400 hover:text-white">Все услуги и прайс <span>&rarr;</span></a>
                  </div>
                <?php else: ?>
                  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <?php foreach ($brandGroups as $g): ?>
                      <div>
                        <p class="mb-3 text-xs font-bold uppercase tracking-wider text-steel-400"><?= e($g['title']) ?></p>
                        <ul class="space-y-1">
                          <?php foreach (array_slice($g['items'], 0, 8) as $it): ?>
                            <li><a href="/marki/<?= e($it['slug']) ?>" class="block rounded-lg px-2 py-1 text-sm text-steel-200 transition-colors hover:bg-white/5 hover:text-white"><?= e($it['name']) ?></a></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="mt-5 border-t border-white/10 pt-4">
                    <a href="/marki" class="inline-flex items-center gap-1 text-sm font-semibold text-neon-400 hover:text-white">Все марки авто <span>&rarr;</span></a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a href="<?= e($item['href']) ?>" class="nav-link rounded-lg px-3 py-2 text-sm font-semibold uppercase tracking-wide transition-colors hover:text-white <?= is_active($item['href']) ? 'is-current text-white' : 'text-steel-200' ?>"><?= e($item['label']) ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <!-- Правый блок: поиск, телефон, CTA, бургер -->
    <div class="flex items-center gap-2">
      <!-- Поиск (десктоп) -->
      <div class="relative hidden xl:block">
        <input id="site-search" type="search" autocomplete="off" placeholder="Поиск по сайту…"
               class="w-44 rounded-lg border border-white/12 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-steel-500 outline-none transition-all focus:w-56 focus:border-neon-500/60"
               aria-label="Поиск по сайту" role="combobox" aria-expanded="false" aria-controls="search-results">
        <ul id="search-results" class="absolute right-0 top-full z-50 mt-2 hidden max-h-80 w-72 overflow-y-auto rounded-xl border border-white/12 bg-ink-900/95 p-1 backdrop-blur-xl shadow-card" role="listbox"></ul>
      </div>

      <a href="<?= e($c['phoneHref']) ?>" class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-white transition-colors hover:text-neon-300 md:inline-flex">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span><?= e($c['phone']) ?></span>
      </a>

      <a href="/#zayavka" class="btn-primary hidden sm:inline-flex">Записаться</a>

      <!-- Бургер -->
      <button id="menu-btn" type="button" aria-label="Открыть меню" aria-controls="mobile-nav" aria-expanded="false"
              class="grid h-11 w-11 place-items-center rounded-lg border border-white/12 bg-white/5 text-white lg:hidden">
        <span class="sr-only">Меню</span>
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
          <line class="bar bar-1" x1="3" y1="6"  x2="21" y2="6"/>
          <line class="bar bar-2" x1="3" y1="12" x2="21" y2="12"/>
          <line class="bar bar-3" x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Мобильное меню -->
  <div id="mobile-nav" class="hidden max-h-[calc(100vh-5rem)] overflow-y-auto border-t border-white/10 bg-ink-950/95 backdrop-blur-2xl lg:hidden">
    <div class="container-x space-y-1 py-4">
      <div class="relative mb-3">
        <input id="site-search-m" type="search" autocomplete="off" placeholder="Поиск по сайту…"
               class="w-full rounded-lg border border-white/12 bg-white/5 px-3 py-2.5 text-sm text-white placeholder:text-steel-500 outline-none focus:border-neon-500/60" aria-label="Поиск по сайту">
        <ul id="search-results-m" class="mt-2 hidden max-h-72 overflow-y-auto rounded-xl border border-white/12 bg-ink-900/95 p-1"></ul>
      </div>
      <?php foreach ($nav as $item): ?>
        <a href="<?= e($item['href']) ?>" class="block rounded-lg px-3 py-2.5 text-base font-semibold uppercase tracking-wide transition-colors hover:bg-white/5 <?= is_active($item['href']) ? 'text-neon-300' : 'text-steel-100' ?>"><?= e($item['label']) ?></a>
      <?php endforeach; ?>
      <div class="flex flex-col gap-2 pt-3">
        <a href="<?= e($c['phoneHref']) ?>" class="btn-ghost w-full"><?= e($c['phone']) ?></a>
        <a href="/#zayavka" class="btn-primary w-full">Записаться</a>
      </div>
    </div>
  </div>
</header>
<!-- Спейсер под фиксированную шапку убран: секции используют свои отступы (pt-28). -->
