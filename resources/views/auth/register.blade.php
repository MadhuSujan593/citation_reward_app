<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - ResearchHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl border border-slate-200">
        
        <div class="flex flex-col items-center mb-6">
            <div class="flex flex-col items-center justify-center mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-2" style="background-color: #2563eb;">
                    <i class="fas fa-layer-group text-xl text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">ResearchHub</h1>
            </div>
            <h2 class="text-lg font-semibold text-slate-800">Create an account</h2>
            <p class="text-xs text-slate-500 mt-1">Start your journey with us today</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form id="registerForm" method="POST" action="{{ route('register.post') }}" novalidate class="space-y-3">
            @csrf
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="first_name" class="block text-xs font-semibold text-slate-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="first_name" 
                        id="first_name" 
                        required
                        placeholder="John"
                        pattern="^[A-Za-z\s'-]+$"
                        class="w-full px-4 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                    />
                    <p class="text-red-500 text-xs mt-1 hidden font-medium" id="firstNameError">Invalid first name</p>
                </div>

                <div>
                    <label for="last_name" class="block text-xs font-semibold text-slate-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="last_name" 
                        id="last_name" 
                        required
                        placeholder="Doe"
                        pattern="^[A-Za-z\s'-]+$"
                        class="w-full px-4 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                    />
                    <p class="text-red-500 text-xs mt-1 hidden font-medium" id="lastNameError">Invalid last name</p>
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-400"></i>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required
                        placeholder="john@example.com"
                        class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                    />
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
                <p class="text-red-500 text-xs mt-1 hidden font-medium" id="emailError">Please enter a valid email</p>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-400"></i>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                        class="w-full pl-10 pr-10 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                    />
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <p class="text-red-500 text-xs mt-1 hidden font-medium" id="passwordError">Min 8 characters required</p>
            </div>

            <!-- Optional Links -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="google_scholar_link" class="block text-xs font-semibold text-slate-700 mb-1">Google Scholar Link (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-link text-slate-400"></i>
                        </div>
                        <input 
                            type="url" 
                            name="google_scholar_link" 
                            id="google_scholar_link" 
                            placeholder="https://scholar.google.com/..."
                            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                        />
                    </div>
                </div>

                <div>
                    <label for="scopus_id_link" class="block text-xs font-semibold text-slate-700 mb-1">Scopus ID Link (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-link text-slate-400"></i>
                        </div>
                        <input 
                            type="url" 
                            name="scopus_id_link" 
                            id="scopus_id_link" 
                            placeholder="https://www.scopus.com/..."
                            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                        />
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="orcid_link" class="block text-xs font-semibold text-slate-700 mb-1">ORCID Link (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fab fa-orcid text-slate-400"></i>
                        </div>
                        <input 
                            type="url" 
                            name="orcid_link" 
                            id="orcid_link" 
                            placeholder="https://orcid.org/..."
                            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"
                        />
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full py-2.5 mt-3 transition-all active:scale-[0.98]">
                Create Account
            </button>
        </form>
        
        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account? <a href="{{ url('/login') }}" class="font-bold text-blue-600 hover:text-blue-700 transition">Sign in</a>
        </p>
    </div>

    <script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    function validateField(input, errorElement) {
        if (input.checkValidity() && input.value.trim() !== '') {
            errorElement.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-slate-200');
        } else if (input.value.trim() !== '') {
            errorElement.classList.remove('hidden');
            input.classList.remove('border-slate-200');
            input.classList.add('border-red-500');
        }
    }

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

            input.addEventListener('input', function() {
                validateField(input, errorElement);
            });

            input.addEventListener('blur', function() {
                if (input.value.trim() !== '') {
                    validateField(input, errorElement);
                }
            });
        });

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            let valid = true;

            inputs.forEach(({ id, errorId }) => {
                const input = document.getElementById(id);
                const error = document.getElementById(errorId);

                if (!input.checkValidity() || input.value.trim() === '') {
                    error.classList.remove('hidden');
                    input.classList.remove('border-slate-200');
                    input.classList.add('border-red-500');
                    valid = false;
                } else {
                    error.classList.add('hidden');
                    input.classList.remove('border-red-500');
                    input.classList.add('border-slate-200');
                }
            });

            if (!valid) {
                event.preventDefault();
            }
        });
    });
    </script>
</body>
</html>
