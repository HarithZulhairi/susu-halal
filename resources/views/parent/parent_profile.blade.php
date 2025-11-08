@extends('layouts.parent')

@section('title', 'Parent Profile')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/parent_profile.css') }}">

    <div class="main-content">
        <div class="page-header">
            <h1>My Profile</h1>
            <a href="{{ route('parent.edit-profile') }}" class="btn-edit-profile">
    <i class="fas fa-edit"></i> Edit Profile
</a>

        </div>

        <div class="profile-layout">
            <!-- Left Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">SA</div>
                    <h2 class="profile-name">Sarah Ahmad</h2>
                    <span class="profile-badge">Parent</span>
                    <p class="profile-registered">Registered since January 2024</p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">18</div>
                            <div class="stat-label">DONATIONS</div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">4.2L</div>
                            <div class="stat-label">TOTAL MILK</div>
                        </div>
                    </div>
                </div>

                <!-- Total Donations Card -->
                <div class="summary-card">
                    <div class="summary-icon blue">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">TOTAL DONATIONS</div>
                        <div class="summary-value">18</div>
                        <div class="summary-change positive">↑ 2 this month</div>
                    </div>
                </div>

                <!-- Total Milk Donated Card -->
                <div class="summary-card">
                    <div class="summary-icon green">
                        <i class="fas fa-droplet"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">TOTAL MILK DONATED</div>
                        <div class="summary-value">4.2L</div>
                        <div class="summary-change positive">↑ 0.5L this month</div>
                    </div>
                </div>

                <!-- Babies Helped Card -->
                <div class="summary-card">
                    <div class="summary-icon orange">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">BABIES HELPED</div>
                        <div class="summary-value">12</div>
                        <div class="summary-change">Helping 3 currently</div>
                    </div>
                </div>

                <!-- Donor Level Card -->
                <div class="summary-card">
                    <div class="summary-icon purple">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">DONOR LEVEL</div>
                        <div class="summary-value">Gold</div>
                        <div class="summary-change">Next Platinum at 5L</div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="profile-content">
                <!-- Personal Information -->
                <div class="info-section">
                    <h3 class="section-title">Personal Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>FULL NAME</label>
                            <div class="info-value">Sarah Ahmad</div>
                        </div>
                        <div class="info-item">
                            <label>EMAIL</label>
                            <div class="info-value">sarah.ahmad@email.com</div>
                        </div>
                        <div class="info-item">
                            <label>PHONE NUMBER</label>
                            <div class="info-value">+1 (555) 123-4567</div>
                        </div>
                        <div class="info-item">
                            <label>DATE OF BIRTH</label>
                            <div class="info-value">March 15, 1990</div>
                        </div>
                        <div class="info-item full-width">
                            <label>ADDRESS</label>
                            <div class="info-value">123 Green Street, Medina City</div>
                        </div>
                        <div class="info-item full-width">
                            <label>EMERGENCY CONTACT</label>
                            <div class="info-value">Ali Ahmad (Spouse) +1 (555) 987-6543</div>
                        </div>
                    </div>
                </div>

                <!-- Health Information -->
                <div class="info-section">
                    <h3 class="section-title">Health Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>BLOOD TYPE</label>
                            <div class="info-value">O+</div>
                        </div>
                        <div class="info-item">
                            <label>HEALTH STATUS</label>
                            <span class="status-badge excellent">Excellent</span>
                        </div>
                        <div class="info-item full-width">
                            <label>LAST SCREENING</label>
                            <div class="info-value">April 28, 2024</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Donations -->
                <div class="info-section">
                    <div class="section-header">
                        <h3 class="section-title">Recent Donations</h3>
                        <div class="section-controls">
                            <button class="btn-control">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <button class="btn-control">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn-control">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Donation Tabs -->
                    <div class="donation-tabs">
                        <button class="donation-tab active">All Donations <span class="tab-count">38</span></button>
                        <button class="donation-tab">This Month <span class="tab-count">5</span></button>
                        <button class="donation-tab">Pending <span class="tab-count">1</span></button>
                    </div>

                    <!-- Donations Table -->
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
                                <td>Baby Boy (7 months)</td>
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