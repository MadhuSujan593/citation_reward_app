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

  <!-- Update Profile Modal -->
<div id="updateProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white w-full max-w-md mx-4 md:mx-0 rounded-lg shadow-lg p-6 relative">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Update Profile</h2>
    <form id="updateProfileForm">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">First Name</label>
        <input type="text" id="first_name" name="first_name" required class="w-full border rounded p-2" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Last Name</label>
        <input type="text" id="last_name" name="last_name" required class="w-full border rounded p-2" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" id="email" name="email" required class="w-full border rounded p-2" />
      </div>
      <div class="flex justify-end space-x-2 mt-6">
        <button type="button" onclick="closeProfileModal()" class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4 sm:px-0 hidden">
  <div class="bg-white w-full max-w-sm sm:rounded-lg sm:shadow-lg p-6 sm:p-8 text-center">
    <h2 class="text-xl sm:text-2xl font-bold text-red-600 mb-2">Are you sure?</h2>
    <p class="text-gray-700 text-sm sm:text-base mb-4">
      This will permanently delete your account.
    </p>
    <div class="flex flex-col sm:flex-row justify-center sm:gap-4 gap-2">
      <button onclick="confirmDelete()"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded w-full sm:w-auto">
        Yes, Delete
      </button>
      <button onclick="closeDeleteModal()"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded w-full sm:w-auto">
        Cancel
      </button>
    </div>
  </div>
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
<p class="font-medium">
    {{ auth()->user()->first_name . ' ' . auth()->user()->last_name ?? 'John Doe' }}
</p>            <p class="text-sm opacity-80">{{ auth()->user()->email ?? 'john@example.com' }}</p>
          </div>
        </div>
        <div class="space-y-2">
          <button onclick="openProfileModal()" class="block text-sm hover:text-indigo-200 transition-colors">
  <i class="fas fa-user-edit mr-2"></i>Update Profile
</button>
          <button type="button"
  onclick="openDeleteModal()"
  class="text-sm hover:text-indigo-200 transition-colors">
  <i class="fas fa-trash mr-2"></i>Delete Account
</button>
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

  <div id="toast" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-sm px-4 py-2 rounded-md shadow-lg z-[9999] opacity-0 transition-opacity duration-300 pointer-events-none">
  <span id="toastMessage"></span>
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

     function openProfileModal() {
  // Close sidebar (if open)
  const sidebar = document.getElementById('sidebar');
  if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
    sidebar.classList.add('-translate-x-full');
  }

  // Open modal
  const modal = document.getElementById('updateProfileModal');
  modal.classList.remove('hidden');

  // Prefill fields
  document.getElementById('first_name').value = '{{ auth()->user()->first_name }}';
  document.getElementById('last_name').value = '{{ auth()->user()->last_name }}';
  document.getElementById('email').value = '{{ auth()->user()->email }}';
}
  function closeProfileModal() {
    document.getElementById('updateProfileModal').classList.add('hidden');
  }

  function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');

    toastMessage.textContent = message;
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');

    setTimeout(() => {
      toast.classList.remove('opacity-100');
      toast.classList.add('opacity-0');
    }, 3000);
  }

  document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;

    fetch('{{ route('profile.edit') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        first_name: form.first_name.value,
        last_name: form.last_name.value,
        email: form.email.value,
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Update user info on page (if needed)
        document.querySelectorAll('p.font-medium')[0].textContent = `${data.user.first_name} ${data.user.last_name}`;
        document.querySelectorAll('p.text-sm.opacity-80')[0].textContent = data.user.email;

        closeProfileModal();
        showToast('Profile updated successfully!');
      } else {
        showToast('Update failed.');
      }
    })
    .catch(error => {
      console.error(error);
      showToast('An error occurred.');
    });
  });


  function openDeleteModal() {
  // Close sidebar (if open)
  const sidebar = document.getElementById('sidebar');
  if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
    sidebar.classList.add('-translate-x-full');
  }
  document.getElementById('deleteConfirmModal').classList.remove('hidden');
}

function closeDeleteModal() {
  document.getElementById('deleteConfirmModal').classList.add('hidden');
}

function confirmDelete() {
  // Close modal
  closeDeleteModal();

  // Send delete request (update route if needed)
  fetch('{{ route("profile.del") }}', {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
        showToast(data.message);
        setTimeout(() => {
          window.location.href = '{{ route("login") }}';
        }, 2000);
    } else {
        showToast(data.message || 'Failed to delete account.', true);
    }
  })
  .catch(error => {
    console.error('Delete error:', error);
    showToast('Something went wrong.');
  });
}



     
  </script>

</body>
</html>
