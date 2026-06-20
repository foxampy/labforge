import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import { Breadcrumbs, CtaBand } from "@/components/ui";
import { JsonLd } from "@/components/JsonLd";
import LeadForm from "@/components/LeadForm";
import { services, getService, serviceSlugs } from "@/data/services";
import { company } from "@/data/company";
import {
  pageMeta,
  breadcrumbJsonLd,
  serviceJsonLd,
  faqJsonLd,
} from "@/lib/seo";

export function generateStaticParams() {
  return serviceSlugs.map((slug) => ({ slug }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const s = getService(slug);
  if (!s) return {};
  return pageMeta({
    title: s.metaTitle,
    description: s.metaDescription,
    path: `/uslugi/${s.slug}`,
    keywords: s.keywords,
  });
}

export default async function ServiceDetail({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const s = getService(slug);
  if (!s) notFound();

  const related = services.filter((x) => x.slug !== s.slug).slice(0, 4);
  const path = `/uslugi/${s.slug}`;

  return (
    <>
      <Breadcrumbs
        items={[
          { name: "Главная", href: "/" },
          { name: "Услуги", href: "/uslugi" },
          { name: s.title, href: path },
        ]}
      />

      <article className="container-x mt-8">
        <div className="grid gap-10 lg:grid-cols-[1.6fr_1fr]">
          {/* Контент */}
          <div>
            <div className="flex items-center gap-3">
              <span className="grid h-14 w-14 place-items-center rounded-2xl bg-brand-500/15 text-3xl">{s.icon}</span>
              <span className="chip">Автосервис · {company.address.city}</span>
            </div>
            <h1 className="mt-5 text-3xl font-extrabold text-white sm:text-4xl">{s.h1}</h1>
            <p className="mt-4 text-lg leading-relaxed text-slate-300">{s.intro}</p>

            {/* Работы */}
            <h2 className="mt-10 text-2xl font-bold text-white">Что входит в услугу</h2>
            <ul className="mt-5 grid gap-2 sm:grid-cols-2">
              {s.works.map((w) => (
                <li key={w} className="flex items-start gap-2 rounded-xl border border-white/5 bg-white/[0.03] p-3 text-sm text-slate-300">
                  <span className="mt-0.5 text-brand-400">✓</span> {w}
                </li>
              ))}
            </ul>

            {/* Цены */}
            <h2 className="mt-10 text-2xl font-bold text-white">Стоимость</h2>
            <div className="mt-5 overflow-hidden rounded-2xl border border-white/10">
              <table className="w-full text-sm">
                <tbody>
                  {s.prices.map((p, i) => (
                    <tr key={p.name} className={i % 2 ? "bg-white/[0.02]" : ""}>
                      <td className="px-4 py-3 text-slate-300">{p.name}</td>
                      <td className="px-4 py-3 text-right font-semibold text-brand-300">{p.price}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <p className="mt-3 text-xs text-slate-500">
              Цены указаны ориентировочно. Точную стоимость рассчитаем после диагностики.
            </p>

            {/* FAQ */}
            {s.faq.length > 0 && (
              <>
                <h2 className="mt-10 text-2xl font-bold text-white">Частые вопросы</h2>
                <div className="mt-5 space-y-3">
                  {s.faq.map((f, i) => (
                    <details key={i} className="card-3d group p-5">
                      <summary className="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-white">
                        {f.q}
                        <span className="text-brand-400 transition-transform group-open:rotate-45">+</span>
                      </summary>
                      <p className="mt-3 text-sm leading-relaxed text-slate-400">{f.a}</p>
                    </details>
                  ))}
                </div>
              </>
            )}
          </div>

          {/* Сайдбар — заявка */}
          <aside className="lg:sticky lg:top-24 lg:self-start">
            <LeadForm defaultService={s.title} />
          </aside>
        </div>

        {/* Похожие услуги */}
        <div className="mt-16">
          <h2 className="text-xl font-bold text-white">Другие услуги</h2>
          <div className="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {related.map((r) => (
              <Link key={r.slug} href={`/uslugi/${r.slug}`} className="card-3d p-4">
                <span className="text-2xl">{r.icon}</span>
                <p className="mt-2 font-semibold text-white">{r.title}</p>
              </Link>
            ))}
          </div>
        </div>
      </article>

      <CtaBand />

      <JsonLd
        data={[
          serviceJsonLd({ title: s.h1, description: s.metaDescription, path }),
          breadcrumbJsonLd([
            { name: "Главная", path: "/" },
            { name: "Услуги", path: "/uslugi" },
            { name: s.title, path },
          ]),
          faqJsonLd(s.faq),
        ]}
      />
    </>
  );
}
