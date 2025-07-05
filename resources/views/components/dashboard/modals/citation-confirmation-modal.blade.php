<div 
    id="confirmCitationModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <div class="text-center">
            <!-- Icon -->
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-quote-left text-white text-lg"></i>
            </div>
            
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Confirm Citation</h3>
            <p id="modalMessage" class="text-gray-700 mb-6 leading-relaxed">
                Are you sure you want to cite this paper?
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button 
                    id="confirmCitationBtn"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium shadow-lg hover:shadow-xl transition-all duration-200"
                >
                    <i class="fas fa-check mr-2"></i>
                    Yes, Cite
                </button>
                <button 
                    onclick="closeConfirmModal()"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium transition-all duration-200"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 