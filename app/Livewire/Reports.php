<?php
namespace App\Livewire;

use App\Models\Report;
use App\Models\User;
use App\Models\Medicine;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $targetTypeFilter = '';
    public $statusFilter = 'all';
    public $fromDate = null;
    public $toDate = null;

    // Create form
    public $target_type = '';
    public $target_id = '';
    public $reason = '';
    public $targets = [];

    // Modal state
    public $viewingReport = null;

    public $showCreateModal = false;
    public $showViewModal = false;

    // Flash
    public $flashVisible = false;
    public $flashMessage = null;
    public $flashType = 'success';

    public function mount()
    {
        $this->loadTargets();
    }

    public function updated($property)
    {
        if (in_array($property, ['targetTypeFilter', 'statusFilter', 'fromDate', 'toDate'])) {
            $this->resetPage();
        }

        if ($property === 'target_type') {
            $this->loadTargets();
        }
    }

    public function loadTargets()
    {
        $this->targets = match ($this->target_type) {
            'medicine' => Medicine::orderBy('name')->get(),
            'user' => User::orderBy('name')->get(),
            default => [],
        };
    }

    public function submit()
    {
        $this->validate([
            'target_type' => 'required|in:user,medicine',
            'target_id'   => 'required|integer',
            'reason'      => 'required|string|min:10',
        ]);

        $report = Report::create([
            'reported_id' => Auth::id(),
            'target_type' => $this->target_type,
            'target_id'   => $this->target_id,
            'reason'      => $this->reason,
        ]);

        AuditLog::create([
            'actor_id' => Auth::id(),
            'action_type' => 'report_created',
            'target_type' => 'report',
            'target_id' => $report->id,
            'details' => [
                'target_type' => $this->target_type,
                'target_id' => $this->target_id,
                'reason' => $this->reason,
            ],
        ]);

        $this->reset(['target_type', 'target_id', 'reason']);
        $this->setFlash('Report submitted successfully.');
        $this->showCreateModal = false;
    }

    public function startView($id)
    {
        $report = Report::findOrFail($id);

        $this->viewingReport = $report;
        $this->showViewModal = true;
    }

    public function cancelView()
    {
        $this->viewingReport = null;
        $this->showViewModal = false;
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

    public function getReportsProperty()
    {
        return Report::with('target')
            ->where('reported_id', Auth::id())
            ->when($this->targetTypeFilter, fn($q) => $q->where('target_type', $this->targetTypeFilter))
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->fromDate, fn($q) => $q->whereDate('created_at', '>=', $this->fromDate))
            ->when($this->toDate, fn($q) => $q->whereDate('created_at', '<=', $this->toDate))
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.reports', [
            'reports' => $this->reports,
        ]);
    }
}
