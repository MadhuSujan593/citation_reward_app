<div 
    id="updateProfileModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Update Profile</h2>
            <button 
                onclick="closeProfileModal()" 
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form id="updateProfileForm" class="space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">First Name</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    required 
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                />
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    required 
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                />
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                />
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button 
                    type="button" 
                    onclick="closeProfileModal()"
                    class="flex-1 py-4 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold transition-all duration-300"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="flex-1 py-4 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-xl shadow-blue-100 hover:-translate-y-1 transition-all duration-300"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div> 