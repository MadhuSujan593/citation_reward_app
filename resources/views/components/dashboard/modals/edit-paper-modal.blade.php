<div 
    id="editPaperModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-lg mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Research</h2>
            <button 
                onclick="closeEditModal()" 
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form id="editPaperForm" class="space-y-6">
            <input type="hidden" name="paper_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Paper Title *</label>
                    <input 
                        type="text" 
                        name="title" 
                        required 
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                        placeholder="Enter paper title"
                    />
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">DOI</label>
                    <input 
                        type="text" 
                        name="doi" 
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                        placeholder="Digital Object Identifier"
                    />
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">MLA Citation</label>
                    <textarea 
                        name="mla" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400 resize-none"
                        placeholder="MLA format citation"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">APA Citation</label>
                    <textarea 
                        name="apa" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400 resize-none"
                        placeholder="APA format citation"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Chicago Citation</label>
                    <textarea 
                        name="chicago" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400 resize-none"
                        placeholder="Chicago format citation"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Harvard Citation</label>
                    <textarea 
                        name="harvard" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400 resize-none"
                        placeholder="Harvard format citation"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Vancouver Citation</label>
                    <textarea 
                        name="vancouver" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400 resize-none"
                        placeholder="Vancouver format citation"
                    ></textarea>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6 mt-6 border-t border-slate-100">
                <button 
                    type="button" 
                    onclick="closeEditModal()"
                    class="flex-1 py-4 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold transition-all duration-300"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="flex-2 py-4 px-8 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-xl shadow-blue-100 hover:-translate-y-1 transition-all duration-300"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Research
                </button>
            </div>
        </form>
    </div>
</div> 