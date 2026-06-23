@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<article>
    <section class="section page-head">
        <div class="container">
            <div class="reveal" style="display:flex;gap:22px;align-items:center;flex-wrap:wrap">
                <span class="brand-emblem" style="width:88px;height:88px;flex:none">{!! brand_logo_img($brand, 'brand-logo', 'text-xl') !!}</span>
                <div>
                    <p class="eyebrow eyebrow--solo">{{ $brand['country'] }}</p>
                    <h1 class="heading" style="margin-top:8px">Ремонт и ТО {{ $brand['name'] }} в Минске</h1>
                </div>
            </div>
            <p class="subheading reveal" style="margin-top:20px;max-width:760px">{{ $brand['intro'] ?? $brand['excerpt'] }}</p>
            <div class="hero__actions reveal" style="justify-content:flex-start;margin-top:24px">
                <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Записаться на ремонт</a>
                <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
            </div>
        </div>
    </section>

    <section class="section-tight">
        <div class="container">
            <div class="grid grid-2" style="align-items:start">
                @if (!empty($brand['commonIssues']))
                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Частые неисправности {{ $brand['name'] }}</h2>
                    <ul class="check-list" style="margin-top:18px">
                        @foreach ($brand['commonIssues'] as $issue)
                            <li>{{ $issue }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (!empty($brand['popularModels']))
                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Популярные модели</h2>
                    <div class="chips" style="margin-top:18px">
                        @foreach ($brand['popularModels'] as $m)
                            <span class="chip" style="cursor:default">{{ $m }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Что мы делаем</p>
                <h2 class="heading">Услуги для {{ $brand['name'] }}</h2>
            </div>
            <div class="grid grid-3 reveal" style="margin-top:36px">
                @foreach ($pillars as $s)
                    <a class="card card-link" href="{{ route('services.show', $s['slug']) }}">
                        <span class="icon-box">{!! svc_icon($s['slug']) !!}</span>
                        <h3 class="card-title">{{ $s['title'] }}</h3>
                        <p class="card-text">{{ $s['excerpt'] }}</p>
                        <span class="card-more">Подробнее <span class="arrow">&rarr;</span></span>
                    </a>
                @endforeach
            </div>
            <div class="center" style="margin-top:30px">
                <a class="btn btn-ghost" href="{{ route('services.index') }}">Все услуги и цены</a>
            </div>
        </div>
    </section>

    @if (!empty($related))
    <section class="section" style="background:var(--ink-2)">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Похожие марки</p>
                <h2 class="heading">Смотрите также</h2>
            </div>
            <div class="grid grid-auto-sm reveal" style="margin-top:36px">
                @foreach ($related as $b)
                    <a class="card card-link brand-card" href="{{ route('brands.show', $b['slug']) }}" title="{{ $b['name'] }}">
                        <span class="brand-emblem">{!! brand_logo_img($b, 'brand-logo') !!}</span>
                        <span class="brand-name">{{ $b['name'] }}</span>
                        <span class="brand-country">{{ $b['country'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</article>
@endsection
