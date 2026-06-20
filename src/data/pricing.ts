// Модель авторасчёта стоимости для конструктора.
// Базовые цены — ориентировочные «средние» по работе (BYN).

export type WorkType = "repair" | "replace";
export type ZoneCategory = "repair" | "replace" | "maintenance";

export const categoryColor: Record<ZoneCategory, { hex: string; label: string }> = {
  repair: { hex: "#ef4444", label: "Ремонт / критичные системы" }, // красный
  replace: { hex: "#2f74ff", label: "Замена узлов" }, // синий
  maintenance: { hex: "#ffbe1a", label: "ТО и обслуживание" }, // жёлтый
};

export type CarZone = {
  id: string;
  serviceSlug: string;
  title: string;
  category: ZoneCategory;
  basePrice: number; // BYN, средняя стоимость работ
  short: string; // краткое описание для окна
};

// Зоны схемы авто (вид сбоку), сопоставленные с услугами.
export const carZones: CarZone[] = [
  { id: "shock", serviceSlug: "remont-podveski", title: "Амортизаторы и стойки", category: "replace", basePrice: 220, short: "Замена амортизаторов, опор и пыльников. Уходят стуки и раскачка кузова." },
  { id: "arms", serviceSlug: "remont-podveski", title: "Рычаги и сайлентблоки", category: "replace", basePrice: 180, short: "Замена рычагов, сайлентблоков и шаровых. Восстановление геометрии подвески." },
  { id: "stabilizer", serviceSlug: "remont-podveski", title: "Стойки стабилизатора", category: "repair", basePrice: 90, short: "Замена стоек и втулок стабилизатора. Устранение стуков на неровностях." },
  { id: "brake-disc", serviceSlug: "tormoznaya-sistema", title: "Тормозные диски и колодки", category: "replace", basePrice: 160, short: "Замена дисков и колодок, проточка. Возврат уверенного торможения." },
  { id: "caliper", serviceSlug: "tormoznaya-sistema", title: "Суппорты", category: "repair", basePrice: 140, short: "Ремонт и обслуживание суппортов, замена направляющих и пыльников." },
  { id: "steering", serviceSlug: "rulevoe-upravlenie", title: "Рулевые наконечники и тяги", category: "replace", basePrice: 150, short: "Замена наконечников, тяг и пыльников рейки. Чёткий и безопасный руль." },
  { id: "exhaust", serviceSlug: "remont-glushitelya", title: "Глушитель и выхлоп", category: "repair", basePrice: 120, short: "Ремонт и замена элементов выхлопа, сварка, устранение прогаров." },
  { id: "service", serviceSlug: "to-zamena-masla", title: "ТО и масло", category: "maintenance", basePrice: 120, short: "Замена масла и фильтров, регламентное обслуживание по пробегу." },
  { id: "diag", serviceSlug: "diagnostika", title: "Компьютерная диагностика", category: "maintenance", basePrice: 60, short: "Считывание ошибок, проверка систем и узлов перед ремонтом." },
];

export const getZone = (id: string) => carZones.find((z) => z.id === id);

// Премиальные марки — выше стоимость нормо-часа и запчастей.
const PREMIUM = new Set(["bmw", "audi", "mercedes-benz"]);
const SEMI_PREMIUM = new Set(["volkswagen", "skoda", "toyota", "mazda"]);

export function brandMultiplier(slug: string): number {
  if (PREMIUM.has(slug)) return 1.45;
  if (SEMI_PREMIUM.has(slug)) return 1.15;
  return 1.0;
}

export function yearMultiplier(year: number): number {
  const age = new Date().getFullYear() - year;
  if (age <= 4) return 1.12; // новые — дороже запчасти
  if (age >= 13) return 1.15; // старые — сложнее, прикипевшие узлы
  return 1.0;
}

export function workTypeMultiplier(t: WorkType): number {
  return t === "repair" ? 0.7 : 1.0;
}

export type Estimate = { min: number; max: number; mid: number };

export function estimatePrice(opts: {
  basePrice: number;
  brandSlug: string;
  year: number;
  workType: WorkType;
}): Estimate {
  const mid =
    opts.basePrice *
    brandMultiplier(opts.brandSlug) *
    yearMultiplier(opts.year) *
    workTypeMultiplier(opts.workType);
  const round = (n: number) => Math.round(n / 10) * 10;
  return { min: round(mid * 0.85), max: round(mid * 1.2), mid: round(mid) };
}

export const yearOptions = (() => {
  const now = new Date().getFullYear();
  const arr: number[] = [];
  for (let y = now; y >= now - 25; y--) arr.push(y);
  return arr;
})();
