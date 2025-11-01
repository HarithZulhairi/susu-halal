<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rahma Milk Bank - Shariah-Compliant Human Milk Sharing')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #1a5f7a;
            --secondary: #57cc99;
            --accent: #ffd166;
            --light: #f8f9fa;
            --dark: #343a40;
            --text: #333333;
            --white: #ffffff;
            --success: #2a9d8f;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--white);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: #155270;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .btn-secondary:hover {
            background: var(--white);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .section-title p {
            font-size: 1.1rem;
            color: var(--dark);
            max-width: 700px;
            margin: 0 auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
<nav style="background: var(--white); box-shadow: var(--shadow); position: sticky; top: 0; z-index: 1000;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0;">
        <a href="{{ url('/') }}" style="font-size: 1.5rem; font-weight: bold; color: var(--primary); text-decoration: none;">
            Rahma Milk Bank
        </a>

        <ul style="display: flex; list-style: none; gap: 25px; margin: 0;">
            <li><a href="{{ url('/') }}" style="text-decoration: none; color: var(--dark); font-weight: 500;">Home</a></li>
            <li><a href="#services" style="text-decoration: none; color: var(--dark); font-weight: 500;">Services</a></li>
            <li><a href="#about" style="text-decoration: none; color: var(--dark); font-weight: 500;">About</a></li>
            <li><a href="#contact" style="text-decoration: none; color: var(--dark); font-weight: 500;">Contact</a></li>

            @auth
                <li><a href="{{ url('/dashboard') }}" style="text-decoration: none; color: var(--primary); font-weight: 600;">Dashboard</a></li>
            @else
                <li><a href="{{ route('login') }}" style="text-decoration: none; color: var(--primary); font-weight: 600;">Login</a></li>
                <li><a href="{{ route('register') }}" style="text-decoration: none; color: var(--secondary); font-weight: 600;">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>
    <!-- Header -->
    <header style="background: linear-gradient(rgba(26, 95, 122, 0.9), rgba(26, 95, 122, 0.9)), url('https://images.unsplash.com/photo-1512295767273-ac109ac3ac1a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; color: var(--white); padding: 100px 0; text-align: center;">
        <div class="container">
            <h1 style="font-size: 3rem; margin-bottom: 20px; font-weight: 700;">Shariah-Compliant Human Milk Sharing</h1>
            <p style="font-size: 1.3rem; margin-bottom: 40px; max-width: 800px; margin-left: auto; margin-right: auto; opacity: 0.9;">
                Providing safe, screened donor milk to infants in need while upholding Islamic principles and values.
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary">Become a Donor</a>
                <a href="#" class="btn btn-secondary">Request Milk</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: var(--dark); color: var(--white); padding: 60px 0 20px;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                <div>
                    <h3 style="color: var(--secondary); margin-bottom: 20px; font-size: 1.5rem;">Rahma Milk Bank</h3>
                    <p>Providing Shariah-compliant human milk sharing services to support mothers and infants in need.</p>
                </div>
                <div>
                    <h4 style="color: var(--secondary); margin-bottom: 15px;">Quick Links</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 8px;"><a href="{{ url('/') }}" style="color: var(--white); text-decoration: none;">Home</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">About Us</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Services</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Fatwa</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: var(--secondary); margin-bottom: 15px;">Services</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Milk Donation</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Milk Request</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Screening Process</a></li>
                        <li style="margin-bottom: 8px;"><a href="#" style="color: var(--white); text-decoration: none;">Islamic Compliance</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: var(--secondary); margin-bottom: 15px;">Contact Us</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Islamic Center, Medina Road</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-phone"></i>
                            <span>+966 12 345 6789</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-envelope"></i>
                            <span>info@rahmamilk.org</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div style="text-align: center; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <p>&copy; 2023 Rahma Milk Bank. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>