<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ config('app.name') }} - Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">

  <!-- Mobile Header with Hamburger -->
  <div class="flex items-center justify-between p-4 bg-white shadow-md md:hidden">
    <h1 class="text-lg font-bold text-gray-800">Research Hub</h1>
    <button id="hamburgerBtn" class="text-gray-600 text-2xl focus:outline-none">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <div class="flex flex-col md:flex-row min-h-screen relative">

    <!-- Sidebar -->
    <div id="sidebar" class="w-full md:w-64 bg-white shadow-lg md:min-h-screen p-6 transform md:transform-none -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out absolute md:relative z-50 md:z-auto">
      <div class="flex items-center space-x-3 mb-8">
        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
          <i class="fas fa-chart-line text-white text-lg"></i>
        </div>
        <h1 class="text-xl font-bold text-gray-800">Research Hub</h1>
      </div>

      <!-- Role Toggle -->
      <div class="mb-6 p-3 bg-gray-50 rounded-lg">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm font-medium text-gray-600">Current Role:</span>
          <span id="currentRole" class="text-sm font-bold text-indigo-600">{{ $userRole ?? 'Citer' }}</span>
        </div>
        <div class="flex bg-white rounded-lg p-1">
          <button id="citerBtn" class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors {{ ($userRole ?? 'Citer') === 'Citer' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600' }}">
            <i class="fas fa-quote-left mr-2"></i>Citer
          </button>
          <button id="funderBtn" class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors {{ ($userRole ?? 'Citer') === 'Funder' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600' }}">
            <i class="fas fa-hand-holding-usd mr-2"></i>Funder
          </button>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-indigo-50 text-indigo-600 rounded-lg font-medium">
          <i class="fas fa-home w-5"></i><span>Dashboard</span>
        </a>

        <div id="citerMenu" class="{{ ($userRole ?? 'Citer') === 'Citer' ? '' : 'hidden' }}">
          <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
            <i class="fas fa-file-alt w-5"></i><span>My Citations</span>
          </a>
          <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
            <i class="fas fa-search w-5"></i><span>Research Papers</span>
          </a>
          <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
            <i class="fas fa-bookmark w-5"></i><span>Saved Papers</span>
          </a>
        </div>

        <div id="funderMenu" class="{{ ($userRole ?? 'Citer') === 'Funder' ? '' : 'hidden' }}">
          <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
            <i class="fas fa-project-diagram w-5"></i><span>My Published Papers</span>
          </a>
        </div>
      </nav>

      <!-- Profile Section -->
      <div class="mt-8 p-4 bg-indigo-600 rounded-xl text-white">
        <div class="flex items-center space-x-3 mb-4">
          <div>
            <p class="font-medium">{{ auth()->user()->name ?? 'John Doe' }}</p>
            <p class="text-sm opacity-80">{{ auth()->user()->email ?? 'john@example.com' }}</p>
          </div>
        </div>
        <div class="space-y-2">
          <a href="{{ route('profile.edit') }}" class="block text-sm hover:text-indigo-200 transition-colors">
            <i class="fas fa-user-edit mr-2"></i>Update Profile
          </a>
          <form method="POST" action="{{ route('account.delete') }}" onsubmit="return confirm('Are you sure you want to delete your account?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-200 hover:text-red-100 transition-colors">
              <i class="fas fa-trash mr-2"></i>Delete Account
            </button>
          </form>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm hover:text-indigo-200 transition-colors">
              <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6 mt-16 md:mt-0">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
          <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
          <p class="text-gray-600 mt-1" id="dashboardSubtitle">
            {{ ($userRole ?? 'Citer') === 'Citer' ? 'Citation Management Overview' : 'Funder Portfolio Overview' }}
          </p>
        </div>
        <div class="flex items-center space-x-4 w-full md:w-auto">
          <div class="relative w-full md:w-auto">
            <input type="text" placeholder="Search here..." class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <button class="p-2 text-gray-600 hover:text-indigo-600">
            <i class="fas fa-bell text-xl"></i>
          </button>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Toggle Sidebar on mobile
    document.getElementById('hamburgerBtn').addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });

    // Role switch logic
    const userRole = '{{ $userRole ?? "Citer" }}';

    document.getElementById('citerBtn').addEventListener('click', () => switchRole('Citer'));
    document.getElementById('funderBtn').addEventListener('click', () => switchRole('Funder'));

    function switchRole(role) {
      document.getElementById('currentRole').textContent = role;

      const citerBtn = document.getElementById('citerBtn');
      const funderBtn = document.getElementById('funderBtn');
      const citerMenu = document.getElementById('citerMenu');
      const funderMenu = document.getElementById('funderMenu');
      const subtitle = document.getElementById('dashboardSubtitle');

      if (role === 'Citer') {
        subtitle.textContent = 'Citation Management Overview';
        citerBtn.classList.add('bg-indigo-600', 'text-white');
        citerBtn.classList.remove('text-gray-600', 'hover:text-indigo-600');
        funderBtn.classList.remove('bg-indigo-600', 'text-white');
        funderBtn.classList.add('text-gray-600', 'hover:text-indigo-600');
        citerMenu.classList.remove('hidden');
        funderMenu.classList.add('hidden');
      } else {
        subtitle.textContent = 'Funder Portfolio Overview';
        funderBtn.classList.add('bg-indigo-600', 'text-white');
        funderBtn.classList.remove('text-gray-600', 'hover:text-indigo-600');
        citerBtn.classList.remove('bg-indigo-600', 'text-white');
        citerBtn.classList.add('text-gray-600', 'hover:text-indigo-600');
        funderMenu.classList.remove('hidden');
        citerMenu.classList.add('hidden');
      }

      fetch(`{{ url('dashboard/switch') }}/${role}`, {
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
      }).then(response => response.json())
        .then(data => console.log(data));
    }
  </script>

</body>
</html>
