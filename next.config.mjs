/** @type {import('next').NextConfig} */

// Режим статического экспорта для cPanel/Apache включается переменной BUILD_TARGET=static.
// В этом режиме output:'export' + images.unoptimized, а headers() НЕ подключается
// (Next.js не поддерживает headers() при output:'export' и билд падает с ошибкой).
const isStatic = process.env.BUILD_TARGET === "static";

const nextConfig = {
  reactStrictMode: true,
  poweredByHeader: false,
  compress: true,
  images: {
    formats: ["image/avif", "image/webp"],
    // При статическом экспорте оптимизатор изображений недоступен — отдаём как есть.
    ...(isStatic ? { unoptimized: true } : {}),
  },
  ...(isStatic
    ? {
        // Статическая сборка для shared-хостинга (cPanel/Apache).
        output: "export",
        trailingSlash: true,
      }
    : {
        // Vercel (Node SSR) — поведение как было: кэш-заголовки через headers().
        async headers() {
          return [
            {
              source: "/:all*(svg|jpg|jpeg|png|webp|avif|woff2)",
              headers: [
                {
                  key: "Cache-Control",
                  value: "public, max-age=31536000, immutable",
                },
              ],
            },
          ];
        },
      }),
};

export default nextConfig;
