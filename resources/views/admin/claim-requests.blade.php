@extends('layouts.dashboard')

@section('title', 'Admin - Claim Requests')

@section('content')
<!-- Page Background and Content Wrapper -->
<div class="min-h-screen bg-slate-50/50 p-4 sm:p-8 pt-20 md:pt-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->


        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pending Card -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                        <i class="fas fa-clock text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-0.5">PENDING REVIEW</p>
                        <p class="text-2xl font-black text-slate-900">{{ $claimRequests->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Approved Card -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-0.5">PROCESSED</p>
                        <p class="text-2xl font-black text-slate-900">{{ $claimRequests->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Rejected Card -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                        <i class="fas fa-times-circle text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-0.5">REJECTED</p>
                        <p class="text-2xl font-black text-slate-900">{{ $claimRequests->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Card -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-layer-group text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-0.5">TOTAL CLAIMS</p>
                        <p class="text-2xl font-black text-slate-900">{{ $claimRequests->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claim Requests Table/List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-3">
                    <i class="fas fa-tasks text-slate-300"></i>
                    Pending Verification
                </h2>
                <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 tracking-wider">
                    <span>FILTER:</span>
                    <form action="{{ route('admin.claim-requests') }}" method="GET">
                        <select name="status" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 cursor-pointer hover:text-blue-600 transition-colors">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Only</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Processed Only</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected Only</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($claimRequests->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($claimRequests as $claim)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 group" id="claim-{{ $claim->id }}">
                            <div class="flex flex-col lg:flex-row divide-y lg:divide-y-0 lg:divide-x divide-slate-50">
                                <!-- Main Info Section -->
                                <div class="flex-1 p-6 lg:p-8">
                                    <div class="flex items-center justify-between mb-6">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border transition-colors shadow-sm
                                            @if($claim->status === 'pending') bg-amber-50 text-amber-600 border-amber-100
                                            @elseif($claim->status === 'approved') bg-emerald-50 text-emerald-600 border-emerald-100
                                            @else bg-rose-50 text-rose-600 border-rose-100 @endif">
                                            <span class="w-1.5 h-1.5 rounded-full mr-2 
                                                @if($claim->status === 'pending') bg-amber-400
                                                @elseif($claim->status === 'approved') bg-emerald-400
                                                @else bg-rose-400 @endif animate-pulse"></span>
                                            {{ $claim->status }}
                                        </span>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                            <i class="far fa-calendar-alt text-slate-300"></i>
                                            {{ $claim->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-bold text-slate-900 leading-tight mb-8 transition-colors">
                                        {{ $claim->citer_paper_title }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-6">
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 shrink-0">
                                                    <i class="fas fa-user-edit"></i>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-1">CLAIMANT</p>
                                                    <p class="text-sm font-bold text-slate-800">{{ $claim->user->first_name }} {{ $claim->user->last_name }}</p>
                                                    <p class="text-[11px] text-slate-500">{{ $claim->user->email }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 shrink-0">
                                                    <i class="fas fa-link"></i>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-1">EXTERNAL SOURCE</p>
                                                    <a href="{{ $claim->paper_link }}" target="_blank" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors truncate block">
                                                        {{ str_replace(['http://', 'https://'], '', $claim->paper_link) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-6">
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 shrink-0">
                                                    <i class="fas fa-file-invoice"></i>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-1">REFERENCED PAPER</p>
                                                    <p class="text-sm font-bold text-slate-800">{{ Str::limit($claim->referencedPaper->title ?? 'N/A', 50) }}</p>
                                                    <p class="text-[10px] font-bold text-emerald-600 tracking-wider uppercase mt-1">Total: ₹{{ number_format($claim->claim_amount, 2) }} • Payout: ₹{{ number_format($claim->claim_amount * 0.95, 2) }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 shrink-0">
                                                    <i class="fas fa-key"></i>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] font-bold text-slate-400 tracking-wider mb-1">REFERENCE ID</p>
                                                    <p class="text-sm font-bold text-slate-800 font-mono">{{ $claim->reference_id ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($claim->admin_notes)
                                        <div class="mt-8 p-6 bg-slate-900 rounded-2xl relative overflow-hidden">
                                            <p class="text-[10px] font-bold text-slate-300 tracking-wider mb-2">INTERNAL NOTES</p>
                                            <p class="text-sm font-medium text-white italic leading-relaxed">"{{ $claim->admin_notes }}"</p>
                                            @if($claim->reviewed_at)
                                                <p class="text-[9px] font-bold text-slate-400 tracking-wider mt-4 uppercase">Reviewed by {{ $claim->reviewedBy->first_name ?? 'System' }} • {{ $claim->reviewed_at->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions Section -->
                                <div class="lg:w-64 p-6 bg-slate-50/50 flex flex-col justify-center gap-3">
                                    @if($claim->pdf_document)
                                        <a href="{{ asset('storage/' . $claim->pdf_document) }}" target="_blank"
                                            class="flex items-center justify-center gap-3 w-full py-3.5 px-6 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold shadow-sm transition-all duration-300 text-sm">
                                            <i class="fas fa-file-pdf text-rose-500"></i>
                                            <span>Review PDF</span>
                                        </a>
                                    @endif

                                    @if($claim->status === 'pending')
                                        <button onclick="showApprovalModal({{ $claim->id }})"
                                            class="flex items-center justify-center gap-3 w-full py-3.5 px-6 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 transition-all duration-300 text-sm">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Approve Claim</span>
                                        </button>
                                        <button onclick="showRejectionModal({{ $claim->id }})"
                                            class="flex items-center justify-center gap-3 w-full py-3.5 px-6 bg-white border border-rose-200 text-rose-600 rounded-xl font-bold transition-all duration-300 text-sm">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Reject Claim</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($claimRequests->hasPages())
                    <div class="pt-10 flex justify-center">
                        <div class="bg-white px-6 py-4 rounded-3xl shadow-sm border border-slate-100">
                            {{ $claimRequests->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-100">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">No requests in queue</h3>
                    <p class="text-slate-400 font-medium max-w-xs mx-auto mt-2">New citation claims from citers will appear here for verification.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Profile Modal -->
<x-dashboard.modals.profile-modal />

<!-- Premium Modals -->
<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 overflow-hidden" id="approvalModalContent">
        <div class="p-10">
            <div class="flex items-start justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Confirm Approval</h3>
                        <p class="text-slate-500 font-medium">Process reward transfer (95% to citer)</p>
                    </div>
                </div>
                <button onclick="closeApprovalModal()" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-sm font-medium text-slate-600 leading-relaxed">
                        By approving this claim, you verify the citation is valid. The claimant will receive <strong class="text-indigo-600">95%</strong> of the specified amount and the system will retain <strong class="text-indigo-600">5%</strong> as commission.
                    </p>
                </div>
                
                <div class="space-y-2">
                    <label for="approvalNotes" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">Admin Feedback (Optional)</label>
                    <textarea id="approvalNotes" rows="3"
                        class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium resize-none"
                        placeholder="e.g. Citation verified via DOI"></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button onclick="closeApprovalModal()"
                        class="flex-1 py-4 px-6 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all duration-300">
                        Cancel
                    </button>
                    <button onclick="confirmApproval()"
                        class="flex-2 py-4 px-8 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-100 active:scale-95 transition-all duration-300">
                        Process Payout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 overflow-hidden" id="rejectionModalContent">
        <div class="p-10">
            <div class="flex items-start justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Reject Claim</h3>
                        <p class="text-slate-500 font-medium">Specify verification failure</p>
                    </div>
                </div>
                <button onclick="closeRejectionModal()" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="rejectionNotes" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">Reason for Rejection *</label>
                    <textarea id="rejectionNotes" required rows="4"
                        class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all duration-300 font-medium resize-none"
                        placeholder="Please explain why the claim was rejected..."></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button onclick="closeRejectionModal()"
                        class="flex-1 py-4 px-6 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all duration-300">
                        Cancel
                    </button>
                    <button onclick="confirmRejection()"
                        class="flex-2 py-4 px-8 bg-rose-600 text-white rounded-2xl font-bold shadow-xl shadow-rose-100 active:scale-95 transition-all duration-300">
                        Confirm Rejection
                    </button>
                </div>
            </div>
        </div>
    </div>
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
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    try {
        const response = await fetch(`/admin/claim-requests/${currentClaimId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ admin_notes: notes })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            closeApprovalModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Approval failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('System error occurred', 'error');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function confirmRejection() {
    if (!currentClaimId) return;
    
    const notes = document.getElementById('rejectionNotes').value.trim();
    if (!notes) {
        showToast('Please provide a reason', 'error');
        return;
    }

    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    try {
        const response = await fetch(`/admin/claim-requests/${currentClaimId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ admin_notes: notes })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            closeRejectionModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Rejection failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('System error occurred', 'error');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}


// Close modals when clicking outside
document.addEventListener('click', (e) => {
    if (e.target.id === 'approvalModal') closeApprovalModal();
    if (e.target.id === 'rejectionModal') closeRejectionModal();
});

// Sync UI
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof Dashboard !== 'undefined') {
            try {
                const originalLoadPapers = Dashboard.prototype.loadPapers;
                Dashboard.prototype.loadPapers = function() {
                    console.log('Skipping loadPapers on admin page');
                };
                window.dashboard = new Dashboard();
            } catch (error) {
                console.warn('UI System Syncing...');
            }
        }
    }, 100);
});
</script>
@endpush