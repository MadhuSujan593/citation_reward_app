@props(['paper', 'currentRole'])

<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 card-hover border border-white/20 overflow-hidden group">
    <!-- Card Header -->
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                    {{ $paper->title }}
                </h4>
                
                <!-- Author Info -->
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            {{ $paper->author_name ?? 'Unknown Author' }}
                        </p>
                        <p class="text-xs text-gray-500">Author ID: {{ $paper->user_id }}</p>
                    </div>
                </div>
                
                <!-- Publication Date -->
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                    <span>Published: {{ \Carbon\Carbon::parse($paper->created_at)->format('M d, Y') }}</span>
                </div>
            </div>
            
            <!-- Action Buttons for Funders -->
            @if($currentRole === 'Funder')
            <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <button 
                    onclick="editPaper({{ $paper->id }})" 
                    class="p-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 rounded-lg transition-colors"
                    title="Edit Paper"
                >
                    <i class="fas fa-edit text-sm"></i>
                </button>
                <button 
                    onclick="deletePaper({{ $paper->id }})" 
                    class="p-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors"
                    title="Delete Paper"
                >
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Card Footer -->
    <div class="px-6 pb-6">
        <div class="flex justify-between items-center">
            <button 
                onclick="viewPaperDetails({{ $paper->id }}, 'view')" 
                class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <i class="fas fa-eye mr-2"></i>
                View Details
            </button>
            
            @if($currentRole === 'Citer')
            <button 
                onclick="toggleCite({{ $paper->id }}, {{ $paper->is_paper_cited_by_current_user ? 'true' : 'false' }})" 
                class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 {{ $paper->is_paper_cited_by_current_user ? 'bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white' : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white' }}"
            >
                <i class="fas {{ $paper->is_paper_cited_by_current_user ? 'fa-times' : 'fa-quote-left' }} mr-2"></i>
                {{ $paper->is_paper_cited_by_current_user ? 'Uncite' : 'Cite' }}
            </button>
            @endif
        </div>
    </div>
    
    <!-- Hover Effect Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
</div> 