@extends('layouts.nurse')

@section('title', 'Nurse Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/nurse_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ session('auth_user')->ns_Name ?? 'Nurse' }}</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Active Donors</span>
                <div class="stat-icon blue">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $activeDonors ?? 45 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $donorsChange ?? '12%' }} from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Appointments</span>
                <div class="stat-icon green">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingAppointments ?? 8 }}</div>
            <div class="stat-change warning">
                <i class="fas fa-clock"></i>
                {{ $appointmentsChange ?? '3 today' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Milk Requests</span>
                <div class="stat-icon orange">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
            </div>
            <div class="stat-value">{{ $milkRequests ?? 23 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $requestsChange ?? '18%' }} from last week
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Processing Queue</span>
                <div class="stat-icon red">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
            <div class="stat-value">{{ $processingQueue ?? 15 }}</div>
            <div class="stat-change warning">
                <i class="fas fa-exclamation-circle"></i>
                {{ $queueChange ?? '5 urgent' }}
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Donation Statistics -->
        <div class="card donations-card">
            <div class="card-header">
                <h2>Donation Statistics</h2>
                <a href="{{ route('nurse.manage-milk-records') }}" class="view-report">
                    View Full Report
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
                <a href="{{ route('nurse.donor-appointment-record') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-calendar-alt"></i></div>
                        <div class="quick-stat-label">Appointments</div>
                    </div>
                    <span class="quick-stat-badge primary">Manage</span>
                </a>
                <a href="{{ route('nurse.milk-request-list') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-baby"></i></div>
                        <div class="quick-stat-label">Milk Requests</div>
                    </div>
                    <span class="quick-stat-badge primary">Review</span>
                </a>
                <a href="{{ route('nurse.allocate-milk') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-tasks"></i></div>
                        <div class="quick-stat-label">Allocate Milk</div>
                    </div>
                    <span class="quick-stat-badge primary">Allocate</span>
                </a>
                <a href="{{ route('nurse.donor-candidate-list') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-users"></i></div>
                        <div class="quick-stat-label">Donor Screening</div>
                    </div>
                    <span class="quick-stat-badge primary">Screen</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="bottom-grid">
        <!-- Today's Appointments -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Today's Appointments</h2>
                <a href="{{ route('nurse.donor-appointment-record') }}" class="view-all">
                    View All Appointments
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>DONOR</th>
                            <th>TIME</th>
                            <th>TYPE</th>
                            <th>STATUS</th>
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
                                        <div class="user-email">Regular Donor</div>
                                    </div>
                                </div>
                            </td>
                            <td>9:00 AM</td>
                            <td><span class="badge badge-donor">Donation</span></td>
                            <td><span class="badge badge-active">Confirmed</span></td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar blue">FK</div>
                                    <div>
                                        <div class="user-name">Fatima Khan</div>
                                        <div class="user-email">New Donor</div>
                                    </div>
                                </div>
                            </td>
                            <td>10:30 AM</td>
                            <td><span class="badge badge-nurse">Screening</span></td>
                            <td><span class="badge badge-pending">Waiting</span></td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar dark-teal">AR</div>
                                    <div>
                                        <div class="user-name">Aisha Rahman</div>
                                        <div class="user-email">Kit Pickup</div>
                                    </div>
                                </div>
                            </td>
                            <td>2:00 PM</td>
                            <td><span class="badge badge-advisor">Pumping Kit</span></td>
                            <td><span class="badge badge-active">Scheduled</span></td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar pink">NJ</div>
                                    <div>
                                        <div class="user-name">Noor Jameela</div>
                                        <div class="user-email">Follow-up</div>
                                    </div>
                                </div>
                            </td>
                            <td>3:30 PM</td>
                            <td><span class="badge badge-donor">Consultation</span></td>
                            <td><span class="badge badge-pending">Rescheduled</span></td>
                            <td class="actions">
                               <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Nursing Activity -->
        <div class="card activity-card">
            <h2>Recent Nursing Activity</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon blue">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Donation Processed</div>
                        <div class="activity-description">300ml donation from Sarah Ahmad</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Infant Weight Recorded</div>
                        <div class="activity-description">Baby girl - 4.2kg at checkup</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon orange">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Health Screening</div>
                        <div class="activity-description">New donor screening completed</div>
                        <div class="activity-time">3 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Urgent Request</div>
                        <div class="activity-description">Priority milk request for NICU</div>
                        <div class="activity-time">4 hours ago</div>
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
                label: 'Appointments Reviewed',
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
                label: 'Milk Requests Processed',
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