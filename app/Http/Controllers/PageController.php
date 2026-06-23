<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $crumbs = [
            ['name' => 'Главная', 'url' => route('home')],
            ['name' => 'О нас',   'url' => route('about')],
        ];

        $faq = lv('about_faq', []);

        return view('pages.about', [
            'meta' => Seo::meta([
                'title'       => 'О компании',
                'description' => 'LEVADA SERVICE — автосервис в Минске с 2014 года. Специализация на китайских авто и электромобилях, письменная гарантия, прозрачные цены, современное оборудование.',
                'canonical'   => route('about'),
            ]),
            'jsonld' => [
                Seo::faqJsonLd($faq),
                Seo::breadcrumbJsonLd($crumbs),
            ],
            'achievements' => lv('achievements', []),
            'guarantees'   => lv('guarantees', []),
            'equipment'    => lv('equipment', []),
            'process'      => lv('process', []),
            'faq'          => $faq,
            'crumbs'       => $crumbs,
        ]);
    }

    public function contacts(): View
    {
        $crumbs = [
            ['name' => 'Главная',  'url' => route('home')],
            ['name' => 'Контакты', 'url' => route('contacts')],
        ];

        return view('pages.contacts', [
            'meta' => Seo::meta([
                'title'       => 'Контакты и запись',
                'description' => 'Автосервис LEVADA SERVICE: ул. Нововиленская, 48к2 (ЖК «Левада»), Минск. Телефон, Telegram, форма записи онлайн. Режим работы Пн–Вс.',
                'canonical'   => route('contacts'),
            ]),
            'jsonld'         => [Seo::autoRepairJsonLd(), Seo::breadcrumbJsonLd($crumbs)],
            'bookingMethods' => lv('booking_methods', []),
            'crumbs'         => $crumbs,
        ]);
    }
}
