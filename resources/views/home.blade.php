@extends('layouts.app')

@section('content')

{{-- ======================= HERO (фон — hero.png) ======================= --}}
<section class="hero">
    <div class="hero__bg">
        <img src="{{ asset('assets/img/hero.png') }}" alt="Бокс автосервиса LEVADA SERVICE в Минске" fetchpriority="high" decoding="async">
    </div>

    <div class="hero__inner reveal">
        <p class="hero__tagline">Китайские авто · электромобили · все марки</p>
        <h1 class="hero__title">Ремонт авто в Минске <span>без переплат</span></h1>
        <p class="hero__sub">{{ lv('description') }}</p>

        <div class="hero__actions">
            <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Записаться на ремонт</a>
            <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{!! ui_icon('phone', 'icon') !!} {{ lv('phone') }}</a>
            <a class="btn btn-ghost btn-lg" href="{{ lv('telegram') }}" target="_blank" rel="noopener">{!! ui_icon('telegram', 'icon') !!} Telegram</a>
        </div>

        <div class="hero__stats">
            @foreach ($stats as $st)
                <div class="hero__stat"><b>{{ $st['value'] }}</b><span>{{ $st['label'] }}</span></div>
            @endforeach
        </div>
    </div>

    <div class="hero__scroll" aria-hidden="true">Листайте<span></span></div>
</section>

{{-- ======================= УСЛУГИ ======================= --}}
<section class="section" id="uslugi">
    <div class="container">
        <div class="center reveal">
            <p class="eyebrow">Что мы делаем</p>
            <h2 class="heading">Услуги автосервиса</h2>
            <p class="subheading">От диагностики и ТО до чип-тюнинга, русификации китайских авто и антикоррозийной обработки — полный цикл обслуживания под одной крышей.</p>
        </div>

        <div class="grid grid-auto reveal" style="margin-top:42px">
            @foreach ($services as $s)
                <a class="card card-link" href="{{ route('services.show', $s['slug']) }}">
                    <span class="icon-box">{!! svc_icon($s['slug']) !!}</span>
                    <h3 class="card-title">{{ $s['title'] }}</h3>
                    <p class="card-text">{{ $s['excerpt'] }}</p>
                    <span class="card-more">Подробнее <span class="arrow">&rarr;</span></span>
                </a>
            @endforeach
        </div>

        <div class="center" style="margin-top:34px">
            <a class="btn btn-ghost" href="{{ route('services.index') }}">Все услуги и цены</a>
        </div>
    </div>
</section>

{{-- ======================= ПРЕИМУЩЕСТВА ======================= --}}
<section class="section" style="background:var(--ink-2)">
    <div class="container">
        <div class="center reveal">
            <p class="eyebrow">Почему мы</p>
            <h2 class="heading">Почему выбирают LEVADA SERVICE</h2>
        </div>
        <div class="grid grid-4 reveal" style="margin-top:42px">
            @foreach ($usp as $u)
                <div class="card feature">
                    <span class="star">&#9733;</span>
                    <h3>{{ $u['title'] }}</h3>
                    <p>{{ $u['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================= МАРКИ (стена логотипов) ======================= --}}
<section class="section" id="marki">
    <div class="container">
        <div class="center reveal">
            <p class="eyebrow">Работаем со всеми</p>
            <h2 class="heading">Марки авто</h2>
            <p class="subheading">Специализируемся на китайских автомобилях и электромобилях, обслуживаем европейские и азиатские марки — всего 43 бренда.</p>
        </div>

        <div class="grid grid-auto-sm reveal" style="margin-top:42px">
            @foreach ($brandGroups as $g)
                @foreach ($g['brands'] as $b)
                    <a class="card card-link brand-card" href="{{ route('brands.show', $b['slug']) }}" title="{{ $b['name'] }}">
                        <span class="brand-emblem">{!! brand_logo_img($b, 'brand-logo') !!}</span>
                        <span class="brand-name">{{ $b['name'] }}</span>
                        <span class="brand-country">{{ $b['country'] }}</span>
                    </a>
                @endforeach
            @endforeach
        </div>

        <div class="center" style="margin-top:34px">
            <a class="btn btn-ghost" href="{{ route('brands.index') }}">Все марки авто</a>
        </div>
    </div>
</section>

{{-- ======================= ПРОЦЕСС ======================= --}}
<section class="section" style="background:var(--ink-2)">
    <div class="container">
        <div class="center reveal">
            <p class="eyebrow">Как мы работаем</p>
            <h2 class="heading">Простой путь до ремонта</h2>
        </div>
        <div class="grid grid-4 reveal" style="margin-top:42px">
            @foreach ($process as $i => $p)
                <div class="step">
                    <span class="step__n">{{ $i + 1 }}</span>
                    <h3>{{ $p['step'] }}</h3>
                    <p>{{ $p['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================= CTA ======================= --}}
<section class="section-tight">
    <div class="container">
        <div class="cta-band reveal">
            <p class="eyebrow eyebrow--solo">Запись онлайн</p>
            <h2 class="heading" style="margin-top:10px">Готовы записать ваш автомобиль</h2>
            <p class="subheading" style="margin-inline:auto">Оставьте заявку — перезвоним в течение 15 минут, подберём удобное время и сориентируем по стоимости.</p>
            <div class="hero__actions" style="margin-top:26px">
                <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Оставить заявку</a>
                <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
            </div>
        </div>
    </div>
</section>

@endsection
