@extends('layouts.nurse')

@section('title', 'Nurse Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/nurse_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">
        <div class="page-header">
            <div class="header-content">
                <h1>Welcome, {{ auth()->user()->name }}<br>
                <p class="muted">Shariah-compliant Human Milk Bank • Nurse dashboard</p>
                </h1>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Active Donors</span>
                    <div class="stat-icon blue">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $activeDonors ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Pending Appointments</span>
                    <div class="stat-icon green">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingAppointments ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Milk Requests</span>
                    <div class="stat-icon orange">
                        <i class="fas fa-hand-holding-medical"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $milkRequests ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Lab Processing Queue</span>
                    <div class="stat-icon red">
                        <i class="fas fa-industry"></i>
                    </div>
                </div>
                {{-- This now reflects real data from Milk table (Stages 1-4) --}}
                <div class="stat-value">{{ $processingQueue ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.8em;">Batches in progress</small>
            </div>
        </div>

        <div class="content-grid">
            <div class="card donations-card">
                <div class="card-header">
                    <h2>Milk Collection Volume ({{ date('Y') }})</h2>
                    <a href="{{ route('nurse.manage-milk-records') }}" class="view-report">
                        Manage Records
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="chart-body" style="height: 400px; position: relative;">
                    <canvas id="milkVolumeChart"></canvas>
                </div>
            </div>

            <div class="card quick-stats-card">
                <h2>Quick Actions</h2>
                <div class="quick-stats-list">
                    <a href="{{ route('nurse.donor-appointment-record') }}" class="quick-stat-item" style="text-decoration: none;">
                        <div class="quick-stat-info">
                            <div class="quick-stat-value"><i class="fas fa-calendar-alt"></i></div>
                            <div class="quick-stat-label">Appointments</div>
                        </div>
                        <span class="quick-stat-badge primary">Manage</span>
                    </a>
                    <a href="{{ route('nurse.nurse_milk-request-list') }}" class="quick-stat-item" style="text-decoration: none;">
                        <div class="quick-stat-info">
                            <div class="quick-stat-value"><i class="fas fa-baby"></i></div>
                            <div class="quick-stat-label">Milk Requests</div>
                        </div>
                        <span class="quick-stat-badge primary">Review</span>
                    </a>
                    <a href="{{ route('nurse.allocate-milk') }}" class="quick-stat-item" style="text-decoration: none;">
                        <div class="quick-stat-info">
                            <div class="quick-stat-value"><i class="fas fa-tasks"></i></div>
                            <div class="quick-stat-label">Allocate Milk</div>
                        </div>
                        <span class="quick-stat-badge primary">Allocate</span>
                    </a>
                    <a href="{{ route('nurse.donor-candidate-list') }}" class="quick-stat-item" style="text-decoration: none;">
                        <div class="quick-stat-info">
                            <div class="quick-stat-value"><i class="fas fa-users"></i></div>
                            <div class="quick-stat-label">Donor Screening</div>
                        </div>
                        <span class="quick-stat-badge primary">Screen</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="quick-stats-card">
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
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($todayAppointments as $app)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar {{ ['teal','blue','dark-teal','pink'][$loop->index % 4] }}">
                                        @if($app->dn_FullName)
                                            @php
                                                $names = explode(' ', $app->dn_FullName);
                                                $initials = '';
                                                if(count($names) >= 2) {
                                                    $initials = substr($names[0], 0, 1) . substr($names[1], 0, 1);
                                                } else {
                                                    $initials = substr($app->dn_FullName, 0, 2);
                                                }
                                            @endphp
                                            {{ strtoupper($initials) }}
                                        @else
                                            UD
                                        @endif
                                    </div>
                                    <div>
                                        <div class="user-name">
                                            {{ $app->dn_FullName ?? 'Unknown Donor' }}
                                        </div>
                                        <div class="user-email">
                                            ID: {{ $app->dn_id ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($app->appointment_datetime)->format('h:i A') }}</td>

                            <td>
                                <span class="badge badge-donor">
                                    Donation
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-{{ strtolower($app->status ?? 'pending') }}">
                                    {{ $app->status ?? 'Pending' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 20px; color: #888;">
                                No appointments scheduled for today.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('milkVolumeChart');

    // Gradient for Volume
    const gradientBlue = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradientBlue.addColorStop(0, 'rgba(75, 156, 211, 0.5)');
    gradientBlue.addColorStop(1, 'rgba(75, 156, 211, 0.05)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [
                {
                    label: 'Total Volume Collected (mL)',
                    data: @json($volumeData), // Real data from controller
                    borderColor: '#1A5F7A',   // Matching your theme blue
                    backgroundColor: gradientBlue,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#1A5F7A',
                    pointHoverRadius: 7,
                    borderWidth: 2
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
                        padding: 15,
                        font: { size: 13, family: "'Poppins', sans-serif" }
                    }
                },
                tooltip: {
                    usePointStyle: true,
                    backgroundColor: '#fff',
                    titleColor: '#1A5F7A',
                    bodyColor: '#333',
                    borderColor: '#E2E8F0',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 5,
                    callbacks: {
                        label: function(context) {
                            return ` ${context.dataset.label}: ${context.formattedValue} mL`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { 
                        color: '#64748b',
                        callback: function(value) { return value + ' ml'; }
                    },
                    title: {
                        display: true,
                        text: 'Volume (Milliliters)',
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                }
            },
            animations: {
                tension: {
                    duration: 1500,
                    easing: 'easeOutQuart',
                    from: 0.5,
                    to: 0.4,
                    loop: false
                }
            }
        }
    });
</script>
@endsection