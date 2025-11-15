@extends('layouts.shariah')

@section('title', 'Shariah Advisor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ session('auth_user')->sc_Name ?? 'Shariah Advisor' }}</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Approvals</span>
                <div class="stat-icon blue">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingApprovals ?? 15 }}</div>
            <div class="stat-change warning">
                <i class="fas fa-exclamation-circle"></i>
                {{ $approvalsChange ?? '5 new today' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Milk Kinship Cases</span>
                <div class="stat-icon green">
                    <i class="fa-solid fa-file-user"></i>
                </div>
            </div>
            <div class="stat-value">{{ $kinshipCases ?? 42 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $kinshipChange ?? '8%' }} from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Compliance Reviews</span>
                <div class="stat-icon orange">
                    <i class="fas fa-scale-balanced"></i>
                </div>
            </div>
            <div class="stat-value">{{ $complianceReviews ?? 28 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-check-circle"></i>
                {{ $complianceChange ?? '95% compliant' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Fatwa Issued</span>
                <div class="stat-icon red">
                    <i class="fas fa-scroll"></i>
                </div>
            </div>
            <div class="stat-value">{{ $fatwaIssued ?? 7 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-pen-fancy"></i>
                {{ $fatwaChange ?? '2 this month' }}
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Compliance Monitoring -->
        <div class="card donations-card">
            <div class="card-header">
                <h2>Compliance Monitoring</h2>
                <a href="{{ route('shariah.view-milk-processing') }}" class="view-report">
                    View Details
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="chart-body" style="height: 400px; position: relative;">
                <canvas id="milkVolumeChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card quick-stats-card">
            <h2>Quick Actions</h2>
            <div class="quick-stats-list">
                <a href="{{ route('shariah.infant-request') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-baby"></i></div>
                        <div class="quick-stat-label">Review Requests</div>
                    </div>
                    <span class="quick-stat-badge primary">Review</span>
                </a>
                <a href="{{ route('shariah.manage-milk-records') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-database"></i></div>
                        <div class="quick-stat-label">Manage Records</div>
                    </div>
                    <span class="quick-stat-badge primary">Manage</span>
                </a>
                <a href="{{ route('shariah.view-milk-processing') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-industry"></i></div>
                        <div class="quick-stat-label">Processing Audit</div>
                    </div>
                    <span class="quick-stat-badge primary">Audit</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-user-edit"></i></div>
                        <div class="quick-stat-label">Update Profile</div>
                    </div>
                    <span class="quick-stat-badge primary">Edit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="bottom-grid">
        <!-- Pending Approvals -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Pending Shariah Approvals</h2>
                <a href="{{ route('shariah.infant-request') }}" class="view-all">
                    View All Cases
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>CASE ID</th>
                            <th>REQUEST TYPE</th>
                            <th>APPLICANT</th>
                            <th>STATUS</th>
                            <th>PRIORITY</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal">MK</div>
                                    <div>
                                        <div class="user-name">#MK-2024-015</div>
                                        <div class="user-email">Milk Kinship Review</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">Kinship</span></td>
                            <td>Fatima Al-Mansoor</td>
                            <td><span class="badge badge-pending">Under Review</span></td>
                            <td><span class="badge badge-high">High</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-gavel"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar blue">DR</div>
                                    <div>
                                        <div class="user-name">#DR-2024-042</div>
                                        <div class="user-email">Donor Registration</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-nurse">Screening</span></td>
                            <td>Aisha Rahman</td>
                            <td><span class="badge badge-pending">Awaiting Fatwa</span></td>
                            <td><span class="badge badge-medium">Medium</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-gavel"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar dark-teal">MR</div>
                                    <div>
                                        <div class="user-name">#MR-2024-128</div>
                                        <div class="user-email">Milk Request</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-advisor">Compliance</span></td>
                            <td>Mohammed Hassan</td>
                            <td><span class="badge badge-active">Approved</span></td>
                            <td><span class="badge badge-low">Low</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-file-download"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar pink">MP</div>
                                    <div>
                                        <div class="user-name">#MP-2024-056</div>
                                        <div class="user-email">Processing Audit</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">Audit</span></td>
                            <td>North Branch Facility</td>
                            <td><span class="badge badge-pending">In Progress</span></td>
                            <td><span class="badge badge-high">High</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-clipboard-check"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Shariah Activity -->
        <div class="card activity-card">
            <h2>Recent Shariah Activity</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon blue">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Fatwa Issued</div>
                        <div class="activity-description">New ruling on milk kinship guidelines</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Case Approved</div>
                        <div class="activity-description">Milk kinship case #MK-2024-012 approved</div>
                        <div class="activity-time">1 day ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon orange">
                        <i class="fas fa-scale-balanced"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Compliance Review</div>
                        <div class="activity-description">Monthly compliance audit completed</div>
                        <div class="activity-time">2 days ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Attention Required</div>
                        <div class="activity-description">Urgent review needed for case #DR-2024-038</div>
                        <div class="activity-time">3 days ago</div>
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
                label: 'Reviewed Milk',
                data: [30, 40, 35, 56, 78, 67, 34],
                borderColor: '#4B9CD3',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#4B9CD3',
                pointHoverRadius: 7,
            },
            {
                label: 'Fatwa Issued',
                data: [8, 12, 13, 14, 16, 18, 20],
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
                        return `${context.dataset.label}: ${context.formattedValue}`;
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