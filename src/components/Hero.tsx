"use client";

import Link from "next/link";
import { useEffect, useRef } from "react";
import { company } from "@/data/company";

const stats = [
  { value: "12+", label: "лет опыта" },
  { value: "30 мин", label: "диагностика" },
  { value: "2 года", label: "гарантия" },
  { value: "14+", label: "марок авто" },
];

export default function Hero() {
  const sceneRef = useRef<HTMLDivElement>(null);
  const rafRef = useRef<number>(0);

  // 3D-параллакс по движению мыши (только для устройств с указателем)
  useEffect(() => {
    const scene = sceneRef.current;
    if (!scene) return;
    const fine = window.matchMedia("(pointer: fine)").matches;
    if (!fine) return;

    let tx = 0, ty = 0, cx = 0, cy = 0;
    const onMove = (e: MouseEvent) => {
      const { innerWidth: w, innerHeight: h } = window;
      tx = (e.clientX / w - 0.5) * 2;
      ty = (e.clientY / h - 0.5) * 2;
    };
    const tick = () => {
      cx += (tx - cx) * 0.06;
      cy += (ty - cy) * 0.06;
      scene.style.setProperty("--rx", `${(-cy * 6).toFixed(2)}deg`);
      scene.style.setProperty("--ry", `${(cx * 8).toFixed(2)}deg`);
      scene.querySelectorAll<HTMLElement>("[data-depth]").forEach((el) => {
        const d = parseFloat(el.dataset.depth || "0");
        el.style.transform = `translate3d(${cx * d * 22}px, ${cy * d * 22}px, 0)`;
      });
      rafRef.current = requestAnimationFrame(tick);
    };
    window.addEventListener("mousemove", onMove);
    rafRef.current = requestAnimationFrame(tick);
    return () => {
      window.removeEventListener("mousemove", onMove);
      cancelAnimationFrame(rafRef.current);
    };
  }, []);

  return (
    <section className="relative overflow-hidden pt-28 pb-16 sm:pt-32 lg:pb-24">
      {/* Фоновые градиентные пятна */}
      <div className="pointer-events-none absolute inset-0 -z-10">
        <div className="absolute inset-0 bg-mesh opacity-70" />
        <div className="absolute inset-0 bg-dots opacity-40" />
        <div className="absolute left-1/2 top-0 h-[520px] w-[820px] -translate-x-1/2 rounded-full bg-brand-500/20 blur-[120px]" />
        <div data-depth="0.4" className="absolute right-[8%] top-24 h-40 w-40 rounded-full bg-accent-500/15 blur-3xl animate-floaty" />
      </div>

      <div className="container-x grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr]">
        {/* Левая колонка — оффер и воронка */}
        <div>
          <span className="eyebrow animate-fade-up">
            <span className="h-1.5 w-1.5 rounded-full bg-brand-400" />
            Автосервис в Минске · {company.address.city}
          </span>

          <h1 className="mt-5 text-4xl font-extrabold leading-[1.05] text-white sm:text-5xl lg:text-6xl">
            Ремонт авто, которому
            <span className="text-gradient"> можно доверять</span>
          </h1>

          <p className="mt-5 max-w-xl text-lg leading-relaxed text-slate-300">
            Диагностика, ремонт и обслуживание всех марок автомобилей. Честные цены,
            гарантия до 2 лет и точный расчёт стоимости до начала работ.
          </p>

          {/* Воронка: 3 пути конверсии */}
          <div className="mt-8 flex flex-wrap gap-3">
            <a href={company.phoneHref} className="btn-primary text-base">
              📞 Позвонить
            </a>
            <a href="#zayavka" className="btn-accent text-base">
              Оставить заявку
            </a>
            <a href={company.telegram} target="_blank" rel="noopener" className="btn-ghost text-base">
              ✈ Telegram
            </a>
          </div>

          <p className="mt-4 text-sm text-slate-400">
            Перезваниваем за 15 минут · работаем {company.openingHours[0].time}
          </p>

          {/* Статы */}
          <dl className="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-4">
            {stats.map((s) => (
              <div key={s.label} className="card-3d p-4">
                <dt className="text-2xl font-extrabold text-white">{s.value}</dt>
                <dd className="mt-0.5 text-xs text-slate-400">{s.label}</dd>
              </div>
            ))}
          </dl>
        </div>

        {/* Правая колонка — псевдо-3D сцена */}
        <div className="perspective">
          <div
            ref={sceneRef}
            className="preserve-3d relative mx-auto max-w-md"
            style={{ transform: "rotateX(var(--rx,0)) rotateY(var(--ry,0))" }}
          >
            {/* Главная стеклянная карточка */}
            <div className="card-3d relative overflow-hidden p-6">
              <div className="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-brand-500/30 blur-2xl" />
              <div className="flex items-center justify-between">
                <span className="chip">● Онлайн-запись</span>
                <span className="text-xs text-slate-400">Сегодня свободно</span>
              </div>

              <div className="mt-6 text-6xl">🔧</div>
              <p className="mt-4 text-lg font-bold text-white">Запишитесь на диагностику</p>
              <p className="mt-1 text-sm text-slate-400">
                Найдём причину неисправности за 30 минут и честно расскажем, что нужно.
              </p>

              <div className="mt-5 space-y-2">
                {["Точный расчёт до начала работ", "Оригинал и качественные аналоги", "Письменная гарантия"].map((t) => (
                  <div key={t} className="flex items-center gap-2 text-sm text-slate-300">
                    <span className="grid h-5 w-5 place-items-center rounded-full bg-brand-500/20 text-xs text-brand-300">✓</span>
                    {t}
                  </div>
                ))}
              </div>

              <Link href="#zayavka" className="btn-primary mt-6 w-full">Записаться онлайн</Link>
            </div>

            {/* Плавающие бейджи (глубина для параллакса) */}
            <div
              data-depth="0.9"
              className="glass absolute -left-6 top-10 hidden rounded-xl px-4 py-3 sm:block"
            >
              <p className="text-xs text-slate-400">Гарантия</p>
              <p className="text-lg font-bold text-brand-300">до 2 лет</p>
            </div>
            <div
              data-depth="1.2"
              className="glass absolute -right-4 bottom-8 hidden rounded-xl px-4 py-3 sm:block"
            >
              <p className="text-xs text-slate-400">Диагностика</p>
              <p className="text-lg font-bold text-accent-400">30 минут</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
