<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;

class Donations extends Component
{
    public $donations;

    public function mount()
    {
        $this->donations = Donation::with(['medicine', 'donor', 'recipient'])->get();
    }

    public function render()
    {
        return view('livewire.donations');
    }
}