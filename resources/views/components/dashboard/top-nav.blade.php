<div class="bg-white/80 backdrop-blur-xl border-b border-white/20 p-6 mt-16 md:mt-0">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
        <!-- Page Header -->
        <div class="fade-in">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Dashboard
            </h2>
            <p class="text-gray-600 mt-1" id="dashboardSubtitle">
                {{ ($userRole ?? 'Citer') === 'Citer' ? 'Citation Management Overview' : 'Funder Portfolio Overview' }}
            </p>
        </div>

        <!-- Search and Filters -->
        <div class="flex items-center space-x-4 w-full lg:w-auto">
            <div class="flex items-center gap-3 flex-wrap lg:flex-nowrap w-full">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[250px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Search papers..." 
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200"
                    >
                </div>

                <!-- Filter Button -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        id="advancedFilterBtn"
                        @click="open = !open"
                        class="flex items-center gap-2 px-4 py-3 border border-gray-200 bg-white/50 backdrop-blur-sm rounded-xl hover:bg-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 whitespace-nowrap"
                    >
                        <i class="fas fa-filter text-indigo-600"></i>
                        <span id="filterLabel">All</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <!-- Filter Dropdown -->
                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 p-2 z-50"
                    >
                        <p class="text-sm font-medium text-gray-700 mb-2 px-3">Search By:</p>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 rounded-lg transition-colors" data-filter="" @click="open = false">All</button>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 rounded-lg transition-colors" data-filter="author_name" @click="open = false">Author Name</button>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 rounded-lg transition-colors" data-filter="author_id" @click="open = false">Author ID</button>
                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 rounded-lg transition-colors" data-filter="title_name" @click="open = false">Title</button>
                    </div>
                </div>

                <!-- Refresh Button -->
                <button 
                    onclick="refreshPapers()" 
                    class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    <i class="fas fa-refresh"></i>
                </button>
            </div>
        </div>
    </div>
</div> 