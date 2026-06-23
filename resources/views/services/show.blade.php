@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<article>
    <section class="section page-head">
        <div class="container">
            <div class="reveal" style="max-width:760px">
                <p class="eyebrow"><span class="icon-box" style="display:inline-grid;vertical-align:middle;width:38px;height:38px;margin-right:10px">{!! svc_icon($service['slug'], 'h-5 w-5') !!}</span>Услуга</p>
                <h1 class="heading" style="margin-top:14px">{{ $service['h1'] ?? $service['title'] }}</h1>
                <p class="subheading">{{ $service['intro'] ?? $service['excerpt'] }}</p>
                <div class="hero__actions" style="justify-content:flex-start;margin-top:26px">
                    <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Записаться на услугу</a>
                    <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
                </div>
            </div>
        </div>
    </section>

    @if (!empty($service['works']))
    <section class="section-tight">
        <div class="container">
            <div class="grid grid-2" style="align-items:start">
                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Что входит в работу</h2>
                    <ul class="check-list" style="margin-top:18px">
                        @foreach ($service['works'] as $w)
                            <li>{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>

                @if (!empty($service['prices']))
                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Цены</h2>
                    <div class="price-list" style="margin-top:18px">
                        @foreach ($service['prices'] as $p)
                            <div class="price-row">
                                <span class="price-row__name">{{ $p['name'] }}</span>
                                <span class="price-row__price">{{ $p['price'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="form-note" style="margin-top:14px">Цены ориентировочные. Точную стоимость называем после диагностики — без навязанных услуг.</p>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['zones']))
    @php
        $zoneIcons = [
            'underbody'  => '<path d="M4 13l1.6-4.2A2.5 2.5 0 0 1 8 7h8a2.5 2.5 0 0 1 2.4 1.8L20 13"/><path d="M3 13h18"/><circle cx="8" cy="16" r="1.7"/><circle cx="16" cy="16" r="1.7"/><path d="M5 19.6h14"/>',
            'arch'       => '<path d="M4 17a8 8 0 0 1 16 0"/><circle cx="12" cy="17" r="3"/><path d="M3 20.5h18"/>',
            'cavity'     => '<rect x="5" y="6" width="14" height="12" rx="1.5"/><path d="M12 9c1.5 1.8 2 2.8 2 3.7a2 2 0 1 1-4 0c0-.9.5-1.9 2-3.7z"/>',
            'sill'       => '<rect x="3" y="10" width="18" height="4" rx="1.2"/><path d="M6 14v2M10 14v2M14 14v2M18 14v2"/>',
            'weld'       => '<path d="M3 13l3-3 3 3 3-3 3 3 3-3 3 3"/><path d="M4 18h16"/>',
            'suspension' => '<path d="M9 4h6M9 20h6"/><path d="M9 4c0 2 6 2 6 4s-6 2-6 4 6 2 6 4-6 2-6 4"/>',
        ];
    @endphp
    <section class="section" style="background:var(--ink-2)">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Зоны защиты</p>
                <h2 class="heading">Что обрабатываем</h2>
                <p class="subheading">Защищаем не только видимое днище, но и скрытые полости, швы и подвеску — именно там коррозия чаще всего и начинается.</p>
            </div>
            <div class="grid grid-auto reveal" style="margin-top:42px">
                @foreach ($service['zones'] as $z)
                    <div class="card zone-card">
                        <span class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $zoneIcons[$z['icon']] ?? $zoneIcons['underbody'] !!}</svg></span>
                        <h3 class="card-title">{{ $z['title'] }}</h3>
                        <p class="card-text">{{ $z['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['methods']))
    <section class="section">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Как мы работаем</p>
                <h2 class="heading">Способы и техники обработки</h2>
                <p class="subheading">Для каждой зоны — свой способ нанесения и свой материал. Так защита получается полной, а не «для галочки».</p>
            </div>
            <div class="grid grid-2 reveal" style="margin-top:42px">
                @foreach ($service['methods'] as $i => $m)
                    <div class="card method-card">
                        <span class="method-n">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3 class="card-title">{{ $m['title'] }}</h3>
                        <p class="card-text">{{ $m['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['materials']))
    <section class="section" style="background:var(--ink-2)">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Чем обрабатываем</p>
                <h2 class="heading">Материалы под задачу</h2>
                <p class="subheading">Подбираем составы под зону и автомобиль — от плотной защиты днища до проникающих составов для скрытых полостей.</p>
            </div>
            <div class="grid grid-3 reveal" style="margin-top:42px">
                @foreach ($service['materials'] as $m)
                    <div class="card">
                        <h3 class="card-title" style="margin-top:0">{{ $m['title'] }}</h3>
                        <p class="card-text">{{ $m['text'] }}</p>
                    </div>
                @endforeach
            </div>
            @if (!empty($service['materialsNote']))
                <p class="form-note center" style="margin-top:22px;max-width:760px;margin-inline:auto">{{ $service['materialsNote'] }}</p>
            @endif
        </div>
    </section>
    @endif

    @if (!empty($service['process']))
    <section class="section">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Пошагово</p>
                <h2 class="heading">Как проходит обработка</h2>
                <p class="subheading">Прозрачный процесс: от мойки и дефектовки до контроля качества и рекомендаций по уходу.</p>
            </div>
            <div class="process-grid reveal" style="margin-top:42px">
                @foreach ($service['process'] as $i => $p)
                    <div class="step">
                        <span class="step__n">{{ $i + 1 }}</span>
                        <h3>{{ $p['step'] }}</h3>
                        <p>{{ $p['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['advantages']))
    <section class="section" style="background:var(--ink-2)">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Почему мы</p>
                <h2 class="heading">Почему антикор в LEVADA SERVICE</h2>
            </div>
            <div class="grid grid-4 reveal" style="margin-top:42px">
                @foreach ($service['advantages'] as $a)
                    <div class="card feature">
                        <span class="star">&#9733;</span>
                        <h3>{{ $a['title'] }}</h3>
                        <p>{{ $a['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['segments']))
    <section class="section">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Парк моделей</p>
                <h2 class="heading">Авто, которые обрабатываем</h2>
                @if (!empty($service['segmentsNote']))
                    <p class="subheading">{{ $service['segmentsNote'] }}</p>
                @endif
            </div>
            <div class="car-segments reveal" style="margin-top:42px">
                @foreach ($service['segments'] as $seg)
                    <div class="car-group">
                        <h3 class="car-group__title">{{ $seg['title'] }} <span>{{ count($seg['cars']) }}</span></h3>
                        <ul class="car-chips">
                            @foreach ($seg['cars'] as $car)
                                <li class="car-chip">{{ $car }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <div class="center" style="margin-top:34px">
                <a class="btn btn-primary" href="{{ route('contacts') }}#zayavka">Записаться на антикор</a>
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['faq']))
    <section class="section">
        <div class="container" style="max-width:840px">
            <div class="center reveal">
                <p class="eyebrow">Вопрос–ответ</p>
                <h2 class="heading">Частые вопросы</h2>
            </div>
            <div class="faq reveal" style="margin-top:32px">
                @foreach ($service['faq'] as $qa)
                    <div class="faq__item">
                        <button class="faq__q" type="button">{{ $qa['q'] }} <span class="pm" aria-hidden="true"></span></button>
                        <div class="faq__a"><div>{{ $qa['a'] }}</div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (!empty($service['articles']))
    <section class="section">
        <div class="container" style="max-width:960px">
            <div class="center reveal">
                <p class="eyebrow">Экспертно</p>
                <h2 class="heading">Полезное об антикоре</h2>
                <p class="subheading">Коротко и по делу: зачем нужна обработка, как она устроена и что бывает, если её не делать.</p>
            </div>
            <div class="article-list reveal" style="margin-top:36px">
                @foreach ($service['articles'] as $a)
                    <article class="article">
                        <h3 class="article__title">{{ $a['title'] }}</h3>
                        @foreach ((array) $a['body'] as $para)
                            <p>{{ $para }}</p>
                        @endforeach
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if (!empty($related))
    <section class="section" style="background:var(--ink-2)">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Ещё услуги</p>
                <h2 class="heading">Смотрите также</h2>
            </div>
            <div class="grid grid-3 reveal" style="margin-top:36px">
                @foreach ($related as $s)
                    <a class="card card-link" href="{{ route('services.show', $s['slug']) }}">
                        <span class="icon-box">{!! svc_icon($s['slug']) !!}</span>
                        <h3 class="card-title">{{ $s['title'] }}</h3>
                        <p class="card-text">{{ $s['excerpt'] }}</p>
                        <span class="card-more">Подробнее <span class="arrow">&rarr;</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</article>
@endsection
