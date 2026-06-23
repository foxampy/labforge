{{-- Форма заявки -> Telegram. JS перехватывает submit (см. site.js: leadForm). --}}
<form id="lead-form" class="lead" action="{{ route('lead.store') }}" method="post" novalidate>
    @csrf
    <input type="hidden" name="source" value="{{ url()->current() }}">

    {{-- honeypot: люди его не видят, боты заполняют --}}
    <div class="hp" aria-hidden="true">
        <label>Не заполняйте это поле
            <input type="text" name="company_website" tabindex="-1" autocomplete="off">
        </label>
    </div>

    <div class="field-row">
        <div class="field">
            <label for="lf-name">Имя *</label>
            <input id="lf-name" name="name" type="text" required autocomplete="name" placeholder="Как к вам обращаться">
        </div>
        <div class="field">
            <label for="lf-phone">Телефон *</label>
            <input id="lf-phone" name="phone" type="tel" required autocomplete="tel" placeholder="+375 (__) ___-__-__">
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <label for="lf-car">Марка / модель авто</label>
            <input id="lf-car" name="car" type="text" placeholder="Напр. Geely Coolray">
        </div>
        <div class="field">
            <label for="lf-service">Услуга</label>
            <select id="lf-service" name="service">
                <option value="">— выберите услугу —</option>
                @foreach (\App\Support\Content::services() as $s)
                    <option value="{{ $s['title'] }}">{{ $s['title'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="field">
        <label for="lf-comment">Комментарий</label>
        <textarea id="lf-comment" name="comment" rows="3" placeholder="Опишите проблему или вопрос"></textarea>
    </div>

    <p id="lead-status" class="form-msg" role="status" aria-live="polite"></p>

    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:16px">Отправить заявку</button>
    <p class="form-note">Нажимая «Отправить», вы соглашаетесь на обработку данных. Перезвоним в течение 15 минут в рабочее время.</p>
</form>
