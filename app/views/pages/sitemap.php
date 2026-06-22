<?php
/**
 * Динамическая карта сайта (XML-тело). Content-Type выставляет роутер.
 * ВАЖНО: .php-файл не может начинаться с литерального <?xml — поэтому
 * декларацию выводим через echo. <loc> строим через abs_url(), экранируем e().
 */
declare(strict_types=1);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

/** Список URL карты: путь, changefreq, priority. */
$urls = [
    ['path' => '/',          'freq' => 'weekly',  'pri' => '1.0'],
    ['path' => '/uslugi',    'freq' => 'weekly',  'pri' => '0.9'],
    ['path' => '/marki',     'freq' => 'weekly',  'pri' => '0.8'],
    ['path' => '/o-nas',     'freq' => 'monthly', 'pri' => '0.5'],
    ['path' => '/kontakty',  'freq' => 'monthly', 'pri' => '0.6'],
];

// Деталь каждой услуги.
foreach (services() as $s) {
    if (empty($s['slug'])) {
        continue;
    }
    $urls[] = ['path' => '/uslugi/' . $s['slug'], 'freq' => 'monthly', 'pri' => '0.7'];
}

// Деталь каждой марки.
foreach (brands() as $b) {
    if (empty($b['slug'])) {
        continue;
    }
    $urls[] = ['path' => '/marki/' . $b['slug'], 'freq' => 'monthly', 'pri' => '0.6'];
}
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u): ?>
  <url>
    <loc><?= e(abs_url($u['path'])) ?></loc>
    <changefreq><?= e($u['freq']) ?></changefreq>
    <priority><?= e($u['pri']) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
