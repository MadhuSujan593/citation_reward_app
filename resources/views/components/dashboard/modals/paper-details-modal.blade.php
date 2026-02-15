<div 
    id="paperDetailsModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex justify-center z-[9999] px-2 sm:px-6 pt-16 sm:pt-0 items-start sm:items-center hidden"
>
    <div class="bg-white w-full max-w-sm sm:max-w-lg md:max-w-xl lg:max-w-2xl xl:max-w-3xl mx-2 my-8 rounded-2xl shadow-2xl p-0 sm:p-0 relative">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-slate-100">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight" id="paperModalTitle">Research Details</h2>
            <button 
                onclick="closePaperDetailsModal()" 
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <!-- Scrollable Body -->
        <div id="paperModalContent" class="overflow-y-auto px-4 sm:px-6 py-4" style="max-height:60vh;">
            <!-- Content will be populated via JavaScript -->
        </div>
        <!-- Footer -->
        <div id="paperModalFooter" class="flex justify-end p-6 bg-slate-50/50 border-t border-slate-100">
            <button 
                id="paperModalActionBtn"
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-xl shadow-blue-100 hover:-translate-y-1 active:scale-95 transition-all duration-300 hidden"
            >
                <!-- Dynamic text (Okay or Proceed to Cite) -->
            </button>
        </div>
    </div>
</div>

<!-- Copy Feedback Component -->
<div 
    id="copyFeedback"
    class="fixed top-6 right-6 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl z-[11000] opacity-0 transition-all duration-300 pointer-events-none translate-y-4"
>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-check text-xs"></i>
        </div>
        <span class="text-sm font-bold">Copied to clipboard!</span>
    </div>
</div>