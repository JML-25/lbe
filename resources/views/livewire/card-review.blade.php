<div>

    @if (!$reviewing)
        {{-- ===================================================
             ÉTAPE 1 : configuration de la session de révision
             ================================================== --}}
        <div class="review-setup">
            <h2>Configurer la session de révision</h2>

            @if (session('reviewInfo'))
                <div class="alert alert-info">{{ session('reviewInfo') }}</div>
            @endif

            {{-- Langue (obligatoire) --}}
            <div class="form-group">
                <label for="revLang">Langue à réviser *</label>
                <select id="revLang" wire:model.live="selectedLanguage" class="form-control">
                    <option value="">— Choisir une langue —</option>
                    @foreach($languages as $code => $label)
                        <option value="{{ $code }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <hr style="margin:1rem 0;border:none;border-top:1px solid var(--color-border);">
            <p style="font-size:0.85rem;color:var(--color-text-muted);margin-bottom:0.8rem;">
                Filtres optionnels — si non renseignés, toutes les fiches de la langue seront prises en compte.
            </p>

            <div class="form-row">
                <div class="form-group">
                    <label for="revRef">Référence (contient)</label>
                    <input id="revRef" type="text" wire:model="filterReference"
                           class="form-control" placeholder="ex : Lesson1">
                </div>
                <div class="form-group">
                    <label for="revPFrom">Période — de</label>
                    <input id="revPFrom" type="text" wire:model="filterPeriodFrom"
                           class="form-control" placeholder="ex : 2023">
                </div>
                <div class="form-group">
                    <label for="revPTo">Période — à</label>
                    <input id="revPTo" type="text" wire:model="filterPeriodTo"
                           class="form-control" placeholder="ex : 2024">
                </div>
            </div>

            <div class="form-group" style="max-width:260px;">
                <label for="revRandom">Nombre de fiches aléatoires</label>
                <input id="revRandom" type="number" wire:model="randomCount"
                       class="form-control" placeholder="Tout (laisser vide)" min="1">
                <small style="color:var(--color-text-muted);font-size:0.78rem;">
                    La sélection aléatoire respecte les filtres ci-dessus.
                </small>
            </div>

            <div style="margin-top:1rem;">
                <button wire:click="startReview"
                        @if(!$selectedLanguage) disabled @endif
                        class="btn btn-primary btn-lg">
                    Commencer la révision
                </button>
            </div>
        </div>

    @else
        {{-- ===================================================
             ÉTAPE 2 : révision des fiches
             ================================================== --}}

        @if ($card)

            {{-- Barre de progression --}}
            <div class="review-progress">
                <span class="progress-info">Fiche {{ $currentNum }} / {{ $totalCards }}</span>
                <div class="progress-bar-outer">
                    <div class="progress-bar-inner"
                         style="width: {{ $totalCards > 0 ? round(($currentNum / $totalCards) * 100) : 0 }}%">
                    </div>
                </div>
                <button wire:click="stopReview" class="btn btn-secondary btn-sm">Quitter</button>
            </div>

            {{-- Méta-info fiche --}}
            <p style="text-align:center;font-size:0.82rem;color:var(--color-text-muted);margin-bottom:0.5rem;">
                <strong>{{ ucfirst($card->language) }}</strong>
                &nbsp;·&nbsp; Réf : {{ $card->reference }}
                &nbsp;·&nbsp; Période : {{ $card->period }}
            </p>

            {{-- Flashcard cliquable --}}
            <div class="flashcard" wire:click="flip">
                <div class="flashcard-inner {{ $flipped ? 'flipped' : '' }}">
                    <span class="flashcard-label">
                        {{ $flipped ? ucfirst($card->language) : 'Français' }}
                    </span>
                    <span class="flashcard-text">
                        {{ $flipped ? $card->translation : $card->french }}
                    </span>
                    @if (!$flipped)
                        <span class="flashcard-hint">Cliquez pour voir la traduction</span>
                    @endif
                    <span class="flashcard-meta">{{ $currentNum }}/{{ $totalCards }}</span>
                </div>
            </div>

            {{-- Note --}}
            @if ($card->note)
                @if ($showNote)
                    <div class="card-note-box">
                        <strong>Note :</strong> {{ $card->note }}
                    </div>
                @endif
                <div style="text-align:center;margin-top:0.3rem;">
                    <button wire:click="toggleNote" class="btn btn-secondary btn-sm">
                        {{ $showNote ? 'Masquer la note' : 'Afficher la note' }}
                    </button>
                </div>
            @endif

            {{-- Navigation --}}
            <div class="review-actions">
                <button wire:click="prevCard"
                        @if($currentIndex === 0) disabled @endif
                        class="review-nav-btn" title="Précédente">&#8592;</button>

                <button wire:click="flip" class="btn btn-primary">
                    {{ $flipped ? 'Masquer' : 'Retourner' }}
                </button>

                <button wire:click="nextCard"
                        @if($currentIndex >= $totalCards - 1) disabled @endif
                        class="review-nav-btn" title="Suivante">&#8594;</button>
            </div>

            <p style="text-align:center;font-size:0.75rem;color:var(--color-text-muted);margin-top:0.8rem;">
                Conseil : cliquez sur la carte pour la retourner · utilisez ← → pour naviguer
            </p>

        @else
            {{-- Fin de session --}}
            <div class="review-end-banner">
                <h2>&#127881; Session terminée !</h2>
                <p>Vous avez parcouru toutes les fiches sélectionnées.</p>
                <div style="margin-top:1rem;">
                    <button wire:click="stopReview" class="btn btn-primary">
                        Nouvelle session
                    </button>
                </div>
            </div>
        @endif

    @endif

</div>
