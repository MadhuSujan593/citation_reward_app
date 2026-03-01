<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Modern CSS Framework & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #818cf8;
            --bg-main: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: var(--bg-main);
        }

        /* Apply fully rounded design to all generic buttons and button-like links globally */
        button, 
        input[type="button"], 
        input[type="submit"], 
        input[type="reset"],
        .btn,
        a.btn {
            border-radius: 9999px !important;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .premium-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .card-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-item-active {
            background: linear-gradient(to right, rgba(99, 102, 241, 0.1), transparent);
            border-right: 3px solid var(--primary);
            color: var(--primary);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen font-sans antialiased text-slate-900 overflow-x-hidden relative" data-user-role="{{ trim(auth()->user()->role ?? 'Citer') }}">
    <!-- User Data Attributes -->
    <div 
        data-user-first-name="{{ auth()->user()->first_name }}"
        data-user-last-name="{{ auth()->user()->last_name }}"
        data-user-email="{{ auth()->user()->email }}"
        data-user-google-scholar="{{ auth()->user()->google_scholar_link }}"
        data-user-scopus-id="{{ auth()->user()->scopus_id_link }}"
        data-user-orcid="{{ auth()->user()->orcid_link }}"
        data-user-profile-picture="{{ auth()->user()->profile_picture }}"
        class="hidden"
    ></div>
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <!-- Mobile Header -->
    <x-dashboard.mobile-header />
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-dashboard.sidebar :userRole="$userRole" />
        
        <!-- Main Content -->
        <div id="mainContent" class="flex-1 flex flex-col min-w-0 w-full">
            <!-- Top Navigation -->
            @if (!Request::is('wallet*') && !Request::is('claim-requests*') && !Request::is('admin/claim-requests*'))
                <x-dashboard.top-nav />
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Modals -->
    @stack('modals')
    
   
    <!-- Premium Global Toast (Tailwind 2 & 3 Compatible) -->
    <div id="toast" class="fixed bottom-6 right-6 z-[100] transform transition-all duration-300 ease-out translate-y-10 scale-95 opacity-0 pointer-events-none">
        <div class="flex items-center gap-3 bg-gray-900 text-white px-4 py-3 rounded-2xl shadow-2xl min-w-fit max-w-xs border border-white/10">
            <div id="toastIcon" class="w-8 h-8 rounded-xl flex items-center justify-center text-base shadow-sm"></div>
            <div>
                <p id="toastTitle" class="text-[10px] font-bold uppercase tracking-widest mb-0.5">Notification</p>
                <p id="toastMessage" class="text-sm font-bold"></p>
            </div>
        </div>
    </div>

    <script>
        window.showToast = function(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            if (!toast || !toastMessage) return;

            // Reset and set styles based on type
            toastIcon.className = 'w-8 h-8 rounded-xl flex items-center justify-center text-base shadow-sm';
            
            if (type === 'success' || type === false) {
                toastIcon.classList.add('bg-green-500', 'text-white');
                toastIcon.innerHTML = '<i class="fas fa-check"></i>';
                toastTitle.textContent = 'Success';
                toastTitle.className = 'text-[10px] font-bold text-green-500 uppercase tracking-widest mb-0.5';
            } else if (type === 'error' || type === true || type === 'isError') {
                toastIcon.classList.add('bg-red-500', 'text-white');
                toastIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                toastTitle.textContent = 'Error';
                toastTitle.className = 'text-[10px] font-bold text-red-500 uppercase tracking-widest mb-0.5';
            } else {
                toastIcon.classList.add('bg-blue-500', 'text-white');
                toastIcon.innerHTML = '<i class="fas fa-info-circle"></i>';
                toastTitle.textContent = 'Info';
                toastTitle.className = 'text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-0.5';
            }
            
            toastMessage.textContent = message;
            
            // Show
            toast.classList.remove('translate-y-10', 'scale-95', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'scale-100', 'opacity-100');
            
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'scale-95', 'opacity-0', 'pointer-events-none');
                toast.classList.remove('translate-y-0', 'scale-100', 'opacity-100');
            }, 4000);
        }
    </script>

    
    <!-- Scripts -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/js/dashboard.js'])
    @stack('scripts')
    
    <script>
    // Global function to handle My Citations click from sidebar
    function handleMyCitationsClick() {
        // Check if we're on the dashboard page
        const isDashboardPage = window.location.pathname === '{{ route("citer.dashboard", [], false) }}';
        
        if (isDashboardPage && window.dashboard && typeof window.dashboard.loadMyCitations === 'function') {
            // We're on dashboard, just load citations
            window.dashboard.loadMyCitations();
        } else {
            // We're on another page, redirect to dashboard with citations flag
            window.location.href = '{{ route("citer.dashboard") }}?citations=1';
        }
    }
    
    // Global function to handle Explore Papers click from sidebar
    function handleExplorePapersClick() {
        // Check if we're on the dashboard page
        const isDashboardPage = window.location.pathname === '{{ route("citer.dashboard", [], false) }}';
        
        if (isDashboardPage && window.dashboard && typeof window.dashboard.loadPapers === 'function') {
            // We're on dashboard, just load all papers normally
            window.dashboard.loadPapers();
        } else {
            // We're on another page, redirect to dashboard with explore flag
            window.location.href = '{{ route("citer.dashboard") }}?explore=1';
        }
    }

    // Initialize Mobile Menu Globally
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        if (hamburgerBtn && sidebar && overlay) {
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

            hamburgerBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openSidebar();
            });
            overlay.addEventListener('click', (e) => {
                e.stopPropagation();
                closeSidebar();
            });
            
            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    closeSidebar();
                });
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
    });
    </script>
</body>
</html> 