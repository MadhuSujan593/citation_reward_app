<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-200 to-white bg-no-repeat bg-cover">
  <div class="bg-white/40 backdrop-blur-md rounded-3xl shadow-lg px-8 py-10 w-full max-w-md border border-white/20">
    <div class="flex justify-center mb-6">
      <div class="bg-white text-black rounded-xl p-4 shadow-lg shadow-color">
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="ARROW 48"><path d="M400.14.16H181.35a82 82 0 0 0-81.9 81.9v50.54a24 24 0 1 0 48 0V82.06a33.94 33.94 0 0 1 33.9-33.9h218.79A33.93 33.93 0 0 1 434 82.06v347.88a33.93 33.93 0 0 1-33.89 33.9H181.35a33.94 33.94 0 0 1-33.9-33.9v-58.47a24 24 0 0 0-48 0v58.47a82 82 0 0 0 81.9 81.9h218.79a82 82 0 0 0 81.86-81.9V82.06A82 82 0 0 0 400.14.16z" fill="#1f2937" opacity="1" data-original="#000000" class=""></path><path d="m364.64 238.53-85.4-85.35a24 24 0 0 0-33.61-.33c-9.7 9.33-9.47 25.13.05 34.65l44.47 44.5H54a24 24 0 0 0-24 24 24 24 0 0 0 24 24h236.16l-44.9 44.9a24 24 0 0 0 33.94 33.95l85.44-85.41a24.66 24.66 0 0 0 0-34.91z" fill="#1f2937" opacity="1" data-original="#000000" class=""></path></g></g></svg>      </div>
    </div>
    
    <h2 class="text-center text-xl font-semibold text-gray-800">Sign in to continue</h2>

    <!-- Email Input -->
    <div class="mt-6">
      <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <div class="flex items-center bg-white/70 border border-gray-300 rounded-lg px-3 gap-2 py-2">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M298.789 313.693c-12.738 8.492-27.534 12.981-42.789 12.981-15.254 0-30.05-4.489-42.788-12.981L3.409 173.82A76.269 76.269 0 0 1 0 171.403V400.6c0 26.278 21.325 47.133 47.133 47.133h417.733c26.278 0 47.133-21.325 47.133-47.133V171.402a75.21 75.21 0 0 1-3.416 2.422z" fill="#1f2937" opacity="1" data-original="#000000" class=""></path><path d="m20.05 148.858 209.803 139.874c7.942 5.295 17.044 7.942 26.146 7.942 9.103 0 18.206-2.648 26.148-7.942L491.95 148.858c12.555-8.365 20.05-22.365 20.05-37.475 0-25.981-21.137-47.117-47.117-47.117H47.117C21.137 64.267 0 85.403 0 111.408a44.912 44.912 0 0 0 20.05 37.45z" fill="#1f2937" opacity="1" data-original="#000000" class=""></path></g></svg>
        <input type="email" placeholder="Enter your email" class="bg-transparent w-full focus:outline-none" />
      </div>
    </div>

    <!-- Password Input -->
    <div class="mt-4"> 
      <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
      <div class="flex items-center bg-white/70 border border-gray-300 rounded-lg px-3 gap-2 py-2">
       <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><path d="M18.75 9H18V6c0-3.309-2.691-6-6-6S6 2.691 6 6v3h-.75A2.253 2.253 0 0 0 3 11.25v10.5C3 22.991 4.01 24 5.25 24h13.5c1.24 0 2.25-1.009 2.25-2.25v-10.5C21 10.009 19.99 9 18.75 9zM8 6c0-2.206 1.794-4 4-4s4 1.794 4 4v3H8zm5 10.722V19a1 1 0 1 1-2 0v-2.278c-.595-.347-1-.985-1-1.722 0-1.103.897-2 2-2s2 .897 2 2c0 .737-.405 1.375-1 1.722z" fill="#1f2937" opacity="1" data-original="#000000"></path></g></svg>
        <input type="password" placeholder="Enter your password" class="bg-transparent w-full focus:outline-none" />
        <button class="ml-2 text-gray-500 hover:text-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </button>
      </div>
      <div class="text-right mt-1">
        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
      </div>
    </div>

    <!-- Get Started Button -->
    <button class="mt-6 bg-black text-white text-sm font-medium rounded-lg w-full py-3 hover:bg-gray-800 transition">
      Get Started
    </button>

     <!-- Social Login Button -->
<div class="mt-4">
  <a href="{{ url('/auth/google') }}" class="flex items-center justify-center gap-2 w-full border border-gray-300 bg-white text-sm text-gray-700 rounded-lg py-3 hover:bg-gray-100 transition">
    <img src="{{ asset('images/google-icon.png') }}" alt="Google" class="w-5 h-5">
    <span>Continue with Google</span>
  </a>
</div>


    <!-- Register -->
    <div class="mt-6 text-center text-sm text-gray-700">
      Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
    </div>
  </div>

  <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.innerHTML = <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.054 10.054 0 011.442-2.568m3.1-2.53A9.959 9.959 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.972 9.972 0 01-4.043 5.317M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>;
            } else {
                password.type = 'password';
                eyeIcon.innerHTML = <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>;
            }
        }
    </script>
</body>
</html>
