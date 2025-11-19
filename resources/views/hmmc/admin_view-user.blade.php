@extends('layouts.hmmc')

@section('title', 'View User Details')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin_view-user.css') }}">

<div class="main-content">
    <div class="page-header">
        <h1>User Details</h1>
    </div>

    <div class="profile-container">
        <!-- Left Sidebar Profile Card -->
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar">
                    <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                </div>
                <h2 class="profile-name">{{ $user->name }}</h2>
                <p class="profile-role">
                    @if($role == 'admin')
                        HMMC Administrator
                    @elseif($role == 'doctor')
                        Medical Doctor
                    @elseif($role == 'nurse')
                        Nurse
                    @elseif($role == 'labtech')
                        Lab Technician
                    @elseif($role == 'shariah')
                        Shariah Committee
                    @elseif($role == 'parent')
                        Parent/Recipient
                    @elseif($role == 'donor')
                        Milk Donor
                    @endif
                </p>
                <p class="profile-registered">
                    Registered since {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('F Y') : 'N/A' }}
                </p>
                
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">ACCOUNT STATUS</div>
                        <div class="stat-number">Active</div>
                        <div class="stat-change positive">Verified</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">EMAIL STATUS</div>
                        <div class="stat-number">Verified</div>
                        <div class="stat-change positive">Active</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">CONTACT</div>
                        <div class="stat-number">{{ $user->contact ? 'Available' : 'Not Set' }}</div>
                        <div class="stat-change">{{ $user->contact ?? 'No number' }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-title">USER ROLE</div>
                        <div class="stat-number">{{ ucfirst($role) }}</div>
                        <div class="stat-change">Full Access</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="profile-content">
            <div class="profile-section">
                <div class="section-header">
                    <h3>Personal Information</h3>
                    <a href="{{ route('hmmc.users.edit', ['role' => $role, 'id' => $user->id]) }}" class="btn-edit">
                        Edit Profile
                    </a>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>FULL NAME</label>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="info-item">
                        <label>EMAIL</label>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="info-item">
                        <label>PHONE</label>
                        <p>{{ $user->contact ?? 'Not provided' }}</p>
                    </div>
                    <div class="info-item">
                        <label>NRIC</label>
                        <p>{{ $user->nric ?? 'Not provided' }}</p>
                    </div>
                    @if($user->username)
                    <div class="info-item">
                        <label>USERNAME</label>
                        <p>@{{ $user->username }}</p>
                    </div>
                    @endif
                    <div class="info-item">
                        <label>ADDRESS</label>
                        <p>{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    @if(isset($user->dob))
                    <div class="info-item">
                        <label>DATE OF BIRTH</label>
                        <p>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('M d, Y') : 'Not provided' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Professional Information (Staff) -->
            @if(in_array($role, ['doctor', 'nurse', 'labtech', 'shariah']))
            <div class="profile-section">
                <h3>Professional Information</h3>
                <div class="qualifications">
                    @if($user->qualification)
                    <div class="qualification-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ $user->qualification }}</span>
                    </div>
                    @endif
                    @if($user->certification)
                    <div class="qualification-item">
                        <i class="fas fa-certificate"></i>
                        <span>{{ $user->certification }}</span>
                    </div>
                    @endif
                    @if($user->institution)
                    <div class="qualification-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $user->institution }}</span>
                    </div>
                    @endif
                    @if($user->specialization)
                    <div class="qualification-item">
                        <i class="fas fa-stethoscope"></i>
                        <span>{{ $user->specialization }}</span>
                    </div>
                    @endif
                    @if($user->experience)
                    <div class="qualification-item">
                        <i class="fas fa-award"></i>
                        <span>{{ $user->experience }} years experience</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Baby Information (Parent) -->
            @if($role == 'parent')
            <div class="profile-section">
                <h3>Baby Information</h3>
                <div class="info-grid">
                    @if($user->baby_name)
                    <div class="info-item">
                        <label>BABY NAME</label>
                        <p>{{ $user->baby_name }}</p>
                    </div>
                    @endif
                    @if($user->baby_dob)
                    <div class="info-item">
                        <label>DATE OF BIRTH</label>
                        <p>{{ \Carbon\Carbon::parse($user->baby_dob)->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($user->baby_gender)
                    <div class="info-item">
                        <label>GENDER</label>
                        <p>{{ $user->baby_gender }}</p>
                    </div>
                    @endif
                    @if($user->baby_birth_weight)
                    <div class="info-item">
                        <label>BIRTH WEIGHT</label>
                        <p>{{ $user->baby_birth_weight }} kg</p>
                    </div>
                    @endif
                    @if($user->baby_current_weight)
                    <div class="info-item">
                        <label>CURRENT WEIGHT</label>
                        <p>{{ $user->baby_current_weight }} kg</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Health Information (Donor) -->
            @if($role == 'donor')
            <div class="profile-section">
                <h3>Health Information</h3>
                <div class="info-grid">
                    @if($user->infection_risk)
                    <div class="info-item">
                        <label>INFECTION RISK</label>
                        <p>{{ $user->infection_risk }}</p>
                    </div>
                    @endif
                    @if($user->medication)
                    <div class="info-item">
                        <label>MEDICATION</label>
                        <p>{{ $user->medication }}</p>
                    </div>
                    @endif
                    @if($user->recent_illness)
                    <div class="info-item">
                        <label>RECENT ILLNESS</label>
                        <p>{{ $user->recent_illness }}</p>
                    </div>
                    @endif
                    <div class="info-item">
                        <label>TOBACCO/ALCOHOL</label>
                        <p>{{ $user->tobacco_alcohol ? 'Yes' : 'No' }}</p>
                    </div>
                    @if($user->dietary_alerts)
                    <div class="info-item">
                        <label>DIETARY ALERTS</label>
                        <p>{{ $user->dietary_alerts }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Account Information -->
            <div class="profile-section">
                <div class="section-header">
                    <h3>Account Information</h3>
                    <div class="section-actions">
                        <a href="{{ route('hmmc.manage-users') }}" class="btn-icon">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active">Account Details</button>
                    <button class="tab">Activity Log</button>
                    <button class="tab">Permissions</button>
                </div>

                <table class="records-table">
                    <thead>
                        <tr>
                            <th>PROPERTY</th>
                            <th>VALUE</th>
                            <th>STATUS</th>
                            <th>LAST UPDATED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>User ID</td>
                            <td>#{{ $user->id }}</td>
                            <td><span class="status-badge completed">Active</span></td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Email Verification</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="status-badge completed">Verified</span></td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Account Status</td>
                            <td>Active Member</td>
                            <td><span class="status-badge completed">Active</span></td>
                            <td>Current</td>
                        </tr>
                        <tr>
                            <td>Member Since</td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('F d, Y') : 'N/A' }}</td>
                            <td><span class="status-badge completed">Registered</span></td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection