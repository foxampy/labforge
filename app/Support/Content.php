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
