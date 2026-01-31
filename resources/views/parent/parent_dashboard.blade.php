@extends('layouts.parent')

@section('title', 'Parent Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/parent_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div>
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ auth()->user()->name }}<br>
            <p class="muted">Shariah-compliant Human Milk Bank • Parent Dashboard</p>
            </h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Milk Requests</span>
                <div class="stat-icon blue">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalRequests ?? 8 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $requestsChange ?? '15%' }} from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Milk Received</span>
                <div class="stat-icon green">
                    <i class="fas fa-bottle-droplet"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($milkReceived ?? 3250) }}ml</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $receivedChange ?? '22%' }} from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Requests</span>
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingRequests ?? 2 }}</div>
            <div class="stat-change warning">
                <i class="fas fa-exclamation-circle"></i>
                {{ $pendingChange ?? 'Awaiting approval' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Infants Registered</span>
                <div class="stat-icon red">
                    <i class="fas fa-baby-carriage"></i>
                </div>
            </div>
            <div class="stat-value">{{ $infantsRegistered ?? 1 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-heart"></i>
                Your little one
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Milk Request History -->
        <div class="card donations-card">
            <div class="card-header">
                <h2>Milk Request History</h2>
                <a href="{{ route('parent.my-infant-request') }}" class="view-report" style="text-decoration: none;">
                    View All Requests
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
                <a href="{{ route('parent.my-infant-request') }}" class="quick-stat-item" style="text-decoration: none;"">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-baby"></i></div>
                        <div class="quick-stat-label">Request Milk</div>
                    </div>
                    <span class="quick-stat-badge primary">New Request</span>
                </a>
                <a href="{{ route('profile.show') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-user"></i></div>
                        <div class="quick-stat-label">View Profile</div>
                    </div>
                    <span class="quick-stat-badge primary">View</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-user-edit"></i></div>
                        <div class="quick-stat-label">Update Profile</div>
                    </div>
                    <span class="quick-stat-badge primary">Edit</span>
                </a>
                <a href="{{ route('parent.my-infant-request') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-history"></i></div>
                        <div class="quick-stat-label">Request History</div>
                    </div>
                    <span class="quick-stat-badge primary">View All</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="quick-stats-card">
        <!-- Recent Milk Requests -->
        <div class="card users-card">
            <div class="card-header">
                <h2>My Infant's Milk Requests</h2>
                <a href="{{ route('parent.my-infant-request') }}" class="view-all">
                    View All Requests
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>REQUEST DATE</th>
                            <th>QUANTITY</th>
                            <th>STATUS</th>
                            <th>INFANT DETAILS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">May 18, 2024</div>
                                        <div class="user-email">Urgent Request</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">500ml</span></td>
                            <td><span class="badge badge-active">Approved</span></td>
                            <td>Baby Girl (3 months)</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar blue"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">May 15, 2024</div>
                                        <div class="user-email">Regular Request</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-nurse">750ml</span></td>
                            <td><span class="badge badge-pending">Processing</span></td>
                            <td>Baby Boy (2 months)</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar dark-teal"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">May 10, 2024</div>
                                        <div class="user-email">Monthly Supply</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-advisor">1000ml</span></td>
                            <td><span class="badge badge-active">Completed</span></td>
                            <td>Baby Girl (4 months)</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-file-download"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar pink"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">May 5, 2024</div>
                                        <div class="user-email">Initial Request</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">300ml</span></td>
                            <td><span class="badge badge-inactive">Archived</span></td>
                            <td>Baby Boy (1 month)</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                                <button class="action-btn"><i class="fas fa-file-download"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</script>
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
                label: 'Infant Milk Request Volume (ml)',
                data: [900, 800, 500, 500, 300, 200, 300],
                borderColor: '#4B9CD3',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#4B9CD3',
                pointHoverRadius: 7,
            },
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