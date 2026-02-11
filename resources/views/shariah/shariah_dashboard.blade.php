@extends('layouts.shariah')

@section('title', 'Shariah Advisor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="container">
<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ auth()->user()->name }}<br>
            <p class="muted">Shariah-compliant Human Milk Bank • Shariah Compliance Dashboard</p>
            </h1>
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
                <a href="{{ route('shariah.shariah_manage-milk-records') }}" class="view-report">
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
                <a href="{{ route('shariah.shariah_manage-milk-records') }}" class="quick-stat-item" style="text-decoration: none;"">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-baby"></i></div>
                        <div class="quick-stat-label">Milk Records</div>
                    </div>
                    <span class="quick-stat-badge primary">View Record</span>
                </a>
                <a href="{{ route('profile.show') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-user"></i></div>
                        <div class="quick-stat-label">View Profile</div>
                    </div>
                    <span class="quick-stat-badge primary">View</span>
                </a>
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
        labels: @json($months),
        datasets: [
            {
                label: 'Reviewed Milk',
                data: @json($reviewedData),
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
                data: @json($fatwaData),
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