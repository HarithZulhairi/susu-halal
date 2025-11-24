<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rahma Milk Bank</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #1a5f7a;
            --secondary: #57cc99;
            --accent: #ffd166;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #2a9d8f;
            --gradient: linear-gradient(135deg, #1a5f7a 0%, #57cc99 100%);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .gradient-text {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center p-4">
    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 4s;"></div>
    </div>

    <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <!-- Left Side - Brand & Illustration -->
        <div class="hidden lg:flex flex-col items-center justify-center space-y-8">
            <div class="text-center space-y-4">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] rounded-2xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-baby-carriage text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold gradient-text">Rahma Milk Bank</h1>
                <p class="text-gray-600 text-lg max-w-md mx-auto">
                    Shariah-compliant human milk sharing platform supporting mothers and infants in need.
                </p>
            </div>

            <div class="space-y-4 mt-8">
                <div class="flex items-center space-x-3 p-4 rounded-2xl glass-effect shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Shariah Compliant</h3>
                        <p class="text-sm text-gray-600">Following Islamic guidelines</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 p-4 rounded-2xl glass-effect shadow-sm">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-md text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Medical Screening</h3>
                        <p class="text-sm text-gray-600">Ensuring safety & quality</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 p-4 rounded-2xl glass-effect shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hands-helping text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Community Support</h3>
                        <p class="text-sm text-gray-600">Helping families in need</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full max-w-md mx-auto">
            <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
                <div class="text-center space-y-2">
                    <div class="lg:hidden w-16 h-16 mx-auto bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-baby-carriage text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to your account</p>
                </div>

                @if(session('status'))
                <div class="p-4 rounded-2xl bg-green-50 border border-green-200 flex items-center space-x-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <p class="text-green-800 text-sm">{{ session('status') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="username" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-user text-blue-500 text-sm"></i>
                            <span>IC / Username</span>
                        </label>
                        <div class="relative">
                            <input 
                                id="username" 
                                name="username" 
                                type="text" 
                                value="{{ old('username') }}"
                                required 
                                autofocus 
                                autocomplete="username"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                placeholder="Enter your username"
                            >
                            <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('username')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-lock text-blue-500 text-sm"></i>
                            <span>Password</span>
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                autocomplete="current-password"
                                class="w-full px-4 py-3 pl-11 pr-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                placeholder="Enter your password"
                            >
                            <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-eye" id="password-toggle"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Role Dropdown -->
                        <div class="space-y-2">
                        <label for="role" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-user-tag text-blue-500 text-sm"></i>
                            <span>Role</span>
                        </label>
                        <div class="relative">
                            <select 
                            id="role" 
                            name="role"
                            required
                            class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl bg-white/80 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none"
                            >
                                <option value="" disabled selected>Select your role</option>
                                <option value="hmmc_admin">HMMC Admin</option>
                                <option value="nurse">Nurse</option>
                                <option value="doctor">Doctor</option>
                                <option value="lab_technician">Lab Technician</option>
                                <option value="shariah_advisor">Shariah Advisor</option>
                                <option value="donor" {{ old('role') == 'donor' ? 'selected' : '' }}>Donor</option>
                                <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                            </select>
                            <i class="fas fa-user-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        @error('role')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                            </div>
                        @enderror
                        </div>


                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <div class="relative">
                                <input id="remember_me" name="remember" type="checkbox" class="sr-only">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center transition-all duration-200">
                                    <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                                </div>
                            </div>
                            <span class="text-sm text-gray-600 select-none">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200 font-medium">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
                    >
                        <span class="flex items-center justify-center space-x-2">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Sign In</span>
                        </span>
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-gray-600">
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                            Become a donor?
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-6 space-y-2">
                <div class="flex justify-center space-x-6 text-sm text-gray-500">
                    <a href="#" class="hover:text-gray-700 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-700 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-gray-700 transition-colors">Support</a>
                </div>
                <p class="text-xs text-gray-400">&copy; 2024 Rahma Milk Bank. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('remember_me').addEventListener('change', function() {
            const checkIcon = this.nextElementSibling.querySelector('i');
            if (this.checked) {
                this.nextElementSibling.style.backgroundColor = '#1a5f7a';
                this.nextElementSibling.style.borderColor = '#1a5f7a';
                checkIcon.style.opacity = '1';
            } else {
                this.nextElementSibling.style.backgroundColor = 'transparent';
                this.nextElementSibling.style.borderColor = '#d1d5db';
                checkIcon.style.opacity = '0';
            }
        });

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => input.parentElement.classList.add('transform', 'scale-105'));
            input.addEventListener('blur', () => input.parentElement.classList.remove('transform', 'scale-105'));
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Signing In...</span>';
            button.disabled = true;
        });
    </script>
</body>
</html>
