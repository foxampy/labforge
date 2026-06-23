<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandController extends Controller
{
    public function index(): View
    {
        $crumbs = [
            ['name' => 'Главная',   'url' => route('home')],
            ['name' => 'Марки авто', 'url' => route('brands.index')],
        ];

        return view('brands.index', [
            'meta' => Seo::meta([
                'title'       => 'Марки авто',
                'description' => 'Обслуживаем 43 марки: китайские авто (Geely, Chery, Haval), электромобили (Li Auto, Zeekr, BYD, Tesla), европейские и азиатские. Ремонт, ТО, диагностика в Минске.',
                'canonical'   => route('brands.index'),
            ]),
            'jsonld' => [Seo::breadcrumbJsonLd($crumbs)],
            'groups' => Content::brandGroups(),
            'crumbs' => $crumbs,
        ]);
    }

    public function show(string $slug): View
    {
        $brand = Content::findBrand($slug);
        if ($brand === null) {
            throw new NotFoundHttpException('Марка не найдена');
        }

        $crumbs = [
            ['name' => 'Главная',    'url' => route('home')],
            ['name' => 'Марки авто', 'url' => route('brands.index')],
            ['name' => $brand['name'], 'url' => route('brands.show', $slug)],
        ];

        $related = array_values(array_filter(
            Content::brands(),
            static fn (array $b): bool => $b['slug'] !== $slug && ($b['group'] ?? '') === ($brand['group'] ?? '')
        ));

        return view('brands.show', [
            'meta' => Seo::meta([
                'title'       => 'Ремонт и ТО ' . $brand['name'] . ' в Минске',
                'description' => $brand['excerpt'] ?? ('Ремонт, ТО и диагностика ' . $brand['name'] . ' в Минске — LEVADA SERVICE.'),
                'canonical'   => route('brands.show', $slug),
            ]),
            'jsonld'  => [Seo::breadcrumbJsonLd($crumbs)],
            'brand'   => $brand,
            'related' => array_slice($related, 0, 6),
            'pillars' => Content::pillarServices(),
            'crumbs'  => $crumbs,
        ]);
    }
}
