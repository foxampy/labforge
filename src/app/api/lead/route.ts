import { NextResponse } from "next/server";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type LeadBody = {
  name?: string;
  phone?: string;
  car?: string;
  service?: string;
  comment?: string;
  source?: string;
  // honeypot — должно быть пустым (заполняют боты)
  company_website?: string;
};

const esc = (s: string) =>
  s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

export async function POST(req: Request) {
  let body: LeadBody;
  try {
    body = await req.json();
  } catch {
    return NextResponse.json({ ok: false, error: "bad_request" }, { status: 400 });
  }

  // Антиспам: honeypot
  if (body.company_website) {
    return NextResponse.json({ ok: true }); // тихо игнорируем ботов
  }

  const name = (body.name || "").trim();
  const phone = (body.phone || "").trim();

  if (name.length < 2 || phone.replace(/\D/g, "").length < 6) {
    return NextResponse.json(
      { ok: false, error: "validation", message: "Укажите имя и телефон" },
      { status: 422 }
    );
  }

  const token = process.env.TELEGRAM_BOT_TOKEN;
  const chatId = process.env.TELEGRAM_CHAT_ID;

  if (!token || !chatId) {
    console.error("TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID не заданы в .env");
    return NextResponse.json(
      { ok: false, error: "not_configured", message: "Сервер не настроен. Позвоните нам." },
      { status: 500 }
    );
  }

  const lines = [
    "🚗 <b>Новая заявка — Livada Center</b>",
    `👤 Имя: <b>${esc(name)}</b>`,
    `📞 Телефон: <b>${esc(phone)}</b>`,
    body.car ? `🚙 Авто: ${esc(body.car)}` : "",
    body.service ? `🔧 Услуга: ${esc(body.service)}` : "",
    body.comment ? `💬 Комментарий: ${esc(body.comment)}` : "",
    body.source ? `🔗 Источник: ${esc(body.source)}` : "",
    `🕒 ${new Date().toLocaleString("ru-RU", { timeZone: "Europe/Minsk" })}`,
  ].filter(Boolean);

  try {
    const tgRes = await fetch(`https://api.telegram.org/bot${token}/sendMessage`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        chat_id: chatId,
        text: lines.join("\n"),
        parse_mode: "HTML",
        disable_web_page_preview: true,
      }),
      cache: "no-store",
    });

    if (!tgRes.ok) {
      const detail = await tgRes.text();
      console.error("Telegram API error:", detail);
      return NextResponse.json(
        { ok: false, error: "telegram", message: "Не удалось отправить. Позвоните нам." },
        { status: 502 }
      );
    }

    return NextResponse.json({ ok: true });
  } catch (e) {
    console.error("Lead send failed:", e);
    return NextResponse.json(
      { ok: false, error: "network", message: "Ошибка отправки. Попробуйте позже." },
      { status: 500 }
    );
  }
}
