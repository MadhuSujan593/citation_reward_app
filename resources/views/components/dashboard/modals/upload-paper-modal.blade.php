<div 
    id="uploadPaperModal"
    class="fixed inset-0 flex justify-center items-center z-[11010] p-3 sm:p-6 hidden bg-black/50 backdrop-blur-sm"
>
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl relative max-h-[90vh] flex flex-col overflow-hidden">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 bg-white flex justify-between items-center p-6 border-b border-slate-100">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Upload Research</h2>
            <button 
                onclick="closePaperModal()" 
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <!-- Scrollable Body -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            <!-- Form -->
            <form id="uploadPaperForm" class="space-y-6">
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
            </form>
        </div>
        <!-- Sticky Footer -->
        <div class="sticky bottom-0 z-10 bg-slate-50/80 backdrop-blur-md p-6 border-t border-slate-200 flex justify-end gap-4">
            <button 
                type="button" 
                onclick="closePaperModal()"
                class="px-8 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-2xl font-bold transition-all duration-200"
            >
                Cancel
            </button>
            <button 
                type="submit" 
                form="uploadPaperForm"
                class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition-all duration-300 shadow-xl shadow-blue-100 hover:-translate-y-1 active:scale-95 flex items-center gap-2"
            >
                <i class="fas fa-upload"></i>
                Submit Research
            </button>
        </div>
    </div>
</div>
 