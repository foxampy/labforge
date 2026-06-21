// Кроссплатформенная статическая сборка для cPanel/Apache.
//
// Зачем скрипт нужен:
//   API-роут src/app/api/lead/route.ts помечен dynamic="force-dynamic" и
//   несовместим с output:'export'. Если оставить его на месте — `next build`
//   в статическом режиме упадёт. Поэтому на время сборки мы ВРЕМЕННО убираем
//   каталог src/app/api в сторону, собираем статику, а затем ГАРАНТИРОВАННО
//   возвращаем его на место (в блоке finally, даже если билд упал).
//
// После успешной сборки копируем PHP-обработчик формы и .htaccess в out/.

import { spawnSync } from "node:child_process";
import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, "..");

const apiDir = path.join(root, "src", "app", "api");
const apiTmp = path.join(root, ".api-tmp");
const outDir = path.join(root, "out");
const cpanelDir = path.join(root, "cpanel");

function log(msg) {
  console.log(`[build:static] ${msg}`);
}

// Перед стартом подчистим возможный «висячий» .api-tmp от прошлого упавшего запуска.
if (fs.existsSync(apiTmp)) {
  if (fs.existsSync(apiDir)) {
    // Оба каталога на месте — значит .api-tmp это мусор, удаляем его.
    log("Обнаружен старый .api-tmp рядом с существующим api — удаляю мусор.");
    fs.rmSync(apiTmp, { recursive: true, force: true });
  } else {
    // api отсутствует, а .api-tmp есть — восстановим из него (прерванный прошлый запуск).
    log("Обнаружен .api-tmp без src/app/api — восстанавливаю каталог api из него.");
    fs.renameSync(apiTmp, apiDir);
  }
}

let movedApi = false;

try {
  // 1. Временно убираем src/app/api, чтобы force-dynamic роут не ломал экспорт.
  if (fs.existsSync(apiDir)) {
    log("Временно перемещаю src/app/api -> .api-tmp (несовместим с output:'export').");
    fs.renameSync(apiDir, apiTmp);
    movedApi = true;
  } else {
    log("Каталог src/app/api не найден — пропускаю перемещение.");
  }

  // 2. Запускаем статическую сборку Next.js.
  log("Запускаю `next build` (BUILD_TARGET=static, NEXT_PUBLIC_LEAD_ENDPOINT=/lead.php)…");
  const env = {
    ...process.env,
    BUILD_TARGET: "static",
    NEXT_PUBLIC_LEAD_ENDPOINT: "/lead.php",
  };
  // shell:true обязателен на Windows, чтобы корректно нашёлся npx.
  const result = spawnSync("npx", ["next", "build"], {
    stdio: "inherit",
    env,
    shell: true,
    cwd: root,
  });

  if (result.status !== 0) {
    throw new Error(`next build завершился с кодом ${result.status}`);
  }
  log("Сборка завершена успешно.");

  // 3. Копируем PHP-обработчик и .htaccess в out/.
  if (!fs.existsSync(outDir)) {
    throw new Error("Каталог out/ не создан — сборка прошла, но экспорта нет.");
  }

  const assets = ["lead.php", ".htaccess"];
  for (const file of assets) {
    const src = path.join(cpanelDir, file);
    const dest = path.join(outDir, file);
    if (fs.existsSync(src)) {
      fs.copyFileSync(src, dest);
      log(`Скопирован ${file} -> out/${file}`);
    } else {
      log(`ВНИМАНИЕ: ${path.join("cpanel", file)} не найден — пропускаю.`);
    }
  }

  log("Готово. Содержимое out/ можно загружать в public_html на cPanel.");
} catch (err) {
  console.error(`[build:static] ОШИБКА: ${err.message}`);
  process.exitCode = 1;
} finally {
  // 4. ГАРАНТИРОВАННО возвращаем src/app/api на место.
  if (movedApi && fs.existsSync(apiTmp)) {
    if (fs.existsSync(apiDir)) {
      // На всякий случай: если api как-то воссоздался — не перезатираем, чистим tmp.
      log("Каталог api уже существует — удаляю временный .api-tmp.");
      fs.rmSync(apiTmp, { recursive: true, force: true });
    } else {
      fs.renameSync(apiTmp, apiDir);
      log("Каталог src/app/api возвращён на место.");
    }
  }
}
