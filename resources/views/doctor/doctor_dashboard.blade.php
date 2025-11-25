@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div>
            <h1>Welcome, {{ auth()->user()->name }}<br>
            <p class="muted">Shariah-compliant Human Milk Bank • Doctor Dashboard</p>
            </h1>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Patients</span>
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalPatients ?? 124 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $patientChange ?? '5%' }} from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Active Donor</span>
                <div class="stat-icon green">
                    <i class="fas fa-file-medical"></i>
                </div>
            </div>
            <div class="stat-value">{{ $activePrescriptions ?? 42 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $prescriptionChange ?? '7%' }} from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Milk Requests</span>
                <div class="stat-icon orange">
                    <i class="fas fa-baby"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingRequests ?? 8 }}</div>
            <div class="stat-change warning">
                <i class="fas fa-exclamation-circle"></i>
                Action needed
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Appointments Today</span>
                <div class="stat-icon red">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $appointmentsToday ?? 5 }}</div>
            <div class="stat-change neutral">
                <i class="fas fa-clock"></i>
                {{ $appointmentChange ?? '2 completed' }}
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Prescription Statistics -->
        <div class="card chart-card">
            <div class="card-header">
                <h2>Prescription & Milk Request Trends</h2>
                <a href="#" class="view-report">
                    View Report
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="chart-body" style="height: 300px; position: relative;">
                <canvas id="doctorStatsChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card quick-stats-card">
            <h2>Quick Actions</h2>
                    <div class="quick-stats-list">
                        <a href="{{ route('doctor.donor-candidate-list') }}" class="quick-stat-item" style="text-decoration: none;">
                            <div class="quick-stat-info">
                                <div class="quick-stat-value"><i class="fas fa-calendar-plus"></i></div>
                                <div class="quick-stat-label">View Donor List</div>
                            </div>
                            <span class="quick-stat-badge primary">View Now</span>
                        </a>
                        <a href="{{ route('doctor.doctor_milk-request-form') }}" class="quick-stat-item" style="text-decoration: none;">
                            <div class="quick-stat-info">
                                <div class="quick-stat-value"><i class="fas fa-box"></i></div>
                                <div class="quick-stat-label">Request Milk</div>
                            </div>
                            <span class="quick-stat-badge primary">Request Now</span>
                        </a>
                        <a href="{{ route('doctor.doctor_milk-request') }}" class="quick-stat-item" style="text-decoration: none;">
                            <div class="quick-stat-info">
                                <div class="quick-stat-value"><i class="fas fa-list"></i></div>
                                <div class="quick-stat-label">View Milk Record</div>
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

    <!-- Bottom Grid -->
    <div class="quick-stats-card">
        <!-- Recent Prescriptions -->
        <div class="card users-card">
            <div class="card-header">
                <h2>Milk Record</h2>
                <a href="#" class="view-all">
                    View All
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>PATIENT</th>
                            <th>REQUEST TYPE</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal">NA</div>
                                    <div>
                                        <div class="user-name">Nur Aisyah</div>
                                        <div class="user-email">nur.aisyah@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-request">Milk Request</span></td>
                            <td><span class="badge badge-active">Approved</span></td>
                            <td>May 15, 2024</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-eye"></i></button>
                                <button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar blue">AM</div>
                                    <div>
                                        <div class="user-name">Ahmad Malik</div>
                                        <div class="user-email">a.malik@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-prescription">Prescription</span></td>
                            <td><span class="badge badge-pending">Pending</span></td>
                            <td>May 14, 2024</td>
                            <td class="actions">
                                <button class="action-btn"><i class="fa-solid fa-eye"></i></button>
                                <button class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('doctorStatsChart');

// gradient fill for purple line
const gradientBlue = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientBlue.addColorStop(0, 'rgba(99, 102, 241, 0.5)');
gradientBlue.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

// gradient fill for green line
const gradientGreen = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Prescriptions',
                data: [30, 45, 55, 48, 60, 72, 90],
                borderColor: '#6366F1',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#6366F1',
                pointHoverRadius: 7,
            },
            {
                label: 'Milk Requests',
                data: [20, 35, 40, 38, 50, 65, 75],
                borderColor: '#10B981',
                backgroundColor: gradientGreen,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#10B981',
                pointHoverRadius: 7,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: '#444', boxWidth: 12, padding: 15, font: { size: 13 } }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#333',
                borderColor: '#E2E8F0',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: context => `${context.dataset.label}: ${context.formattedValue}`
                }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#555', stepSize: 20 } },
            x: { grid: { display: false }, ticks: { color: '#555' } }
        },
        animations: {
            tension: { duration: 2000, easing: 'easeOutElastic', from: 0.5, to: 0.4 }
        }
    }
});
</script>

@endsection
