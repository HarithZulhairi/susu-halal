@extends('layouts.labtech')

@section('title', 'Lab Technician Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1>Welcome, {{ session('auth_user')->lt_Name ?? 'Lab Technician' }}</h1>
                <p class="muted">Shariah-compliant Human Milk Bank • Laboratory Dashboard</p>
            </div>

        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Milk Samples</span>
                <div class="stat-icon blue">
                    <i class="fas fa-bottle-droplet"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalSamples ?? 320 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                10% this month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Processed Samples</span>
                <div class="stat-icon green">
                    <i class="fas fa-flask"></i>
                </div>
            </div>
            <div class="stat-value">{{ $processedSamples ?? 250 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                8% increase
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Pasteurization</span>
                <div class="stat-icon orange">
                    <i class="fas fa-temperature-high"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingPasteurization ?? 40 }}</div>
            <div class="stat-change warning">
                <i class="fas fa-exclamation-circle"></i>
                Needs attention
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Storage Used</span>
                <div class="stat-icon purple">
                    <i class="fas fa-snowflake"></i>
                </div>
            </div>
            <div class="stat-value">{{ $storageUsed ?? '78%' }}</div>
            <div class="stat-change neutral">
                <i class="fas fa-warehouse"></i>
                Freezer 3 near capacity
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Milk Processing Statistics -->
        <div class="card chart-card">
            <div class="card-header">
                <h2>Milk Processing & Dispatch Statistics</h2>
                <a href="#" class="view-report">
                    View Full Report
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="chart-body" style="height: 400px; position: relative;">
                <canvas id="labTechChart"></canvas>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card quick-stats-card">
            <h2>Quick Stats</h2>
            <div class="quick-stats-list">
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">12</div>
                        <div class="quick-stat-label">New Samples Today</div>
                    </div>
                    <span class="quick-stat-badge positive">+12</span>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">6</div>
                        <div class="quick-stat-label">Samples Under Pasteurization</div>
                    </div>
                    <span class="quick-stat-badge warning">Processing</span>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">9</div>
                        <div class="quick-stat-label">Dispatched Today</div>
                    </div>
                    <span class="quick-stat-badge positive">+9</span>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value">3</div>
                        <div class="quick-stat-label">Alerts</div>
                    </div>
                    <span class="quick-stat-badge danger">Check</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="bottom-grid">
        <!-- Recent Milk Records -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Recent Milk Records</h2>
                <a href="#" class="view-all">
                    View All Records
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Sample ID</th>
                            <th>Donor</th>
                            <th>Status</th>
                            <th>Volume</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#MLK-2451</td>
                            <td>Fatima Khan</td>
                            <td><span class="badge badge-active">Processed</span></td>
                            <td>250ml</td>
                            <td>May 16, 2024</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-eye"></i></button>
                                <button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#MLK-2448</td>
                            <td>Aisyah N.</td>
                            <td><span class="badge badge-pending">Pending Pasteurization</span></td>
                            <td>200ml</td>
                            <td>May 15, 2024</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-eye"></i></button>
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
                    <div class="activity-icon green">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Sample Processed</div>
                        <div class="activity-description">Sample #MLK-2451 completed pasteurization</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon orange">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Pasteurization Started</div>
                        <div class="activity-description">Sample #MLK-2448 now in process</div>
                        <div class="activity-time">3 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Alert: Storage Limit</div>
                        <div class="activity-description">Freezer 3 at 92% capacity</div>
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
const ctx = document.getElementById('labTechChart');
const gradientBlue = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

const gradientTeal = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientTeal.addColorStop(0, 'rgba(20, 184, 166, 0.4)');
gradientTeal.addColorStop(1, 'rgba(20, 184, 166, 0.05)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Processed Samples',
                data: [40, 55, 60, 65, 75, 90, 110],
                borderColor: '#3B82F6',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3B82F6',
                pointHoverRadius: 7,
            },
            {
                label: 'Dispatched Milk',
                data: [20, 35, 45, 50, 60, 80, 95],
                borderColor: '#14B8A6',
                backgroundColor: gradientTeal,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#14B8A6',
                pointHoverRadius: 7,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: '#444', padding: 15, font: { size: 13 } }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#555' } },
            x: { grid: { display: false }, ticks: { color: '#555' } }
        },
        animations: {
            tension: { duration: 2000, easing: 'easeOutElastic', from: 0.6, to: 0.4 }
        }
    }
});
</script>

@endsection
