@extends('layouts.donor')

@section('title', 'Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Beautified Clinical Status Colors */
    .status-tag {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        border: 1px solid transparent;
    }

    /* 1. Pre-Pasteurization (Warm Orange/Yellow) */
    .status-screening { 
        background-color: #fff7ed; 
        color: #c2410c; 
        border-color: #ffedd5; 
    }

    /* 2. Thawing (Cool Ice Blue) */
    .status-labelling { 
        background-color: #eff6ff; 
        color: #1d4ed8; 
        border-color: #dbeafe; 
    }

    /* 3. Pasteurization (Vibrant Purple/Indigo) */
    .status-distributing { 
        background-color: #eef2ff; 
        color: #4338ca; 
        border-color: #e0e7ff; 
    }

    /* 4. Microbiology (Teal/Mint) */
    .status-microbiology { 
        background-color: #f0fdf4; 
        color: #15803d; 
        border-color: #dcfce7; 
    }

    /* 5. Post-Pasteurization (Solid Green) */
    .status-post-pasteurization { 
        background-color: #ecfdf5; 
        color: #047857; 
        border-color: #d1fae5; 
    }

    /* Pending/Gray */
    .status-pending { 
        background-color: #f3f4f6; 
        color: #6b7280; 
        border-color: #e5e7eb; 
    }

    /* Profile Icon Style */
    .milk-icon-wrapper {
        background-color: #f1f5f9;
        color: #64748b;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>

<div class="container">
    <div class="main-content">
        <div class="page-header">
            <h1>Milk Records Management</h1>
            <p>Milk Processing and Records</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Milk Processing and Records</h2>
                <div class="actions-header">
                    <button class="btn btn-search">
                        <i class="fas fa-search"></i> Search &amp; Filter
                    </button>
                </div>
            </div>

            <div id="filterPanel" class="filter-panel">
                <form id="filterForm" onsubmit="event.preventDefault();">
                    <input id="searchInput" class="form-control" type="search" placeholder="Search by Milk ID">
                    <div class="filter-actions">
                        <button type="submit" class="btn">Apply</button>
                        <button type="button" class="btn">Clear</button>
                    </div>
                </form>
            </div>

            <div class="records-list">
                <div class="record-header">
                    {{-- Changed Header to MILK ID --}}
                    <button class="sortable-header" data-key="milkId">MILK ID <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="status">CLINICAL STATUS <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="volume">VOLUME <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="expiry">EXPIRATION DATE <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="shariah">SHARIAH APPROVAL <span class="sort-indicator"></span></button>
                    <span>ACTIONS</span>
                </div>

                {{-- ================================================================= --}}
                {{-- DUMMY RECORD 1 --}}
                {{-- ================================================================= --}}
                @php
                    $payload1 = [
                        'milkId' => 'M26-001',
                        'status' => 'Pre-Pasteurization',
                        'volume' => '150 mL',
                        'expiry' => '-',
                        'shariah' => 'Approved',
                        'location' => 'Reception Fridge',
                        'quality' => 'Pending Screening',
                        'timeline' => [
                            ['stage' => 'Pre-Pasteurization', 'status' => 'In Progress', 'date' => date('Y-m-d H:i')],
                            ['stage' => 'Thawing', 'status' => 'Pending', 'date' => '-'],
                            ['stage' => 'Pasteurization', 'status' => 'Pending', 'date' => '-'],
                            ['stage' => 'Microbiology', 'status' => 'Pending', 'date' => '-'],
                            ['stage' => 'Storage', 'status' => 'Pending', 'date' => '-']
                        ]
                    ];
                @endphp
                <div class="record-item" data-milk-id="1">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper"><i class="fas fa-user"></i></div>
                        <div>
                            {{-- Only showing Milk ID --}}
                            <span class="milk-id">M26-001</span>
                        </div>
                    </div>
                    <div class="clinical-status">
                        <span class="status-tag status-screening">Pre-Pasteurization</span>
                    </div>
                    <div class="volume-data">150 mL</div>
                    <div class="expiry-date">-</div>
                    <div class="shariah-status"><span class="status-tag status-approved">Approved</span></div>
                    <div class="actions">
                        <button class="btn-view" title="View Details" data-payload='@json($payload1)'><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                {{-- ================================================================= --}}
                {{-- DUMMY RECORD 2 --}}
                {{-- ================================================================= --}}
                @php
                    $payload2 = [
                        'milkId' => 'M26-002',
                        'status' => 'Thawing',
                        'volume' => '200 mL',
                        'expiry' => '-',
                        'shariah' => 'Approved',
                        'location' => 'Thawing Counter',
                        'quality' => 'Passed Screening',
                        'timeline' => [
                            ['stage' => 'Pre-Pasteurization', 'status' => 'Completed', 'date' => '2026-01-20'],
                            ['stage' => 'Thawing', 'status' => 'In Progress', 'date' => date('Y-m-d H:i')],
                            ['stage' => 'Pasteurization', 'status' => 'Pending', 'date' => '-'],
                            ['stage' => 'Microbiology', 'status' => 'Pending', 'date' => '-'],
                            ['stage' => 'Storage', 'status' => 'Pending', 'date' => '-']
                        ]
                    ];
                @endphp
                <div class="record-item" data-milk-id="2">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper"><i class="fas fa-user"></i></div>
                        <div>
                            <span class="milk-id">M26-002</span>
                        </div>
                    </div>
                    <div class="clinical-status">
                        <span class="status-tag status-labelling">Thawing</span>
                    </div>
                    <div class="volume-data">200 mL</div>
                    <div class="expiry-date">-</div>
                    <div class="shariah-status"><span class="status-tag status-approved">Approved</span></div>
                    <div class="actions">
                        <button class="btn-view" title="View Details" data-payload='@json($payload2)'><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                {{-- ================================================================= --}}
                {{-- DUMMY RECORD 3 --}}
                {{-- ================================================================= --}}
                @php
                    $payload3 = [
                        'milkId' => 'M26-003',
                        'status' => 'Pasteurization',
                        'volume' => '120 mL',
                        'expiry' => '2026-07-22',
                        'shariah' => 'Approved',
                        'location' => 'Pasteurizer Machine A',
                        'quality' => 'Heat Treatment Active',
                        'timeline' => [
                            ['stage' => 'Pre-Pasteurization', 'status' => 'Completed', 'date' => '2026-01-21'],
                            ['stage' => 'Thawing', 'status' => 'Completed', 'date' => '2026-01-21'],
                            ['stage' => 'Pasteurization', 'status' => 'In Progress', 'date' => date('Y-m-d H:i')],
                            ['stage' => 'Microbiology', 'status' => 'Pending', 'date' => '-'],
                            ['stage' => 'Storage', 'status' => 'Pending', 'date' => '-']
                        ]
                    ];
                @endphp
                <div class="record-item" data-milk-id="3">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper"><i class="fas fa-user"></i></div>
                        <div>
                            <span class="milk-id">M26-003</span>
                        </div>
                    </div>
                    <div class="clinical-status">
                        <span class="status-tag status-distributing">Pasteurization</span>
                    </div>
                    <div class="volume-data">120 mL</div>
                    <div class="expiry-date">Jul 22, 2026</div>
                    <div class="shariah-status"><span class="status-tag status-approved">Approved</span></div>
                    <div class="actions">
                        <button class="btn-view" title="View Details" data-payload='@json($payload3)'><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                {{-- ================================================================= --}}
                {{-- DUMMY RECORD 4 --}}
                {{-- ================================================================= --}}
                @php
                    $payload4 = [
                        'milkId' => 'M26-004',
                        'status' => 'Microbiology Test',
                        'volume' => '180 mL',
                        'expiry' => '2026-07-20',
                        'shariah' => 'Approved',
                        'location' => 'Lab Incubator',
                        'quality' => 'Samples under analysis',
                        'timeline' => [
                            ['stage' => 'Pre-Pasteurization', 'status' => 'Completed', 'date' => '2026-01-19'],
                            ['stage' => 'Thawing', 'status' => 'Completed', 'date' => '2026-01-20'],
                            ['stage' => 'Pasteurization', 'status' => 'Completed', 'date' => '2026-01-20'],
                            ['stage' => 'Microbiology', 'status' => 'In Progress', 'date' => date('Y-m-d')],
                            ['stage' => 'Storage', 'status' => 'Pending', 'date' => '-']
                        ]
                    ];
                @endphp
                <div class="record-item" data-milk-id="4">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper"><i class="fas fa-user"></i></div>
                        <div>
                            <span class="milk-id">M26-004</span>
                        </div>
                    </div>
                    <div class="clinical-status">
                        <span class="status-tag status-microbiology">Microbiology Test</span>
                    </div>
                    <div class="volume-data">180 mL</div>
                    <div class="expiry-date">Jul 20, 2026</div>
                    <div class="shariah-status"><span class="status-tag status-approved">Approved</span></div>
                    <div class="actions">
                        <button class="btn-view" title="View Details" data-payload='@json($payload4)'><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                {{-- ================================================================= --}}
                {{-- DUMMY RECORD 5 --}}
                {{-- ================================================================= --}}
                @php
                    $payload5 = [
                        'milkId' => 'M26-005',
                        'status' => 'Post-Pasteurization',
                        'volume' => '200 mL',
                        'expiry' => '2026-07-15',
                        'shariah' => 'Approved',
                        'location' => 'Freezer 2 - Drawer A01',
                        'quality' => 'Passed / Safe',
                        'timeline' => [
                            ['stage' => 'Pre-Pasteurization', 'status' => 'Completed', 'date' => '2026-01-15'],
                            ['stage' => 'Thawing', 'status' => 'Completed', 'date' => '2026-01-15'],
                            ['stage' => 'Pasteurization', 'status' => 'Completed', 'date' => '2026-01-15'],
                            ['stage' => 'Microbiology', 'status' => 'Passed', 'date' => '2026-01-17'],
                            ['stage' => 'Storage', 'status' => 'Stored', 'date' => '2026-01-18']
                        ]
                    ];
                @endphp
                <div class="record-item" data-milk-id="5">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper"><i class="fas fa-user"></i></div>
                        <div>
                            <span class="milk-id">M26-005</span>
                        </div>
                    </div>
                    <div class="clinical-status">
                        <span class="status-tag status-post-pasteurization">Post-Pasteurization</span>
                    </div>
                    <div class="volume-data">200 mL</div>
                    <div class="expiry-date">Jul 15, 2026</div>
                    <div class="shariah-status"><span class="status-tag status-approved">Approved</span></div>
                    <div class="actions">
                        <button class="btn-view" title="View Details" data-payload='@json($payload5)'><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <div id="paginationControls" class="pagination-controls"></div>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- VIEW MODAL --}}
{{-- ======================================================== --}}
<div id="viewMilkModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2><i class="fas fa-notes-medical"></i> Milk Record Details</h2>
            <button class="modal-close-btn" onclick="closeViewMilkModal()">Close</button>
        </div>
        <div class="modal-body">
            
            {{-- 1. HEADER INFO --}}
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #1A5F7A;">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <small style="color: #64748b;">Milk ID</small>
                        <h3 id="view-milk-id" style="margin:0; color:#1A5F7A;">-</h3>
                    </div>
                    </div>
            </div>

            {{-- 2. VITAL INFO --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <p style="margin-bottom:5px;"><strong><i class="fas fa-flask"></i> Volume:</strong> <span id="view-volume"></span></p>
                    <p style="margin-bottom:5px;"><strong><i class="fas fa-calendar-alt"></i> Expiry:</strong> <span id="view-expiry"></span></p>
                </div>
                <div>
                    <p style="margin-bottom:5px;"><strong><i class="fas fa-star"></i> Shariah:</strong> <span id="view-shariah"></span></p>
                    <p style="margin-bottom:5px;"><strong><i class="fas fa-shield-alt"></i> Safety:</strong> <span id="view-quality" style="color: green; font-weight: bold;">-</span></p>
                </div>
            </div>

            {{-- 3. CURRENT LOCATION --}}
            <div style="margin-bottom: 20px; padding: 10px; border: 2px dashed #cbd5e1; border-radius: 8px; text-align: center;">
                <label style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Current Storage Location</label>
                <div id="view-location" style="font-size: 18px; font-weight: bold; color: #1e293b; margin-top: 5px;">-</div>
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            {{-- 4. SIMPLIFIED TIMELINE --}}
            <h3 style="font-size: 16px; margin-bottom: 15px;">Processing Timeline</h3>
            <div id="timeline-container">
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const viewModal = document.getElementById("viewMilkModal");

    // Filter panel toggle
    document.querySelector('.btn-search')?.addEventListener('click', () => {
        document.getElementById('filterPanel').classList.toggle('active');
    });

    // Close modal on backdrop click
    window.addEventListener('click', e => {
        if (e.target === viewModal) viewModal.style.display = 'none';
    });

    // View button click
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
            try {
                const data = JSON.parse(btn.dataset.payload);
                openViewMilkModal(data);
            } catch (err) {
                console.error("Payload error:", err);
            }
        });
    });
});

