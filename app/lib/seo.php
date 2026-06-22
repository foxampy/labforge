<?php
declare(strict_types=1);

/* ------------------------------------------------------------------ *
 *  SEO/GEO — порт src/lib/seo.ts.                                     *
 *  page_meta() -> массив метаданных; *_jsonld() -> объекты JSON-LD.   *
 * ------------------------------------------------------------------ */

/**
 * Метаданные страницы.
 *  opts: title(string, обязат.), description(string), path(string='/'),
 *        keywords(array), noindex(bool), titleTemplate(bool=true).
 * Если titleTemplate=true, в <title> добавляется « | LEVADA SERVICE».
 */
function page_meta(array $opts): array
{
    $c   = company();
    $url = abs_url($opts['path'] ?? '/');
    return [
        'title'         => $opts['title'] ?? $c['name'],
        'titleTemplate' => $opts['titleTemplate'] ?? true,
        'description'   => $opts['description'] ?? $c['description'],
        'keywords'      => $opts['keywords'] ?? null,
        'canonical'     => $url,
        'noindex'       => !empty($opts['noindex']),
        'ogTitle'       => $opts['title'] ?? $c['name'],
        'ogDescription' => $opts['description'] ?? $c['description'],
        'ogUrl'         => $url,
        'ogImage'       => abs_url('/assets/img/og.jpg'),
        'siteName'      => $c['name'],
        'locale'        => $c['locale'] ?? 'ru_BY',
    ];
}

/** JSON-LD: автосервис как локальный бизнес (GEO-ядро). */
function auto_repair_jsonld(): array
{
    $c = company();
    return [
        '@context'    => 'https://schema.org',
        '@type'       => 'AutoRepair',
        '@id'         => abs_url('/#business'),
        'name'        => $c['name'],
        'legalName'   => $c['legalName'],
        'description' => $c['description'],
        'url'         => site_url(),
        'telephone'   => $c['phone'],
        'email'       => $c['email'],
        'priceRange'  => $c['priceRange'],
        'image'       => abs_url('/assets/img/og.jpg'),
        'logo'        => abs_url('/assets/img/logo.png'),
        'foundingDate' => (string) $c['foundedYear'],
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $c['address']['street'],
            'addressLocality' => $c['address']['city'],
            'addressRegion'   => $c['address']['region'],
            'postalCode'      => $c['address']['postalCode'],
            'addressCountry'  => $c['address']['country'],
        ],
        'geo' => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => $c['geo']['lat'],
            'longitude' => $c['geo']['lng'],
        ],
        'areaServed' => array_map(
            static fn ($name) => ['@type' => 'City', 'name' => $name],
            $c['areasServed']
        ),
        'openingHoursSpecification' => array_map(
            static fn ($o) => [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => $o['days'],
                'opens'     => $o['opens'],
                'closes'    => $o['closes'],
            ],
            $c['openingHoursSpec']
        ),
        'sameAs' => array_values(array_filter(array_values($c['social']))),
    ];
}

/** JSON-LD: услуга. $s: title, description, path. */
function service_jsonld(array $s): array
{
    $c = company();
    return [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => $s['title'],
        'description' => $s['description'],
        'url'         => abs_url($s['path']),
        'serviceType' => $s['title'],
        'areaServed'  => array_map(
            static fn ($name) => ['@type' => 'City', 'name' => $name],
            $c['areasServed']
        ),
        'provider' => ['@type' => 'AutoRepair', '@id' => abs_url('/#business'), 'name' => $c['name']],
    ];
}

/** JSON-LD: хлебные крошки. $items: список [name, path]. */
function breadcrumb_jsonld(array $items): array
{
    $list = [];
    foreach ($items as $i => $it) {
        $list[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $it['name'],
            'item'     => abs_url($it['path']),
        ];
    }
    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $list,
    ];
}

/** JSON-LD: FAQ. $faq: список [q, a]. */
function faq_jsonld(array $faq): array
{
    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(
            static fn ($f) => [
                '@type'          => 'Question',
                'name'           => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ],
            $faq
        ),
    ];
}
