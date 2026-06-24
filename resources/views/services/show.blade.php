@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<article>
    <section class="section page-head{{ !empty($slides) ? ' page-head--gallery' : '' }}">
        <div class="container">
            @if (!empty($slides))
                <div class="svc-hero-gallery">
                    <div class="svc-hero-gallery__panel glass reveal">
                        <p class="eyebrow"><span class="icon-box" style="display:inline-grid;vertical-align:middle;width:38px;height:38px;margin-right:10px">{!! svc_icon($service['slug'], 'h-5 w-5') !!}</span>Услуга</p>
                        <h1 class="heading" style="margin-top:14px">{{ $service['h1'] ?? $service['title'] }}</h1>
                        <p class="subheading svc-hero__desc svc-hero__desc--in">{{ $service['intro'] ?? $service['excerpt'] }}</p>
                        <div class="hero__actions" style="justify-content:flex-start;margin-top:26px">
                            <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Записаться на услугу</a>
                            <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
                        </div>
                    </div>

                    <div class="svc-hero-gallery__media">
                        <div class="svc-slider" data-slider>
                            <div class="svc-slider__track" data-slider-track>
                                @foreach ($slides as $sl)
                                    @php
                                        $hasCar = ! $sl['generic'] && $sl['car'] !== '';
                                        $leadParams = $hasCar ? ['service' => $service['title'], 'car' => $sl['car']] : ['service' => $service['title']];
                                    @endphp
                                    <a class="svc-slide" href="{{ route('contacts', $leadParams) }}#zayavka" aria-label="Записаться на антикор{{ $hasCar ? ' — ' . $sl['car'] : '' }}">
                                        <img class="svc-slide__img" src="{{ $sl['src'] }}" alt="Антикор{{ $hasCar ? ' — ' . $sl['car'] : '' }}" loading="lazy" decoding="async">
                                        @if ($hasCar)
                                            <span class="svc-slide__car">{{ $sl['car'] }}</span>
                                        @endif
                                        <span class="svc-slide__cta">Записаться
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <button class="svc-slider__btn svc-slider__btn--prev" type="button" aria-label="Предыдущее фото" data-slider-prev>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                            </button>
                            <button class="svc-slider__btn svc-slider__btn--next" type="button" aria-label="Следующее фото" data-slider-next>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                            </button>
                        </div>
                    </div>
                    <p class="subheading svc-hero__desc svc-hero__desc--out">{{ $service['intro'] ?? $service['excerpt'] }}</p>
                </div>
                @else
                <div class="svc-hero">
                    <div class="svc-hero__text reveal">
                        <p class="eyebrow"><span class="icon-box" style="display:inline-grid;vertical-align:middle;width:38px;height:38px;margin-right:10px">{!! svc_icon($service['slug'], 'h-5 w-5') !!}</span>Услуга</p>
                        <h1 class="heading" style="margin-top:14px">{{ $service['h1'] ?? $service['title'] }}</h1>
                        <p class="subheading">{{ $service['intro'] ?? $service['excerpt'] }}</p>
                        <div class="hero__actions" style="justify-content:flex-start;margin-top:26px">
                            <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Записаться на услугу</a>
                            <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
                        </div>
                    </div>
                </div>
                @endif
        </div>
    </section>

    @if (!empty($service['warranty']))
    @php $w = $service['warranty']; @endphp
    <section class="section warranty-section">
        <div class="container">
            <div class="warranty-section__grid">
                <aside class="warranty-card reveal" aria-label="Гарантийный сертификат LEVADA SERVICE">
                    <div class="warranty-card__head">
                        <div class="brand">
                            <img class="brand__logo" src="{{ asset('assets/img/logo.png') }}" alt="LEVADA SERVICE" width="46" height="46" decoding="async">
                            <span class="brand__name">LEVADA<small>SERVICE</small></span>
                        </div>
                        @if (!empty($w['tag']))
                            <span class="warranty-card__tag">{{ $w['tag'] }}</span>
                        @endif
                    </div>

                    <div class="warranty-card__lead">
                        <span class="warranty-card__seal" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/>
                                <path d="M9 11.6l2.1 2.1 4-4.4"/>
                            </svg>
                        </span>
                        <div>
                            @if (!empty($w['term']))
                                <p class="warranty-card__term">{{ $w['term'] }}</p>
                            @endif
                            <h2 class="warranty-card__title">{{ $w['title'] }}</h2>
                        </div>
                    </div>

                    @if (!empty($w['points']))
                    <ul class="warranty-card__points">
                        @foreach ($w['points'] as $pt)
                            <li>{{ $pt }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if (!empty($w['rows']))
                    <dl class="warranty-card__rows">
                        @foreach ($w['rows'] as $r)
                            <div class="warranty-card__row">
                                <dt>{{ $r['label'] }}</dt>
                                <dd class="@empty($r['value']) is-blank @endempty">{{ $r['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    @endif

                    @if (!empty($w['terms']))
                    <div class="warranty-card__terms">
                        <span class="warranty-card__terms-label">Срок гарантии, мес.</span>
                        <div class="warranty-card__chips" role="group" aria-label="Срок гарантии в месяцах">
                            @foreach ($w['terms'] as $t)
                                <span class="warranty-chip">{{ $t }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="warranty-card__foot">
                        <span>Подпись и печать</span>
                        <span class="warranty-card__no">№&nbsp;_______</span>
                    </div>
                </aside>

                <div class="warranty-info reveal">
                    @if (!empty($w['heading']))
                        <h2 class="heading warranty-info__title">{{ $w['heading'] }}</h2>
                    @endif
                    @if (!empty($w['lead']))
                        <p class="warranty-info__lead">{{ $w['lead'] }}</p>
                    @endif
                    @foreach (($w['body'] ?? []) as $para)
                        <p class="warranty-info__text">{{ $para }}</p>
                    @endforeach
                    @if (!empty($w['features']))
                    <ul class="warranty-info__features">
                        @foreach ($w['features'] as $f)
                            <li>{{ $f }}</li>
                        @endforeach
                    </ul>
                    @endif
                    @if (!empty($w['disclaimer']))
                        <p class="warranty-info__note">* {{ $w['disclaimer'] }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

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
                        <h3 class="car-group__title">{{ $seg['title'] }}</h3>
                        <ul class="car-chips">
                            @foreach ($seg['cars'] as $car)
                                <li class="car-chip">{{ $car }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <div class="car-note reveal">
                <div>
                    <h3 class="car-note__title">Не нашли свою модель?</h3>
                    <p class="car-note__text">Обрабатываем любые автомобили — от бюджетных седанов до больших внедорожников, китайских новинок и электромобилей. Оставьте заявку или позвоните: рассчитаем антикор под вашу машину и назовём стоимость после осмотра.</p>
                </div>
                <div class="car-note__actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Оставить заявку</a>
                    <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
                </div>
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
