<div>
    <div class="import-panel">

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if (!$processed)
            {{-- ---- Formulaire d'import ----------------------- --}}
            <p style="margin-bottom:1.2rem;color:var(--color-text-muted);">
                Sélectionnez un fichier <strong>JSON</strong> contenant les fiches à importer.
                Les champs <code>language</code>, <code>french</code> et <code>translation</code>
                sont obligatoires. Les champs <code>reference</code> et <code>period</code>
                sont optionnels (défaut : <code>XXXXXXXX</code>).
            </p>

            <div class="form-group">
                <label for="jsonFile">Fichier JSON</label>
                <input id="jsonFile" type="file" wire:model="jsonFile" accept=".json"
                       class="form-control {{ $errors->has('jsonFile') ? 'error' : '' }}">
                @if($errors->has('jsonFile'))
                    <span class="form-error">{{ $errors->first('jsonFile') }}</span>
                @endif
            </div>

            <div style="margin-top:1rem;">
                <button wire:click="import"
                        wire:loading.attr="disabled"
                        class="btn btn-primary">
                    <span wire:loading.remove wire:target="import">Lancer l'import</span>
                    <span wire:loading wire:target="import">Import en cours…</span>
                </button>
            </div>

            <details style="margin-top:1.5rem;">
                <summary style="cursor:pointer;color:var(--color-primary);font-weight:600;">
                    Voir le format JSON attendu
                </summary>
                <pre style="margin-top:0.7rem;background:#f4f7fb;padding:1rem;border-radius:6px;font-size:0.83rem;overflow-x:auto;">[
  {
    "language": "english",
    "french": "La maison est grande.",
    "translation": "The house is big.",
    "note": "Adjectif en fin de groupe nominal.",
    "reference": "Lesson1",
    "period": "2024"
  },
  {
    "language": "german",
    "french": "Je mange une pomme.",
    "translation": "Ich esse einen Apfel."
  }
]</pre>
                <p style="font-size:0.82rem;color:var(--color-text-muted);margin-top:0.5rem;">
                    Langues acceptées : <strong>english</strong>, <strong>german</strong>, <strong>spanish</strong>
                </p>
            </details>

        @else
            {{-- ---- Résumé d'import --------------------------- --}}
            <h2 style="font-size:1.1rem;font-weight:700;color:var(--color-primary);margin-bottom:0.8rem;">
                Résultat de l'import
            </h2>

            <div class="import-summary">
                <div class="import-stat read">
                    <span class="stat-num">{{ $countRead }}</span>
                    <span class="stat-label">Enregistrements lus</span>
                </div>
                <div class="import-stat added">
                    <span class="stat-num">{{ $countAdded }}</span>
                    <span class="stat-label">Ajoutés</span>
                </div>
                <div class="import-stat rejected">
                    <span class="stat-num">{{ $countRejected }}</span>
                    <span class="stat-label">Rejetés</span>
                </div>
            </div>

            {{-- Détail des erreurs — utilise $importErrors (propriété du composant) --}}
            @if (!empty($importErrors))
                <div class="import-errors">
                    <details open>
                        <summary>{{ $countRejected }} erreur(s) détaillée(s)</summary>
                        <ul>
                            @foreach($importErrors as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </details>
                </div>
            @endif

            <p style="font-size:0.83rem;color:var(--color-text-muted);margin-top:1rem;">
                Le détail complet a été enregistré dans le fichier log sur le serveur
                (<code>storage/logs/import_*.log</code>).
            </p>

            <div style="margin-top:1.2rem;">
                <button wire:click="$set('processed', false)" class="btn btn-primary">
                    Nouvel import
                </button>
                <a href="{{ route('cards.index') }}" class="btn btn-secondary" style="margin-left:0.5rem;">
                    Voir les fiches
                </a>
            </div>
        @endif

    </div>
</div>
