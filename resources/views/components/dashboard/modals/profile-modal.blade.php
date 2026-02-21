<div 
    id="updateProfileModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-lg mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
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
        <form id="updateProfileForm" class="space-y-6" enctype="multipart/form-data">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Profile Picture (Optional)</label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 border-2 border-solid border-slate-300 flex items-center justify-center overflow-hidden" id="profileImagePreview">
                        <i class="fas fa-user text-2xl text-slate-400"></i>
                    </div>
                    <div class="flex-1">
                        <label for="profile_picture" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-semibold text-sm hover:bg-blue-100 transition-colors">
                            <i class="fas fa-camera"></i>
                            <span>Change Picture</span>
                        </label>
                        <input 
                            type="file" 
                            id="profile_picture" 
                            name="profile_picture" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewProfileImage(event)"
                        />
                        <p class="text-[11px] text-slate-400 mt-2 font-medium">JPEG, PNG, GIF, WEBP, or AVIF. Max 5MB.</p>
                    </div>
                </div>
            </div>

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
                    readonly 
                    class="w-full px-4 py-3.5 bg-slate-100 border border-slate-200 rounded-2xl focus:outline-none transition-all duration-300 font-medium text-slate-500 cursor-not-allowed"
                />
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Google Scholar Link</label>
                <input 
                    type="url" 
                    id="google_scholar_link" 
                    name="google_scholar_link" 
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                    placeholder="https://scholar.google.com/..."
                />
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Scopus ID Link</label>
                <input 
                    type="url" 
                    id="scopus_id_link" 
                    name="scopus_id_link" 
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                    placeholder="https://www.scopus.com/..."
                />
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">ORCID Link</label>
                <input 
                    type="url" 
                    id="orcid_link" 
                    name="orcid_link" 
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-300 font-medium text-slate-900 placeholder-slate-400"
                    placeholder="https://orcid.org/..."
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