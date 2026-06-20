import type { Metadata } from "next";
import Link from "next/link";
import { Breadcrumbs, SectionHeading, BrandCard, CtaBand } from "@/components/ui";
import { JsonLd } from "@/components/JsonLd";
import { brands } from "@/data/brands";
import { brandGroups } from "@/data/nav";
import { pageMeta, breadcrumbJsonLd } from "@/lib/seo";

export const metadata: Metadata = pageMeta({
  title: "Ремонт авто по маркам в Минске — все марки автомобилей",
  description:
    "Ремонт и обслуживание всех марок авто в Минске: Volkswagen, Audi, BMW, Mercedes, Toyota, Kia, Hyundai, Geely и другие. Дилерское оборудование, гарантия. Запись онлайн.",
  path: "/marki",
  keywords: ["ремонт авто по маркам Минск", "автосервис по маркам", "СТО марки авто"],
});

export default function BrandsPage() {
  return (
    <>
      <Breadcrumbs items={[{ name: "Главная", href: "/" }, { name: "Марки авто", href: "/marki" }]} />

      <section className="container-x mt-8">
        <SectionHeading
          eyebrow="Марки авто"
          title="Ремонт по маркам автомобилей в Минске"
          subtitle="Знаем типичные неисправности каждой марки и используем дилерское диагностическое оборудование и ПО."
        />
        <div className="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
          {brands.map((b) => (
            <BrandCard key={b.slug} slug={b.slug} name={b.name} country={b.country} />
          ))}
        </div>
      </section>

      {/* Группы по происхождению — внутренняя перелинковка */}
      <section className="container-x mt-20">
        <div className="grid gap-6 lg:grid-cols-2">
          {brandGroups.map((g) => (
            <div key={g.title} className="glass rounded-2xl p-6">
              <h2 className="text-lg font-bold text-white">{g.title}</h2>
              <div className="mt-4 flex flex-wrap gap-2">
                {g.items.map((b) => (
                  <Link key={b.slug} href={`/marki/${b.slug}`} className="chip hover:text-white">
                    {b.name}
                  </Link>
                ))}
              </div>
            </div>
          ))}
        </div>
      </section>

      <CtaBand />

      <JsonLd
        data={breadcrumbJsonLd([
          { name: "Главная", path: "/" },
          { name: "Марки авто", path: "/marki" },
        ])}
      />
    </>
  );
}
