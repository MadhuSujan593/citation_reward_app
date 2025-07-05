// Dashboard JavaScript Module
class Dashboard {
    constructor() {
        // Get current role from DOM instead of Blade syntax
        this.currentRole = document.getElementById('currentRole')?.textContent?.trim() || 'Citer';
        console.log('Initial role:', this.currentRole);
        this.papers = [];
        this.filteredPapers = [];
        this.selectedFilter = '';
        this.citationPaperId = null;
        this.citationAction = null;
        this.paperIdToDelete = null;
        
        this.init();
    }

    init() {
        this.loadPapers();
        this.setupEventListeners();
        this.setupSearchAndFilters();
        this.setupRoleSwitching();
        this.updateUIForRole();
    }

    setupRoleSwitching() {
        // Listen for role changes from Alpine.js
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.target.id === 'currentRole') {
                    const newRole = mutation.target.textContent.trim();
                    if (newRole !== this.currentRole) {
                        console.log('Role changed from', this.currentRole, 'to', newRole);
                        this.currentRole = newRole;
                        this.loadPapers();
                    }
                }
            });
        });

        const currentRoleElement = document.getElementById('currentRole');
        if (currentRoleElement) {
            observer.observe(currentRoleElement, { childList: true, subtree: true });
        }

        // Also listen for clicks on role buttons to update immediately
        document.addEventListener('click', (e) => {
            if (e.target.closest('#citerBtn') || e.target.closest('#funderBtn')) {
                // Small delay to let Alpine.js update the DOM first
                setTimeout(() => {
                    const newRole = document.getElementById('currentRole')?.textContent?.trim();
                    if (newRole && newRole !== this.currentRole) {
                        console.log('Role button clicked, updating from', this.currentRole, 'to', newRole);
                        this.currentRole = newRole;
                        this.loadPapers();
                    }
                }, 50);
            }
        });
    }

    // Method to update role programmatically
    updateRole(newRole) {
        if (newRole !== this.currentRole) {
            console.log('Updating role from', this.currentRole, 'to', newRole);
            this.currentRole = newRole;
            this.updateUIForRole();
            this.loadPapers();
        }
    }

    updateUIForRole() {
        // Update papers title
        const papersTitle = document.getElementById('papersTitle');
        if (papersTitle) {
            papersTitle.textContent = this.currentRole === 'Funder' ? 'My Published Papers' : 'Available Research Papers';
        }

        // Update dashboard subtitle if it exists
        const dashboardSubtitle = document.getElementById('dashboardSubtitle');
        if (dashboardSubtitle) {
            dashboardSubtitle.textContent = this.currentRole === 'Funder' ? 'Funder Portfolio Overview' : 'Citation Management Overview';
        }

        // Update stats card labels
        const citationsLabel = document.querySelector('[data-stat="citations"]');
        if (citationsLabel) {
            citationsLabel.textContent = this.currentRole === 'Citer' ? 'My Citations' : 'Total Citations';
        }

        // Update welcome message if it exists
        const welcomeMessage = document.querySelector('[data-welcome-message]');
        if (welcomeMessage) {
            welcomeMessage.textContent = this.currentRole === 'Citer' 
                ? 'Manage your research citations and discover new papers.' 
                : 'Manage your published papers and track citations.';
        }
    }

    setupEventListeners() {
        // Filter dropdown
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', (e) => {
                this.selectedFilter = e.target.getAttribute('data-filter') || '';
                this.updateFilterLabel(e.target.textContent.trim());
                this.closeFilterDropdown();
            });
        });

        // Search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.performSearch();
                }
            });
        }

        // Form submissions
        this.setupFormSubmissions();
    }

    setupFormSubmissions() {
        // Profile update form
        const profileForm = document.getElementById('updateProfileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', (e) => this.handleProfileUpdate(e));
        }

        // Upload paper form
        const uploadForm = document.getElementById('uploadPaperForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', (e) => this.handlePaperUpload(e));
        }

        // Edit paper form
        const editForm = document.getElementById('editPaperForm');
        if (editForm) {
            editForm.addEventListener('submit', (e) => this.handlePaperEdit(e));
        }

        // Citation confirmation
        const confirmCitationBtn = document.getElementById('confirmCitationBtn');
        if (confirmCitationBtn) {
            confirmCitationBtn.addEventListener('click', () => this.handleCitationConfirm());
        }
    }

    setupSearchAndFilters() {
        // Close filter dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[x-data]')) {
                this.closeFilterDropdown();
            }
        });
    }

    async loadPapers() {
        try {
            this.showLoading(true);
            const roleName = this.currentRole.replace(/^\d+/, '');
            console.log('Loading papers for role:', roleName);
            const endpoint = `/dashboard/papers?role=${encodeURIComponent(roleName)}`;
            
            const response = await fetch(endpoint, {
                headers: {
                    // Get CSRF token from meta tag
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            console.log('Papers loaded:', data);

            if (data.success) {
                this.papers = data.papers || [];
                this.filteredPapers = [...this.papers];
                console.log(`Loaded ${this.papers.length} papers for ${roleName} role`);
                this.displayPapers();
            } else {
                this.showToast(data.message || 'Failed to load papers', true);
            }
        } catch (error) {
            console.error('Error loading papers:', error);
            this.showToast('Error loading papers', true);
        } finally {
            this.showLoading(false);
        }
    }

    displayPapers() {
        const container = document.getElementById('papersContainer');
        const emptyState = document.getElementById('papersEmpty');
        const countElement = document.getElementById('papersCount');
        const emptyTitle = document.getElementById('emptyTitle');
        const emptyMessage = document.getElementById('emptyMessage');

        if (!container) return;

        // Update count
        if (countElement) {
            countElement.textContent = `${this.filteredPapers.length} paper${this.filteredPapers.length !== 1 ? 's' : ''}`;
        }

        if (this.filteredPapers.length === 0) {
            container.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');

            if (emptyTitle && emptyMessage) {
                if (this.currentRole === 'Funder') {
                    emptyTitle.textContent = 'No papers published yet';
                    emptyMessage.textContent = 'Upload your first research paper to get started.';
                } else {
                    emptyTitle.textContent = 'No papers available';
                    emptyMessage.textContent = 'No research papers have been published by funders yet.';
                }
            }
        } else {
            if (emptyState) emptyState.classList.add('hidden');
            container.innerHTML = this.filteredPapers.map(paper => this.createPaperCard(paper)).join('');
        }
    }

    createPaperCard(paper) {
        const publishedDate = new Date(paper.created_at).toLocaleDateString();
        const authorName = paper.author_name || 'Unknown Author';
        const isCited = paper.is_paper_cited_by_current_user;

        return `
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 card-hover border border-white/20 overflow-hidden group relative">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                ${paper.title}
                            </h4>
                            
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">${authorName}</p>
                                    <p class="text-xs text-gray-500">Author ID: ${paper.user_id}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                                <span>Published: ${publishedDate}</span>
                            </div>
                        </div>
                        
                        ${this.currentRole === 'Funder' ? `
                            <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button onclick="dashboard.editPaper(${paper.id})" class="p-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 rounded-lg transition-colors" title="Edit Paper">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="dashboard.deletePaper(${paper.id})" class="p-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors" title="Delete Paper">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="px-6 pb-6">
                    <div class="flex justify-between items-center">
                        <button onclick="dashboard.viewPaperDetails(${paper.id}, 'view')" 
                            class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </button>
                        
                        ${this.currentRole === 'Citer' ? `
                            <button onclick="dashboard.toggleCite(${paper.id}, ${isCited})" 
                                class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 ${isCited ? 'bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white' : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white'}">
                                <i class="fas ${isCited ? 'fa-times' : 'fa-quote-left'} mr-2"></i>
                                ${isCited ? 'Uncite' : 'Cite'}
                            </button>
                        ` : ''}
                    </div>
                </div>
                
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            </div>
        `;
    }

    viewPaperDetails(paperId, mode = 'view', action = 'cite') {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
        const paper = this.papers.find(p => p.id === paperId);
        if (!paper) return;
        document.getElementById('paperModalTitle').textContent = paper.title;
        const modalContent = document.getElementById('paperModalContent');
        modalContent.innerHTML = this.createPaperDetailsContent(paper);
        // Footer Buttons
        const actionBtn = document.getElementById('paperModalActionBtn');
        if (actionBtn) {
            if (mode === 'view') {
                actionBtn.textContent = 'Okay';
                actionBtn.classList.remove('hidden');
                actionBtn.onclick = () => this.closePaperDetailsModal();
            } else if (mode === 'cite') {
                actionBtn.textContent = 'Proceed to Cite';
                actionBtn.classList.remove('hidden');
                actionBtn.onclick = () => this.confirmCitation(paper.id, action);
            }
        }
        document.getElementById('paperDetailsModal').classList.remove('hidden');
    }

    createPaperDetailsContent(paper) {
        const citationFields = [
            { key: 'mla', label: 'MLA Citation' },
            { key: 'apa', label: 'APA Citation' },
            { key: 'chicago', label: 'Chicago Citation' },
            { key: 'harvard', label: 'Harvard Citation' },
            { key: 'vancouver', label: 'Vancouver Citation' },
            { key: 'doi', label: 'DOI' }
        ];

        const citationHTML = citationFields
            .filter(field => paper[field.key])
            .map(field => `
                <div class="relative group">
                    <h4 class="font-semibold text-gray-800 mb-2">${field.label}</h4>
                    <div class="relative">
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg" id="${field.key}Text">${paper[field.key]}</p>
                        <button onclick="dashboard.handleCopy('${field.key}Text')" 
                            class="absolute top-2 right-2 p-1 bg-white hover:bg-gray-100 rounded transition-colors">
                            <i class="fas fa-copy text-gray-500 hover:text-gray-700"></i>
                        </button>
                    </div>
                </div>
            `).join('');

        return `
            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Author Information</h4>
                    <p class="text-gray-600">${paper.author_name || 'Unknown Author'}</p>
                    <p class="text-sm text-gray-500">Author ID: ${paper.user_id}</p>
                    <p class="text-sm text-gray-500">Paper ID: ${paper.id}</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Publication Date</h4>
                    <p class="text-gray-600">${new Date(paper.created_at).toLocaleDateString()}</p>
                </div>
                
                ${citationHTML}
            </div>
        `;
    }

    async handleCopy(targetId) {
        const textEl = document.getElementById(targetId);
        if (!textEl) return;

        const text = textEl.innerText.trim();

        try {
            await navigator.clipboard.writeText(text);
            this.showCopyFeedback();
        } catch (err) {
            this.fallbackCopy(text);
        }
    }

    fallbackCopy(text) {
        const tempInput = document.createElement("textarea");
        tempInput.value = text;
        tempInput.style.position = "fixed";
        tempInput.style.opacity = 0;
        document.body.appendChild(tempInput);
        tempInput.select();

        try {
            const success = document.execCommand("copy");
            if (success) {
                this.showCopyFeedback();
            }
        } catch (err) {
            this.showToast("Copy failed. Please copy manually.", true);
        }

        document.body.removeChild(tempInput);
    }

    showCopyFeedback() {
        const feedback = document.getElementById('copyFeedback');
        if (feedback) {
            feedback.classList.remove('opacity-0');
            setTimeout(() => {
                feedback.classList.add('opacity-0');
            }, 1500);
        }
    }

    toggleCite(paperId, isCited) {
        if (isCited) {
            this.confirmCitation(paperId, 'uncite');
        } else {
            this.viewPaperDetails(paperId, 'cite', 'cite');
        }
    }

    confirmCitation(paperId, action = 'cite') {
        this.citationPaperId = paperId;
        this.citationAction = action.toLowerCase();

        const actionCapitalized = this.citationAction.charAt(0).toUpperCase() + this.citationAction.slice(1);
        const modal = document.getElementById('confirmCitationModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const confirmBtn = document.getElementById('confirmCitationBtn');

        if (title) title.textContent = `Confirm ${actionCapitalized}`;
        if (message) message.textContent = `Are you sure you want to ${actionCapitalized} this paper?`;
        if (confirmBtn) {
            confirmBtn.textContent = `Yes, ${actionCapitalized}`;
            
            if (this.citationAction === 'uncite') {
                confirmBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                confirmBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            } else {
                confirmBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }
        }

        if (modal) modal.classList.remove('hidden');
    }

    async handleCitationConfirm() {
        if (!this.citationPaperId || !this.citationAction) return;

        try {
            const response = await fetch(`/${this.citationAction}-paper/${this.citationPaperId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(`Paper ${this.citationAction === 'cite' ? 'Cited' : 'Uncited'} successfully!`);
                this.loadPapers();
            } else {
                this.showToast(data.message || `Failed to ${this.citationAction}.`, true);
            }
            
            this.closePaperDetailsModal();
            this.closeConfirmModal();
        } catch (err) {
            console.error(err);
            this.closePaperDetailsModal();
            this.closeConfirmModal();
            this.showToast('Something went wrong.', true);
        }
    }

    performSearch() {
        const query = document.getElementById('searchInput')?.value.trim();
        if (!query) return;

        const params = new URLSearchParams();
        params.append('query', query);
        if (this.selectedFilter) {
            params.append('filter_type', this.selectedFilter);
        }
        params.append('role', this.currentRole);

        fetch(`/papers/search?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            this.filteredPapers = data;
            this.displayPapers();
        })
        .catch(err => {
            console.error("Search failed:", err);
            this.showToast('Search failed', true);
        });
    }

    updateFilterLabel(label) {
        const filterLabel = document.getElementById('filterLabel');
        if (filterLabel) {
            filterLabel.textContent = label;
        }
    }

    closeFilterDropdown() {
        // This will be handled by Alpine.js
    }

    showLoading(show) {
        const loading = document.getElementById('papersLoading');
        const container = document.getElementById('papersContainer');
        const emptyState = document.getElementById('papersEmpty');

        if (show) {
            if (loading) loading.classList.remove('hidden');
            if (container) container.classList.add('hidden');
            if (emptyState) emptyState.classList.add('hidden');
        } else {
            if (loading) loading.classList.add('hidden');
            if (container) container.classList.remove('hidden');
        }
    }

    showToast(message, isError = false) {
        // Use Alpine.js toast if available
        if (window.Alpine && window.Alpine.store('toast')) {
            window.Alpine.store('toast').showToast(message, isError);
        } else {
            // Fallback to old toast
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');

            if (toast && toastMessage) {
                toastMessage.textContent = message;
                toast.classList.remove('bg-green-600', 'bg-red-600');
                toast.classList.add(isError ? 'bg-red-600' : 'bg-green-600');
                toast.classList.remove('opacity-0', 'pointer-events-none');
                toast.classList.add('opacity-100');

                setTimeout(() => {
                    toast.classList.remove('opacity-100');
                    toast.classList.add('opacity-0', 'pointer-events-none');
                }, 3000);
            }
        }
    }

    // Modal functions
    openProfileModal() {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
        document.getElementById('updateProfileModal').classList.remove('hidden');
        // Get user data from data attributes
        const firstName = document.querySelector('[data-user-first-name]')?.getAttribute('data-user-first-name') || '';
        const lastName = document.querySelector('[data-user-last-name]')?.getAttribute('data-user-last-name') || '';
        const email = document.querySelector('[data-user-email]')?.getAttribute('data-user-email') || '';
        document.getElementById('first_name').value = firstName;
        document.getElementById('last_name').value = lastName;
        document.getElementById('email').value = email;
    }

    closeProfileModal() {
        document.getElementById('updateProfileModal').classList.add('hidden');
    }

    openPaperModal() {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
        console.log('Opening upload paper modal');
        const modal = document.getElementById('uploadPaperModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            console.log('Upload paper modal opened successfully');
        } else {
            console.error('Upload paper modal not found');
        }
    }

    closePaperModal() {
        console.log('Closing upload paper modal');
        const modal = document.getElementById('uploadPaperModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            console.log('Upload paper modal closed successfully');
        } else {
            console.error('Upload paper modal not found');
        }
    }

    closePaperDetailsModal() {
        document.getElementById('paperDetailsModal').classList.add('hidden');
    }

    closeConfirmModal() {
        document.getElementById('confirmCitationModal').classList.add('hidden');
        this.citationPaperId = null;
        this.citationAction = null;
    }

    // Form handlers
    async handleProfileUpdate(e) {
        e.preventDefault();
        const form = e.target;

        try {
            const response = await fetch('/profile-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    first_name: form.first_name.value,
                    last_name: form.last_name.value,
                    email: form.email.value,
                })
            });

            const data = await response.json();

            if (data.success) {
                document.querySelectorAll('p.font-medium')[0].textContent = `${data.user.first_name} ${data.user.last_name}`;
                document.querySelectorAll('p.text-sm.opacity-80')[0].textContent = data.user.email;
                this.closeProfileModal();
                this.showToast('Profile updated successfully!');
            } else {
                this.showToast('Update failed.', true);
            }
        } catch (error) {
            console.error(error);
            this.showToast('An error occurred.', true);
        }
    }

    async handlePaperUpload(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch('/papers/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.closePaperModal();
                this.showToast('Paper uploaded successfully!');
                this.loadPapers();
                e.target.reset();
            } else {
                this.showToast(data.message || 'Failed to upload paper.', true);
            }
        } catch (err) {
            console.error(err);
            this.showToast('Something went wrong.', true);
        }
    }

    async handlePaperEdit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const paperId = form.paper_id.value;

        formData.append('_method', 'PUT');

        try {
            const response = await fetch(`/papers/${paperId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showToast('Paper updated successfully');
                this.closeEditModal();
                this.loadPapers();
            } else {
                this.showToast(data.message || 'Failed to update paper', true);
            }
        } catch (err) {
            console.error('Update error:', err);
            this.showToast('Error updating paper', true);
        }
    }

    editPaper(paperId) {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
        const paper = this.papers.find(p => p.id === paperId);
        if (!paper) {
            this.showToast('Paper not found', true);
            return;
        }
        const form = document.getElementById('editPaperForm');
        if (form) {
            form.paper_id.value = paper.id;
            form.title.value = paper.title || '';
            form.mla.value = paper.mla || '';
            form.apa.value = paper.apa || '';
            form.chicago.value = paper.chicago || '';
            form.harvard.value = paper.harvard || '';
            form.vancouver.value = paper.vancouver || '';
            form.doi.value = paper.doi || '';
        }
        document.getElementById('editPaperModal').classList.remove('hidden');
    }

    closeEditModal() {
        const form = document.getElementById('editPaperForm');
        if (form) form.reset();
        document.getElementById('editPaperModal').classList.add('hidden');
    }

    deletePaper(paperId) {
        this.paperIdToDelete = paperId;
        document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    }

    closeDeletePaperModal() {
        this.paperIdToDelete = null;
        document.getElementById('deleteConfirmationModal').classList.add('hidden');
    }

    async confirmDeletePaper() {
        if (!this.paperIdToDelete) return;

        try {
            const response = await fetch(`/papers/${this.paperIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            this.closeDeletePaperModal();

            if (data.success) {
                this.showToast(data.message || 'Paper deleted');
                this.papers = this.papers.filter(p => p.id !== this.paperIdToDelete);
                this.filteredPapers = [...this.papers];
                this.loadPapers();
            } else {
                this.showToast(data.message || 'Delete failed', true);
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.closeDeletePaperModal();
            this.showToast('Error deleting paper', true);
        }
    }

    refreshPapers() {
        this.loadPapers();
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new Dashboard();
});

// Global functions for backward compatibility
function loadPapers() { window.dashboard?.loadPapers(); }
function refreshPapers() { window.dashboard?.refreshPapers(); }
function openProfileModal() { window.dashboard?.openProfileModal(); }
function closeProfileModal() { window.dashboard?.closeProfileModal(); }
function openPaperModal() { window.dashboard?.openPaperModal(); }
function closePaperModal() { window.dashboard?.closePaperModal(); }
function closePaperDetailsModal() { window.dashboard?.closePaperDetailsModal(); }
function viewPaperDetails(paperId, mode, action) { window.dashboard?.viewPaperDetails(paperId, mode, action); }
function toggleCite(paperId, isCited) { window.dashboard?.toggleCite(paperId, isCited); }
function editPaper(paperId) { window.dashboard?.editPaper(paperId); }
function deletePaper(paperId) { window.dashboard?.deletePaper(paperId); }
function confirmDeletePaper() { window.dashboard?.confirmDeletePaper(); }
function closeDeletePaperModal() { window.dashboard?.closeDeletePaperModal(); }
function closeEditModal() { window.dashboard?.closeEditModal(); }
function confirmCitation(paperId, action) { window.dashboard?.confirmCitation(paperId, action); }
function closeConfirmModal() { window.dashboard?.closeConfirmModal(); }
function handleCopy(targetId) { window.dashboard?.handleCopy(targetId); }
function switchRole(role) { window.dashboard?.updateRole(role); }

// Global safeguard: close sidebar if any modal is opened
(function() {
    const modalIds = [
        'uploadPaperModal',
        'updateProfileModal',
        'paperDetailsModal',
        'editPaperModal',
        'deleteConfirmationModal',
        'deleteConfirmModal',
        'confirmCitationModal'
    ];
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    modalIds.forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            new MutationObserver(() => {
                if (!modal.classList.contains('hidden')) {
                    if (sidebar && sidebarOverlay) {
                        sidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                    }
                }
            }).observe(modal, { attributes: true, attributeFilter: ['class'] });
        }
    });
})();

const hamburgerBtn = document.getElementById('hamburgerBtn');
const sidebar = document.getElementById('sidebar');
const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
const sidebarOverlay = document.getElementById('sidebarOverlay');

if (hamburgerBtn && sidebar && sidebarOverlay) {
    hamburgerBtn.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
    });
}
if (sidebarCloseBtn && sidebar && sidebarOverlay) {
    sidebarCloseBtn.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    });
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    });
} 