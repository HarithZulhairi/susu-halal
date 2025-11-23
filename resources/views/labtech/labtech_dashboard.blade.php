@extends('layouts.labtech')

@section('title', 'Lab Technician Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="container">


<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1>Welcome, {{ auth()->user()->name }}<br>
                <p class="muted">Shariah-compliant Human Milk Bank • Lab Technician dashboard</p>
                </h1>
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
        <!-- Quick Actions -->
        <div class="card quick-stats-card">
            <h2>Quick Actions</h2>
                    <div class="quick-stats-list">
                        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="quick-stat-item" style="text-decoration: none;">
                            <div class="quick-stat-info">
                                <div class="quick-stat-value"><i class="fas fa-calendar-plus"></i></div>
                                <div class="quick-stat-label">Milk Record</div>
                            </div>
                            <span class="quick-stat-badge primary">View Now</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="quick-stat-item" style="text-decoration: none;">
                            <div class="quick-stat-info">
                                <div class="quick-stat-value"><i class="fas fa-user-edit"></i></div>
                                <div class="quick-stat-label">Update Profile</div>
                            </div>
                            <span class="quick-stat-badge primary">Edit</span>
                        </a>
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
