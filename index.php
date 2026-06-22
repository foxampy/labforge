<?php
declare(strict_types=1);

/* ================================================================== *
 *  LEVADA SERVICE — фронт-контроллер (чистый PHP, без фреймворков).    *
 *  Все «красивые» URL переписываются на этот файл через .htaccess.    *
 * ================================================================== */

require __DIR__ . '/app/lib/helpers.php';
require __DIR__ . '/app/lib/data.php';
require __DIR__ . '/app/lib/seo.php';
require __DIR__ . '/app/lib/icons.php';
require __DIR__ . '/app/lib/ui.php';

$path = current_path();
$c    = company();

/** 404. */
function send_404(): void
{
    http_response_code(404);
    $meta = page_meta([
        'title'         => 'Страница не найдена',
        'description'   => 'Запрашиваемая страница не найдена. Вернитесь на главную LEVADA SERVICE.',
        'path'          => $_SERVER['REQUEST_URI'] ?? '/',
        'noindex'       => true,
    ]);
    echo render_page('pages/404', [], $meta);
    exit;
}

// ── sitemap.xml (динамически) ───────────────────────────────────────
if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    echo render('pages/sitemap', []);
    exit;
}

// ── Маршруты ────────────────────────────────────────────────────────
switch (true) {

    // Главная
    case $path === '/':
        $meta = page_meta([
            'title'         => $c['tagline'],
            'titleTemplate' => false,
            'description'   => $c['description'],
            'path'          => '/',
        ]);
        $homeFaq = [
            ['q' => 'Сколько стоит ремонт?', 'a' => 'Стоимость зависит от модели авто и характера неисправности. Мы делаем точный расчёт после диагностики и согласуем смету до начала работ — без навязанных услуг.'],
            ['q' => 'Даёте ли гарантию?', 'a' => 'Да, на все работы и установленные запчасти предоставляется письменная гарантия — в зависимости от вида работ до 2 лет.'],
            ['q' => 'С какими марками вы работаете?', 'a' => 'Обслуживаем большинство европейских, азиатских и китайских марок: Volkswagen, Audi, BMW, Mercedes-Benz, Toyota, Kia, Hyundai, Geely и другие.'],
            ['q' => 'Нужно ли записываться заранее?', 'a' => 'Желательно — так мы подготовим запчасти и примем вас без ожидания. Записаться можно по телефону, через форму на сайте или в Telegram.'],
        ];
        echo render_page('pages/home', ['homeFaq' => $homeFaq], $meta, [faq_jsonld($homeFaq)]);
        break;

    // Список услуг
    case $path === '/uslugi':
        $meta = page_meta([
            'title'       => 'Услуги автосервиса и цены',
            'description' => 'Полный спектр работ по ремонту и обслуживанию авто в Минске: диагностика, ТО, ремонт ходовой, тормоза, чип-тюнинг, русификация, антикор. Прайс и запись онлайн.',
            'path'        => '/uslugi',
        ]);
        $crumbs = [['name' => 'Главная', 'path' => '/'], ['name' => 'Услуги', 'path' => '/uslugi']];
        echo render_page('pages/uslugi-index', ['crumbs' => $crumbs], $meta, [breadcrumb_jsonld($crumbs)]);
        break;

    // Деталь услуги
    case preg_match('#^/uslugi/([a-z0-9\-]+)$#', $path, $m) === 1:
        $s = find_service($m[1]);
        if (!$s) {
            send_404();
        }
        $svcPath = '/uslugi/' . $s['slug'];
        $meta = page_meta([
            'title'       => $s['metaTitle'] ?? $s['title'],
            'description' => $s['metaDescription'] ?? $s['excerpt'],
            'path'        => $svcPath,
            'keywords'    => $s['keywords'] ?? null,
        ]);
        $crumbs = [
            ['name' => 'Главная', 'path' => '/'],
            ['name' => 'Услуги', 'path' => '/uslugi'],
            ['name' => $s['title'], 'path' => $svcPath],
        ];
        $jsonld = [
            service_jsonld(['title' => $s['title'], 'description' => $s['metaDescription'] ?? $s['excerpt'], 'path' => $svcPath]),
            breadcrumb_jsonld($crumbs),
        ];
        if (!empty($s['faq'])) {
            $jsonld[] = faq_jsonld($s['faq']);
        }
        echo render_page('pages/uslugi-detail', ['s' => $s, 'crumbs' => $crumbs], $meta, $jsonld);
        break;

    // Список марок
    case $path === '/marki':
        $meta = page_meta([
            'title'       => 'Марки авто — ремонт и обслуживание',
            'description' => 'Обслуживаем большинство марок: китайские авто, электромобили, европейские и азиатские бренды. Дилерское диагностическое оборудование и гарантия.',
            'path'        => '/marki',
        ]);
        $crumbs = [['name' => 'Главная', 'path' => '/'], ['name' => 'Марки авто', 'path' => '/marki']];
        echo render_page('pages/marki-index', ['crumbs' => $crumbs], $meta, [breadcrumb_jsonld($crumbs)]);
        break;

    // Деталь марки
    case preg_match('#^/marki/([a-z0-9\-]+)$#', $path, $m) === 1:
        $b = find_brand($m[1]);
        if (!$b) {
            send_404();
        }
        $brandPath = '/marki/' . $b['slug'];
        $meta = page_meta([
            'title'       => 'Ремонт и обслуживание ' . $b['name'],
            'description' => $b['excerpt'] ?? ('Профессиональный ремонт ' . $b['name'] . ' в Минске: диагностика, ТО, ходовая, гарантия.'),
            'path'        => $brandPath,
            'keywords'    => $b['keywords'] ?? null,
        ]);
        $crumbs = [
            ['name' => 'Главная', 'path' => '/'],
            ['name' => 'Марки авто', 'path' => '/marki'],
            ['name' => $b['name'], 'path' => $brandPath],
        ];
        echo render_page('pages/marki-detail', ['b' => $b, 'crumbs' => $crumbs], $meta, [breadcrumb_jsonld($crumbs)]);
        break;

    // О нас
    case $path === '/o-nas':
        $meta = page_meta([
            'title'       => 'О нас',
            'description' => 'LEVADA SERVICE — автосервис в Минске с 2014 года. Специализация на китайских авто и электромобилях. Оборудование, гарантии и честные цены.',
            'path'        => '/o-nas',
        ]);
        $crumbs = [['name' => 'Главная', 'path' => '/'], ['name' => 'О нас', 'path' => '/o-nas']];
        echo render_page('pages/o-nas', ['crumbs' => $crumbs], $meta, [breadcrumb_jsonld($crumbs), faq_jsonld($c['aboutFaq'])]);
        break;

    // Контакты
    case $path === '/kontakty':
        $meta = page_meta([
            'title'       => 'Контакты',
            'description' => 'Адрес, телефон и режим работы автосервиса LEVADA SERVICE в Минске. Запись по телефону, в Telegram или через форму на сайте.',
            'path'        => '/kontakty',
        ]);
        $crumbs = [['name' => 'Главная', 'path' => '/'], ['name' => 'Контакты', 'path' => '/kontakty']];
        echo render_page('pages/kontakty', ['crumbs' => $crumbs], $meta, [breadcrumb_jsonld($crumbs)]);
        break;

    default:
        send_404();
}
