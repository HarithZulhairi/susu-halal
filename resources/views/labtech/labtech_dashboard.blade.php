@extends('layouts.labtech')

@section('title', 'Lab Technician Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">
        <div class="page-header">
            <div class="header-content">
                <div>
                    <h1>Welcome, {{ auth()->user()->name }}<br>
                    <p class="muted">Shariah-compliant Human Milk Bank • Lab Technician dashboard</p>
                    </h1>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total Milk Batches</span>
                    <div class="stat-icon blue">
                        <i class="fas fa-bottle-droplet"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalSamples ?? 0 }}</div>
                <div class="stat-change neutral">
                    <i class="fas fa-info-circle"></i>
                    Total donations received
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Labeled & Processed</span>
                    <div class="stat-icon green">
                        <i class="fas fa-flask"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $processedSamples ?? 0 }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-check"></i>
                    Stage 1 Completed
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Ready for Pasteurization</span>
                    <div class="stat-icon orange">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingPasteurization ?? 0 }}</div>
                <div class="stat-change warning">
                    <i class="fas fa-exclamation-circle"></i>
                    Thawed (Stage 2 Done)
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Active Bottles</span>
                    <div class="stat-icon purple">
                        <i class="fas fa-snowflake"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $activeBottles ?? 0 }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-warehouse"></i>
                    Stage 5 Completed
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card chart-card">
                <div class="card-header">
                    <h2>Processing (Stage 1) vs Storage (Stage 5)</h2>
                </div>
                <div class="chart-body" style="height: 400px; position: relative;">
                    <canvas id="labTechChart"></canvas>
                </div>
            </div>

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

            <div class="quick-stats" style="grid-column: span 2;">
                <div class="card milk-records-card ">
                    <div class="card-header">
                        <h2>Recent Milk Batches</h2>
                        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="view-all">
                            View All Records
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-container">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>MILK DONOR</th>
                                    <th>CLINICAL STATUS</th>
                                    <th>VOLUME</th>
                                    <th>SHARIAH APPROVAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($milks as $milk)
                                <tr data-milk-id="{{ $milk->milk_ID }}">
                                    <td>
                                        <div class="milk-donor-info">
                                            <div class="milk-icon-wrapper">
                                                <i class="fas fa-bottle-droplet milk-icon"></i>
                                            </div>
                                            <div>
                                                <span class="milk-id">{{ $milk->formatted_id }}</span>
                                                <span class="donor-name">{{ $milk->donor?->dn_FullName ?? 'Unknown Donor' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $rawStatus = $milk->milk_Status ?? 'Not Yet Started';
                                            $statusClass = strtolower(str_replace(' ', '-', $rawStatus));
                                            $statusBaseMap = [
                                                'not' => 'pending',
                                                'processing' => 'processing',
                                                'completed' => 'completed',
                                                'screening' => 'processing',
                                                'labelling' => 'processing',
                                                'thawing' => 'processing',
                                                'pasteurization' => 'processing',
                                                'microbiology' => 'processing',
                                                'storage' => 'completed'
                                            ];
                                            $firstWord = strtolower(explode(' ', $rawStatus)[0]);
                                            $baseClass = $statusBaseMap[$firstWord] ?? 'pending';
                                        @endphp
                                        <a href="{{ route('labtech.labtech_process-milk', $milk->milk_ID) }}"
                                           class="status-tag status-{{ $baseClass }} status-{{ $statusClass }} status-clickable"
                                           title="Click to continue processing">
                                            {{ $rawStatus }}
                                        </a>
                                    </td>
                                    <td>{{ $milk->milk_volume }} mL</td>
                                    <td>
                                        @php
                                            $approval = $milk->milk_shariahApproval;
                                        @endphp
                                        <span class="status-tag {{ is_null($approval) ? 'status-pending' : ($approval ? 'status-approved' : 'status-rejected') }}">
                                            {{ is_null($approval) ? 'Not Yet Reviewed' : ($approval ? 'Approved' : 'Rejected') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        No milk records yet. Add one to begin!
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
</div>

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
        labels: @json($months),
        datasets: [
            {
                label: 'Batches Processed (Stage 1)',
                data: @json($processedMonthly),
                borderColor: '#3B82F6',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3B82F6',
                pointHoverRadius: 7,
            },
            {
                label: 'Batches Stored (Stage 5)',
                data: @json($dispatchedMonthly),
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