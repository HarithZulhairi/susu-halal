<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set Your Password - Rahma Milk Bank</title>
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
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center p-4">
  <!-- Floating Background Elements -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation"></div>
    <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
  </div>

  <div class="w-full max-w-md mx-auto">
    <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
      <div class="text-center space-y-2">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] rounded-2xl flex items-center justify-center shadow-lg">
          <i class="fas fa-user-shield text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-800">Welcome to HMMC!</h2>
        <p class="text-gray-600">Please set your password to continue</p>
      </div>

      <!-- First Time Password Set Form -->
      <form method="POST" action="{{ route('password.set.first-time') }}" class="space-y-6">
        @csrf
        
        <!-- NRIC (pre-filled from session) -->
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
              value="{{ old('nric', session('nric')) }}"
              required
              readonly
              class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl bg-gray-100 backdrop-blur-sm cursor-not-allowed"
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

        <!-- New Password -->
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
              placeholder="Enter new password"
              class="w-full px-4 py-3 pl-11 pr-11 border border-gray-300 rounded-2xl bg-white/80 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            >
            <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <button type="button" onclick="togglePassword('password', 'password-toggle')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
              <i id="password-toggle" class="fas fa-eye"></i>
            </button>
          </div>
          @error('password')
            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
              <i class="fas fa-exclamation-circle"></i>
              <span>{{ $message }}</span>
            </div>
          @enderror
        </div>

        <!-- Confirm Password -->
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
              placeholder="Confirm new password"
              class="w-full px-4 py-3 pl-11 pr-11 border border-gray-300 rounded-2xl bg-white/80 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            >
            <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <button type="button" onclick="togglePassword('password_confirmation', 'confirm-toggle')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
              <i id="confirm-toggle" class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
        >
          <span class="flex items-center justify-center space-x-2">
            <i class="fas fa-save"></i>
            <span>Set Password & Continue</span>
          </span>
        </button>
      </form>
    </div>
  </div>

  <script>
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
  </script>
</body>
</html>