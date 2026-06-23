{{-- Хлебные крошки. Принимает $crumbs = [['name'=>..,'url'=>..], ...]. --}}
<nav class="crumbs container" aria-label="Хлебные крошки">
    @foreach ($crumbs as $i => $c)
        @if ($i > 0)<span class="sep">/</span>@endif
        @if ($loop->last)
            <span class="cur">{{ $c['name'] }}</span>
        @else
            <a href="{{ $c['url'] }}">{{ $c['name'] }}</a>
        @endif
    @endforeach
</nav>
