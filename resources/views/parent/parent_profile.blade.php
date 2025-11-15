@extends('layouts.parent')

@section('title', 'Parent Profile')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/donor_profile.css') }}">
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
            <!-- Left Sidebar Profile Card -->
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <div class="avatar-circle">{{ strtoupper(substr($profile->name ?? 'D', 0, 2)) }}</div>
                    </div>
                    <h2 class="profile-name">{{ $profile->name ?? 'Donor' }}</h2>
                    <p class="profile-role">Milk Donor</p>
                    <p class="profile-registered">
                        Registered since {{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('F Y') : 'N/A' }}
                    </p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalDonations ?? 0 }}</div>
                            <div class="stat-label">DONATIONS</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalMilk ?? 0 }}L</div>
                            <div class="stat-label">TOTAL MILK</div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">TOTAL DONATIONS</div>
                            <div class="stat-number">{{ $totalDonations ?? 0 }}</div>
                            <div class="stat-change positive">
                                {{ $monthDonations ?? 0 > 0 ? '↑ ' . $monthDonations . ' this month' : 'No donations this month' }}
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-droplet"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">TOTAL MILK DONATED</div>
                            <div class="stat-number">{{ $totalMilk ?? 0 }}L</div>
                            <div class="stat-change positive">Making a difference</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-baby"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">BABIES HELPED</div>
                            <div class="stat-number">{{ $babiesHelped ?? 0 }}</div>
                            <div class="stat-change current">Helping families</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">DONOR STATUS</div>
                            <div class="stat-number">
                                @if(($totalMilk ?? 0) >= 5)
                                    Platinum
                                @elseif(($totalMilk ?? 0) >= 3)
                                    Gold
                                @elseif(($totalMilk ?? 0) >= 1)
                                    Silver
                                @else
                                    Bronze
                                @endif
                            </div>
                            <div class="stat-change">Active Donor</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="profile-content">
                <!-- Personal Information -->
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Personal Information</h3>
                        <a href="{{ route('profile.edit') }}" class="btn-edit">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>FULL NAME</label>
                            <p>{{ $profile->name ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>EMAIL</label>
                            <p>{{ $profile->email ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>PHONE</label>
                            <p>{{ $profile->contact ?? 'Not provided' }}</p>
                        </div>
                        <div class="info-item">
                            <label>DATE OF BIRTH</label>
                            <p>{{ $profile->dob ? \Carbon\Carbon::parse($profile->dob)->format('F d, Y') : 'Not provided' }}</p>
                        </div>
                        <div class="info-item">
                            <label>ADDRESS</label>
                            <p>{{ $profile->address ?? 'Not provided' }}</p>
                        </div>
                        <div class="info-item">
                            <label>NRIC</label>
                            <p>{{ $profile->nric ?? 'Not provided' }}</p>
                        </div>
                        <div class="info-item">
                            <label>MEMBER SINCE</label>
                            <p>{{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>ACCOUNT STATUS</label>
                            <p><span class="status-badge completed">Active</span></p>
                        </div>
                    </div>
                </div>

                <!-- Health Information -->
                <div class="profile-section">
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>INFECTION/DISEASE RISK</label>
                            <p>{{ $profile->infection_risk ?? 'Not provided' }}</p>
                        </div>
                        <div class="info-item">
                            <label>CURRENT MEDICATION</label>
                            <p>{{ $profile->medication ?? 'None' }}</p>
                        </div>
                        <div class="info-item">
                            <label>RECENT ILLNESS</label>
                            <p>{{ $profile->recent_illness ?? 'None reported' }}</p>
                        </div>
                        <div class="info-item">
                            <label>DIETARY ALERTS</label>
                            <p>{{ $profile->dietary_alerts ?? 'None' }}</p>
                        </div>
                        <div class="info-item">
                            <label>TOBACCO/ALCOHOL USE</label>
                            <p>
                                @if($profile->tobacco_alcohol ?? false)
                                    <span class="status-badge pending">Yes</span>
                                @else
                                    <span class="status-badge completed">No</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Certifications & Qualifications -->
                <div class="profile-section">
                    <h3>Certifications & Qualifications</h3>
                    <div class="qualifications">
                        <div class="qualification-item">
                            <i class="fas fa-certificate"></i>
                            <span>Registered Milk Donor</span>
                        </div>
                        <div class="qualification-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Profile Verified</span>
                        </div>
                        <div class="qualification-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Halal Certified</span>
                        </div>
                        @if(($totalDonations ?? 0) > 10)
                        <div class="qualification-item">
                            <i class="fas fa-award"></i>
                            <span>Experienced Donor (10+ donations)</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Donations -->
                @if(isset($recentDonations) && $recentDonations->count() > 0)
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Recent Donations</h3>
                        <div class="section-actions">
                            <a href="{{ route('donor.donations') }}" class="btn-view-all">View All</a>
                        </div>
                    </div>

                    <div class="tabs">
                        <button class="tab active">All Donations <span class="badge">{{ $totalDonations ?? 0 }}</span></button>
                        <button class="tab">This Month <span class="badge">{{ $monthDonations ?? 0 }}</span></button>
                        <button class="tab">Pending <span class="badge">{{ $pendingDonations ?? 0 }}</span></button>
                    </div>

                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>DATE & TIME</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>LOCATION</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDonations as $donation)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($donation->date)->format('M d, Y') }}<br>
                                    <small>{{ \Carbon\Carbon::parse($donation->time)->format('h:i A') }}</small>
                                </td>
                                <td>{{ $donation->amount }}ml</td>
                                <td>
                                    <span class="status-badge {{ strtolower($donation->status) }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                                <td>{{ $donation->location ?? 'Main Center' }}</td>
                                <td class="actions">
                                    <button class="btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Recent Donations</h3>
                    </div>
                    <div class="empty-state">
                        <i class="fas fa-droplet"></i>
                        <p>No donation records yet</p>
                        <a href="#" class="btn-primary">Make Your First Donation</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 64px;
        opacity: 0.3;
        margin-bottom: 20px;
    }

    .empty-state p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .btn-primary {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
    }

    .btn-view-all {
        padding: 8px 16px;
        background: #f3f4f6;
        color: #374151;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-view-all:hover {
        background: #e5e7eb;
    }
    </style>

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