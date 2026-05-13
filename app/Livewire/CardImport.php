<?php

namespace App\Livewire;

use App\Models\Card;
use Livewire\Component;
use Livewire\WithFileUploads;

class CardImport extends Component
{
    use WithFileUploads;

    public $jsonFile = null;

    public bool  $processed     = false;
    public int   $countRead     = 0;
    public int   $countAdded    = 0;
    public int   $countRejected = 0;
    public array $importErrors  = [];   // NE PAS nommer $errors : conflit avec Livewire

    protected array $rules = [
        'jsonFile' => 'required|file|max:10240',
    ];

    public function render()
    {
        return view('livewire.card-import');
    }

    public function import(): void
    {
        $this->validate();

        $this->countRead     = 0;
        $this->countAdded    = 0;
        $this->countRejected = 0;
        $this->importErrors  = [];
        $this->processed     = false;

        $content = file_get_contents($this->jsonFile->getRealPath());
        $data    = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            session()->flash('error', 'Le fichier JSON est invalide : ' . json_last_error_msg());
            return;
        }
        if (!is_array($data)) {
            session()->flash('error', 'Le fichier JSON doit contenir un tableau d\'objets.');
            return;
        }

        $logPath  = storage_path('logs/import_' . date('Ymd_His') . '.log');
        $logLines = [
            '=== Import LanguageByExample === ' . now()->toDateTimeString(),
            'Fichier : ' . $this->jsonFile->getClientOriginalName(),
            str_repeat('-', 60),
        ];

        foreach ($data as $index => $row) {
            $lineNum = $index + 1;
            $this->countRead++;

            $missing = [];
            foreach (['language', 'french', 'translation'] as $required) {
                if (empty($row[$required])) $missing[] = $required;
            }
            if (!empty($missing)) {
                $msg = "Ligne {$lineNum} : REJETÉ - champs obligatoires manquants : " . implode(', ', $missing);
                $logLines[] = $msg; $this->importErrors[] = $msg; $this->countRejected++;
                continue;
            }

            if (!in_array($row['language'], Card::availableLanguages())) {
                $msg = "Ligne {$lineNum} : REJETÉ - langue invalide '{$row['language']}'. Acceptées : " . implode(', ', Card::availableLanguages());
                $logLines[] = $msg; $this->importErrors[] = $msg; $this->countRejected++;
                continue;
            }

            $card = Card::create([
                'language'    => $row['language'],
                'french'      => $row['french'],
                'translation' => $row['translation'],
                'note'        => $row['note'] ?? null,
                'reference'   => !empty($row['reference']) ? $row['reference'] : 'XXXXXXXX',
                'period'      => !empty($row['period'])    ? $row['period']    : 'XXXXXXXX',
            ]);

            $logLines[] = "Ligne {$lineNum} : AJOUTÉ (id={$card->id}) lang={$row['language']} ref=" . ($row['reference'] ?? 'XXXXXXXX');
            $this->countAdded++;
        }

        $logLines[] = str_repeat('-', 60);
        $logLines[] = "Résumé : lus={$this->countRead} | ajoutés={$this->countAdded} | rejetés={$this->countRejected}";
        $logLines[] = '=== Fin import ===';
        file_put_contents($logPath, implode(PHP_EOL, $logLines) . PHP_EOL);

        $this->processed = true;
        $this->jsonFile  = null;
    }
}
