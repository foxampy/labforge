"use client";

import { useEffect, useRef, useState } from "react";
import { company } from "@/data/company";
import TelegramIcon from "./TelegramIcon";

/* ------------------------------------------------------------------ */
/*  Иконки в едином стиле бренда (линейные, currentColor).            */
/*  Логотип Telegram — отдельный залитый глиф (TelegramIcon).         */
/* ------------------------------------------------------------------ */

type IconProps = { className?: string };
const stroke = {
  fill: "none" as const,
  stroke: "currentColor",
  strokeWidth: 1.7,
  strokeLinecap: "round" as const,
  strokeLinejoin: "round" as const,
};

function PhoneIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z" />
    </svg>
  );
}

function ArrowRightIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <path d="M5 12h13" />
      <path d="M12 6l6 6-6 6" />
    </svg>
  );
}

function ShieldCheckIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
      <path d="M9 12l2 2 4-4.5" />
    </svg>
  );
}

function ClockIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <circle cx="12" cy="12" r="9" />
      <path d="M12 7.5V12l3 2" />
    </svg>
  );
}

function BoltIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <path d="M13 2L4.5 13.5H11l-1 8.5 8.5-11.5H12l1-8.5z" />
    </svg>
  );
}

function TagIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <path d="M20.6 13.4l-7.2 7.2a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z" />
      <path d="M7.5 7.5h.01" />
    </svg>
  );
}

/* ------------------------------------------------------------------ */
/*  Данные.                                                          */
/* ------------------------------------------------------------------ */

const stats = [
  { value: 12, suffix: "+", label: "лет опыта" },
  { value: 30, suffix: " мин", label: "диагностика" },
  { value: 2, suffix: " года", label: "гарантия" },
  { value: 14, suffix: "+", label: "марок авто" },
];

const usps = [
  { Icon: BoltIcon, title: "Китайские авто и EV", note: "профильные спецы" },
  { Icon: ShieldCheckIcon, title: "Гарантия на работы", note: "письменно" },
  { Icon: TagIcon, title: "Честные цены", note: "расчёт до работ" },
];

/* ------------------------------------------------------------------ */
/*  Счётчик с анимацией (запуск при появлении, reduced-motion safe).  */
/* ------------------------------------------------------------------ */

function CountUp({ value, suffix = "" }: { value: number; suffix?: string }) {
  const [n, setN] = useState(0);
  const ref = useRef<HTMLSpanElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (reduce) {
      setN(value);
      return;
    }

    let raf = 0;
    let started = false;
    const run = () => {
      const start = performance.now();
      const dur = 1100;
      const tick = (t: number) => {
        const p = Math.min((t - start) / dur, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        setN(Math.round(eased * value));
        if (p < 1) raf = requestAnimationFrame(tick);
      };
      raf = requestAnimationFrame(tick);
    };

    const io = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && !started) {
          started = true;
          run();
          io.disconnect();
        }
      },
      { threshold: 0.4 }
    );
    io.observe(el);

    return () => {
      io.disconnect();
      cancelAnimationFrame(raf);
    };
  }, [value]);

  return (
    <span ref={ref}>
      {n}
      {suffix}
    </span>
  );
}

/* ------------------------------------------------------------------ */
/*  Hero: контент интегрирован в сцену hero.png.                      */
/*  Mobile-first — на мобиле один столбец; на ПК зоны:               */
/*  главное справа, доп-инфо слева, статы снизу.                     */
/* ------------------------------------------------------------------ */

