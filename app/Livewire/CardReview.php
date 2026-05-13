<?php

namespace App\Livewire;

use App\Models\Card;
use Livewire\Component;

class CardReview extends Component
{
    public string $selectedLanguage = '';
    public string $filterReference  = '';
    public string $filterPeriodFrom = '';
    public string $filterPeriodTo   = '';
    public string $randomCount      = '';

    public bool  $reviewing    = false;
    public array $cardIds      = [];
    public int   $currentIndex = 0;
    public bool  $flipped      = false;
    public bool  $showNote     = false;

    public function render()
    {
        $card = null;
        if ($this->reviewing && !empty($this->cardIds)) {
            $card = Card::find($this->cardIds[$this->currentIndex] ?? null);
        }

        return view('livewire.card-review', [
            'languages'  => Card::languageLabels(),
            'card'       => $card,
            'totalCards' => count($this->cardIds),
            'currentNum' => $this->currentIndex + 1,
        ]);
    }

    public function startReview(): void
    {
        if (!$this->selectedLanguage) return;

        $query = Card::where('language', $this->selectedLanguage);
        if ($this->filterReference)  $query->where('reference', 'like', '%' . $this->filterReference . '%');
        if ($this->filterPeriodFrom) $query->where('period', '>=', $this->filterPeriodFrom);
        if ($this->filterPeriodTo)   $query->where('period', '<=', $this->filterPeriodTo);

        $ids = $query->pluck('id')->toArray();
        $n   = (int) $this->randomCount;

        if ($n > 0 && $n < count($ids)) {
            shuffle($ids);
            $ids = array_slice($ids, 0, $n);
        } else {
            shuffle($ids);
        }

        if (empty($ids)) {
            session()->flash('reviewInfo', 'Aucune fiche ne correspond à votre sélection.');
            return;
        }

        $this->cardIds      = $ids;
        $this->currentIndex = 0;
        $this->flipped      = false;
        $this->showNote     = false;
        $this->reviewing    = true;
    }

    public function flip(): void        { $this->flipped   = !$this->flipped; }
    public function toggleNote(): void  { $this->showNote  = !$this->showNote; }

    public function nextCard(): void
    {
        if ($this->currentIndex < count($this->cardIds) - 1) {
            $this->currentIndex++;
            $this->flipped  = false;
            $this->showNote = false;
        }
    }

    public function prevCard(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->flipped  = false;
            $this->showNote = false;
        }
    }

    public function stopReview(): void
    {
        $this->reviewing    = false;
        $this->cardIds      = [];
        $this->currentIndex = 0;
        $this->flipped      = false;
        $this->showNote     = false;
    }
}
