@extends('layouts.hmmc')

@section('title', 'Create New User')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hmmc_create-new-user.css') }}">

    <div class="main-content">
        <div class="page-header">
            <h1>Create New User</h1>
        </div>

        <div class="create-user-container">
            <form class="create-user-form">
                <!-- Role Badge -->
                <div class="role-badge-section">
                    <div class="selected-role-badge {{ $role ?? 'parent' }}">
                        <i class="fas fa-{{ getRoleIcon($role ?? 'parent') }}"></i>
                        <span>{{ ucfirst($role ?? 'parent') }}</span>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fullName">Full Name <span class="required">*</span></label>
                            <input type="text" id="fullName" class="form-control" placeholder="Enter full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" class="form-control" placeholder="example@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" class="form-control" placeholder="+1 (555) 123-4567">
                        </div>
                        
                        <div class="form-group">
                            <label for="dob">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="dob" class="form-control">
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="address">Address <span class="required">*</span></label>
                            <textarea id="address" class="form-control" rows="2" placeholder="Enter full address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Professional Information (Hidden for Parent) -->
                <div class="form-section" id="professionalSection" style="display: {{ ($role ?? 'parent') === 'parent' ? 'none' : 'block' }}">
                    <h3 class="section-title">
                        <i class="fas fa-briefcase"></i> Professional Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="licenseNumber">License Number <span class="required">*</span></label>
                            <input type="text" id="licenseNumber" class="form-control" placeholder="Enter license number">
                        </div>
                        
                        <div class="form-group">
                            <label for="yearsOfPractice">Years of Practice <span class="required">*</span></label>
                            <input type="number" id="yearsOfPractice" class="form-control" placeholder="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="department">Department/Unit</label>
                            <select id="department" class="form-control">
                                <option value="">Select department</option>
                                <option value="pediatrics">Pediatrics</option>
                                <option value="nicu">NICU</option>
                                <option value="maternity">Maternity</option>
                                <option value="lactation">Lactation Services</option>
                                <option value="laboratory">Laboratory</option>
                                <option value="shariah">Shariah Committee</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="position">Position/Title</label>
                            <input type="text" id="position" class="form-control" placeholder="Enter position">
                        </div>
                    </div>
                </div>

                <!-- Specialization Background (Hidden for Parent) -->
                <div class="form-section" id="specializationSection" style="display: {{ ($role ?? 'parent') === 'parent' ? 'none' : 'block' }}">
                    <h3 class="section-title">
                        <i class="fas fa-graduation-cap"></i> Specialization Background
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="specialization">Primary Specialization <span class="required">*</span></label>
                            <select id="specialization" class="form-control">
                                <option value="">Select specialization</option>
                                <option value="pediatrics">Pediatrics</option>
                                <option value="neonatology">Neonatology</option>
                                <option value="lactation">Lactation Consultant</option>
                                <option value="nutrition">Nutrition & Dietetics</option>
                                <option value="nursing">Nursing</option>
                                <option value="laboratory">Laboratory Science</option>
                                <option value="shariah">Shariah Healthcare</option>
                                <option value="obstetrics">Obstetrics & Gynecology</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="secondarySpecialization">Secondary Specialization</label>
                            <select id="secondarySpecialization" class="form-control">
                                <option value="">Select (optional)</option>
                                <option value="pediatrics">Pediatrics</option>
                                <option value="neonatology">Neonatology</option>
                                <option value="lactation">Lactation Consultant</option>
                                <option value="nutrition">Nutrition & Dietetics</option>
                                <option value="nursing">Nursing</option>
                                <option value="laboratory">Laboratory Science</option>
                                <option value="shariah">Shariah Healthcare</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="qualifications">Qualifications & Certifications <span class="required">*</span></label>
                            <textarea id="qualifications" class="form-control" rows="3" placeholder="List degrees, certifications, and professional qualifications..."></textarea>
                            <small class="form-helper">Include degrees, board certifications, and relevant training</small>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="expertise">Areas of Expertise <span class="required">*</span></label>
                            <textarea id="expertise" class="form-control" rows="3" placeholder="Describe specific areas of expertise..."></textarea>
                            <small class="form-helper">Describe specialized knowledge and experience</small>
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
                            <input type="text" id="contactName" class="form-control" placeholder="Enter contact name">
                        </div>
                        
                        <div class="form-group">
                            <label for="relationship">Relationship <span class="required">*</span></label>
                            <select id="relationship" class="form-control">
                                <option value="">Select relationship</option>
                                <option value="spouse">Spouse</option>
                                <option value="parent">Parent</option>
                                <option value="sibling">Sibling</option>
                                <option value="child">Child</option>
                                <option value="friend">Friend</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="contactPhone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="contactPhone" class="form-control" placeholder="+1 (555) 987-6543">
                        </div>
                    </div>
                </div>

                <!-- Health Information (For Parent only) -->
                <div class="form-section" id="healthSection" style="display: {{ ($role ?? 'parent') === 'parent' ? 'block' : 'none' }}">
                    <h3 class="section-title">
                        <i class="fas fa-heart-pulse"></i> Health Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bloodType">Blood Type</label>
                            <select id="bloodType" class="form-control">
                                <option value="">Select blood type</option>
                                <option value="a+">A+</option>
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
                            <input type="text" id="babyAge" class="form-control" placeholder="e.g., 6 months">
                            <small class="form-helper">Age of breastfeeding baby</small>
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
                            <label for="location">{{ ($role ?? 'parent') === 'parent' ? 'Preferred Center' : 'Primary Work Location' }}</label>
                            <select id="location" class="form-control">
                                <option value="">Select location</option>
                                <option value="main">Main Center</option>
                                <option value="north">North Branch</option>
                                <option value="south">South Branch</option>
                                <option value="east">East Branch</option>
                                <option value="west">West Branch</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="communication">Communication Preference</label>
                            <select id="communication" class="form-control">
                                <option value="">Select preference</option>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="phone">Phone Call</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-key"></i> Account Settings
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username">Username <span class="required">*</span></label>
                            <input type="text" id="username" class="form-control" placeholder="Enter username">
                            <small class="form-helper">Used for system login</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tempPassword">Temporary Password <span class="required">*</span></label>
                            <input type="password" id="tempPassword" class="form-control" placeholder="Enter temporary password">
                            <small class="form-helper">User will be required to change on first login</small>
                        </div>
                        
                        <div class="form-group full-width">
                            <label>Account Status</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="status" value="active" checked>
                                    <span>Active - User can login immediately</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="status" value="pending">
                                    <span>Pending - Requires approval before login</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('hmmc.manage-users') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-create">
                        <i class="fas fa-check"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
    function getRoleIcon($role) {
        $icons = [
            'parent' => 'baby',
            'shariah' => 'book-quran',
            'nurse' => 'user-nurse',
            'clinician' => 'stethoscope',
            'lab-tech' => 'flask'
        ];
        return $icons[$role] ?? 'user';
    }
    @endphp
@endsection