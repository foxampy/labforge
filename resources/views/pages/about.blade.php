@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<article>
    <section class="section page-head">
        <div class="container">
            <div class="reveal" style="max-width:820px">
                <p class="eyebrow">О компании</p>
                <h1 class="heading">{{ lv('name') }} — автосервис в Минске</h1>
                <p class="subheading">С {{ lv('founded_year') }} года ремонтируем и обслуживаем автомобили в Минске. Специализируемся на китайских авто и электромобилях, работаем со всеми марками — честно, с письменной гарантией и прозрачными ценами.</p>
                <div class="hero__actions" style="justify-content:flex-start;margin-top:26px">
                    <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Записаться на ремонт</a>
                    <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
                </div>
            </div>

            @if (!empty($achievements))
            <div class="hero__stats reveal" style="margin-top:48px;backdrop-filter:none">
                @foreach ($achievements as $a)
                    <div class="hero__stat">
                        <b>{{ $a['value'] }}</b>
                        <span>{{ $a['label'] }}</span>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <section class="section-tight">
        <div class="container">
            <div class="grid grid-2" style="align-items:start">
                @if (!empty($guarantees))
                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Наши гарантии</h2>
                    <ul class="check-list" style="margin-top:18px">
                        @foreach ($guarantees as $g)
                            <li>{{ $g }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (!empty($equipment))
                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Оборудование и оснащение</h2>
                    <ul class="check-list" style="margin-top:18px">
                        @foreach ($equipment as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if (!empty($process))
    <section class="section">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Как мы работаем</p>
                <h2 class="heading">Процесс ремонта — от заявки до выдачи</h2>
            </div>
            <div class="grid grid-2 reveal" style="margin-top:40px;align-items:start">
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
    @endif

    @if (!empty($faq))
    <section class="section" style="background:var(--ink-2)">
        <div class="container" style="max-width:840px">
            <div class="center reveal">
                <p class="eyebrow">Вопрос–ответ</p>
                <h2 class="heading">Частые вопросы о сервисе</h2>
            </div>
            <div class="faq reveal" style="margin-top:32px">
                @foreach ($faq as $qa)
                    <div class="faq__item">
                        <button class="faq__q" type="button">{{ $qa['q'] }} <span class="pm" aria-hidden="true"></span></button>
                        <div class="faq__a"><div>{{ $qa['a'] }}</div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="section-tight">
        <div class="container">
            <div class="cta-band reveal">
                <h2 class="heading">Готовы записать ваш автомобиль</h2>
                <p class="subheading" style="margin-inline:auto">Диагностика, ремонт и ТО любых марок. Позвоните или оставьте заявку — перезвоним в течение 15 минут.</p>
                <div class="hero__actions" style="margin-top:24px">
                    <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Оставить заявку</a>
                    <a class="btn btn-ghost btn-lg" href="{{ route('services.index') }}">Услуги и цены</a>
                </div>
            </div>
        </div>
    </section>
</article>
@endsection
