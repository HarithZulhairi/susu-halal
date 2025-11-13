@extends('layouts.hmmc')

@section('title', 'HMMC Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hmmc_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ session('auth_user')->ad_Name ?? 'HMMC' }}</h1>
            <div class="header-actions">
                <button class="btn-secondary">
                    <i class="fas fa-file-export"></i>
                    Export
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
        <div class="chart-body" style="height: 400px; position: relative;">
            <canvas id="milkVolumeChart"></canvas>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('milkVolumeChart');

// gradient fill for blue line
const gradientBlue = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientBlue.addColorStop(0, 'rgba(75, 156, 211, 0.5)');
gradientBlue.addColorStop(1, 'rgba(75, 156, 211, 0.05)');

// gradient fill for green line
const gradientGreen = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientGreen.addColorStop(0, 'rgba(72, 187, 120, 0.4)');
gradientGreen.addColorStop(1, 'rgba(72, 187, 120, 0.05)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Registered Donor',
                data: [12, 15, 17, 16, 19, 21, 24],
                borderColor: '#4B9CD3',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#4B9CD3',
                pointHoverRadius: 7,
            },
            {
                label: 'Active Donor',
                data: [80, 120, 130, 140, 160, 180, 200],
                borderColor: '#48BB78',
                backgroundColor: gradientGreen,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#48BB78',
                pointHoverRadius: 7,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#444',
                    boxWidth: 12,
                    boxHeight: 12,
                    padding: 15,
                    font: { size: 13 }
                }
            },
            tooltip: {
                usePointStyle: true,
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#333',
                borderColor: '#E2E8F0',
                borderWidth: 1,
                padding: 10,
                displayColors: true,
                boxPadding: 5,
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.formattedValue} ml`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: { color: '#555', stepSize: 500 }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#555' }
            }
        },
        animations: {
            tension: {
                duration: 2000,
                easing: 'easeOutElastic',
                from: 0.5,
                to: 0.4,
                loop: false
            }
        }
    }
});
</script>
@endsection