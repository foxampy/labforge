<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Content;
use Illuminate\Http\Response;

/**
 * Машиночитаемые файлы для поиска и AI: sitemap.xml, robots.txt, llms.txt.
 * Генерируются динамически из контента — править нечего, всегда актуальны.
 */
class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            ['loc' => route('home'),           'priority' => '1.0', 'freq' => 'weekly'],
            ['loc' => route('services.index'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => route('brands.index'),   'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => route('about'),          'priority' => '0.6', 'freq' => 'monthly'],
            ['loc' => route('contacts'),       'priority' => '0.7', 'freq' => 'monthly'],
        ];

        foreach (Content::services() as $s) {
            $urls[] = ['loc' => route('services.show', $s['slug']), 'priority' => '0.8', 'freq' => 'monthly'];
        }
        foreach (Content::brands() as $b) {
            $urls[] = ['loc' => route('brands.show', $b['slug']), 'priority' => '0.7', 'freq' => 'monthly'];
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            'Sitemap: ' . url('sitemap.xml'),
        ];

        return response(implode("\n", $lines) . "\n")
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function llms(): Response
    {
        $hours = implode('; ', array_map(
            static fn (array $h): string => $h['days'] . ' ' . $h['time'],
            (array) lv('opening_hours', [])
        ));

        $lines = [
            '# ' . lv('name'),
            '',
            lv('tagline'),
            '',
            '## Кратко о компании',
            '',
            lv('description'),
            '',
            '## Контакты',
            '',
            '- Адрес: ' . lv('address.street') . ' (' . lv('address.district') . '), ' . lv('address.city') . ', ' . lv('address.country_name'),
            '- Телефон: ' . lv('phone'),
            '- E-mail: ' . lv('email'),
            '- Telegram: ' . lv('telegram'),
            '- Сайт: ' . lv('url'),
            '- Год основания: ' . lv('founded_year'),
            '- Режим работы: ' . $hours,
            '',
            '## Услуги',
            '',
        ];

        foreach (Content::services() as $s) {
            $lines[] = '- [' . $s['title'] . '](' . route('services.show', $s['slug']) . '): ' . ($s['excerpt'] ?? '');
        }

        $lines[] = '';
        $lines[] = '## Марки';
        $lines[] = '';
        foreach (Content::brands() as $b) {
            $lines[] = '- [' . $b['name'] . '](' . route('brands.show', $b['slug']) . ')';
        }

        $lines[] = '';
        $lines[] = '## Страницы';
        $lines[] = '';
        $lines[] = '- [Главная](' . route('home') . ')';
        $lines[] = '- [Все услуги и цены](' . route('services.index') . ')';
        $lines[] = '- [Марки авто](' . route('brands.index') . ')';
        $lines[] = '- [О нас](' . route('about') . ')';
        $lines[] = '- [Контакты](' . route('contacts') . ')';

        return response(implode("\n", $lines) . "\n")
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
