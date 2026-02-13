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
                <span class="stat-label">Total Milk Requests</span>
                <div class="stat-icon blue">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalRequests }}</div>
            <div class="stat-change positive">
                <i class="fas fa-check-circle"></i>
                {{ $approvedRequests }} approved
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Milk Received</span>
                <div class="stat-icon green">
                    <i class="fas fa-bottle-droplet"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($milkReceived) }}ml</div>
            <div class="stat-change positive">
                <i class="fas fa-flask-vial"></i>
                From allocations
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Requests</span>
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingRequests }}</div>
            <div class="stat-change warning">
                <i class="fas fa-exclamation-circle"></i>
                Awaiting approval
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Infant Registered</span>
                <div class="stat-icon red">
                    <i class="fas fa-baby-carriage"></i>
                </div>
            </div>
            <div class="stat-value">{{ $infantsRegistered }}</div>
            <div class="stat-change positive">
                <i class="fas fa-baby"></i>
                {{ $parent->pr_BabyName }} @if($babyAge) ({{ $babyAge }}) @endif
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
                <a href="{{ route('parent.my-infant-request') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-baby"></i></div>
                        <div class="quick-stat-label">View Milk Requests</div>
                    </div>
                    <span class="quick-stat-badge primary">View</span>
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

            <!-- Infant Info Card -->
            <div style="margin-top: 20px; padding: 15px; background: #f0fdf4; border-radius: 10px; border: 1px solid #bbf7d0;">
                <h3 style="margin: 0 0 10px 0; font-size: 14px; color: #166534;">
                    <i class="fas fa-baby"></i> My Infant
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 13px;">
                    <div><strong>Name:</strong> {{ $parent->pr_BabyName }}</div>
                    <div><strong>Gender:</strong> {{ $parent->pr_BabyGender ?? 'N/A' }}</div>
                    <div><strong>NICU:</strong> {{ $parent->pr_NICU ?? 'N/A' }}</div>
                    <div><strong>Weight:</strong> {{ $parent->pr_BabyCurrentWeight ?? 'N/A' }} kg</div>
                    <div><strong>DOB:</strong> {{ $parent->pr_BabyDOB ? \Carbon\Carbon::parse($parent->pr_BabyDOB)->format('d M Y') : 'N/A' }}</div>
                    <div><strong>Consent:</strong> {{ $parent->pr_ConsentStatus ?? 'N/A' }}</div>
                </div>
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
                            <th>REQUEST ID</th>
                            <th>DATE</th>
                            <th>DAILY VOLUME</th>
                            <th>STATUS</th>
                            <th>DOCTOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $req)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar teal"><i class="fas fa-file-medical"></i></div>
                                    <div>
                                        <div class="user-name">{{ $req->formatted_id }}</div>
                                        <div class="user-email">{{ $req->kinship_method ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $req->created_at ? $req->created_at->format('d/m/Y')  : 'N/A' }}</td>
                            <td><span class="badge badge-donor">{{ $req->total_daily_volume ?? 0 }}ml</span></td>
                            <td>
                                @if($req->status == 'Approved')
                                    <span class="badge badge-active">Approved</span>
                                @elseif($req->status == 'Waiting')
                                    <span class="badge badge-pending">Waiting</span>
                                @else
                                    <span class="badge badge-inactive">{{ ucfirst($req->status) }}</span>
                                @endif
                            </td>
                            <td>{{ optional($req->doctor)->dr_Name ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:#999;">
                                No milk requests yet.
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

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($monthLabels),
        datasets: [
            {
                label: 'Milk Request Volume (ml)',
                data: @json($monthlyVolumes),
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
                ticks: { color: '#555' }
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