<?php

namespace App\Livewire;

use App\Models\Card;
use Livewire\Component;
use Livewire\WithPagination;

class CardManager extends Component
{
    use WithPagination;

    public string $filterLanguage  = '';
    public string $filterReference = '';
    public string $filterPeriod    = '';

    public ?int  $editingId       = null;
    public string $formLanguage    = '';
    public string $formFrench      = '';
    public string $formTranslation = '';
    public string $formNote        = '';
    public string $formReference   = '';
    public string $formPeriod      = '';

    public bool  $showForm      = false;
    public bool  $confirmDelete = false;
    public ?int  $deleteId      = null;

    protected array $rules = [
        'formLanguage'    => 'required|in:english,german,spanish',
        'formFrench'      => 'required|string',
        'formTranslation' => 'required|string',
        'formNote'        => 'nullable|string',
        'formReference'   => 'nullable|string|max:60',
        'formPeriod'      => 'nullable|string|max:8',
    ];

    public function updatingFilterLanguage(): void  { $this->resetPage(); }
    public function updatingFilterReference(): void { $this->resetPage(); }
    public function updatingFilterPeriod(): void    { $this->resetPage(); }

    public function render()
    {
        $query = Card::query();
        if ($this->filterLanguage)  $query->where('language', $this->filterLanguage);
        if ($this->filterReference) $query->where('reference', 'like', '%' . $this->filterReference . '%');
        if ($this->filterPeriod)    $query->where('period',    'like', '%' . $this->filterPeriod    . '%');

        return view('livewire.card-manager', [
            'cards'     => $query->orderBy('created_at', 'desc')->paginate(20, ['*'], 'page'),
            'languages' => Card::languageLabels(),
        ]);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }
	public function paginationView(): string
{
    return 'vendor.pagination.custom';
}

    public function openEdit(int $id): void
    {
        $card = Card::findOrFail($id);
        $this->editingId       = $id;
        $this->formLanguage    = $card->language;
        $this->formFrench      = $card->french;
        $this->formTranslation = $card->translation;
        $this->formNote        = $card->note ?? '';
        $this->formReference   = $card->reference;
        $this->formPeriod      = $card->period;
        $this->showForm        = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'language'    => $this->formLanguage,
            'french'      => $this->formFrench,
            'translation' => $this->formTranslation,
            'note'        => $this->formNote ?: null,
            'reference'   => $this->formReference ?: 'XXXXXXXX',
            'period'      => $this->formPeriod    ?: 'XXXXXXXX',
        ];

        if ($this->editingId) {
            Card::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Fiche mise à jour avec succès.');
        } else {
            Card::create($data);
            session()->flash('success', 'Fiche créée avec succès.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function confirmDeleteCard(int $id): void
    {
        $this->deleteId      = $id;
        $this->confirmDelete = true;
    }

    public function deleteCard(): void
    {
        if ($this->deleteId) {
            Card::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Fiche supprimée.');
        }
        $this->confirmDelete = false;
        $this->deleteId      = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = false;
        $this->deleteId      = null;
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->formLanguage    = '';
        $this->formFrench      = '';
        $this->formTranslation = '';
        $this->formNote        = '';
        $this->formReference   = '';
        $this->formPeriod      = '';
        $this->editingId       = null;
        $this->resetValidation();
    }
}
