<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Category;
use Livewire\Component;

class BrowseMedicines extends Component
{
    public $search = '';
    public $selectedCategory = '';
    public $medicines = [];
    public $categories = [];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadMedicines();
    }

    public function updatedSearch()
    {
        $this->loadMedicines();
    }

    public function updatedSelectedCategory()
    {
        $this->loadMedicines();
    }

    private function loadMedicines()
    {
        $query = Medicine::with('category');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('brand', 'like', '%' . $this->search . '%')
                  ->orWhere('form', 'like', '%' . $this->search . '%')
                  ->orWhere('strength', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        $this->medicines = $query->orderBy('name')->get();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->loadMedicines();
    }

    public function render()
    {
        return view('livewire.browse-medicines');
    }
}
