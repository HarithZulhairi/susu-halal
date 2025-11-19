@extends('layouts.hmmc')

@section('title', 'Edit User')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/admin_edit-user.css') }}">

@if(session('success'))
<div id="success-toast" class="toast-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="main-content">
    <div class="page-header">
        <h1>Edit {{ ucfirst($role) }} User</h1>
        <div class="breadcrumb">
            <a href="{{ route('hmmc.manage-users') }}"><i class="fas fa-users"></i> User Management</a> / 
            <a href="{{ route('hmmc.users.show', ['role' => $role, 'id' => $user->id]) }}">View User</a> / 
            Edit User
        </div>
    </div>

    <div class="create-user-container">
        <form class="create-user-form" method="POST" action="{{ route('hmmc.users.update', ['role' => $role, 'id' => $user->id]) }}">
            @csrf
            @method('PUT')

            <!-- Role Badge Section -->
            <div class="role-badge-section">
                <div class="selected-role-badge {{ $role }}">
                    <i class="fas fa-{{ getRoleIcon($role) }}"></i>
                    <span>{{ ucfirst($role) }}</span>
                </div>
                <button type="button" class="btn-add-user" onclick="openRoleModal()">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>

            {{-- ====================== PERSONAL INFORMATION ====================== --}}
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-user"></i> Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name" required value="{{ old('name', $user->name) }}">
                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="example@email.com" required value="{{ old('email', $user->email) }}">
                        @error('email')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="tel" name="contact" id="contact" class="form-control" placeholder="e.g., +60 12-3456789" value="{{ old('contact', $user->contact) }}">
                        @error('contact')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Gender Field for roles that require it --}}
                    @if(in_array($role, ['admin']))
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    {{-- NRIC for all roles except some --}}
                    @if(in_array($role, ['admin','doctor','labtech','shariah','nurse','parent']))
                    <div class="form-group">
                        <label for="nric">NRIC</label>
                        <input type="text" name="nric" id="nric" class="form-control" placeholder="Enter NRIC" value="{{ old('nric', $user->nric) }}">
                        @error('nric')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Enter full address">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ====================== ROLE-SPECIFIC FIELDS ====================== --}}

            {{-- PARENT SPECIFIC FIELDS --}}
            @if($role === 'parent')
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-baby"></i> Baby Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="baby_name">Baby Name</label>
                        <input type="text" name="baby_name" id="baby_name" class="form-control" placeholder="Enter baby's name" value="{{ old('baby_name', $user->baby_name) }}">
                        @error('baby_name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="baby_dob">Baby Date of Birth</label>
                        <input type="date" name="baby_dob" id="baby_dob" class="form-control" value="{{ old('baby_dob', $user->baby_dob) }}">
                        @error('baby_dob')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="baby_gender">Baby Gender</label>
                        <select name="baby_gender" id="baby_gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('baby_gender', $user->baby_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('baby_gender', $user->baby_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('baby_gender')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="baby_birth_weight">Baby Birth Weight (kg)</label>
                        <input type="number" step="0.01" name="baby_birth_weight" id="baby_birth_weight" class="form-control" value="{{ old('baby_birth_weight', $user->baby_birth_weight) }}">
                        @error('baby_birth_weight')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="baby_current_weight">Baby Current Weight (kg)</label>
                        <input type="number" step="0.01" name="baby_current_weight" id="baby_current_weight" class="form-control" value="{{ old('baby_current_weight', $user->baby_current_weight) }}">
                        @error('baby_current_weight')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            {{-- PROFESSIONAL FIELDS FOR MEDICAL STAFF --}}
            @if(in_array($role, ['doctor','nurse','labtech','shariah']))
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-briefcase"></i> Professional Information</h3>
                <div class="form-grid">
                    {{-- Institution --}}
                    <div class="form-group">
                        <label for="institution">Institution</label>
                        <input type="text" name="institution" id="institution" class="form-control" placeholder="Enter institution name" value="{{ old('institution', $user->institution) }}">
                        @error('institution')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Qualification --}}
                    <div class="form-group">
                        <label for="qualification">Qualification</label>
                        <input type="text" name="qualification" id="qualification" class="form-control" placeholder="Enter qualification" value="{{ old('qualification', $user->qualification) }}">
                        @error('qualification')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Certification --}}
                    <div class="form-group">
                        <label for="certification">Certification</label>
                        <input type="text" name="certification" id="certification" class="form-control" placeholder="Enter certification" value="{{ old('certification', $user->certification) }}">
                        @error('certification')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Specialization --}}
                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <input type="text" name="specialization" id="specialization" class="form-control" placeholder="Enter specialization" value="{{ old('specialization', $user->specialization) }}">
                        @error('specialization')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Years of Experience --}}
                    <div class="form-group">
                        <label for="experience">Years of Experience</label>
                        <input type="number" name="experience" id="experience" class="form-control" placeholder="0" value="{{ old('experience', $user->experience) }}">
                        @error('experience')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            {{-- HEALTH INFORMATION FOR DONORS --}}
            @if($role == 'donor')
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-heartbeat"></i> Health Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user->dob) }}">
                        @error('dob')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label for="infection_risk">Infection Disease Risk</label>
                        <textarea name="infection_risk" id="infection_risk" class="form-control" rows="2">{{ old('infection_risk', $user->infection_risk) }}</textarea>
                        @error('infection_risk')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label for="medication">Current Medication</label>
                        <textarea name="medication" id="medication" class="form-control" rows="2">{{ old('medication', $user->medication) }}</textarea>
                        @error('medication')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label for="recent_illness">Recent Illness</label>
                        <textarea name="recent_illness" id="recent_illness" class="form-control" rows="2">{{ old('recent_illness', $user->recent_illness) }}</textarea>
                        @error('recent_illness')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label for="dietary_alerts">Dietary Alerts</label>
                        <textarea name="dietary_alerts" id="dietary_alerts" class="form-control" rows="2">{{ old('dietary_alerts', $user->dietary_alerts) }}</textarea>
                        @error('dietary_alerts')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="tobacco_alcohol" name="tobacco_alcohol" value="1" {{ old('tobacco_alcohol', $user->tobacco_alcohol) ? 'checked' : '' }} class="form-checkbox">
                            <label for="tobacco_alcohol">Uses Tobacco/Alcohol</label>
                        </div>
                        @error('tobacco_alcohol')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            {{-- ====================== ACCOUNT SETTINGS ====================== --}}
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-key"></i> Account Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" value="{{ old('username', $user->username) }}">
                        @error('username')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">New Password (Optional)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
                        <small class="form-helper">Minimum 6 characters</small>
                        @error('password')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password">
                        @error('password_confirmation')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ====================== SUBMIT ====================== --}}
            <div class="form-actions">
                <a href="{{ url()->previous() }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-create">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Role Selection Modal -->
