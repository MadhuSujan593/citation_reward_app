<div 
    id="deleteConfirmationModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <div class="text-center">
            <!-- Warning Icon -->
            <div class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-rose-600 text-xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-slate-900 mb-3">Confirm Deletion</h3>
            <p class="text-sm text-slate-600 mb-8 leading-relaxed font-medium">
                Are you sure you want to delete this paper? This action cannot be undone.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col gap-3">
                <button 
                    onclick="confirmDeletePaper()"
                    class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-rose-100 hover:-translate-y-0.5 transition-all duration-300 w-full"
                >
                    <i class="fas fa-trash mr-2"></i>
                    Delete Paper
                </button>
                <button 
                    onclick="closeDeletePaperModal()"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 rounded-xl transition-all duration-300 w-full"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 