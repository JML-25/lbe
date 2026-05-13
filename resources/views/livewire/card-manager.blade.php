<div>

    {{-- ---- Alertes flash ------------------------------------ --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ---- Confirmation suppression ------------------------ --}}
    @if ($confirmDelete)
        <div class="confirm-banner">
            <span>⚠ Confirmer la suppression de cette fiche ?</span>
            <button wire:click="deleteCard" class="btn btn-danger btn-sm">Supprimer</button>
            <button wire:click="cancelDelete" class="btn btn-secondary btn-sm">Annuler</button>
        </div>
    @endif

    {{-- ---- Formulaire create / edit ------------------------ --}}
    @if ($showForm)
        <div class="card-form">
            <h2>{{ $editingId ? 'Modifier la fiche' : 'Nouvelle fiche' }}</h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="lang">Langue *</label>
                    <select id="lang" wire:model="formLanguage"
                            class="form-control {{ $errors->has('formLanguage') ? 'error' : '' }}">
                        <option value="">— Choisir —</option>
                        @foreach($languages as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('formLanguage'))
                        <span class="form-error">{{ $errors->first('formLanguage') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="ref">Référence</label>
                    <input id="ref" type="text" wire:model="formReference" maxlength="60"
                           class="form-control {{ $errors->has('formReference') ? 'error' : '' }}"
                           placeholder="ex : Lesson1">
                    @if($errors->has('formReference'))
                        <span class="form-error">{{ $errors->first('formReference') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="per">Période</label>
                    <input id="per" type="text" wire:model="formPeriod" maxlength="8"
                           class="form-control {{ $errors->has('formPeriod') ? 'error' : '' }}"
                           placeholder="ex : 2024">
                    @if($errors->has('formPeriod'))
                        <span class="form-error">{{ $errors->first('formPeriod') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="french">Phrase en français *</label>
                <textarea id="french" wire:model="formFrench" rows="2"
                          class="form-control {{ $errors->has('formFrench') ? 'error' : '' }}"></textarea>
                @if($errors->has('formFrench'))
                    <span class="form-error">{{ $errors->first('formFrench') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="translation">Traduction *</label>
                <textarea id="translation" wire:model="formTranslation" rows="2"
                          class="form-control {{ $errors->has('formTranslation') ? 'error' : '' }}"></textarea>
                @if($errors->has('formTranslation'))
                    <span class="form-error">{{ $errors->first('formTranslation') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <textarea id="note" wire:model="formNote" rows="2"
                          class="form-control {{ $errors->has('formNote') ? 'error' : '' }}"
                          placeholder="Remarques grammaticales, mnémotechniques…"></textarea>
                @if($errors->has('formNote'))
                    <span class="form-error">{{ $errors->first('formNote') }}</span>
                @endif
            </div>

            <div class="form-actions">
                <button wire:click="save" class="btn btn-success">
                    {{ $editingId ? 'Enregistrer' : 'Créer la fiche' }}
                </button>
                <button wire:click="cancelForm" class="btn btn-secondary">Annuler</button>
            </div>
        </div>
    @else
        <div style="margin-bottom:1rem;">
            <button wire:click="openCreate" class="btn btn-primary">+ Nouvelle fiche</button>
        </div>
    @endif

    {{-- ---- Filtres ----------------------------------------- --}}
    <div class="filter-panel">
        <div class="form-group">
            <label>Langue</label>
            <select wire:model.live="filterLanguage" class="form-control">
                <option value="">Toutes</option>
                @foreach($languages as $code => $label)
                    <option value="{{ $code }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Référence</label>
            <input type="text" wire:model.live.debounce.400ms="filterReference"
                   class="form-control" placeholder="Filtrer…">
        </div>
        <div class="form-group">
            <label>Période</label>
            <input type="text" wire:model.live.debounce.400ms="filterPeriod"
                   class="form-control" placeholder="Filtrer…">
        </div>
    </div>

    {{-- ---- Tableau des fiches ------------------------------ --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Langue</th>
                    <th>Français</th>
                    <th>Traduction</th>
                    <th>Note</th>
                    <th>Référence</th>
                    <th>Période</th>
                    <th>Créée le</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cards as $card)
                    <tr>
                        <td><span class="badge-lang">{{ ucfirst($card->language) }}</span></td>
                        <td class="td-truncate" title="{{ $card->french }}">{{ $card->french }}</td>
                        <td class="td-truncate" title="{{ $card->translation }}">{{ $card->translation }}</td>
                        <td class="td-truncate" title="{{ $card->note }}">{{ $card->note ?? '—' }}</td>
                        <td>{{ $card->reference }}</td>
                        <td>{{ $card->period }}</td>
                        <td style="white-space:nowrap">{{ $card->created_at->format('d/m/Y') }}</td>
                        <td class="td-actions">
                            <button wire:click="openEdit({{ $card->id }})"
                                    class="btn btn-secondary btn-sm">Éditer</button>
                            <button wire:click="confirmDeleteCard({{ $card->id }})"
                                    class="btn btn-danger btn-sm">Suppr.</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2rem;color:var(--color-text-muted);">
                            Aucune fiche trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ---- Pagination -------------------------------------- --}}
    <div class="pagination-wrapper">
        {{ $cards->links() }}
    </div>

</div>
