<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ isset($user_table) ? 'Reset Password' : 'Set Your Password' }} - Rahma Milk Bank</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --primary: #1a5f7a;
      --secondary: #57cc99;
      --accent: #ffd166;
      --gradient: linear-gradient(135deg, #1a5f7a 0%, #57cc99 100%);
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    .float-animation { animation: float 6s ease-in-out infinite; }
    .glass-effect {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    @supports not (backdrop-filter: blur(10px)) {
      .glass-effect { background: rgba(255, 255, 255, 0.98); }
    }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center p-4">
  
  @if(session('info'))
    <div class="fixed top-4 right-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-lg z-50">
      <div class="flex items-center space-x-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('info') }}</span>
      </div>
    </div>
  @endif

  @if(session('status'))
    <div class="fixed top-4 right-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl shadow-lg z-50">
      <div class="flex items-center space-x-2">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('status') }}</span>
      </div>
    </div>
  @endif

  <!-- Floating Background Elements -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation"></div>
    <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 4s;"></div>
  </div>

  <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
    
    <!-- Left Side - Brand Info (hidden on mobile) -->
    <div class="hidden lg:flex flex-col items-center justify-center space-y-8">
      <div class="text-center space-y-4">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] rounded-2xl flex items-center justify-center shadow-2xl">
          <i class="fas fa-baby-carriage text-white text-3xl"></i>
        </div>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] bg-clip-text text-transparent">
          Rahma Milk Bank
        </h1>
        <p class="text-gray-600 text-lg max-w-md mx-auto">
          A Shariah-compliant milk sharing platform helping mothers and infants in need.
        </p>
      </div>
    </div>

    <!-- Right Side - Form -->
    <div class="w-full max-w-md mx-auto">
      <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
        
        <div class="text-center space-y-2">
          <div class="lg:hidden w-16 h-16 mx-auto bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] rounded-2xl flex items-center justify-center shadow-lg">
            <i class="fas {{ isset($user_table) ? 'fa-lock' : 'fa-user-shield' }} text-white text-2xl"></i>
          </div>
          
          @if(isset($user_table))
            {{-- Forgot Password Flow --}}
            <h2 class="text-3xl font-bold text-gray-800">Reset Password</h2>
            <p class="text-gray-600">Create a new password for your account</p>
          @else
            {{-- First Time Donor Login Flow --}}
            <h2 class="text-3xl font-bold text-gray-800">Welcome to HMMC!</h2>
            <p class="text-gray-600">Please set your password to continue</p>
          @endif
        </div>

        {{-- Error Display --}}
        @if ($errors->any())
          <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-2">
            <div class="flex items-center space-x-2 text-red-800 font-semibold">
              <i class="fas fa-exclamation-triangle"></i>
              <span>Please fix the following errors:</span>
            </div>
            <ul class="text-sm text-red-600 space-y-1 ml-6">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Form --}}
        <form 
          method="POST" 
          action="{{ isset($user_table) ? route('password.reset.submit', [$user_table, $user_id]) : route('password.set.first-time') }}" 
          class="space-y-6"
        >
          @csrf
          <input type="hidden" name="role" value="{{ $role ?? session('auth_role') }}">
          
          {{-- NRIC --}}
          @if(!isset($user_table))
            <div class="space-y-2">
              <label for="nric" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-id-card text-blue-500"></i>
                <span>NRIC Number</span>
              </label>
              <div class="relative">
                <input
                  id="nric"
                  type="text"
                  name="nric"
                  value="{{ old('nric', $nric) }}"
                  required
                  readonly
                  class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl bg-gray-100 cursor-not-allowed"
                >
                <i class="fas fa-id-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              </div>
              @error('nric')
                <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                  <i class="fas fa-exclamation-circle"></i>
                  <span>{{ $message }}</span>
                </div>
              @enderror
            </div>
          @endif

          {{-- New Password --}}
          <div class="space-y-2">
            <label for="password" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
              <i class="fas fa-lock text-blue-500"></i>
              <span>New Password</span>
            </label>
            <div class="relative">
              <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Enter new password (min. 8 characters)"
                class="w-full px-4 py-3 pl-11 pr-11 border border-gray-300 rounded-2xl bg-white/80 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
              <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              <button 
                type="button" 
                onclick="togglePassword('password', 'password-toggle')" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
              >
                <i id="password-toggle" class="fas fa-eye"></i>
              </button>
            </div>
            <p class="text-xs text-gray-500 flex items-center space-x-1">
              <i class="fas fa-info-circle"></i>
              <span>Minimum 8 characters required</span>
            </p>
            @error('password')
              <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
              </div>
            @enderror
          </div>

          {{-- Confirm Password --}}
          <div class="space-y-2">
            <label for="password_confirmation" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
              <i class="fas fa-lock text-blue-500"></i>
              <span>Confirm Password</span>
            </label>
            <div class="relative">
              <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Re-enter your password"
                class="w-full px-4 py-3 pl-11 pr-11 border border-gray-300 rounded-2xl bg-white/80 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
              <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              <button 
                type="button" 
                onclick="togglePassword('password_confirmation', 'confirm-toggle')" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
              >
                <i id="confirm-toggle" class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          {{-- Password Strength Indicator --}}
          <div id="password-strength" class="hidden">
            <div class="flex items-center justify-between text-xs mb-1">
              <span class="text-gray-600">Password Strength:</span>
              <span id="strength-text" class="font-semibold"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div id="strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
          </div>

          {{-- Submit Button --}}
          <button
            type="submit"
            class="w-full bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
          >
            <span class="flex items-center justify-center space-x-2">
              <i class="fas {{ isset($user_table) ? 'fa-check' : 'fa-save' }}"></i>
              <span>{{ isset($user_table) ? 'Reset Password' : 'Set Password & Continue' }}</span>
            </span>
          </button>
        </form>

        {{-- Back to Login Link (only for forgot password flow) --}}
        @if(isset($user_table))
          <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
              <i class="fas fa-arrow-left text-sm"></i>
              <span>Back to Login</span>
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    function togglePassword(inputId, toggleId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(toggleId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    passwordInput.addEventListener('input', function() {
      const password = this.value;
      
      if (password.length === 0) {
        strengthIndicator.classList.add('hidden');
        return;
      }
      
      strengthIndicator.classList.remove('hidden');
      
      let strength = 0;
      let text = '';
      let color = '';
      
      // Length check
      if (password.length >= 8) strength += 25;
      if (password.length >= 12) strength += 15;
      
      // Contains lowercase
      if (/[a-z]/.test(password)) strength += 15;
      
      // Contains uppercase
      if (/[A-Z]/.test(password)) strength += 15;
      
      // Contains numbers
      if (/[0-9]/.test(password)) strength += 15;
      
      // Contains special characters
      if (/[^A-Za-z0-9]/.test(password)) strength += 15;
      
      // Determine strength level
      if (strength < 30) {
        text = 'Weak';
        color = '#ef4444'; // red
      } else if (strength < 60) {
        text = 'Fair';
        color = '#f59e0b'; // orange
      } else if (strength < 80) {
        text = 'Good';
        color = '#3b82f6'; // blue
      } else {
        text = 'Strong';
        color = '#10b981'; // green
      }
      
      strengthBar.style.width = strength + '%';
      strengthBar.style.backgroundColor = color;
      strengthText.textContent = text;
      strengthText.style.color = color;
    });

    // Password match indicator
    const confirmInput = document.getElementById('password_confirmation');
    
    confirmInput.addEventListener('input', function() {
      if (this.value && passwordInput.value !== this.value) {
        this.classList.add('border-red-500');
        this.classList.remove('border-gray-300');
      } else if (this.value && passwordInput.value === this.value) {
        this.classList.add('border-green-500');
        this.classList.remove('border-gray-300', 'border-red-500');
      } else {
        this.classList.remove('border-red-500', 'border-green-500');
        this.classList.add('border-gray-300');
      }
    });

    // Auto-hide success messages
    setTimeout(() => {
      const alerts = document.querySelectorAll('.fixed.top-4');
      alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease-out';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
  </script>
</body>
</html>