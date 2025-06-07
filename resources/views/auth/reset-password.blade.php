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
        <div class="flex justify-center mb-6">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h2 class="text-center text-2xl font-semibold text-gray-800">Reset your password</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Enter a new password to access your account.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    required
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-900 transition">
                Reset Password
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-700">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Back to login</a>
        </div>
    </div>
</body>
</html>
