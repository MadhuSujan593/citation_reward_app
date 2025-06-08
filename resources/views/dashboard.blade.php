<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">
                Welcome, {{ Auth::user()->first_name }}
            </h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                    Logout
                </button>
            </form>
        </div>

        <p class="text-gray-600">You're logged in to your dashboard.</p>
    </div>

</body>
</html>
