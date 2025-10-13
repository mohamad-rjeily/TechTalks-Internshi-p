<?php

namespace App\Livewire;

use App\Models\Donation;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DonationPortal extends Component
{
    /** Modal visibility */
    public bool $showCreate = false;

    /** Form state */
    public string $createMedicineId = '';
    public string $createQuantity   = '';
    public string $createExpiry     = '';
    public string $createNotes      = '';

    /** Lock the medicine select when opened from Browse */
    public bool $createMedicineLocked = false;

    public $medicines = [];

    protected $listeners = [
        // Fired from the Browse page buttons
        'openDonationFromBrowse' => 'openFromBrowse',
    ];

    public function mount(): void
    {
        $this->medicines = Medicine::orderBy('name')->get();
    }

    public function openFromBrowse(int $medicineId): void
    {
        $this->resetCreateForm();
        $this->createMedicineId     = (string) $medicineId;
        $this->createMedicineLocked = true;
        $this->showCreate           = true;
    }

    public function resetCreateForm(): void
    {
        $this->reset(['createMedicineId','createQuantity','createExpiry','createNotes']);
        $this->createMedicineLocked = false;
    }

    // No return type (lets Livewire handle redirect)
    public function create()
    {
        $this->validate([
            'createMedicineId' => 'required|exists:medicines,id',
            'createQuantity'   => 'required|integer|min:1',
            'createExpiry'     => 'required|date',
            'createNotes'      => 'nullable|string|max:500',
        ]);

        $actorId = $this->resolveActorUserId();

        Donation::create([
            'medicine_id'  => (int)$this->createMedicineId,
            'donor_id'     => $actorId,
            'recipient_id' => null,
            'quantity'     => (int)$this->createQuantity,
            'status'       => 'available',
            'expiry_date'  => $this->createExpiry,
            'notes'        => $this->createNotes ?: null,
        ]);

        // Close modal and redirect to the donations page with success toast
        $this->showCreate = false;
        $this->resetCreateForm();
        session()->flash('success', 'Donation created successfully.');
        return redirect()->to('/donations');
    }

    private function resolveActorUserId(): int
    {
        if ($id = Auth::id()) return (int)$id;
        if ($existing = User::value('id')) return (int)$existing;

        $guest = User::create([
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'password' => bcrypt('password'),
        ]);

        return (int)$guest->id;
    }

    public function render()
    {
        return view('livewire.donation-portal');
    }
}


