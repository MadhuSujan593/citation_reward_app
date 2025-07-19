<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Modern CSS Framework -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    
    <!-- Custom Styles -->
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse-glow {
            animation: pulseGlow 2s infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 5px rgba(99, 102, 241, 0.5); }
            50% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.8); }
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans">
    <!-- User Data Attributes -->
    <div 
        data-user-first-name="{{ auth()->user()->first_name }}"
        data-user-last-name="{{ auth()->user()->last_name }}"
        data-user-email="{{ auth()->user()->email }}"
        class="hidden"
    ></div>
    <!-- Mobile Header -->
    <x-dashboard.mobile-header />
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-dashboard.sidebar :userRole="$userRole" />
        
        <!-- Main Content -->
        <div id="mainContent" class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            @if (!Request::is('wallet*'))
                <x-dashboard.top-nav />
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Modals -->
    @stack('modals')
    
   
    <!-- Toast at bottom right -->
    <div id="toast"
        class="fixed bottom-6 right-4 z-[9999] isolation-isolate text-white px-4 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transform translate-x-full transition-all duration-300 ease-in-out">
        <span id="toastMessage"></span>
    </div>
    <!-- Scripts -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    @stack('scripts')
</body>
</html> 