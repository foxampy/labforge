<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Content — единая точка доступа к контенту сайта.
 *
 * Услуги и марки лежат в app/Data/*.php (большие массивы), компания —
 * в config/levada.php. Здесь только аксессоры и группировки для меню/страниц.
 * Данные кешируются статически на время запроса.
 */
final class Content
{
    private static ?array $services = null;
    private static ?array $brands   = null;

    /** Все услуги (11), порядок из источника. */
    public static function services(): array
    {
        return self::$services ??= require base_path('app/Data/services.php');
    }

    /** Все марки (43), порядок из источника. */
    public static function brands(): array
    {
        return self::$brands ??= require base_path('app/Data/brands.php');
    }

    public static function findService(string $slug): ?array
    {
        foreach (self::services() as $s) {
            if (($s['slug'] ?? null) === $slug) {
                return $s;
            }
        }

        return null;
    }

    public static function findBrand(string $slug): ?array
    {
        foreach (self::brands() as $b) {
            if (($b['slug'] ?? null) === $slug) {
                return $b;
            }
        }

        return null;
    }

    /** Опорные услуги (pillar) — для блоков на главной. */
    public static function pillarServices(): array
    {
        return array_values(array_filter(
            self::services(),
            static fn (array $s): bool => ! empty($s['pillar'])
        ));
    }

    /**
     * Услуги в трёх смысловых блоках — для мега-меню и страницы «Услуги».
     * Возвращает [Заголовок => [услуга, ...], ...] в фиксированном порядке.
     */
    public static function serviceGroups(): array
    {
        $map = [
            'diagnostika'        => 'Диагностика и ТО',
            'to-zamena-masla'    => 'Диагностика и ТО',
            'razval-shozhdenie'  => 'Диагностика и ТО',
            'remont-podveski'    => 'Ремонт и ходовая',
            'tormoznaya-sistema' => 'Ремонт и ходовая',
            'rulevoe-upravlenie' => 'Ремонт и ходовая',
            'remont-glushitelya' => 'Ремонт и ходовая',
            'chip-tyuning'       => 'Электроника и защита',
            'rusifikaciya'       => 'Электроника и защита',
            'antikor'            => 'Электроника и защита',
            'ozonirovanie'       => 'Электроника и защита',
        ];

        $order  = ['Диагностика и ТО', 'Ремонт и ходовая', 'Электроника и защита'];
        $groups = array_fill_keys($order, []);

        foreach (self::services() as $s) {
            $title             = $map[$s['slug'] ?? ''] ?? $order[0];
            $groups[$title][]  = $s;
        }

        return $groups;
    }

    /**
     * Марки по группам с человекочитаемыми заголовками — для мега-меню и
     * страницы «Марки». Ключи: china | ev | asia | euro.
     */
    public static function brandGroups(): array
    {
        $titles = [
            'china' => 'Китайские авто',
            'ev'    => 'Электромобили и гибриды',
            'asia'  => 'Азиатские',
            'euro'  => 'Европейские',
        ];

        $groups = [];
        foreach ($titles as $key => $title) {
            $groups[$key] = ['key' => $key, 'title' => $title, 'brands' => []];
        }

        foreach (self::brands() as $b) {
            $key = $b['group'] ?? 'china';
            if (! isset($groups[$key])) {
                $groups[$key] = ['key' => $key, 'title' => ucfirst($key), 'brands' => []];
            }
            $groups[$key]['brands'][] = $b;
        }

        return $groups;
    }

    /**
     * Галерея услуги — авто-скан папки public/assets/img/<slug>.
     *
     * Имя файла трактуется как марка/модель авто и используется для
     * автоподстановки в форму заявки. Если имя совпадает с авто из segments —
     * берём каноническое написание; иначе аккуратно форматируем имя файла.
     * «Мусорные» имена (ChatGPT…, screenshot, таймстампы) — плитка без модели.
     *
     * @return array<int,array{src:string,car:string,generic:bool}>
     */
    public static function serviceGallery(string $slug): array
    {
        $dir = public_path('assets/img/' . $slug);
        if (! is_dir($dir)) {
            return [];
        }

        $files = glob($dir . '/*.{png,jpg,jpeg,webp,PNG,JPG,JPEG,WEBP}', GLOB_BRACE) ?: [];
        sort($files, SORT_NATURAL | SORT_FLAG_CASE);

        // Каноничные названия авто из этой услуги (для точных подписей).
        $known = [];
        foreach ((self::findService($slug)['segments'] ?? []) as $seg) {
            foreach (($seg['cars'] ?? []) as $car) {
                $known[self::normalizeCar($car)] = $car;
            }
        }

        $slides = [];
        foreach ($files as $path) {
            $base = pathinfo($path, PATHINFO_FILENAME);
            $name = trim((string) preg_replace('/\s+/', ' ', (string) preg_replace('/[_\-]+/', ' ', $base)));

            $generic = (bool) preg_match('/chatgpt|screenshot|untitled|^image\b|\d{2}[\s_]\d{2}[\s_]\d{2}/iu', $base);
            $car     = $generic ? '' : ($known[self::normalizeCar($name)] ?? self::titleizeCar($name));

            $slides[] = [
                'src'     => asset('assets/img/' . $slug . '/' . rawurlencode(basename($path))),
                'car'     => $car,
                'generic' => $generic,
            ];
        }

        return $slides;
    }

    /** Нормализация названия авто для сравнения (нижний регистр, один пробел). */
    private static function normalizeCar(string $car): string
    {
        return mb_strtolower(trim((string) preg_replace('/\s+/', ' ', (string) preg_replace('/[_\-]+/', ' ', $car))), 'UTF-8');
    }

    /** Форматирование имени файла в подпись: «belgee x70» → «Belgee X70». */
    private static function titleizeCar(string $name): string
    {
        return (string) preg_replace_callback('/[\p{L}\p{N}]+/u', static function (array $m): string {
            $w = $m[0];
            // коды с цифрами (x70, id4, l6) — целиком в верхний регистр
            if (preg_match('/\p{N}/u', $w)) {
                return mb_strtoupper($w, 'UTF-8');
            }

            return mb_strtoupper(mb_substr($w, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($w, 1, null, 'UTF-8');
        }, $name);
    }

    /** Главное меню сайта. Пункты с 'mega' раскрывают мега-панель. */
    public static function mainNav(): array
    {
        return [
            ['label' => 'Главная',    'route' => 'home',           'mega' => null],
            ['label' => 'Услуги',     'route' => 'services.index', 'mega' => 'services'],
            ['label' => 'Марки авто', 'route' => 'brands.index',   'mega' => 'brands'],
            ['label' => 'О нас',      'route' => 'about',          'mega' => null],
            ['label' => 'Контакты',   'route' => 'contacts',       'mega' => null],
        ];
    }
}
