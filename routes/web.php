<?php

declare(strict_types=1);

use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------------------------------------
| Публичные страницы сайта LEVADA SERVICE.
| ЧПУ на русском транслите: /uslugi, /marki, /o-nas, /kontakty.
|------------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/uslugi', [ServiceController::class, 'index'])->name('services.index');
Route::get('/uslugi/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/marki', [BrandController::class, 'index'])->name('brands.index');
Route::get('/marki/{slug}', [BrandController::class, 'show'])->name('brands.show');

Route::get('/o-nas', [PageController::class, 'about'])->name('about');
Route::get('/kontakty', [PageController::class, 'contacts'])->name('contacts');

// Приём заявки с формы -> Telegram.
Route::post('/lead', [LeadController::class, 'store'])->name('lead.store');

// Машиночитаемые файлы (генерируются динамически).
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('llms');
