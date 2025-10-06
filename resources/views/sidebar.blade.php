<!-- Sidebar -->
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full bg-gradient-to-b from-emerald-700 to-emerald-900 text-gray-100 shadow-xl md:translate-x-0 transition-transform duration-300">
  <div class="p-6">
    <h1 class="text-2xl font-extrabold text-white tracking-wide mb-10">MediShare</h1>
    <nav class="space-y-2 text-sm font-medium">
      <a href="#" class="sidebar-link sidebar-active flex items-center p-3 rounded-lg">
        <i data-lucide="layout-dashboard" class="mr-3 sidebar-icon"></i>
        <span class="sidebar-text">Dashboard</span>
      </a>
      <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
        <i data-lucide="pill" class="mr-3 sidebar-icon"></i>
        <span class="sidebar-text">My Medicines</span>
      </a>
      <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
        <i data-lucide="heart-handshake" class="mr-3 sidebar-icon"></i>
        <span class="sidebar-text">My Donations</span>
      </a>
      <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
        <i data-lucide="inbox" class="mr-3 sidebar-icon"></i>
        <span class="sidebar-text">My Requests</span>
      </a>
      <a href="#" class="sidebar-link flex items-center p-3 rounded-lg">
        <i data-lucide="bar-chart-2" class="mr-3 sidebar-icon"></i>
        <span class="sidebar-text">Reports</span>
      </a>
      <a href="{{ route('profile_settings') }}" class="sidebar-link flex items-center p-3 rounded-lg">
        <i data-lucide="settings" class="mr-3 sidebar-icon"></i>
        <span class="sidebar-text">Settings</span>
      </a>
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

<script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/lucide.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
  });
</script>
