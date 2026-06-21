"use client";

import { useEffect, useRef, useState } from "react";
import { company } from "@/data/company";
import TelegramIcon from "./TelegramIcon";

/* ------------------------------------------------------------------ */
/*  Иконки в едином стиле бренда (линейные, currentColor).            */
/*  Logo Telegram — отдельный залитый глиф (TelegramIcon).            */
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

function ChevronDownIcon({ className = "h-4 w-4" }: IconProps) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={className} {...stroke}>
      <path d="M6 9l6 6 6-6" />
    </svg>
  );
}

/* ------------------------------------------------------------------ */
/*  Счётчик с анимацией (уважает prefers-reduced-motion).            */
/* ------------------------------------------------------------------ */

const stats = [
  { value: 12, suffix: "+", label: "лет опыта" },
  { value: 30, suffix: " мин", label: "диагностика" },
  { value: 2, suffix: " года", label: "гарантия" },
  { value: 14, suffix: "+", label: "марок авто" },
];

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

    // запускаем счётчик, когда блок попадает во вьюпорт
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
/*  Hero: кинематографичный фон hero.png + наш UX/UI поверх.          */
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
      className="relative isolate flex min-h-[92vh] items-center overflow-hidden"
    >
      {/* ── Фоновое изображение (кинематографичный гараж) ───────────── */}
      <div data-depth="-0.12" className="absolute inset-0 -z-20">
        <img
          src="/hero.png"
          alt="Сервисный бокс LEVADA CENTER: диагностика и ремонт автомобилей в неоновом свете"
          className="h-full w-full scale-[1.12] object-cover object-[center_42%]"
          fetchPriority="high"
          decoding="async"
        />
      </div>

      {/* ── Скримы для читаемости текста поверх насыщенной картинки ── */}
      <div className="pointer-events-none absolute inset-0 -z-10">
        {/* слева → направо: затемняем зону под текст */}
        <div className="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/85 to-transparent" />
        {/* снизу вверх: посадка на тёмную базу страницы */}
        <div className="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/55 to-transparent" />
        {/* сверху: подложка под прозрачный хедер */}
        <div className="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-ink-950/85 to-transparent" />
        {/* на мобильных усиливаем общий скрим (картинка не глушит текст) */}
        <div className="absolute inset-0 bg-ink-950/45 sm:bg-ink-950/20 lg:bg-transparent" />
        {/* техно-сетка + неоновое свечение бренда */}
        <div className="absolute inset-0 bg-tech-grid opacity-[0.18]" />
        <div className="absolute -left-32 top-1/3 h-[420px] w-[620px] rounded-full bg-brand-500/20 blur-[130px]" />
      </div>

      {/* ── Контент ──────────────────────────────────────────────── */}
      <div className="container-x relative py-28 sm:py-32">
        <div className="max-w-2xl">
          <span className="eyebrow animate-fade-up">
            <BoltIcon className="h-3.5 w-3.5" />
            Китайские авто · электромобили · все марки
          </span>

          <h1
            className="mt-5 text-4xl font-extrabold leading-[1.05] text-white drop-shadow-[0_2px_24px_rgba(0,0,0,0.55)] sm:text-5xl lg:text-6xl animate-fade-up"
            style={{ animationDelay: "60ms" }}
          >
            Ремонт авто, которому
            <span className="text-gradient"> можно доверять</span>
          </h1>

          <p
            className="mt-5 max-w-xl text-lg leading-relaxed text-slate-200 drop-shadow-[0_1px_12px_rgba(0,0,0,0.5)] animate-fade-up"
            style={{ animationDelay: "120ms" }}
          >
            Диагностика, ремонт и обслуживание в {company.address.city}. Честные цены,
            гарантия до 2 лет и точный расчёт стоимости до начала работ.
          </p>

          {/* Воронка: 3 пути конверсии */}
          <div
            className="mt-8 flex flex-wrap gap-3 animate-fade-up"
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

          {/* Статы с анимированными счётчиками */}
          <dl
            className="mt-10 grid max-w-xl grid-cols-2 gap-3 sm:grid-cols-4 animate-fade-up"
            style={{ animationDelay: "280ms" }}
          >
            {stats.map((s) => (
              <div key={s.label} className="glass rounded-xl px-4 py-3">
                <dt className="text-2xl font-extrabold text-white">
                  <CountUp value={s.value} suffix={s.suffix} />
                </dt>
                <dd className="mt-0.5 text-xs text-slate-400">{s.label}</dd>
              </div>
            ))}
          </dl>
        </div>

        {/* Плавающие бейджи поверх машины (десктоп, с параллаксом) */}
        <div
          data-depth="0.55"
          className="glass pointer-events-none absolute right-[6%] top-[22%] hidden items-center gap-3 rounded-2xl px-4 py-3 lg:flex"
        >
          <span className="grid h-9 w-9 place-items-center rounded-xl bg-brand-500/20 text-brand-300">
            <ShieldCheckIcon className="h-5 w-5" />
          </span>
          <div>
            <p className="text-[11px] uppercase tracking-wider text-slate-400">Гарантия</p>
            <p className="text-base font-bold text-white">до 2 лет</p>
          </div>
        </div>

        <div
          data-depth="0.8"
          className="glass pointer-events-none absolute bottom-[16%] right-[14%] hidden items-center gap-3 rounded-2xl px-4 py-3 lg:flex"
        >
          <span className="grid h-9 w-9 place-items-center rounded-xl bg-accent-500/20 text-accent-400">
            <ClockIcon className="h-5 w-5" />
          </span>
          <div>
            <p className="text-[11px] uppercase tracking-wider text-slate-400">Диагностика</p>
            <p className="text-base font-bold text-white">30 минут</p>
          </div>
        </div>
      </div>

      {/* Индикатор скролла */}
      <a
        href="#zayavka"
        aria-label="Прокрутить вниз к форме заявки"
        className="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-1 text-slate-400 transition-colors hover:text-white sm:flex"
      >
        <span className="text-[11px] uppercase tracking-widest">Листайте</span>
        <ChevronDownIcon className="h-5 w-5 animate-floaty" />
      </a>
    </section>
  );
}
