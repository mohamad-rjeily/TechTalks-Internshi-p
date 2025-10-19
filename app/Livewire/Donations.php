<?php

namespace App\Livewire;

use App\Models\Donation;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Donations extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'my';

    /** Select options */
    public $medicines = [];

    /** Flash */
    public bool $flashVisible = false;
    public ?string $flashMessage = null;
    public string $flashType = 'success';

    /** Create modal state */
    public bool $showCreateModal = false;
    public string $createMedicineId = '';
    public string $createQuantity   = '';
    public string $createExpiry     = '';
    public string $createNotes      = '';

    /** Edit modal state */
    public bool $showEditModal = false;
    public ?int $editDonationId = null;
    public ?string $editQuantity = null;
    public ?string $editExpiry = null;
    public ?string $editNotes = null;

    /** Request modal state */
    public bool $showRequestModal = false;
    public ?int $requestDonationId = null;
    public ?string $requestMessage = '';
    public ?int $requestQuantity = 1;

    /** Filters */
    public ?string $medicineFilter = '';
    public string $statusFilter = 'all';
    public ?string $fromDate = null; // created_at >= fromDate
    public ?string $toDate = null;   // expiry_date <= toDate
    public int $filtersNonce = 0;

    protected $listeners = [
        'donationCreated' => '$refresh',
    ];

    public function mount(): void
    {
        $this->medicines = Medicine::orderBy('name')->get();
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

    public function openCreateModal(?int $medicineId = null): void
    {
        $this->resetCreateForm();
        if ($medicineId) $this->createMedicineId = (string)$medicineId;
        $this->showCreateModal = true;
    }

    public function resetCreateForm(): void
    {
        $this->reset(['createMedicineId','createQuantity','createExpiry','createNotes']);
    }

    public function createDonation(): void
    {
        $this->validate([
            'createMedicineId' => 'required|exists:medicines,id',
            'createQuantity'   => 'required|integer|min:1',
            'createExpiry'     => 'required|date',
            'createNotes'      => 'nullable|string|max:500',
        ]);

        $actorId = $this->resolveActorUserId();

        try {
            $created = Donation::create([
                'medicine_id'  => (int)$this->createMedicineId,
                'donor_id'     => $actorId,
                'recipient_id' => null,
                'quantity'     => (int)$this->createQuantity,
                'status'       => 'available',
                'expiry_date'  => $this->createExpiry,
                'notes'        => $this->createNotes ?: null,
            ]);
        } catch (\Throwable $e) {
            $this->setFlash('Failed to create donation: '.$e->getMessage(), 'danger');
            return;
        }

        $this->setFlash('Donation created successfully (ID: '.$created->id.').');
        $this->showCreateModal = false;
        $this->resetCreateForm();

        $this->resetPage('myDonations');
        $this->resetPage('pendingDonations');
        $this->dispatch('$refresh');
        $this->dispatch('donationCreated');
    }

    public function resetFilters(): void
    {
        $this->medicineFilter = '';
        $this->statusFilter = 'all';
        $this->fromDate = null;
        $this->toDate = null;
        $this->filtersNonce++;
    }

    public function startEdit(int $id): void
    {
        $don = Donation::findOrFail($id);
        $this->editDonationId = $id;
        $this->editQuantity   = (string)$don->quantity;
        $this->editExpiry     = optional($don->expiry_date)?->format('Y-m-d') ?? '';
        $this->editNotes      = $don->notes;
        $this->showEditModal  = true;
    }

    public function updateDonation(): void
    {
        if (!$this->editDonationId) {
            $this->setFlash('No donation selected to update.', 'danger');
            return;
        }

        $validated = $this->validate([
            'editQuantity' => 'required|integer|min:1',
            'editExpiry'   => 'required|date',
            'editNotes'    => 'nullable|string|max:500',
        ]);

        $don = Donation::findOrFail($this->editDonationId);
        $don->update([
            'quantity'    => (int)$validated['editQuantity'],
            'expiry_date' => $validated['editExpiry'],
            'notes'       => $validated['editNotes'] ?? $don->notes,
        ]);

        $this->setFlash('Donation updated successfully.');
        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->reset(['editDonationId','editQuantity','editExpiry','editNotes','showEditModal']);
    }

    public function openRequestModal(int $donationId): void
    {
        $this->requestDonationId = $donationId;
        $this->requestMessage = '';
        $this->requestQuantity = 1;
        $this->showRequestModal = true;
    }

    public function submitRequest(): void
    {
        if (!$this->requestDonationId) {
            $this->setFlash('No donation selected.', 'danger');
            return;
        }

        // Get the donation to validate quantity
        $donation = Donation::findOrFail($this->requestDonationId);

        $this->validate([
            'requestMessage' => 'required|string|max:500',
            'requestQuantity' => 'required|integer|min:1|max:' . $donation->quantity,
        ]);

        // Here you would typically create a request record
        // For now, we'll just show a success message and redirect
        $this->setFlash("Request submitted successfully! You requested {$this->requestQuantity} units. The donor will be notified.");
        $this->cancelRequest();
        
        // Redirect to requests page using Livewire's redirect
        $this->redirect(route('requests.index'));
    }

    public function cancelRequest(): void
    {
        $this->reset(['requestDonationId', 'requestMessage', 'requestQuantity', 'showRequestModal']);
    }

    public function cancelDonation(int $donationId): void
    {
        try {
            $donation = Donation::findOrFail($donationId);
            $donation->delete();
            $this->setFlash('Donation deleted successfully.', 'danger');
        } catch (\Throwable $e) {
            $this->setFlash('Failed to delete donation: ' . $e->getMessage(), 'danger');
        }
    }

    private function loadMyDonations()
    {
        $actorId = Auth::id() ?: (int)(User::value('id') ?? 0);
        return Donation::with(['medicine','recipient'])
            ->when($actorId > 0, fn($q) => $q->where('donor_id', $actorId))
            ->when($this->medicineFilter, fn($q) => $q->where('medicine_id', (int)$this->medicineFilter))
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->fromDate, fn($q) => $q->whereDate('created_at', '>=', $this->fromDate))
            ->when($this->toDate, fn($q) => $q->whereDate('expiry_date', '<=', $this->toDate))
            ->latest();
    }

    private function loadPendingDonations()
    {
        $actorId = Auth::id() ?: (int)(User::value('id') ?? 0);
        return Donation::with(['medicine','donor'])
            ->where('status', 'available')
            ->when($actorId > 0, fn($q) => $q->where('donor_id', '!=', $actorId)) // Exclude current user's donations
            ->when($this->medicineFilter, fn($q) => $q->where('medicine_id', (int)$this->medicineFilter))
            ->when($this->fromDate, fn($q) => $q->whereDate('created_at', '>=', $this->fromDate))
            ->when($this->toDate, fn($q) => $q->whereDate('expiry_date', '<=', $this->toDate))
            ->latest();
    }

    private function setFlash(string $message, string $type = 'success'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = $type;
        $this->flashVisible = true;
    }

    public function hideFlash(): void
    {
        $this->flashVisible = false;
    }

    public function render()
    {
        return view('livewire.donations', [
            'myDonations'      => $this->loadMyDonations()->paginate(10, ['*'], 'myDonations'),
            'pendingDonations' => $this->loadPendingDonations()->paginate(10, ['*'], 'pendingDonations'),
            'medicines'        => Medicine::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
