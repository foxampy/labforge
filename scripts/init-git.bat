@echo off
REM ============================================================
REM  Инициализация git и создание рабочей ветки для Livada Center
REM  Запустите этот файл из корня проекта (двойной клик или из cmd)
REM ============================================================
setlocal

cd /d "%~dp0.."

echo [1/5] Очистка возможного повреждённого .git...
if exist ".git" rmdir /s /q ".git"
if exist "__t.txt" del /q "__t.txt"

echo [2/5] git init...
git init
git branch -M main

echo [3/5] Первый коммит в main...
git add -A
git commit -m "chore: initial Next.js scaffold (Livada Center)"

echo [4/5] Создание рабочей ветки...
git checkout -b feature/website-seo-geo

echo [5/5] Готово. Текущая ветка:
git branch --show-current

echo.
echo Далее: подключите удалённый репозиторий, например:
echo   git remote add origin ВАШ_URL
echo   git push -u origin feature/website-seo-geo
echo.
pause
