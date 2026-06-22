<?php
declare(strict_types=1);

/* ------------------------------------------------------------------ *
 *  Базовые помощники: экранирование, URL, рендер вью, JSON-LD.        *
 *  Никаких зависимостей — чистый PHP.                                 *
 * ------------------------------------------------------------------ */

/** HTML-экранирование значения (для вывода в разметку). */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Базовый URL сайта без завершающего слэша. */
function site_url(): string
{
    static $u = null;
    if ($u !== null) {
        return $u;
    }
    $c = company();
    $u = rtrim($c['SITE_URL'] ?? ($c['url'] ?? 'https://livada-center.by'), '/');
    return $u;
}

/** Абсолютный URL из относительного пути. */
function abs_url(string $path = '/'): string
{
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return site_url() . '/' . ltrim($path, '/');
}

/** URL ассета: /assets/<path>. */
function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

/** Текущий путь запроса, нормализованный (без хвостового слэша, кроме корня). */
function current_path(): string
{
    $p = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $p = is_string($p) ? rawurldecode($p) : '/';
    $p = '/' . trim($p, '/');
    return $p === '' ? '/' : $p;
}

/** Активна ли ссылка для текущего пути (точное совпадение или вложенный раздел). */
function is_active(string $href): bool
{
    $cur = current_path();
    if ($href === '/') {
        return $cur === '/';
    }
    return $cur === $href || str_starts_with($cur, $href . '/');
}

/**
 * Рендерит вью-файл из app/views/<view>.php с переданными переменными,
 * возвращает HTML строкой.
 */
function render(string $view, array $data = []): string
{
    $__file = dirname(__DIR__) . '/views/' . $view . '.php';
    if (!is_file($__file)) {
        return "<!-- view not found: {$view} -->";
    }
    extract($data, EXTR_SKIP);
    ob_start();
    include $__file;
    return (string) ob_get_clean();
}

/**
 * Полная страница: оборачивает тело вью в общий layout.
 *  $view   — имя вью тела (например, 'pages/home')
 *  $data   — данные для тела
 *  $meta   — массив SEO-метаданных (см. page_meta())
 *  $jsonld — список JSON-LD объектов (массивов) для <head>
 */
function render_page(string $view, array $data, array $meta, array $jsonld = []): string
{
    $content = render($view, $data);
    return render('layout', [
        'content' => $content,
        'meta'    => $meta,
        'jsonld'  => $jsonld,
    ]);
}

/** Один или несколько <script type="application/ld+json"> из массива объектов. */
function json_ld_tags(array $items): string
{
    $out = '';
    foreach ($items as $obj) {
        if (!$obj) {
            continue;
        }
        $json = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $out .= '<script type="application/ld+json">' . $json . "</script>\n";
    }
    return $out;
}

/** Безопасный доступ к вложенному ключу массива. */
function arr(array $a, string $key, $default = '')
{
    return $a[$key] ?? $default;
}
