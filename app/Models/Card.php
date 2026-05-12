<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'language',
        'french',
        'translation',
        'note',
        'reference',
        'period',
    ];

    /**
     * Langues disponibles dans l'application.
     */
    public static function availableLanguages(): array
    {
        return ['english', 'german', 'spanish'];
    }

    /**
     * Labels affichés pour chaque langue.
     */
    public static function languageLabels(): array
    {
        return [
            'english' => 'Anglais',
            'german'  => 'Allemand',
            'spanish' => 'Espagnol',
        ];
    }
}
