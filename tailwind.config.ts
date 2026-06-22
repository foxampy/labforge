import type { Config } from "tailwindcss";

const config: Config = {
  // Чистый PHP-проект: сканируем шаблоны и корневые PHP-файлы.
  content: ["./app/**/*.php", "./*.php"],
  theme: {
    extend: {
      colors: {
        // Near-black «garage» base (фон сцены: #050505 / #0A0A0A / #111111)
        ink: {
          950: "#050505",
          900: "#0a0a0a",
          800: "#111111",
          700: "#181818",
          600: "#222222",
        },
        // Бетонно-серый / металлик (нейтральный, для поверхностей)
        steel: {
          50: "#f7f7f8",
          100: "#ededee",
          200: "#d4d4d6",
          300: "#b8b8b8", // вторичный текст из спеки
          400: "#8c8c90",
          500: "#6a6a6e",
          600: "#4d4d51",
          700: "#363639",
          800: "#242426",
          900: "#161618",
        },
        // МОНОХРОМ: все «акцентные» рампы сведены к белому/серому.
        // Раньше brand=синий — теперь чисто-белый акцент интерфейса.
        brand: {
          50: "#ffffff",
          100: "#fafafa",
          200: "#f0f0f0",
          300: "#e5e5e5",
          400: "#ffffff",
          500: "#ffffff",
          600: "#e5e5e5",
          700: "#a3a3a3",
          800: "#525252",
          900: "#262626",
        },
        // Раньше accent=жёлтый — теперь белый (монохром).
        accent: {
          400: "#ffffff",
          500: "#ffffff",
          600: "#e5e5e5",
        },
        // Раньше signal=красный — теперь белый (никаких красных букв).
        signal: {
          400: "#ffffff",
          500: "#ffffff",
          600: "#e5e5e5",
        },
        // Раньше neon=фиолет (hover/интерактив) — теперь белый акцент.
        neon: {
          300: "#ffffff",
          400: "#ffffff",
          500: "#ffffff",
          600: "#e5e5e5",
        },
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"],
        display: ["var(--font-display)", "var(--font-sans)", "sans-serif"],
        // Тяжёлый дисплейный вордмарк «LEVADA SERVICE» (латиница) — Anton.
        wordmark: ["var(--font-wordmark)", "var(--font-display)", "sans-serif"],
      },
      boxShadow: {
        // Монохромное «свечение» — мягкий белый ореол.
        glow: "0 0 18px rgba(255,255,255,0.18)",
        "glow-hover":
          "0 0 12px rgba(255,255,255,0.28), 0 0 32px rgba(255,255,255,0.14)",
        card: "0 24px 60px -24px rgba(0,0,0,0.85)",
        metal:
          "inset 0 1px 0 rgba(255,255,255,0.08), inset 0 -1px 0 rgba(0,0,0,0.45), 0 20px 50px -24px rgba(0,0,0,0.8)",
      },
      backgroundImage: {
        "grid-fade":
          "radial-gradient(ellipse at top, rgba(255,255,255,0.10), transparent 55%)",
        // bg-mesh — монохромные белые радиальные подсветки.
        mesh: "radial-gradient(at 18% 18%, rgba(255,255,255,0.10) 0, transparent 50%), radial-gradient(at 82% 0%, rgba(255,255,255,0.06) 0, transparent 45%), radial-gradient(at 62% 92%, rgba(255,255,255,0.08) 0, transparent 50%)",
        metal:
          "linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.015) 40%, rgba(0,0,0,0.28))",
      },
      keyframes: {
        floaty: {
          "0%,100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-12px)" },
        },
        shimmer: {
          "100%": { transform: "translateX(100%)" },
        },
        "fade-up": {
          "0%": { opacity: "0", transform: "translateY(24px)" },
          "100%": { opacity: "1", transform: "translateY(0)" },
        },
        "pulse-ring": {
          "0%": { transform: "scale(0.85)", opacity: "0.65" },
          "100%": { transform: "scale(1.7)", opacity: "0" },
        },
        // Мягкое неоновое «дыхание» для хотспотов
        "neon-breathe": {
          "0%,100%": { opacity: "0.55", filter: "brightness(1)" },
          "50%": { opacity: "1", filter: "brightness(1.35)" },
        },
      },
      animation: {
        floaty: "floaty 6s ease-in-out infinite",
        "fade-up": "fade-up 0.7s cubic-bezier(0.22,1,0.36,1) both",
        "pulse-ring": "pulse-ring 2.4s ease-out infinite",
        "neon-breathe": "neon-breathe 2.6s ease-in-out infinite",
      },
    },
  },
  plugins: [],
};

export default config;
