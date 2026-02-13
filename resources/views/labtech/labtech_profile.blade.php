@extends('layouts.labtech')

@section('title', 'Labtech Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_profile.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        <h1>My Profile</h1>
    </div>

    <div class="profile-container">

        <!-- LEFT SIDEBAR -->
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar">
                    <div class="avatar-circle">{{ strtoupper(substr($profile->name ?? 'DR', 0, 2)) }}</div>
                </div>

                <h2 class="profile-name">{{ $profile->name ?? 'Doctor' }}</h2>
                <p class="profile-role">{{ auth()->user()->role }}</p>

                <p class="profile-registered">
                    Joined since 
                    {{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('F Y') : 'N/A' }}
                </p>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $totalPatients ?? 0 }}</div>
                        <div class="stat-label">PATIENTS HANDLED</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $profile->experience ?? 0 }}</div>
                        <div class="stat-label">YEARS EXP</div>
                    </div>
                </div>
            </div>

            <!-- Stats Additional Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">SPECIALIZATION</div>
                        <div class="stat-number">{{ $profile->specialization ?? 'N/A' }}</div>
                        <div class="stat-change positive">Certified Specialist</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">QUALIFICATION</div>
                        <div class="stat-number">{{ $profile->qualification ?? 'N/A' }}</div>
                        <div class="stat-change current">Professional Degree</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">INSTITUTION</div>
                        <div class="stat-number">{{ $profile->institution ?? 'N/A' }}</div>
                        <div class="stat-change">Affiliated</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">EXPERIENCE</div>
                        <div class="stat-number">{{ $profile->experience ?? 0 }} Years</div>
                        <div class="stat-change">Verified Professional</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE CONTENT -->
        <div class="profile-content">

            <!-- PERSONAL INFO -->
            <div class="profile-section">
                <div class="section-header">
                    <h3>Personal Information</h3>
                    <a href="{{ route('profile.edit') }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>

                <div class="info-grid">
                    <div class="info-item"><label>FULL NAME</label><p>{{ $profile->name }}</p></div>
                    <div class="info-item"><label>EMAIL</label><p>{{ $profile->email }}</p></div>
                    <div class="info-item"><label>PHONE</label><p>{{ $profile->contact ?? 'Not provided' }}</p></div>
                    <div class="info-item"><label>NRIC</label><p>{{ $profile->nric ?? 'Not provided' }}</p></div>
                    <div class="info-item"><label>USERNAME</label><p>{{ $profile->username }}</p></div>
                    <div class="info-item"><label>ADDRESS</label><p>{{ $profile->address ?? 'Not provided' }}</p></div>
                    <div class="info-item"><label>ACCOUNT CREATED</label>
                        <p>{{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="info-item"><label>STATUS</label>
                        <p><span class="status-badge completed">Active</span></p>
                    </div>
                </div>
            </div>

            <!-- PROFESSIONAL INFO -->
            <div class="profile-section">
                <h3>Professional Information</h3>
                <div class="info-grid">
                    <div class="info-item"><label>QUALIFICATION</label><p>{{ $profile->qualification }}</p></div>
                    <div class="info-item"><label>CERTIFICATION</label><p>{{ $profile->certification ?? 'N/A' }}</p></div>
                    <div class="info-item"><label>INSTITUTION</label><p>{{ $profile->institution }}</p></div>
                    <div class="info-item"><label>SPECIALIZATION</label><p>{{ $profile->specialization }}</p></div>
                    <div class="info-item"><label>YEARS OF EXPERIENCE</label><p>{{ $profile->experience }} Years</p></div>
                </div>
            </div>

            <!-- CERTIFICATIONS -->
            <div class="profile-section">
                <h3>Certifications & Qualifications</h3>
                <div class="qualifications">
                    <div class="qualification-item"><i class="fas fa-certificate"></i><span>Licensed Doctor</span></div>
                    <div class="qualification-item"><i class="fas fa-user"></i><span>Verified Profile</span></div>
                    <div class="qualification-item"><i class="fas fa-user-md"></i><span>Medical Practitioner</span></div>
                    @if($profile->experience >= 10)
                    <div class="qualification-item"><i class="fas fa-award"></i><span>Senior Medical Officer</span></div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

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
                    this.classList.add('error-input');
                } else {
                    this.classList.remove('error-input');
                }
            });
        });
    });
</script>
@endsection
