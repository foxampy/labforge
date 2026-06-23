{{-- Подвал: бренд, соцсети, навигация, контакты, режим работы. --}}
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand">
                    <img class="brand__logo" src="{{ asset('assets/img/logo.png') }}" alt="LEVADA SERVICE" width="46" height="46" decoding="async">
                    <span class="brand__name">LEVADA<small>SERVICE</small></span>
                </a>
                <p>{{ lv('tagline') }}</p>
                <div class="socials">
                    <a href="{{ lv('telegram') }}" target="_blank" rel="noopener" aria-label="Telegram">{!! ui_icon('telegram') !!}</a>
                    <a href="{{ lv('whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-6c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.2 0-.4 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3c-.2.3-.9.9-.9 2.2s.9 2.5 1 2.7c.2.2 1.8 2.8 4.4 3.9 1.6.7 2.2.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.2-.5-.3Z"/></svg>
                    </a>
                    @if (lv('social.instagram'))
                    <a href="{{ lv('social.instagram') }}" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.8.07 1.2.05 1.8.25 2.2.42.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.17.4.37 1 .42 2.2.06 1.2.07 1.6.07 4.8s0 3.6-.07 4.8c-.05 1.2-.25 1.8-.42 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.17-1 .37-2.2.42-1.2.06-1.6.07-4.8.07s-3.6 0-4.8-.07c-1.2-.05-1.8-.25-2.2-.42-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.17-.4-.37-1-.42-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.8c.05-1.2.25-1.8.42-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.17 1-.37 2.2-.42C8.4 2.2 8.8 2.2 12 2.2Zm0 1.8c-3.1 0-3.5 0-4.7.07-1.1.05-1.7.24-2.1.4-.5.2-.9.43-1.3.83-.4.4-.63.8-.83 1.3-.16.4-.35 1-.4 2.1C2.6 9.5 2.6 9.9 2.6 13s0 3.5.07 4.7c.05 1.1.24 1.7.4 2.1.2.5.43.9.83 1.3.4.4.8.63 1.3.83.4.16 1 .35 2.1.4 1.2.07 1.6.07 4.7.07s3.5 0 4.7-.07c1.1-.05 1.7-.24 2.1-.4.5-.2.9-.43 1.3-.83.4-.4.63-.8.83-1.3.16-.4.35-1 .4-2.1.07-1.2.07-1.6.07-4.7s0-3.5-.07-4.7c-.05-1.1-.24-1.7-.4-2.1-.2-.5-.43-.9-.83-1.3-.4-.4-.8-.63-1.3-.83-.4-.16-1-.35-2.1-.4C15.5 4 15.1 4 12 4Zm0 3.06A4.94 4.94 0 1 1 7.06 12 4.94 4.94 0 0 1 12 7.06Zm0 8.14A3.2 3.2 0 1 0 8.8 12a3.2 3.2 0 0 0 3.2 3.2Zm5.13-8.34a1.15 1.15 0 1 1-1.15-1.15 1.15 1.15 0 0 1 1.15 1.15Z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            <nav class="footer-col" aria-label="Услуги">
                <h4>Услуги</h4>
                @foreach (\App\Support\Content::pillarServices() as $s)
                    <a href="{{ route('services.show', $s['slug']) }}">{{ $s['title'] }}</a>
                @endforeach
                <a href="{{ route('services.index') }}">Все услуги и цены &rarr;</a>
            </nav>

            <nav class="footer-col" aria-label="Разделы">
                <h4>Разделы</h4>
                <a href="{{ route('brands.index') }}">Марки авто</a>
                <a href="{{ route('about') }}">О нас</a>
                <a href="{{ route('contacts') }}">Контакты</a>
                <a href="{{ route('contacts') }}#zayavka">Оставить заявку</a>
            </nav>

            <div class="footer-col">
                <h4>Контакты</h4>
                <a href="{{ lv('phone_href') }}">{{ lv('phone') }}</a>
                <a href="mailto:{{ lv('email') }}">{{ lv('email') }}</a>
                <a href="{{ lv('telegram') }}" target="_blank" rel="noopener">Telegram</a>
                <p style="color:var(--muted);padding-top:8px">{{ lv('address.street') }}, {{ lv('address.city') }}</p>
                <h4 style="margin-top:18px">Режим работы</h4>
                @foreach (lv('opening_hours', []) as $oh)
                    <p style="color:var(--muted)">{{ $oh['days'] }}: <span style="color:var(--text)">{{ $oh['time'] }}</span></p>
                @endforeach
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ lv('legal_name') }}. Все права защищены.</p>
            <p>Автосервис в {{ lv('address.city') }} &middot; ремонт всех марок авто</p>
        </div>
    </div>
</footer>
