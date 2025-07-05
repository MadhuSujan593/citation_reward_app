<div 
    id="updateProfileModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] px-2 sm:px-6 hidden"
>
    <div class="bg-white w-full max-w-sm mx-2 rounded-2xl shadow-2xl p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Update Profile</h2>
            <button 
                onclick="closeProfileModal()" 
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form id="updateProfileForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    required 
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200"
                />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    required 
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200"
                />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-200"
                />
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <button 
                    type="button" 
                    onclick="closeProfileModal()"
                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div> 