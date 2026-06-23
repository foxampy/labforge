{{-- Шапка: эмблема на светлой табличке + вордмарк, навигация, телефон, CTA, бургер. --}}
<header class="site-header">
    <div class="container">
        <a href="{{ route('home') }}" class="brand" aria-label="LEVADA SERVICE — на главную">
            <img class="brand__logo" src="{{ asset('assets/img/logo.png') }}" alt="LEVADA SERVICE" width="46" height="46" decoding="async">
            <span class="brand__name">LEVADA<small>SERVICE</small></span>
        </a>

        <nav class="main-nav" id="main-nav" aria-label="Основная навигация">
            @foreach (\App\Support\Content::mainNav() as $item)
                @if ($item['mega'] === 'services')
                    <div class="nav-item has-mega">
                        <a href="{{ route($item['route']) }}"
                           class="nav-link {{ request()->routeIs('services.*') ? 'is-active' : '' }}"
                           aria-haspopup="true" aria-expanded="false">
                            {{ $item['label'] }}
                            <svg class="nav-caret" viewBox="0 0 12 8" aria-hidden="true"><path d="M1 1l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <div class="mega mega--services" role="menu">
                            <div class="mega-grid">
                                @foreach (\App\Support\Content::serviceGroups() as $groupTitle => $items)
                                    <div class="mega-col">
                                        <h4 class="mega-title">{{ $groupTitle }}</h4>
                                        @foreach ($items as $s)
                                            <a class="mega-link" role="menuitem" href="{{ route('services.show', $s['slug']) }}">{{ $s['title'] }}</a>
                                        @endforeach
                                    </div>
                                @endforeach
                                <div class="mega-foot">
                                    <a href="{{ route('services.index') }}">Все услуги и цены &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($item['mega'] === 'brands')
                    <div class="nav-item has-mega">
                        <a href="{{ route($item['route']) }}"
                           class="nav-link {{ request()->routeIs('brands.*') ? 'is-active' : '' }}"
                           aria-haspopup="true" aria-expanded="false">
                            {{ $item['label'] }}
                            <svg class="nav-caret" viewBox="0 0 12 8" aria-hidden="true"><path d="M1 1l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <div class="mega mega--brands" role="menu">
                            <div class="mega-grid">
                                @foreach (\App\Support\Content::brandGroups() as $g)
                                    @if (! empty($g['brands']))
                                        <div class="mega-col">
                                            <h4 class="mega-title">{{ $g['title'] }}</h4>
                                            @foreach (array_slice($g['brands'], 0, 7) as $b)
                                                <a class="mega-link" role="menuitem" href="{{ route('brands.show', $b['slug']) }}">{{ $b['name'] }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                                <div class="mega-foot">
                                    <a href="{{ route('brands.index') }}">Все марки авто &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                @endif
            @endforeach
        </nav>

        <div class="header-cta">
            <a class="header-phone" href="{{ lv('phone_href') }}">
                {!! ui_icon('phone', 'icon') !!}
                <span>{{ lv('phone') }}</span>
            </a>
            <a class="btn btn-primary" href="{{ route('contacts') }}#zayavka">Записаться</a>

            <button class="nav-toggle" type="button" aria-label="Меню" aria-controls="main-nav" aria-expanded="false">
                <span></span>
            </button>
        </div>
    </div>
</header>
