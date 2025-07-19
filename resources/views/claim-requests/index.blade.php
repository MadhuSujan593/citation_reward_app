@extends('layouts.dashboard')

@section('title', 'Claim Requests')

@section('content')
<!-- Add top padding for mobile header -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-3 sm:p-6 pt-20 md:pt-6"
     x-data="{ 
                currentRole: '{{ $userRole ?? 'Citer' }}',
        switchRole(role) {
            this.currentRole = role;
            // Role switching on claim request page is handled by sidebar redirect
            // This function is kept for consistency but Funder role redirects to dashboard
        }
     }">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
                <!-- Header -->
        <div class="hidden md:flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 id="pageTitle" class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    Claim Requests
                </h1>
                <p id="pageDescription" class="text-gray-600 mt-1 text-sm sm:text-base">Submit claims for your cited papers</p>
            </div>
        </div>

        <!-- Mobile Title -->
        <div class="md:hidden text-center mb-6">
            <h1 id="mobileTitle" class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                Claim Requests
            </h1>
            <p id="mobileDescription" class="text-gray-600 mt-1 text-sm">Submit claims for your cited papers</p>
        </div>

        <!-- Claim Request Form and List -->
        <div id="claimSection">
        <!-- Claim Request Form -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-white/20">
                         <div class="flex items-center space-x-3 mb-6">
                 <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg" id="submit-icon-container">
                     <span class="text-white text-xl font-bold">+</span>
                 </div>
                                 <div>
                     <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Submit New Claim</h2>
                     <p class="text-gray-600 text-xs sm:text-sm">Submit your paper citation claim for review</p>
                 </div>
            </div>

            <form id="claimRequestForm" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Your Paper Title -->
                    <div>
                        <label for="citer_paper_title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Your Paper Title *
                        </label>
                        <input 
                            type="text" 
                            id="citer_paper_title" 
                            name="citer_paper_title" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50/50"
                            placeholder="Enter your paper title"
                            required
                        >
                    </div>

                    <!-- Paper Link -->
                    <div>
                        <label for="paper_link" class="block text-sm font-semibold text-gray-700 mb-2">
                            Your Paper Link *
                        </label>
                        <input 
                            type="url" 
                            id="paper_link" 
                            name="paper_link" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50/50"
                            placeholder="https://example.com/your-paper"
                            required
                        >
                    </div>

                    <!-- Referenced Paper -->
                    <div>
                        <label for="referenced_paper_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Paper You Cited *
                        </label>
                        <select 
                            id="referenced_paper_id" 
                            name="referenced_paper_id" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50/50"
                            required
                        >
                            <option value="">Select a paper you cited</option>
                            @foreach($citedPapers as $paper)
                                <option value="{{ $paper->id }}">
                                    {{ Str::limit($paper->title, 60) }} - by {{ $paper->author_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reference ID -->
                    <div>
                        <label for="reference_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Reference ID
                        </label>
                        <input 
                            type="text" 
                            id="reference_id" 
                            name="reference_id" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50/50"
                            placeholder="e.g., REF001, Citation#123 (Optional)"
                        >
                    </div>
                </div>

                <!-- PDF Upload -->
                <div>
                    <label for="pdf_document" class="block text-sm font-semibold text-gray-700 mb-2">
                        Supporting Document (PDF)
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="pdf_document" 
                            name="pdf_document" 
                            accept=".pdf"
                            class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        >
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Optional: Upload your paper or supporting document (PDF, max 10MB)</p>
                </div>

                                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button 
                        type="submit"
                        class="px-8 py-3 bg-emerald-600 text-white rounded-2xl font-semibold shadow-lg hover:bg-emerald-700 transition-all duration-200 border-2 border-emerald-600"
                        style="background-color: #059669 !important; color: white !important; min-height: 50px; display: flex; align-items: center; justify-content: center;"
                    >
                       
                        <span style="color: white !important;">Submit Claim Request</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- My Claim Requests -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-white/20">
                         <div class="flex items-center space-x-3 mb-6">
                 <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg" id="list-icon-container">
                     <span class="text-white text-lg font-bold">📋</span>
                 </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">My Claim Requests</h2>
                    <p class="text-gray-600 text-xs sm:text-sm">Track your submitted claims</p>
                </div>
            </div>

            @if($claimRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($claimRequests as $claim)
                        <div class="group bg-gradient-to-r from-gray-50/80 to-white/80 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-800 flex-1 pr-4">
                                            {{ Str::limit($claim->citer_paper_title, 80) }}
                                        </h3>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $claim->status_badge_class }} flex-shrink-0">
                                            @if($claim->status === 'pending')
                                                <i class="fas fa-clock mr-1"></i>
                                            @elseif($claim->status === 'approved')
                                                <i class="fas fa-check mr-1"></i>
                                            @else
                                                <i class="fas fa-times mr-1"></i>
                                            @endif
                                            {{ ucfirst($claim->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p><i class="fas fa-link mr-2 text-blue-500"></i> <a href="{{ $claim->paper_link }}" target="_blank" class="text-blue-600 hover:underline">{{ Str::limit($claim->paper_link, 60) }}</a></p>
                                        <p><i class="fas fa-file-alt mr-2 text-gray-500"></i> Cited: {{ Str::limit($claim->referencedPaper->title ?? 'Unknown Paper', 60) }}</p>
                                        <p><i class="fas fa-hashtag mr-2 text-purple-500"></i> Ref ID: {{ $claim->reference_id }}</p>
                                        @if($claim->pdf_document)
                                            <p><i class="fas fa-file-pdf mr-2 text-red-500"></i> 
                                                <a href="{{ asset('storage/' . $claim->pdf_document) }}" target="_blank" class="text-red-600 hover:underline">View Document</a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- <div class="flex flex-col lg:items-end space-y-2">
                
                                    @if($claim->reviewed_at)
                                        <div class="text-xs text-gray-500">
                                            ✅ Reviewed {{ $claim->reviewed_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div> --}}
                            </div>
                            
                            @if($claim->admin_notes)
                                <div class="mt-4 p-3 bg-blue-50 rounded-xl border border-blue-200">
                                    <p class="text-sm text-blue-800">
                                        <i class="fas fa-sticky-note mr-2"></i>
                                        <strong>Admin Notes:</strong> {{ $claim->admin_notes }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                                 <div class="text-center py-12">
                     <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-4">
                         <span class="text-gray-400 text-4xl">📨</span>
                     </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No claim requests yet</h3>
                    <p class="text-gray-500">Submit your first claim request above to get started</p>
                </div>
            @endif
        </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 pointer-events-none translate-x-full text-white">
    <span id="toastMessage" class="font-medium text-sm"></span>
</div>
@endsection

@push('modals')
    <x-dashboard.modals.profile-modal />
    <x-dashboard.modals.delete-confirmation-modal />
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const claimForm = document.getElementById('claimRequestForm');
    
    claimForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = claimForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
            submitBtn.disabled = true;
            
            const formData = new FormData(claimForm);
            
            const response = await fetch('{{ route("claim-requests.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, false);
                claimForm.reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Failed to submit claim request', true);
            }
            
        } catch (error) {
            console.error('Error:', error);
            showToast('An error occurred while submitting your claim', true);
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
    
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        toastMessage.textContent = message;
        toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out ${isError ? 'bg-red-500' : 'bg-green-500'} text-white`;
        
        // Show toast
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-full');
        toast.classList.add('opacity-100', 'translate-x-0');
        
        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-full');
            toast.classList.remove('opacity-100', 'translate-x-0');
        }, 3000);
    }
    
    // Initialize minimal dashboard for profile functionality only
    setTimeout(function() {
        if (typeof Dashboard !== 'undefined') {
            try {
                // Override loadPapers to prevent any paper loading on claim request page
                const originalLoadPapers = Dashboard.prototype.loadPapers;
                Dashboard.prototype.loadPapers = function() {
                    console.log('Papers functionality disabled on claim request page');
                };
                
                window.dashboard = new Dashboard();
                
                // Keep loadPapers disabled since Funder role redirects to dashboard
                console.log('Minimal dashboard initialized for claim requests page');
            } catch (error) {
                console.warn('Dashboard initialization failed:', error);
            }
        }
    }, 100);
});
</script>
@endpush 