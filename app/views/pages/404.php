<?php
/**
 * Страница: 404 — не найдено.
 * Спец-переменных нет. Выводит ТОЛЬКО тело для <main> (layout оборачивает сам).
 */
$co = company();
?>
<section class="container-x flex min-h-[70svh] flex-col items-center justify-center py-28 text-center reveal">
  <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[60vh] bg-mesh opacity-40" aria-hidden="true"></div>

  <span class="eyebrow">Ошибка 404</span>

  <h1 class="mt-4 font-display text-7xl font-bold uppercase leading-none tracking-wide text-white sm:text-8xl xl:text-9xl">
    404
  </h1>
  <p class="mt-4 font-display text-2xl font-bold uppercase tracking-wide text-white sm:text-3xl">
    Страница не найдена
  </p>
  <p class="mx-auto mt-4 max-w-md text-base leading-relaxed text-steel-300">
    Возможно, страница была перемещена или удалена. Вернитесь на главную или загляните в каталог услуг автосервиса <?= e($co['name']) ?>.
  </p>

  <div class="mt-8 flex flex-wrap justify-center gap-3">
    <a href="/" class="btn-primary text-base">На главную</a>
    <a href="/uslugi" class="btn-ghost text-base">Все услуги</a>
  </div>

  <?php if (!empty($co['phoneHref'])): ?>
    <p class="mt-6 text-sm text-steel-400">
      Нужна помощь? Позвоните:
      <a href="<?= e($co['phoneHref']) ?>" class="font-semibold text-neon-300 hover:text-neon-200"><?= e($co['phone']) ?></a>
    </p>
  <?php endif; ?>
</section>
