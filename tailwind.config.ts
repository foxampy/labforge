import type { Config } from "tailwindcss";

const config: Config = {
  content: ["./src/**/*.{ts,tsx,mdx}"],
  theme: {
    extend: {
      colors: {
        // Тёмная «бетонно-стальная» база (технический киберпанк)
        ink: {
          950: "#080a0f",
          900: "#0d1017",
          800: "#141925",
          700: "#1e2535",
          600: "#2a3346",
        },
        // Бетонно-серый / металлик (основной нейтральный)
        steel: {
          50: "#f5f7fa",
          100: "#e9edf3",
          200: "#cdd5e0",
          300: "#a7b2c2",
          400: "#7c899c",
          500: "#5b6678",
          600: "#454e5d",
          700: "#343b48",
          800: "#262c36",
          900: "#1a1f27",
        },
        // Синий — дополнительный / ведущий акцент интерфейса
        brand: {
          50: "#eaf2ff",
          100: "#d3e3ff",
          200: "#a6c6ff",
          300: "#73a3ff",
          400: "#4585ff",
          500: "#2f74ff",
          600: "#1a5be6",
          700: "#1448b4",
          800: "#143c8c",
          900: "#13346f",
        },
        // Жёлтый — акцент
        accent: {
          400: "#ffd24a",
          500: "#ffbe1a",
          600: "#e6a200",
        },
        // Красный — акцент / критичные системы
        signal: {
          400: "#f87171",
          500: "#ef4444",
          600: "#dc2626",
        },
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"],
        display: ["var(--font-display)", "var(--font-sans)", "sans-serif"],
      },
      boxShadow: {
        glow: "0 0 0 1px rgba(47,116,255,0.30), 0 20px 60px -20px rgba(47,116,255,0.55)",
        card: "0 24px 60px -24px rgba(5,9,18,0.75)",
        metal:
          "inset 0 1px 0 rgba(255,255,255,0.10), inset 0 -1px 0 rgba(0,0,0,0.35), 0 20px 50px -24px rgba(0,0,0,0.7)",
      },
      backgroundImage: {
        "grid-fade":
          "radial-gradient(ellipse at top, rgba(47,116,255,0.20), transparent 55%)",
        mesh: "radial-gradient(at 18% 18%, rgba(47,116,255,0.30) 0, transparent 50%), radial-gradient(at 82% 0%, rgba(239,68,68,0.14) 0, transparent 45%), radial-gradient(at 62% 92%, rgba(255,190,26,0.16) 0, transparent 50%)",
        // Металлический градиент для поверхностей
        metal:
          "linear-gradient(160deg, rgba(255,255,255,0.10), rgba(255,255,255,0.02) 40%, rgba(0,0,0,0.18))",
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
          "0%": { transform: "scale(0.85)", opacity: "0.6" },
          "100%": { transform: "scale(1.6)", opacity: "0" },
        },
      },
      animation: {
        floaty: "floaty 6s ease-in-out infinite",
        "fade-up": "fade-up 0.7s cubic-bezier(0.22,1,0.36,1) both",
        "pulse-ring": "pulse-ring 2.4s ease-out infinite",
      },
    },
  },
  plugins: [],
};

export default config;
