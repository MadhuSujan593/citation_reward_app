<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }} - Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/heroicons@2.0.13"></script>
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
    <div id="updateProfileModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                    <button type="button" onclick="closeProfileModal()"
                        class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm px-4 hidden">
        <div
            class="bg-white w-full max-w-md rounded-xl shadow-xl p-6 sm:p-8 text-center transition-all transform scale-100">
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Are you sure?</h2>
            <p class="text-gray-600 text-base mb-6">
                This action will permanently delete your account. This cannot be undone.
            </p>
            <div class="flex flex-col sm:flex-row sm:justify-center sm:gap-4 gap-3">
                <button onclick="confirmDelete()"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-6 rounded-md shadow-sm transition-colors w-full sm:w-auto">
                    Yes, Delete
                </button>
                <button onclick="closeDeleteModal()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 px-6 rounded-md border border-gray-300 transition-colors w-full sm:w-auto">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Paper Details Modal -->
    <div id="paperDetailsModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden px-4 sm:px-6">
        <div class="bg-white w-full max-w-4xl sm:rounded-lg shadow-lg p-4 sm:p-6 relative overflow-y-auto max-h-screen">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-semibold text-gray-800" id="paperModalTitle">Paper Details</h2>
                <button onclick="closePaperDetailsModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="paperModalContent" class="pb-4">
                <!-- Paper details will be populated here -->
            </div>

            <!-- Modal Footer -->
            <div id="paperModalFooter" class="flex justify-end space-x-2 border-t pt-4 mt-4">
                <button id="paperModalActionBtn"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm hidden">
                    <!-- Dynamic text (Okay or Proceed to Cite) -->
                </button>
            </div>
        </div>
    </div>


    <div class="flex flex-col md:flex-row min-h-screen relative">

        <!-- Sidebar -->
        <div id="sidebar"
            class="w-full md:w-64 bg-white shadow-lg md:min-h-screen p-6 transform md:transform-none -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out absolute md:relative z-50 md:z-auto">
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
                    <button id="citerBtn"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors {{ ($userRole ?? 'Citer') === 'Citer' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600' }}">
                        <i class="fas fa-quote-left mr-2"></i>Citer
                    </button>
                    <button id="funderBtn"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors {{ ($userRole ?? 'Citer') === 'Funder' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600' }}">
                        <i class="fas fa-hand-holding-usd mr-2"></i>Funder
                    </button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 bg-indigo-50 text-indigo-600 rounded-lg font-medium">
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
                        <a href="#" onclick="openPaperModal()"
                            class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                            <i class="fas fa-upload w-5"></i><span>Upload Paper</span>
                        </a>
                    </a>
                </div>
            </nav>

            <!-- Profile Section -->
            <div class="mt-8 p-4 bg-indigo-600 rounded-xl text-white">
                <div class="flex items-center space-x-3 mb-4">
                    <div>
                        <p class="font-medium">
                            {{ auth()->user()->first_name.' '.auth()->user()->last_name ?? 'John Doe' }}
                        </p>
                        <p class="text-sm opacity-80">{{ auth()->user()->email ?? 'john@example.com' }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <button onclick="openProfileModal()" class="block text-sm hover:text-indigo-200 transition-colors">
                        <i class="fas fa-user-edit mr-2"></i>Update Profile
                    </button>
                    <button type="button" onclick="openDeleteModal()"
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
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
                    <p class="text-gray-600 mt-1" id="dashboardSubtitle">
                        {{ ($userRole ?? 'Citer') === 'Citer' ? 'Citation Management Overview' : 'Funder Portfolio Overview' }}
                    </p>
                </div>
                <div class="flex items-center space-x-4 w-full md:w-auto">
                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap w-full">
                        <!-- Search Input -->
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" id="searchInput" placeholder="Search papers..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>

                        <!-- Filter Button -->
                        <button id="advancedFilterBtn"
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 bg-white rounded-lg hover:bg-gray-100 focus:outline-none whitespace-nowrap">
                            <i class="fas fa-filter text-indigo-600"></i>
                            <span id="filterLabel">All</span>
                        </button>
                    </div>

                    <!-- Filter Popup -->
                    <div id="filterPopup"
                        class="absolute mt-1 right-0 w-48 bg-white border border-gray-200 rounded-md shadow-lg p-2 hidden z-50">
                        <p class="text-sm font-medium text-gray-700 mb-1">Search By:</p>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100" data-filter="">All</button>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100"
                            data-filter="author_name">Author Name</button>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100"
                            data-filter="author_id">Author ID</button>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100"
                            data-filter="title_name">Title</button>
                    </div>





                </div>
            </div>

            <!-- Papers Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800" id="papersTitle">
                        Available Research Papers
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500" id="papersCount">0 papers</span>
                        <button onclick="refreshPapers()" class="text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="papersLoading" class="text-center py-8 hidden">
                    <i class="fas fa-spinner fa-spin text-2xl text-indigo-600"></i>
                    <p class="text-gray-600 mt-2">Loading papers...</p>
                </div>

                <!-- Empty State -->
                <div id="papersEmpty" class="text-center py-12 bg-white rounded-lg shadow-sm hidden">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-600 mb-2" id="emptyTitle">No papers found</h4>
                    <p class="text-gray-500" id="emptyMessage">No published papers available at the moment.</p>
                </div>

                <!-- Papers Grid -->
                <div id="papersContainer" class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Papers will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Paper Modal -->
    <div id="uploadPaperModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden px-4 sm:px-6">
        <div class="bg-white w-full max-w-4xl sm:rounded-lg shadow-lg p-4 sm:p-6 relative overflow-y-auto max-h-screen">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Upload Published Paper</h2>
            <form id="uploadPaperForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Paper Title</label>
                        <input type="text" name="title" required class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">MLA Citation</label>
                        <textarea name="mla" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">APA Citation</label>
                        <textarea name="apa" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Chicago Citation</label>
                        <textarea name="chicago" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Harvard Citation</label>
                        <textarea name="harvard" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Vancouver Citation</label>
                        <textarea name="vancouver" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">DOI</label>
                        <textarea name="doi" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-6">
                    <button type="button" onclick="closePaperModal()"
                        class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Paper Modal -->
    <div id="editPaperModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden px-4 sm:px-6">
        <div class="bg-white w-full max-w-4xl sm:rounded-lg shadow-lg p-4 sm:p-6 relative overflow-y-auto max-h-screen">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Edit Published Paper</h2>
            <form id="editPaperForm">
                <input type="hidden" name="paper_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Paper Title</label>
                        <input type="text" name="title" required class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">MLA Citation</label>
                        <textarea name="mla" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">APA Citation</label>
                        <textarea name="apa" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Chicago Citation</label>
                        <textarea name="chicago" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Harvard Citation</label>
                        <textarea name="harvard" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Vancouver Citation</label>
                        <textarea name="vancouver" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">DOI</label>
                        <textarea name="doi" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4 sm:px-6 hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Confirm Deletion</h3>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete this paper?
            </p>
            <div class="flex flex-col space-y-3">
                <button onclick="confirmDeletePaper()"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded-lg transition-colors">
                    Delete
                </button>
                <button onclick="closeDeletePaperModal()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    <!-- Confirm Citation Modal -->
    <div id="confirmCitationModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center px-4 sm:px-0">
        <div class="bg-white p-6 rounded-md shadow-lg w-full max-w-sm">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Confirm Citation</h3>
            <p id="modalMessage" class="text-gray-700 mb-6">Are you sure you want to Cite this paper?</p>
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button id="confirmCitationBtn"
                    class="w-full sm:w-auto px-4 py-2 text-sm rounded-md bg-green-600 hover:bg-green-700 text-white">
                    Yes, Cite
                </button>
                <button onclick="closeConfirmModal()"
                    class="w-full sm:w-auto px-4 py-2 text-sm rounded-md bg-gray-300 hover:bg-gray-400 text-gray-800">
                    Cancel
                </button>
            </div>
        </div>
    </div>



    <!-- Toast -->
    <div id="toast"
        class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-sm px-4 py-2 rounded-md shadow-lg z-[9999] opacity-0 transition-opacity duration-300 pointer-events-none">
        <span id="toastMessage"></span>
    </div>



    <script>
        // Current user role and papers data
        let currentRole = '{{ $userRole ?? 'Citer' }}';
        let papers = [];
        let filteredPapers = [];

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadPapers();
            //setupSearch();
        });

        // Toggle Sidebar on mobile
        document.getElementById('hamburgerBtn').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });

        // Role switch logic
        document.getElementById('citerBtn').addEventListener('click', () => switchRole('Citer'));
        document.getElementById('funderBtn').addEventListener('click', () => switchRole('Funder'));

        function switchRole(role) {
            currentRole = role;
            document.getElementById('currentRole').textContent = role;

            const citerBtn = document.getElementById('citerBtn');
            const funderBtn = document.getElementById('funderBtn');
            const citerMenu = document.getElementById('citerMenu');
            const funderMenu = document.getElementById('funderMenu');
            const subtitle = document.getElementById('dashboardSubtitle');
            const papersTitle = document.getElementById('papersTitle');

            if (role === 'Citer') {
                subtitle.textContent = 'Citation Management Overview';
                papersTitle.textContent = 'Available Research Papers';
                citerBtn.classList.add('bg-indigo-600', 'text-white');
                citerBtn.classList.remove('text-gray-600', 'hover:text-indigo-600');
                funderBtn.classList.remove('bg-indigo-600', 'text-white');
                funderBtn.classList.add('text-gray-600', 'hover:text-indigo-600');
                citerMenu.classList.remove('hidden');
                funderMenu.classList.add('hidden');
            } else {
                subtitle.textContent = 'Funder Portfolio Overview';
                papersTitle.textContent = 'My Published Papers';
                funderBtn.classList.add('bg-indigo-600', 'text-white');
                funderBtn.classList.remove('text-gray-600', 'hover:text-indigo-600');
                citerBtn.classList.remove('bg-indigo-600', 'text-white');
                citerBtn.classList.add('text-gray-600', 'hover:text-indigo-600');
                funderMenu.classList.remove('hidden');
                citerMenu.classList.add('hidden');
            }

            // Reload papers for the new role
            loadPapers();
        }

        // Load papers based on current role
        async function loadPapers() {
            try {
                showLoading(true);
                const roleName = currentRole.replace(/^\d+/, '');
                console.log(roleName);
                const endpoint = `{{ route('dashboard.papers') }}?role=${encodeURIComponent(roleName)}`;
                const response = await fetch(endpoint, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    papers = data.papers || [];
                    filteredPapers = [...papers];
                    displayPapers();
                } else {
                    showToast(data.message || 'Failed to load papers', true);
                }
            } catch (error) {
                console.error('Error loading papers:', error);
                showToast('Error loading papers', true);
            } finally {
                showLoading(false);
            }
        }


        // Display papers in the grid
        function displayPapers() {
            const container = document.getElementById('papersContainer');
            const emptyState = document.getElementById('papersEmpty');
            const countElement = document.getElementById('papersCount');
            const emptyTitle = document.getElementById('emptyTitle');
            const emptyMessage = document.getElementById('emptyMessage');

            // Update count
            countElement.textContent = `${filteredPapers.length} paper${filteredPapers.length !== 1 ? 's' : ''}`;

            if (filteredPapers.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');

                if (currentRole === 'Funder') {
                    emptyTitle.textContent = 'No papers published yet';
                    emptyMessage.textContent = 'Upload your first research paper to get started.';
                } else {
                    emptyTitle.textContent = 'No papers available';
                    emptyMessage.textContent = 'No research papers have been published by funders yet.';
                }
            } else {
                emptyState.classList.add('hidden');
                container.innerHTML = filteredPapers.map(paper => createPaperCard(paper)).join('');
            }
        }

        // Create individual paper card HTML
        function createPaperCard(paper) {
            const publishedDate = new Date(paper.created_at).toLocaleDateString();
            const authorName = paper.author_name || 'Unknown Author';

            return `
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">${paper.title}</h4>
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-user mr-1"></i>
                                ${authorName}
                            </p>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Published: ${publishedDate}
                            </p>
                        </div>  
                        ${currentRole === 'Funder' ? `
                                                                        <div class="flex space-x-2">
                                                                            <button onclick="editPaper(${paper.id})" class="text-indigo-600 hover:text-indigo-800">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <button onclick="deletePaper(${paper.id})" class="text-red-600 hover:text-red-800">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    ` : ''}
                    </div>
                    
                   
                    
                    <div class="flex justify-between items-center">
                        <button onclick="viewPaperDetails(${paper.id},'view')" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            View Details
                        </button>
                        ${currentRole === 'Citer' ? `
        <button onclick="toggleCite(${paper.id}, ${paper.is_paper_cited_by_current_user})" 
            class="${paper.is_paper_cited_by_current_user ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'} text-white px-4 py-2 rounded-md text-sm transition-colors">
            <i class="fas ${paper.is_paper_cited_by_current_user ? 'fa-times' : 'fa-quote-left'} mr-1"></i>
            ${paper.is_paper_cited_by_current_user ? 'Uncite' : 'Cite'}
        </button>
    ` : ''}
                    </div>
                </div>
            `;
        }


        // Paper action functions
        function viewPaperDetails(paperId, mode = 'view', action = 'cite') {
            const paper = papers.find(p => p.id === paperId);
            if (!paper) return;

            document.getElementById('paperModalTitle').textContent = paper.title;

            const modalContent = document.getElementById('paperModalContent');
            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Author Information</h4>
                        <p class="text-gray-600">${paper.author_name || 'Unknown Author'}</p>
                        <p class="text-sm text-gray-500">Author ID: ${paper.user_id}</p>
                        <p class="text-sm text-gray-500">Paper ID: ${paper.id}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Publication Date</h4>
                        <p class="text-gray-600">${new Date(paper.created_at).toLocaleDateString()}</p>
                    </div>
                    
                    ${paper.mla ? `
                                                                   <div class="relative group">
        <h4 class="font-semibold text-gray-800 mb-2">MLA Citation</h4>
        <p class="text-sm text-gray-700" id="mlaText">${paper.mla}</p>

        <!-- Copy Icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-5 h-5 text-gray-500 hover:text-gray-700 cursor-pointer copy-icon absolute top-2 right-2"
             data-target="mlaText"
             onclick="handleCopy(this)">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2
                     m-4 12h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6
                     a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>

        <!-- Feedback -->
        <span class="copy-feedback absolute top-2 right-10 text-xs text-green-600 hidden">Copied!</span>
    </div>

                                                                ` : ''}
                    
                    ${paper.apa ? `
                                                                      <div class="relative group">
        <h4 class="font-semibold text-gray-800 mb-2">APA Citation</h4>
        <p class="text-sm text-gray-700" id="apaText">${paper.apa}</p>

        <!-- Copy Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" 
             stroke-width="1.5" stroke="currentColor" 
             class="w-5 h-5 text-gray-500 hover:text-gray-700 cursor-pointer copy-icon absolute top-2 right-2"
             data-target="apaText"
             onclick="handleCopy(this)">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2
                     m-4 12h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6
                     a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>

        <!-- Feedback -->
        <span class="copy-feedback absolute top-2 right-10 text-xs text-green-600 hidden">Copied!</span>
    </div>
                                                                ` : ''}
                    
                    ${paper.chicago ? `
                                                                     <div class="relative group">
        <h4 class="font-semibold text-gray-800 mb-2">Chicago Citation</h4>
        <p class="text-sm text-gray-700" id="chicagoText">${paper.chicago}</p>

        <!-- Copy Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" 
             stroke-width="1.5" stroke="currentColor" 
             class="w-5 h-5 text-gray-500 hover:text-gray-700 cursor-pointer copy-icon absolute top-2 right-2"
             data-target="chicagoText"
             onclick="handleCopy(this)">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2
                     m-4 12h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6
                     a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>

        <!-- Feedback -->
        <span class="copy-feedback absolute top-2 right-10 text-xs text-green-600 hidden">Copied!</span>
    </div>

                                                                ` : ''}
                    
                    ${paper.harvard ? `
                                                                   <div class="relative group">
        <h4 class="font-semibold text-gray-800 mb-2">Harvard Citation</h4>
        <p class="text-sm text-gray-700" id="harvardText">${paper.harvard}</p>

        <!-- Copy Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" 
             stroke-width="1.5" stroke="currentColor" 
             class="w-5 h-5 text-gray-500 hover:text-gray-700 cursor-pointer copy-icon absolute top-2 right-2"
             data-target="harvardText"
             onclick="handleCopy(this)">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2
                     m-4 12h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6
                     a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>

        <!-- Feedback -->
        <span class="copy-feedback absolute top-2 right-10 text-xs text-green-600 hidden">Copied!</span>
    </div>

                                                                ` : ''}
                    
                    ${paper.vancouver ? `
                                                                    <div class="relative group">
        <h4 class="font-semibold text-gray-800 mb-2">Vancouver Citation</h4>
        <p class="text-sm text-gray-700" id="vancouverText">${paper.vancouver}</p>

        <!-- Copy Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" 
             stroke-width="1.5" stroke="currentColor" 
             class="w-5 h-5 text-gray-500 hover:text-gray-700 cursor-pointer copy-icon absolute top-2 right-2"
             data-target="vancouverText"
             onclick="handleCopy(this)">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2
                     m-4 12h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6
                     a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>

        <!-- Feedback -->
        <span class="copy-feedback absolute top-2 right-10 text-xs text-green-600 hidden">Copied!</span>
    </div>

                                                                ` : ''}
                     ${paper.doi ? `
                                                                  <div class="relative group">
        <h4 class="font-semibold text-gray-800 mb-2">DOI</h4>
        <p class="text-sm text-gray-700" id="doiText">${paper.doi}</p>

        <!-- Copy Icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-5 h-5 text-gray-500 hover:text-gray-700 cursor-pointer copy-icon absolute top-2 right-2"
             data-target="doiText"
             onclick="handleCopy(this)">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2
                     m-4 12h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6
                     a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>

        <!-- Feedback -->
        <span class="copy-feedback absolute top-2 right-10 text-xs text-green-600 hidden">Copied!</span>
    </div>

                                                                ` : ''}
                </div>
            `;

            // Footer Buttons
            const actionBtn = document.getElementById('paperModalActionBtn');
            const cancelBtn = document.getElementById('paperModalCancelBtn');

            if (mode === 'view') {
                actionBtn.textContent = 'Okay';
                actionBtn.classList.remove('hidden');
                actionBtn.onclick = closePaperDetailsModal;
            } else if (mode === 'cite') {
                actionBtn.textContent = 'Proceed to Cite';
                actionBtn.classList.remove('hidden');
                actionBtn.onclick = () => confirmCitation(paper.id, action);
            }

            document.getElementById('paperDetailsModal').classList.remove('hidden');
        }

        function citePaper(paperId) {
            const paper = papers.find(p => p.id === paperId);
            if (!paper) return;


        }

        function editPaper(paperId) {
            const paper = papers.find(p => p.id === paperId);
            if (!paper) {
                showToast('Paper not found', true);
                return;
            }

            const form = document.getElementById('editPaperForm');

            form.paper_id.value = paper.id;
            form.title.value = paper.title || '';
            form.mla.value = paper.mla || '';
            form.apa.value = paper.apa || '';
            form.chicago.value = paper.chicago || '';
            form.harvard.value = paper.harvard || '';
            form.vancouver.value = paper.vancouver || '';
            form.doi.value = paper.doi || '';
            document.getElementById('editPaperModal').classList.remove('hidden');
        }

        document.getElementById('editPaperForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const paperId = form.paper_id.value;

            formData.append('_method', 'PUT');

            fetch(`/papers/${paperId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Paper updated successfully');
                        closeEditModal();
                        loadPapers(); // reload the list
                    } else {
                        showToast(data.message || 'Failed to update paper', true);
                    }
                })
                .catch(err => {
                    console.error('Update error:', err);
                    showToast('Error updating paper', true);
                });
        });

        function closeEditModal() {
            const form = document.getElementById('editPaperForm');
            form.reset();
            document.getElementById('editPaperModal').classList.add('hidden');
        }


        function deletePaper(paperId) {
            if (!confirm('Are you sure you want to delete this paper?')) return;

            fetch(`{{ url('papers') }}/${paperId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Paper deleted successfully!');
                        loadPapers(); // Reload papers
                    } else {
                        showToast(data.message || 'Failed to delete paper', true);
                    }
                })
                .catch(error => {
                    console.error('Error deleting paper:', error);
                    showToast('Error deleting paper', true);
                });
        }



        function refreshPapers() {
            loadPapers();
        }

        function showLoading(show) {
            const loading = document.getElementById('papersLoading');
            const container = document.getElementById('papersContainer');
            const emptyState = document.getElementById('papersEmpty');

            if (show) {
                loading.classList.remove('hidden');
                container.classList.add('hidden');
                emptyState.classList.add('hidden');
            } else {
                loading.classList.add('hidden');
                container.classList.remove('hidden');
            }
        }

        function closePaperDetailsModal() {
            document.getElementById('paperDetailsModal').classList.add('hidden');
        }

        // Existing modal functions
        function openProfileModal() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }

            const modal = document.getElementById('updateProfileModal');
            modal.classList.remove('hidden');

            document.getElementById('first_name').value = '{{ auth()->user()->first_name }}';
            document.getElementById('last_name').value = '{{ auth()->user()->last_name }}';
            document.getElementById('email').value = '{{ auth()->user()->email }}';
        }

        function closeProfileModal() {
            document.getElementById('updateProfileModal').classList.add('hidden');
        }
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');

            if (!toast || !toastMessage) {
                console.warn('Toast element or message span not found.');
                return;
            }

            toastMessage.textContent = message;
            // Reset background color
            toast.classList.remove('bg-green-600', 'bg-red-600');
            toast.classList.add(isError ? 'bg-red-600' : 'bg-green-600');

            // Remove hidden states
            toast.classList.remove('opacity-0', 'pointer-events-none');



            // Apply visible state
            toast.classList.add('opacity-100');

            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }


        document.getElementById('updateProfileForm').addEventListener('submit', function (e) {
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
                        document.querySelectorAll('p.font-medium')[0].textContent =
                            `${data.user.first_name} ${data.user.last_name}`;
                        document.querySelectorAll('p.text-sm.opacity-80')[0].textContent = data.user.email;

                        closeProfileModal();
                        showToast('Profile updated successfully!');
                    } else {
                        showToast('Update failed.', true);
                    }
                })
                .catch(error => {
                    console.error(error);
                    showToast('An error occurred.');
                });
        });

        function openDeleteModal() {
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
            closeDeleteModal();

            fetch('{{ route('profile.del') }}', {
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
                            window.location.href = '{{ route('login') }}';
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

        function openPaperModal() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }
            document.getElementById('uploadPaperModal').classList.remove('hidden');
        }

        function closePaperModal() {
            document.getElementById('uploadPaperModal').classList.add('hidden');
        }

        let paperIdToDelete = null;

        function deletePaper(paperId) {
            paperIdToDelete = paperId;
            document.getElementById('deleteConfirmationModal').classList.remove('hidden');
        }

        function closeDeletePaperModal() {
            paperIdToDelete = null;
            document.getElementById('deleteConfirmationModal').classList.add('hidden');
        }

        function confirmDeletePaper() {
            if (!paperIdToDelete) return;

            fetch(`/papers/${paperIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    closeDeletePaperModal();
                    if (data.success) {
                        showToast(data.message || 'Paper deleted');
                        papers = papers.filter(p => p.id !== paperIdToDelete);
                        filteredPapers = [...papers];
                        loadPapers();
                    } else {
                        showToast(data.message || 'Delete failed', true);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    closeDeletePaperModal();
                    showToast('Error deleting paper', true);
                });
        }


        document.getElementById('uploadPaperForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch('{{ route('papers.upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closePaperModal();
                        showToast('Paper uploaded successfully!');
                        loadPapers(); // Reload papers to show the new one
                        e.target.reset(); // Clear form
                    } else {
                        showToast(data.message || 'Failed to upload paper.', true);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Something went wrong.', true);
                });
        });

        let selectedFilter = ''; // Default filter (All)

        const filterBtn = document.getElementById('advancedFilterBtn');
        const filterPopup = document.getElementById('filterPopup');
        const searchInput = document.getElementById('searchInput');

        // Toggle the filter popup
        filterBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent immediate close
            filterPopup.classList.toggle('hidden');
        });

        // Handle filter selection
        filterPopup.querySelectorAll('button[data-filter]').forEach(button => {
            button.addEventListener('click', function () {
                selectedFilter = this.getAttribute('data-filter') || '';
                filterPopup.classList.add('hidden');
                const icon = '<i class="fas fa-filter text-indigo-600"></i>';
                filterBtn.innerHTML = `${icon} ${this.textContent.trim()}`;
            });
        });

        // Close popup when clicking outside
        window.addEventListener('click', function () {
            filterPopup.classList.add('hidden');
        });

        // Prevent popup from closing when clicked inside
        filterPopup.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Handle Enter key on search input
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                const query = this.value.trim();
                const params = new URLSearchParams();
                params.append('query', query);
                if (selectedFilter) {
                    params.append('filter_type', selectedFilter);
                }
                params.append('role', currentRole);

                fetch(`/papers/search?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        filteredPapers = data;
                        displayPapers(); // Ensure this function is defined
                    })
                    .catch(err => {
                        console.error("Search failed:", err);
                    });
            }
        });

        function handleCopy(iconElement) {
            const targetId = iconElement.getAttribute('data-target');
            const textEl = document.getElementById(targetId);
            const feedbackEl = iconElement.nextElementSibling;

            if (!textEl) {
                alert("Text not found.");
                return;
            }

            const text = textEl.innerText.trim();

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopiedFeedback(feedbackEl);
                }).catch(() => {
                    fallbackCopy(text, feedbackEl);
                });
            } else {
                fallbackCopy(text, feedbackEl);
            }
        }

        function fallbackCopy(text, feedbackEl) {
            const tempInput = document.createElement("textarea");
            tempInput.value = text;
            tempInput.style.position = "fixed";
            tempInput.style.opacity = 0;
            document.body.appendChild(tempInput);
            tempInput.select();

            try {
                const success = document.execCommand("copy");
                if (success) {
                    showCopiedFeedback(feedbackEl);
                } else {
                    alert("Copy not supported in this environment.");
                }
            } catch (err) {
                alert("Copy failed. Please copy manually.");
            }

            document.body.removeChild(tempInput);
        }

        function showCopiedFeedback(feedbackEl) {
            if (!feedbackEl) return;
            feedbackEl.classList.remove("hidden");
            setTimeout(() => {
                feedbackEl.classList.add("hidden");
            }, 1500);
        }

        let citationPaperId = null;
        let citationAction = null;

        function confirmCitation(paperId, action = 'cite') {
            citationPaperId = paperId;
            citationAction = action.toLowerCase();

            const actionCapitalized = citationAction.charAt(0).toUpperCase() + citationAction.slice(1);
            const modal = document.getElementById('confirmCitationModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('confirmCitationBtn');

            // Update modal content
            title.textContent = `Confirm ${actionCapitalized}`;
            message.textContent = `Are you sure you want to ${actionCapitalized} this paper?`;
            confirmBtn.textContent = `Yes, ${actionCapitalized}`;

            // Update button color
            if (citationAction === 'uncite') {
                confirmBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                confirmBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            } else {
                confirmBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }

            modal.classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmCitationModal').classList.add('hidden');
            citationPaperId = null;
            citationAction = null;
        }

        document.getElementById('confirmCitationBtn').addEventListener('click', function () {
            if (!citationPaperId || !citationAction) return;

            fetch(`/${citationAction}-paper/${citationPaperId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(`Paper ${citationAction === 'cite' ? 'Cited' : 'Uncited'} successfully!`);
                        loadPapers(); // Refresh the list
                    } else {
                        showToast(data.message || `Failed to ${citationAction}.`, true);
                    }
                    closePaperDetailsModal();
                    closeConfirmModal();
                })
                .catch(err => {
                    console.error(err);
                    closePaperDetailsModal();
                    closeConfirmModal();
                    showToast('Something went wrong.', true);
                });
        });



        function toggleCite(paperId, isCited) {
            if (isCited) {
                confirmCitation(paperId, 'uncite');
            } else {
                viewPaperDetails(paperId, 'cite', 'cite');
            }
        }




    </script>

</body>

</html>