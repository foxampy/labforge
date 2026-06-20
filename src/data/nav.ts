import { services } from "./services";
import { brands } from "./brands";

/** Группировка услуг по блокам для мега-меню. */
export const serviceGroups = [
  {
    title: "Диагностика и ТО",
    slugs: ["diagnostika", "to-zamena-masla", "razval-shozhdenie", "ozonirovanie"],
  },
  {
    title: "Ремонт и ходовая",
    slugs: ["remont-podveski", "tormoznaya-sistema", "rulevoe-upravlenie", "remont-glushitelya"],
  },
  {
    title: "Электроника и защита",
    slugs: ["chip-tyuning", "rusifikaciya", "antikor"],
  },
].map((g) => ({
  title: g.title,
  items: g.slugs
    .map((slug) => services.find((s) => s.slug === slug))
    .filter(Boolean)
    .map((s) => ({ slug: s!.slug, title: s!.title, icon: s!.icon })),
}));

/** Группировка марок по происхождению для мега-меню. */
export const brandGroups = [
  { title: "Китайские авто", group: "china" as const },
  { title: "Электромобили", group: "ev" as const },
  { title: "Европейские", group: "euro" as const },
  { title: "Азиатские", group: "asia" as const },
].map((g) => ({
  title: g.title,
  items: brands
    .filter((b) => b.group === g.group)
    .map((b) => ({ slug: b.slug, name: b.name })),
}));

export const mainNav = [
  { href: "/", label: "Главная" },
  { href: "/uslugi", label: "Услуги", mega: "services" as const },
  { href: "/marki", label: "Марки авто", mega: "brands" as const },
  { href: "/o-nas", label: "О нас" },
  { href: "/kontakty", label: "Контакты" },
];

/** Плоский индекс для поиска по сайту. */
export const searchIndex = [
  { title: "Главная", href: "/", type: "Страница", keywords: "автосервис минск" },
  { title: "Все услуги", href: "/uslugi", type: "Страница", keywords: "услуги работы прайс" },
  { title: "Все марки авто", href: "/marki", type: "Страница", keywords: "марки бренды авто" },
  { title: "О нас", href: "/o-nas", type: "Страница", keywords: "о компании гарантия" },
  { title: "Контакты", href: "/kontakty", type: "Страница", keywords: "адрес телефон карта запись" },
  ...services.map((s) => ({
    title: s.title,
    href: `/uslugi/${s.slug}`,
    type: "Услуга",
    keywords: s.keywords.join(" "),
  })),
  ...brands.map((b) => ({
    title: `Ремонт ${b.name}`,
    href: `/marki/${b.slug}`,
    type: "Марка",
    keywords: `${b.name} ${b.keywords.join(" ")} ${b.popularModels.join(" ")}`,
  })),
];
