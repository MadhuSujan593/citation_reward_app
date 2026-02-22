<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - Citation Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
    <div class="bg-white rounded-3xl p-8 sm:p-10 w-full max-w-md border border-slate-200 shadow-xl shadow-slate-200/50">
        
        <div class="flex flex-col items-center mb-8">
            <div class="flex flex-col items-center justify-center mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #2563eb;">
                    <i class="fas fa-layer-group text-2xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Citation Hub</h1>
            </div>
            <h2 class="text-xl font-semibold text-slate-800">Reset your password</h2>
            <p class="text-sm text-slate-500 mt-1 text-center">Enter a new secure password for your account.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i> <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="••••••••"
                        required
                        class="w-full pl-10 pr-12 py-3 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-slate-800"
                    />
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i id="eyeIcon1" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-shield-check text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="••••••••"
                        required
                        class="w-full pl-10 pr-12 py-3 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-slate-800"
                    />
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i id="eyeIcon2" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full py-3.5 mt-2 transition-all shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 active:scale-[0.98]">
                Reset Password
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-slate-800 transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to login
            </a>
        </p>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
