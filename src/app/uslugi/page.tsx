import type { Metadata } from "next";
import Link from "next/link";
import { Breadcrumbs, SectionHeading, ServiceCard, CtaBand } from "@/components/ui";
import { JsonLd } from "@/components/JsonLd";
import { services } from "@/data/services";
import { company } from "@/data/company";
import { pageMeta, breadcrumbJsonLd } from "@/lib/seo";

export const metadata: Metadata = pageMeta({
  title: "Услуги автосервиса в Минске — полный список работ и цены",
  description:
    "Все услуги автосервиса Livada Center в Минске: диагностика, ремонт двигателя, ходовой, КПП, тормозов, кузовной ремонт, ТО, шиномонтаж. Прайс и запись онлайн.",
  path: "/uslugi",
  keywords: ["услуги автосервиса Минск", "прайс СТО Минск", "ремонт авто цены"],
});

export default function ServicesPage() {
  return (
    <>
      <Breadcrumbs items={[{ name: "Главная", href: "/" }, { name: "Услуги", href: "/uslugi" }]} />

      <section className="container-x mt-8">
        <SectionHeading
          eyebrow="Направления"
          title="Услуги автосервиса в Минске"
          subtitle={`Полный спектр работ по ремонту и обслуживанию автомобилей. Работаем со всеми системами авто. ${company.address.city} и пригород.`}
        />
        <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {services.map((s) => (
            <ServiceCard key={s.slug} slug={s.slug} title={s.title} icon={s.icon} excerpt={s.excerpt} />
          ))}
        </div>
      </section>

      {/* Полный перечень работ */}
      <section className="container-x mt-20">
        <SectionHeading title="Полный перечень работ" subtitle="Что именно мы выполняем по каждому направлению." />
        <div className="mt-8 grid gap-6 lg:grid-cols-2">
          {services.map((s) => (
            <div key={s.slug} className="glass rounded-2xl p-6">
              <div className="flex items-center gap-3">
                <span className="text-2xl">{s.icon}</span>
                <h3 className="text-lg font-bold text-white">
                  <Link href={`/uslugi/${s.slug}`} className="hover:text-brand-200">{s.title}</Link>
                </h3>
              </div>
              <ul className="mt-4 grid gap-1.5">
                {s.works.map((w) => (
                  <li key={w} className="flex items-start gap-2 text-sm text-slate-300">
                    <span className="mt-1 text-brand-400">•</span> {w}
                  </li>
                ))}
              </ul>
              <Link href={`/uslugi/${s.slug}`} className="mt-4 inline-flex text-sm font-semibold text-brand-300 hover:text-brand-200">
                Подробнее и цены →
              </Link>
            </div>
          ))}
        </div>
      </section>

      <CtaBand />

      <JsonLd
        data={breadcrumbJsonLd([
          { name: "Главная", path: "/" },
          { name: "Услуги", path: "/uslugi" },
        ])}
      />
    </>
  );
}
