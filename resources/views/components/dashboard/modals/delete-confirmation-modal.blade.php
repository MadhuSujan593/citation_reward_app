<div 
    id="deleteConfirmModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <div class="text-center">
            <!-- Warning Icon -->
            <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-rose-600 text-2xl"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Are you sure?</h2>
            <p class="text-slate-600 text-base mb-8 leading-relaxed font-medium">
                This action will permanently delete your account. This cannot be undone and all your data will be lost.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:justify-center sm:gap-3 gap-3">
                <button 
                    onclick="closeDeleteModal()"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3.5 px-6 rounded-xl transition-all duration-200 w-full sm:w-auto"
                >
                    Cancel
                </button>
                <button 
                    onclick="confirmDelete()"
                    class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-rose-100 hover:-translate-y-0.5 transition-all duration-300 w-full sm:w-auto"
                >
                    <i class="fas fa-trash mr-2"></i>
                    Yes, Delete Account
                </button>
            </div>
        </div>
    </div>
</div> 