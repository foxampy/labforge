# Деплой — LEVADA SERVICE (Laravel)

Сайт переписан на **Laravel** (PHP 8.3+). Фронтенд — **Blade + обычный CSS/JS**,
**без Node.js, npm и сборки**. База данных **не нужна**: весь контент лежит в
конфигах и PHP-массивах, сессии и кэш — файловые.

---

## 1. Требования к хостингу

- **PHP 8.3+** с расширениями: `curl` (заявки в Telegram), `mbstring`, `openssl`,
  `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`.
- **Composer** (на сервере или собрать локально и залить `vendor/`).
- Веб-сервер (Apache/Nginx/LiteSpeed) с возможностью указать корнем папку `public/`.
- БД и Node.js **не требуются**.

---

## 2. Установка

```bash
# 1. Зависимости (без dev-пакетов, с оптимизацией автозагрузки)
composer install --no-dev --optimize-autoloader

# 2. Окружение
cp .env.example .env
php artisan key:generate
```

### Заполнить `.env`

```dotenv
APP_NAME="LEVADA SERVICE"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://livada-center.by

# Заявки с формы -> Telegram (см. раздел 4)
TELEGRAM_BOT_TOKEN=123456:AA...        # токен бота от @BotFather
TELEGRAM_CHAT_ID=-1001234567890        # id чата/канала, куда слать заявки
```

> `SESSION_DRIVER=file` и `CACHE_STORE=file` уже стоят по умолчанию — менять не нужно.
> Строки `DB_*` можно не трогать: база не используется.

### Заменить плейсхолдеры контактов

Реальные телефон/мессенджеры лежат в **`config/levada.php`** — замените:

```php
'phone'      => '+375 (XX) XXX-XX-XX',
'phone_href' => 'tel:+375XXXXXXXXX',
'whatsapp'   => 'https://wa.me/375XXXXXXXXX',
'telegram'   => 'https://t.me/ВашБот',
'email'      => 'info@livada-center.by',
```

---

## 3. Прод-оптимизация и запуск

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- **Корень сайта (docroot)** должен указывать на папку **`public/`**.
- Папки `storage/` и `bootstrap/cache/` должны быть **доступны на запись**.
- При смене контента/конфигов сбросьте кэш: `php artisan optimize:clear`.

### Shared-хостинг / cPanel

Если docroot нельзя переназначить на `public/`:
1. Содержимое `public/` положить в `public_html/`.
2. В `public_html/index.php` поправить пути на `require __DIR__.'/../<папка_проекта>/vendor/autoload.php'`
   и `bootstrap/app.php` соответственно.
3. Сам проект (всё кроме `public/`) разместить **выше** `public_html/`.

### Локальная проверка

```bash
php artisan serve     # http://127.0.0.1:8000
```

---

## 4. Telegram-бот (форма заявки)

1. Создать бота у **@BotFather** → получить `TELEGRAM_BOT_TOKEN`.
2. Узнать `TELEGRAM_CHAT_ID`: написать боту, затем открыть
   `https://api.telegram.org/bot<ТОКЕН>/getUpdates` и взять `chat.id`
   (для группы добавьте бота в группу и возьмите отрицательный id).
3. Вписать оба значения в `.env`.

Логика приёма: honeypot-антиспам → валидация имени/телефона → отправка в Telegram
(`app/Http/Controllers/LeadController.php`). Без токенов форма корректно отвечает
«Сервер не настроен» (HTTP 500) и пишет причину в `storage/logs`.

---

## 5. Где править контент

| Что | Файл |
|-----|------|
| Контакты, адрес, режим, УТП, статистика, FAQ «О нас» | `config/levada.php` |
| Услуги (11 шт.: тексты, цены, работы, FAQ) | `app/Data/services.php` |
| Марки (43 шт.: тексты, неисправности, модели) | `app/Data/brands.php` |
| Соответствие марка → файл логотипа | `app/Data/brand-logos.php` |
| Шаблоны страниц | `resources/views/` |
| Стили / скрипты | `public/assets/css/site.css`, `public/assets/js/site.js` |
| Логотип, hero, favicon, OG | `public/assets/img/` |

SEO/JSON-LD (`AutoRepair`, `Service`, `FAQPage`, `BreadcrumbList`, `WebSite`),
а также `sitemap.xml`, `robots.txt`, `llms.txt` генерируются автоматически
(`app/Support/Seo.php`, `app/Http/Controllers/SeoController.php`).

---

## 6. Иконки и OG-картинка (опционально, dev)

`/.gen-icons.php` генерирует favicon/apple-touch/og.jpg из **оригинального**
логотипа `public/assets/img/logo.png` (без перекраски). Требует расширение `gd`
и шрифты Windows. Запуск: `php .gen-icons.php`. Готовые файлы уже лежат в репозитории,
повторно запускать не нужно.
