@extends('layouts.nurse')

@section('title', 'Nurse Profile')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/nurse_profile.css') }}">
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="main-content">
        <div class="page-header">
            <h1>My Profile</h1>
        </div>

        <div class="profile-container">
            <!-- Left Sidebar Profile Card -->
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <div class="avatar-circle">NJ</div>
                    </div>
                    <h2 class="profile-name">Nurse Jamila</h2>
                    <p class="profile-role">Registered Nurse</p>
                    <p class="profile-registered">Registered since January 2024</p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $patientsScreened ?? 156 }}</div>
                            <div class="stat-label">PATIENTS SCREENED</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $donationsProcessed ?? 284 }}</div>
                            <div class="stat-label">DONATIONS PROCESSED</div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">PATIENTS SCREENED</div>
                            <div class="stat-number">{{ $patientsScreened ?? 156 }}</div>
                            <div class="stat-change positive">↑ 12 this month</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-hand-holding-medical"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">DONATIONS PROCESSED</div>
                            <div class="stat-number">{{ $donationsProcessed ?? 284 }}</div>
                            <div class="stat-change positive">↑ 8 this month</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-baby"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">INFANTS ASSISTED</div>
                            <div class="stat-number">{{ $infantsAssisted ?? 89 }}</div>
                            <div class="stat-change current">Helping 5 currently</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">NURSE LEVEL</div>
                            <div class="stat-number">Senior Nurse</div>
                            <div class="stat-change">2 years experience</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="profile-content">
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Professional Information</h3>
                        <a href="{{ route('nurse.edit-profile') }}" class="btn-edit">
                            Edit Profile
                        </a>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>FULL NAME</label>
                            <p>Nurse Jamila</p>
                        </div>
                        <div class="info-item">
                            <label>EMAIL</label>
                            <p>n.jamila@email.com</p>
                        </div>
                        <div class="info-item">
                            <label>PHONE</label>
                            <p>011-23456789</p>
                        </div>
                        <div class="info-item">
                            <label>EMPLOYEE ID</label>
                            <p>NUR-2024-015</p>
                        </div>
                        <div class="info-item">
                            <label>DEPARTMENT</label>
                            <p>Milk Bank & Lactation Services</p>
                        </div>
                        <div class="info-item">
                            <label>SHIFT</label>
                            <p>Morning Shift (8:00 AM - 4:00 PM)</p>
                        </div>
                        <div class="info-item">
                            <label>LICENSE NUMBER</label>
                            <p>RN-789456123</p>
                        </div>
                        <div class="info-item">
                            <label>EMERGENCY CONTACT</label>
                            <p>Ahmed Rahman (Spouse) +1 (555) 987-6543</p>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h3>Specialization & Qualifications</h3>
                    <div class="qualifications">
                        <div class="qualification-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Bachelor of Science in Nursing</span>
                        </div>
                        <div class="qualification-item">
                            <i class="fas fa-certificate"></i>
                            <span>Certified Lactation Consultant</span>
                        </div>
                        <div class="qualification-item">
                            <i class="fas fa-certificate"></i>
                            <span>Pediatric Nursing Certification</span>
                        </div>
                        <div class="qualification-item">
                            <i class="fas fa-certificate"></i>
                            <span>Milk Bank Operations Specialist</span>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection