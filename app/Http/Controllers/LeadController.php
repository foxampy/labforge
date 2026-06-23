<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Приём заявок с формы и отправка в Telegram.
 *
 * Логика повторяет исходный _ref_lead.php:
 *   - honeypot company_website непустой -> тихо ok;
 *   - name < 2 символов ИЛИ телефон < 6 цифр -> 422;
 *   - токен/chat_id из .env (TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID).
 */
class LeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // 1. Антиспам: honeypot. Бот заполнил скрытое поле -> тихо отвечаем ok.
        if (trim((string) $request->input('company_website', '')) !== '') {
            return response()->json(['ok' => true]);
        }

        // 2. Валидация имени и телефона.
        $name  = trim((string) $request->input('name', ''));
        $phone = trim((string) $request->input('phone', ''));
        $phoneDigits = preg_replace('/\D/', '', $phone) ?? '';

        if (mb_strlen($name) < 2 || strlen($phoneDigits) < 6) {
            return response()->json([
                'ok'      => false,
                'error'   => 'validation',
                'message' => 'Укажите имя и телефон',
            ], 422);
        }

        // 3. Секреты Telegram.
        $token  = (string) config('services.telegram.token', env('TELEGRAM_BOT_TOKEN', ''));
        $chatId = (string) config('services.telegram.chat_id', env('TELEGRAM_CHAT_ID', ''));

        if ($token === '' || $chatId === '') {
            Log::error('TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID не заданы');

            return response()->json([
                'ok'      => false,
                'error'   => 'not_configured',
                'message' => 'Сервер не настроен. Позвоните нам.',
            ], 500);
        }

        // 4. Сообщение (те же поля и эмодзи, что в исходнике).
        $esc = static fn (string $s): string => str_replace(['&', '<', '>'], ['&amp;', '&lt;', '&gt;'], $s);

        $lines = [
            '🚗 <b>Новая заявка — LEVADA SERVICE</b>',
            '👤 Имя: <b>' . $esc($name) . '</b>',
            '📞 Телефон: <b>' . $esc($phone) . '</b>',
        ];
        foreach (['car' => '🚙 Авто: ', 'service' => '🔧 Услуга: ', 'comment' => '💬 Комментарий: ', 'source' => '🔗 Источник: '] as $field => $label) {
            $val = trim((string) $request->input($field, ''));
            if ($val !== '') {
                $lines[] = $label . $esc($val);
            }
        }
        $lines[] = '🕒 ' . now()->setTimezone('Europe/Minsk')->format('d.m.Y, H:i:s');

        // 5. Отправка.
        try {
            $response = Http::timeout(15)->asJson()->post(
                'https://api.telegram.org/bot' . $token . '/sendMessage',
                [
                    'chat_id'                  => $chatId,
                    'text'                     => implode("\n", $lines),
                    'parse_mode'               => 'HTML',
                    'disable_web_page_preview' => true,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Telegram API exception: ' . $e->getMessage());
            $response = null;
        }

        if ($response === null || $response->failed()) {
            return response()->json([
                'ok'      => false,
                'error'   => 'telegram',
                'message' => 'Не удалось отправить. Позвоните нам.',
            ], 502);
        }

        return response()->json(['ok' => true]);
    }
}
