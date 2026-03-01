@extends('layouts.dashboard')

@section('title', 'Claim Requests')

@section('content')
<!-- Page Background and Content Wrapper -->
<div class="min-h-screen bg-slate-50/50 p-4 sm:p-8 pt-20 md:pt-8"
     x-data="{ 
        currentRole: '{{ $userRole ?? 'Citer' }}',
        switchRole(role) {
            this.currentRole = role;
        }
     }">
    
    <div class="max-w-6xl mx-auto space-y-6 sm:space-y-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-6 border-b border-slate-200 pb-6 sm:pb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Claim Requests</h1>
                <p class="text-slate-500 mt-1 sm:mt-2 font-medium text-sm sm:base">Verify your citations and manage reward claims</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center gap-2">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider">Active Protection</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
            <div class="xl:col-span-12 space-y-10">
                
                <!-- Submission Form Card -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all duration-300">
                    <div class="p-8 sm:p-10">
                        <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-100 group shrink-0">
                                <i class="fas fa-plus text-white text-base group-hover:rotate-90 transition-transform duration-300"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">New Claim</h2>
                                <p class="text-slate-500 text-sm font-medium">Step-by-step verification process</p>
                            </div>
                        </div>

                        <form id="claimRequestForm" class="space-y-8" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="citer_paper_title" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Your Publication</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-book-open text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="text" id="citer_paper_title" name="citer_paper_title" required
                                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium"
                                            placeholder="The title of your paper">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="paper_link" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Live URL</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-link text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="url" id="paper_link" name="paper_link" required
                                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium"
                                            placeholder="https://example.com/publication">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="referenced_paper_id" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Supporting Reference</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select id="referenced_paper_id" name="referenced_paper_id" required
                                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 appearance-none focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium cursor-pointer">
                                            <option value="">Select paper you cited</option>
                                            @foreach($citedPapers as $paper)
                                                <option value="{{ $paper->id }}">{{ Str::limit($paper->title, 50) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="reference_id" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Reference Identifier</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-hashtag text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="text" id="reference_id" name="reference_id"
                                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium"
                                            placeholder="e.g. DOI or Internal ID">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Verification Document (PDF)</label>
                                <div class="relative group">
                                    <input type="file" id="pdf_document" name="pdf_document" accept=".pdf"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="w-full border-2 border-dashed border-slate-200 rounded-[1.5rem] p-8 flex flex-col items-center justify-center gap-3 bg-slate-50/50 group-hover:border-blue-400 group-hover:bg-blue-50/30 transition-all duration-300">
                                        <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-500">
                                            <i class="fas fa-upload text-lg"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-slate-700 font-bold" id="fileName">Drop PDF here or click to browse</p>
                                            <p class="text-slate-400 text-xs mt-1">Maximum file size: 10MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex pt-4">
                                <button type="submit"
                                    class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white rounded-xl font-bold shadow-sm transition-all duration-300 flex items-center justify-center gap-3 text-sm">
                                    <span>Submit Claim Request</span>
                                    <i class="fas fa-arrow-right text-xs opacity-50"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- History Section -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                            <i class="fas fa-history text-slate-300"></i>
                            Recent Submissions
                        </h2>
                        <span class="text-sm font-bold text-slate-400 tracking-widest uppercase">{{ $claimRequests->count() }} Total</span>
                    </div>

                    @if($claimRequests->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($claimRequests as $claim)
                                <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100 transition-all duration-300 group">
                                    <div class="flex items-start justify-between mb-4">
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
                                        <div class="text-slate-300 group-hover:text-blue-200 transition-colors">
                                            <i class="fas fa-quote-right italic"></i>
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-bold text-slate-900 leading-tight mb-4 group-hover:text-blue-700 transition-colors">
                                        {{ Str::limit($claim->citer_paper_title, 70) }}
                                    </h3>

                                    <div class="space-y-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-50">
                                        <a href="{{ $claim->paper_link }}" target="_blank" 
                                            class="flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors overflow-hidden">
                                            <i class="fas fa-link text-blue-400 text-[10px]"></i>
                                            <span class="truncate">{{ str_replace(['http://', 'https://'], '', $claim->paper_link) }}</span>
                                        </a>
                                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                            <i class="fas fa-file-invoice text-emerald-400 text-[10px]"></i>
                                            <span class="truncate">Ref: {{ $claim->referencedPaper->title ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex items-center justify-between">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                            <i class="far fa-calendar-alt text-slate-300"></i>
                                            {{ $claim->created_at->format('M d, Y') }}
                                        </div>
                                        @if($claim->pdf_document)
                                            <a href="{{ asset('storage/' . $claim->pdf_document) }}" target="_blank"
                                                class="flex items-center gap-2 text-[10px] font-extrabold text-blue-600 uppercase tracking-widest hover:text-blue-800 transition-colors bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
                                                <i class="fas fa-file-pdf"></i>
                                                Review PDF
                                            </a>
                                        @endif
                                    </div>

                                    @if($claim->admin_notes)
                                        <div class="mt-5 p-4 bg-slate-900 rounded-xl relative overflow-hidden">
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Feedback</p>
                                            <p class="text-xs font-medium text-slate-300 line-clamp-2 italic leading-relaxed">"{{ $claim->admin_notes }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-100">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="fas fa-folder-open text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">No records found</h3>
                            <p class="text-slate-400 font-medium max-w-xs mx-auto mt-2">Your verified citation claims will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
    const fileInput = document.getElementById('pdf_document');
    const fileNameDisplay = document.getElementById('fileName');
    
    // File input feedback
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
                fileNameDisplay.classList.add('text-blue-600');
            } else {
                fileNameDisplay.textContent = 'Drop PDF here or click to browse';
                fileNameDisplay.classList.remove('text-blue-600');
            }
        });
    }

    claimForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = claimForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
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
                showToast(data.message, 'success');
                claimForm.reset();
                if (fileNameDisplay) fileNameDisplay.textContent = 'Drop PDF here or click to browse';
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Verification failed', 'error');
            }
            
        } catch (error) {
            console.error('Error:', error);
            showToast('Connection error occurred', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
    
    
    // Minimal dashboard for profile
    setTimeout(function() {
        if (typeof Dashboard !== 'undefined') {
            try {
                const originalLoadPapers = Dashboard.prototype.loadPapers;
                Dashboard.prototype.loadPapers = function() {
                    console.log('Context preserved: Claim requests active');
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