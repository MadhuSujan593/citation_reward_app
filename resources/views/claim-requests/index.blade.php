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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label for="referenced_paper_id" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Select your cited papers</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select id="referenced_paper_id" name="referenced_paper_id" required
                                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 appearance-none focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium cursor-pointer">
                                            <option value="">Select paper you cited</option>
                                            @foreach($citedPapers as $paper)
                                                <option value="{{ $paper->id }}">{{ Str::limit($paper->title, 70) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic Citations Selection -->
                                <div id="citations_container" class="md:col-span-2 space-y-3 hidden">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Select Citation Titles</label>
                                    <div id="citations_list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <!-- Citations will be loaded here via AJAX -->
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="paper_link" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Live URL</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-link text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="url" id="paper_link" name="paper_link" required
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium sm:text-sm"
                                            placeholder="https://example.com/publication">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="reference_id" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Reference Identifier</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-hashtag text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="text" id="reference_id" name="reference_id"
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium sm:text-sm"
                                            placeholder="e.g. DOI or Internal ID">
                                    </div>
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label for="claim_amount" class="text-sm font-bold text-slate-700 uppercase tracking-wider ml-1">Total Reward Amount (₹)</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-coins text-amber-500 group-focus-within:text-amber-600 transition-colors"></i>
                                        </div>
                                        <input type="number" id="claim_amount" name="claim_amount" required min="100" step="1"
                                            class="w-full pl-11 pr-4 py-3.5 bg-white border-2 border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-bold"
                                            placeholder="100">
                                            <p class="text-[9px] text-slate-400 mt-1.5 font-bold uppercase tracking-wider ml-1">₹100 per citation. Editable.</p>
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

    // Paper selection and citation loading
    const paperSelect = document.getElementById('referenced_paper_id');
    const citationsContainer = document.getElementById('citations_container');
    const citationsList = document.getElementById('citations_list');
    const amountInput = document.getElementById('claim_amount');

    // Auto-select paper if paper_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const preselectedPaperId = urlParams.get('paper_id');
    
    paperSelect.addEventListener('change', async function() {
        const paperId = this.value;
        if (!paperId) {
            citationsContainer.classList.add('hidden');
            return;
        }

        try {
            citationsList.innerHTML = '<div class="col-span-full py-8 text-center"><i class="fas fa-spinner fa-spin text-blue-500"></i><p class="text-xs text-slate-400 mt-2">Loading citations...</p></div>';
            citationsContainer.classList.remove('hidden');

            const response = await fetch(`/papers/${paperId}/citations`);
            const data = await response.json();

            if (data.success && data.citations.length > 0) {
                citationsList.innerHTML = '';
                data.citations.forEach(cit => {
                    const isDisabled = cit.already_claimed;
                    const card = document.createElement('div');
                    card.className = `p-3 rounded-xl border ${isDisabled ? 'bg-slate-50 border-slate-100 opacity-60' : 'bg-white border-slate-200 hover:border-blue-300 shadow-sm'} transition-all`;
                    card.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="shrink-0">
                                <input type="checkbox" name="selected_cit_ids[]" value="${cit.id}" 
                                    class="citation-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    ${isDisabled ? 'disabled' : ''}>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="cit_title_${cit.id}" value="${cit.citing_paper_title || ''}" 
                                    class="cit-title-input w-full px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all"
                                    ${isDisabled ? 'readonly' : ''} placeholder="Edit title">
                                ${isDisabled ? '<p class="text-[8px] font-bold text-amber-600 uppercase mt-0.5 tracking-tighter">Already claimed</p>' : ''}
                            </div>
                        </div>
                    `;
                    citationsList.appendChild(card);
                });

                // Re-bind checkbox event listeners
                document.querySelectorAll('.citation-checkbox').forEach(cb => {
                    cb.addEventListener('change', updateAmount);
                });
            } else {
                citationsList.innerHTML = '<div class="col-span-full py-8 text-center text-slate-400 text-sm">No citations found for this paper.</div>';
            }
        } catch (error) {
            console.error('Error loading citations:', error);
            showToast('Failed to load citations', 'error');
        }
    });

    if (preselectedPaperId) {
        // check if this paper_id exists in the options
        const optionExists = Array.from(paperSelect.options).some(option => option.value === preselectedPaperId);
        if (optionExists) {
            paperSelect.value = preselectedPaperId;
            paperSelect.dispatchEvent(new Event('change'));
        }
    }

    function updateAmount() {
        const checkedCount = document.querySelectorAll('.citation-checkbox:checked').length;
        amountInput.value = checkedCount * 100;
    }

    claimForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const selectedCheckboxes = document.querySelectorAll('.citation-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            showToast('Please select at least one citation title', 'error');
            return;
        }

        const submitBtn = claimForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            submitBtn.disabled = true;
            
            const formData = new FormData(claimForm);
            
            // Build selected_citations JSON
            const selectedCitations = [];
            selectedCheckboxes.forEach(cb => {
                const id = cb.value;
                const titleInput = document.querySelector(`input[name="cit_title_${id}"]`);
                selectedCitations.push({
                    id: id,
                    title: titleInput ? titleInput.value : ''
                });
            });
            
            // We append the JSON string for the backend to parse
            // But actually we can pass it as a hidden field or just individual fields
            // Let's use individual fields for simplicity and append them to formData
            selectedCitations.forEach((cit, index) => {
                formData.append(`selected_citations[${index}][id]`, cit.id);
                formData.append(`selected_citations[${index}][title]`, cit.title);
            });
            
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