import Link from "next/link";

export function Breadcrumbs({ items }: { items: { name: string; href: string }[] }) {
  return (
    <nav aria-label="Хлебные крошки" className="container-x pt-24 sm:pt-28">
      <ol className="flex flex-wrap items-center gap-1.5 text-sm text-slate-400">
        {items.map((it, i) => (
          <li key={it.href} className="flex items-center gap-1.5">
            {i > 0 && <span className="text-slate-600">/</span>}
            {i < items.length - 1 ? (
              <Link href={it.href} className="hover:text-white">{it.name}</Link>
            ) : (
              <span className="text-slate-200">{it.name}</span>
            )}
          </li>
        ))}
      </ol>
    </nav>
  );
}

export function SectionHeading({
  eyebrow,
  title,
  subtitle,
  center,
}: {
  eyebrow?: string;
  title: string;
  subtitle?: string;
  center?: boolean;
}) {
  return (
    <div className={center ? "mx-auto max-w-2xl text-center" : "max-w-2xl"}>
      {eyebrow && <span className="eyebrow">{eyebrow}</span>}
      <h2 className="mt-4 text-3xl font-extrabold text-white sm:text-4xl">{title}</h2>
      {subtitle && <p className="mt-3 text-base leading-relaxed text-slate-400">{subtitle}</p>}
    </div>
  );
}

export function CtaBand() {
  return (
    <section className="container-x my-20">
      <div className="glass relative overflow-hidden rounded-3xl px-6 py-12 text-center sm:px-12">
        <div className="pointer-events-none absolute inset-0 bg-mesh opacity-50" />
        <div className="relative">
          <h2 className="text-3xl font-extrabold text-white sm:text-4xl">
            Нужен ремонт или диагностика?
          </h2>
          <p className="mx-auto mt-3 max-w-xl text-slate-300">
            Оставьте заявку — перезвоним за 15 минут, рассчитаем стоимость и запишем на удобное время.
          </p>
          <div className="mt-7 flex flex-wrap justify-center gap-3">
            <Link href="/#zayavka" className="btn-primary text-base">Оставить заявку</Link>
            <Link href="/kontakty" className="btn-ghost text-base">Как нас найти</Link>
          </div>
        </div>
      </div>
    </section>
  );
}

export function ServiceCard({
  slug,
  title,
  icon,
  excerpt,
}: {
  slug: string;
  title: string;
  icon: string;
  excerpt: string;
}) {
  return (
    <Link href={`/uslugi/${slug}`} className="card-3d group block p-6">
      <div className="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/15 text-2xl">{icon}</div>
      <h3 className="mt-4 text-lg font-bold text-white group-hover:text-brand-200">{title}</h3>
      <p className="mt-2 text-sm leading-relaxed text-slate-400">{excerpt}</p>
      <span className="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-300">
        Подробнее
        <span className="transition-transform group-hover:translate-x-1">→</span>
      </span>
    </Link>
  );
}

export function BrandCard({ slug, name, country }: { slug: string; name: string; country: string }) {
  return (
    <Link href={`/marki/${slug}`} className="card-3d group flex flex-col items-center p-5 text-center">
      <div className="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-white/10 to-white/0 text-xl font-extrabold text-white">
        {name.slice(0, 2)}
      </div>
      <h3 className="mt-3 font-bold text-white group-hover:text-brand-200">{name}</h3>
      <p className="text-xs text-slate-500">{country}</p>
    </Link>
  );
}
