<?php
declare(strict_types=1);

/* ------------------------------------------------------------------ *
 *  Доступ к данным (app/data/*.php) + навигация (порт src/data/nav.ts) *
 *  Все загрузчики кешируются статически.                              *
 * ------------------------------------------------------------------ */

function company(): array
{
    static $d = null;
    return $d ??= require dirname(__DIR__) . '/data/company.php';
}

function services(): array
{
    static $d = null;
    return $d ??= require dirname(__DIR__) . '/data/services.php';
}

function brands(): array
{
    static $d = null;
    return $d ??= require dirname(__DIR__) . '/data/brands.php';
}

function service_map(): array
{
    static $d = null;
    return $d ??= require dirname(__DIR__) . '/data/service_map.php';
}

/** Услуга по slug или null. */
function find_service(string $slug): ?array
{
    foreach (services() as $s) {
        if (($s['slug'] ?? null) === $slug) {
            return $s;
        }
    }
    return null;
}

/** Марка по slug или null. */
function find_brand(string $slug): ?array
{
    foreach (brands() as $b) {
        if (($b['slug'] ?? null) === $slug) {
            return $b;
        }
    }
    return null;
}

/** Услуги-«столпы» (pillar=true) — для перелинковки. */
function pillar_services(): array
{
    return array_values(array_filter(services(), static fn ($s) => !empty($s['pillar'])));
}

/** Группировка услуг для мега-меню (порт serviceGroups). */
function service_groups(): array
{
    $groups = [
        ['title' => 'Диагностика и ТО', 'slugs' => ['diagnostika', 'to-zamena-masla', 'razval-shozhdenie', 'ozonirovanie']],
        ['title' => 'Ремонт и ходовая', 'slugs' => ['remont-podveski', 'tormoznaya-sistema', 'rulevoe-upravlenie', 'remont-glushitelya']],
        ['title' => 'Электроника и защита', 'slugs' => ['chip-tyuning', 'rusifikaciya', 'antikor']],
    ];
    $out = [];
    foreach ($groups as $g) {
        $items = [];
        foreach ($g['slugs'] as $slug) {
            $s = find_service($slug);
            if ($s) {
                $items[] = ['slug' => $s['slug'], 'title' => $s['title'], 'icon' => $s['icon'] ?? ''];
            }
        }
        $out[] = ['title' => $g['title'], 'items' => $items];
    }
    return $out;
}

/** Группировка марок для мега-меню (порт brandGroups). */
function brand_groups(): array
{
    $groups = [
        ['title' => 'Китайские авто', 'group' => 'china'],
        ['title' => 'Электромобили', 'group' => 'ev'],
        ['title' => 'Европейские', 'group' => 'euro'],
        ['title' => 'Азиатские', 'group' => 'asia'],
    ];
    $out = [];
    foreach ($groups as $g) {
        $items = [];
        foreach (brands() as $b) {
            if (($b['group'] ?? null) === $g['group']) {
                $items[] = ['slug' => $b['slug'], 'name' => $b['name']];
            }
        }
        $out[] = ['title' => $g['title'], 'items' => $items];
    }
    return $out;
}

/** Главная навигация (порт mainNav). */
function main_nav(): array
{
    return [
        ['href' => '/', 'label' => 'Главная'],
        ['href' => '/uslugi', 'label' => 'Услуги', 'mega' => 'services'],
        ['href' => '/marki', 'label' => 'Марки авто', 'mega' => 'brands'],
        ['href' => '/o-nas', 'label' => 'О нас'],
        ['href' => '/kontakty', 'label' => 'Контакты'],
    ];
}

/** Плоский индекс для поиска по сайту (порт searchIndex). */
function search_index(): array
{
    $idx = [
        ['title' => 'Главная', 'href' => '/', 'type' => 'Страница', 'keywords' => 'автосервис минск'],
        ['title' => 'Все услуги', 'href' => '/uslugi', 'type' => 'Страница', 'keywords' => 'услуги работы прайс'],
        ['title' => 'Все марки авто', 'href' => '/marki', 'type' => 'Страница', 'keywords' => 'марки бренды авто'],
        ['title' => 'О нас', 'href' => '/o-nas', 'type' => 'Страница', 'keywords' => 'о компании гарантия'],
        ['title' => 'Контакты', 'href' => '/kontakty', 'type' => 'Страница', 'keywords' => 'адрес телефон карта запись'],
    ];
    foreach (services() as $s) {
        $idx[] = [
            'title'    => $s['title'],
            'href'     => '/uslugi/' . $s['slug'],
            'type'     => 'Услуга',
            'keywords' => implode(' ', $s['keywords'] ?? []),
        ];
    }
    foreach (brands() as $b) {
        $idx[] = [
            'title'    => 'Ремонт ' . $b['name'],
            'href'     => '/marki/' . $b['slug'],
            'type'     => 'Марка',
            'keywords' => $b['name'] . ' ' . implode(' ', $b['keywords'] ?? []) . ' ' . implode(' ', $b['popularModels'] ?? []),
        ];
    }
    return $idx;
}
