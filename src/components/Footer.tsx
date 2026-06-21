import Link from "next/link";
import { company } from "@/data/company";
import { services } from "@/data/services";
import { brands } from "@/data/brands";
import TelegramIcon from "./TelegramIcon";

export default function Footer() {
  const year = new Date().getFullYear();
  return (
    <footer className="relative mt-24 border-t border-white/10 bg-ink-950">
      <div className="container-x grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        {/* О компании / NAP */}
        <div>
          <Link href="/" className="flex items-center gap-2.5">
            <span className="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 font-extrabold text-ink-950">
              L
            </span>
            <span className="text-lg font-bold text-white">
              Livada<span className="text-brand-300">Center</span>
            </span>
          </Link>
          <p className="mt-4 text-sm leading-relaxed text-slate-400">
            {company.tagline}
          </p>
          <address className="mt-4 space-y-1 not-italic text-sm text-slate-400">
            <p>{company.address.street}, {company.address.city}</p>
            <p>
              <a href={company.phoneHref} className="link-muted">{company.phone}</a>
            </p>
            <p>
              <a href={`mailto:${company.email}`} className="link-muted">{company.email}</a>
            </p>
          </address>
          <div className="mt-4 flex gap-2">
            <a href={company.telegram} target="_blank" rel="noopener" className="chip hover:text-white">
              <TelegramIcon className="h-3.5 w-3.5" />
              Telegram
            </a>
            <a href={company.whatsapp} target="_blank" rel="noopener" className="chip hover:text-white">WhatsApp</a>
          </div>
        </div>

        {/* Услуги */}
        <nav aria-label="Услуги">
          <p className="mb-4 text-sm font-semibold text-white">Услуги</p>
          <ul className="space-y-2">
            {services.slice(0, 8).map((s) => (
              <li key={s.slug}>
                <Link href={`/uslugi/${s.slug}`} className="text-sm link-muted">{s.title}</Link>
              </li>
            ))}
            <li>
              <Link href="/uslugi" className="text-sm font-medium text-brand-300 hover:text-brand-200">Все услуги →</Link>
            </li>
          </ul>
        </nav>

        {/* Марки */}
        <nav aria-label="Марки авто">
          <p className="mb-4 text-sm font-semibold text-white">Марки авто</p>
          <ul className="grid grid-cols-2 gap-x-4 gap-y-2">
            {brands.slice(0, 12).map((b) => (
              <li key={b.slug}>
                <Link href={`/marki/${b.slug}`} className="text-sm link-muted">{b.name}</Link>
              </li>
            ))}
            <li className="col-span-2">
              <Link href="/marki" className="text-sm font-medium text-brand-300 hover:text-brand-200">Все марки →</Link>
            </li>
          </ul>
        </nav>

        {/* Режим работы / GEO */}
        <div>
          <p className="mb-4 text-sm font-semibold text-white">Режим работы</p>
          <ul className="space-y-1 text-sm text-slate-400">
            {company.openingHours.map((o) => (
              <li key={o.days} className="flex justify-between gap-4">
                <span>{o.days}</span>
                <span className="text-slate-300">{o.time}</span>
              </li>
            ))}
          </ul>
          <p className="mb-2 mt-5 text-sm font-semibold text-white">Обслуживаем районы</p>
          <p className="text-sm leading-relaxed text-slate-400">
            {company.areasServed.join(" · ")}
          </p>
        </div>
      </div>

      <div className="border-t border-white/10">
        <div className="container-x flex flex-col items-center justify-between gap-2 py-5 text-xs text-slate-500 sm:flex-row">
          <p>© {year} {company.legalName}. Все права защищены.</p>
          <p>Автосервис в Минске · ремонт всех марок авто</p>
        </div>
      </div>
    </footer>
  );
}
