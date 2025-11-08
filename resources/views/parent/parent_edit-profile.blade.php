@extends('layouts.parent')

@section('title', 'Edit Profile')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/parent_edit-profile.css') }}">

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
                
                <h2 class="profile-name">Sarah Ahmad</h2>
                <span class="profile-badge">Parent</span>
                <p class="profile-member">Member since January 2024</p>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value">18</div>
                        <div class="stat-label">TOTAL DONATIONS</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-value">4.2L</div>
                        <div class="stat-label">TOTAL MILK</div>
                    </div>
                </div>

                <div class="health-status-card">
                    <div class="health-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="health-content">
                        <div class="health-title">Health Status:</div>
                        <div class="health-value">Excellent</div>
                        <div class="health-date">Last screening: April 28, 2024</div>
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
                                <input type="text" id="fullName" value="Sarah Ahmed" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" value="sarah.ahmed@gmail.com" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone" value="+1 (555) 123-4567" class="form-control">
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

                    <!-- Emergency Contact -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-phone-alt"></i> Emergency Contact
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="contactName">Contact Name <span class="required">*</span></label>
                                <input type="text" id="contactName" value="Ali Ahmed" class="form-control">
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

                    <!-- Health Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-heart-pulse"></i> Health Information
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="bloodType">Blood Type</label>
                                <select id="bloodType" class="form-control">
                                    <option value="a+" selected>A+</option>
                                    <option value="a-">A-</option>
                                    <option value="b+">B+</option>
                                    <option value="b-">B-</option>
                                    <option value="ab+">AB+</option>
                                    <option value="ab-">AB-</option>
                                    <option value="o+">O+</option>
                                    <option value="o-">O-</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="babyAge">Baby's Age</label>
                                <input type="text" id="babyAge" value="6 months" class="form-control">
                                <small class="form-helper">Age of your breastfeeding baby</small>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="healthConditions">Health Conditions</label>
                                <textarea id="healthConditions" class="form-control" rows="3" placeholder="List any relevant health conditions or medications..."></textarea>
                                <small class="form-helper">Please disclose any conditions that might affect milk donation</small>
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
                                <label for="donationCenter">Preferred Donation Center</label>
                                <select id="donationCenter" class="form-control">
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
                                <label>Notification Preferences</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked>
                                        <span class="checkbox-text">Appointment reminders</span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked>
                                        <span class="checkbox-text">Health screening updates</span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox">
                                        <span class="checkbox-text">Newsletter and updates</span>
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
                        <a href="{{ route('parent.profile') }}" class="btn-save">
    <i class="fas fa-save"></i> Save Changes
</a>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection