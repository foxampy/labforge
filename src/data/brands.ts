// =============================================================
//  МАРКИ АВТО — LEVADA CENTER (Минск)
//  Специализация: китайские авто и электромобили + все марки.
//  Каждая марка = страница /marki/[slug] для SEO/GEO.
// =============================================================

export type BrandGroup = "china" | "ev" | "euro" | "asia";

export type Brand = {
  slug: string;
  name: string;
  country: string;
  group: BrandGroup;
  ev?: boolean; // есть электромобили / гибриды
  antikor?: boolean; // делаем антикор
  commercial?: boolean; // есть микроавтобусы / коммерческий
  excerpt: string;
  intro: string;
  commonIssues: string[];
  popularModels: string[];
  keywords: string[];
};

export const brands: Brand[] = [
  // ---------- КИТАЙСКИЕ ----------
  {
    slug: "geely",
    name: "Geely",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Ремонт и ТО Geely Coolray, Atlas Pro, Monjaro в Минске.",
    intro:
      "Geely — самый массовый китайский бренд в Беларуси. Обслуживаем Coolray, Atlas Pro и Monjaro: знаем турбомоторы и роботизированные коробки, делаем ТО, ремонт подвески и антикор.",
    commonIssues: [
      "Стуки стоек стабилизатора и сайлентблоков",
      "Запотевание турбины на пробеге",
      "Необходимость антикора перед первой зимой",
      "Обновление и русификация мультимедиа",
    ],
    popularModels: ["Coolray", "Atlas Pro", "Monjaro", "Emgrand", "Okavango"],
    keywords: ["ремонт Geely Минск", "ТО Geely Минск", "сервис Geely Coolray", "антикор Geely"],
  },
  {
    slug: "belgee",
    name: "Belgee",
    country: "Беларусь / Китай",
    group: "china",
    antikor: true,
    excerpt: "Сервис Belgee X50 и X70 белорусской сборки.",
    intro:
      "Belgee — собранные в Беларуси кроссоверы на платформе Geely. Обслуживаем X50 и X70: ТО, ремонт ходовой и тормозов, антикор и подготовка к зиме.",
    commonIssues: [
      "Износ тормозных колодок раньше срока",
      "Стуки в подвеске на неровностях",
      "Антикор кузова и арок",
      "Настройка и русификация мультимедиа",
    ],
    popularModels: ["X50", "X70"],
    keywords: ["ремонт Belgee Минск", "сервис Belgee X50", "ТО Belgee X70", "антикор Belgee"],
  },
  {
    slug: "haval",
    name: "Haval",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Ремонт Haval Jolion, Dargo, F7 в Минске.",
    intro:
      "Haval — популярные кроссоверы с турбомоторами и роботом DCT. Обслуживаем Jolion, M6, Dargo, F7, H3 и H5: ТО, ремонт коробки и подвески, антикор.",
    commonIssues: [
      "Адаптация и обслуживание робота DCT",
      "Стуки подвески и стоек стабилизатора",
      "Антикоррозийная обработка днища",
      "Замена тормозных колодок и дисков",
    ],
    popularModels: ["Jolion", "M6", "Dargo", "F7", "H3", "H5"],
    keywords: ["ремонт Haval Минск", "сервис Haval Jolion", "ремонт DCT Haval", "антикор Haval"],
  },
  {
    slug: "chery",
    name: "Chery",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Ремонт и ТО Chery Tiggo 4/7/8 Pro в Минске.",
    intro:
      "Chery Tiggo — одни из самых продаваемых китайских кроссоверов. Обслуживаем Tiggo 4 Pro, 7 Pro Max и 8 Pro Max: ТО, диагностика, ремонт подвески и антикор.",
    commonIssues: [
      "Стуки в передней подвеске",
      "Обслуживание вариатора CVT",
      "Антикор кузова и скрытых полостей",
      "Русификация и обновление ПО",
    ],
    popularModels: ["Tiggo 4 Pro", "Tiggo 7 Pro Max", "Tiggo 8 Pro Max"],
    keywords: ["ремонт Chery Минск", "сервис Chery Tiggo", "ремонт CVT Chery", "антикор Chery"],
  },
  {
    slug: "omoda",
    name: "Omoda",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Сервис Omoda C5 и S5 в Минске.",
    intro:
      "Omoda — молодой бренд концерна Chery. Обслуживаем C5 и S5: ТО, ремонт ходовой и тормозов, диагностика электроники и антикор.",
    commonIssues: [
      "Настройка мультимедиа и ассистентов",
      "Износ элементов подвески",
      "Антикор перед зимним сезоном",
      "Обслуживание вариатора",
    ],
    popularModels: ["C5", "S5"],
    keywords: ["ремонт Omoda Минск", "сервис Omoda C5", "ТО Omoda S5", "антикор Omoda"],
  },
  {
    slug: "jaecoo",
    name: "Jaecoo",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Ремонт Jaecoo J7 и J8 в Минске.",
    intro:
      "Jaecoo — премиальный суб-бренд Chery. Обслуживаем J7 и J8: ТО, ремонт подвески и тормозов, антикор и диагностика электроники.",
    commonIssues: [
      "Обслуживание полного привода",
      "Износ тормозных колодок",
      "Антикор кузова и арок",
      "Настройка систем помощи водителю",
    ],
    popularModels: ["J7", "J8"],
    keywords: ["ремонт Jaecoo Минск", "сервис Jaecoo J7", "ТО Jaecoo J8", "антикор Jaecoo"],
  },
  {
    slug: "exeed",
    name: "Exeed",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Сервис Exeed LX, TXL, RX, VX в Минске.",
    intro:
      "Exeed — флагманский бренд Chery. Обслуживаем LX, TXL, RX и VX: ТО, ремонт подвески, тормозов и трансмиссии, антикор.",
    commonIssues: [
      "Обслуживание турбомотора и DCT",
      "Стуки подвески на пробеге",
      "Антикоррозийная обработка",
      "Диагностика электроники",
    ],
    popularModels: ["LX", "TXL", "RX", "VX"],
    keywords: ["ремонт Exeed Минск", "сервис Exeed TXL", "ТО Exeed RX", "антикор Exeed"],
  },
  {
    slug: "changan",
    name: "Changan",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Ремонт Changan CS55 Plus, UNI-K, UNI-T в Минске.",
    intro:
      "Changan — крупный китайский производитель. Обслуживаем CS35 Plus, CS55 Plus, UNI-S, UNI-K и UNI-T: ТО, ремонт ходовой и тормозов, антикор.",
    commonIssues: [
      "Обслуживание робота и турбомотора",
      "Стуки в подвеске",
      "Антикор кузова",
      "Русификация мультимедиа",
    ],
    popularModels: ["CS35 Plus", "CS55 Plus", "UNI-S", "UNI-K", "UNI-T"],
    keywords: ["ремонт Changan Минск", "сервис Changan CS55", "ТО Changan UNI-K", "антикор Changan"],
  },
  {
    slug: "jetour",
    name: "Jetour",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Сервис Jetour Dashing, X70 Plus, X90 Plus.",
    intro:
      "Jetour — бренд кроссоverов концерна Chery. Обслуживаем Dashing, X70 Plus и X90 Plus: ТО, ремонт подвески и тормозов, антикор.",
    commonIssues: [
      "Износ элементов подвески",
      "Обслуживание турбомотора",
      "Антикор днища и арок",
      "Настройка мультимедиа",
    ],
    popularModels: ["Dashing", "X70 Plus", "X90 Plus"],
    keywords: ["ремонт Jetour Минск", "сервис Jetour Dashing", "ТО Jetour X70", "антикор Jetour"],
  },
  {
    slug: "gac",
    name: "GAC",
    country: "Китай",
    group: "china",
    excerpt: "Ремонт GAC GS3, GS4, GS8, Empow в Минске.",
    intro:
      "GAC — китайский автоконцерн с надёжными моторами Mitsubishi. Обслуживаем GS3, GS4, GS8 и Empow: ТО, ремонт подвески и тормозов, диагностика.",
    commonIssues: [
      "Обслуживание АКПП Aisin",
      "Стуки подвески",
      "Антикор кузова",
      "Диагностика электроники",
    ],
    popularModels: ["GS3", "GS4", "GS8", "Empow"],
    keywords: ["ремонт GAC Минск", "сервис GAC GS8", "ТО GAC GS4"],
  },
  {
    slug: "dongfeng",
    name: "Dongfeng",
    country: "Китай",
    group: "china",
    excerpt: "Сервис Dongfeng Shine Max, Mage, Aeolus AX7.",
    intro:
      "Dongfeng — один из старейших китайских концернов. Обслуживаем Shine Max, Mage и Aeolus AX7: ТО, ремонт ходовой и тормозов, диагностика.",
    commonIssues: [
      "Обслуживание турбомотора и коробки",
      "Износ подвески",
      "Антикор кузова",
      "Настройка мультимедиа",
    ],
    popularModels: ["Shine Max", "Mage", "Aeolus AX7"],
    keywords: ["ремонт Dongfeng Минск", "сервис Dongfeng Mage", "ТО Dongfeng"],
  },
  {
    slug: "baic",
    name: "BAIC",
    country: "Китай",
    group: "china",
    excerpt: "Ремонт BAIC X35, X55, BJ40 в Минске.",
    intro:
      "BAIC — пекинский автозавод. Обслуживаем X35, X55 и внедорожник BJ40: ТО, ремонт подвески, тормозов и трансмиссии.",
    commonIssues: [
      "Обслуживание полного привода BJ40",
      "Стуки подвески",
      "Антикор кузова",
      "Диагностика электрики",
    ],
    popularModels: ["X35", "X55", "BJ40"],
    keywords: ["ремонт BAIC Минск", "сервис BAIC BJ40", "ТО BAIC X55"],
  },
  {
    slug: "jac",
    name: "JAC",
    country: "Китай",
    group: "china",
    commercial: true,
    excerpt: "Сервис JAC JS4, JS6, T8 Pro и фургонов Sunray.",
    intro:
      "JAC — производитель легковых и коммерческих авто. Обслуживаем JS4, JS6, пикап T8 Pro и микроавтобусы Sunray: ТО, ремонт ходовой, тормозов и дизельных моторов.",
    commonIssues: [
      "Обслуживание дизельного мотора",
      "Ремонт подвески и тормозов",
      "Антикор кузова и фургона",
      "Диагностика электрики",
    ],
    popularModels: ["JS4", "JS6", "T8 Pro", "Sunray"],
    keywords: ["ремонт JAC Минск", "сервис JAC Sunray", "ТО JAC JS6"],
  },
  {
    slug: "faw-bestune",
    name: "FAW / Bestune",
    country: "Китай",
    group: "china",
    excerpt: "Ремонт Bestune T55, T77, T90, B70 в Минске.",
    intro:
      "FAW Bestune — бренд старейшего китайского концерна FAW. Обслуживаем T55, T77, T90 и седан B70: ТО, ремонт подвески и тормозов, диагностика.",
    commonIssues: [
      "Обслуживание турбомотора и DCT",
      "Износ подвески",
      "Антикор кузова",
      "Настройка мультимедиа",
    ],
    popularModels: ["T55", "T77", "T90", "B70"],
    keywords: ["ремонт FAW Минск", "сервис Bestune T77", "ТО Bestune B70"],
  },
  {
    slug: "tank",
    name: "Tank",
    country: "Китай",
    group: "china",
    antikor: true,
    excerpt: "Сервис рамных внедорожников Tank 300 и 500.",
    intro:
      "Tank — бренд премиальных внедорожников концерна Great Wall. Обслуживаем Tank 300 и 500: ТО, ремонт подвески, тормозов, полного привода, антикор рамы и кузова.",
    commonIssues: [
      "Обслуживание раздатки и полного привода",
      "Антикор рамы и днища",
      "Ремонт подвески под нагрузкой",
      "Диагностика электроники",
    ],
    popularModels: ["300", "500"],
    keywords: ["ремонт Tank Минск", "сервис Tank 300", "ТО Tank 500", "антикор Tank"],
  },
  {
    slug: "maxus",
    name: "Maxus",
    country: "Китай",
    group: "china",
    commercial: true,
    excerpt: "Ремонт и ТО фургонов Maxus Deliver 9.",
    intro:
      "Maxus — китайский бренд коммерческого транспорта. Обслуживаем фургоны Deliver 9: ТО, ремонт дизельного мотора, ходовой и тормозов, антикор кузова.",
    commonIssues: [
      "Обслуживание дизельного мотора",
      "Ремонт подвески под нагрузкой",
      "Антикор кузова и днища",
      "Диагностика электрики",
    ],
    popularModels: ["Deliver 9"],
    keywords: ["ремонт Maxus Минск", "сервис Maxus Deliver 9", "ТО фургона Maxus"],
  },

  // ---------- ЭЛЕКТРОМОБИЛИ / ГИБРИДЫ (китайские EV) ----------
  {
    slug: "li-auto",
    name: "Li Auto",
    country: "Китай",
    group: "china",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация Li Auto L7, L8, L9.",
    intro:
      "Li Auto — премиальные гибриды на электротяге. Обслуживаем L6, L7, L8 и L9: ТО ходовой и тормозов, антикор, комплексная русификация и адаптация мультимедиа.",
    commonIssues: [
      "Комплексная русификация и обход блокировок",
      "Адаптация телематики под местную SIM",
      "Антикор кузова и защита батареи",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["L6", "L7", "L8", "L9"],
    keywords: ["ремонт Li Auto Минск", "русификация Li Auto", "сервис Li L9", "антикор Li Auto"],
  },
  {
    slug: "zeekr",
    name: "Zeekr",
    country: "Китай",
    group: "china",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация электромобилей Zeekr 001 и X.",
    intro:
      "Zeekr — премиальный электромобильный бренд Geely. Обслуживаем 001 и X: ТО ходовой и тормозов, антикор, сложная русификация и адаптация под европейские стандарты.",
    commonIssues: [
      "Комплексная русификация интерфейса",
      "Адаптация телематики и SIM",
      "Антикор и защита батарейного отсека",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["001", "X"],
    keywords: ["ремонт Zeekr Минск", "русификация Zeekr", "сервис Zeekr 001", "антикор Zeekr"],
  },
  {
    slug: "voyah",
    name: "Voyah",
    country: "Китай",
    group: "china",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация Voyah Free и Dream.",
    intro:
      "Voyah — премиальный EV-бренд Dongfeng. Обслуживаем Free и Dream: ТО ходовой и тормозов, антикор, комплексная русификация и активация мастер-аккаунта.",
    commonIssues: [
      "Сложная русификация всех экранов",
      "Активация мастер-аккаунта",
      "Антикор и защита днища",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["Free", "Dream"],
    keywords: ["ремонт Voyah Минск", "русификация Voyah", "сервис Voyah Free", "антикор Voyah"],
  },
  {
    slug: "byd",
    name: "BYD",
    country: "Китай",
    group: "china",
    ev: true,
    antikor: true,
    excerpt: "Сервис BYD Song Plus, Seal, Atto 3 в Минске.",
    intro:
      "BYD — мировой лидер по выпуску электромобилей. Обслуживаем Song Plus, Yuan Plus, Seal и Atto 3: ТО ходовой и тормозов, антикор, диагностика и русификация.",
    commonIssues: [
      "Русификация и адаптация мультимедиа",
      "Антикор и защита батареи Blade",
      "Обслуживание тормозов (рекуперация)",
      "Диагностика электроники",
    ],
    popularModels: ["Song Plus", "Yuan Plus", "Seal", "Atto 3"],
    keywords: ["ремонт BYD Минск", "русификация BYD", "сервис BYD Seal", "антикор BYD"],
  },
  {
    slug: "tesla",
    name: "Tesla",
    country: "США",
    group: "ev",
    ev: true,
    antikor: true,
    excerpt: "Сервис Tesla Model 3 и Model Y в Минске.",
    intro:
      "Tesla — эталон электромобилей. Обслуживаем Model 3 и Model Y: ремонт и диагностика ходовой, тормозов, антикор и защита днища, обслуживание подвески.",
    commonIssues: [
      "Износ рычагов и сайлентблоков",
      "Антикор и защита днища",
      "Обслуживание тормозов при рекуперации",
      "Диагностика ходовой части",
    ],
    popularModels: ["Model 3", "Model Y"],
    keywords: ["ремонт Tesla Минск", "сервис Tesla Model 3", "антикор Tesla", "ремонт ходовой Tesla"],
  },
  {
    slug: "avatr",
    name: "Avatr",
    country: "Китай",
    group: "ev",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация электромобилей Avatr 11.",
    intro:
      "Avatr — премиальный EV-бренд (Changan, Huawei, CATL). Обслуживаем Avatr 11: ТО ходовой и тормозов, антикор, сложная русификация и адаптация телематики.",
    commonIssues: [
      "Комплексная русификация интерфейса",
      "Адаптация телематики и SIM",
      "Антикор и защита батареи",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["11"],
    keywords: ["ремонт Avatr Минск", "русификация Avatr 11", "сервис Avatr", "антикор Avatr"],
  },
  {
    slug: "aito",
    name: "AITO",
    country: "Китай",
    group: "ev",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация AITO M7 и M9.",
    intro:
      "AITO — бренд Huawei и Seres. Обслуживаем M7 и M9: ТО ходовой и тормозов, антикор, комплексная русификация и адаптация под местные стандарты.",
    commonIssues: [
      "Русификация интерфейса HarmonyOS",
      "Адаптация телематики",
      "Антикор и защита днища",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["M7", "M9"],
    keywords: ["ремонт AITO Минск", "русификация AITO M9", "сервис AITO M7", "антикор AITO"],
  },
  {
    slug: "xpeng",
    name: "XPeng",
    country: "Китай",
    group: "ev",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация XPeng G6 и G9.",
    intro:
      "XPeng — технологичные электромобили из Китая. Обслуживаем G6 и G9: ТО ходовой и тормозов, антикор, русификация и адаптация телематики.",
    commonIssues: [
      "Русификация и установка приложений",
      "Адаптация телематики и SIM",
      "Антикор и защита батареи",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["G6", "G9"],
    keywords: ["ремонт XPeng Минск", "русификация XPeng G9", "сервис XPeng G6", "антикор XPeng"],
  },
  {
    slug: "nio",
    name: "NIO",
    country: "Китай",
    group: "ev",
    ev: true,
    antikor: true,
    excerpt: "Сервис и русификация NIO ES6 в Минске.",
    intro:
      "NIO — премиальный электромобильный бренд. Обслуживаем ES6: ТО ходовой и тормозов, антикор, русификация интерфейса и адаптация телематики.",
    commonIssues: [
      "Русификация интерфейса и NOMI",
      "Адаптация телематики и SIM",
      "Антикор и защита днища",
      "Обслуживание тормозов и подвески",
    ],
    popularModels: ["ES6"],
    keywords: ["ремонт NIO Минск", "русификация NIO ES6", "сервис NIO", "антикор NIO"],
  },

  // ---------- АЗИАТСКИЕ ----------
  {
    slug: "toyota",
    name: "Toyota",
    country: "Япония",
    group: "asia",
    antikor: true,
    excerpt: "Ремонт Toyota RAV4, Camry, Land Cruiser в Минске.",
    intro:
      "Toyota — эталон надёжности. Обслуживаем RAV4, Camry, Corolla, Highlander и Land Cruiser 300: ТО, ремонт двигателя, подвески, тормозов и антикор.",
    commonIssues: [
      "Профилактика и антикор кузова",
      "Обслуживание вариатора и гибридной системы",
      "Износ ступичных подшипников",
      "Замена тормозных дисков и колодок",
    ],
    popularModels: ["RAV4", "Camry", "Corolla", "Highlander", "Land Cruiser 300"],
    keywords: ["ремонт Toyota Минск", "сервис Toyota Camry", "ТО Toyota RAV4", "антикор Toyota"],
  },
  {
    slug: "lexus",
    name: "Lexus",
    country: "Япония",
    group: "asia",
    excerpt: "Сервис Lexus RX, NX, LX в Минске.",
    intro:
      "Lexus — премиум от Toyota. Обслуживаем RX, NX и LX: ТО, ремонт подвески (в т.ч. пневмо), тормозов, гибридных систем и диагностика.",
    commonIssues: [
      "Обслуживание пневмоподвески LX",
      "Гибридная батарея и инвертор",
      "Износ тормозов",
      "Антикор кузова",
    ],
    popularModels: ["RX", "NX", "LX"],
    keywords: ["ремонт Lexus Минск", "сервис Lexus RX", "ТО Lexus NX"],
  },
  {
    slug: "kia",
    name: "Kia",
    country: "Южная Корея",
    group: "asia",
    antikor: true,
    excerpt: "Ремонт Kia Sportage, Sorento, K5 в Минске.",
    intro:
      "Kia — популярная корейская марка. Обслуживаем Sportage, Seltos, Sorento, K5 и Cerato: ТО, ремонт двигателя, подвески, тормозов и антикор.",
    commonIssues: [
      "Износ катушек и свечей",
      "Стуки подвески и стоек стабилизатора",
      "Антикор кузова и арок",
      "Обслуживание АКПП",
    ],
    popularModels: ["Sportage", "Seltos", "Sorento", "K5", "Cerato"],
    keywords: ["ремонт Kia Минск", "сервис Kia Sportage", "ТО Kia K5", "антикор Kia"],
  },
  {
    slug: "hyundai",
    name: "Hyundai",
    country: "Южная Корея",
    group: "asia",
    antikor: true,
    excerpt: "Ремонт Hyundai Tucson, Santa Fe, Creta в Минске.",
    intro:
      "Hyundai — надёжные и распространённые авто. Обслуживаем Tucson, Santa Fe, Creta и Elantra: ТО, ремонт двигателя, подвески, тормозов и антикор.",
    commonIssues: [
      "Износ катушек зажигания",
      "Стуки подвески",
      "Антикор кузова и арок",
      "Обслуживание АКПП и робота",
    ],
    popularModels: ["Tucson", "Santa Fe", "Creta", "Elantra"],
    keywords: ["ремонт Hyundai Минск", "сервис Hyundai Tucson", "ТО Hyundai Creta", "антикор Hyundai"],
  },
  {
    slug: "mitsubishi",
    name: "Mitsubishi",
    country: "Япония",
    group: "asia",
    excerpt: "Сервис Mitsubishi Outlander и Pajero Sport.",
    intro:
      "Mitsubishi — надёжные кроссоверы и внедорожники. Обслуживаем Outlander и Pajero Sport: ТО, ремонт двигателя, вариатора, подвески и полного привода.",
    commonIssues: [
      "Обслуживание вариатора CVT",
      "Ремонт полного привода",
      "Износ подвески",
      "Антикор кузова",
    ],
    popularModels: ["Outlander", "Pajero Sport"],
    keywords: ["ремонт Mitsubishi Минск", "сервис Outlander", "ремонт вариатора Mitsubishi"],
  },
  {
    slug: "nissan",
    name: "Nissan",
    country: "Япония",
    group: "asia",
    excerpt: "Ремонт Nissan X-Trail и Qashqai в Минске.",
    intro:
      "Nissan — популярные кроссоверы. Обслуживаем X-Trail и Qashqai: ТО, обслуживание вариатора, ремонт подвески, тормозов и диагностика.",
    commonIssues: [
      "Обслуживание и ремонт вариатора",
      "Стуки подвески",
      "Износ тормозов",
      "Антикор кузова",
    ],
    popularModels: ["X-Trail", "Qashqai"],
    keywords: ["ремонт Nissan Минск", "сервис Nissan X-Trail", "ремонт вариатора Nissan"],
  },
  {
    slug: "mazda",
    name: "Mazda",
    country: "Япония",
    group: "asia",
    excerpt: "Сервис Mazda CX-5, CX-50, Mazda 6 в Минске.",
    intro:
      "Mazda — драйверские авто с моторами SkyActiv. Обслуживаем CX-5, CX-50 и Mazda 6: ТО, ремонт двигателя, подвески, тормозов и диагностика.",
    commonIssues: [
      "Нагар и обслуживание SkyActiv",
      "Износ подвески",
      "Замена тормозных дисков",
      "Антикор кузова",
    ],
    popularModels: ["CX-5", "CX-50", "Mazda 6"],
    keywords: ["ремонт Mazda Минск", "сервис Mazda CX-5", "ТО Mazda 6"],
  },
  {
    slug: "subaru",
    name: "Subaru",
    country: "Япония",
    group: "asia",
    excerpt: "Ремонт Subaru Forester и Outback в Минске.",
    intro:
      "Subaru — оппозитные моторы и симметричный полный привод. Обслуживаем Forester и Outback: ТО, ремонт двигателя, подвески, полного привода и диагностика.",
    commonIssues: [
      "Течи прокладок оппозитного мотора",
      "Обслуживание полного привода и вариатора",
      "Износ подвески",
      "Антикор кузова",
    ],
    popularModels: ["Forester", "Outback"],
    keywords: ["ремонт Subaru Минск", "сервис Subaru Forester", "ТО Subaru Outback"],
  },

  // ---------- ЕВРОПЕЙСКИЕ ----------
  {
    slug: "volkswagen",
    name: "Volkswagen",
    country: "Германия",
    group: "euro",
    ev: true,
    antikor: true,
    commercial: true,
    excerpt: "Ремонт VW Tiguan, Passat, Polo и Transporter.",
    intro:
      "Volkswagen — одна из самых массовых марок. Обслуживаем Tiguan, Taos, Polo, Passat, микроавтобусы Transporter T6.1 и Multivan, а также электро ID.4/ID.6: моторы TSI/TDI, коробки DSG, подвеска, антикор.",
    commonIssues: [
      "Растяжение цепи ГРМ на TSI",
      "Мехатроник и сцепление DSG",
      "Течи масла из-под клапанной крышки",
      "Антикор кузова и арок",
    ],
    popularModels: ["Tiguan", "Taos", "Polo", "Passat", "Transporter T6.1", "Multivan", "ID.4", "ID.6"],
    keywords: ["ремонт Volkswagen Минск", "ремонт DSG Минск", "сервис VW Transporter", "антикор Volkswagen"],
  },
  {
    slug: "skoda",
    name: "Skoda",
    country: "Чехия",
    group: "euro",
    antikor: true,
    excerpt: "Сервис Skoda Octavia, Kodiaq, Superb в Минске.",
    intro:
      "Skoda — практичные авто на платформах VW. Обслуживаем Kodiaq, Karoq, Octavia и Superb: моторы TSI, коробки DSG, подвеска, тормоза и антикор.",
    commonIssues: [
      "Цепь ГРМ и катушки на TSI",
      "Обслуживание DSG",
      "Стуки подвески",
      "Антикор кузова",
    ],
    popularModels: ["Kodiaq", "Karoq", "Octavia", "Superb"],
    keywords: ["ремонт Skoda Минск", "сервис Skoda Octavia", "ремонт DSG Skoda", "антикор Skoda"],
  },
  {
    slug: "bmw",
    name: "BMW",
    country: "Германия",
    group: "euro",
    ev: true,
    excerpt: "Ремонт BMW X3, X5, 3 Series и iX в Минске.",
    intro:
      "BMW требует точной диагностики и опыта. Обслуживаем X1, X3, X5, 3 Series и электро iX: ремонт двигателей, подвески, тормозов, электроники с дилерским ПО.",
    commonIssues: [
      "Течи масла и системы охлаждения",
      "Износ цепи ГРМ на N-моторах",
      "Пневмоподвеска и амортизаторы",
      "Диагностика электроники",
    ],
    popularModels: ["X1", "X3", "X5", "3 Series", "iX"],
    keywords: ["ремонт BMW Минск", "сервис BMW X5", "ремонт BMW iX", "диагностика BMW"],
  },
  {
    slug: "mercedes-benz",
    name: "Mercedes-Benz",
    country: "Германия",
    group: "euro",
    commercial: true,
    excerpt: "Сервис Mercedes GLC, GLE, E-Class, Vito, Sprinter.",
    intro:
      "Mercedes-Benz — премиум и коммерческий транспорт. Обслуживаем GLC, GLE, E-Class, а также Vito, V-Class и Sprinter: ремонт двигателей, подвески, тормозов и антикор.",
    commonIssues: [
      "Течи масла и сальников",
      "Пневмоподвеска Airmatic",
      "Обслуживание дизелей OM",
      "Антикор кузова и фургона",
    ],
    popularModels: ["GLC", "GLE", "E-Class", "Vito", "V-Class", "Sprinter"],
    keywords: ["ремонт Mercedes Минск", "сервис Mercedes Sprinter", "ремонт Vito", "диагностика Mercedes"],
  },
  {
    slug: "audi",
    name: "Audi",
    country: "Германия",
    group: "euro",
    excerpt: "Ремонт Audi Q5, Q7, A4 в Минске.",
    intro:
      "Audi — техника с системой quattro и моторами TFSI. Обслуживаем Q3, Q5, Q7 и A4: ремонт двигателей, коробок, подвески и электроники с дилерским ПО.",
    commonIssues: [
      "Масложор на 1.8/2.0 TFSI",
      "Износ цепи ГРМ",
      "Пневмоподвеска Q7",
      "Ошибки мехатроника S-tronic",
    ],
    popularModels: ["Q3", "Q5", "Q7", "A4"],
    keywords: ["ремонт Audi Минск", "сервис Audi Q7", "диагностика Audi", "ремонт quattro"],
  },
  {
    slug: "renault",
    name: "Renault",
    country: "Франция",
    group: "euro",
    commercial: true,
    excerpt: "Сервис Renault Duster, Arkana и Trafic в Минске.",
    intro:
      "Renault — практичные и неприхотливые авто. Обслуживаем Duster, Arkana и фургоны Trafic: ТО, ремонт двигателя, подвески, тормозов и диагностика.",
    commonIssues: [
      "Обслуживание вариатора и робота",
      "Износ подвески",
      "Антикор кузова",
      "Электрика и датчики",
    ],
    popularModels: ["Duster", "Arkana", "Trafic"],
    keywords: ["ремонт Renault Минск", "сервис Renault Duster", "ремонт Renault Trafic"],
  },
  {
    slug: "ford",
    name: "Ford",
    country: "США / Европа",
    group: "euro",
    commercial: true,
    excerpt: "Ремонт и ТО фургонов Ford Transit в Минске.",
    intro:
      "Ford Transit — самый популярный фургон. Обслуживаем Transit: ремонт дизельных моторов, подвески под нагрузкой, тормозов, антикор кузова и диагностика.",
    commonIssues: [
      "Обслуживание дизеля и турбины",
      "Ремонт подвески под нагрузкой",
      "Антикор кузова и днища",
      "Диагностика электрики",
    ],
    popularModels: ["Transit"],
    keywords: ["ремонт Ford Transit Минск", "сервис фургона Ford", "ТО Ford Transit"],
  },
  {
    slug: "peugeot",
    name: "Peugeot",
    country: "Франция",
    group: "euro",
    commercial: true,
    excerpt: "Сервис Peugeot Traveller и Expert в Минске.",
    intro:
      "Peugeot — французский коммерческий транспорт. Обслуживаем Traveller и Expert: ремонт дизельных моторов, подвески, тормозов и диагностика электроники.",
    commonIssues: [
      "Обслуживание дизеля HDi",
      "Ремонт подвески",
      "Антикор кузова",
      "Электрика и датчики",
    ],
    popularModels: ["Traveller", "Expert"],
    keywords: ["ремонт Peugeot Минск", "сервис Peugeot Expert", "ремонт Traveller"],
  },
  {
    slug: "citroen",
    name: "Citroen",
    country: "Франция",
    group: "euro",
    commercial: true,
    excerpt: "Ремонт Citroen SpaceTourer и Jumpy в Минске.",
    intro:
      "Citroen — французские микроавтобусы и фургоны. Обслуживаем SpaceTourer и Jumpy: ремонт дизельных моторов, подвески, тормозов и диагностика.",
    commonIssues: [
      "Обслуживание дизеля HDi",
      "Ремонт подвески под нагрузкой",
      "Антикор кузова",
      "Электрика и датчики",
    ],
    popularModels: ["SpaceTourer", "Jumpy"],
    keywords: ["ремонт Citroen Минск", "сервис Citroen Jumpy", "ремонт SpaceTourer"],
  },
  {
    slug: "fiat",
    name: "Fiat",
    country: "Италия",
    group: "euro",
    commercial: true,
    excerpt: "Сервис фургонов Fiat Ducato в Минске.",
    intro:
      "Fiat Ducato — популярный фургон и база для автодомов. Обслуживаем Ducato: ремонт дизельного мотора, подвески под нагрузкой, тормозов и антикор.",
    commonIssues: [
      "Обслуживание дизеля и турбины",
      "Ремонт подвески под нагрузкой",
      "Антикор кузова и днища",
      "Диагностика электрики",
    ],
    popularModels: ["Ducato"],
    keywords: ["ремонт Fiat Ducato Минск", "сервис фургона Fiat", "ТО Ducato"],
  },
];

export const getBrand = (slug: string) => brands.find((b) => b.slug === slug);
export const brandSlugs = brands.map((b) => b.slug);
export const evBrands = brands.filter((b) => b.ev);
export const antikorBrands = brands.filter((b) => b.antikor);
