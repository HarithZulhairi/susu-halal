@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, Doctor</h1>
            <div class="header-actions">
                <button class="btn-secondary">
                    <i class="fas fa-file-export"></i>
                    Export
                </button>
                <button class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Users</span>
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">248</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                12% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Active Donors</span>
                <div class="stat-icon green">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div class="stat-value">195</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                8% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Donations</span>
                <div class="stat-icon orange">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
            </div>
            <div class="stat-value">2,847</div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                20% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">System Alerts</span>
                <div class="stat-icon red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-value">41</div>
            <div class="stat-change warning">
                <i class="fas fa-arrow-up"></i>
                3 news today
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Donations Statistics -->
        <div class="card donations-card">
            <div class="card-header">
                <h2>Donations Statistics</h2>
                <a href="#" class="view-report">
                    View Report
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="chart-placeholder">
                <i class="fas fa-chart-line"></i>
                <p>Monthly Donation Volume Chart</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card quick-stats-card">
            <h2>Quick Stats</h2>
            <div class="quick-stats-list">
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">10</div>
                        <div class="quick-stat-label">New Donors This Week</div>
                    </div>
                    <span class="quick-stat-badge positive">+10</span>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">12</div>
                        <div class="quick-stat-label">Milk Requests</div>
                    </div>
                    <span class="quick-stat-badge positive">+12</span>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">10</div>
                        <div class="quick-stat-label">Pending Approvals</div>
                    </div>
                    <span class="quick-stat-badge positive">+12</span>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">10</div>
                        <div class="quick-stat-label">Active Campaigns</div>
                    </div>
                    <span class="quick-stat-badge positive">+12</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="bottom-grid">
        <!-- Recent User Registrations -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Recent User Registrations</h2>
                <a href="#" class="view-all">
                    View All Users
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>USER</th>
                            <th>ROLE</th>
                            <th>STATUS</th>
                            <th>REGISTRATION DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal">SA</div>
                                    <div>
                                        <div class="user-name">Sarah Ahmad</div>
                                        <div class="user-email">sarah.ahmad2@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">Donor</span></td>
                            <td><span class="badge badge-active">Active</span></td>
                            <td>May 15, 2024</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar blue">NJ</div>
                                    <div>
                                        <div class="user-name">Nurse Jamila</div>
                                        <div class="user-email">n.jamila@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-nurse">Nurse</span></td>
                            <td><span class="badge badge-active">Active</span></td>
                            <td>May 14, 2024</td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar dark-teal">AS</div>
                                    <div>
                                        <div class="user-name">Ahmed Al-Sayed</div>
                                        <div class="user-email">a.alsayed@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-advisor">Shariah Advisor</span></td>
                            <td><span class="badge badge-pending">Pending</span></td>
                            <td>May 12, 2024</td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar pink">FK</div>
                                    <div>
                                        <div class="user-name">Fatima Khan</div>
                                        <div class="user-email">f.khan@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">Donor</span></td>
                            <td><span class="badge badge-inactive">Inactive</span></td>
                            <td>May 10, 2024</td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card activity-card">
            <h2>Recent Activity</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon blue">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New User Registration</div>
                        <div class="activity-description">Sarah Ahmad registered as a donor</div>
                        <div class="activity-time">3 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Milk Donation Processed</div>
                        <div class="activity-description">250ml donation from Fatima Khan</div>
                        <div class="activity-time">4 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">System Alert</div>
                        <div class="activity-description">Email service is currently offline</div>
                        <div class="activity-time">6 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon orange">
                        <i class="fas fa-sync"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">System Update</div>
                        <div class="activity-description">Security patch applied successfully</div>
                        <div class="activity-time">1 day ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection