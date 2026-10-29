<?php

use App\Http\Controllers\BuildController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\PartController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/laptops', [LaptopController::class, 'index'])->name('laptops.index');
Route::get('/laptops/{slug}', [LaptopController::class, 'show'])->name('laptops.show');

Route::get('/parts', [PartController::class, 'index'])->name('parts.index');
Route::get('/parts/{part}/{slug?}', [PartController::class, 'show'])->whereNumber('part')->name('parts.show');

Route::get('/build', BuildController::class)->name('build');

Route::view('/contact', 'contact')->name('contact');
