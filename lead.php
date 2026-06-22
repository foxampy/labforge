<?php
/**
 * lead.php — PHP-порт src/app/api/lead/route.ts для cPanel/Apache (без Node).
 *
 * Принимает заявку с формы LeadForm.tsx (JSON или form-data), проверяет honeypot
 * и валидацию, затем отправляет HTML-сообщение в Telegram через cURL.
 *
 * Поведение полностью повторяет route.ts:
 *   - honeypot company_website непустой -> {"ok":true} (тихо игнорируем ботов);
 *   - name короче 2 символов ИЛИ телефон содержит < 6 цифр -> 422;
 *   - токен/chat_id берутся из переменных окружения, иначе из констант ниже.
 */

// ВНИМАНИЕ (ПЛЕЙСХОЛДЕР): впишите токен бота и chat_id здесь, ТОЛЬКО если на
// хостинге нельзя задать переменные окружения TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID.
// НЕ коммитьте реальные значения в репозиторий.
const TELEGRAM_BOT_TOKEN_FALLBACK = ''; // например: '123456789:AA...'
const TELEGRAM_CHAT_ID_FALLBACK   = ''; // например: '-1001234567890'

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Minsk');

/**
 * Подхватываем секреты из .env.local в корне проекта, если они там есть
 * (как делал Next.js). Файл .env.local закрыт от веб-доступа в .htaccess,
 * но PHP читает его с диска. Существующие переменные окружения не трогаем —
 * приоритет у getenv()/SetEnv. Если файла нет — просто пропускаем.
 */
(static function (): void {
    foreach (['/.env.local', '/.env'] as $envFile) {
        $path = __DIR__ . $envFile;
        if (!is_file($path) || !is_readable($path)) {
            continue;
        }
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value, " \t\"'");
            if ($key !== '' && getenv($key) === false) {
                putenv("$key=$value");
            }
        }
    }
})();

/** Возвращает JSON-ответ и завершает выполнение. */
function respond(int $code, array $payload): void {
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Эскейп HTML-спецсимволов (как esc() в route.ts: & < >). */
function esc(string $s): string {
    return str_replace(['&', '<', '>'], ['&amp;', '&lt;', '&gt;'], $s);
}

// Принимаем только POST.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    respond(405, ['ok' => false, 'error' => 'method_not_allowed']);
}

// 1. Читаем тело: сначала пробуем JSON (как шлёт fetch из формы), иначе $_POST.
$raw = file_get_contents('php://input');
$body = [];
if ($raw !== false && $raw !== '') {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $body = $decoded;
    }
}
if (empty($body) && !empty($_POST)) {
    $body = $_POST;
}

$get = static function (string $key) use ($body): string {
    return isset($body[$key]) ? trim((string) $body[$key]) : '';
};

// 2. Антиспам: honeypot. Непустое company_website -> тихо отвечаем ok.
if ($get('company_website') !== '') {
    respond(200, ['ok' => true]);
}

// 3. Валидация имени и телефона (как в route.ts).
$name  = $get('name');
$phone = $get('phone');

$phoneDigits = preg_replace('/\D/', '', $phone);
if (mb_strlen($name) < 2 || strlen($phoneDigits) < 6) {
    respond(422, [
        'ok' => false,
        'error' => 'validation',
        'message' => 'Укажите имя и телефон',
    ]);
}

// 4. Токен и chat_id: переменные окружения с фолбэком на константы выше.
$token  = getenv('TELEGRAM_BOT_TOKEN') ?: TELEGRAM_BOT_TOKEN_FALLBACK;
$chatId = getenv('TELEGRAM_CHAT_ID') ?: TELEGRAM_CHAT_ID_FALLBACK;

if ($token === '' || $chatId === '') {
    error_log('TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID не заданы (env и константы пусты)');
    respond(500, [
        'ok' => false,
        'error' => 'not_configured',
        'message' => 'Сервер не настроен. Позвоните нам.',
    ]);
}

// 5. Собираем HTML-сообщение (те же поля и эмодзи, что в route.ts).
$car     = $get('car');
$service = $get('service');
$comment = $get('comment');
$source  = $get('source');

$lines = [
    '🚗 <b>Новая заявка — LEVADA SERVICE</b>',
    '👤 Имя: <b>' . esc($name) . '</b>',
    '📞 Телефон: <b>' . esc($phone) . '</b>',
];
if ($car !== '')     { $lines[] = '🚙 Авто: ' . esc($car); }
if ($service !== '') { $lines[] = '🔧 Услуга: ' . esc($service); }
if ($comment !== '') { $lines[] = '💬 Комментарий: ' . esc($comment); }
if ($source !== '')  { $lines[] = '🔗 Источник: ' . esc($source); }
$lines[] = '🕒 ' . date('d.m.Y, H:i:s');

$text = implode("\n", $lines);

// 6. Отправляем в Telegram через cURL POST.
$apiUrl = 'https://api.telegram.org/bot' . $token . '/sendMessage';
$postData = json_encode([
    'chat_id' => $chatId,
    'text' => $text,
    'parse_mode' => 'HTML',
    'disable_web_page_preview' => true,
], JSON_UNESCAPED_UNICODE);

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode < 200 || $httpCode >= 300) {
    error_log('Telegram API error: ' . ($curlErr !== '' ? $curlErr : (string) $response));
    respond(502, [
        'ok' => false,
        'error' => 'telegram',
        'message' => 'Не удалось отправить. Позвоните нам.',
    ]);
}

respond(200, ['ok' => true]);
