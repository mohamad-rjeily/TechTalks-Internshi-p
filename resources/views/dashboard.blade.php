<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MediShare Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    /* Hover effect for cards */
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    /* Scrollable right column */
    .scrollable-column {
      height: 100vh;
      overflow-y: auto;
      padding-bottom: 2rem;
    }
    /* Sidebar links */
    .sidebar-link {
      transition: all 0.3s;
    }
    .sidebar-link:hover {
      background: rgba(16, 185, 129, 0.15);
      color: #10b981;
    }
    .sidebar-active {
      background: linear-gradient(90deg, #10b981, #059669);
      color: white;
    }
    /* Notification badge */
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
  </style>
</head>
<body class="bg-gradient-to-tr from-green-50 via-green-50 to-white min-h-screen flex">

  <!-- Sidebar -->
  <aside class="w-64 bg-gradient-to-b from-emerald-700 to-emerald-900 text-gray-100 flex-shrink-0 shadow-xl fixed top-0 left-0 h-full">
    <div class="p-6">
      <h1 class="text-2xl font-extrabold text-white tracking-wide mb-10">MediShare</h1>
      <nav class="space-y-2 text-sm font-medium">
        <a href="#" class="sidebar-link sidebar-active flex items-center p-3 rounded-lg">
          <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
        </a>
        <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="pill" class="w-5 h-5 mr-3"></i> My Medicines
        </a>
        <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="heart-handshake" class="w-5 h-5 mr-3"></i> My Donations
        </a>
        <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="inbox" class="w-5 h-5 mr-3"></i> My Requests
        </a>
        <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i> Reports
        </a>
        <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="settings" class="w-5 h-5 mr-3"></i> Settings
        </a>
      </nav>
    </div>
  </aside>

  <!-- Main Layout -->
  <div class="flex-1 ml-64 flex">

    <!-- Left column (fixed) -->
    <div class="w-[620px] border-r border-gray-200 p-6 fixed top-0 left-64 h-full overflow-y-auto">
      <!-- Top Bar -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-emerald-800"></h2>
        <div class="relative">
          <button id="notifBtn" class="relative p-2 rounded-full hover:bg-green-100 transition">
            <i data-lucide="bell" class="w-7 h-7 text-emerald-700"></i>
            <span class="notif-badge">3</span>
          </button>
          <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg p-4 z-10">
            <h3 class="font-semibold text-gray-800 mb-2">Notifications</h3>
            <div class="space-y-2 max-h-60 overflow-y-auto text-sm">
              <div class="p-2 bg-red-50 rounded-md">⚠ Paracetamol expiring in 6 days</div>
              <div class="p-2 bg-yellow-50 rounded-md">⚠ Amoxicillin expiring in 20 days</div>
              <div class="p-2 bg-green-50 rounded-md">✅ Your donation was approved</div>
            </div>
            <button class="mt-3 w-full text-center text-emerald-600 hover:underline text-sm">View all</button>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-2xl shadow card-hover text-center">
          <p class="text-gray-500 text-sm">My Donations</p>
          <p class="text-3xl font-extrabold text-sky-600">12</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow card-hover text-center">
          <p class="text-gray-500 text-sm">My Requests</p>
          <p class="text-3xl font-extrabold text-sky-600">8</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow card-hover text-center">
          <p class="text-gray-500 text-sm">Fulfilled</p>
          <p class="text-3xl font-extrabold text-green-600">10</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow card-hover text-center">
          <p class="text-gray-500 text-sm">Trust Score</p>
          <p class="text-3xl font-extrabold text-yellow-600">92%</p>
        </div>
      </div>

      <!-- Available Donations -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover mb-6">
        <h2 class="text-lg font-semibold mb-4 text-emerald-700 flex items-center">
          <i data-lucide="gift" class="w-5 h-5 mr-2 text-emerald-600"></i> Available Donations
        </h2>
        <ul class="space-y-3 text-sm max-h-48 overflow-y-auto">
          <li class="p-3 bg-emerald-50 rounded-lg flex justify-between items-center">
            <span> <b>Paracetamol</b> (x10)</span>
            <button class="text-s font-medium text-emerald-700 hover:underline">Accept</button>
          </li>
          <li class="p-3 bg-emerald-50 rounded-lg flex justify-between items-center">
            <span><b>Insulin</b> (x2)</span>
            <button class="text-s font-medium text-emerald-700 hover:underline">Accept</button>
          </li>
        </ul>
      </div>

      <!-- Open Requests -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover">
        <h2 class="text-lg font-semibold mb-4 text-sky-700 flex items-center">
          <i data-lucide="inbox" class="w-5 h-5 mr-2 text-sky-600"></i> Open Requests
        </h2>
        <ul class="space-y-3 text-sm max-h-48 overflow-y-auto">
          <li class="p-3 bg-sky-50 rounded-lg flex justify-between items-center">
            <span>Sarah needs Insulin</span>
            <button class="text-xs font-medium text-sky-700 hover:underline">Offer</button>
          </li>
          <li class="p-3 bg-sky-50 rounded-lg flex justify-between items-center">
            <span>Ali requests Paracetamol</span>
            <button class="text-xs font-medium text-sky-700 hover:underline">Offer</button>
          </li>
        </ul>
      </div>
    </div>

    <!-- Right column (scrollable) -->
    <div class="flex-1 scrollable-column ml-[620px] p-6">
      <!-- Quick Actions -->
      {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Request Box -->
        {{-- <div class="bg-white p-6 rounded-2xl shadow card-hover">
          <h2 class="text-lg font-semibold mb-4 text-green-700 flex items-center">
            <i data-lucide="zap" class="w-5 h-5 mr-2 text-green-600"></i> Request a Medicine
          </h2>
          <form class="space-y-3">
            <input type="text" placeholder="Medicine name..." required
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-emerald-400">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl w-full transition">Request</button>
          </form>
        </div> --}}

        <!-- Donate Box -->
        {{-- <div class="bg-white p-6 rounded-2xl shadow card-hover">
          <h2 class="text-lg font-semibold mb-4 text-sky-700 flex items-center">
            <i data-lucide="gift" class="w-5 h-5 mr-2 text-sky-600"></i> Donate a Medicine
          </h2>
          <form class="space-y-3">
            <input type="text" placeholder="Medicine name..." required
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-sky-400">
            <input type="number" placeholder="Quantity" required
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-sky-400">
            <input type="date" required
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-sky-400">
            <button class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-xl w-full transition">Donate</button>
          </form>
        </div> --
      </div> --}}

      <!-- Urgent Requests -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover mb-6">
        <h2 class="text-lg font-semibold mb-4 text-red-700 flex items-center">
          <i data-lucide="alert-triangle" class="w-5 h-5 mr-2 text-red-600"></i> Urgent Requests
        </h2>
        <div class="grid grid-cols-1 gap-4">
          <div class="p-4 bg-red-50 rounded-xl flex justify-between items-center">
            <span>Fatima needs <b>Insulin</b> within 2 days</span>
            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">Help</button>
          </div>
          <div class="p-4 bg-red-50 rounded-xl flex justify-between items-center">
            <span>Khaled needs <b>Oxygen Mask</b> today</span>
            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">Help</button>
          </div>
          <div class="p-4 bg-red-50 rounded-xl flex justify-between items-center">
            <span>Ali needs <b>Piodiab 50mg</b> within a day</span>
            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">Help</button>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white p-6 rounded-2xl shadow card-hover">
        <h2 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
          <i data-lucide="history" class="w-5 h-5 mr-2 text-gray-600"></i> My Recent Activity
        </h2>
        <ul class="space-y-3 text-sm">
          <li class="p-3 bg-gray-50 rounded-lg">Requested Insulin (Pending)</li>
          <li class="p-3 bg-gray-50 rounded-lg">Donated Paracetamol (Approved)</li>
          <li class="p-3 bg-gray-50 rounded-lg">Requested Amoxicillin (Fulfilled)</li>
          <li class="p-3 bg-gray-50 rounded-lg">Donated Vitamin C (Awaiting Approval)</li>
          <li class="p-3 bg-gray-50 rounded-lg">Requested Ibuprofen (Rejected)</li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    lucide.createIcons();
    const notifBtn = document.getElementById("notifBtn");
    const notifDropdown = document.getElementById("notifDropdown");
    notifBtn.addEventListener("click", () => {
      notifDropdown.classList.toggle("hidden");
    });
    document.addEventListener("click", (e) => {
      if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
        notifDropdown.classList.add("hidden");
      }
    });
  </script>
</body>
</html>
