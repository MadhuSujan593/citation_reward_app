<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password - Citation Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
    <div class="bg-white rounded-3xl p-8 sm:p-10 w-full max-w-md border border-slate-200">
        
        <div class="flex flex-col items-center mb-8">
            <div class="flex flex-col items-center justify-center mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #2563eb;">
                    <i class="fas fa-layer-group text-2xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Citation Hub</h1>
            </div>
            <h2 class="text-xl font-semibold text-slate-800">Forgot your password?</h2>
            <p class="text-sm text-slate-500 mt-1 text-center">No worries, we'll send you reset instructions.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i> <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" novalidate class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-400"></i>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                    />
                </div>
                <p id="emailError" class="text-red-500 text-xs mt-1 hidden font-medium">Please enter a valid email address.</p>

                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full py-3 mt-4 transition-all active:scale-[0.98]">
                Send Reset Link
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-slate-800 transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to sign in
            </a>
        </p>
    </div>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailValue = emailInput.value.trim();
            const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

            emailError.classList.add('hidden');
            emailInput.classList.remove('border-red-500');

            if (!emailPattern.test(emailValue)) {
                e.preventDefault();
                emailError.classList.remove('hidden');
                emailInput.classList.add('border-red-500');
            }
        });

        document.getElementById('email').addEventListener('input', (e) => {
            document.getElementById('emailError').classList.add('hidden');
            e.target.classList.remove('border-red-500');
        });
    </script>
</body>
</html>
