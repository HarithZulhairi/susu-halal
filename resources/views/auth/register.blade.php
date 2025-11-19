<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - HALIMATUSSAADIA Mother's Milk Centre</title>
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

        /* PROGRESS BAR STYLES */
        .step-item {
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 12px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            z-index: -1;
        }

        .step-item.active:not(:last-child)::after {
            background: linear-gradient(to right, #57cc99, #e5e7eb);
        }

        .step-item.completed:not(:last-child)::after {
            background: #57cc99;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 4s;"></div>
    </div>

    <div id="emailPage" class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="hidden lg:flex flex-col items-center justify-center space-y-8">
            <div class="text-center space-y-4">
                <img src="{{ asset('images/hmmc_logo_clear.png') }}" alt="HALIMATUSSAADIA Mother's Milk Centre Logo" style="width: 270px; height: auto;" class="mx-auto">
                <h1 class="text-4xl font-bold gradient-text">HMMC</h1>
                <p class="text-gray-600 text-lg max-w-md mx-auto">
                    Join our community of caring donors and families. Create your account to get started.
                </p>
            </div>
            <div class="space-y-4 mt-8">
                <div class="flex items-center space-x-3 p-4 rounded-2xl glass-effect shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Secure & Private</h3>
                        <p class="text-sm text-gray-600">Your data is protected</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-4 rounded-2xl glass-effect shadow-sm">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Quick Setup</h3>
                        <p class="text-sm text-gray-600">Get started in minutes</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-md mx-auto">
            <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
                <div class="text-center space-y-2">
                    <div class="lg:hidden w-16 h-16 mx-auto bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-baby-carriage text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                    <p class="text-gray-600">Join our community today</p>
                </div>
                <form id="emailForm" class="space-y-6">
    <div class="space-y-2">
        <label for="contact_number_first" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
            <i class="fas fa-phone text-blue-500 text-sm"></i>
            <span>Contact Number</span>
        </label>
        <div class="relative">
            <input 
                id="contact_number_first" 
                name="contact_number" 
                type="tel" 
                required 
                autocomplete="tel"
                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                placeholder="Start with +60"
                pattern="^(\+?\d{1,3}[- ]?)?(\d{8,15})$" 
                title="Please enter a valid phone number (8 to 15 digits, optional country code)."
            >
            <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>
    <div class="flex items-start space-x-3">
        <input 
            id="terms" 
            name="terms" 
            type="checkbox" 
            required
            class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
        >
        <label for="terms" class="text-sm text-gray-600">
            I agree to the 
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Terms of Service</a> 
            and 
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Privacy Policy</a>
        </label>
    </div>
    <button 
        type="submit" 
        class="w-full bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
    >
        <span class="flex items-center justify-center space-x-2">
            <span>Next</span>
            <i class="fas fa-arrow-right"></i>
        </span>
    </button>
</form>
                <div class="text-center">
                    <p class="text-gray-600">
                        Already become a donor?
                        <a href="{{route('login')}}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                            Sign in
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

    <div id="personalInfoPage" class="w-full max-w-6xl mx-auto hidden">
        <div class="w-full max-w-3xl mx-auto">
            <div class="mb-8">
                <div class="flex items-center justify-between max-w-3xl mx-auto">
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Email</span>
                    </div>
                    <div class="step-item active flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] flex items-center justify-center text-white shadow-lg mb-2">
                            <span class="text-sm font-semibold">2</span>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Profile</span>
                    </div>
                    <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mb-2">
                            <span class="text-sm font-semibold">3</span>
                        </div>
                        <span class="text-xs font-medium text-gray-400">Health</span>
                    </div>
                    <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mb-2">
                            <span class="text-sm font-semibold">4</span>
                        </div>
                        <span class="text-xs font-medium text-gray-400">Time Slot</span>
                    </div>
                    <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mb-2">
                            <span class="text-sm font-semibold">5</span>
                        </div>
                        <span class="text-xs font-medium text-gray-400">Complete</span>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
    <div class="text-center space-y-2 pb-4 border-b border-gray-200">
        <h2 class="text-3xl font-bold gradient-text">Personal Information</h2>
        <p class="text-gray-600">Fill out your profile</p>
    </div>

    <form id="personalInfoForm" class="space-y-5">
        <div class="space-y-2">
            <label for="nric" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-id-card text-blue-500 text-sm"></i>
                <span>NRIC/Passport <span class="text-red-500">*</span></span>
            </label>
            <div class="relative">
                <input 
                    id="nric" 
                    name="nric" 
                    type="text" 
                    required 
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                    placeholder="Enter your NRIC/passport"
                >
                <i class="fas fa-id-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">Your national identification or passport number</p>
        </div>

        <div class="space-y-2">
            <label for="fullname" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-user text-green-500 text-sm"></i>
                <span>Full Name <span class="text-red-500">*</span></span>
            </label>
            <div class="relative">
                <input 
                    id="fullname" 
                    name="fullname" 
                    type="text" 
                    required 
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                    placeholder="Enter your Full Name"
                >
                <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">Your official full name</p>
        </div>

        <div class="space-y-2">
            <label for="dob" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-calendar-alt text-purple-500 text-sm"></i>
                <span>Date of Birth <span class="text-red-500">*</span></span>
            </label>
            <div class="relative">
                <input 
                    id="dob" 
                    name="dob" 
                    type="date" 
                    required 
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                >
                <i class="fas fa-calendar-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">Your date of birth</p>
        </div>

        <div class="space-y-2">
            <label for="email" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-envelope text-yellow-500 text-sm"></i>
                <span>Email Address (Optional)</span>
            </label>
            <div class="relative">
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                    placeholder="Enter your email (for communication)"
                >
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">We will send your login credentials here (if provided).</p>
        </div>
        {{--  
        <div class="space-y-2">
            <label for="contact" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-phone text-orange-500 text-sm"></i>
                <span>Contact <span class="text-red-500">*</span></span>
            </label>
            <div class="relative">
                <input 
                    id="contact" 
                    name="contact" 
                    type="tel" 
                    required 
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                    placeholder="Enter your Phone Number"
                >
                <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">Your primary contact phone number</p>
        </div>--}}

        <div class="space-y-2">
            <label for="address" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-home text-pink-500 text-sm"></i>
                <span>Address <span class="text-red-500">*</span></span>
            </label>
            <div class="relative">
                <textarea 
                    id="address" 
                    name="address" 
                    rows="3"
                    required 
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm resize-none"
                    placeholder="Enter your address"
                ></textarea>
                <i class="fas fa-home absolute left-4 top-5 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">Enter your complete residential address</p>
        </div>

        <div class="space-y-2">
            <label for="parity" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                <i class="fas fa-baby text-indigo-500 text-sm"></i>
                <span>Parity (Number of births) <span class="text-red-500">*</span></span>
            </label>
            <div class="relative">
                <input 
                    id="parity" 
                    name="parity" 
                    type="number" 
                    required 
                    min="0"
                    oninput="generateDeliveryFields(this.value)"
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                    placeholder="Number of children"
                >
                <i class="fas fa-baby absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-500 ml-1">Number of times you have given birth (Parity 0 for no live births).</p>
        </div>

        <div id="delivery-info-container" class="space-y-4 p-4 border border-gray-200 rounded-xl hidden bg-blue-50">
            <p class="font-semibold text-gray-700">Delivery Details:</p>
            </div>

        <div class="flex gap-4 pt-4">
            <button 
                type="button"
                onclick="goBackToEmail()"
                class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-2xl font-semibold hover:bg-gray-200 transition-all duration-200 focus:ring-4 focus:ring-gray-200 focus:outline-none"
            >
                <span class="flex items-center justify-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </span>
            </button>
            <button 
                type="submit" 
                class="flex-1 bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
            >
                <span class="flex items-center justify-center space-x-2">
                    <span>Next</span>
                    <i class="fas fa-arrow-right"></i>
                </span>
            </button>
        </div>
    </form>
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

    <div id="healthLifestylePage" class="w-full max-w-6xl mx-auto hidden">
        <div class="w-full max-w-3xl mx-auto">
            <div class="mb-8">
                <div class="flex items-center justify-between max-w-3xl mx-auto">
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Email</span>
                    </div>
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Profile</span>
                    </div>
                    <div class="step-item active flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] flex items-center justify-center text-white shadow-lg mb-2">
                            <span class="text-sm font-semibold">3</span>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Health</span>
                    </div>
                    <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mb-2">
                            <span class="text-sm font-semibold">4</span>
                        </div>
                        <span class="text-xs font-medium text-gray-400">Time Slot</span>
                    </div>
                     <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mb-2">
                            <span class="text-sm font-semibold">5</span>
                        </div>
                        <span class="text-xs font-medium text-gray-400">Complete</span>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
                <div class="text-center space-y-2 pb-4 border-b border-gray-200">
                    <h2 class="text-3xl font-bold gradient-text">Health & Lifestyle</h2>
                    <p class="text-gray-600">Fill out your health and lifestyle information</p>
                </div>

                <form id="healthLifestyleForm" class="space-y-5">
                    
                    <div class="space-y-2">
                        <label for="infectiousRisk" class="flex items-center space-x-2 text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-shield-virus text-red-500 text-sm"></i>
                            <span>Do you have any **Infectious Disease Risk**? <span class="text-red-500">*</span></span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="infectiousRiskOption" value="no" class="form-radio text-green-600 h-4 w-4" checked onclick="toggleDetail('infectiousRiskDetail', false)" required>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="infectiousRiskOption" value="yes" class="form-radio text-red-600 h-4 w-4" onclick="toggleDetail('infectiousRiskDetail', true)" required>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                        </div>
                        <div id="infectiousRiskDetail" class="relative mt-2 hidden">
                            <textarea 
                                name="infectiousRiskDetailText" 
                                rows="2"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm resize-none"
                                placeholder="Please specify details about the infectious disease risk."
                            ></textarea>
                            <i class="fas fa-comment-dots absolute left-4 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="medication" class="flex items-center space-x-2 text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-pills text-blue-500 text-sm"></i>
                            <span>Are you currently taking any **Medication**? <span class="text-red-500">*</span></span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="medicationOption" value="no" class="form-radio text-green-600 h-4 w-4" checked onclick="toggleDetail('medicationDetail', false)" required>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="medicationOption" value="yes" class="form-radio text-red-600 h-4 w-4" onclick="toggleDetail('medicationDetail', true)" required>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                        </div>
                        <div id="medicationDetail" class="relative mt-2 hidden">
                            <textarea 
                                name="medicationDetailText" 
                                rows="2"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm resize-none"
                                placeholder="Please list the medications you are taking."
                            ></textarea>
                            <i class="fas fa-comment-dots absolute left-4 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="recentIllness" class="flex items-center space-x-2 text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-thermometer-half text-orange-500 text-sm"></i>
                            <span>Have you had any **Recent Illness**? (last 30 days) <span class="text-red-500">*</span></span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="recentIllnessOption" value="no" class="form-radio text-green-600 h-4 w-4" checked onclick="toggleDetail('recentIllnessDetail', false)" required>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="recentIllnessOption" value="yes" class="form-radio text-red-600 h-4 w-4" onclick="toggleDetail('recentIllnessDetail', true)" required>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                        </div>
                        <div id="recentIllnessDetail" class="relative mt-2 hidden">
                            <textarea 
                                name="recentIllnessDetailText" 
                                rows="2"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm resize-none"
                                placeholder="Please specify details about your recent illness."
                            ></textarea>
                            <i class="fas fa-comment-dots absolute left-4 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="tobaccoAlcohol" class="flex items-center space-x-2 text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-smoking-ban text-gray-600 text-sm"></i>
                            <span>Do you use **Tobacco or Alcohol**? <span class="text-red-500">*</span></span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="tobaccoAlcoholOption" value="no" class="form-radio text-green-600 h-4 w-4" checked required>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="tobaccoAlcoholOption" value="yes" class="form-radio text-red-600 h-4 w-4" required>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="dietaryAlerts" class="flex items-center space-x-2 text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-utensils text-green-500 text-sm"></i>
                            <span>Do you have any **Dietary Alerts or Restrictions**? <span class="text-red-500">*</span></span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="dietaryAlertsOption" value="no" class="form-radio text-green-600 h-4 w-4" checked onclick="toggleDetail('dietaryAlertsDetail', false)" required>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="dietaryAlertsOption" value="yes" class="form-radio text-red-600 h-4 w-4" onclick="toggleDetail('dietaryAlertsDetail', true)" required>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                        </div>
                        <div id="dietaryAlertsDetail" class="relative mt-2 hidden">
                            <textarea 
                                name="dietaryAlertsDetailText" 
                                rows="2"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm resize-none"
                                placeholder="Please list any dietary alerts or restrictions."
                            ></textarea>
                            <i class="fas fa-comment-dots absolute left-4 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button 
                            type="button"
                            onclick="goBackToPersonalInfo()"
                            class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-2xl font-semibold hover:bg-gray-200 transition-all duration-200 focus:ring-4 focus:ring-gray-200 focus:outline-none"
                        >
                            <span class="flex items-center justify-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back</span>
                            </span>
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
                        >
                            <span class="flex items-center justify-center space-x-2">
                                <span>Next</span>
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </button>
                    </div>
                </form>
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

    <div id="donorAvailabilityPage" class="w-full max-w-6xl mx-auto hidden">
    <div class="w-full max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between max-w-3xl mx-auto">
                <div class="step-item completed flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Contact</span>
                </div>
                <div class="step-item completed flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Profile</span>
                </div>
                <div class="step-item completed flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Health</span>
                </div>
                <div class="step-item active flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#1a5f7a] to-[#57cc99] flex items-center justify-center text-white shadow-lg mb-2">
                        <span class="text-sm font-semibold">4</span>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Time Slot</span>
                </div>
                <div class="step-item flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mb-2">
                        <span class="text-sm font-semibold">5</span>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Complete</span>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">
            <div class="text-center space-y-2 pb-4 border-b border-gray-200">
                <h2 class="text-3xl font-bold gradient-text">Weekly Availability</h2>
                <p class="text-gray-600">
                    Select all days you are available and choose your preferred one-hour donation slots.
                </p>
            </div>

            <form id="availabilityForm" class="space-y-6">
                <div class="space-y-3 p-4 border rounded-2xl bg-gray-50">
                    <label class="flex items-center space-x-2 text-lg font-semibold text-gray-800">
                        <i class="fas fa-calendar-alt text-indigo-600"></i>
                        <span>Select Available Days <span class="text-red-500">*</span></span>
                    </label>
                    <div class="flex flex-wrap gap-2 justify-center">
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Monday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Monday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Mon</span>
                        </label>
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Tuesday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Tuesday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Tue</span>
                        </label>
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Wednesday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Wednesday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Wed</span>
                        </label>
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Thursday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Thursday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Thu</span>
                        </label>
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Friday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Friday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Fri</span>
                        </label>
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Saturday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Saturday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Sat</span>
                        </label>
                        <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                            <input type="checkbox" name="availableDays[]" value="Sunday" class="form-checkbox text-indigo-600 h-5 w-5 rounded day-checkbox" onclick="toggleTimeSlots('Sunday', this.checked)" />
                            <span class="ml-2 text-sm font-medium text-gray-700">Sun</span>
                        </label>
                    </div>
                </div>

                <div id="dynamicTimeSlotsContainer" class="space-y-4">
                    </div>

                <div class="flex gap-4 pt-4">
                    <button
                        type="button"
                        onclick="goBackToHealthLifestyle()"
                        class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-2xl font-semibold hover:bg-gray-200 transition-all duration-200 focus:ring-4 focus:ring-gray-200 focus:outline-none"
                    >
                        <span class="flex items-center justify-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </span>
                    </button>

                    <button
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-blue-200 focus:outline-none"
                    >
                        <span class="flex items-center justify-center space-x-2">
                            <span>Next</span>
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </button>
                </div>
            </form>
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

    <div id="completePage" class="w-full max-w-6xl mx-auto hidden">
        <div class="w-full max-w-3xl mx-auto">
            <div class="mb-8">
                <div class="flex items-center justify-between max-w-3xl mx-auto">
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Email</span>
                    </div>
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Profile</span>
                    </div>
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Health</span>
                    </div>
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check-double text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Time Slot</span>
                    </div>
                    <div class="step-item completed flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#57cc99] to-[#2a9d8f] flex items-center justify-center text-white shadow-lg mb-2">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Complete</span>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-3xl shadow-2xl p-12 space-y-8">
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="relative w-32 h-32 bg-gradient-to-br from-[#0066ff] to-[#0052cc] rounded-full flex items-center justify-center shadow-2xl">
                            <i class="fas fa-envelope-open-text text-white text-6xl"></i>
                        </div>
                    </div>
                </div>

                <div class="text-center space-y-4">
                    <h2 class="text-3xl font-bold text-gray-800">Registration Complete!</h2>
                    <p class="text-gray-600">
                        You will receive your **credential via email** shortly. 
                        Your availability has been recorded, and a staff member will contact you to confirm your first donation appointment.
                    </p>
                </div>

                <div class="flex justify-end items-center pt-6">
                    <a 
                        href="{{ route('login') }}" 
                        class="bg-gradient-to-r from-[#1a5f7a] to-[#57cc99] text-white py-3 px-8 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-green-200 focus:outline-none"
                    >
                        <span class="flex items-center space-x-2">
                            <span>Go to Login</span>
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                    </a>
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

    <!-- Add this hidden form at the bottom of your blade -->
<form id="finalRegistrationForm" method="POST" action="{{ route('register.donor.store') }}" class="hidden">
    @csrf
    <!-- Step 1 Data -->
    <input type="hidden" name="contact_number" id="final_contact_number">
    <input type="hidden" name="terms" id="final_terms" value="accepted">
    
    <!-- Step 2 Data -->
    <input type="hidden" name="nric" id="final_nric">
    <input type="hidden" name="fullname" id="final_fullname">
    <input type="hidden" name="dob" id="final_dob">
    <input type="hidden" name="email" id="final_email">
    <input type="hidden" name="contact" id="final_contact">
    <input type="hidden" name="address" id="final_address">
    <input type="hidden" name="parity" id="final_parity">
    <!-- Delivery details as JSON -->
    <input type="hidden" name="deliveryDate" id="final_deliveryDate">
    <input type="hidden" name="gestationWeek" id="final_gestationWeek">
    
    <!-- Step 3 Data -->
    <input type="hidden" name="infectiousRiskOption" id="final_infectiousRiskOption">
    <input type="hidden" name="infectiousRiskDetailText" id="final_infectiousRiskDetailText">
    <input type="hidden" name="medicationOption" id="final_medicationOption">
    <input type="hidden" name="medicationDetailText" id="final_medicationDetailText">
    <input type="hidden" name="recentIllnessOption" id="final_recentIllnessOption">
    <input type="hidden" name="recentIllnessDetailText" id="final_recentIllnessDetailText">
    <input type="hidden" name="tobaccoAlcoholOption" id="final_tobaccoAlcoholOption">
    <input type="hidden" name="dietaryAlertsOption" id="final_dietaryAlertsOption">
    <input type="hidden" name="dietaryAlertsDetailText" id="final_dietaryAlertsDetailText">
    
    <!-- Step 4 Data -->
    <input type="hidden" name="availableDays" id="final_availableDays">
    <input type="hidden" name="timeSlots" id="final_timeSlots">
</form>


<script>
    // Function to toggle textareas for Health & Lifestyle Page (Step 3)
    function toggleDetail(detailId, isVisible) {
        const detailElement = document.getElementById(detailId);
        if (detailElement) {
            const textarea = detailElement.querySelector('textarea');
            if (isVisible) {
                detailElement.classList.remove('hidden');
                if (textarea) textarea.setAttribute('required', 'required');
            } else {
                detailElement.classList.add('hidden');
                if (textarea) {
                    textarea.removeAttribute('required');
                    textarea.value = ''; 
                }
            }
        }
    }
    
    // Function to generate dynamic fields based on Parity value
    function generateDeliveryFields(parityValue) {
        const container = document.getElementById('delivery-info-container');
        if (!container) return;
        
        const count = parseInt(parityValue) || 0;
        container.innerHTML = '<p class="font-semibold text-gray-700">Delivery Details:</p>';

        if (count > 0) {
            container.classList.remove('hidden');
            for (let i = 1; i <= count; i++) {
                const isLastChild = (i === count);
                const sectionHtml = `
                    <div class="p-3 border border-blue-200 rounded-xl bg-white space-y-3 shadow-sm">
                        <h4 class="font-bold text-sm text-blue-600">Child ${i} Details ${isLastChild ? '(Most Recent)' : ''}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="deliveryDate_${i}" class="text-xs font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-calendar-check text-teal-500 mr-2"></i>
                                    Delivery Date <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    id="deliveryDate_${i}" 
                                    name="deliveryDate[${i}]" 
                                    type="date" 
                                    required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 transition"
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="gestationWeek_${i}" class="text-xs font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-clock text-pink-500 mr-2"></i>
                                    Gestation Week <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    id="gestationWeek_${i}" 
                                    name="gestationWeek[${i}]" 
                                    type="number" 
                                    min="1"
                                    max="50"
                                    required 
                                    placeholder="e.g., 38"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 transition"
                                >
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', sectionHtml);
            }
        } else {
            container.classList.add('hidden');
        }
    }
    
    const AVAILABLE_SLOTS = [
        "8:00 AM - 9:00 AM",
        "9:00 AM - 10:00 AM",
        "10:00 AM - 11:00 AM",
        "11:00 AM - 12:00 PM",
        "2:00 PM - 3:00 PM",
        "3:00 PM - 4:00 PM"
    ];

    function generateTimeSlotHTML(day) {
        let slotsHtml = AVAILABLE_SLOTS.map(slot => `
            <label class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer">
                <input
                    type="checkbox"
                    name="timeSlots[${day}][]"
                    value="${slot}"
                    class="form-checkbox text-indigo-600 h-5 w-5 rounded"
                />
                <span class="ml-2 text-sm font-medium text-gray-700">${slot}</span>
            </label>
        `).join('');

        return `
            <div id="${day}TimeSlots" class="space-y-3 p-4 border rounded-2xl bg-indigo-50">
                <h3 class="font-semibold text-gray-800">${day} Time Slots:</h3>
                <div class="flex flex-wrap gap-3 justify-center">
                    ${slotsHtml}
                </div>
            </div>
        `;
    }

    // Function to show/hide time slots for a specific day
    function toggleTimeSlots(day, isChecked) {
        const container = document.getElementById('dynamicTimeSlotsContainer');
        if (!container) return;
        
        const dayId = `${day}TimeSlots`;
        let dayElement = document.getElementById(dayId);

        if (isChecked) {
            if (!dayElement) {
                // If element doesn't exist, create and append it
                const newHtml = generateTimeSlotHTML(day);
                container.insertAdjacentHTML('beforeend', newHtml);
            }
        } else {
            // If unchecked, remove the element and ensure slots are cleared
            if (dayElement) {
                dayElement.remove();
            }
        }
        
        // This makes sure the container is only visible if days are selected
        const visibleDayContainers = container.querySelectorAll('div[id$="TimeSlots"]').length;
        if (visibleDayContainers > 0) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

// Function to collect all data and submit via AJAX
function submitAllData() {
    console.log('Starting form submission...');
    
    const submitBtn = document.querySelector('#availabilityForm button[type="submit"]');
    if (!submitBtn) {
        console.error('Submit button not found');
        return;
    }
    
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitBtn.disabled = true;

    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                        document.querySelector('input[name="_token"]')?.value;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        // Collect Step 1 data
        const contactNumber = document.getElementById('contact_number_first')?.value;
        const termsChecked = document.getElementById('terms')?.checked;
        
        if (!contactNumber) {
            throw new Error('Contact number is required');
        }
        
        if (!termsChecked) {
            throw new Error('Please agree to the Terms of Service and Privacy Policy');
        }
        
        // Collect Step 2 data
        const nric = document.getElementById('nric')?.value;
        const fullname = document.getElementById('fullname')?.value;
        const dob = document.getElementById('dob')?.value;
        const email = document.getElementById('email')?.value;
        const address = document.getElementById('address')?.value;
        const parity = document.getElementById('parity')?.value;
        
        if (!nric || !fullname || !dob || !address || !parity) {
            throw new Error('Please fill all required personal information fields');
        }
        
        // Collect delivery details as JSON arrays
        const deliveryDates = [];
        const gestationWeeks = [];
        const parityValue = parseInt(parity) || 0;
        
        for (let i = 1; i <= parityValue; i++) {
            const dateField = document.getElementById(`deliveryDate_${i}`);
            const weekField = document.getElementById(`gestationWeek_${i}`);
            if (dateField && weekField) {
                if (!dateField.value || !weekField.value) {
                    throw new Error(`Please fill all delivery details for Child ${i}`);
                }
                deliveryDates.push(dateField.value);
                gestationWeeks.push(weekField.value);
            }
        }
        
        // Collect Step 3 data
        const infectiousRiskOption = document.querySelector('input[name="infectiousRiskOption"]:checked')?.value;
        const infectiousRiskDetailText = document.querySelector('textarea[name="infectiousRiskDetailText"]')?.value || '';
        const medicationOption = document.querySelector('input[name="medicationOption"]:checked')?.value;
        const medicationDetailText = document.querySelector('textarea[name="medicationDetailText"]')?.value || '';
        const recentIllnessOption = document.querySelector('input[name="recentIllnessOption"]:checked')?.value;
        const recentIllnessDetailText = document.querySelector('textarea[name="recentIllnessDetailText"]')?.value || '';
        const tobaccoAlcoholOption = document.querySelector('input[name="tobaccoAlcoholOption"]:checked')?.value;
        const dietaryAlertsOption = document.querySelector('input[name="dietaryAlertsOption"]:checked')?.value;
        const dietaryAlertsDetailText = document.querySelector('textarea[name="dietaryAlertsDetailText"]')?.value || '';
        
        if (!infectiousRiskOption || !medicationOption || !recentIllnessOption || !tobaccoAlcoholOption || !dietaryAlertsOption) {
            throw new Error('Please fill all health and lifestyle information');
        }
        
        // Collect Step 4 data
        const availableDays = Array.from(document.querySelectorAll('input[name="availableDays[]"]:checked')).map(cb => cb.value);
        const timeSlots = {};
        
        if (availableDays.length === 0) {
            throw new Error('Please select at least one available day');
        }
        
        availableDays.forEach(day => {
            const daySlots = Array.from(document.querySelectorAll(`input[name="timeSlots[${day}][]"]:checked`)).map(cb => cb.value);
            if (daySlots.length > 0) {
                timeSlots[day] = daySlots;
            }
        });
        
        // Check if at least one time slot is selected
        const totalTimeSlots = Object.values(timeSlots).reduce((total, slots) => total + slots.length, 0);
        if (totalTimeSlots === 0) {
            throw new Error('Please select at least one time slot');
        }
        
        if (!termsChecked) {
            alert('You must accept the Terms of Service and Privacy Policy to continue.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }
        // Prepare form data
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('contact_number', contactNumber);
        formData.append('terms', '1');  // or 'on' or 'true'
        formData.append('nric', nric);
        formData.append('fullname', fullname);
        formData.append('dob', dob);
        formData.append('email', email || '');
        formData.append('address', address);
        formData.append('parity', parity);
        formData.append('deliveryDate', JSON.stringify(deliveryDates));
        formData.append('gestationWeek', JSON.stringify(gestationWeeks));
        formData.append('infectiousRiskOption', infectiousRiskOption);
        formData.append('infectiousRiskDetailText', infectiousRiskDetailText);
        formData.append('medicationOption', medicationOption);
        formData.append('medicationDetailText', medicationDetailText);
        formData.append('recentIllnessOption', recentIllnessOption);
        formData.append('recentIllnessDetailText', recentIllnessDetailText);
        formData.append('tobaccoAlcoholOption', tobaccoAlcoholOption);
        formData.append('dietaryAlertsOption', dietaryAlertsOption);
        formData.append('dietaryAlertsDetailText', dietaryAlertsDetailText);
        formData.append('availableDays', JSON.stringify(availableDays));
        formData.append('timeSlots', JSON.stringify(timeSlots));

        console.log('Form data collected, making AJAX request...');
        console.log('Terms accepted:', termsChecked);
        console.log('Available days:', availableDays);
        console.log('Time slots:', timeSlots);

        // Add timeout to the fetch request
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

        // Submit via AJAX
        fetch("{{ route('register.donor.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
            signal: controller.signal
        })
        .then(response => {
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Server error: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Server response:', data);
            
            if (data.success) {
                // Success - show completion page
                document.getElementById('donorAvailabilityPage').classList.add('hidden');
                document.getElementById('completePage').classList.remove('hidden');
                window.scrollTo(0, 0);
            } else {
                throw new Error(data.message || 'Registration failed');
            }
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Registration Error:', error);
            
            if (error.name === 'AbortError') {
                alert('Request timed out. Please try again.');
            } else {
                alert('Registration failed: ' + error.message);
            }
            
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });

    } catch (error) {
        console.error('Error in submitAllData:', error);
        alert('Error: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

    // NAVIGATION FUNCTIONS

    // Step 1 -> Step 2
    document.getElementById('emailForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate contact number
        const contactNumber = document.getElementById('contact_number_first')?.value;
        const termsChecked = document.getElementById('terms')?.checked;
        
        if (!contactNumber) {
            alert('Please enter your contact number');
            return;
        }
        
        if (!termsChecked) {
            alert('Please agree to the Terms of Service and Privacy Policy');
            return;
        }
        
        document.getElementById('emailPage').classList.add('hidden');
        document.getElementById('personalInfoPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    });

    // Step 2 -> Step 1
    function goBackToEmail() {
        document.getElementById('personalInfoPage').classList.add('hidden');
        document.getElementById('emailPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    // Step 2 -> Step 3
    document.getElementById('personalInfoForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const nric = document.getElementById('nric')?.value;
        const fullname = document.getElementById('fullname')?.value;
        const dob = document.getElementById('dob')?.value;
        const address = document.getElementById('address')?.value;
        const parity = document.getElementById('parity')?.value;
        
        if (!nric || !fullname || !dob || !address || !parity) {
            alert('Please fill all required fields');
            return;
        }
        
        // Basic validation for dynamic fields when parity > 0
        const parityValue = parseInt(parity) || 0;
        if (parityValue > 0) {
            let valid = true;
            for (let i = 1; i <= parityValue; i++) {
                const deliveryDate = document.getElementById(`deliveryDate_${i}`);
                const gestationWeek = document.getElementById(`gestationWeek_${i}`);
                if (deliveryDate && gestationWeek && (!deliveryDate.value || !gestationWeek.value)) {
                    alert(`Please fill out all details for Child ${i}.`);
                    valid = false;
                    break;
                }
            }
            if (!valid) return;
        }

        document.getElementById('personalInfoPage').classList.add('hidden');
        document.getElementById('healthLifestylePage').classList.remove('hidden');
        window.scrollTo(0, 0);
    });

    // Step 3 -> Step 2
    function goBackToPersonalInfo() {
        document.getElementById('healthLifestylePage').classList.add('hidden');
        document.getElementById('personalInfoPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    // Step 3 -> Step 4 (Availability Page)
    document.getElementById('healthLifestyleForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const infectiousRiskOption = document.querySelector('input[name="infectiousRiskOption"]:checked');
        const medicationOption = document.querySelector('input[name="medicationOption"]:checked');
        const recentIllnessOption = document.querySelector('input[name="recentIllnessOption"]:checked');
        const tobaccoAlcoholOption = document.querySelector('input[name="tobaccoAlcoholOption"]:checked');
        const dietaryAlertsOption = document.querySelector('input[name="dietaryAlertsOption"]:checked');
        
        if (!infectiousRiskOption || !medicationOption || !recentIllnessOption || !tobaccoAlcoholOption || !dietaryAlertsOption) {
            alert('Please answer all health and lifestyle questions');
            return;
        }

        document.getElementById('healthLifestylePage').classList.add('hidden');
        document.getElementById('donorAvailabilityPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    });

    // Step 4 -> Step 3
    function goBackToHealthLifestyle() {
        document.getElementById('donorAvailabilityPage').classList.add('hidden');
        document.getElementById('healthLifestylePage').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    // Step 4 -> Final Submission (AJAX)
    document.getElementById('availabilityForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedDays = document.querySelectorAll('.day-checkbox:checked').length;
        const selectedTimeSlots = document.querySelectorAll('#dynamicTimeSlotsContainer input[type="checkbox"]:checked').length;

        if (selectedDays === 0) {
            alert("Please select at least one available day.");
            return;
        }
        if (selectedTimeSlots === 0) {
            alert("Please select at least one time slot on the selected day(s).");
            return;
        }

        // Submit data via AJAX and show completion page
        submitAllData();
    });

    // Initialize any required elements on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Registration form loaded');
        
        // Initialize delivery fields if parity has a value
        const parityField = document.getElementById('parity');
        if (parityField && parityField.value) {
            generateDeliveryFields(parityField.value);
        }
        
        // Make sure CSRF token is available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.warn('CSRF token meta tag not found');
        }
    });
</script>
</body>
</html>