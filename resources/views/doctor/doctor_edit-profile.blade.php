@extends('layouts.doctor')

@section('title', 'Edit Profile')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/doctor_edit-profile.css') }}">

    <div class="main-content">
        <div class="edit-profile-layout">
            <!-- Left Sidebar -->
            <div class="profile-sidebar-card">
                <div class="profile-avatar-section">
                    <div class="profile-avatar">SA</div>
                    <button class="avatar-edit-btn" title="Edit Avatar">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                
                <h2 class="profile-name">Aqila Asyikin</h2>
                <span class="profile-badge">Doctor</span>
                <p class="profile-member">Registered since January 2024</p>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value">18</div>
                        <div class="stat-label">DONATIONS</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-value">4.2L</div>
                        <div class="stat-label">TOTAL MILK</div>
                    </div>
                </div>

                <div class="health-status-card">
                    <div class="health-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="health-content">
                        <div class="health-title">Professional Status:</div>
                        <div class="health-value">Verified</div>
                        <div class="health-date">License verified: April 28, 2024</div>
                    </div>
                </div>
            </div>

            <!-- Right Form Area -->
            <div class="profile-form-container">
                <form class="edit-profile-form">
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i> Personal Information
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="fullName">Full Name <span class="required">*</span></label>
                                <input type="text" id="fullName" value="Aqila Asyikin" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" value="aqilaasyikin@email.com" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone" value="011-1341231" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="dob">Date of Birth <span class="required">*</span></label>
                                <input type="date" id="dob" value="1990-03-15" class="form-control">
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="address">Address <span class="required">*</span></label>
                                <textarea id="address" class="form-control" rows="2">123 Green Street, Medina City</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-briefcase"></i> Professional Information
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="licenseNumber">Medical License Number <span class="required">*</span></label>
                                <input type="text" id="licenseNumber" value="MD-2024-12345" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="yearsOfPractice">Years of Practice <span class="required">*</span></label>
                                <input type="number" id="yearsOfPractice" value="10" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="clinicHospital">Clinic/Hospital Affiliation</label>
                                <input type="text" id="clinicHospital" value="Medina Medical Center" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="position">Position/Title</label>
                                <input type="text" id="position" value="Shariah Committee Member" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Specialization Background -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap"></i> Specialization Background
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="specialization">Primary Specialization <span class="required">*</span></label>
                                <select id="specialization" class="form-control">
                                    <option value="">Select specialization</option>
                                    <option value="pediatrics">Pediatrics</option>
                                    <option value="obstetrics">Obstetrics & Gynecology</option>
                                    <option value="lactation" selected>Lactation Consultant</option>
                                    <option value="nutrition">Nutrition & Dietetics</option>
                                    <option value="neonatology">Neonatology</option>
                                    <option value="family">Family Medicine</option>
                                    <option value="shariah">Shariah Committee</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="secondarySpecialization">Secondary Specialization</label>
                                <select id="secondarySpecialization" class="form-control">
                                    <option value="">Select specialization (optional)</option>
                                    <option value="pediatrics">Pediatrics</option>
                                    <option value="obstetrics">Obstetrics & Gynecology</option>
                                    <option value="lactation">Lactation Consultant</option>
                                    <option value="nutrition">Nutrition & Dietetics</option>
                                    <option value="neonatology">Neonatology</option>
                                    <option value="family">Family Medicine</option>
                                    <option value="shariah" selected>Shariah Committee</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="qualifications">Qualifications & Certifications <span class="required">*</span></label>
                                <textarea id="qualifications" class="form-control" rows="3" placeholder="List your degrees, certifications, and professional qualifications...">MBBS, MD Pediatrics
International Board Certified Lactation Consultant (IBCLC)
Shariah Healthcare Ethics Certification</textarea>
                                <small class="form-helper">Include degrees, board certifications, and relevant training</small>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="expertise">Areas of Expertise <span class="required">*</span></label>
                                <textarea id="expertise" class="form-control" rows="3" placeholder="Describe your specific areas of expertise...">Breastfeeding support and lactation management
Islamic guidelines for milk donation and wet-nursing
Maternal and infant nutrition counseling
Shariah compliance in healthcare practices</textarea>
                                <small class="form-helper">Describe your specialized knowledge and experience</small>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="biography">Professional Biography</label>
                                <textarea id="biography" class="form-control" rows="4" placeholder="Write a brief professional biography...">Dr. Aqila Asyikin is a dedicated member of the Shariah Committee with over 10 years of experience in integrating Islamic healthcare principles with modern medical practices. She specializes in ensuring that milk donation programs comply with Islamic guidelines while maintaining the highest standards of care.</textarea>
                                <small class="form-helper">This will be displayed on your public profile (optional)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-phone-alt"></i> Emergency Contact
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="contactName">Contact Name <span class="required">*</span></label>
                                <input type="text" id="contactName" value="Ali Ahmad" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="relationship">Relationship <span class="required">*</span></label>
                                <select id="relationship" class="form-control">
                                    <option value="spouse" selected>Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="child">Child</option>
                                    <option value="friend">Friend</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="contactPhone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="contactPhone" value="+1 (555) 987-6543" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-sliders"></i> Preferences
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="primaryLocation">Primary Work Location</label>
                                <select id="primaryLocation" class="form-control">
                                    <option value="main" selected>Main Center</option>
                                    <option value="north">North Branch</option>
                                    <option value="south">South Branch</option>
                                    <option value="east">East Branch</option>
                                    <option value="west">West Branch</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="communication">Communication Preference</label>
                                <select id="communication" class="form-control">
                                    <option value="email" selected>Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="phone">Phone Call</option>
                                    <option value="whatsapp">WhatsApp</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label>Availability</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked>
                                        <span class="checkbox-text">Available for consultations</span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked>
                                        <span class="checkbox-text">Available for emergency reviews</span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked>
                                        <span class="checkbox-text">Receive case notifications</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <a href="{{ route('doctor.profile') }}" class="btn-save">
                            <i class="fas fa-save"></i> Save Changes
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection