@extends('layouts.app')

@section('content')
@include('partials.crumbs')

<section class="section page-head">
    <div class="container">
        <div class="reveal">
            <p class="eyebrow">Марки авто</p>
            <h1 class="heading">Ремонт и обслуживание всех марок</h1>
            <p class="subheading">Специализация — китайские автомобили и электромобили. Также обслуживаем европейские и азиатские бренды. Выберите марку, чтобы узнать о типичных неисправностях и услугах.</p>
        </div>

        @foreach ($groups as $g)
            @continue(empty($g['brands']))
            <div class="reveal" style="margin-top:50px">
                <p class="eyebrow eyebrow--solo">{{ $g['title'] }} <span style="color:var(--muted-2);font-family:var(--font-body);letter-spacing:0">· {{ count($g['brands']) }}</span></p>
                <div class="grid grid-auto-sm" style="margin-top:22px">
                    @foreach ($g['brands'] as $b)
                        <a class="card card-link brand-card" href="{{ route('brands.show', $b['slug']) }}" title="{{ $b['name'] }}">
                            <span class="brand-emblem">{!! brand_logo_img($b, 'brand-logo') !!}</span>
                            <span class="brand-name">{{ $b['name'] }}</span>
                            <span class="brand-country">{{ $b['country'] }}</span>
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
            <h2 class="heading">Вашей марки нет в списке?</h2>
            <p class="subheading" style="margin-inline:auto">Мы работаем со всеми марками авто. Позвоните или оставьте заявку — поможем с диагностикой и ремонтом.</p>
            <div class="hero__actions" style="margin-top:24px">
                <a class="btn btn-primary btn-lg" href="{{ route('contacts') }}#zayavka">Оставить заявку</a>
                <a class="btn btn-ghost btn-lg" href="{{ route('services.index') }}">Все услуги</a>
            </div>
        </div>
    </div>
</section>
@endsection
