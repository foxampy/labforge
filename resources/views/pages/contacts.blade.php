@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<article>
    <section class="section page-head">
        <div class="container">
            <div class="reveal" style="max-width:760px">
                <p class="eyebrow">Контакты</p>
                <h1 class="heading">Как нас найти и записаться</h1>
                <p class="subheading">Запишитесь на ремонт или диагностику удобным способом — по телефону, в Telegram или через форму. Перезвоним в течение 15 минут в рабочее время.</p>
            </div>
        </div>
    </section>

    <section class="section-tight">
        <div class="container">
            <div class="grid grid-2" style="align-items:start;gap:clamp(28px,5vw,64px)">

                <div class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Контактная информация</h2>

                    <div style="margin-top:24px">
                        <div class="info-row">
                            <span class="icon-box">{!! ui_icon('location') !!}</span>
                            <div>
                                <b>Адрес</b>
                                <p>{{ lv('address.street') }} ({{ lv('address.district') }})<br>{{ lv('address.city') }}, {{ lv('address.country_name') }}</p>
                                <p style="margin-top:6px"><a href="https://maps.google.com/?q={{ lv('geo.lat') }},{{ lv('geo.lng') }}" target="_blank" rel="noopener">Открыть на карте &rarr;</a></p>
                            </div>
                        </div>

                        <div class="info-row">
                            <span class="icon-box">{!! ui_icon('phone') !!}</span>
                            <div>
                                <b>Телефон и почта</b>
                                <p><a href="{{ lv('phone_href') }}">{{ lv('phone') }}</a></p>
                                <p style="margin-top:6px"><a href="mailto:{{ lv('email') }}">{{ lv('email') }}</a></p>
                            </div>
                        </div>

                        <div class="info-row">
                            <span class="icon-box">{!! ui_icon('telegram') !!}</span>
                            <div>
                                <b>Telegram</b>
                                <p><a href="{{ lv('telegram') }}" target="_blank" rel="noopener">Написать в Telegram &rarr;</a></p>
                            </div>
                        </div>

                        <div class="info-row">
                            <span class="icon-box">{!! ui_icon('clock') !!}</span>
                            <div>
                                <b>Режим работы</b>
                                @foreach (lv('opening_hours', []) as $oh)
                                    <p>{{ $oh['days'] }}: <span style="color:var(--white)">{{ $oh['time'] }}</span></p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <section id="zayavka" class="reveal">
                    <h2 class="heading" style="font-size:clamp(1.5rem,3vw,2.1rem)">Оставить заявку</h2>
                    <p class="form-note" style="margin-top:8px">Заполните форму — заявка придёт нам в Telegram, и мы перезвоним для подтверждения.</p>
                    <div style="margin-top:18px">
                        @include('partials.lead-form')
                    </div>
                </section>

            </div>
        </div>
    </section>

    @if (!empty($bookingMethods))
    <section class="section" style="background:var(--ink-2)">
        <div class="container">
            <div class="center reveal">
                <p class="eyebrow">Способы записи</p>
                <h2 class="heading">Запишитесь, как вам удобно</h2>
            </div>
            <div class="grid grid-4 reveal" style="margin-top:36px">
                @foreach ($bookingMethods as $m)
                    <div class="card">
                        <span class="icon-box">{!! ui_icon($m['icon']) !!}</span>
                        <h3 class="card-title">{{ $m['title'] }}</h3>
                        <p class="card-text">{{ $m['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</article>
@endsection
