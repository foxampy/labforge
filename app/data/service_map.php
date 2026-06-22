<?php
// Ported 1:1 from src/data/serviceMap.ts — do not edit by hand, edit the data here.
// Карта интерактивных хотспотов для секции «Интерактивный гараж».
// Координаты в процентах (left/top) относительно фоновой сцены public/services.png (1583×994).
// accent — цвет неонового маркера. slug должен существовать в services.
//
// Two exports mirrored as top-level keys:
//   'hotspots'  — list of hotspot markers (slug, label, xPct, yPct, accent)
//   'ACCENT_HEX' — map of accent name => hex colour
return [
  'hotspots' => [
    // ── Левое авто (жёлтый подъёмник) ──────────────────────────
    [
      'slug' => 'diagnostika',
      'label' => 'Диагностика',
      'xPct' => 9,
      'yPct' => 24,
      'accent' => 'blue',
    ],
    [
      'slug' => 'to-zamena-masla',
      'label' => 'ТО и масло',
      'xPct' => 20,
      'yPct' => 16,
      'accent' => 'yellow',
    ],
    [
      'slug' => 'remont-podveski',
      'label' => 'Подвеска',
      'xPct' => 14,
      'yPct' => 34,
      'accent' => 'blue',
    ],

    // ── Центральное авто (синий подъёмник, вид снизу/сзади) ────
    [
      'slug' => 'remont-glushitelya',
      'label' => 'Выхлоп',
      'xPct' => 49,
      'yPct' => 40,
      'accent' => 'violet',
    ],
    [
      'slug' => 'antikor',
      'label' => 'Антикор',
      'xPct' => 49,
      'yPct' => 22,
      'accent' => 'blue',
    ],

    // ── Правое авто (красный подъёмник) ────────────────────────
    [
      'slug' => 'tormoznaya-sistema',
      'label' => 'Тормоза',
      'xPct' => 78,
      'yPct' => 34,
      'accent' => 'red',
    ],
    [
      'slug' => 'razval-shozhdenie',
      'label' => 'Развал-схождение',
      'xPct' => 90,
      'yPct' => 33,
      'accent' => 'yellow',
    ],
    [
      'slug' => 'rusifikaciya',
      'label' => 'Русификация',
      'xPct' => 84,
      'yPct' => 17,
      'accent' => 'violet',
    ],
  ],

  // Tailwind-токены под каждый акцент (рамка / свечение / текст).
  'ACCENT_HEX' => [
    'blue' => '#178bff',
    'red' => '#ff1e1e',
    'yellow' => '#ffc400',
    'violet' => '#8b5cff',
  ],
];
