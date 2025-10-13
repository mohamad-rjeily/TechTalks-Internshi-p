<?php

namespace App\Livewire;

use App\Models\Request;
use App\Models\Medicine;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PendingRequests extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $medicineFilter = '';
    public $requesterFilter = '';
    public $fromDate = null;
    public $toDate = null;
    public $urgentOnly = false;

    public $medicines = [];

    public $flashVisible = false;
    public $flashMessage = null;
    public $flashType = 'success';

    public $helpRequest = null;

    public $showHelpModal = false;

    public function mount()
    {
        $this->medicines = Medicine::orderBy('name')->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['medicineFilter', 'requesterFilter', 'fromDate', 'toDate', 'urgentOnly'])) {
            $this->resetPage('pendingPage');
        }
    }

    private function loadRequests()
    {
        $authId = Auth::id();
        $query = Request::with(['medicine', 'requester'])
            ->where('status', 'pending')
            ->when($authId, function ($q) use ($authId) {
                $q->where('requester_id', '!=', $authId);
            });

        if ($this->medicineFilter !== '') {
            $query->where('medicine_id', (int) $this->medicineFilter);
        }

        if ($this->requesterFilter !== '') {
            $query->whereHas('requester', function ($q) {
                $q->where('name', 'like', '%' . $this->requesterFilter . '%');
            });
        }

        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        if ($this->urgentOnly) {
            $query->where('quantity_remaining', '<=', 3); 
        }

        return $query->latest();
    }

    public function showHelp($id)
    {
        $this->helpRequest = Request::with(['medicine', 'requester'])->findOrFail($id);
        $this->showHelpModal = true;
    }

    public function acceptHelp()
    {
        if (!$this->helpRequest) return;

        if (Auth::check()) {
            $this->authorize('accept', $this->helpRequest);
        }

        $this->helpRequest->update([
            'donor_id' => Auth::id() ?: null,
            'status' => 'approved',
        ]);

        $this->setFlash('You accepted to help this request.');
        $this->helpRequest = null;

        $this->showHelpModal = false;
    }

    private function setFlash($message, $type = 'success')
    {
        $this->flashMessage = $message;
        $this->flashType = $type;
        $this->flashVisible = true;
    }

    public function hideFlash()
    {
        $this->flashVisible = false;
    }

    public function render()
    {
        $requests = $this->loadRequests()->paginate(10, ['*'], 'pendingPage');

        return view('livewire.pending-requests', [
            'pendingRequests' => $requests,
        ]);
    }
}
