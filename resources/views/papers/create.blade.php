@extends('layouts.dashboard')

@section('title', 'Upload Paper')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-white p-6 md:p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Upload Research Paper</h1>
                <p class="text-sm text-slate-500 mt-1">Add your research to the platform to start collecting citations.</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-file-upload text-blue-600 text-xl"></i>
            </div>
        </div>

        <!-- Form -->
        <div class="p-6 md:p-8">
            <form id="uploadPaperPageForm" class="space-y-8" action="{{ route('papers.upload') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Basic Info -->
                    <div class="md:col-span-2 space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Basic Information</h3>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Paper Title <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                name="title" 
                                required 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                                placeholder="Enter the full title of your research paper"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">DOI (Optional)</label>
                            <input 
                                type="text" 
                                name="doi" 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                                placeholder="e.g., 10.1038/s41586-020-2649-2"
                            />
                        </div>
                    </div>

                    <!-- Citation Formats -->
                    <div class="md:col-span-2 space-y-6 mt-4">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">MLA Citation</label>
                                <textarea 
                                    name="mla" 
                                    rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 resize-none"
                                ></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">APA Citation</label>
                                <textarea 
                                    name="apa" 
                                    rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Chicago Citation</label>
                                <textarea 
                                    name="chicago" 
                                    rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Harvard Citation</label>
                                <textarea 
                                    name="harvard" 
                                    rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 resize-none"
                                ></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-2">Vancouver Citation</label>
                                <textarea 
                                    name="vancouver" 
                                    rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a 
                        href="{{ route('dashboard') }}"
                        class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full font-bold transition-all duration-200 text-sm"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-bold transition-all duration-300 shadow-lg shadow-blue-100 hover:-translate-y-0.5 active:scale-95 flex items-center gap-2 text-sm"
                        id="submitPaperBtn"
                    >
                        <i class="fas fa-upload"></i>
                        Publish Research
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadPaperPageForm');
    const submitBtn = document.getElementById('submitPaperBtn');
    
    if (uploadForm) {
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Set loading state
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (window.showToast) {
                        window.showToast('Paper published successfully!');
                    }
                    
                    // Redirect back to dashboard after brief delay to show toast
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 1000);
                } else {
                    // Reset button
                    submitBtn.innerHTML = originalBtnHtml;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    
                    // Parse validation errors if present
                    let errorMsg = data.message || 'Failed to publish paper.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0][0];
                        errorMsg = firstError;
                    }
                    
                    if (window.showToast) {
                        window.showToast(errorMsg, true);
                    } else {
                        alert(errorMsg);
                    }
                }
            } catch (err) {
                console.error('Upload error:', err);
                
                // Reset button
                submitBtn.innerHTML = originalBtnHtml;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                
                if (window.showToast) {
                    window.showToast('Something went wrong during upload.', true);
                } else {
                    alert('Something went wrong during upload.');
                }
            }
        });
    }
});
</script>
@endpush
@endsection
