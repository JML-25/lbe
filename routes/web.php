<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('cards.index'));

Route::get('/cards',        fn() => view('cards.index'))->name('cards.index');
Route::get('/cards/import', fn() => view('cards.import'))->name('cards.import');
Route::get('/cards/review', fn() => view('cards.review'))->name('cards.review');

Route::get('/offline',      fn() => view('offline.index'))->name('offline');