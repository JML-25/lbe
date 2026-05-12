<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LanguageByExample — Routes
|--------------------------------------------------------------------------
*/

// Redirection racine vers la liste des fiches
Route::get('/', function () {
    return redirect()->route('cards.index');
});

// Gestion interactive (CRUD)
Route::get('/cards', function () {
    return view('cards.index');
})->name('cards.index');

// Import par lot
Route::get('/cards/import', function () {
    return view('cards.import');
})->name('cards.import');

// Révision
Route::get('/cards/review', function () {
    return view('cards.review');
})->name('cards.review');