export default function Hero() {
  const sceneRef = useRef<HTMLElement>(null);
  const rafRef = useRef<number>(0);

  // Лёгкий параллакс по курсору (только pointer:fine + без reduced-motion)
  useEffect(() => {
    const scene = sceneRef.current;
    if (!scene) return;
    const fine = window.matchMedia("(pointer: fine)").matches;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (!fine || reduce) return;

    let tx = 0,
      ty = 0,
      cx = 0,
      cy = 0;

    const onMove = (e: MouseEvent) => {
      const r = scene.getBoundingClientRect();
      tx = ((e.clientX - r.left) / r.width - 0.5) * 2;
      ty = ((e.clientY - r.top) / r.height - 0.5) * 2;
    };
    const tick = () => {
      cx += (tx - cx) * 0.06;
      cy += (ty - cy) * 0.06;
      scene.querySelectorAll<HTMLElement>("[data-depth]").forEach((el) => {
        const d = parseFloat(el.dataset.depth || "0");
        el.style.transform = `translate3d(${(cx * d * 26).toFixed(2)}px, ${(cy * d * 26).toFixed(2)}px, 0)`;
      });
      rafRef.current = requestAnimationFrame(tick);
    };

    scene.addEventListener("mousemove", onMove);
    rafRef.current = requestAnimationFrame(tick);
    return () => {
      scene.removeEventListener("mousemove", onMove);
      cancelAnimationFrame(rafRef.current);
    };
  }, []);

  return (
    <section
      ref={sceneRef}
      className="relative isolate min-h-[100svh] overflow-hidden"
    >
      {/* ── Фоновая сцена ──────────────────────────────────────────── */}
      <div data-depth="-0.1" className="absolute inset-0 -z-20">
        <img
          src="/hero.png"
          alt="Сервисный бокс LEVADA CENTER: X-ray диагностика и ремонт автомобилей в неоновом свете"
          className="h-full w-full scale-[1.1] object-cover object-[center_34%]"
          fetchPriority="high"
          decoding="async"
        />
      </div>

      {/* ── Скримы под читаемость (зоны: низ, верх, лево, право) ───── */}
      <div className="pointer-events-none absolute inset-0 -z-10">
        {/* низ — посадка на тёмную базу + место под статы */}
        <div className="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/55 to-ink-950/5" />
        {/* верх — подложка под прозрачный хедер с меню над авто */}
        <div className="absolute inset-x-0 top-0 h-36 bg-gradient-to-b from-ink-950/85 to-transparent" />
        {/* лево — под HUD-бейджи */}
        <div className="absolute inset-y-0 left-0 w-2/3 bg-gradient-to-r from-ink-950/85 via-ink-950/25 to-transparent" />
        {/* право — под главную панель (на ПК) */}
        <div className="absolute inset-y-0 right-0 hidden w-2/3 bg-gradient-to-l from-ink-950/90 via-ink-950/30 to-transparent lg:block" />
        {/* мобильное общее затемнение — текст не глушится картинкой */}
        <div className="absolute inset-0 bg-ink-950/50 lg:bg-transparent" />
        {/* техно-сетка + неоновое свечение бренда */}
        <div className="absolute inset-0 bg-tech-grid opacity-[0.14]" />
        <div className="absolute right-[6%] top-1/3 hidden h-[360px] w-[460px] rounded-full bg-brand-500/15 blur-[130px] lg:block" />
      </div>

      {/* ── Контент: flow на мобиле, зоны на ПК ───────────────────── */}
      <div className="container-x relative z-10 flex min-h-[100svh] flex-col justify-center gap-9 py-28 lg:block lg:py-0">
        {/* ГЛАВНЫЙ ОФФЕР — один экземпляр: мобиле в потоке, на ПК справа */}
        <div className="max-w-xl lg:absolute lg:right-0 lg:top-1/2 lg:w-[44%] lg:max-w-lg lg:-translate-y-1/2 lg:rounded-2xl lg:border lg:border-white/10 lg:bg-ink-950/55 lg:p-8 lg:shadow-card lg:backdrop-blur-md">
          <span className="eyebrow animate-fade-up">
            <BoltIcon className="h-3.5 w-3.5" />
            Китайские авто · электромобили · все марки
          </span>

          <h1
            className="mt-5 text-4xl font-extrabold leading-[1.05] text-white drop-shadow-[0_2px_24px_rgba(0,0,0,0.6)] sm:text-5xl lg:text-4xl xl:text-5xl animate-fade-up"
            style={{ animationDelay: "60ms" }}
          >
            Ремонт авто, которому
            <span className="text-gradient"> можно доверять</span>
          </h1>

          <p
            className="mt-5 text-lg leading-relaxed text-slate-200 drop-shadow-[0_1px_12px_rgba(0,0,0,0.55)] lg:text-base xl:text-lg animate-fade-up"
            style={{ animationDelay: "120ms" }}
          >
            Диагностика, ремонт и обслуживание авто в Минске. Честные
            цены, гарантия до 2 лет и точный расчёт стоимости до начала работ.
          </p>

          {/* Воронка: 3 пути конверсии */}
          <div
            className="mt-7 flex flex-wrap gap-3 animate-fade-up"
            style={{ animationDelay: "180ms" }}
          >
            <a href={company.phoneHref} className="btn-primary text-base">
              <PhoneIcon className="h-[18px] w-[18px]" />
              Позвонить
            </a>
            <a href="#zayavka" className="btn-accent text-base">
              Оставить заявку
              <ArrowRightIcon className="h-[18px] w-[18px]" />
            </a>
            <a
              href={company.telegram}
              target="_blank"
              rel="noopener"
              className="btn-ghost text-base"
            >
              <TelegramIcon className="h-[18px] w-[18px]" />
              Telegram
            </a>
          </div>

          <p
            className="mt-4 flex items-center gap-2 text-sm text-slate-300 animate-fade-up"
            style={{ animationDelay: "220ms" }}
          >
            <span className="relative flex h-2 w-2">
              <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400/70" />
              <span className="relative inline-flex h-2 w-2 rounded-full bg-brand-400" />
            </span>
            Перезваниваем за 15 минут · работаем {company.openingHours[0].time}
          </p>
        </div>

        {/* ДОП-ИНФО СЛЕВА — HUD-бейджи (только ПК) */}
        <div
          data-depth="0.5"
          className="hidden lg:absolute lg:left-0 lg:top-1/2 lg:block lg:w-60 lg:-translate-y-1/2 lg:space-y-3"
        >
          {usps.map(({ Icon, title, note }) => (
            <div
              key={title}
              className="glass flex items-center gap-3 rounded-xl px-4 py-3"
            >
              <span className="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-500/20 text-brand-300">
                <Icon className="h-5 w-5" />
              </span>
              <div className="min-w-0">
                <p className="truncate text-sm font-semibold text-white">{title}</p>
                <p className="truncate text-xs text-slate-400">{note}</p>
              </div>
            </div>
          ))}
        </div>

        {/* СТАТЫ СНИЗУ — на полу сцены (на ПК — нижняя строка) */}
        <dl className="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:absolute lg:inset-x-0 lg:bottom-7 lg:flex lg:gap-3">
          {stats.map((s) => (
            <div
              key={s.label}
              className="glass rounded-xl px-4 py-3 lg:flex-1"
            >
              <dt className="text-2xl font-extrabold text-white">
                <CountUp value={s.value} suffix={s.suffix} />
              </dt>
              <dd className="mt-0.5 text-xs text-slate-400">{s.label}</dd>
            </div>
          ))}
        </dl>
      </div>
    </section>
  );
}
