@extends('layouts.parent')

@section('title', "My Infant's Milk Requests")

@section('content')
<link rel="stylesheet" href="{{ asset('css/parent_my-infant-request.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .swal2-container { z-index: 9999 !important; }
    .modal-overlay { z-index: 2000; }
    
    /* Custom styles for new detailed modal */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .detail-item label {
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        display: block;
        margin-bottom: 4px;
    }
    .detail-item p {
        font-size: 14px;
        color: #334155;
        font-weight: 500;
        margin: 0;
    }
    .allocation-list {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 13px;
    }
    .allocation-list th {
        text-align: left;
        background: #f1f5f9;
        padding: 8px;
        color: #475569;
        font-size: 11px;
        text-transform: uppercase;
    }
    .allocation-list td {
        padding: 8px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
    }
    .badge-method {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-kinship { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-no-kinship { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
    
    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 5px;
    }
    /* PDF Icon Style */
    .btn-pdf {
        background: none;
        border: none;
        cursor: pointer;
        padding: 6px;
        transition: transform 0.2s;
    }
    .btn-pdf:hover {
        background-color: #fee2e2;
        border-radius: 6px;
        transform: scale(1.1);
    }
    .btn-pdf i {
        color: #dc2626; /* Red for PDF */
        font-size: 18px;
</style>

{{-- DUMMY DATA SETUP --}}
@php
    // Simulate a parent/infant record
    $infant = (object)[
        'id' => 'P-2024-001',
        'name' => 'Baby Adam',
        'nicu' => 'NICU-A1',
        'last_updated' => '2026-01-22 09:30 AM',
        'current_weight' => 2.5,
        'requests' => [
            (object)[
                'req_id' => 101,
                'total_allocated_vol' => 60, // 30ml + 30ml
                // Detailed data for modal
                'details' => (object)[
                    'donor_id' => 'D-2024-055',
                    'donor_name' => 'Sarah Connor',
                    'consent' => 'Consent Granted (Full)',
                    'method' => 'Milk Kinship', // or 'No Milk Kinship'
                    'schedule' => 'Every 3 Hours',
                    'start_time' => '2026-01-22 08:00 AM',
                    'doctor_id' => 'DR-007',
                    'doctor_name' => 'Dr. Strange',
                    // List of actual milk packs given
                    'allocations' => [
                        (object)[
                            'milk_id' => 'M26-001',
                            'volume' => 30,
                            'time' => '2026-01-22 08:15 AM',
                            'nurse_id' => 'N-101',
                            'nurse_name' => 'Nurse Joy'
                        ],
                        (object)[
                            'milk_id' => 'M26-002',
                            'volume' => 30,
                            'time' => '2026-01-22 11:15 AM',
                            'nurse_id' => 'N-102',
                            'nurse_name' => 'Nurse Carla'
                        ]
                    ]
                ]
            ],
            // Another request example
            (object)[
                'req_id' => 102,
                'total_allocated_vol' => 30,
                'details' => (object)[
                    'donor_id' => 'D-2024-088',
                    'donor_name' => 'Jane Doe',
                    'consent' => 'Consent Granted (Restricted)',
                    'method' => 'No Milk Kinship',
                    'schedule' => 'Every 4 Hours',
                    'start_time' => '2026-01-23 10:00 AM',
                    'doctor_id' => 'DR-009',
                    'doctor_name' => 'Dr. House',
                    'allocations' => [
                        (object)[
                            'milk_id' => 'M26-005',
                            'volume' => 30,
                            'time' => '2026-01-23 10:05 AM',
                            'nurse_id' => 'N-101',
                            'nurse_name' => 'Nurse Joy'
                        ]
                    ]
                ]
            ]
        ]
    ];
@endphp

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>My Infant's Milk Requests</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Infant Milk Information</h2>
                <div class="actions">
                    <input type="text" class="form-control" placeholder="Search..." id="searchBox" style="padding:6px;font-size:14px;">
                </div>
            </div>

            <table class="infants-table" id="infantsTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">Patient Name <i class="fas fa-sort-down sort-icon sort-active"></i></th>
                        <th onclick="sortTable(1)">NICU Cubicle No. <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(2)">Total Milk Allocation <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(3)">Last Updated Weight <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(4)">Current Weight <i class="fas fa-sort sort-icon"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                <div class="patient-details">
                                    <strong>#{{ $infant->id }}</strong>
                                    <span>{{ $infant->name }}</span>
                                </div>
                            </div>
                        </td>

                        <td>{{ $infant->nicu }}</td>

                        <td>
                            <div class="milk-badge-container">
                                @foreach($infant->requests as $req)
                                    <span class="milk-badge" style="cursor: pointer;"
                                          onclick='openAllocationModal(@json($req->details), {{ $req->total_allocated_vol }})'>
                                        <i class="fas fa-flask"></i> {{ $req->total_allocated_vol }} ml
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td>{{ $infant->last_updated }}</td>

                        <td>
                            <div class="weight-display">
                                <i class="fa-solid fa-weight-scale"></i>
                                <span>{{ $infant->current_weight }} kg</span>
                            </div>
                        </td>

                       <td class="actions">
                            {{-- UPDATED: Replaced Eye Icon with Download PDF Icon --}}
                            <button class="btn-pdf" title="Download Report" onclick="downloadReport('{{ $infant->id }}')">
                                <i class="fa-solid fa-file-pdf"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ======================= MODALS ======================= --}}

{{-- Milk Allocation Detail Modal --}}
<div id="allocationDetailModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:700px;">
        <div class="modal-header">
            <h2><i class="fas fa-file-medical-alt"></i> Milk Allocation Details</h2>
            <button class="modal-close-btn" onclick="closeAllocationModal()">Close</button>
        </div>
        <div class="modal-body">
            
            {{-- 1. Donor & Milk Info --}}
            <div class="section-title"><i class="fas fa-user-circle"></i> Donor & Consent Information</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Donor Name</label>
                    <p id="modalDonorName">-</p>
                </div>
                <div class="detail-item">
                    <label>Donor ID</label>
                    <p id="modalDonorId">-</p>
                </div>
                <div class="detail-item">
                    <label>Donor Consent Status</label>
                    <p id="modalConsent" style="color:#166534; font-weight:700;">-</p>
                </div>
                <div class="detail-item">
                    <label>Total Allocated Volume</label>
                    <p id="modalTotalVol" style="color:#0369a1; font-weight:800; font-size:16px;">-</p>
                </div>
            </div>

            {{-- 2. Dispensing Method & Doctor --}}
            <div class="section-title"><i class="fas fa-prescription-bottle-alt"></i> Dispensing & Assignment</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dispensing Method</label>
                    <div id="modalMethodBadge"></div>
                </div>
                <div class="detail-item">
                    <label>Feeding Schedule</label>
                    <p id="modalSchedule">-</p>
                    <small id="modalStartTime" style="color:#64748b;">-</small>
                </div>
                <div class="detail-item">
                    <label>Assigned By (Doctor)</label>
                    <p id="modalDoctorName">-</p>
                    <small id="modalDoctorId" style="color:#64748b;">-</small>
                </div>
            </div>

            {{-- 3. Allocation History Table --}}
            <div class="section-title"><i class="fas fa-history"></i> Allocation History (Nurse Records)</div>
            <table class="allocation-list">
                <thead>
                    <tr>
                        <th>Milk Unit ID</th>
                        <th>Volume</th>
                        <th>Allocation Time</th>
                        <th>Allocated By (Nurse)</th>
                    </tr>
                </thead>
                <tbody id="allocationTableBody">
                    {{-- Rows injected via JS --}}
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>

    function downloadReport(patientId) {
            // In a real app, you would pass the ID to the route: 
            // window.open(`/parent/report-pdf/${patientId}`, '_blank');
            
            // For this demo, we assume a static route or view
            // Ensure you create the route in web.php pointing to the new file below
            window.open("{{ url('/layouts/milk_report_pdf') }}", "_blank");
        }

    function openAllocationModal(details, totalVol) {
        // 1. Populate Basic Info
        document.getElementById('modalDonorName').textContent = details.donor_name;
        document.getElementById('modalDonorId').textContent = details.donor_id;
        document.getElementById('modalConsent').textContent = details.consent;
        document.getElementById('modalTotalVol').textContent = totalVol + " ml";
        
        // 2. Method Badge
        const badgeDiv = document.getElementById('modalMethodBadge');
        if(details.method === 'Milk Kinship') {
            badgeDiv.innerHTML = `<span class="badge-method badge-kinship"><i class="fas fa-check"></i> Milk Kinship</span>`;
        } else {
            badgeDiv.innerHTML = `<span class="badge-method badge-no-kinship"><i class="fas fa-ban"></i> No Milk Kinship</span>`;
        }

        // 3. Schedule & Doctor
        document.getElementById('modalSchedule').textContent = details.schedule;
        document.getElementById('modalStartTime').textContent = "Start: " + details.start_time;
        document.getElementById('modalDoctorName').textContent = details.doctor_name;
        document.getElementById('modalDoctorId').textContent = details.doctor_id;

        // 4. Populate Table
        const tbody = document.getElementById('allocationTableBody');
        tbody.innerHTML = '';
        
        details.allocations.forEach(alloc => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="font-weight:600; color:#0f172a;">${alloc.milk_id}</td>
                <td>${alloc.volume} ml</td>
                <td>${alloc.time}</td>
                <td>
                    <strong>${alloc.nurse_name}</strong><br>
                    <span style="color:#64748b; font-size:11px;">${alloc.nurse_id}</span>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('allocationDetailModal').style.display = 'flex';
    }

    function closeAllocationModal() {
        document.getElementById('allocationDetailModal').style.display = 'none';
    }

    function openInfantProfile() {
        Swal.fire('Info', 'This would open the full infant profile view.', 'info');
    }
    
    // Sort logic (Simple placeholder)
    function sortTable(n) { console.log("Sorting column " + n); }
</script>

@endsection