@extends('layouts.app')

@section('title', 'Rahma Milk Bank - Shariah-Compliant Human Milk Sharing')

@section('content')
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
@endsection

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