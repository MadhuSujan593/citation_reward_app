// Dashboard JavaScript Module
class Dashboard {
    constructor() {
        // Get current role from DOM instead of Blade syntax
        this.currentRole =
            document.getElementById("currentRole")?.textContent?.trim() ||
            "Citer";
        console.log("Initial role:", this.currentRole);
        this.papers = [];
        this.filteredPapers = [];
        this.selectedFilter = "";
        this.citationPaperId = null;
        this.citationAction = null;
        this.paperIdToDelete = null;
        this.viewMode = "explore"; // 'explore' or 'citations'
        this.currentPage = 1;

        this.init();
    }

    init() {
        if (document.getElementById("papersContainer")) {
            this.loadPapers();
        }
        this.setupEventListeners();
        this.setupSearchAndFilters();
        this.setupRoleSwitching();
        this.updateUIForRole();
    }

    setupRoleSwitching() {
        const buttons = {
            Citer: document.getElementById("citerBtn"),
            Funder: document.getElementById("funderBtn"),
            Admin: document.getElementById("adminBtn"),
        };

        Object.entries(buttons).forEach(([role, btn]) => {
            if (btn) {
                btn.addEventListener("click", () => this.updateRole(role));
            }
        });
    }
    // Method to update role programmatically
    async updateRole(newRole) {
        if (newRole === this.currentRole) return;

        console.log("Updating role from", this.currentRole, "to", newRole);

        try {
            const response = await fetch("/dashboard/switch-role", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: JSON.stringify({ role: newRole }),
            });

            const data = await response.json();

            if (data.success) {
                this.currentRole = newRole;
                this.updateUIForRole();
                this.loadPapers();

                // Optionally redirect if role is "Admin"
                if (newRole === "Admin") {
                    window.location.href = "/admin/dashboard";
                }
            } else {
                this.showToast(data.message || "Role update failed", true);
            }
        } catch (error) {
            console.error("Failed to update role:", error);
            this.showToast("Error updating role", true);
        }
    }

    updateUIForRole() {
        // Update papers title
        const papersTitle = document.getElementById("papersTitle");
        if (papersTitle) {
            papersTitle.textContent =
                this.currentRole === "Funder"
                    ? "My Published Papers"
                    : "Available Research Papers";
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
                    ? "Manage your research citations and discover new papers."
                    : "Manage your published papers and track citations.";
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

    setupEventListeners() {
        // Filter dropdown
        document.querySelectorAll("[data-filter]").forEach((button) => {
            button.addEventListener("click", (e) => {
                this.selectedFilter =
                    e.target.getAttribute("data-filter") || "";
                this.updateFilterLabel(e.target.textContent.trim());
                this.closeFilterDropdown();
            });
        });

        // Search input
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("input", (e) => {
                const value = e.target.value.trim();
                if (value === "") {
                    // Reset to full list
                    this.filteredPapers = [...this.papers];
                    this.displayPapers();
                }
            });
            searchInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
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
        const profileForm = document.getElementById("updateProfileForm");
        if (profileForm) {
            profileForm.addEventListener("submit", (e) =>
                this.handleProfileUpdate(e)
            );
        }

        // Upload paper form
        const uploadForm = document.getElementById("uploadPaperForm");
        if (uploadForm) {
            uploadForm.addEventListener("submit", (e) =>
                this.handlePaperUpload(e)
            );
        }

        // Edit paper form
        const editForm = document.getElementById("editPaperForm");
        if (editForm) {
            editForm.addEventListener("submit", (e) => this.handlePaperEdit(e));
        }

        // Citation confirmation
        const confirmCitationBtn =
            document.getElementById("confirmCitationBtn");
        if (confirmCitationBtn) {
            confirmCitationBtn.addEventListener("click", () =>
                this.handleCitationConfirm()
            );
        }
    }

    setupSearchAndFilters() {
        // Close filter dropdown when clicking outside
        document.addEventListener("click", (e) => {
            if (!e.target.closest("[x-data]")) {
                this.closeFilterDropdown();
            }
        });
    }

    async loadPapers(page = 1) {
        if (!document.getElementById("papersContainer")) return;
        this.viewMode = "explore";
        this.currentPage = page;
        
        try {
            this.showLoading(true);
            const roleName = this.currentRole.replace(/^\d+/, "");
            console.log("Loading papers for role:", roleName, "page:", page);
            
            const endpoint = `/dashboard/papers?role=${encodeURIComponent(roleName)}&page=${page}`;

            const response = await fetch(endpoint, {
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "",
                    Accept: "application/json",
                },
            });

            const data = await response.json();
            console.log("Papers loaded:", data);

            if (data.success) {
                this.papers = data.papers || [];
                this.filteredPapers = [...this.papers];
                this.displayPapers();
                
                // Render pagination
                if (data.pagination) {
                    this.renderPagination(data.pagination);
                }

                // Update stats
                if (data.stats) {
                    document.getElementById("totalPapers").textContent = data.stats.totalPapers;
                    document.getElementById("totalCitations").textContent = data.stats.totalCitations;
                }
            } else {
                this.showToast(data.message || "Failed to load papers", true);
            }
        } catch (error) {
            console.error("Error loading papers:", error);
            this.showToast("Error loading papers", true);
        } finally {
            this.showLoading(false);
        }
    }

    displayPapers() {
        const container = document.getElementById("papersContainer");
        const emptyState = document.getElementById("papersEmpty");
        const countElement = document.getElementById("papersCount");
        const emptyTitle = document.getElementById("emptyTitle");
        const emptyMessage = document.getElementById("emptyMessage");

        if (!container) return;

        // Update count
        if (countElement) {
            countElement.textContent = `${this.filteredPapers.length} paper${
                this.filteredPapers.length !== 1 ? "s" : ""
            }`;
        }

        if (this.filteredPapers.length === 0) {
            container.innerHTML = "";
            if (emptyState) emptyState.classList.remove("hidden");

            if (emptyTitle && emptyMessage) {
                if (this.currentRole === "Funder") {
                    emptyTitle.textContent = "No papers published yet";
                    emptyMessage.textContent =
                        "Upload your first research paper to get started.";
                } else {
                    emptyTitle.textContent = "No papers available";
                    emptyMessage.textContent =
                        "No research papers have been published by funders yet.";
                }
            }
        } else {
            if (emptyState) emptyState.classList.add("hidden");
            container.innerHTML = this.filteredPapers
                .map((paper) => this.createPaperCard(paper))
                .join("");
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
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02),0_10px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_4px_8px_rgba(0,0,0,0.04),0_20px_40px_rgba(0,0,0,0.06)] transition-all duration-300 overflow-hidden flex flex-col h-full group relative">
                <!-- Subtle Accent -->
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-100 group-hover:bg-slate-900 transition-colors duration-300"></div>
                
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
                        <div class="w-11 h-11 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-900 font-bold text-sm border border-slate-100 flex-shrink-0">
                            ${authorName.split(' ').map(n => n[0]).join('')}
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
                        class="flex-1 h-10 flex items-center justify-center gap-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm active:scale-95">
                        <i class="fas fa-eye text-slate-400"></i>
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

    viewPaperDetails(paperId, mode = "view", action = "cite") {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add("-translate-x-full");
            sidebarOverlay.classList.add("hidden");
        }
        const paper = this.papers.find((p) => p.id === paperId);
        if (!paper) return;
        document.getElementById("paperModalTitle").textContent = paper.title;
        const modalContent = document.getElementById("paperModalContent");
        modalContent.innerHTML = this.createPaperDetailsContent(paper);
        // Footer Buttons
        const actionBtn = document.getElementById("paperModalActionBtn");
        if (actionBtn) {
            if (mode === "view") {
                actionBtn.textContent = "Okay";
                actionBtn.classList.remove("hidden");
                actionBtn.onclick = () => this.closePaperDetailsModal();
            } else if (mode === "cite") {
                actionBtn.textContent = "Proceed to Cite";
                actionBtn.classList.remove("hidden");
                actionBtn.onclick = () =>
                    this.confirmCitation(paper.id, action);
            }
        }
        document.getElementById("paperDetailsModal").classList.remove("hidden");
    }

    createPaperDetailsContent(paper) {
        const citationFields = [
            { key: "mla", label: "MLA Citation" },
            { key: "apa", label: "APA Citation" },
            { key: "chicago", label: "Chicago Citation" },
            { key: "harvard", label: "Harvard Citation" },
            { key: "vancouver", label: "Vancouver Citation" },
            { key: "doi", label: "DOI" },
        ];

        const citationHTML = citationFields
            .filter((field) => paper[field.key])
            .map(
                (field) => `
                <div class="relative group">
                    <h4 class="font-semibold text-gray-800 mb-2">${
                        field.label
                    }</h4>
                    <div class="relative">
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg" id="${
                            field.key
                        }Text">${paper[field.key]}</p>
                        <button onclick="dashboard.handleCopy('${
                            field.key
                        }Text')" 
                            class="absolute top-2 right-2 p-1 bg-white hover:bg-gray-100 rounded transition-colors">
                            <i class="fas fa-copy text-gray-500 hover:text-gray-700"></i>
                        </button>
                    </div>
                </div>
            `
            )
            .join("");

        return `
            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Author Information</h4>
                    <p class="text-gray-600">${
                        paper.author_name || "Unknown Author"
                    }</p>
                    <p class="text-sm text-gray-500">Author ID: ${
                        paper.user_id
                    }</p>
                    <p class="text-sm text-gray-500">Paper ID: ${paper.id}</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Publication Date</h4>
                    <p class="text-gray-600">${new Date(
                        paper.created_at
                    ).toLocaleDateString()}</p>
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
            this.showToast("Copied to clipboard!");
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
        const feedback = document.getElementById("copyFeedback");
        if (feedback) {
            feedback.classList.remove("opacity-0");
            setTimeout(() => {
                feedback.classList.add("opacity-0");
            }, 1500);
        }
    }

    toggleCite(paperId, isCited) {
        // Find the paper to check if it has pending claim
        const paper = this.papers.find(p => p.id === paperId);
        
        if (isCited && paper && paper.has_pending_claim) {
            this.showToast('Cannot uncite - you have a pending or approved claim for this paper', true);
            return;
        }
        
        if (isCited) {
            this.confirmCitation(paperId, "uncite");
        } else {
            this.viewPaperDetails(paperId, "cite", "cite");
        }
    }

    confirmCitation(paperId, action = "cite") {
        this.citationPaperId = paperId;
        this.citationAction = action.toLowerCase();

        const actionCapitalized =
            this.citationAction.charAt(0).toUpperCase() +
            this.citationAction.slice(1);
        const modal = document.getElementById("confirmCitationModal");
        const title = document.getElementById("modalTitle");
        const message = document.getElementById("modalMessage");
        const confirmBtn = document.getElementById("confirmCitationBtn");

        if (title) title.textContent = `Confirm ${actionCapitalized}`;
        if (message)
            message.textContent = `Are you sure you want to ${actionCapitalized} this paper?`;
        if (confirmBtn) {
            const icon = this.citationAction === "uncite" ? "times" : "check";
            confirmBtn.innerHTML = `<i class="fas fa-${icon} mr-2"></i>Yes, ${actionCapitalized}`;

            // Remove all existing color classes
            confirmBtn.classList.remove(
                "bg-green-600",
                "hover:bg-green-700",
                "bg-emerald-500",
                "hover:bg-emerald-600",
                "bg-red-600",
                "hover:bg-red-700"
            );
        }

        if (modal) modal.classList.remove("hidden");
    }

    async handleCitationConfirm() {
        if (!this.citationPaperId || !this.citationAction) return;

        try {
            const response = await fetch(
                `/${this.citationAction}-paper/${this.citationPaperId}`,
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                    },
                }
            );

            const data = await response.json();

            if (data.success) {
                this.showToast(
                    `Paper ${
                        this.citationAction === "cite" ? "Cited" : "Uncited"
                    } successfully!`
                );
                this.loadPapers();
            } else {
                this.showToast(
                    data.message || `Failed to ${this.citationAction}.`,
                    true
                );
            }

            this.closePaperDetailsModal();
            this.closeConfirmModal();
        } catch (err) {
            console.error(err);
            this.closePaperDetailsModal();
            this.closeConfirmModal();
            this.showToast("Something went wrong.", true);
        }
    }

    performSearch() {
        const query = document.getElementById("searchInput")?.value.trim();
        if (!query) return;

        const params = new URLSearchParams();
        params.append("query", query);
        if (this.selectedFilter) {
            params.append("filter_type", this.selectedFilter);
        }
        params.append("role", this.currentRole);

        fetch(`/papers/search?${params.toString()}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                this.filteredPapers = data;
                this.displayPapers();
            })
            .catch((err) => {
                console.error("Search failed:", err);
                this.showToast("Search failed", true);
            });
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

    showLoading(show) {
        const loading = document.getElementById("papersLoading");
        const container = document.getElementById("papersContainer");
        const emptyState = document.getElementById("papersEmpty");

        if (show) {
            if (loading) loading.classList.remove("hidden");
            if (container) container.classList.add("hidden");
            if (emptyState) emptyState.classList.add("hidden");
        } else {
            if (loading) loading.classList.add("hidden");
            if (container) container.classList.remove("hidden");
        }
    }

    showToast(message, isError = false) {
        if (typeof window.showToast === "function") {
            window.showToast(message, isError);
        } else {
            console.log("Global showToast not available:", message);
        }
    }

    // Modal functions
    openProfileModal() {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add("-translate-x-full");
            sidebarOverlay.classList.add("hidden");
        }
        document
            .getElementById("updateProfileModal")
            .classList.remove("hidden");
        // Get user data from data attributes
        const firstName =
            document
                .querySelector("[data-user-first-name]")
                ?.getAttribute("data-user-first-name") || "";
        const lastName =
            document
                .querySelector("[data-user-last-name]")
                ?.getAttribute("data-user-last-name") || "";
        const email =
            document
                .querySelector("[data-user-email]")
                ?.getAttribute("data-user-email") || "";
        document.getElementById("first_name").value = firstName;
        document.getElementById("last_name").value = lastName;
        document.getElementById("email").value = email;
    }

    closeProfileModal() {
        document.getElementById("updateProfileModal").classList.add("hidden");
    }

    openPaperModal() {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add("-translate-x-full");
            sidebarOverlay.classList.add("hidden");
        }
        console.log("Opening upload paper modal");
        const modal = document.getElementById("uploadPaperModal");
        if (modal) {
            modal.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
            console.log("Upload paper modal opened successfully");
        } else {
            console.error("Upload paper modal not found");
        }
    }

    closePaperModal() {
        console.log("Closing upload paper modal");
        const modal = document.getElementById("uploadPaperModal");
        if (modal) {
            modal.classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
            console.log("Upload paper modal closed successfully");
        } else {
            console.error("Upload paper modal not found");
        }
    }

    closePaperDetailsModal() {
        document.getElementById("paperDetailsModal").classList.add("hidden");
    }

    closeConfirmModal() {
        document.getElementById("confirmCitationModal").classList.add("hidden");
        this.citationPaperId = null;
        this.citationAction = null;
    }

    async loadMyCitations(page = 1) {
        if (!document.getElementById("papersContainer")) return;
        this.viewMode = "citations";
        this.currentPage = page;
        this.showLoading(true);

        try {
            const res = await fetch(`/my-citations?page=${page}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            });

            const data = await res.json();
            
            if (data.success) {
                this.papers = data.papers || [];
                this.filteredPapers = [...this.papers];
                this.showLoading(false);
                this.displayPapers(); // reuse existing function to show cards
                
                // Render pagination
                if (data.pagination) {
                    this.renderPagination(data.pagination);
                }

                // Update stats
                if (data.stats) {
                    document.getElementById("totalPapers").textContent = data.stats.totalPapers;
                    document.getElementById("totalCitations").textContent = data.stats.totalCitations;
                }
            } else {
                this.showToast(data.message || "Failed to load citations", true);
                this.showLoading(false);
            }
        } catch (err) {
            console.error("Failed to load citations:", err);
            this.showToast("Failed to load citations", true);
            this.showLoading(false);
        }
    }

    // Form handlers
    async handleProfileUpdate(e) {
        e.preventDefault();
        const form = e.target;

        try {
            const response = await fetch("/profile-update", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: JSON.stringify({
                    first_name: form.first_name.value,
                    last_name: form.last_name.value,
                    email: form.email.value,
                }),
            });

            const data = await response.json();

            if (data.success) {
                document.querySelectorAll(
                    "p.font-medium"
                )[0].textContent = `${data.user.first_name} ${data.user.last_name}`;
                document.querySelectorAll(
                    "p.text-sm.opacity-80"
                )[0].textContent = data.user.email;
                this.closeProfileModal();
                this.showToast("Profile updated successfully!");
            } else {
                this.showToast("Update failed.", true);
            }
        } catch (error) {
            console.error(error);
            this.showToast("An error occurred.", true);
        }
    }

    async handlePaperUpload(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch("/papers/upload", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                this.closePaperModal();
                this.showToast("Paper uploaded successfully!");
                this.loadPapers();
                e.target.reset();
            } else {
                this.showToast(data.message || "Failed to upload paper.", true);
            }
        } catch (err) {
            console.error(err);
            this.showToast("Something went wrong.", true);
        }
    }

    async handlePaperEdit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const paperId = form.paper_id.value;

        formData.append("_method", "PUT");

        try {
            const response = await fetch(`/papers/${paperId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    Accept: "application/json",
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                this.showToast("Paper updated successfully");
                this.closeEditModal();
                this.loadPapers();
            } else {
                this.showToast(data.message || "Failed to update paper", true);
            }
        } catch (err) {
            console.error("Update error:", err);
            this.showToast("Error updating paper", true);
        }
    }

    editPaper(paperId) {
        // Close sidebar and overlay if open (for mobile)
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add("-translate-x-full");
            sidebarOverlay.classList.add("hidden");
        }
        const paper = this.papers.find((p) => p.id === paperId);
        if (!paper) {
            this.showToast("Paper not found", true);
            return;
        }
        const form = document.getElementById("editPaperForm");
        if (form) {
            form.paper_id.value = paper.id;
            form.title.value = paper.title || "";
            form.mla.value = paper.mla || "";
            form.apa.value = paper.apa || "";
            form.chicago.value = paper.chicago || "";
            form.harvard.value = paper.harvard || "";
            form.vancouver.value = paper.vancouver || "";
            form.doi.value = paper.doi || "";
        }
        document.getElementById("editPaperModal").classList.remove("hidden");
    }

    closeEditModal() {
        const form = document.getElementById("editPaperForm");
        if (form) form.reset();
        document.getElementById("editPaperModal").classList.add("hidden");
    }

    deletePaper(paperId) {
        this.paperIdToDelete = paperId;
        document
            .getElementById("deleteConfirmationModal")
            .classList.remove("hidden");
    }

    closeDeletePaperModal() {
        this.paperIdToDelete = null;
        document
            .getElementById("deleteConfirmationModal")
            .classList.add("hidden");
    }

    async confirmDeletePaper() {
        if (!this.paperIdToDelete) return;

        try {
            const response = await fetch(`/papers/${this.paperIdToDelete}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    Accept: "application/json",
                },
            });

            const data = await response.json();
            this.closeDeletePaperModal();

            if (data.success) {
                this.showToast(data.message || "Paper deleted");
                this.papers = this.papers.filter(
                    (p) => p.id !== this.paperIdToDelete
                );
                this.filteredPapers = [...this.papers];
                this.loadPapers();
            } else {
                this.showToast(data.message || "Delete failed", true);
            }
        } catch (error) {
            console.error("Delete error:", error);
            this.closeDeletePaperModal();
            this.showToast("Error deleting paper", true);
        }
    }

    refreshPapers() {
        this.loadPapers();
    }

    openDeleteModal() {
        // Just open the modal directly, do NOT call window.dashboard.openDeleteModal()
        const modal =
            document.getElementById("deleteConfirmModal") ||
            document.getElementById("deleteConfirmationModal");
        if (modal) modal.classList.remove("hidden");
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize dashboard logic on dashboard page
    if (window.location.pathname === '/dashboard' || window.location.pathname === '/dashboard-new') {
        window.dashboard = new Dashboard();
    }

    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', () => {
            // List all modal IDs
            const modalIds = [
                'updateProfileModal',
                'uploadPaperModal',
                'paperDetailsModal',
                'editPaperModal',
                'deleteConfirmationModal',
                'deleteConfirmModal',
                'confirmCitationModal'
            ];
            modalIds.forEach(id => {
                const modal = document.getElementById(id);
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
            modalOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    }

    // Escape key closes any open modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modalIds = [
                'updateProfileModal',
                'uploadPaperModal',
                'paperDetailsModal',
                'editPaperModal',
                'deleteConfirmationModal',
                'deleteConfirmModal',
                'confirmCitationModal'
            ];
            let anyOpen = false;
            modalIds.forEach(id => {
                const modal = document.getElementById(id);
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    anyOpen = true;
                }
            });
            if (anyOpen && modalOverlay) {
                modalOverlay.classList.add('hidden');
            }
            document.body.classList.remove('overflow-hidden');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Auto-open upload modal if ?upload=1 is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('upload') === '1' && typeof openPaperModal === 'function') {
        openPaperModal();
    }
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

window.openDeleteModal = function() {
    if (window.dashboard && typeof window.dashboard.openDeleteModal === 'function') {
        window.dashboard.openDeleteModal();
    } else {
        const modal = document.getElementById('deleteConfirmModal') || document.getElementById('deleteConfirmationModal');
        if (modal) modal.classList.remove('hidden');
    }
};

window.closeDeleteModal = function() {
    if (window.dashboard && typeof window.dashboard.closeDeleteModal === 'function') {
        window.dashboard.closeDeleteModal();
    } else {
        const modal = document.getElementById('deleteConfirmModal') || document.getElementById('deleteConfirmationModal');
        if (modal) modal.classList.add('hidden');
    }
};

window.confirmDelete = function() {
    window.dashboard?.confirmDelete();
};

// For deleting a paper
window.confirmDeletePaper = function() {
    window.dashboard?.confirmDeletePaper();
};

// For deleting the user account
window.confirmDeleteAccount = async function() {
    // Show a loading state or confirmation if needed
    try {
        const response = await fetch('/profile-delete', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            window.dashboard?.showToast(data.message);
            setTimeout(() => {
                window.location.href = '/login'; // or your login route
            }, 2000);
        } else {
            window.dashboard?.showToast(data.message || 'Failed to delete account.', true);
        }
    } catch (error) {
        console.error('Delete error:', error);
        window.dashboard?.showToast('Something went wrong.', true);
    }
};

// Legacy alias for account deletion (if used in old HTML)
window.confirmDelete = window.confirmDeleteAccount; 