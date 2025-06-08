<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Forgot Password - Ebolt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-200 to-white bg-no-repeat bg-cover px-5">
    <div class="bg-white/40 backdrop-blur-md rounded-3xl shadow-lg px-8 py-10 w-full max-w-md border border-white/20">
        <div class="flex justify-center mb-6">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h2 class="text-center text-2xl font-semibold text-gray-800">Forgot your password?</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Enter your email to receive a reset link.</p>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" novalidate>
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <p id="emailError" class="text-red-500 text-sm mt-1 hidden">Please enter a valid email address</p>

                {{-- Backend validation error --}}
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-900 transition">
                Send Reset Link
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-700">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Back to login</a>
        </div>
    </div>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailValue = emailInput.value.trim();
            const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

            emailError.classList.add('hidden');

            if (!emailPattern.test(emailValue)) {
                e.preventDefault();
                emailError.classList.remove('hidden');
            }
        });

        // Optional: hide error as user types
        document.getElementById('email').addEventListener('input', () => {
            document.getElementById('emailError').classList.add('hidden');
        });
    </script>
</body>
</html>
