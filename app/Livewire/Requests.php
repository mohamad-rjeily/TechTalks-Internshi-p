<?php
namespace App\Livewire;

use App\Models\Request;
use Livewire\Component;
use App\Models\AuditLog;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;

class Requests extends Component
{
    public $activeTab = 'my';

    public $flashVisible = false;
    public $flashMessage = null;
    public $flashType = 'success';

    public $medicine_id = '';
    public $quantity_remaining = '';
    public $message = '';
    public $medicines = [];

    public $showCreateModal = false;

    public function mount()
    {
        $this->medicines = Medicine::orderBy('name')->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function submit()
    {
        $this->validate([
            'medicine_id'        => 'required|exists:medicines,id',
            'quantity_remaining' => 'required|integer|min:0',
            'message'            => 'nullable|string|max:500',
        ]);

        $req = Request::create([
            'medicine_id'        => $this->medicine_id,
            'requester_id'       => Auth::id(),
            'donor_id'           => null,
            'quantity_remaining' => $this->quantity_remaining,
            'message'            => $this->message,
            'status'             => 'pending',
        ]);

        AuditLog::create([
            'actor_id'    => Auth::id(),
            'action_type' => 'request_created',
            'target_type' => 'request',
            'target_id'   => $req->id,
            'details'     => ['quantity_remaining' => $this->quantity_remaining],
        ]);

        $this->reset(['medicine_id', 'quantity_remaining', 'message']);
        $this->setFlash('Request created successfully.');
        $this->dispatch('requestCreated');
        $this->showCreateModal = false;
    }

    public function resetForm()
    {
        $this->reset(['medicine_id', 'quantity_remaining', 'message']);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setFlash($message, $type = 'success')
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
        return view('livewire.requests');
    }
}