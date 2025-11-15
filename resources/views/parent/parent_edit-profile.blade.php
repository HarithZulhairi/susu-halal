

@extends('layouts.shariah')

@section('title', 'Edit Profile')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shariah_edit-profile.css') }}">

<div class="main-content">
        <div class="edit-profile-layout">
            <!-- Left Sidebar -->
            <div class="profile-sidebar-card">
                <div class="profile-avatar-section">
                    <div class="avatar-circle">{{ strtoupper(substr($profile->name ?? 'DR', 0, 2)) }}</div>
                    <button class="avatar-edit-btn" title="Edit Avatar">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                
                <h2 class="profile-name">{{$profile->name}}</h2>
                <span class="profile-badge">Active Doctor</span>
                <p class="profile-member">Member since {{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('F Y') : 'N/A' }}</p>
                

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
            <form class="edit-profile-form" action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <!-- Personal Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $profile->name ?? '') }}" 
                                class="form-control @error('name') is-invalid @enderror" 
                                required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $profile->email ?? '') }}" 
                                class="form-control @error('email') is-invalid @enderror" 
                                required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="contact">Phone Number</label>
                            <input type="tel" 
                                id="contact" 
                                name="contact" 
                                value="{{ old('contact', $profile->contact ?? '') }}" 
                                class="form-control @error('contact') is-invalid @enderror">
                            @error('contact')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        @if(in_array($role, ['donor']))
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" 
                                id="dob" 
                                name="dob" 
                                value="{{ old('dob', $profile->dob ?? '') }}" 
                                class="form-control @error('dob') is-invalid @enderror">
                            @error('dob')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                        
                        <div class="form-group full-width">
                            <label for="address">Address</label>
                            <textarea id="address" 
                                    name="address" 
                                    class="form-control @error('address') is-invalid @enderror" 
                                    rows="2">{{ old('address', $profile->address ?? '') }}</textarea>
                            @error('address')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Information (Staff only) -->
                @if(in_array($role, ['doctor', 'nurse', 'lab_technician', 'shariah_advisor']))
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-graduation-cap"></i> Professional Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <input type="text" 
                                id="qualification" 
                                name="qualification" 
                                value="{{ old('qualification', $profile->qualification ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="certification">Certification</label>
                            <input type="text" 
                                id="certification" 
                                name="certification" 
                                value="{{ old('certification', $profile->certification ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="institution">Institution</label>
                            <input type="text" 
                                id="institution" 
                                name="institution" 
                                value="{{ old('institution', $profile->institution ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="specialization">Specialization</label>
                            <input type="text" 
                                id="specialization" 
                                name="specialization" 
                                value="{{ old('specialization', $profile->specialization ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="experience">Years of Experience</label>
                            <input type="number" 
                                id="experience" 
                                name="experience" 
                                value="{{ old('experience', $profile->experience ?? 0) }}" 
                                min="0" 
                                class="form-control">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Baby Information (Parent only) -->
                @if($role === 'parent')
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-baby"></i> Baby Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="baby_name">Baby Name</label>
                            <input type="text" 
                                id="baby_name" 
                                name="baby_name" 
                                value="{{ old('baby_name', $profile->baby_name ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="baby_dob">Date of Birth</label>
                            <input type="date" 
                                id="baby_dob" 
                                name="baby_dob" 
                                value="{{ old('baby_dob', $profile->baby_dob ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="baby_gender">Gender</label>
                            <select id="baby_gender" 
                                    name="baby_gender" 
                                    class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('baby_gender', $profile->baby_gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('baby_gender', $profile->baby_gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="baby_birth_weight">Birth Weight (kg)</label>
                            <input type="number" 
                                step="0.01" 
                                id="baby_birth_weight" 
                                name="baby_birth_weight" 
                                value="{{ old('baby_birth_weight', $profile->baby_birth_weight ?? '') }}" 
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="baby_current_weight">Current Weight (kg)</label>
                            <input type="number" 
                                step="0.01" 
                                id="baby_current_weight" 
                                name="baby_current_weight" 
                                value="{{ old('baby_current_weight', $profile->baby_current_weight ?? '') }}" 
                                class="form-control">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Health Information (Donor only) -->
                @if($role === 'donor')
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-heart-pulse"></i> Health Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="infection_risk">Infection/Disease Risk</label>
                            <textarea id="infection_risk" 
                                    name="infection_risk" 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="List any infection or disease risks...">{{ old('infection_risk', $profile->infection_risk ?? '') }}</textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="medication">Current Medication</label>
                            <textarea id="medication" 
                                    name="medication" 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="List any current medications...">{{ old('medication', $profile->medication ?? '') }}</textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="recent_illness">Recent Illness</label>
                            <textarea id="recent_illness" 
                                    name="recent_illness" 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="Describe any recent illnesses...">{{ old('recent_illness', $profile->recent_illness ?? '') }}</textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="dietary_alerts">Dietary Alerts</label>
                            <textarea id="dietary_alerts" 
                                    name="dietary_alerts" 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="List any dietary restrictions or alerts...">{{ old('dietary_alerts', $profile->dietary_alerts ?? '') }}</textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                    name="tobacco_alcohol" 
                                    value="1" 
                                    {{ old('tobacco_alcohol', $profile->tobacco_alcohol ?? false) ? 'checked' : '' }}>
                                <span class="checkbox-text">I consume tobacco or alcohol</span>
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Change Password -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-lock"></i> Change Password (Optional)
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" 
                                id="current_password" 
                                name="current_password" 
                                class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="form-helper">Leave blank if you don't want to change password</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" 
                                id="new_password_confirmation" 
                                name="new_password_confirmation" 
                                class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('profile.show') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection