#!/usr/bin/env bash
# ============================================================
#  Инициализация git и создание рабочей ветки для Livada Center
#  Запуск:  bash scripts/init-git.sh   (из корня проекта)
# ============================================================
set -e
cd "$(dirname "$0")/.."

echo "[1/5] Очистка возможного повреждённого .git..."
rm -rf .git __t.txt 2>/dev/null || true

echo "[2/5] git init..."
git init
git branch -M main

echo "[3/5] Первый коммит в main..."
git add -A
git commit -m "chore: initial Next.js scaffold (Livada Center)"

echo "[4/5] Создание рабочей ветки..."
git checkout -b feature/website-seo-geo

echo "[5/5] Готово. Текущая ветка:"
git branch --show-current

echo
echo "Далее подключите удалённый репозиторий:"
echo "  git remote add origin ВАШ_URL"
echo "  git push -u origin feature/website-seo-geo"
