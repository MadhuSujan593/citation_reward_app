// Dashboard JavaScript Module
class Dashboard {
    constructor() {
        // Source of truth: Body data attribute
        this.currentRole = document.body.dataset.userRole?.trim() || 'Citer';
        this.papers = [];
        this.filteredPapers = [];
        this.paperIdToDelete = null;
        this.viewMode = "explore"; // 'explore' or 'citations'
        this.currentPage = 1;
        
        this.init();
    }

    init() {
        if (document.getElementById('papersContainer')) {
            this.loadPapers();
        }
        this.updateUIForRole();
        this.setupSearchAndFilters();
        this.setupMobileMenu();
    }

    setupMobileMenu() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        if (!hamburgerBtn || !sidebar || !overlay) return;

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        hamburgerBtn.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeSidebar);
        }

        // Close sidebar on navigation (for mobile)
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });
    }

    updateRole(newRole) {
        if (this.currentRole === newRole) return;
        
        this.currentRole = newRole;
        
        // Update body attribute for consistency
        document.body.dataset.userRole = newRole;
        document.body.setAttribute('data-user-role', newRole);
        
        // Update all UI elements
        this.updateUIForRole();

        // Persist to backend
        fetch('/dashboard/switch-role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ role: newRole })
        }).catch(err => console.error('Failed to persist role:', err));

        // Refresh content
        this.loadPapers();
    }

    updateUIForRole() {
        // Update papers title
        const papersTitle = document.getElementById("papersTitle");
        if (papersTitle) {
            if (this.currentRole === "Funder") {
                papersTitle.textContent = "My Published Papers";
            } else if (this.viewMode === "citations") {
                papersTitle.textContent = "My Citations";
            } else {
                papersTitle.textContent = "Available Research Papers";
            }
        }

        // Update current role display in welcome banner
        const roleDisplay = document.getElementById("currentRoleDisplay");
        if (roleDisplay) {
            roleDisplay.textContent = this.currentRole;
        }

        // Update dashboard subtitle if it exists
        const dashboardSubtitle = document.getElementById("dashboardSubtitle");
        if (dashboardSubtitle) {
            dashboardSubtitle.textContent =
                this.currentRole === "Funder"
                    ? "Funder Portfolio Overview"
                    : "Citation Management Overview";
        }

        // Update stats card labels
        const citationsLabel = document.querySelector(
            '[data-stat="citations"]'
        );
        if (citationsLabel) {
            citationsLabel.textContent =
                this.currentRole === "Citer"
                    ? "My Citations"
                    : "Total Citations";
        }

        // Update welcome message if it exists
        const welcomeMessage = document.querySelector("[data-welcome-message]");
        if (welcomeMessage) {
            welcomeMessage.textContent =
                this.currentRole === "Citer"
                    ? "Explore research papers and manage your citations."
                    : "Control your publications and track citation growth.";
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

    async loadPapers(page = 1) {
        if (!document.getElementById('papersContainer')) return;
        this.viewMode = "explore";
        this.currentPage = page;

        try {
            this.showLoading(true);
            const roleName = this.currentRole.replace(/^\d+/, '');
            const endpoint = `/dashboard/papers?role=${encodeURIComponent(roleName)}&page=${page}`;
            
            const response = await fetch(endpoint, {
                headers: {
                    // Get CSRF token from meta tag
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.papers = data.papers || [];
                this.filteredPapers = [...this.papers];
                this.updateUIForRole(); // Add this to update title when viewMode changes
                this.displayPapers();

                // Render pagination
                if (data.pagination) {
                    this.renderPagination(data.pagination);
                }

                // Update stats
                if (data.stats) {
                    const totalPapersEl = document.getElementById("totalPapers");
                    const totalCitsEl = document.getElementById("totalCitations");
                    if (totalPapersEl) totalPapersEl.textContent = data.stats.totalPapers;
                    if (totalCitsEl) totalCitsEl.textContent = data.stats.totalCitations;
                }
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
                } else if (this.viewMode === 'citations') {
                    emptyTitle.textContent = 'No citations found';
                    emptyMessage.textContent = 'Explore research papers to find and cite interesting research.';
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

    renderPagination(pagination) {
        const container = document.getElementById("paginationContainer");
        if (!container || !pagination) return;

        if (pagination.last_page <= 1) {
            container.innerHTML = "";
            return;
        }

        let html = `
            <div class="flex items-center gap-1 bg-white p-1 rounded-xl shadow-sm border border-slate-100">
                <button 
                    onclick="dashboard.loadPage(${pagination.current_page - 1})"
                    ${pagination.current_page === 1 ? 'disabled' : ''}
                    class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-slate-50 disabled:opacity-30 disabled:hover:bg-transparent transition-all"
                >
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
        `;

        for (let i = 1; i <= pagination.last_page; i++) {
            html += `
                <button 
                    onclick="dashboard.loadPage(${i})"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold transition-all ${
                        pagination.current_page === i 
                        ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' 
                        : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600'
                    }"
                >
                    ${i}
                </button>
            `;
        }

        html += `
                <button 
                    onclick="dashboard.loadPage(${pagination.current_page + 1})"
                    ${pagination.current_page === pagination.last_page ? 'disabled' : ''}
                    class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-slate-50 disabled:opacity-30 disabled:hover:bg-transparent transition-all"
                >
                    <i class="fas fa-chevron-right text-sm"></i>
                </button>
            </div>
        `;

        container.innerHTML = html;
    }

    async loadPage(page) {
        if (page < 1) return;
        this.currentPage = page;
        if (this.viewMode === "citations") {
            await this.loadMyCitations(page);
        } else {
            await this.loadPapers(page);
        }
    }

    createPaperCard(paper) {
        const publishedDate = new Date(paper.created_at).toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
        const authorName = paper.author_name || "Unknown Author";
        const isCited = paper.is_paper_cited_by_current_user;
        const hasPendingClaim = paper.has_pending_claim;
        const citationsCount = paper.citers_count || 0;

        return `
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02),0_10px_20px_rgba(0,0,0,0.03)] transition-all duration-300 overflow-hidden flex flex-col h-full group relative">
                <!-- Subtle Accent -->
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-100 transition-colors duration-300"></div>
                
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-2">
                        ${
                            this.currentRole === "Funder"
                                ? `
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity ml-auto">
                                <button onclick="dashboard.editPaper(${paper.id})" class="p-1.5 text-slate-400 hover:text-slate-900 rounded-lg transition-all">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </button>
                                <button onclick="dashboard.deletePaper(${paper.id})" class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg transition-all">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        `
                                : ""
                        }
                    </div>
                    
                    <h4 class="text-[17px] font-bold text-slate-900 mb-5 line-clamp-2 leading-tight group-hover:text-slate-800 transition-colors">
                        ${paper.title}
                    </h4>
                    
                    <div class="flex items-center gap-4 mb-6 bg-slate-50/50 p-4 rounded-xl border border-slate-100/50">
                        <div class="w-11 h-11 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-900 font-bold text-sm border border-slate-100 flex-shrink-0 overflow-hidden">
                            ${paper.user && paper.user.profile_picture 
                                ? `<img src="/storage/${paper.user.profile_picture}" alt="${authorName}" class="w-full h-full object-cover" onerror="this.outerHTML='${authorName.split(' ').map(n => n[0]).join('')}'">` 
                                : authorName.split(' ').map(n => n[0]).join('')}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">${authorName}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold mb-1 tracking-tight">Published</p>
                            <p class="text-[11px] font-bold text-slate-700 flex items-center">
                                <i class="fas fa-calendar text-slate-400 mr-1.5"></i> ${publishedDate}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold mb-1 tracking-tight">Citations</p>
                            <p class="text-[11px] font-bold text-slate-700 flex items-center">
                                <i class="fas fa-quote-right text-slate-400 mr-1.5"></i> ${citationsCount}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center gap-3">
                    <button onclick="dashboard.viewPaperDetails(${paper.id}, 'view')" 
                        class="flex-1 h-10 flex items-center justify-center gap-2 text-xs font-bold text-white bg-blue-600 border border-transparent rounded-full hover:bg-blue-700 transition-all shadow-sm active:scale-95">
                        <i class="fas fa-eye text-blue-100"></i>
                        Details
                    </button>
                    
                    ${
                        this.currentRole === "Citer"
                            ? `
                        <button onclick="${isCited && hasPendingClaim ? '' : `dashboard.toggleCite(${paper.id}, ${isCited})`}" 
                            class="flex-1 h-10 flex items-center justify-center text-xs font-bold rounded-xl transition-all shadow-md active:scale-95 ${
                                isCited && hasPendingClaim
                                    ? "bg-slate-200 text-slate-400 cursor-not-allowed shadow-none"
                                    : isCited
                                    ? "bg-rose-500 text-white hover:bg-rose-600 shadow-rose-100 font-bold"
                                    : "bg-blue-600 hover:bg-blue-700 text-white shadow-blue-200 font-bold"
                            }" ${isCited && hasPendingClaim ? 'disabled' : ''} style="background-color: ${isCited && hasPendingClaim ? '#e2e8f0' : isCited ? '#f43f5e' : '#2563eb'} !important; color: white !important;">
                            ${isCited && hasPendingClaim ? "Claimed" : isCited ? "Uncite" : "Cite paper"}
                        </button>
                    `
                            : ""
                    }
                </div>
            </div>
        `;
    }

    viewPaperDetails(paperId, mode = 'view', action = 'cite') {
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

    updateFilterLabel(label) {
        const filterLabel = document.getElementById("filterLabel");
        if (filterLabel) {
            filterLabel.textContent = label;
        }
    }

    closeFilterDropdown() {
        // This will be handled by Alpine.js
    }

    async loadMyCitations(page = 1) {
        if (!document.getElementById('papersContainer')) return;
        this.viewMode = 'citations';
        this.currentPage = page;
        this.showLoading(true);

        try {
            const res = await fetch(`/my-citations?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            const data = await res.json();
            
            if (data.success) {
                this.papers = data.papers || [];
                this.filteredPapers = [...this.papers];
                this.showLoading(false);
                this.updateUIForRole(); // Add this to update title when viewMode changes
                this.displayPapers(); // reuse existing function to show cards
                
                // Render pagination
                if (data.pagination) {
                    this.renderPagination(data.pagination);
                }

                // Update stats
                if (data.stats) {
                    const totalPapersEl = document.getElementById("totalPapers");
                    const totalCitsEl = document.getElementById("totalCitations");
                    if (totalPapersEl) totalPapersEl.textContent = data.stats.totalPapers;
                    if (totalCitsEl) totalCitsEl.textContent = data.stats.totalCitations;
                }
            } else {
                this.showToast(data.message || 'Failed to load citations', true);
                this.showLoading(false);
            }
        } catch (err) {
            console.error('Failed to load citations:', err);
            this.showToast('Failed to load citations', true);
            this.showLoading(false);
        }
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
        if (typeof window.showToast === 'function') {
            window.showToast(message, isError);
        } else {
            console.log('Global showToast not available:', message);
        }
    }

    // Global helper for profile image preview
    previewProfileImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                this.showToast('Image is too large. Max size is 5MB.', true);
                event.target.value = ''; // Reset input
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profileImagePreview');
                if (preview) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                }
            }
            reader.readAsDataURL(file);
        }
    }

    // Modal functions
    openProfileModal() {
        document.getElementById('updateProfileModal').classList.remove('hidden');
        // Get user data from data attributes
        const userDataContainer = document.querySelector('[data-user-first-name]');
        const firstName = userDataContainer?.getAttribute('data-user-first-name') || '';
        const lastName = userDataContainer?.getAttribute('data-user-last-name') || '';
        const email = userDataContainer?.getAttribute('data-user-email') || '';
        const googleScholar = userDataContainer?.getAttribute('data-user-google-scholar') || '';
        const scopusId = userDataContainer?.getAttribute('data-user-scopus-id') || '';
        const orcid = userDataContainer?.getAttribute('data-user-orcid') || '';
        const profilePicture = userDataContainer?.getAttribute('data-user-profile-picture') || '';
        
        document.getElementById('first_name').value = firstName;
        document.getElementById('last_name').value = lastName;
        document.getElementById('email').value = email;
        document.getElementById('google_scholar_link').value = googleScholar;
        document.getElementById('scopus_id_link').value = scopusId;
        document.getElementById('orcid_link').value = orcid;

        // Display current picture
        const preview = document.getElementById('profileImagePreview');
        if (preview) {
            if (profilePicture) {
                // Ensure storage path format is correct. Assuming stored in 'profiles/...'
                preview.innerHTML = `<img src="/storage/${profilePicture}" alt="Profile" class="w-full h-full object-cover">`;
            } else {
                preview.innerHTML = `<i class="fas fa-user text-2xl text-slate-400"></i>`;
            }
        }
    }

    closeProfileModal() {
        document.getElementById('updateProfileModal').classList.add('hidden');
    }

    openPaperModal() {
        console.log('Class openPaperModal called');
        const modal = document.getElementById('uploadPaperModal');
        if (modal) {
            modal.classList.remove('hidden');
        } else {
            console.error('Modal element not found');
        }
    }

    closePaperModal() {
        document.getElementById('uploadPaperModal').classList.add('hidden');
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
        const formData = new FormData(form);

        // Don't send empty file object
        const picInput = form.querySelector('#profile_picture');
        if (picInput && picInput.files.length === 0) {
            formData.delete('profile_picture');
        } else if (picInput && picInput.files.length > 0) {
            if (picInput.files[0].size > 5 * 1024 * 1024) {
                this.showToast('Profile picture exceeds the 5MB limit.', true);
                return;
            }
        }

        try {
            const response = await fetch('/profile-update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.closeProfileModal();
                this.showToast('Profile updated successfully!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                let errorMsg = data.message || 'Update failed.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join(' ');
                }
                this.showToast(errorMsg, true);
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
                let errorMsg = data.message || 'Failed to upload paper.';
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    errorMsg = firstError;
                }
                this.showToast(errorMsg, true);
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
                let errorMsg = data.message || 'Failed to update paper';
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    errorMsg = firstError;
                }
                this.showToast(errorMsg, true);
            }
        } catch (err) {
            console.error('Update error:', err);
            this.showToast('Error updating paper', true);
        }
    }

    editPaper(paperId) {
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

// Global functions for backward compatibility (Exposed to window for inline onclicks)
window.loadPapers = function() { window.dashboard?.loadPapers(); };
window.refreshPapers = function() { window.dashboard?.refreshPapers(); };
window.openProfileModal = function() { window.dashboard?.openProfileModal(); };
window.closeProfileModal = function() { window.dashboard?.closeProfileModal(); };
window.previewProfileImage = function(event) { window.dashboard?.previewProfileImage(event); };
window.openPaperModal = function() { 
    console.log('Global openPaperModal called');
    if (window.dashboard) {
        window.dashboard.openPaperModal();
    } else {
        console.error('window.dashboard not initialized');
    }
};
window.closePaperModal = function() { window.dashboard?.closePaperModal(); };
window.closePaperDetailsModal = function() { window.dashboard?.closePaperDetailsModal(); };
window.viewPaperDetails = function(paperId, mode, action) { window.dashboard?.viewPaperDetails(paperId, mode, action); };
window.toggleCite = function(paperId, isCited) { window.dashboard?.toggleCite(paperId, isCited); };
window.editPaper = function(paperId) { window.dashboard?.editPaper(paperId); };
window.deletePaper = function(paperId) { window.dashboard?.deletePaper(paperId); };
window.confirmDeletePaper = function() { window.dashboard?.confirmDeletePaper(); };
window.closeDeletePaperModal = function() { window.dashboard?.closeDeletePaperModal(); };
window.closeEditModal = function() { window.dashboard?.closeEditModal(); };
window.confirmCitation = function(paperId, action) { window.dashboard?.confirmCitation(paperId, action); };
window.closeConfirmModal = function() { window.dashboard?.closeConfirmModal(); };
window.handleCopy = function(targetId) { window.dashboard?.handleCopy(targetId); }; 