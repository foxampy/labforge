@php
    use App\Support\Seo;
    $meta   = $meta   ?? Seo::meta();
    $jsonld = $jsonld ?? [];
@endphp
<!doctype html>
<html lang="ru" class="js-off">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0b">

    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="robots" content="{{ $meta['robots'] }}">
    <link rel="canonical" href="{{ $meta['canonical'] }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="{{ $meta['type'] }}">
    <meta property="og:site_name" content="{{ $meta['site_name'] }}">
    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    <meta property="og:locale" content="{{ $meta['locale'] }}">
    <meta property="og:image" content="{{ $meta['image'] }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['title'] }}">
    <meta name="twitter:description" content="{{ $meta['description'] }}">
    <meta name="twitter:image" content="{{ $meta['image'] }}">

    {{-- Иконки --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">

    {{-- Шрифты: Inter (текст) + Anton/Oswald (дисплей) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">

    {{-- Снимаем js-off до отрисовки, чтобы reveal не «моргал» --}}
    <script>document.documentElement.classList.remove('js-off');</script>

    {{-- JSON-LD (schema.org) --}}
    {!! json_ld(...$jsonld) !!}
</head>
<body>
    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/js/site.js') }}" defer></script>
</body>
</html>
