<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rahma Milk Bank - Shariah-Compliant Human Milk Sharing')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    

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

        nav ul li a:hover {
        background-color: rgba(26, 95, 122, 0.1);
        color: var(--primary) !important;
        transform: translateY(-2px);
        }
        
        nav ul li a[href*="/dashboard"]:hover,
        nav ul li a[href*="login"]:hover {
            background-color: rgba(26, 95, 122, 0.15);
        }
        
        nav ul li a[href*="register"]:hover {
            background-color: rgba(87, 204, 153, 0.15);
            color: var(--secondary) !important;
        }
    </style>
    @stack('styles')
</head>
 
<body>

    <nav style="background: var(--white); box-shadow: var(--shadow); position: sticky; top: 0; z-index: 1000;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0;">
            <a href="{{ url('/') }}" style="display: flex; align-items: center;">
                <img src="{{ asset('images/hmmc_logo_clear.png') }}" 
                    alt="HALIMATUSSAADIA Mother's Milk Centre Logo" 
                    style="width: 200px; height: auto;">
            </a>

            <ul style="display: flex; list-style: none; gap: 25px; margin: 0;">
                <li>
                    <a href="{{ url('/') }}" 
                    style="text-decoration: none; color: var(--dark); font-weight: 500; padding: 8px 12px; border-radius: 4px; transition: var(--transition);">
                    Home
                    </a>
                </li>
                <li>
                    <a href="#services" 
                    style="text-decoration: none; color: var(--dark); font-weight: 500; padding: 8px 12px; border-radius: 4px; transition: var(--transition);">
                    Services
                    </a>
                </li>
                <li>
                    <a href="#about" 
                    style="text-decoration: none; color: var(--dark); font-weight: 500; padding: 8px 12px; border-radius: 4px; transition: var(--transition);">
                    About
                    </a>
                </li>
                <li>
                    <a href="#contact" 
                    style="text-decoration: none; color: var(--dark); font-weight: 500; padding: 8px 12px; border-radius: 4px; transition: var(--transition);">
                    Contact
                    </a>
                </li>

                
                    <li>
                        <a href="/register" 
                        style="text-decoration: none; color: var(--secondary); font-weight: 600; padding: 8px 16px; border-radius: 4px; transition: var(--transition);">
                        Become A Donor
                        </a>
                    </li>
                    <li>
                        <a href="/login" 
                        style="text-decoration: none; color: var(--primary); font-weight: 600; padding: 8px 16px; border-radius: 4px; transition: var(--transition);">
                        Login
                        </a>
                    </li>
                
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
                <a href="{{ route('register') }}" class="btn btn-primary">Become a Donor</a>
                <a href="#" class="btn btn-secondary">Request Milk</a>
            </div>
        </div>
    </header>
    <!-- Services Section -->
    <section class="section" style="background: var(--light);">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>We provide comprehensive milk banking services in accordance with Islamic principles</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div style="background: var(--white); padding: 40px; border-radius: 10px; text-align: center; box-shadow: var(--shadow); transition: var(--transition);">
                    <div style="width: 80px; height: 80px; background: rgba(26, 95, 122, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-hand-holding-heart" style="font-size: 2rem; color: var(--primary);"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 15px; font-size: 1.5rem;">Milk Donation</h3>
                    <p style="color: var(--dark);">Safely donate your excess breast milk to help infants in need while following Islamic guidelines.</p>
                </div>
                
                <div style="background: var(--white); padding: 40px; border-radius: 10px; text-align: center; box-shadow: var(--shadow); transition: var(--transition);">
                    <div style="width: 80px; height: 80px; background: rgba(87, 204, 153, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-user-md" style="font-size: 2rem; color: var(--secondary);"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 15px; font-size: 1.5rem;">Medical Screening</h3>
                    <p style="color: var(--dark);">Comprehensive health screening for all donors to ensure milk safety and quality.</p>
                </div>
                
                <div style="background: var(--white); padding: 40px; border-radius: 10px; text-align: center; box-shadow: var(--shadow); transition: var(--transition);">
                    <div style="width: 80px; height: 80px; background: rgba(255, 209, 102, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-heart" style="font-size: 2rem; color: var(--accent);"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 15px; font-size: 1.5rem;">No Cost Service</h3>
                    <p style="color: var(--dark);">Free access to donor milk for families in medical need, supported by charitable contributions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>How It Works</h2>
                <p>Our simple process ensures safety and compliance with Islamic principles</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div style="text-align: center; padding: 30px;">
                    <div style="width: 60px; height: 60px; background: var(--primary); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 20px;">1</div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Screening</h3>
                    <p style="color: var(--dark);">Donors undergo comprehensive health screening and Islamic compliance verification.</p>
                </div>
                
                <div style="text-align: center; padding: 30px;">
                    <div style="width: 60px; height: 60px; background: var(--primary); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 20px;">2</div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Collection</h3>
                    <p style="color: var(--dark);">Milk is collected following strict hygiene protocols and Islamic guidelines.</p>
                </div>
                
                <div style="text-align: center; padding: 30px;">
                    <div style="width: 60px; height: 60px; background: var(--primary); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 20px;">3</div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Processing</h3>
                    <p style="color: var(--dark);">Milk is pasteurized, tested, and stored according to medical and Shariah standards.</p>
                </div>
                
                <div style="text-align: center; padding: 30px;">
                    <div style="width: 60px; height: 60px; background: var(--primary); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 20px;">4</div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Distribution</h3>
                    <p style="color: var(--dark);">Milk is distributed to recipients with consideration of Islamic mahram relationships.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Islamic Compliance Section -->
    <section class="section" style="background: var(--light);">
        <div class="container">
            <div class="section-title">
                <h2>Islamic Compliance</h2>
                <p>Our services are designed in accordance with Islamic principles and fatwas</p>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center;">
                <div>
                    <h3 style="color: var(--primary); margin-bottom: 20px; font-size: 1.8rem;">Shariah-Compliant Milk Sharing</h3>
                    <p style="margin-bottom: 30px; color: var(--dark);">We follow Islamic guidelines regarding milk kinship (radāʿah) to ensure all relationships are properly established according to Shariah.</p>
                    
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; gap: 15px;">
                            <div style="min-width: 30px;">
                                <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--primary); margin-bottom: 5px;">Milk Kinship Consideration</h4>
                                <p style="color: var(--dark);">We carefully track donor-recipient relationships to establish proper mahram status.</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 15px;">
                            <div style="min-width: 30px;">
                                <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--primary); margin-bottom: 5px;">Approved by Islamic Scholars</h4>
                                <p style="color: var(--dark);">Our processes have been reviewed and approved by a board of qualified Islamic scholars.</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 15px;">
                            <div style="min-width: 30px;">
                                <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--primary); margin-bottom: 5px;">Transparent Operations</h4>
                                <p style="color: var(--dark);">We maintain complete transparency in our operations to ensure trust and compliance.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div style="background: var(--white); padding: 40px; border-radius: 10px; box-shadow: var(--shadow);">
                        <img src="https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Islamic Compliance" style="width: 100%; border-radius: 8px; margin-bottom: 20px;">
                        <h4 style="color: var(--primary); text-align: center;">Certified Shariah Compliance</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>What Families Say</h2>
                <p>Hear from mothers who have benefited from our Shariah-compliant milk sharing services</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow); position: relative;">
                    <div style="position: absolute; top: 20px; left: 20px; font-size: 4rem; color: rgba(26, 95, 122, 0.1);">"</div>
                    <p style="font-style: italic; margin-bottom: 20px; position: relative; z-index: 1;">The medical team at Rahma provided excellent support throughout the process. Their understanding of both medical requirements and Islamic law is impressive.</p>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: bold;">DM</div>
                        <div>
                            <h4 style="color: var(--primary); margin-bottom: 5px;">Dr. Sarah Malik</h4>
                            <p style="color: var(--dark); font-size: 0.9rem;">Pediatrician</p>
                        </div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow); position: relative;">
                    <div style="position: absolute; top: 20px; left: 20px; font-size: 4rem; color: rgba(26, 95, 122, 0.1);">"</div>
                    <p style="font-style: italic; margin-bottom: 20px; position: relative; z-index: 1;">Donating my excess milk through Rahma gave me peace of mind knowing it would help other babies while following Islamic principles. The screening process was thorough yet respectful.</p>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: bold;">FA</div>
                        <div>
                            <h4 style="color: var(--primary); margin-bottom: 5px;">Fatima Ahmed</h4>
                            <p style="color: var(--dark); font-size: 0.9rem;">Milk Donor</p>
                        </div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow); position: relative;">
                    <div style="position: absolute; top: 20px; left: 20px; font-size: 4rem; color: rgba(26, 95, 122, 0.1);">"</div>
                    <p style="font-style: italic; margin-bottom: 20px; position: relative; z-index: 1;">As a Muslim mother who couldn't breastfeed, finding Rahma Milk Bank was a blessing. They not only provided safe milk but also ensured Islamic compliance.</p>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: bold;">AR</div>
                        <div>
                            <h4 style="color: var(--primary); margin-bottom: 5px;">Aisha Rahman</h4>
                            <p style="color: var(--dark); font-size: 0.9rem;">Recipient Mother</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section" style="background: var(--light);">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Common questions about Shariah-compliant milk banking</p>
            </div>
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background: var(--white); border-radius: 10px; overflow: hidden; box-shadow: var(--shadow);">
                    <div style="padding: 25px 30px; border-bottom: 1px solid #eee; cursor: pointer; transition: var(--transition);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="color: var(--primary);">How does milk kinship work in Islam?</h4>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                    </div>
                    
                    <div style="padding: 25px 30px; border-bottom: 1px solid #eee; cursor: pointer; transition: var(--transition);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="color: var(--primary);">Is donor milk permissible in Islam?</h4>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                    </div>
                    
                    <div style="padding: 25px 30px; border-bottom: 1px solid #eee; cursor: pointer; transition: var(--transition);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="color: var(--primary);">How do you ensure Shariah compliance?</h4>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                    </div>
                    
                    <div style="padding: 25px 30px; cursor: pointer; transition: var(--transition);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="color: var(--primary);">Who can receive donor milk?</h4>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: var(--white); text-align: center;">
        <div class="container">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;">Join Our Community of Care</h2>
            <p style="font-size: 1.2rem; margin-bottom: 40px; max-width: 700px; margin-left: auto; margin-right: auto;">
                Whether you need milk for your infant or want to donate your excess milk, we're here to help in a Shariah-compliant manner.
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="#" class="btn" style="background: var(--white); color: var(--primary);">Become a Donor</a>
                <a href="#" class="btn" style="background: transparent; color: var(--white); border: 2px solid var(--white);">Request Milk</a>
            </div>
        </div>
    </section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ toggle functionality
        const faqItems = document.querySelectorAll('#faq-section > div');
        faqItems.forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
    });
</script>
@endpush

<!-- Footer -->
    <footer style="background: var(--dark); color: var(--white); padding: 60px 0 20px;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                <div style="text-align: left;">
    <img src="{{ asset('images/hmmc_logo_clear.png') }}" 
         alt="HALIMATUSSAADIA Mother's Milk Centre Logo" 
         style="width: 270px; height: auto; margin-bottom: 15px;">
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
                <p>&copy; 2025 Halimatus Saadia Mother's Milk Centre. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>