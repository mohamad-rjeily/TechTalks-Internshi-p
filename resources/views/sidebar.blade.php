<!-- Sidebar -->
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full bg-gradient-to-b from-emerald-700 to-emerald-900 text-gray-100 shadow-xl md:translate-x-0 transition-transform duration-300">
  <div class="p-6">
    <h1 class="text-2xl font-extrabold text-white tracking-wide mb-10">MediShare</h1>
    <nav class="space-y-2 text-sm font-medium">
      @if(request()->is('admin/*'))
        {{-- Admin Sidebar Links --}}
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="layout-dashboard" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="{{ route('admin.medicines.index') }}" class="sidebar-link {{ request()->routeIs('admin.medicines.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="pill" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Medicines</span>
        </a>
        <a href="{{ route('admin.donations.index') }}" class="sidebar-link {{ request()->routeIs('admin.donations.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="heart-handshake" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Donations</span>
        </a>
        <a href="{{ route('admin.requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.requests.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="inbox" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Requests</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="bar-chart-2" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Reports</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="users" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Users</span>
        </a>
        <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit-logs.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="file-text" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Audit Logs</span>
        </a>
      @else
        {{-- User Sidebar Links --}}
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="layout-dashboard" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="{{ route('medicines.browse') }}" class="sidebar-link {{ request()->routeIs('medicines.browse') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="pill" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Browse Medicines</span>
        </a>
        <a href="{{ route('donations.index') }}" class="sidebar-link {{ request()->routeIs('donations.*') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="heart-handshake" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Donations</span>
        </a>
        <a href="{{route('requests')}}" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="inbox" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Requests</span>
        </a>
        <a href="{{route('reports')}}" class="sidebar-link flex items-center p-3 rounded-lg">
          <i data-lucide="bar-chart-2" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Reports</span>
        </a>
        <a href="{{ route('profile_settings') }}" class="sidebar-link {{ request()->routeIs('profile_settings') ? 'sidebar-active' : '' }} flex items-center p-3 rounded-lg">
          <i data-lucide="settings" class="mr-3 sidebar-icon"></i>
          <span class="sidebar-text">Settings</span>
        </a>
      @endif
    </nav>
  </div>
</aside>

<style>
  .sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s;
    color: inherit;
  }

  .sidebar-link:hover {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
  }

  .sidebar-active {
    background: linear-gradient(90deg, #10b981, #059669);
    color: white;
  }

  /* fix Lucide icons alignment & size */
  .sidebar-icon {
    display: inline-flex;
    width: 1.5rem;
    height: 1.5rem;
    flex-shrink: 0;
  }

  .sidebar-text {
    white-space: nowrap;
  }

  .sidebar-active .sidebar-icon,
  .sidebar-active .sidebar-text {
    color: inherit;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
    
    // Mobile menu toggle
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    
    if (menuBtn && sidebar) {
      menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
      });
      
      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', (e) => {
        if (window.innerWidth < 768 && 
            !sidebar.contains(e.target) && 
            !menuBtn.contains(e.target)) {
          sidebar.classList.add('-translate-x-full');
        }
      });
    }
  });
</script>