"use client";

import Link from "next/link";
import { useMemo, useState } from "react";
import { brands } from "@/data/brands";
import {
  carZones,
  categoryColor,
  estimatePrice,
  yearOptions,
  type WorkType,
  type CarZone,
} from "@/data/pricing";

// Позиции горячих точек на схеме (viewBox 0 0 800 360)
const HOTSPOTS: Record<string, { x: number; y: number }> = {
  engine: { x: 150, y: 232 },
  service: { x: 245, y: 256 },
  transmission: { x: 360, y: 272 },
  suspension: { x: 480, y: 286 },
  electric: { x: 320, y: 182 },
  ac: { x: 430, y: 172 },
  body: { x: 520, y: 150 },
  wheels: { x: 205, y: 300 },
  brakes: { x: 600, y: 300 },
};

export default function CarStudio() {
  const [brandSlug, setBrandSlug] = useState(brands[0].slug);
  const [model, setModel] = useState(brands[0].popularModels[0]);
  const [year, setYear] = useState(yearOptions[3]);
  const [workType, setWorkType] = useState<WorkType>("replace");
  const [selectedId, setSelectedId] = useState<string>("engine");
  const [zoomed, setZoomed] = useState(false);

  const brand = useMemo(() => brands.find((b) => b.slug === brandSlug)!, [brandSlug]);
  const zone = useMemo(
    () => carZones.find((z) => z.id === selectedId)!,
    [selectedId]
  );
  const estimate = useMemo(
    () => estimatePrice({ basePrice: zone.basePrice, brandSlug, year, workType }),
    [zone, brandSlug, year, workType]
  );

  const selectZone = (z: CarZone) => {
    setSelectedId(z.id);
    setZoomed(true);
  };

  const spot = HOTSPOTS[selectedId];
  const scale = 1.7;
  const carTransform = zoomed && spot
    ? `translate(${spot.x - spot.x * scale}px, ${spot.y - spot.y * scale}px) scale(${scale})`
    : "translate(0px,0px) scale(1)";

  return (
    <section id="konfigurator" className="container-x mt-24 scroll-mt-24">
      <div className="mx-auto max-w-2xl text-center">
        <span className="eyebrow">Интерактивно</span>
        <h2 className="mt-4 text-3xl font-extrabold text-white sm:text-4xl">
          Схема авто и расчёт стоимости
        </h2>
        <p className="mt-3 text-slate-400">
          Нажмите на узел автомобиля, чтобы увидеть детали, или соберите расчёт: марка,
          год, ремонт или замена — стоимость посчитается автоматически.
        </p>
      </div>

      <div className="mt-10 grid gap-6 lg:grid-cols-[1.3fr_1fr]">
        {/* СХЕМА */}
        <div className="glass relative overflow-hidden rounded-3xl p-4 sm:p-6">
          <div className="pointer-events-none absolute inset-0 bg-mesh opacity-30" />
          <div className="relative">
            <svg viewBox="0 0 800 360" className="w-full" role="img" aria-label="Интерактивная схема автомобиля">
              <defs>
                <linearGradient id="glass-body" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#bcd2ff" stopOpacity="0.22" />
                  <stop offset="100%" stopColor="#2f74ff" stopOpacity="0.10" />
                </linearGradient>
                <linearGradient id="glass-window" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#ffffff" stopOpacity="0.30" />
                  <stop offset="100%" stopColor="#9fc0ff" stopOpacity="0.10" />
                </linearGradient>
              </defs>

              {/* группа авто (зумируется) */}
              <g style={{ transform: carTransform, transition: "transform 0.6s cubic-bezier(0.22,1,0.36,1)" }}>
                {/* тень/дорога */}
                <ellipse cx="400" cy="332" rx="320" ry="14" fill="#000" opacity="0.35" />

                {/* кузов (полупрозрачный схематичный) */}
                <path
                  d="M70,250 C70,222 92,214 120,212 L235,208 L300,135 C308,124 320,118 334,118 L486,118 C500,118 512,124 520,135 L560,196 L700,206 C724,208 740,222 740,248 L740,266 C740,274 734,280 726,280 L96,280 C82,280 70,268 70,250 Z"
                  fill="url(#glass-body)"
                  stroke="#7fa8ff"
                  strokeOpacity="0.55"
                  strokeWidth="2"
                />
                {/* окна */}
                <path d="M318,140 L420,140 L420,196 L300,196 Z" fill="url(#glass-window)" stroke="#9fc0ff" strokeOpacity="0.5" strokeWidth="1.5" />
                <path d="M438,140 L480,140 C490,140 498,146 506,156 L536,196 L438,196 Z" fill="url(#glass-window)" stroke="#9fc0ff" strokeOpacity="0.5" strokeWidth="1.5" />

                {/* колёса */}
                {[205, 600].map((cx) => (
                  <g key={cx}>
                    <circle cx={cx} cy="300" r="50" fill="#0b1020" stroke="#7fa8ff" strokeOpacity="0.5" strokeWidth="3" />
                    <circle cx={cx} cy="300" r="22" fill="none" stroke="#7fa8ff" strokeOpacity="0.45" strokeWidth="3" />
                  </g>
                ))}

                {/* горячие точки */}
                {carZones.map((z) => {
                  const p = HOTSPOTS[z.id];
                  const color = categoryColor[z.category].hex;
                  const active = z.id === selectedId;
                  return (
                    <g
                      key={z.id}
                      transform={`translate(${p.x},${p.y})`}
                      className="cursor-pointer"
                      onClick={() => selectZone(z)}
                      role="button"
                      aria-label={z.title}
                    >
                      {active && <circle r="22" fill={color} opacity="0.18" />}
                      <circle r="14" fill={color} fillOpacity={active ? 0.9 : 0.55} stroke="#fff" strokeOpacity="0.8" strokeWidth="2" />
                      <circle r="5" fill="#fff" />
                      <title>{z.title}</title>
                    </g>
                  );
                })}
              </g>
            </svg>

            {/* Управление зумом */}
            <div className="mt-2 flex items-center justify-between">
              <div className="flex flex-wrap gap-3 text-xs">
                {Object.values(categoryColor).map((c) => (
                  <span key={c.label} className="inline-flex items-center gap-1.5 text-slate-300">
                    <span className="h-3 w-3 rounded-full" style={{ background: c.hex }} />
                    {c.label}
                  </span>
                ))}
              </div>
              <button
                onClick={() => setZoomed((v) => !v)}
                className="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-slate-200 hover:bg-white/10"
              >
                {zoomed ? "Отдалить" : "Приблизить узел"}
              </button>
            </div>

            {/* Быстрый выбор зоны (чипы) */}
            <div className="mt-3 flex flex-wrap gap-2">
              {carZones.map((z) => (
                <button
                  key={z.id}
                  onClick={() => selectZone(z)}
                  className={`rounded-full border px-3 py-1 text-xs transition-colors ${
                    z.id === selectedId
                      ? "border-transparent text-white"
                      : "border-white/10 text-slate-300 hover:bg-white/5"
                  }`}
                  style={z.id === selectedId ? { background: categoryColor[z.category].hex } : undefined}
                >
                  {z.title}
                </button>
              ))}
            </div>
          </div>
        </div>

        {/* ОКНО ДЕТАЛЕЙ + КОНСТРУКТОР */}
        <div className="glass rounded-3xl p-6">
          {/* Деталь выбранной зоны */}
          <div className="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
            <div className="flex items-center gap-3">
              <span
                className="grid h-10 w-10 place-items-center rounded-xl text-lg font-bold text-ink-950"
                style={{ background: categoryColor[zone.category].hex }}
              >
                ●
              </span>
              <div>
                <h3 className="text-lg font-bold text-white">{zone.title}</h3>
                <p className="text-xs text-slate-400">{categoryColor[zone.category].label}</p>
              </div>
            </div>
            <p className="mt-3 text-sm leading-relaxed text-slate-300">{zone.short}</p>
            <Link
              href={`/uslugi/${zone.serviceSlug}`}
              className="mt-3 inline-flex text-sm font-semibold text-brand-300 hover:text-brand-200"
            >
              Подробно об услуге →
            </Link>
          </div>

          {/* Конструктор */}
          <div className="mt-5 space-y-3">
            <div className="grid grid-cols-2 gap-3">
              <label className="block">
                <span className="mb-1 block text-xs text-slate-400">Марка</span>
                <select
                  value={brandSlug}
                  onChange={(e) => {
                    const b = brands.find((x) => x.slug === e.target.value)!;
                    setBrandSlug(b.slug);
                    setModel(b.popularModels[0]);
                  }}
                  className="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white outline-none focus:border-brand-400/50"
                >
                  {brands.map((b) => (
                    <option key={b.slug} value={b.slug} className="bg-ink-900">{b.name}</option>
                  ))}
                </select>
              </label>
              <label className="block">
                <span className="mb-1 block text-xs text-slate-400">Модель</span>
                <select
                  value={model}
                  onChange={(e) => setModel(e.target.value)}
                  className="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white outline-none focus:border-brand-400/50"
                >
                  {brand.popularModels.map((m) => (
                    <option key={m} value={m} className="bg-ink-900">{m}</option>
                  ))}
                </select>
              </label>
            </div>

            <div className="grid grid-cols-2 gap-3">
              <label className="block">
                <span className="mb-1 block text-xs text-slate-400">Год выпуска</span>
                <select
                  value={year}
                  onChange={(e) => setYear(Number(e.target.value))}
                  className="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white outline-none focus:border-brand-400/50"
                >
                  {yearOptions.map((y) => (
                    <option key={y} value={y} className="bg-ink-900">{y}</option>
                  ))}
                </select>
              </label>
              <div className="block">
                <span className="mb-1 block text-xs text-slate-400">Тип работ</span>
                <div className="grid grid-cols-2 overflow-hidden rounded-xl border border-white/10">
                  {(["repair", "replace"] as WorkType[]).map((t) => (
                    <button
                      key={t}
                      onClick={() => setWorkType(t)}
                      className={`py-2.5 text-sm font-medium transition-colors ${
                        workType === t ? "bg-brand-500 text-white" : "bg-white/5 text-slate-300"
                      }`}
                    >
                      {t === "repair" ? "Ремонт" : "Замена"}
                    </button>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Расчёт */}
          <div className="mt-5 rounded-2xl border border-brand-400/30 bg-brand-500/10 p-5 text-center">
            <p className="text-xs uppercase tracking-wider text-brand-300">Ориентировочная стоимость</p>
            <p className="mt-1 text-3xl font-extrabold text-white">
              {estimate.min}–{estimate.max} <span className="text-lg text-slate-400">BYN</span>
            </p>
            <p className="mt-1 text-xs text-slate-400">
              {brand.name} {model}, {year} · {workType === "repair" ? "ремонт" : "замена"}
            </p>
          </div>

          <p className="mt-2 text-[11px] leading-relaxed text-slate-500">
            Расчёт предварительный. Точную стоимость определяем после диагностики.
          </p>

          <a href="#zayavka" className="btn-primary mt-4 w-full">
            Записаться на «{zone.title}»
          </a>
        </div>
      </div>
    </section>
  );
}
