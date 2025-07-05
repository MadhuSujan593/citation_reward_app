@props(['currentRole'])

<div class="space-y-6">
    <!-- Section Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-800" id="papersTitle">
                {{ $currentRole === 'Funder' ? 'My Published Papers' : 'Available Research Papers' }}
            </h3>
            <p class="text-gray-600 mt-1">Browse and manage research papers</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm text-gray-500 bg-white/50 px-3 py-1 rounded-full" id="papersCount">0 papers</span>
        </div>
    </div>

    <!-- Loading State -->
    <div id="papersLoading" class="hidden">
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mb-4">
                <i class="fas fa-spinner fa-spin text-white text-2xl"></i>
            </div>
            <p class="text-gray-600 text-lg">Loading papers...</p>
            <p class="text-gray-400 text-sm mt-1">Please wait while we fetch the latest research</p>
        </div>
    </div>

    <!-- Empty State -->
    <div id="papersEmpty" class="hidden">
        <div class="text-center py-16 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20">
            <div class="w-20 h-20 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-alt text-3xl text-gray-400"></i>
            </div>
            <h4 class="text-xl font-semibold text-gray-600 mb-2" id="emptyTitle">No papers found</h4>
            <p class="text-gray-500 max-w-md mx-auto" id="emptyMessage">
                No published papers available at the moment.
            </p>
            @if($currentRole === 'Funder')
            <button 
                onclick="openPaperModal()" 
                class="mt-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <i class="fas fa-plus mr-2"></i>
                Upload Your First Paper
            </button>
            @endif
        </div>
    </div>

    <!-- Papers Grid -->
    <div id="papersContainer" class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <!-- Papers will be populated here via JavaScript -->
    </div>
</div> 