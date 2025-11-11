<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <label for="email" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope text-blue-500 text-sm"></i>
                            <span>Email Address</span>
                        </label>
                        <div class="relative">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                autocomplete="email"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                placeholder="Enter your email"
                            >
                            <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
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
                    </div>

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
                            <span>Parity <span class="text-red-500">*</span></span>
                        </label>
                        <div class="relative">
                            <input 
                                id="parity" 
                                name="parity" 
                                type="number" 
                                required 
                                min="0"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                placeholder="Number of children"
                            >
                            <i class="fas fa-baby absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <p class="text-xs text-gray-500 ml-1">Number of times you have given birth</p>
                    </div>

                    <div class="space-y-2">
                        <label for="deliveryDates" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-calendar-check text-teal-500 text-sm"></i>
                            <span>Last Delivery Date <span class="text-red-500">*</span></span>
                        </label>
                        <div class="relative">
                            <input 
                                id="deliveryDates" 
                                name="deliveryDates" 
                                type="date" 
                                required 
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                            >
                            <i class="fas fa-calendar-check absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <p class="text-xs text-gray-500 ml-1">Date of your most recent delivery</p>
                    </div>

                    <div class="space-y-2">
                        <label for="breastfeedingStatus" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-heart text-red-500 text-sm"></i>
                            <span>Breastfeeding Status <span class="text-red-500">*</span></span>
                        </label>
                        <div class="relative">
                            <select 
                                id="breastfeedingStatus" 
                                name="breastfeedingStatus" 
                                required 
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm appearance-none"
                            >
                                <option value="" disabled selected>Select status</option>
                                <option value="Exclusive">Exclusively Breastfeeding</option>
                                <option value="Partial">Partially Breastfeeding</option>
                                <option value="Stopped">Stopped Breastfeeding</option>
                            </select>
                            <i class="fas fa-caret-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            <i class="fas fa-heart absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <p class="text-xs text-gray-500 ml-1">Current breastfeeding status</p>
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
      Select the date you’re available, and your day will appear automatically.
    </p>
  </div>

  <form id="availabilityForm" class="space-y-6">
    <div class="space-y-3 p-4 border rounded-2xl bg-gray-50">
      <label
        class="flex items-center space-x-2 text-lg font-semibold text-gray-800"
      >
        <i class="fas fa-calendar text-indigo-600"></i>
        <span>Select Available Date <span class="text-red-500">*</span></span>
      </label>

      <!-- Modern date picker -->
      <div class="relative">
        <input
          type="date"
          id="availableDate"
          name="availableDate"
          class="w-full p-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 transition-all duration-200 bg-white shadow-sm"
          required
        />
        <div
          id="dayDisplay"
          class="mt-3 text-indigo-700 font-semibold text-center text-lg hidden"
        ></div>
      </div>
    </div>

    <!-- Example: show dynamic time slots after date selection -->
    <div
      id="timeSlotsContainer"
      class="space-y-3 p-4 border rounded-2xl bg-indigo-50 hidden"
    >
      <h3 class="font-semibold text-gray-800" id="selectedDayTitle"></h3>
      <div class="flex flex-wrap gap-3 justify-center">
        <label
          class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer"
        >
          <input
            type="checkbox"
            name="timeSlot[]"
            value="9am-11am"
            class="form-checkbox text-indigo-600 h-5 w-5 rounded"
          />
          <span class="ml-2 text-sm font-medium text-gray-700"
            >9:00 AM - 11:00 AM</span
          >
        </label>
        <label
          class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer"
        >
          <input
            type="checkbox"
            name="timeSlot[]"
            value="11am-1pm"
            class="form-checkbox text-indigo-600 h-5 w-5 rounded"
          />
          <span class="ml-2 text-sm font-medium text-gray-700"
            >11:00 AM - 1:00 PM</span
          >
        </label>
        <label
          class="inline-flex items-center p-2 bg-white rounded-xl shadow-sm hover:ring-2 hover:ring-indigo-500 transition cursor-pointer"
        >
          <input
            type="checkbox"
            name="timeSlot[]"
            value="2pm-4pm"
            class="form-checkbox text-indigo-600 h-5 w-5 rounded"
          />
          <span class="ml-2 text-sm font-medium text-gray-700"
            >2:00 PM - 4:00 PM</span
          >
        </label>
      </div>
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


    <script>
        // Function to toggle textareas for Health & Lifestyle Page (Step 3)
        function toggleDetail(detailId, isVisible) {
            const detailElement = document.getElementById(detailId);
            const textarea = detailElement.querySelector('textarea');
            if (isVisible) {
                detailElement.classList.remove('hidden');
                textarea.setAttribute('required', 'required');
            } else {
                detailElement.classList.add('hidden');
                textarea.removeAttribute('required');
                textarea.value = ''; 
            }
        }
        
        // Function to toggle time slot containers for Donor Availability Page (Step 4)
        function toggleTimeSlots(day, isChecked) {
            const timeSlotId = day + 'TimeSlots';
            const timeSlotContainer = document.getElementById(timeSlotId);
            if (isChecked) {
                timeSlotContainer.classList.remove('hidden');
            } else {
                timeSlotContainer.classList.add('hidden');
                // Uncheck all time slots for the removed day
                timeSlotContainer.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }

        // NAVIGATION FUNCTIONS
        
        // Step 1 -> Step 2
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
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
        document.getElementById('personalInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
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
        document.getElementById('healthLifestyleForm').addEventListener('submit', function(e) {
            e.preventDefault();
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
        
        // Step 4 -> Step 5 (Final Completion Page)
        document.getElementById('availabilityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation: Check if at least one day or time slot is selected (can be improved)
            const dateSelected = document.getElementById("availableDate").value;
            const selectedTimes = document.querySelectorAll('#timeSlotsContainer input[type="checkbox"]:checked').length;

            if (!dateSelected) {
            alert("Please select an available date.");
            return;
            }
            if (selectedTimes === 0) {
            alert("Please select at least one time slot.");
            return;
            }

            document.getElementById('donorAvailabilityPage').classList.add('hidden');
            document.getElementById('completePage').classList.remove('hidden');
            window.scrollTo(0, 0);
        });

            const dateInput = document.getElementById("availableDate");
            const dayDisplay = document.getElementById("dayDisplay");
            const timeSlotsContainer = document.getElementById("timeSlotsContainer");
            const selectedDayTitle = document.getElementById("selectedDayTitle");

            dateInput.addEventListener("change", function () {
                const dateValue = this.value;
                if (dateValue) {
                const selectedDate = new Date(dateValue + "T00:00:00");
                const options = { weekday: "long" };
                const dayName = selectedDate.toLocaleDateString("en-US", options);

                // Show day name
                dayDisplay.textContent = `You selected: ${dayName}`;
                dayDisplay.classList.remove("hidden");

                // Show corresponding time slots
                timeSlotsContainer.classList.remove("hidden");
                selectedDayTitle.textContent = `${dayName} Time Slots:`;
                } else {
                dayDisplay.classList.add("hidden");
                timeSlotsContainer.classList.add("hidden");
                }
            });
        // Removed goToDonorList and donorListPage as requested.
        
    </script>
</body>
</html>