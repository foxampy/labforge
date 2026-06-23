<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'meta' => Seo::meta([
                'title'       => '',
                'description' => lv('description'),
                'canonical'   => route('home'),
            ]),
            'jsonld' => [
                Seo::autoRepairJsonLd(),
                Seo::webSiteJsonLd(),
            ],
            'pillars'     => Content::pillarServices(),
            'services'    => Content::services(),
            'brandGroups' => Content::brandGroups(),
            'stats'       => lv('stats', []),
            'usp'         => lv('usp', []),
            'process'     => lv('process', []),
        ]);
    }
}