<div id="roleModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-title">Select User Role</h2>
        <p class="modal-subtitle">Choose the type of user you want to create</p>
        
        <div class="role-cards">
            <a href="{{ route('hmmc.create-new-user', ['role' => 'admin']) }}" class="role-card">
                <div class="role-card-content">
                    <div class="role-icon blue">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>HMMC Admin</h3>
                    <p>System administrator</p>
                </div>
            </a>

            <a href="{{ route('hmmc.create-new-user', ['role' => 'doctor']) }}" class="role-card">
                <div class="role-card-content">
                    <div class="role-icon green">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3>Doctor</h3>
                    <p>Medical practitioner</p>
                </div>
            </a>

            <a href="{{ route('hmmc.create-new-user', ['role' => 'nurse']) }}" class="role-card">
                <div class="role-card-content">
                    <div class="role-icon teal">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <h3>Nurse</h3>
                    <p>Healthcare professional</p>
                </div>
            </a>

            <a href="{{ route('hmmc.create-new-user', ['role' => 'labtech']) }}" class="role-card">
                <div class="role-card-content">
                    <div class="role-icon purple">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h3>Lab Technician</h3>
                    <p>Laboratory specialist</p>
                </div>
            </a>

            <a href="{{ route('hmmc.create-new-user', ['role' => 'shariah']) }}" class="role-card">
                <div class="role-card-content">
                    <div class="role-icon orange">
                        <i class="fas fa-book-quran"></i>
                    </div>
                    <h3>Shariah Committee</h3>
                    <p>Islamic compliance expert</p>
                </div>
            </a>

            <a href="{{ route('hmmc.create-new-user', ['role' => 'parent']) }}" class="role-card">
                <div class="role-card-content">
                    <div class="role-icon pink">
                        <i class="fas fa-baby"></i>
                    </div>
                    <h3>Parent</h3>
                    <p>Milk recipient</p>
                </div>
            </a>
        </div>

        <button class="btn-cancel-modal" onclick="closeRoleModal()">
            <i class="fas fa-times"></i> Cancel
        </button>
    </div>
</div>

@php
function getRoleIcon($role) {
    return [
        'doctor' => 'stethoscope',
        'nurse' => 'user-nurse',
        'clinician' => 'user-doctor',
        'labtech' => 'flask',
        'shariah' => 'book-quran',
        'admin' => 'user-shield',
        'parent' => 'child',
        'donor' => 'hand-holding-heart'
    ][$role] ?? 'user';
}
@endphp

<script>
    // Modal Functions
    function openRoleModal() {
        document.getElementById('roleModal').style.display = 'flex';
    }

    function closeRoleModal() {
        document.getElementById('roleModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const roleModal = document.getElementById('roleModal');
        if (event.target === roleModal) closeRoleModal();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('success-toast');
        if(toast){
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Add real-time validation feedback
        const inputs = document.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.classList.add('error-input');
                } else {
                    this.classList.remove('error-input');
                }
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strengthIndicator = document.getElementById('password-strength') || createStrengthIndicator();
                
                if (password.length === 0) {
                    strengthIndicator.textContent = '';
                    strengthIndicator.className = 'password-strength';
                    return;
                }
                
                let strength = 0;
                if (password.length >= 6) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/\d/)) strength++;
                if (password.match(/[^a-zA-Z\d]/)) strength++;
                
                const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'][strength];
                const strengthClass = ['very-weak', 'weak', 'fair', 'good', 'strong'][strength];
                
                strengthIndicator.textContent = `Strength: ${strengthText}`;
                strengthIndicator.className = `password-strength ${strengthClass}`;
            });
            
            function createStrengthIndicator() {
                const indicator = document.createElement('div');
                indicator.id = 'password-strength';
                indicator.className = 'password-strength';
                passwordInput.parentNode.appendChild(indicator);
                return indicator;
            }
        }

        // Form change detection
        const form = document.querySelector('form');
        let formChanged = false;
        
        const formInputs = form.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            const originalValue = input.value;
            input.addEventListener('input', () => {
                formChanged = input.value !== originalValue;
            });
        });
        
        window.addEventListener('beforeunload', (e) => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        form.addEventListener('submit', () => {
            formChanged = false;
        });
    });
</script>
@endsection