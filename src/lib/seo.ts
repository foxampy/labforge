import type { Metadata } from "next";
import { company, SITE_URL } from "@/data/company";

/** Абсолютный URL для канонических ссылок и OG. */
export const abs = (path = "/") => new URL(path, SITE_URL).toString();

/** Базовые метаданные страницы с canonical и OpenGraph. */
export function pageMeta(opts: {
  title: string;
  description: string;
  path?: string;
  keywords?: string[];
  noindex?: boolean;
}): Metadata {
  const url = abs(opts.path ?? "/");
  return {
    title: opts.title,
    description: opts.description,
    keywords: opts.keywords,
    alternates: { canonical: url },
    robots: opts.noindex ? { index: false, follow: true } : undefined,
    openGraph: {
      type: "website",
      url,
      siteName: company.name,
      title: opts.title,
      description: opts.description,
      locale: company.locale,
    },
    twitter: {
      card: "summary_large_image",
      title: opts.title,
      description: opts.description,
    },
  };
}

/** JSON-LD: автосервис как локальный бизнес (GEO-ядро). */
export function autoRepairJsonLd() {
  return {
    "@context": "https://schema.org",
    "@type": "AutoRepair",
    "@id": abs("/#business"),
    name: company.name,
    legalName: company.legalName,
    description: company.description,
    url: SITE_URL,
    telephone: company.phone,
    email: company.email,
    priceRange: company.priceRange,
    image: abs("/og.jpg"),
    logo: abs("/logo.png"),
    foundingDate: String(company.foundedYear),
    address: {
      "@type": "PostalAddress",
      streetAddress: company.address.street,
      addressLocality: company.address.city,
      addressRegion: company.address.region,
      postalCode: company.address.postalCode,
      addressCountry: company.address.country,
    },
    geo: {
      "@type": "GeoCoordinates",
      latitude: company.geo.lat,
      longitude: company.geo.lng,
    },
    areaServed: company.areasServed.map((name) => ({ "@type": "City", name })),
    openingHoursSpecification: company.openingHoursSpec.map((o) => ({
      "@type": "OpeningHoursSpecification",
      dayOfWeek: o.days,
      opens: o.opens,
      closes: o.closes,
    })),
    sameAs: Object.values(company.social).filter(Boolean),
  };
}

/** JSON-LD: услуга. */
export function serviceJsonLd(s: { title: string; description: string; path: string }) {
  return {
    "@context": "https://schema.org",
    "@type": "Service",
    name: s.title,
    description: s.description,
    url: abs(s.path),
    serviceType: s.title,
    areaServed: company.areasServed.map((name) => ({ "@type": "City", name })),
    provider: { "@type": "AutoRepair", "@id": abs("/#business"), name: company.name },
  };
}

/** JSON-LD: хлебные крошки. */
export function breadcrumbJsonLd(items: { name: string; path: string }[]) {
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((it, i) => ({
      "@type": "ListItem",
      position: i + 1,
      name: it.name,
      item: abs(it.path),
    })),
  };
}

/** JSON-LD: FAQ. */
export function faqJsonLd(faq: { q: string; a: string }[]) {
  return {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    mainEntity: faq.map((f) => ({
      "@type": "Question",
      name: f.q,
      acceptedAnswer: { "@type": "Answer", text: f.a },
    })),
  };
}

// Компонент JsonLd вынесен в src/components/JsonLd.tsx
