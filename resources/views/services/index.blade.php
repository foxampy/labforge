@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<section class="section page-head">
    <div class="container">
        <div class="reveal">
            <p class="eyebrow">Услуги и цены</p>
            <h1 class="heading">Услуги автосервиса в Минске</h1>
            <p class="subheading">Полный спектр работ по китайским авто, электромобилям и всем маркам. Цены ориентировочные в BYN — точную стоимость называем после диагностики.</p>
        </div>

        @foreach ($groups as $title => $items)
            @continue(empty($items))
            <div class="reveal" style="margin-top:54px">
                <p class="eyebrow eyebrow--solo">{{ $title }}</p>
                <div class="grid grid-3" style="margin-top:22px">
                    @foreach ($items as $s)
                        <a class="card card-link" href="{{ route('services.show', $s['slug']) }}">
                            <span class="icon-box">{!! svc_icon($s['slug']) !!}</span>
                            <h2 class="card-title">{{ $s['title'] }}</h2>
                            <p class="card-text">{{ $s['excerpt'] }}</p>
                            @if (!empty($s['prices'][0]))
                                <p class="card-text" style="color:var(--white);margin-top:12px">
                                    от {{ $s['prices'][0]['price'] }}
                                </p>
                            @endif
                            <span class="card-more">Подробнее и цены <span class="arrow">&rarr;</span></span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="section-tight">
    <div class="container">
        <div class="cta-band reveal">
            <h2 class="heading">Не нашли нужную услугу?</h2>
            <p class="subheading" style="margin-inline:auto">Позвоните или напишите — подскажем, поможем и запишем на удобное время.</p>
            <div class="hero__actions" style="margin-top:24px">
                <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Оставить заявку</a>
                <a class="btn btn-ghost btn-lg" href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
