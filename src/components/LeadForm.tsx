"use client";

import { useState } from "react";
import { services } from "@/data/services";
import { company } from "@/data/company";

type Status = "idle" | "loading" | "success" | "error";

export default function LeadForm({
  defaultService = "",
  compact = false,
}: {
  defaultService?: string;
  compact?: boolean;
}) {
  const [status, setStatus] = useState<Status>("idle");
  const [message, setMessage] = useState("");

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("loading");
    setMessage("");
    const form = e.currentTarget;
    const data = Object.fromEntries(new FormData(form).entries());

    try {
      const res = await fetch("/api/lead", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          ...data,
          source: typeof window !== "undefined" ? window.location.pathname : "",
        }),
      });
      const json = await res.json();
      if (res.ok && json.ok) {
        setStatus("success");
        form.reset();
      } else {
        setStatus("error");
        setMessage(json.message || "Не удалось отправить. Позвоните нам.");
      }
    } catch {
      setStatus("error");
      setMessage("Ошибка сети. Попробуйте ещё раз или позвоните.");
    }
  }

  if (status === "success") {
    return (
      <div className="glass rounded-2xl p-8 text-center">
        <div className="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-full bg-brand-500/20 text-3xl">
          ✓
        </div>
        <h3 className="text-xl font-bold text-white">Заявка отправлена!</h3>
        <p className="mt-2 text-sm text-slate-400">
          Мы перезвоним вам в ближайшее время. Если срочно —{" "}
          <a href={company.phoneHref} className="text-brand-300">позвоните нам</a>.
        </p>
        <button onClick={() => setStatus("idle")} className="btn-ghost mt-5">
          Отправить ещё одну
        </button>
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} className={`glass rounded-2xl ${compact ? "p-5" : "p-6 sm:p-8"}`}>
      {!compact && (
        <>
          <h3 className="text-xl font-bold text-white">Оставьте заявку</h3>
          <p className="mt-1 text-sm text-slate-400">
            Перезвоним за 15 минут, рассчитаем стоимость и запишем на удобное время.
          </p>
        </>
      )}

      {/* honeypot */}
      <input
        type="text"
        name="company_website"
        tabIndex={-1}
        autoComplete="off"
        className="hidden"
        aria-hidden="true"
      />

      <div className="mt-4 grid gap-3">
        <input
          name="name"
          required
          placeholder="Ваше имя *"
          className="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none focus:border-brand-400/50"
        />
        <input
          name="phone"
          type="tel"
          required
          placeholder="Телефон *"
          className="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none focus:border-brand-400/50"
        />
        <input
          name="car"
          placeholder="Марка и модель авто"
          className="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none focus:border-brand-400/50"
        />
        <select
          name="service"
          defaultValue={defaultService}
          className="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-brand-400/50"
        >
          <option value="" className="bg-ink-900">Выберите услугу (необязательно)</option>
          {services.map((s) => (
            <option key={s.slug} value={s.title} className="bg-ink-900">
              {s.title}
            </option>
          ))}
        </select>
        {!compact && (
          <textarea
            name="comment"
            rows={3}
            placeholder="Опишите проблему (необязательно)"
            className="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none focus:border-brand-400/50"
          />
        )}
      </div>

      {status === "error" && (
        <p className="mt-3 rounded-lg bg-red-500/10 px-3 py-2 text-sm text-red-300">{message}</p>
      )}

      <button type="submit" disabled={status === "loading"} className="btn-primary mt-4 w-full disabled:opacity-60">
        {status === "loading" ? "Отправляем…" : "Отправить заявку"}
      </button>

      <div className="mt-3 flex items-center justify-center gap-3 text-xs text-slate-500">
        <span>Или сразу:</span>
        <a href={company.phoneHref} className="text-brand-300 hover:text-brand-200">Позвонить</a>
        <span>·</span>
        <a href={company.telegram} target="_blank" rel="noopener" className="text-brand-300 hover:text-brand-200">Telegram</a>
      </div>
      <p className="mt-3 text-center text-[11px] leading-relaxed text-slate-600">
        Нажимая «Отправить», вы соглашаетесь на обработку персональных данных.
      </p>
    </form>
  );
}
