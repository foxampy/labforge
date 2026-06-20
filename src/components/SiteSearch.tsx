"use client";

import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { searchIndex } from "@/data/nav";

/** Поиск по сайту: мгновенная фильтрация услуг, марок и страниц. */
export default function SiteSearch({ compact = false }: { compact?: boolean }) {
  const [q, setQ] = useState("");
  const [open, setOpen] = useState(false);
  const boxRef = useRef<HTMLDivElement>(null);

  const results = useMemo(() => {
    const term = q.trim().toLowerCase();
    if (!term) return [];
    return searchIndex
      .filter(
        (i) =>
          i.title.toLowerCase().includes(term) ||
          i.keywords.toLowerCase().includes(term)
      )
      .slice(0, 8);
  }, [q]);

  useEffect(() => {
    const onClick = (e: MouseEvent) => {
      if (boxRef.current && !boxRef.current.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("mousedown", onClick);
    return () => document.removeEventListener("mousedown", onClick);
  }, []);

  return (
    <div ref={boxRef} className={`relative ${compact ? "w-full" : "w-full max-w-sm"}`}>
      <div className="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 focus-within:border-brand-400/50">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" className="shrink-0 text-slate-400">
          <circle cx="11" cy="11" r="7" stroke="currentColor" strokeWidth="2" />
          <path d="m20 20-3-3" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
        </svg>
        <input
          value={q}
          onChange={(e) => {
            setQ(e.target.value);
            setOpen(true);
          }}
          onFocus={() => q && setOpen(true)}
          placeholder="Поиск: услуга или марка авто…"
          className="w-full bg-transparent text-sm text-white placeholder:text-slate-500 outline-none"
          aria-label="Поиск по сайту"
        />
      </div>

      {open && results.length > 0 && (
        <div className="absolute z-50 mt-2 w-full overflow-hidden rounded-xl border border-white/10 bg-ink-900/95 shadow-card backdrop-blur-xl">
          {results.map((r) => (
            <Link
              key={r.href}
              href={r.href}
              onClick={() => {
                setOpen(false);
                setQ("");
              }}
              className="flex items-center justify-between gap-3 px-4 py-2.5 text-sm hover:bg-white/5"
            >
              <span className="text-slate-100">{r.title}</span>
              <span className="chip shrink-0">{r.type}</span>
            </Link>
          ))}
        </div>
      )}
      {open && q && results.length === 0 && (
        <div className="absolute z-50 mt-2 w-full rounded-xl border border-white/10 bg-ink-900/95 px-4 py-3 text-sm text-slate-400 backdrop-blur-xl">
          Ничего не найдено. Позвоните нам — поможем!
        </div>
      )}
    </div>
  );
}
