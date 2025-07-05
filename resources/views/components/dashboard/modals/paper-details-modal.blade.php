<div 
    id="paperDetailsModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex justify-center z-[9999] px-2 sm:px-6 pt-16 sm:pt-0 items-start sm:items-center hidden"
>
    <div class="bg-white w-full max-w-sm sm:max-w-lg md:max-w-xl lg:max-w-2xl xl:max-w-3xl mx-2 my-8 rounded-2xl shadow-2xl p-0 sm:p-0 relative">
        <!-- Header -->
        <div class="flex justify-between items-start p-4 sm:p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800" id="paperModalTitle">Paper Details</h2>
            <button 
                onclick="closePaperDetailsModal()" 
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <!-- Scrollable Body -->
        <div id="paperModalContent" class="overflow-y-auto px-4 sm:px-6 py-4" style="max-height:60vh;">
            <!-- Content will be populated via JavaScript -->
        </div>
        <!-- Footer -->
        <div id="paperModalFooter" class="flex justify-end space-x-3 border-t border-gray-200 p-4 sm:p-6">
            <button 
                id="paperModalActionBtn"
                class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl hidden"
            >
                <!-- Dynamic text (Okay or Proceed to Cite) -->
            </button>
        </div>
    </div>
</div>

<!-- Copy Feedback Component -->
<div 
    id="copyFeedback"
    class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-[9999] opacity-0 transition-opacity duration-300 pointer-events-none"
>
    <i class="fas fa-check mr-2"></i>
    <span>Copied to clipboard!</span>
</div> 