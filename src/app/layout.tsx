import type { Metadata, Viewport } from "next";
import { Inter, Manrope } from "next/font/google";
import "./globals.css";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { JsonLd } from "@/components/JsonLd";
import { autoRepairJsonLd } from "@/lib/seo";
import { company, SITE_URL } from "@/data/company";

const inter = Inter({ subsets: ["latin", "cyrillic"], variable: "--font-sans", display: "swap" });
const manrope = Manrope({ subsets: ["latin", "cyrillic"], variable: "--font-display", display: "swap" });

export const metadata: Metadata = {
  metadataBase: new URL(SITE_URL),
  title: {
    default: `${company.name} — ${company.tagline}`,
    template: `%s | ${company.name}`,
  },
  description: company.description,
  applicationName: company.name,
  authors: [{ name: company.name }],
  generator: "Next.js",
  keywords: [
    "автосервис Минск",
    "ремонт авто Минск",
    "СТО Минск",
    "диагностика авто",
    "ремонт двигателя",
    "автосервис",
  ],
  alternates: { canonical: "/" },
  openGraph: {
    type: "website",
    locale: company.locale,
    url: SITE_URL,
    siteName: company.name,
    title: `${company.name} — ${company.tagline}`,
    description: company.description,
  },
  twitter: { card: "summary_large_image" },
  robots: { index: true, follow: true },
  icons: { icon: "/favicon.ico" },
  verification: {
    // TODO: вставьте коды подтверждения после регистрации сайта
    // google: "...", yandex: "...",
  },
};

export const viewport: Viewport = {
  themeColor: "#0a0e1a",
  width: "device-width",
  initialScale: 1,
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="ru" className={`${inter.variable} ${manrope.variable}`}>
      <body className="font-sans antialiased">
        <JsonLd data={autoRepairJsonLd()} />
        <Header />
        <main>{children}</main>
        <Footer />
      </body>
    </html>
  );
}
