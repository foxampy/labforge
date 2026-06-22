<?php
/** <head> содержимое. Использует $meta, $allJsonLd. */
$c = company();
$title = $meta['title'] ?? $c['name'];
if (!empty($meta['titleTemplate'])) {
    $title .= ' | ' . $c['name'];
}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#050505">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($meta['description'] ?? $c['description']) ?>">
<?php if (!empty($meta['keywords'])): ?>
<meta name="keywords" content="<?= e(is_array($meta['keywords']) ? implode(', ', $meta['keywords']) : $meta['keywords']) ?>">
<?php endif; ?>
<?php if (!empty($meta['noindex'])): ?>
<meta name="robots" content="noindex, follow">
<?php endif; ?>
<link rel="canonical" href="<?= e($meta['canonical'] ?? site_url()) ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($meta['siteName'] ?? $c['name']) ?>">
<meta property="og:title" content="<?= e($meta['ogTitle'] ?? $title) ?>">
<meta property="og:description" content="<?= e($meta['ogDescription'] ?? ($c['description'])) ?>">
<meta property="og:url" content="<?= e($meta['ogUrl'] ?? site_url()) ?>">
<meta property="og:locale" content="<?= e($meta['locale'] ?? 'ru_BY') ?>">
<meta property="og:image" content="<?= e($meta['ogImage'] ?? abs_url('/assets/img/og.jpg')) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($meta['ogTitle'] ?? $title) ?>">
<meta name="twitter:description" content="<?= e($meta['ogDescription'] ?? $c['description']) ?>">

<!-- Иконки -->
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" href="/assets/img/apple-touch-icon.png" sizes="180x180">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">

<!-- Шрифты: Inter (текст), Oswald (дисплей), Anton (вордмарк) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&family=Anton&display=swap" rel="stylesheet">
<style>
  :root{
    --font-sans:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
    --font-display:"Oswald",var(--font-sans);
    --font-wordmark:"Anton",var(--font-display);
  }
</style>

<!-- Стили (собранный Tailwind, статический файл) -->
<link rel="stylesheet" href="<?= asset('css/styles.css') ?>">

<!-- JSON-LD -->
<?= json_ld_tags($allJsonLd) ?>
