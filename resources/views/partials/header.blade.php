{{-- Шапка: эмблема на светлой табличке + вордмарк, навигация, телефон, CTA, бургер. --}}
<header class="site-header">
    <div class="container">
        <a href="{{ route('home') }}" class="brand" aria-label="LEVADA SERVICE — на главную">
            <img class="brand__logo" src="{{ asset('assets/img/logo.png') }}" alt="LEVADA SERVICE" width="46" height="46" decoding="async">
            <span class="brand__name">LEVADA<small>SERVICE</small></span>
        </a>

        <nav class="main-nav" aria-label="Основная навигация">
            @foreach (\App\Support\Content::mainNav() as $item)
                <a href="{{ route($item['route']) }}"
                   class="{{ request()->routeIs($item['route']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
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
