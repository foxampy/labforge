import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import { Breadcrumbs, CtaBand, ServiceCard } from "@/components/ui";
import { JsonLd } from "@/components/JsonLd";
import LeadForm from "@/components/LeadForm";
import { brands, getBrand, brandSlugs } from "@/data/brands";
import { services } from "@/data/services";
import { company } from "@/data/company";
import { pageMeta, breadcrumbJsonLd, serviceJsonLd } from "@/lib/seo";

export function generateStaticParams() {
  return brandSlugs.map((slug) => ({ slug }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const b = getBrand(slug);
  if (!b) return {};
  return pageMeta({
    title: `Ремонт ${b.name} в Минске — сервис ${b.name} | ${company.name}`,
    description: `Ремонт и обслуживание ${b.name} в Минске: диагностика, ремонт двигателя, КПП, ходовой, ТО. Знаем особенности ${b.name}, дилерское оборудование, гарантия. Запись онлайн.`,
    path: `/marki/${b.slug}`,
    keywords: b.keywords,
  });
}

export default async function BrandDetail({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const b = getBrand(slug);
  if (!b) notFound();

  const others = brands.filter((x) => x.slug !== b.slug);
  const path = `/marki/${b.slug}`;

  return (
    <>
      <Breadcrumbs
        items={[
          { name: "Главная", href: "/" },
          { name: "Марки авто", href: "/marki" },
          { name: b.name, href: path },
        ]}
      />

      <article className="container-x mt-8">
        <div className="grid gap-10 lg:grid-cols-[1.6fr_1fr]">
          <div>
            <div className="flex items-center gap-4">
              <span className="grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-white/10 to-white/0 text-2xl font-extrabold text-white">
                {b.name.slice(0, 2)}
              </span>
              <div>
                <span className="chip">{b.country}</span>
              </div>
            </div>
            <h1 className="mt-5 text-3xl font-extrabold text-white sm:text-4xl">
              Ремонт {b.name} в Минске
            </h1>
            <p className="mt-4 text-lg leading-relaxed text-slate-300">{b.intro}</p>

            <h2 className="mt-10 text-2xl font-bold text-white">Частые неисправности {b.name}</h2>
            <ul className="mt-5 grid gap-2 sm:grid-cols-2">
              {b.commonIssues.map((w) => (
                <li key={w} className="flex items-start gap-2 rounded-xl border border-white/5 bg-white/[0.03] p-3 text-sm text-slate-300">
                  <span className="mt-0.5 text-accent-500">!</span> {w}
                </li>
              ))}
            </ul>

            <h2 className="mt-10 text-2xl font-bold text-white">Популярные модели</h2>
            <div className="mt-4 flex flex-wrap gap-2">
              {b.popularModels.map((m) => (
                <span key={m} className="chip">{b.name} {m}</span>
              ))}
            </div>

            <h2 className="mt-10 text-2xl font-bold text-white">Услуги для {b.name}</h2>
            <div className="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
              {services.slice(0, 6).map((s) => (
                <ServiceCard key={s.slug} slug={s.slug} title={s.title} icon={s.icon} excerpt={s.excerpt} />
              ))}
            </div>
          </div>

          <aside className="lg:sticky lg:top-24 lg:self-start">
            <LeadForm defaultService={`Ремонт ${b.name}`} />
          </aside>
        </div>

        <div className="mt-16">
          <h2 className="text-xl font-bold text-white">Ремонт других марок</h2>
          <div className="mt-5 flex flex-wrap gap-2">
            {others.map((o) => (
              <Link key={o.slug} href={`/marki/${o.slug}`} className="chip hover:text-white">
                {o.name}
              </Link>
            ))}
          </div>
        </div>
      </article>

      <CtaBand />

      <JsonLd
        data={[
          serviceJsonLd({
            title: `Ремонт ${b.name} в Минске`,
            description: `Ремонт и обслуживание ${b.name} всех моделей.`,
            path,
          }),
          breadcrumbJsonLd([
            { name: "Главная", path: "/" },
            { name: "Марки авто", path: "/marki" },
            { name: b.name, path },
          ]),
        ]}
      />
    </>
  );
}
