<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Reset Password - Ebolt</title>
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

        {{-- Success Checkmark --}}
        <div class="flex justify-center mb-6">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h2 class="text-center text-2xl font-semibold text-gray-800">Reset your password</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Enter a new password to access your account.</p>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="mb-6 text-sm text-green-700 bg-green-100 border border-green-300 px-4 py-3 rounded-lg text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Global validation errors --}}
        @if ($errors->any())
            <div class="mb-6 text-sm text-red-700 bg-red-100 border border-red-300 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            @php
                $passwordError = $errors->has('password') ? 'border-red-500' : 'border-gray-300';
                $confirmError = $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300';
            @endphp

            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <div class="flex items-center rounded-lg border {{ $passwordError }} shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500 mt-1">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        class="bg-transparent w-full px-3 py-2 rounded-l-lg focus:outline-none"
                        placeholder="Enter new password"
                    />
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="pr-3 text-gray-500 hover:text-gray-700">
                        <svg id="eyeIcon1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="flex items-center rounded-lg border {{ $confirmError }} shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500 mt-1">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        class="bg-transparent w-full px-3 py-2 rounded-l-lg focus:outline-none"
                        placeholder="Confirm new password"
                    />
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="pr-3 text-gray-500 hover:text-gray-700">
                        <svg id="eyeIcon2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-900 transition">
                Reset Password
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-700">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Back to login</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7
                        a10.054 10.054 0 011.442-2.568m3.1-2.53A9.959 9.959 0 0112 5
                        c4.477 0 8.268 2.943 9.542 7a9.972 9.972 0 01-4.043 5.317M15 12
                        a3 3 0 11-6 0 3 3 0 016 0z"/>`;
            } else {
                input.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542
                        7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>
</html>
