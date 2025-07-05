<div 
    id="deleteConfirmModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <div class="text-center">
            <!-- Warning Icon -->
            <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Are you sure?</h2>
            <p class="text-gray-600 text-base mb-6 leading-relaxed">
                This action will permanently delete your account. This cannot be undone and all your data will be lost.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:justify-center sm:gap-4 gap-3">
                <button 
                    onclick="confirmDelete()"
                    class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-medium py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 w-full sm:w-auto"
                >
                    <i class="fas fa-trash mr-2"></i>
                    Yes, Delete Account
                </button>
                <button 
                    onclick="closeDeleteModal()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-6 rounded-xl border border-gray-300 transition-all duration-200 w-full sm:w-auto"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 