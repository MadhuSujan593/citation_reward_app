<div 
    id="uploadPaperModal"
    class="fixed inset-0 flex justify-center items-start sm:items-center pt-16 sm:pt-0 z-[11010] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full h-full sm:h-auto sm:max-w-lg mx-2 rounded-2xl shadow-2xl p-0 sm:p-0 relative max-h-[60vh] flex flex-col">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 bg-white flex justify-between items-center p-4 sm:p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Upload Published Paper</h2>
            <button 
                onclick="closePaperModal()" 
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <!-- Scrollable Body -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            <!-- Form -->
            <form id="uploadPaperForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paper Title *</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200"
                            placeholder="Enter paper title"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                        <input 
                            type="text" 
                            name="doi" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200"
                            placeholder="Digital Object Identifier"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">MLA Citation</label>
                        <textarea 
                            name="mla" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200 resize-none"
                            placeholder="MLA format citation"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">APA Citation</label>
                        <textarea 
                            name="apa" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200 resize-none"
                            placeholder="APA format citation"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chicago Citation</label>
                        <textarea 
                            name="chicago" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200 resize-none"
                            placeholder="Chicago format citation"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harvard Citation</label>
                        <textarea 
                            name="harvard" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200 resize-none"
                            placeholder="Harvard format citation"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vancouver Citation</label>
                        <textarea 
                            name="vancouver" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200 resize-none"
                            placeholder="Vancouver format citation"
                        ></textarea>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button 
                        type="button" 
                        onclick="closePaperModal()"
                        class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-upload mr-2"></i>
                        Submit Paper
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 