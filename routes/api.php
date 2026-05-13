<?php

use Illuminate\Support\Facades\Route;
use App\Models\Card;

/*
|--------------------------------------------------------------------------
| API Routes — LanguageByExample
|--------------------------------------------------------------------------
| Déclaré dans bootstrap/app.php :
|   api: __DIR__.'/../routes/api.php',
*/

Route::get('/cards/export', function () {
    $cards = Card::select('id', 'language', 'french', 'translation', 'note', 'reference', 'period')
        ->orderBy('language')
        ->orderBy('reference')
        ->get();

    return response()->json([
        'count'       => $cards->count(),
        'exported_at' => now()->toISOString(),
        'cards'       => $cards,
    ]);
});
