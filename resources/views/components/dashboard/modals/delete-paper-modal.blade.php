<div 
    id="deleteConfirmationModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <div class="text-center">
            <!-- Warning Icon -->
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Confirm Deletion</h3>
            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                Are you sure you want to delete this paper? This action cannot be undone.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col space-y-3">
                <button 
                    onclick="confirmDeletePaper()"
                    class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-medium py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200"
                >
                    <i class="fas fa-trash mr-2"></i>
                    Delete Paper
                </button>
                <button 
                    onclick="closeDeletePaperModal()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 rounded-xl transition-all duration-200"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 