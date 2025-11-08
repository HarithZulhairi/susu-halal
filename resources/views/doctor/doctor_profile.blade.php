@extends('layouts.doctor')

@section('title', 'Doctor Profile')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/doctor_profile.css') }}">
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
                        <div class="avatar-circle">SA</div>
                    </div>
                    <h2 class="profile-name">Aqila Asyikin</h2>
                    <p class="profile-role">Doctor</p>
                    <p class="profile-registered">Registered since January 2024</p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">18</div>
                            <div class="stat-label">DONATIONS</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">4.2L</div>
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
                            <div class="stat-number">18</div>
                            <div class="stat-change positive">↑ 2 this month</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-droplet"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">TOTAL MILK DONATED</div>
                            <div class="stat-number">4.2L</div>
                            <div class="stat-change positive">↑ 0.5L this month</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-baby"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">BABIES HELPED</div>
                            <div class="stat-number">12</div>
                            <div class="stat-change current">Helping 3 currently</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-title">DONOR LEVEL</div>
                            <div class="stat-number">Gold</div>
                            <div class="stat-change">Next: Platinum at 5L</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="profile-content">
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Personal Information</h3>
                        <a href="{{ route('doctor.edit-profile') }}" class="btn-edit">
    Edit Profile
</a>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>FULL NAME</label>
                            <p>Aqila Asyikin</p>
                        </div>
                        <div class="info-item">
                            <label>EMAIL</label>
                            <p>aqilaasyikin@email.com</p>
                        </div>
                        <div class="info-item">
                            <label>PHONE</label>
                            <p>011-1341231</p>
                        </div>
                        <div class="info-item">
                            <label>DATE OF BIRTH</label>
                            <p>March 15, 1990</p>
                        </div>
                        <div class="info-item">
                            <label>ADDRESS</label>
                            <p>123 Green Street, Medina City</p>
                        </div>
                        <div class="info-item">
                            <label>EMERGENCY CONTACT</label>
                            <p>Ali Ahmad (Spouse) +1 (555) 987-6543</p>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h3>Specialization Background</h3>
                    <p class="specialization-text">Information about specialization will be displayed here.</p>
                </div>

                <!-- Recent Donations -->
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Recent Donations</h3>
                        <div class="section-actions">
                            <button class="btn-icon"><i class="fas fa-search"></i> Search</button>
                            <button class="btn-icon"><i class="fas fa-filter"></i> Filter</button>
                            <button class="btn-icon"><i class="fas fa-ellipsis-v"></i></button>
                        </div>
                    </div>

                    <div class="tabs">
                        <button class="tab active">All Donations <span class="badge">18</span></button>
                        <button class="tab">This Month <span class="badge">2</span></button>
                        <button class="tab">Pending <span class="badge">1</span></button>
                    </div>

                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>RECIPIENT</th>
                                <th>LOCATION</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>May 15, 2024</td>
                                <td>250ml</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>Baby Girl (3 months)</td>
                                <td>Main Center</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>May 8, 2024</td>
                                <td>300ml</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>Baby Boy (2 months)</td>
                                <td>North Branch</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>May 1, 2024</td>
                                <td>200ml</td>
                                <td><span class="status-badge processing">Processing</span></td>
                                <td>-</td>
                                <td>Main Center</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>April 24, 2024</td>
                                <td>350ml</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>Baby Girl (4 months)</td>
                                <td>Main Center</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>April 18, 2024</td>
                                <td>275ml</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>Baby Boy (1 month)</td>
                                <td>South Branch</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection