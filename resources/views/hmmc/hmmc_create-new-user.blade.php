@extends('layouts.hmmc')

@section('title', 'Create New User')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/hmmc_create-new-user.css') }}">

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
        <h1>Create New {{ ucfirst($role) }} User</h1>
    </div>

    <div class="create-user-container">
        <form class="create-user-form" method="POST" action="{{ route('hmmc.users.store') }}">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">

    <!-- Role Badge and Change Role Button -->
    <div class="role-badge-section">
        <div class="selected-role-badge {{ $role }}">
            <i class="fas fa-{{ getRoleIcon($role) }}"></i>
            <span>{{ ucfirst($role) }}</span>
        </div>
        <button class="btn-change-role" onclick="openRoleModal()" title="Change Role">
            <i class="fas fa-exchange-alt"></i>
        </button>
    </div>

            {{-- ====================== PERSONAL INFORMATION ====================== --}}
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-user"></i> Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name" required value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="example@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number <span class="required">*</span></label>
                        <input type="tel" name="contact" id="contact" class="form-control" placeholder="e.g., +60 12-3456789" required value="{{ old('contact') }}">
                    </div>

                    {{-- Gender Field for roles that require it --}}
                    @if(in_array($role, ['admin']))
                    <div class="form-group">
                        <label for="gender">Gender <span class="required">*</span></label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    @endif

                    {{-- NRIC for all roles except some --}}
                    @if(in_array($role, ['admin','doctor','labtech','shariah','nurse','parent']))
                    <div class="form-group">
                        <label for="nric">NRIC <span class="required">*</span></label>
                        <input type="text" name="nric" id="nric" class="form-control" placeholder="Enter NRIC" required value="{{ old('nric') }}">
                    </div>
                    @endif

                    <div class="form-group full-width">
                        <label for="address">Address <span class="required">*</span></label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Enter full address" required>{{ old('address') }}</textarea>
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
                        <label for="baby_name">Baby Name <span class="required">*</span></label>
                        <input type="text" name="baby_name" id="baby_name" class="form-control" placeholder="Enter baby's name" required value="{{ old('baby_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="baby_dob">Baby Date of Birth <span class="required">*</span></label>
                        <input type="date" name="baby_dob" id="baby_dob" class="form-control" required value="{{ old('baby_dob') }}">
                    </div>
                    <div class="form-group">
                        <label for="baby_gender">Baby Gender <span class="required">*</span></label>
                        <select name="baby_gender" id="baby_gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('baby_gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('baby_gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="baby_birth_weight">Baby Birth Weight (kg) <span class="required">*</span></label>
                        <input type="number" step="0.01" name="baby_birth_weight" id="baby_birth_weight" class="form-control" required value="{{ old('baby_birth_weight') }}">
                    </div>
                    <div class="form-group">
                        <label for="baby_current_weight">Baby Current Weight (kg) <span class="required">*</span></label>
                        <input type="number" step="0.01" name="baby_current_weight" id="baby_current_weight" class="form-control" required value="{{ old('baby_current_weight') }}">
                    </div>
                </div>
            </div>
            @endif

            {{-- PROFESSIONAL FIELDS FOR MEDICAL STAFF --}}
            @if(in_array($role, ['doctor','nurse','labtech','shariah']))
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-briefcase"></i> Professional Information</h3>
                <div class="form-grid">
                    {{-- Institution (required for doctor, nurse, shariah) --}}
                    @if(in_array($role, ['doctor','nurse','shariah']))
                    <div class="form-group">
                        <label for="institution">Institution <span class="required">*</span></label>
                        <input type="text" name="institution" id="institution" class="form-control" placeholder="Enter institution name" required value="{{ old('institution') }}">
                    </div>
                    @elseif($role === 'labtech')
                    <div class="form-group">
                        <label for="institution">Institution</label>
                        <input type="text" name="institution" id="institution" class="form-control" placeholder="Enter institution name" value="{{ old('institution') }}">
                    </div>
                    @endif

                    {{-- Qualification (required for all professional roles) --}}
                    <div class="form-group">
                        <label for="qualification">Qualification 
                            @if(in_array($role, ['doctor','nurse','shariah']))<span class="required">*</span>@endif
                        </label>
                        <input type="text" name="qualification" id="qualification" class="form-control" placeholder="Enter qualification" 
                            @if(in_array($role, ['doctor','nurse','shariah'])) required @endif value="{{ old('qualification') }}">
                    </div>

                    {{-- Certification (required for doctor, nurse, shariah) --}}
                    @if(in_array($role, ['doctor','nurse','shariah']))
                    <div class="form-group">
                        <label for="certification">Certification <span class="required">*</span></label>
                        <input type="text" name="certification" id="certification" class="form-control" placeholder="Enter certification" required value="{{ old('certification') }}">
                    </div>
                    @elseif($role === 'labtech')
                    <div class="form-group">
                        <label for="certification">Certification</label>
                        <input type="text" name="certification" id="certification" class="form-control" placeholder="Enter certification" value="{{ old('certification') }}">
                    </div>
                    @endif

                    {{-- Specialization (required for all professional roles) --}}
                    <div class="form-group">
                        <label for="specialization">Specialization 
                            @if(in_array($role, ['doctor','nurse','shariah']))<span class="required">*</span>@endif
                        </label>
                        <input type="text" name="specialization" id="specialization" class="form-control" placeholder="Enter specialization" 
                            @if(in_array($role, ['doctor','nurse','shariah'])) required @endif value="{{ old('specialization') }}">
                    </div>

                    {{-- Years of Experience (required for all professional roles) --}}
                    <div class="form-group">
                        <label for="experience">Years of Experience 
                            @if(in_array($role, ['doctor','nurse','shariah']))<span class="required">*</span>@endif
                        </label>
                        <input type="number" name="experience" id="experience" class="form-control" placeholder="0" 
                            @if(in_array($role, ['doctor','nurse','shariah'])) required @endif value="{{ old('experience', 0) }}">
                    </div>

                    {{-- Department for organization --}}
                    <div class="form-group">
                        <label for="department">Department / Unit</label>
                        <select name="department" id="department" class="form-control">
                            <option value="">Select department</option>
                            <option value="pediatrics" {{ old('department') == 'pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="nicu" {{ old('department') == 'nicu' ? 'selected' : '' }}>NICU</option>
                            <option value="maternity" {{ old('department') == 'maternity' ? 'selected' : '' }}>Maternity</option>
                            <option value="lactation" {{ old('department') == 'lactation' ? 'selected' : '' }}>Lactation Services</option>
                            <option value="laboratory" {{ old('department') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                            <option value="shariah" {{ old('department') == 'shariah' ? 'selected' : '' }}>Shariah Committee</option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            {{-- ====================== ACCOUNT SETTINGS ====================== --}}
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-key"></i> Account Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="username">Username <span class="required">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required value="{{ old('username') }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Temporary Password <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                    </div>

                    {{-- Account Status for administrative control --}}
                    <div class="form-group full-width">
                        <label>Account Status</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="status" value="active" checked> <span>Active</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="status" value="pending"> <span>Pending</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====================== SUBMIT ====================== --}}
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

    <!-- Role Selection Modal -->
    <div id="roleModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Select User Role</h2>
            <p class="modal-subtitle">Choose the type of user you want to create</p>
            
            <div class="role-cards">
                <a href="{{ route('hmmc.create-new-user', ['role' => 'admin']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Hmmc Admin</h3>
                        <p>Manage User</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'parent']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-baby"></i>
                        </div>
                        <h3>Parent</h3>
                        <p>Milk donor or recipient</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'shariah']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-book-quran"></i>
                        </div>
                        <h3>Shariah Committee</h3>
                        <p>Islamic compliance expert</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'nurse']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-user-nurse"></i>
                        </div>
                        <h3>Nurse</h3>
                        <p>Healthcare professional</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'doctor']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3>Doctor</h3>
                        <p>Medical practitioner</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'labtech']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>Lab Technician</h3>
                        <p>Laboratory specialist</p>
                    </div>
                </a>
            </div>

            <button class="btn-cancel-modal" onclick="closeRoleModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </div>

    <script>
        function openRoleModal() {
            document.getElementById('roleModal').style.display = 'flex';
        }

        function closeRoleModal() {
            document.getElementById('roleModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('roleModal');
            if (event.target === modal) {
                closeRoleModal();
            }
        }
    </script>
@php
function getRoleIcon($role) {
    return [
        'doctor' => 'stethoscope',
        'nurse' => 'user-nurse',
        'clinician' => 'user-doctor',
        'labtech' => 'flask',
        'shariah' => 'book-quran',
        'admin' => 'user-shield',
        'parent' => 'child'
    ][$role] ?? 'user';
}
@endphp

<script>
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
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
        });
    });
</script>

<style>
    .error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
</style>
@endsection