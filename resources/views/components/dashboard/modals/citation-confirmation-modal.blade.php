<div 
    id="confirmCitationModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <div class="text-center">
            <!-- Icon -->
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-quote-left text-blue-600 text-lg"></i>
            </div>
            
            <h3 id="modalTitle" class="text-xl font-bold text-slate-900 mb-4">Confirm Citation</h3>
            <p id="modalMessage" class="text-slate-600 mb-6 leading-relaxed font-medium">
                Are you sure you want to cite this paper?
            </p>

            <!-- Paper Title Input -->
            <div id="citingPaperTitleContainer" class="mb-8 hidden">
                <label for="citingPaperTitle" class="block text-left text-sm font-bold text-slate-700 mb-2">
                    Title of your paper using this citation: <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="citingPaperTitle" 
                    required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200"
                    placeholder="e.g., Advances in AI Research"
                >
                <p id="citingPaperTitleError" class="text-rose-500 text-[11px] font-bold mt-2 text-left hidden">
                    <i class="fas fa-exclamation-circle mr-1"></i> Please enter your paper title.
                </p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <button 
                    onclick="closeConfirmModal()"
                    class="flex-1 px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold transition-all duration-200"
                >
                    Cancel
                </button>
                <button 
                    id="confirmCitationBtn"
                    class="flex-1 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-100 hover:-translate-y-0.5 transition-all duration-300"
                >
                    <i class="fas fa-check mr-2"></i>
                    Yes, Cite
                </button>
            </div>
        </div>
    </div>
</div> 