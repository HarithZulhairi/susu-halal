@extends('layouts.donor')

@section('title', 'Donor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ session('auth_user')->dn_Name ?? 'Donor' }}</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Donations</span>
                <div class="stat-icon blue">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
            </div>
            <div class="stat-value">18</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                12% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Milk Donated</span>
                <div class="stat-icon green">
                    <i class="fas fa-bottle-droplet"></i>
                </div>
            </div>
            <div class="stat-value">4,210ml</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                8% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Upcoming Appointments</span>
                <div class="stat-icon orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">2</div>
            <div class="stat-change warning">
                <i class="fas fa-clock"></i>
                Next: Tomorrow
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Babies Helped</span>
                <div class="stat-icon red">
                    <i class="fas fa-baby"></i>
                </div>
            </div>
            <div class="stat-value">12</div>
            <div class="stat-change positive">
                <i class="fas fa-heart"></i>
                Making a difference
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Donation History -->
        <div class="card donations-card">
            <div class="card-header">
                <h2>Donation History</h2>
                <a href="{{ route('donor.profile') }}" class="view-report">
                    View Full History
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
                <a href="{{ route('donor.appointment-form') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-calendar-plus"></i></div>
                        <div class="quick-stat-label">Schedule Appointment</div>
                    </div>
                    <span class="quick-stat-badge primary">Book Now</span>
                </a>
                <a href="{{ route('donor.pumping-kit-form') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-box"></i></div>
                        <div class="quick-stat-label">Request Pumping Kit</div>
                    </div>
                    <span class="quick-stat-badge primary">Request</span>
                </a>
                <a href="{{ route('donor.my-appointments') }}" class="quick-stat-item">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-list"></i></div>
                        <div class="quick-stat-label">View My Appointments</div>
                    </div>
                    <span class="quick-stat-badge primary">View All</span>
                </a>
                <a href="{{ route('donor.edit-profile') }}" class="quick-stat-item">
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
        <!-- Upcoming Appointments -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Upcoming Appointments</h2>
                <a href="{{ route('donor.my-appointments') }}" class="view-all">
                    View All Appointments
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>DATE & TIME</th>
                            <th>TYPE</th>
                            <th>LOCATION</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">May 20, 2024</div>
                                        <div class="user-email">10:00 AM - 11:00 AM</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-donor">Milk Donation</span></td>
                            <td>Main Center</td>
                            <td><span class="badge badge-active">Confirmed</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar blue"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">May 25, 2024</div>
                                        <div class="user-email">2:00 PM - 3:00 PM</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-nurse">Health Screening</span></td>
                            <td>North Branch</td>
                            <td><span class="badge badge-pending">Pending</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar dark-teal"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">June 1, 2024</div>
                                        <div class="user-email">9:00 AM - 10:00 AM</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-advisor">Pumping Kit Pickup</span></td>
                            <td>Main Center</td>
                            <td><span class="badge badge-active">Scheduled</span></td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
<button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Donation Activity -->
        <div class="card activity-card">
            <h2>Recent Donation Activity</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon blue">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Milk Donation Completed</div>
                        <div class="activity-description">250ml donated at Main Center</div>
                        <div class="activity-time">2 days ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Appointment Scheduled</div>
                        <div class="activity-description">Health screening on May 25</div>
                        <div class="activity-time">3 days ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon orange">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Pumping Kit Requested</div>
                        <div class="activity-description">Kit pickup scheduled for June 1</div>
                        <div class="activity-time">1 week ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon red">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Milestone Reached</div>
                        <div class="activity-description">Helped 12 babies in total</div>
                        <div class="activity-time">2 weeks ago</div>
                    </div>
                </div>
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
                label: 'Donation Volume (ml)',
                data: [900, 800, 500, 500, 300, 200, 300],
                borderColor: '#4B9CD3',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#4B9CD3',
                pointHoverRadius: 7,
            },
            {
                label: 'Donation Frequency',
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
