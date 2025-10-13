@extends('layouts.app')

@push('styles')
<style>
    /* Notifications */
  .notif-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background-color: #ef4444;
    color: white;
    font-size: 0.65rem;
    padding: 0 4px;
    border-radius: 9999px;
  }
  /* Donation Modal */
  #donationModal {
    transition: opacity 0.3s ease;
  }
  #modalContent {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .modal-btn-group {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
  }

    /* Card hover effect for Quick Stats */
  .card-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .card-hover:hover {
    transform: scale(1.002); /* slightly bigger */
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.15); /* slightly stronger shadow */
  }
</style>
@endpush

@section('content')
<div class="flex min-h-screen bg-gradient-to-tr from-green-50 via-green-50 to-white">

  <!-- 🔹 Mobile Topbar -->
  <div class="fixed top-0 left-0 w-full flex items-center justify-between bg-white shadow-md p-4 md:hidden z-20">
    <button id="menuBtn" class="p-2 rounded-md border border-gray-300">
      <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
    <h1 class="font-bold text-emerald-700">MediShare</h1>
  </div>

  <!-- 🔹 Main Content -->
  <div class="flex-1 flex flex-col md:flex-row md:ml-64 mt-14 md:mt-0 p-6">

    <!-- 🔸 Left Column -->
    <div class="w-full md:w-[800px] border-r border-gray-200 pr-6 overflow-y-none">

      <!-- Top Bar -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-emerald-800">Dashboard</h2>

        <!-- Notifications -->
        <div class="relative">
           <button id="notifBtn" class="relative p-2 rounded-full hover:bg-green-100 transition">
             <i data-lucide="bell" class="w-7 h-7 text-emerald-700"></i>
             @if($unreadCount > 0)
              <span class="notif-badge">{{ $unreadCount }}</span>
             @endif
           </button>

           <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg p-4 z-10">
              <h3 class="font-semibold text-gray-800 mb-2">Notifications</h3>
              <div class="space-y-2 max-h-60 overflow-y-auto text-sm">
                 @forelse($notifications as $notif)
                    <div class="notif-item p-2 rounded-md {{ $notif['read_at'] ? 'bg-gray-50' : 'bg-green-50' }}"
                      data-id="{{ $notif['id'] }}">
                      {{ $notif['message'] }} <br>
                      <small class="text-gray-400">{{ $notif['created_at'] }}</small>
                    </div>
                    @empty
                     <div class="p-2 bg-gray-100 text-gray-500 rounded-md">No notifications</div>
                    @endforelse
             </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-6 rounded-2xl shadow card-hover text-center">
          <p class="text-gray-500 text-sm">My Donations</p>
          <p class="text-3xl font-extrabold text-sky-600">{{ $stats['total_donations'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow card-hover text-center">
          <p class="text-gray-500 text-sm">My Requests</p>
          <p class="text-3xl font-extrabold text-sky-600">{{ $stats['total_requests'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow text-center card-hover">
          <p class="text-gray-500 text-sm">Fulfilled</p>
          <p class="text-3xl font-extrabold text-green-600">{{ $stats['fulfilled_donations'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow text-center card-hover">
          <p class="text-gray-500 text-sm">Trust Score</p>
          <p class="text-3xl font-extrabold text-yellow-600">{{ $stats['trust_score'] }}%</p>
        </div>
      </div>

      <!-- Available Donations -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover mb-6">
        <h2 class="text-lg font-semibold mb-4 text-emerald-700 flex items-center">
          <i data-lucide="gift" class="w-5 h-5 mr-2 text-emerald-600"></i> Available Donations
        </h2>

        <ul class="space-y-3 text-sm max-h-48 overflow-y-auto">
          @forelse ($availableDonations as $med)
            <li class="p-3 bg-emerald-50 rounded-lg flex justify-between items-center">
              <span>
                <b>{{ $med->medicine->name ?? 'Unknown' }}</b>
                (x{{ $med->quantity }}) — Expires:
                {{ \Carbon\Carbon::parse($med->expiry_date)->format('d M Y') }}
                <br>
                <small class="text-gray-500">
                  Donor: {{ $med->donor->name ?? 'Unknown' }}
                </small>
              </span>
              <button
                class="text-sm font-medium text-emerald-700 hover:underline show-details-btn"
                data-donor="{{ $med->donor->name ?? 'Unknown' }}"
                data-location="{{ $med->donor->location ?? 'Unknown' }}"
                data-date="{{ \Carbon\Carbon::parse($med->created_at)->format('d M Y H:i') }}"
                data-quantity="{{ $med->quantity }}"
                data-expiry="{{ \Carbon\Carbon::parse($med->expiry_date)->format('d M Y') }}"
                data-medicine="{{ $med->medicine->name ?? 'Unknown' }}">
                Show Details
              </button>
            </li>
          @empty
            <li class="p-3 bg-gray-100 text-gray-500 rounded-lg">
              No donations available
            </li>
          @endforelse
        </ul>
      </div>

      <!-- Donation Details Modal -->
      <div id="donationModal"
           class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center px-4">
        <div id="modalContent"
             class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 transform scale-90 opacity-0 transition-all duration-300 ease-in-out relative">

          <button id="closeModal"
                  class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>

          <h3 class="text-xl font-semibold mb-4 text-emerald-700 flex items-center">
            <i data-lucide="gift" class="w-5 h-5 mr-2"></i> Donation Details
          </h3>

          <div class="space-y-2 text-gray-700 text-sm">
            <p><b>Medicine:</b> <span id="modalMedicine"></span></p>
            <p><b>Quantity:</b> <span id="modalQuantity"></span></p>
            <p><b>Expires:</b> <span id="modalExpiry"></span></p>
            <p><b>Donor:</b> <span id="modalDonor"></span></p>
            <p><b>Location:</b> <span id="modalLocation"></span></p>
            <p><b>Posted on:</b> <span id="modalDate"></span></p>
          </div>

          <div class="modal-btn-group">
            <button id="requestDonationBtn"
                    class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl shadow-md transition">
              Request
            </button>
          </div>
        </div>
      </div>

      <!-- Open Requests -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover">
        <h2 class="text-lg font-semibold mb-4 text-sky-700 flex items-center">
          <i data-lucide="inbox" class="w-5 h-5 mr-2 text-sky-600"></i> Open Requests
        </h2>

        <ul class="space-y-3 text-sm max-h-48 overflow-y-auto">
          @forelse ($openRequests as $req)
            <li class="p-3 bg-sky-50 rounded-lg flex justify-between items-center">
              <span>
                {{ $req->requester->name ?? 'Unknown' }} needs
                <b>{{ $req->medicine->name ?? 'Unknown Medicine' }}</b>,
                remaining quantity:
                <b>{{ $req->quantity_remaining }}</b>
              </span>
              <button class="text-xs font-medium text-sky-700 hover:underline offer-request-btn"
                      data-id="{{ $req->id }}">Offer
              </button>
            </li>
          @empty
            <li class="p-3 bg-gray-100 text-gray-500 rounded-lg">No open requests</li>
          @endforelse
        </ul>
      </div>

    </div>

    <!-- 🔸 Right Column -->
    <div class="flex-1 p-6 w-full md:w-[800px] overflow-y-none">

      <!-- Urgent Requests -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover mb-6">
        <h2 class="text-lg font-semibold mb-4 text-red-700 flex items-center">
          <i data-lucide="alert-triangle" class="w-5 h-5 mr-2 text-red-600"></i> Urgent Requests
        </h2>

        <div class="grid grid-cols-1 gap-4">
          @forelse ($urgentRequests as $urgent)
            <div class="p-4 bg-red-50 rounded-xl flex justify-between items-center">
              <span>
                {{ $urgent->requester->name ?? 'Someone' }} needs
                <b>{{ $urgent->medicine_name }}</b>
                ({{ $urgent->quantity_remaining }} left)
              </span>
              <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm help-request-btn"
                    data-id="{{ $urgent->id }}">Help
              </button>
            </div>
          @empty
            <p class="text-gray-500">No urgent requests</p>
          @endforelse
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover">
        <h2 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
          <i data-lucide="history" class="w-5 h-5 mr-2 text-gray-600"></i> My Recent Activity
        </h2>

        <ul class="space-y-3 text-sm">
          @forelse ($recentActivity as $activity)
            <li class="p-3 bg-gray-50 rounded-lg">
              @if($activity['type'] === 'donation')
                You have <b>donated</b> <b>{{ $activity['medicine_name'] }}</b>
                (x{{ $activity['quantity'] }}) — Expires at:
                {{ \Carbon\Carbon::parse($activity['expiry_date'])->format('d M Y') }}.
              @elseif($activity['type'] === 'request')
                You have <b>requested</b> <b>{{ $activity['medicine_name'] }}</b>
                (x{{ $activity['quantity'] }}) on
                {{ \Carbon\Carbon::parse($activity['created_at'])->format('d M Y H:i') }}.
              @endif
            </li>
          @empty
            <li class="p-3 bg-gray-100 text-gray-500 rounded-lg">No recent activity</li>
          @endforelse
        </ul>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
  // Notifications
  const notifBtn = document.getElementById("notifBtn");
  const notifDropdown = document.getElementById("notifDropdown");
  notifBtn.addEventListener("click", () => notifDropdown.classList.toggle("hidden"));
  document.addEventListener("click", (e) => {
    if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
      notifDropdown.classList.add("hidden");
    }
  });

  // Mark notification as read
document.querySelectorAll('.notif-item').forEach(item => {
    item.addEventListener('click', () => {
        const notifId = item.dataset.id;
        if (!notifId) return;

        // Optimistically mark as read
        item.classList.remove('bg-green-50');
        item.classList.add('bg-gray-50');

        // Update unread count
        const badge = document.querySelector('.notif-badge');
        if (badge) {
            let count = parseInt(badge.innerText);
            if (count > 1) badge.innerText = count - 1;
            else badge.remove();
        }

        // Send AJAX to backend
        fetch(`/notifications/${notifId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(res => res.json())
        .then(data => console.log('Marked as read', data))
        .catch(err => console.error(err));
    });
});

  // Donation Modal
  const donationModal = document.getElementById('donationModal');
  const modalContent = document.getElementById('modalContent');
  const closeModal = document.getElementById('closeModal');
  const requestDonationBtn = document.getElementById('requestDonationBtn');


  document.querySelectorAll('.show-details-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('modalMedicine').innerText = btn.dataset.medicine;
      document.getElementById('modalQuantity').innerText = btn.dataset.quantity;
      document.getElementById('modalExpiry').innerText = btn.dataset.expiry;
      document.getElementById('modalDonor').innerText = btn.dataset.donor;
      document.getElementById('modalLocation').innerText = btn.dataset.location;
      document.getElementById('modalDate').innerText = btn.dataset.date;

       // Save donation id for request button
      requestDonationBtn.dataset.id = btn.dataset.id;

      donationModal.classList.remove('hidden');
      setTimeout(() => modalContent.classList.remove('scale-90', 'opacity-0'), 10);
    });
  });

  function closeDonationModal() {
    modalContent.classList.add('scale-90', 'opacity-0');
    setTimeout(() => donationModal.classList.add('hidden'), 200);
  }

  closeModal.addEventListener('click', closeDonationModal);
  donationModal.addEventListener('click', e => {
    if (e.target === donationModal) closeDonationModal();
  });

  // Request button redirects to Donations page with donation id
  requestDonationBtn.addEventListener('click', (e) => {
    const donationId = e.currentTarget.dataset.id;
    window.location.href = `/donations?highlight=${donationId}`;
  });

  // Offer button for Open Requests
document.querySelectorAll('.offer-request-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const requestId = e.currentTarget.dataset.id;
        window.location.href = `/requests?highlight=${requestId}`;
    });
});

// Help button for Urgent Requests
document.querySelectorAll('.help-request-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const requestId = e.currentTarget.dataset.id;
        window.location.href = `/requests?highlight=${requestId}`;
    });
});


</script>
@endpush
