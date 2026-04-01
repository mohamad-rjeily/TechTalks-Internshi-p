<?php

namespace App\Livewire;

use App\Models\Request;
use App\Models\AuditLog;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyRequests extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $medicineFilter = '';
    public $statusFilter = 'all';
    public $fromDate = null;
    public $toDate = null;

    public $medicines = [];

    public $flashVisible = false;
    public $flashMessage = null;
    public $flashType = 'success';

    public $editRequestId = null;
    public $editQuantity = null;
    public $editMessage = null;
    public $editingReq = null;

    public $showEditModal = false;


    protected $listeners = [
        'requestCreated' => '$refresh', 
    ];


    public function updated($property)
    {
        if (in_array($property, ['medicineFilter', 'statusFilter', 'fromDate', 'toDate'])) {
            $this->resetPage('myPage');
        }
    }


    public function mount()
    {
        $this->medicines = Medicine::orderBy('name')->get();
    }

    private function loadRequests()
    {
        $query = Request::with('medicine', 'donor')
            ->where('requester_id', Auth::id()); 

        if ($this->medicineFilter !== '') {
            $query->where('medicine_id', (int)$this->medicineFilter);
        }

        if ($this->statusFilter && $this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        return $query->latest();
    }

    public function cancelRequest($id)
    {
        $req = Request::findOrFail($id);
        $this->authorize('cancel', $req);

        $req->update(['status' => 'cancelled']);

        AuditLog::create([
            'actor_id'    => Auth::id(),
            'action_type' => 'request_cancelled',
            'target_type' => 'request',
            'target_id'   => $req->id,
        ]);

        $this->setFlash('Request cancelled successfully.');
    }

    public function completeRequest($id)
    {
        $req = Request::findOrFail($id);
        $this->authorize('complete', $req);

        $req->update(['status' => 'completed']);

        AuditLog::create([
            'actor_id'    => Auth::id(),
            'action_type' => 'request_completed',
            'target_type' => 'request',
            'target_id'   => $req->id,
        ]);

        $this->setFlash('Request marked as completed.');
    }

    public function startEdit($id)
    {
        $req = Request::with('medicine')->findOrFail($id);

        $this->editRequestId = $id;
        $this->editQuantity  = $req->quantity_remaining;
        $this->editMessage   = $req->message;
        $this->editingReq    = $req;
        $this->showEditModal = true;
    }

    public function updateRequest()
    {
        if (!$this->editRequestId) {
            $this->setFlash('No request selected to update.', 'danger');
            return;
        }

        $validated = $this->validate([
            'editQuantity' => 'required|integer|min:0',
            'editMessage'  => 'nullable|string|max:500',
        ]);

        $req = Request::findOrFail($this->editRequestId);
        $this->authorize('update', $req);

        $oldQty = $req->quantity_remaining;

        $req->update([
            'quantity_remaining' => $validated['editQuantity'],
            'message'            => $validated['editMessage'] ?? $req->message,
        ]);

        AuditLog::create([
            'actor_id'    => Auth::id(),
            'action_type' => 'request_updated',
            'target_type' => 'request',
            'target_id'   => $req->id,
            'details'     => [
                'old_quantity_remaining' => $oldQty,
                'new_quantity_remaining' => $validated['editQuantity'],
            ],
        ]);

        $this->setFlash('Request updated successfully.');
        $this->cancelEdit();
        $this->showEditModal = false;

    }

    public function cancelEdit()
    {
        $this->editRequestId = null;
        $this->editQuantity  = null;
        $this->editMessage   = null;
        $this->editingReq    = null;
        $this->showEditModal = false;

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
        $requests = $this->loadRequests()->paginate(10, ['*'], 'myPage');

        return view('livewire.my-requests', [
            'sentRequests' => $requests,
        ]);
    }
}