<?php
declare(strict_types=1);

/* ------------------------------------------------------------------ *
 *  UI-компоненты как функции — порт src/components/ui.tsx.            *
 *  Возвращают HTML-строку. Классы Tailwind 1:1 с оригиналом.          *
 * ------------------------------------------------------------------ */

/** Хлебные крошки. $items: список [name, href]. */
function breadcrumbs(array $items): string
{
    $n = count($items);
    $li = '';
    foreach (array_values($items) as $i => $it) {
        $sep = $i > 0 ? '<span class="text-steel-600">/</span>' : '';
        if ($i < $n - 1) {
            $inner = '<a href="' . e($it['href']) . '" class="hover:text-white">' . e($it['name']) . '</a>';
        } else {
            $inner = '<span class="text-steel-200">' . e($it['name']) . '</span>';
        }
        $li .= '<li class="flex items-center gap-1.5">' . $sep . $inner . '</li>';
    }
    return '<nav aria-label="Хлебные крошки" class="container-x pt-28 sm:pt-32">'
        . '<ol class="flex flex-wrap items-center gap-1.5 text-sm text-steel-400">' . $li . '</ol>'
        . '</nav>';
}

/** Заголовок секции. $o: eyebrow, title, subtitle, center(bool). */
function section_heading(array $o): string
{
    $wrap = !empty($o['center']) ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl';
    $html = '<div class="' . $wrap . '">';
    if (!empty($o['eyebrow'])) {
        $html .= '<span class="eyebrow">' . e($o['eyebrow']) . '</span>';
    }
    $html .= '<h2 class="mt-4 font-display text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl">'
        . e($o['title']) . '</h2>';
    if (!empty($o['subtitle'])) {
        $html .= '<p class="mt-3 text-base leading-relaxed text-steel-300">' . e($o['subtitle']) . '</p>';
    }
    return $html . '</div>';
}

/** Финальный CTA-блок. */
function cta_band(): string
{
    return <<<HTML
<section class="container-x my-20">
  <div class="glass relative overflow-hidden rounded-3xl px-6 py-12 text-center sm:px-12">
    <div class="pointer-events-none absolute inset-0 bg-mesh opacity-60"></div>
    <div class="relative">
      <h2 class="font-display text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl">Нужен ремонт или диагностика?</h2>
      <p class="mx-auto mt-3 max-w-xl text-steel-300">Оставьте заявку — перезвоним за 15 минут, рассчитаем стоимость и запишем на удобное время.</p>
      <div class="mt-7 flex flex-wrap justify-center gap-3">
        <a href="/#zayavka" class="btn-primary text-base">Оставить заявку</a>
        <a href="/kontakty" class="btn-ghost text-base">Как нас найти</a>
      </div>
    </div>
  </div>
</section>
HTML;
}

/** Карточка услуги. $s: slug, title, icon, excerpt. */
function service_card(array $s): string
{
    $icon = svc_icon($s['slug'], 'h-7 w-7');
    return '<a href="/uslugi/' . e($s['slug']) . '" class="card-3d group block p-6">'
        . '<div class="grid h-12 w-12 place-items-center rounded-xl border border-neon-500/25 bg-neon-500/10 text-white transition-colors group-hover:border-neon-500/60 group-hover:bg-neon-500/20">'
        . $icon
        . '</div>'
        . '<h3 class="mt-4 font-display text-lg font-bold uppercase tracking-wide text-white group-hover:text-neon-300">' . e($s['title']) . '</h3>'
        . '<p class="mt-2 text-sm leading-relaxed text-steel-400">' . e($s['excerpt'] ?? '') . '</p>'
        . '<span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-neon-400">Подробнее'
        . '<span class="transition-transform group-hover:translate-x-1">&rarr;</span></span>'
        . '</a>';
}

/** Карточка марки. $b: slug, name, country. Логотип монохромный, в цвете на ховере. */
function brand_card(array $b): string
{
    $emblem = brand_logo_img($b, 'h-9 w-9', 'text-xl');
    return '<a href="/marki/' . e($b['slug']) . '" class="card-3d group flex flex-col items-center p-5 text-center">'
        . '<div class="grid h-14 w-14 place-items-center rounded-2xl border border-white/10 bg-gradient-to-br from-white/10 to-white/0 transition-colors group-hover:border-neon-500/50">'
        . $emblem . '</div>'
        . '<h3 class="mt-3 font-bold text-white group-hover:text-neon-300">' . e($b['name']) . '</h3>'
        . '<p class="text-xs text-steel-500">' . e($b['country'] ?? '') . '</p>'
        . '</a>';
}
