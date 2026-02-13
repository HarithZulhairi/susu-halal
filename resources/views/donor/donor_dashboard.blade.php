@extends('layouts.donor')

@section('title', 'Donor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Welcome, {{ auth()->user()->name }}<br>
            <p class="muted">Shariah-compliant Human Milk Bank • Donor Dashboard</p>
            </h1>
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
            <div class="stat-value">{{ $totalDonations }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{-- Optional: calculate percentage change --}}
                @if(isset($donationChangePercent))
                    {{ $donationChangePercent }}% from last month
                @endif
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Milk Donated</span>
                <div class="stat-icon green">
                    <i class="fas fa-bottle-droplet"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalMilk }}ml</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                @if(isset($milkChangePercent))
                    {{ $milkChangePercent }}% from last month
                @endif
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Upcoming Appointments</span>
                <div class="stat-icon orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $upcomingAppointments->count() }}</div>
            <div class="stat-change warning">
                <i class="fas fa-clock"></i>
                @if($nextAppointment = $upcomingAppointments->first())
                    Next: {{ \Carbon\Carbon::parse($nextAppointment->appointment_datetime)->diffForHumans() }}
                @endif
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">My Bottles</span>
                <div class="stat-icon red">
                    <i class="fas fa-flask-vial"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalBottles }}</div>
            <div class="stat-change positive">
                <i class="fas fa-heart"></i>
                Post-pasteurization
            </div>
        </div>
    </div>


    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Donation History -->
        <div class="card donations-card">
            <div class="card-header">
                <h2>Donation History</h2>
                <a href="#" class="view-report">
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
                <a href="{{ route('donor.appointment-form') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-calendar-plus"></i></div>
                        <div class="quick-stat-label">Donate Milk</div>
                    </div>
                    <span class="quick-stat-badge primary">Book Now</span>
                </a>
                <a href="{{ route('donor.pumping-kit-form') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-box"></i></div>
                        <div class="quick-stat-label">Request Pumping Kit</div>
                    </div>
                    <span class="quick-stat-badge primary">Request</span>
                </a>
                <a href="{{ route('donor.appointments') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-list"></i></div>
                        <div class="quick-stat-label">View My Appointments</div>
                    </div>
                    <span class="quick-stat-badge primary">View All</span>
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

    <!-- Bottom Grid -->
    <div class="quick-stats-card">
        <!-- Upcoming Appointments -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Upcoming Appointments</h2>
                <a href="{{ route('donor.appointments') }}" class="view-all">
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingAppointments as $app)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal"><i class="fas fa-calendar"></i></div>
                                    <div>
                                        <div class="user-name">{{ \Carbon\Carbon::parse($app->appointment_datetime)->format('d/m/Y') }}</div>
                                        <div class="user-email">{{ \Carbon\Carbon::parse($app->appointment_datetime)->format('h:i A') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-donor">
                                    {{ $app->milk_amount ? 'Milk Donation' : 'Pumping Kit Pickup' }}
                                </span>
                            </td>
                            <td>{{ $app->location ?? $app->collection_address ?? 'N/A' }}</td>
                            <td>
                                <span class="status {{ strtolower($app->status) }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px;">
                                No upcoming appointments.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
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
        labels: {!! json_encode($monthLabels) !!},
        datasets: [
            {
                label: 'Donation Volume (ml)',
                data: {!! json_encode($monthlyDonations) !!},
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
                data: {!! json_encode($monthlyFrequency) !!},
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
