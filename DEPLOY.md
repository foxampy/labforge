# Деплой — LEVADA SERVICE (чистый PHP)

Сайт работает на **чистом PHP 8.x** без фреймворков, без Node.js и без сборки на
сервере. Всё, что нужно хостингу, — PHP с включённым расширением **cURL**
(для отправки заявок в Telegram). Подходит любой shared-хостинг с cPanel/Apache.

> ⚠️ **Секреты не коммитим.** Токен бота (`TELEGRAM_BOT_TOKEN`) и
> `TELEGRAM_CHAT_ID` хранятся в `.env.local` (он в `.gitignore`) либо задаются
> через `SetEnv`/панель хостинга. В git попадает только шаблон `.env.example`.

---

## Что загружать на сервер

В `public_html` (корень домена) кладём **всё, кроме dev-инструментов**:

```
✅ ЗАГРУЖАЕМ                         ❌ НЕ ЗАГРУЖАЕМ (только для разработки)
─────────────────────────────       ─────────────────────────────────────
index.php          (роутер)         node_modules/      (сборка CSS)
.htaccess                           package.json
lead.php           (форма)          tailwind.config.ts
app/               (логика+шаблоны)  assets/css/tailwind.src.css
assets/css/styles.css               .dev-router.php
assets/js/  assets/img/             src/ (удалён)
favicon.ico                         README.md / DEPLOY.md (по желанию)
robots.txt  llms.txt
.env.local         (секреты, см. ниже)
```

`assets/css/styles.css` — это **уже собранный статический CSS**. Пересобирать его
на сервере не нужно: Tailwind/Node нужны только локально, если меняешь дизайн.

---

## Шаг 1. Залить файлы

Через **File Manager** в cPanel (удобно загрузить ZIP и распаковать в
`public_html`) или по FTP/SFTP. Включи «Show Hidden Files», чтобы увидеть
`.htaccess` и `.env.local`.

Проверь, что `.htaccess` и `index.php` лежат **в корне** `public_html`.

## Шаг 2. Прописать токен Telegram

Любой из трёх способов (приоритет сверху вниз):

**A. Файл `.env.local`** (проще всего — он уже работает локально):
```
TELEGRAM_BOT_TOKEN=123456789:AA...
TELEGRAM_CHAT_ID=-1001234567890
```
`lead.php` читает его автоматически. Файл закрыт от веб-доступа в `.htaccess`.

**B. Переменные окружения** (если хостинг позволяет) — в `.htaccess` или панели:
```apache
SetEnv TELEGRAM_BOT_TOKEN 123456789:AA...
SetEnv TELEGRAM_CHAT_ID -1001234567890
```

**C. Константы прямо в `lead.php`** (если A и B недоступны) — впиши значения в
`TELEGRAM_BOT_TOKEN_FALLBACK` / `TELEGRAM_CHAT_ID_FALLBACK` вверху файла.
Этот файл с реальным токеном **не возвращай в git**.

## Шаг 3. Проверить PHP и cURL

- PHP **8.0+** (проект использует `declare(strict_types=1)` и типобезопасный код).
- Расширение **cURL** включено (на большинстве cPanel — по умолчанию).
  Проверка: временный `phpinfo.php` → секция **curl** → «cURL support: enabled».
  После проверки удали `phpinfo.php`.
- Исходящие HTTPS к `api.telegram.org` не заблокированы (редко — спроси поддержку).

## Шаг 4. Проверить сайт и форму

1. Открой домен — должна открыться главная, заработать меню, поиск, гараж-хотспоты.
2. Проверь несколько URL: `/uslugi`, `/uslugi/diagnostika`, `/marki`, `/marki/bmw`,
   `/o-nas`, `/kontakty`, `/sitemap.xml`, `/robots.txt`. Несуществующий адрес →
   страница 404.
3. Отправь тестовую заявку. При успехе: браузер получит `{"ok":true}`, а в Telegram
   придёт «🚗 Новая заявка — LEVADA SERVICE».

Если форма отвечает `not_configured` (500) — токен не задан (см. Шаг 2). Логи
ошибок PHP — в cPanel → Metrics → Errors.

---

## Локальная разработка

Запуск без хостинга (нужен только PHP в PATH):

```bash
php -S 127.0.0.1:8000 .dev-router.php
```

`.dev-router.php` повторяет логику `.htaccess`: статика отдаётся как есть,
остальное идёт в `index.php`. Открой http://127.0.0.1:8000.

### Пересборка CSS (только если меняешь дизайн)

CSS собирается Tailwind CLI из `assets/css/tailwind.src.css` в
`assets/css/styles.css`. Node нужен **только для этого шага и только локально**:

```bash
npm install            # один раз
npm run build:css      # пересобрать styles.css (минифицировано)
npm run watch:css      # авто-пересборка при правках шаблонов
```

Конфиг Tailwind (`tailwind.config.ts`) сканирует `./app/**/*.php` и `./*.php`.
После пересборки залей обновлённый `assets/css/styles.css` на сервер.
