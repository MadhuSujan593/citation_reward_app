<div id="manageCitationsModal" class="fixed inset-0 z-[10002] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="manageCitationsContent">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-slate-900" id="manageModalPaperTitle">Manage Citations</h3>
                    <p class="text-xs text-slate-500 mt-1">Manage all your citations for this paper</p>
                </div>
                <button onclick="dashboard.closeManageCitationsModal()" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Citations List -->
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div id="manageCitationsList" class="space-y-3">
                    <!-- Dynamic content will be injected here -->
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-5 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button onclick="dashboard.closeManageCitationsModal()" class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Animation helpers for the new modal since it's not handled by the default modal logic yet
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('manageCitationsModal');
        const content = document.getElementById('manageCitationsContent');
        if (e.target === modal && !content.contains(e.target)) {
            if (window.dashboard) window.dashboard.closeManageCitationsModal();
        }
    });
</script>
