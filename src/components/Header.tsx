"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { usePathname } from "next/navigation";
import { company } from "@/data/company";
import { mainNav, serviceGroups, brandGroups } from "@/data/nav";
import SiteSearch from "./SiteSearch";
import TelegramIcon from "./TelegramIcon";

export default function Header() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [openMega, setOpenMega] = useState<null | "services" | "brands">(null);
  const pathname = usePathname();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 12);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setMobileOpen(false);
    setOpenMega(null);
  }, [pathname]);

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
        scrolled ? "border-b border-white/10 bg-ink-950/85 backdrop-blur-xl" : "bg-transparent"
      }`}
    >
      <div className="container-x flex h-16 items-center justify-between gap-4">
        {/* Лого */}
        <Link href="/" className="flex items-center gap-2.5 font-display">
          <span className="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 text-ink-950 font-extrabold shadow-glow">
            L
          </span>
          <span className="text-lg font-bold tracking-tight text-white">
            Livada<span className="text-brand-300">Center</span>
          </span>
        </Link>

        {/* Десктоп-навигация + мега-меню в единой hover-зоне (без «провала» курсора) */}
        <div
          className="hidden h-16 items-center lg:flex"
          onMouseLeave={() => setOpenMega(null)}
        >
          <nav className="flex items-center gap-1">
            {mainNav.map((item) => (
              <div key={item.href} onMouseEnter={() => setOpenMega(item.mega ?? null)}>
                <Link
                  href={item.href}
                  className={`rounded-lg px-3 py-2 text-sm font-medium transition-colors ${
                    pathname === item.href ? "text-white" : "text-slate-300 hover:text-white"
                  }`}
                >
                  {item.label}
                  {item.mega && <span className="ml-1 text-xs text-slate-500">▾</span>}
                </Link>
              </div>
            ))}
          </nav>

          {/* Выпадающее мега-меню (матовая подложка) */}
          {openMega && (
            <div className="absolute inset-x-0 top-16 hidden lg:block">
              <div className="container-x">
                <div className="rounded-2xl border border-white/10 bg-ink-900 p-6 shadow-card">
                  {openMega === "services" ? (
                    <div className="grid grid-cols-3 gap-6">
                      {serviceGroups.map((g) => (
                        <div key={g.title}>
                          <p className="mb-3 text-xs font-semibold uppercase tracking-wider text-brand-300">
                            {g.title}
                          </p>
                          <ul className="space-y-1">
                            {g.items.map((s) => (
                              <li key={s.slug}>
                                <Link
                                  href={`/uslugi/${s.slug}`}
                                  className="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white"
                                >
                                  <span className="text-base">{s.icon}</span>
                                  {s.title}
                                </Link>
                              </li>
                            ))}
                          </ul>
                        </div>
                      ))}
                      <div className="col-span-3 mt-2 border-t border-white/10 pt-3">
                        <Link href="/uslugi" className="text-sm font-semibold text-brand-300 hover:text-brand-200">
                          Все услуги и прайс →
                        </Link>
                      </div>
                    </div>
                  ) : (
                    <div className="grid grid-cols-2 gap-6">
                      {brandGroups.map((g) => (
                        <div key={g.title}>
                          <p className="mb-3 text-xs font-semibold uppercase tracking-wider text-brand-300">
                            {g.title}
                          </p>
                          <div className="grid grid-cols-2 gap-1">
                            {g.items.map((b) => (
                              <Link
                                key={b.slug}
                                href={`/marki/${b.slug}`}
                                className="rounded-lg px-2 py-1.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white"
                              >
                                {b.name}
                              </Link>
                            ))}
                          </div>
                        </div>
                      ))}
                      <div className="col-span-2 mt-2 border-t border-white/10 pt-3">
                        <Link href="/marki" className="text-sm font-semibold text-brand-300 hover:text-brand-200">
                          Все марки авто →
                        </Link>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          )}
        </div>

        {/* Правый блок */}
        <div className="hidden items-center gap-3 lg:flex">
          <div className="w-56">
            <SiteSearch compact />
          </div>
          <a href={company.phoneHref} className="btn-primary !px-4 !py-2 text-sm">
            Позвонить
          </a>
        </div>

        {/* Бургер */}
        <button
          onClick={() => setMobileOpen((v) => !v)}
          className="grid h-10 w-10 place-items-center rounded-lg border border-white/10 bg-white/5 lg:hidden"
          aria-label="Меню"
          aria-expanded={mobileOpen}
        >
          <span className="relative block h-4 w-5">
            <span className={`absolute left-0 h-0.5 w-5 bg-white transition-all ${mobileOpen ? "top-2 rotate-45" : "top-0"}`} />
            <span className={`absolute left-0 top-2 h-0.5 w-5 bg-white transition-all ${mobileOpen ? "opacity-0" : ""}`} />
            <span className={`absolute left-0 h-0.5 w-5 bg-white transition-all ${mobileOpen ? "top-2 -rotate-45" : "top-4"}`} />
          </span>
        </button>
      </div>

      {/* Мобильное меню */}
      {mobileOpen && (
        <div className="lg:hidden">
          <div className="container-x max-h-[calc(100vh-4rem)] overflow-y-auto border-t border-white/10 bg-ink-950/98 pb-8 pt-4 backdrop-blur-xl">
            <div className="mb-4">
              <SiteSearch compact />
            </div>
            <nav className="space-y-1">
              {mainNav.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className="block rounded-lg px-3 py-2.5 text-base font-medium text-slate-200 hover:bg-white/5"
                >
                  {item.label}
                </Link>
              ))}
            </nav>

            <p className="mb-2 mt-5 px-3 text-xs font-semibold uppercase tracking-wider text-brand-300">
              Услуги
            </p>
            <div className="grid grid-cols-2 gap-1">
              {serviceGroups.flatMap((g) => g.items).map((s) => (
                <Link
                  key={s.slug}
                  href={`/uslugi/${s.slug}`}
                  className="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-white/5"
                >
                  <span>{s.icon}</span> {s.title}
                </Link>
              ))}
            </div>

            <p className="mb-2 mt-5 px-3 text-xs font-semibold uppercase tracking-wider text-brand-300">
              Марки авто
            </p>
            <div className="grid grid-cols-3 gap-1">
              {brandGroups.flatMap((g) => g.items).map((b) => (
                <Link
                  key={b.slug}
                  href={`/marki/${b.slug}`}
                  className="rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-white/5"
                >
                  {b.name}
                </Link>
              ))}
            </div>

            <div className="mt-6 flex flex-col gap-2">
              <a href={company.phoneHref} className="btn-primary w-full">
                Позвонить {company.phone}
              </a>
              <a href={company.telegram} target="_blank" rel="noopener" className="btn-ghost w-full">
                <TelegramIcon className="h-4 w-4" />
                Написать в Telegram
              </a>
            </div>
          </div>
        </div>
      )}
    </header>
  );
}
