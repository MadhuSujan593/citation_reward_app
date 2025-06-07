<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register - Ebolt</title>
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

        <h2 class="text-center text-2xl font-semibold text-gray-800">Create your account</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Start your journey with us today</p>

        <form id="registerForm" method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input 
                    type="text" 
                    name="first_name" 
                    id="first_name" 
                    required
                    placeholder="Enter your first name"
                    pattern="^[A-Za-z\s'-]+$"
                    title="First name should contain only letters, spaces, apostrophes, or hyphens."
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <p class="text-red-600 text-sm mt-1 hidden" id="firstNameError">Please enter a valid first name.</p>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input 
                    type="text" 
                    name="last_name" 
                    id="last_name" 
                    required
                    placeholder="Enter your last name"
                    pattern="^[A-Za-z\s'-]+$"
                    title="Last name should contain only letters, spaces, apostrophes, or hyphens."
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <p class="text-red-600 text-sm mt-1 hidden" id="lastNameError">Please enter a valid last name.</p>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required
                    placeholder="example@mail.com"
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <p class="text-red-600 text-sm mt-1 hidden" id="emailError">Please enter a valid email address.</p>
            </div>

            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    placeholder="Enter your password"
                    minlength="8"
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                    autocomplete="new-password"
                />
                <p class="text-red-600 text-sm mt-1 hidden" id="passwordError">Password must be at least 8 characters.</p>
                <div
                    class="absolute inset-y-1/3 right-0 flex items-center pr-3 cursor-pointer select-none h-12"
                    onclick="togglePassword()"
                >
                    <svg
                        id="eyeIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>
                </div>
            </div>

            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-900 transition">Create Account</button>
        </form>
        
        <div class="text-center mt-6 text-sm text-gray-600">
            Already have an account? <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Sign in</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7
                    a10.054 10.054 0 011.442-2.568m3.1-2.53A9.959 9.959 0 0112 5c4.477 0 8.268 2.943
                    9.542 7a9.972 9.972 0 01-4.043 5.317M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
            } else {
                password.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                    9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            let valid = true;

            const inputs = [
                { id: 'first_name', errorId: 'firstNameError' },
                { id: 'last_name', errorId: 'lastNameError' },
                { id: 'email', errorId: 'emailError' },
                { id: 'password', errorId: 'passwordError' }
            ];

            inputs.forEach(({ id, errorId }) => {
                const input = document.getElementById(id);
                const error = document.getElementById(errorId);
                if (!input.checkValidity()) {
                    error.classList.remove('hidden');
                    valid = false;
                } else {
                    error.classList.add('hidden');
                }
            });

            if (!valid) event.preventDefault();
        });
         // Real-time validation function
        function validateField(input, errorElement) {
            if (input.checkValidity() && input.value.trim() !== '') {
                errorElement.classList.add('hidden');
                input.style.borderColor = '#d1d5db';
            } else if (input.value.trim() !== '') {
                errorElement.classList.remove('hidden');
                input.style.borderColor = '#ef4444';
            }
        }

        // Add event listeners for real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = [
                { id: 'first_name', errorId: 'firstNameError' },
                { id: 'last_name', errorId: 'lastNameError' },
                { id: 'email', errorId: 'emailError' },
                { id: 'password', errorId: 'passwordError' }
            ];

            inputs.forEach(({ id, errorId }) => {
                const input = document.getElementById(id);
                const errorElement = document.getElementById(errorId);

                // Validate on input (as user types)
                input.addEventListener('input', function() {
                    validateField(input, errorElement);
                });

                // Validate on blur (when user leaves the field)
                input.addEventListener('blur', function() {
                    if (input.value.trim() !== '') {
                        validateField(input, errorElement);
                    }
                });

                // Clear error when user focuses on the field
                input.addEventListener('focus', function() {
                    if (input.checkValidity()) {
                        errorElement.classList.add('hidden');
                        input.style.borderColor = '#3b82f6';
                    }
                });
            });
        });

        // Form submission validation
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            let valid = true;

            const inputs = [
                { id: 'first_name', errorId: 'firstNameError' },
                { id: 'last_name', errorId: 'lastNameError' },
                { id: 'email', errorId: 'emailError' },
                { id: 'password', errorId: 'passwordError' }
            ];

            inputs.forEach(({ id, errorId }) => {
                const input = document.getElementById(id);
                const error = document.getElementById(errorId);
                
                if (!input.checkValidity() || input.value.trim() === '') {
                    error.classList.remove('hidden');
                    input.style.borderColor = '#ef4444';
                    valid = false;
                } else {
                    error.classList.add('hidden');
                    input.style.borderColor = '#d1d5db';
                }
            });

            if (!valid) {
                event.preventDefault();
            } else {
                // Simulate form submission
                alert('Form would be submitted! (This is just a demo)');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