function openViewMilkModal(data) {
    // Populate Basic Info
    document.getElementById('view-milk-id').textContent = data.milkId || '-';
    document.getElementById('view-volume').textContent = data.volume || '-';
    
    // Logic for Expiry color
    const expiryEl = document.getElementById('view-expiry');
    expiryEl.textContent = data.expiry || '-';
    if(data.expiry && data.expiry !== '-') expiryEl.style.color = '#dc2626'; // Highlight expiry
    else expiryEl.style.color = 'inherit';

    // Shariah
    document.getElementById('view-shariah').textContent = data.shariah || '-';
    
    // Location & Quality
    document.getElementById('view-location').textContent = data.location || 'Unknown';
    
    const qualityEl = document.getElementById('view-quality');
    qualityEl.textContent = data.quality || 'Pending';
    qualityEl.style.color = data.quality.includes('Passed') || data.quality.includes('Safe') ? 'green' : 'orange';

    // Render Timeline
    const timelineContainer = document.getElementById('timeline-container');
    timelineContainer.innerHTML = ''; // Clear previous

    if(data.timeline && Array.isArray(data.timeline)) {
        data.timeline.forEach(step => {
            const row = document.createElement('div');
            row.className = 'stage-row';
            row.style.cssText = 'display:flex; justify-content:space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size:14px;';
            
            // Icon selection logic
            let icon = '<i class="fas fa-circle" style="color:#ccc; margin-right:8px;"></i>';
            let statusColor = '#64748b';

            if(step.status === 'Completed' || step.status === 'Passed' || step.status === 'Stored') {
                icon = '<i class="fas fa-check-circle" style="color:#10b981; margin-right:8px;"></i>';
                statusColor = '#10b981';
            } else if (step.status === 'In Progress') {
                icon = '<i class="fas fa-spinner fa-spin" style="color:#f59e0b; margin-right:8px;"></i>';
                statusColor = '#f59e0b';
            }

            row.innerHTML = `
                <div>
                    ${icon} <strong>${step.stage}</strong>
                </div>
                <div style="text-align:right;">
                    <span style="color:${statusColor}; font-weight:600;">${step.status}</span>
                    <br>
                    <small style="color:#94a3b8;">${step.date}</small>
                </div>
            `;
            timelineContainer.appendChild(row);
        });
    }

    document.getElementById('viewMilkModal').style.display = 'flex';
}

function closeViewMilkModal() {
    document.getElementById('viewMilkModal').style.display = 'none';
}
</script>
@endsection