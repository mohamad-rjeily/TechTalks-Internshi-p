<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RequestCreateModal extends Component
{
    public bool $show = false;

    public string $medicineId   = '';
    public string $medicineName = '';   // shown read-only when locked
    public string $quantity     = '';
    public string $message      = '';

    public $medicines = [];
    public bool $lockMedicine = false;

    protected $listeners = [
        'openRequestFromBrowse' => 'open',
        'openRequestModal'      => 'open',
    ];

    public function mount(): void
    {
        $this->medicines = Medicine::orderBy('name')->get();
    }

    /** Open from browse; payload may be 5, [5], or ['id'=>5] */
    public function open($payload = null): void
    {
        $id = $this->extractId($payload);

        $this->resetForm();

        if ($id) {
            $this->medicineId   = (string) $id;
            $this->medicineName = optional(Medicine::find($id))->name ?? '';
            $this->lockMedicine = true;
        } else {
            $this->lockMedicine = false;
        }

        $this->show = true;
    }

    public function resetForm(): void
    {
        $this->reset(['medicineId', 'medicineName', 'quantity', 'message']);
        $this->lockMedicine = false;
    }

    public function submit()
    {
        $this->validate([
            'medicineId' => 'required|exists:medicines,id',
            'quantity'   => 'required|integer|min:1',
            'message'    => 'nullable|string|max:500',
        ]);

        $actorId = $this->resolveActorUserId();

        RequestModel::create([
            'medicine_id'        => (int) $this->medicineId,
            'requester_id'       => $actorId,
            'donor_id'           => null,
            'quantity_remaining' => (int) $this->quantity,
            'message'            => $this->message ?: null,
            'status'             => 'pending',
        ]);

        // Close + navigate to the public requests page with success message
        $this->show = false;
        $this->resetForm();
        session()->flash('success', 'Request created successfully.');
        return redirect()->route('requests');
    }

    private function resolveActorUserId(): int
    {
        if ($id = Auth::id()) return (int) $id;
        if ($existing = User::value('id')) return (int) $existing;

        $guest = User::create([
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'password' => bcrypt('password'),
        ]);

        return (int) $guest->id;
    }

    /** @return int|null */
    private function extractId($payload)
    {
        if (is_array($payload)) {
            if (array_key_exists('id', $payload)) return (int) $payload['id'];
            if (array_key_exists(0, $payload))   return (int) $payload[0];
            return null;
        }
        return $payload !== null ? (int) $payload : null;
    }

    public function render()
    {
        return view('livewire.request-create-modal');
    }
}
