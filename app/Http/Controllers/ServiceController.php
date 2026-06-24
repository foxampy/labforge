<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends Controller
{
    public function index(): View
    {
        $crumbs = [
            ['name' => 'Главная', 'url' => route('home')],
            ['name' => 'Услуги',  'url' => route('services.index')],
        ];

        return view('services.index', [
            'meta' => Seo::meta([
                'title'       => 'Услуги и цены',
                'description' => 'Все услуги автосервиса LEVADA SERVICE в Минске: диагностика, ТО, ремонт ходовой и тормозов, чип-тюнинг, русификация китайских авто, антикор. Цены в BYN.',
                'canonical'   => route('services.index'),
            ]),
            'jsonld'    => [Seo::breadcrumbJsonLd($crumbs)],
            'groups'    => Content::serviceGroups(),
            'crumbs'    => $crumbs,
        ]);
    }

    public function show(string $slug): View
    {
        $service = Content::findService($slug);
        if ($service === null) {
            throw new NotFoundHttpException('Услуга не найдена');
        }

        $crumbs = [
            ['name' => 'Главная', 'url' => route('home')],
            ['name' => 'Услуги',  'url' => route('services.index')],
            ['name' => $service['title'], 'url' => route('services.show', $slug)],
        ];

        $related = array_values(array_filter(
            Content::services(),
            static fn (array $s): bool => $s['slug'] !== $slug
        ));

        return view('services.show', [
            'meta' => Seo::meta([
                'title'       => $service['metaTitle'] ?? $service['title'],
                'description' => $service['metaDescription'] ?? ($service['excerpt'] ?? ''),
                'canonical'   => route('services.show', $slug),
            ]),
            'jsonld' => [
                Seo::serviceJsonLd($service),
                Seo::faqJsonLd($service['faq'] ?? []),
                Seo::howToJsonLd('Как проходит ' . mb_strtolower($service['title']), $service['process'] ?? []),
                Seo::breadcrumbJsonLd($crumbs),
            ],
            'service' => $service,
            'related' => array_slice($related, 0, 6),
            'crumbs'  => $crumbs,
            'slides'  => Content::serviceGallery($slug),
        ]);
    }
}
