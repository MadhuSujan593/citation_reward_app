@extends('layouts.dashboard')

@section('title', 'Admin - Claim Requests')

@section('content')
<!-- Add top padding for mobile header -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-3 sm:p-6 pt-20 md:pt-6">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        <!-- Header -->
        <div class="hidden md:flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    Admin Dashboard
                </h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Manage claim requests from citers</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-shield text-white text-sm sm:text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Mobile Title -->
        <div class="md:hidden text-center mb-6">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                Admin Dashboard
            </h1>
            <p class="text-gray-600 mt-1 text-sm">Manage claim requests</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-4 sm:p-6 shadow-xl border border-white/20">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $claimRequests->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-4 sm:p-6 shadow-xl border border-white/20">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-green-600">{{ $claimRequests->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-4 sm:p-6 shadow-xl border border-white/20">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-times text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Rejected</p>
                        <p class="text-2xl font-bold text-red-600">{{ $claimRequests->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-4 sm:p-6 shadow-xl border border-white/20">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $claimRequests->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claim Requests Table -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl shadow-xl border border-white/20 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tasks text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Claim Requests</h2>
                        <p class="text-gray-600 text-xs sm:text-sm">Review and process citer claims</p>
                    </div>
                </div>
            </div>

            @if($claimRequests->count() > 0)
                <div class="overflow-x-auto">
                    <div class="space-y-4 p-6">
                        @foreach($claimRequests as $claim)
                            <div class="group bg-gradient-to-r from-gray-50/80 to-white/80 rounded-2xl p-6 border border-gray-100/50 hover:shadow-lg transition-all duration-300" id="claim-{{ $claim->id }}">
                                <div class="flex flex-col lg:flex-row gap-6">
                                    <!-- Main Details -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                                    {{ $claim->citer_paper_title }}
                                                </h3>
                                                <div class="flex items-center space-x-4 mb-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $claim->status_badge_class }}">
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
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
                                            <div class="space-y-3">
                                                <div class="flex items-start">
                                                    <i class="fas fa-user mr-3 text-blue-500 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-800">Citer</p>
                                                        <p class="text-gray-600">{{ $claim->user->first_name }} {{ $claim->user->last_name }}</p>
                                                        <p class="text-gray-500 text-xs">{{ $claim->user->email }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-start">
                                                    <i class="fas fa-link mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-800">Paper Link</p>
                                                        <a href="{{ $claim->paper_link }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                                            {{ Str::limit($claim->paper_link, 60) }}
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-start">
                                                    <i class="fas fa-hashtag mr-3 text-purple-500 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-800">Reference ID</p>
                                                        <p class="text-gray-600">{{ $claim->reference_id }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-3">
                                                <div class="flex items-start">
                                                    <i class="fas fa-file-alt mr-3 text-gray-500 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-800">Referenced Paper</p>
                                                        <p class="text-gray-600">{{ Str::limit($claim->referencedPaper->title ?? 'Unknown Paper', 50) }}</p>
                                                        @if($claim->referencedPaper)
                                                            <p class="text-gray-500 text-xs">by {{ $claim->referencedPaper->user->first_name }} {{ $claim->referencedPaper->user->last_name }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if($claim->pdf_document)
                                                    <div class="flex items-start">
                                                        <i class="fas fa-file-pdf mr-3 text-red-500 mt-1 flex-shrink-0"></i>
                                                        <div>
                                                            <p class="font-medium text-gray-800">Supporting Document</p>
                                                            <a href="{{ asset('storage/' . $claim->pdf_document) }}" target="_blank" class="text-red-600 hover:underline">
                                                                <i class="fas fa-download mr-1"></i>View PDF
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex items-start">
                                                    <i class="fas fa-calendar mr-3 text-indigo-500 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-800">Submitted</p>
                                                        <p class="text-gray-600">{{ $claim->created_at->format('M d, Y H:i') }}</p>
                                                        @if($claim->reviewed_at)
                                                            <p class="text-gray-500 text-xs">Reviewed {{ $claim->reviewed_at->format('M d, Y H:i') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($claim->admin_notes)
                                            <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                                <p class="text-sm text-blue-800">
                                                    <i class="fas fa-sticky-note mr-2"></i>
                                                    <strong>Admin Notes:</strong> {{ $claim->admin_notes }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    @if($claim->status === 'pending')
                                        <div class="lg:w-64 flex flex-col space-y-3">
                                            <button 
                                                onclick="showApprovalModal({{ $claim->id }})"
                                                class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1"
                                            >
                                                <i class="fas fa-check mr-2"></i>
                                                Approve Claim
                                            </button>
                                            
                                            <button 
                                                onclick="showRejectionModal({{ $claim->id }})"
                                                class="w-full px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1"
                                            >
                                                <i class="fas fa-times mr-2"></i>
                                                Reject Claim
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($claimRequests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                        {{ $claimRequests->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No claim requests yet</h3>
                    <p class="text-gray-500">Claim requests from citers will appear here</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="approvalModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Approve Claim Request</h3>
                <button onclick="closeApprovalModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">Are you sure you want to approve this claim? ₹95 will be transferred to the citer (₹5 commission retained).</p>
                
                <div>
                    <label for="approvalNotes" class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes (Optional)</label>
                    <textarea 
                        id="approvalNotes" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 resize-none"
                        placeholder="Optional notes about the approval..."
                    ></textarea>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button 
                    onclick="closeApprovalModal()"
                    class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-all duration-200"
                >
                    Cancel
                </button>
                <button 
                    onclick="confirmApproval()"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1"
                >
                    <i class="fas fa-check mr-2"></i>
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="rejectionModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Reject Claim Request</h3>
                <button onclick="closeRejectionModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">Please provide a reason for rejecting this claim request.</p>
                
                <div>
                    <label for="rejectionNotes" class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason *</label>
                    <textarea 
                        id="rejectionNotes" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 resize-none"
                        placeholder="Explain why this claim is being rejected..."
                        required
                    ></textarea>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button 
                    onclick="closeRejectionModal()"
                    class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-all duration-200"
                >
                    Cancel
                </button>
                <button 
                    onclick="confirmRejection()"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1"
                >
                    <i class="fas fa-times mr-2"></i>
                    Reject
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 pointer-events-none translate-x-full text-white">
    <span id="toastMessage" class="font-medium text-sm"></span>
</div>
@endsection

@push('scripts')
<script>
let currentClaimId = null;

function showApprovalModal(claimId) {
    currentClaimId = claimId;
    const modal = document.getElementById('approvalModal');
    const content = document.getElementById('approvalModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeApprovalModal() {
    const modal = document.getElementById('approvalModal');
    const content = document.getElementById('approvalModalContent');
    
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('approvalNotes').value = '';
        currentClaimId = null;
    }, 300);
}

function showRejectionModal(claimId) {
    currentClaimId = claimId;
    const modal = document.getElementById('rejectionModal');
    const content = document.getElementById('rejectionModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeRejectionModal() {
    const modal = document.getElementById('rejectionModal');
    const content = document.getElementById('rejectionModalContent');
    
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('rejectionNotes').value = '';
        currentClaimId = null;
    }, 300);
}

async function confirmApproval() {
    if (!currentClaimId) return;
    
    const notes = document.getElementById('approvalNotes').value;
    
    try {
        const response = await fetch(`/admin/claim-requests/${currentClaimId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, false);
            closeApprovalModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to approve claim', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while approving the claim', true);
    }
}

async function confirmRejection() {
    if (!currentClaimId) return;
    
    const notes = document.getElementById('rejectionNotes').value.trim();
    
    if (!notes) {
        showToast('Please provide a rejection reason', true);
        return;
    }
    
    try {
        const response = await fetch(`/admin/claim-requests/${currentClaimId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, false);
            closeRejectionModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to reject claim', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while rejecting the claim', true);
    }
}

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

// Close modals when clicking outside
document.addEventListener('click', (e) => {
    if (e.target.id === 'approvalModal') {
        closeApprovalModal();
    }
    if (e.target.id === 'rejectionModal') {
        closeRejectionModal();
    }
});

// Initialize minimal dashboard for profile functionality only
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof Dashboard !== 'undefined') {
            try {
                const originalLoadPapers = Dashboard.prototype.loadPapers;
                Dashboard.prototype.loadPapers = function() {
                    console.log('Skipping loadPapers on admin page');
                };
                
                window.dashboard = new Dashboard();
                Dashboard.prototype.loadPapers = originalLoadPapers;
                
                console.log('Dashboard initialized for admin page');
            } catch (error) {
                console.warn('Dashboard initialization failed:', error);
            }
        }
    }, 100);
});
</script>
@endpush 