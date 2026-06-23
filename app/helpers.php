<?php

declare(strict_types=1);

use App\Support\Icons;
use Illuminate\Support\HtmlString;

/*
|------------------------------------------------------------------------------
| Глобальные хелперы для Blade — тонкие обёртки над App\Support\*.
| Подключаются через composer.json → autoload.files.
|------------------------------------------------------------------------------
*/

if (! function_exists('lv')) {
    /** Значение из config/levada.php: lv('phone'), lv('address.city'). */
    function lv(string $key, mixed $default = null): mixed
    {
        return config('levada.' . $key, $default);
    }
}

if (! function_exists('svc_icon')) {
    /** SVG-иконка услуги по slug. */
    function svc_icon(string $slug, string $class = 'h-7 w-7'): HtmlString
    {
        return Icons::service($slug, $class);
    }
}

if (! function_exists('ui_icon')) {
    /** UI-иконка: phone|telegram|form|location|clock|check. */
    function ui_icon(string $name, string $class = 'h-6 w-6'): HtmlString
    {
        return Icons::ui($name, $class);
    }
}

if (! function_exists('brand_logo_img')) {
    /** Эмблема марки: <img> логотип или монограмма-фолбэк. */
    function brand_logo_img(array $b, string $imgClass = 'h-9 w-9', string $monoClass = 'text-xl'): HtmlString
    {
        return Icons::brandLogoImg($b, $imgClass, $monoClass);
    }
}

if (! function_exists('json_ld')) {
    /** Рендер одного или нескольких JSON-LD блоков в <script>. null-блоки пропускаются. */
    function json_ld(?array ...$blocks): HtmlString
    {
        $out = '';
        foreach ($blocks as $block) {
            if ($block === null || $block === []) {
                continue;
            }
            $json = json_encode(
                $block,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
            $out .= '<script type="application/ld+json">' . $json . '</script>' . "\n";
        }

        return new HtmlString($out);
    }
}
