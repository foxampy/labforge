<?php
/** Общий каркас страницы. Получает: $content (HTML), $meta (array), $jsonld (array). */
$meta   = $meta ?? page_meta(['title' => company()['name']]);
$jsonld = $jsonld ?? [];
// Сайт-уровневый JSON-LD (бизнес) присутствует на всех страницах.
$allJsonLd = array_merge([auto_repair_jsonld()], $jsonld);
?>
<!doctype html>
<html lang="ru">
<head>
<?php include __DIR__ . '/partials/head.php'; ?>
</head>
<body class="font-sans antialiased">
<?php include __DIR__ . '/partials/header.php'; ?>
<main id="main"><?= $content ?></main>
<?php include __DIR__ . '/partials/footer.php'; ?>
<?php include __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
