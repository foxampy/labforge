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
