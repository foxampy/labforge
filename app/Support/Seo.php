<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Seo — нормализация мета-тегов и сборка JSON-LD (schema.org).
 *
 * meta() даёт единый массив для <head> с фолбэками из config/levada.
 * Методы *JsonLd() возвращают массивы; рендер в <script> — через helper json_ld().
 */
final class Seo
{
    /**
     * Нормализованные мета-данные страницы.
     *
     * @param  array{title?:string,description?:string,canonical?:string,image?:string,robots?:string,type?:string}  $o
     * @return array{title:string,description:string,canonical:string,image:string,robots:string,type:string,site_name:string,locale:string}
     */
    public static function meta(array $o = []): array
    {
        $siteName = (string) config('levada.name');
        $title    = trim((string) ($o['title'] ?? ''));

        return [
            'title'       => $title !== '' ? $title . ' — ' . $siteName : (string) config('levada.tagline'),
            'description' => (string) ($o['description'] ?? config('levada.description')),
            'canonical'   => (string) ($o['canonical'] ?? url()->current()),
            'image'       => self::abs((string) ($o['image'] ?? 'assets/img/og.jpg')),
            'robots'      => (string) ($o['robots'] ?? 'index, follow'),
            'type'        => (string) ($o['type'] ?? 'website'),
            'site_name'   => $siteName,
            'locale'      => (string) config('levada.locale', 'ru_BY'),
        ];
    }

    /** Главная бизнес-сущность: AutoRepair (LocalBusiness). */
    public static function autoRepairJsonLd(): array
    {
        $cfg  = config('levada');
        $addr = $cfg['address'];

        $node = [
            '@context'    => 'https://schema.org',
            '@type'       => 'AutoRepair',
            '@id'         => rtrim((string) $cfg['url'], '/') . '/#business',
            'name'        => $cfg['name'],
            'legalName'   => $cfg['legal_name'],
            'description' => $cfg['description'],
            'url'         => $cfg['url'],
            'telephone'   => $cfg['phone'],
            'email'       => $cfg['email'],
            'image'       => self::abs('assets/img/og.jpg'),
            'logo'        => self::abs('assets/img/logo.png'),
            'priceRange'  => $cfg['price_range'],
            'foundingDate' => (string) $cfg['founded_year'],
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $addr['street'],
                'addressLocality' => $addr['city'],
                'addressRegion'   => $addr['region'],
                'postalCode'      => $addr['postal_code'],
                'addressCountry'  => $addr['country'],
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $cfg['geo']['lat'],
                'longitude' => $cfg['geo']['lng'],
            ],
            'areaServed' => array_map(
                static fn (string $city): array => ['@type' => 'City', 'name' => $city],
                $cfg['areas_served']
            ),
            'openingHoursSpecification' => array_map(
                static fn (array $h): array => [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => $h['days'],
                    'opens'     => $h['opens'],
                    'closes'    => $h['closes'],
                ],
                $cfg['opening_hours_spec']
            ),
        ];

        $sameAs = array_values(array_filter([
            $cfg['social']['instagram'] ?? '',
            $cfg['social']['vk'] ?? '',
            $cfg['social']['youtube'] ?? '',
        ]));
        if ($sameAs !== []) {
            $node['sameAs'] = $sameAs;
        }

        return $node;
    }

    /** Сайт как WebSite (для AI/поиска). */
    public static function webSiteJsonLd(): array
    {
        return [
            '@context'      => 'https://schema.org',
            '@type'         => 'WebSite',
            'name'          => config('levada.name'),
            'url'           => config('levada.url'),
            'inLanguage'    => 'ru-BY',
            'publisher'     => ['@id' => rtrim((string) config('levada.url'), '/') . '/#business'],
        ];
    }

    /** Услуга как Service, привязанная к бизнесу. */
    public static function serviceJsonLd(array $s): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'serviceType' => $s['title'] ?? '',
            'name'        => $s['h1'] ?? ($s['title'] ?? ''),
            'description' => $s['metaDescription'] ?? ($s['excerpt'] ?? ''),
            'url'         => isset($s['slug']) ? route('services.show', $s['slug']) : config('levada.url'),
            'areaServed'  => ['@type' => 'City', 'name' => 'Минск'],
            'provider'    => [
                '@type'     => 'AutoRepair',
                '@id'       => rtrim((string) config('levada.url'), '/') . '/#business',
                'name'      => config('levada.name'),
                'telephone' => config('levada.phone'),
            ],
        ];
    }

    /**
     * Хлебные крошки.
     *
     * @param  array<int,array{name:string,url:string}>  $items
     */
    public static function breadcrumbJsonLd(array $items): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array_map(
                static fn (array $it, int $i): array => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'name'     => $it['name'],
                    'item'     => $it['url'],
                ],
                $items,
                array_keys($items)
            ),
        ];
    }

    /**
     * FAQ-разметка.
     *
     * @param  array<int,array{q:string,a:string}>  $faq
     */
    public static function faqJsonLd(array $faq): ?array
    {
        if ($faq === []) {
            return null;
        }

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => array_map(
                static fn (array $qa): array => [
                    '@type'          => 'Question',
                    'name'           => $qa['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa['a']],
                ],
                $faq
            ),
        ];
    }

    /**
     * HowTo-разметка для пошагового процесса (например, этапы услуги).
     *
     * @param  array<int,array{step:string,text?:string}>  $steps
     */
    public static function howToJsonLd(string $name, array $steps): ?array
    {
        if ($steps === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type'    => 'HowTo',
            'name'     => $name,
            'step'     => array_map(
                static fn (array $s, int $i): array => [
                    '@type'    => 'HowToStep',
                    'position' => $i + 1,
                    'name'     => $s['step'] ?? '',
                    'text'     => $s['text'] ?? ($s['step'] ?? ''),
                ],
                $steps,
                array_keys($steps)
            ),
        ];
    }

    /** Абсолютный URL из относительного пути (для og/schema). */
    private static function abs(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return rtrim((string) config('levada.url'), '/') . '/' . ltrim($path, '/');
    }
}
