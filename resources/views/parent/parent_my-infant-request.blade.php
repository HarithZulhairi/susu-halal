@extends('layouts.parent')

@section('title', "My Infant's Milk Requests")

@section('content')
<link rel="stylesheet" href="{{ asset('css/parent_my-infant-request.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .swal2-container { z-index: 9999 !important; }
    .modal-overlay { z-index: 2000; }
    
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
        color: #dc2626;
        font-size: 18px;
    }
</style>

<div>
    <div>

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
                        <th>Patient ID</th>
                        <th>NICU Cubicle No.</th>
                        <th>Milk Requests</th>
                        <th>Last Updated</th>
                        <th>Current Weight</th>
                        <!-- <th>Document</th> -->
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                <div class="patient-details">
                                    <strong>{{ $parent->formatted_id }}</strong>
                                    <!-- <span>{{ $parent->pr_BabyName }}</span> -->
                                </div>
                            </div>
                        </td>

                        <td>{{ $parent->pr_NICU ?? 'N/A' }}</td>

                        <td>
                            <div class="milk-badge-container">
                                @forelse($parent->requests as $req)
                                    @php
                                        $modalData = [
                                            'request_id' => $req->formatted_id,
                                            'doctor_name' => optional($req->doctor)->dr_Name ?? 'N/A',
                                            'doctor_id' => $req->doctor ? '#DR' . $req->doctor->dr_ID : 'N/A',
                                            'kinship_method' => $req->kinship_method ?? 'N/A',
                                            'feeding_interval' => $req->feeding_interval ?? 'N/A',
                                            'volume_per_feed' => $req->volume_per_feed ?? 'N/A',
                                            'status' => $req->status,
                                            'feeding_start_date' => $req->feeding_start_date,
                                            'feeding_start_time' => $req->feeding_start_time,
                                            
                                            'allocations' => $req->allocations->map(function($alloc) {
                                                $donor = optional($alloc->postBottles?->milk?->donor);
                                                return [
                                                    'allocation_id' => $alloc->allocation_ID,
                                                    'post_id' => $alloc->post_ID,
                                                    'volume' => $alloc->total_selected_milk ?? 'N/A',
                                                    'time' => $alloc->created_at ? $alloc->created_at->format('d/m/Y • h:i A') : 'N/A',
                                                    'nurse_name' => optional($alloc->nurse)->ns_Name ?? 'N/A',
                                                    'nurse_id' => $alloc->nurse ? '#NS' . $alloc->nurse->ns_ID : 'N/A',
                                                    'donor_id' => $donor->dn_ID ? '#D' . $donor->dn_ID : 'N/A',
                                                    'donor_contact' => $donor->dn_Contact ?? 'N/A',
                                                    'donor_consent' => $donor->dn_ConsentStatus ?? 'Granted'
                                                ];
                                            })->toArray()
                                        ];
                                    @endphp
                                    <span class="milk-badge" style="cursor: pointer;"
                                          onclick='openAllocationModal(@json($modalData), "{{ $req->volume_per_feed ?? 0 }}")'>
                                        <i class="fas fa-flask"></i> {{ $req->formatted_id }} - {{ ucfirst($req->status) }}
                                    </span>
                                @empty
                                    <span style="color:#999;">No requests</span>
                                @endforelse
                            </div>
                        </td>

                        <td>{{ $parent->updated_at?->format('d/m/Y • h:i A') ?? 'N/A' }}</td>

                        <td>
                            <div class="weight-display">
                                <i class="fa-solid fa-weight-scale"></i>
                                <span>{{ $parent->pr_BabyCurrentWeight ?? 'N/A' }} kg</span>
                            </div>
                        </td>

                       <!-- <td class="actions">
                            <button class="btn-pdf" title="Download Report" onclick="downloadReport('{{ $parent->pr_ID }}')">
                                <i class="fa-solid fa-file-pdf"></i>
                            </button>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ======================= MODALS ======================= --}}

{{-- Milk Request Detail Modal --}}
<div id="allocationDetailModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:700px;">
        <div class="modal-header">
            <h2><i class="fas fa-file-medical-alt"></i> Milk Request Details</h2>
            <button class="modal-close-btn" onclick="closeAllocationModal()">Close</button>
        </div>
        <div class="modal-body">

            {{-- 2. Donor & Consent Info --}}
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
            
            {{-- 2. Request & Doctor Info --}}
            <div class="section-title"><i class="fas fa-user-circle"></i> Request & Doctor Information</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Request ID</label>
                    <p id="modalRequestId">-</p>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <p id="modalStatus" style="font-weight:700;">-</p>
                </div>
                <div class="detail-item">
                    <label>Assigned By (Doctor)</label>
                    <p id="modalDoctorName">-</p>
                    <small id="modalDoctorId" style="color:#64748b;">-</small>
                </div>
                <div class="detail-item">
                    <label>Volume Per Feed</label>
                    <p id="modalTotalVol" style="color:#0369a1; font-weight:800; font-size:16px;">-</p>
                </div>
            </div>

            {{-- 3. Feeding Details --}}
            <div class="section-title"><i class="fas fa-prescription-bottle-alt"></i> Feeding & Assignment</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Kinship Method</label>
                    <div id="modalMethodBadge"></div>
                </div>
                <div class="detail-item">
                    <label>Feeding Interval</label>
                    <p id="modalSchedule">-</p>
                    <small id="modalStartTime" style="color:#64748b;">-</small>
                </div>
            </div>

            {{-- 4. Allocation History Table --}}
            <div class="section-title"><i class="fas fa-history"></i> Allocation History (Nurse Records)</div>
            <table class="allocation-list">
                <thead>
                    <tr>
                        <th>Allocation ID</th>
                        <th>Bottle ID</th>
                        <th>Volume</th>
                        <th>Time</th>
                        <th>Allocated By (Nurse)</th>
                        <th>Donor Details</th>
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

    function downloadReport(parentId) {
        window.open("{{ url('/layouts/milk_report_pdf') }}/" + parentId, "_blank");
    }

    // Search Functionality
    document.getElementById('searchBox').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let requests = document.querySelectorAll('.milk-badge'); // Target the request badges

        requests.forEach(badge => {
            let badgeText = badge.innerText.toLowerCase();
            if (badgeText.includes(searchText)) {
                badge.style.display = 'inline-block'; // Show matching request
            } else {
                badge.style.display = 'none'; // Hide non-matching request
            }
        });
    });


    function openAllocationModal(details, volumePerFeed) {
        // 1. Populate Basic Info
        document.getElementById('modalRequestId').textContent = details.request_id;
        document.getElementById('modalStatus').textContent = details.status;
        document.getElementById('modalDoctorName').textContent = details.doctor_name;
        document.getElementById('modalDoctorId').textContent = details.doctor_id;
        document.getElementById('modalTotalVol').textContent = volumePerFeed + " ml/feed";
        if (details.allocations && details.allocations.length > 0) {
        // document.getElementById('modalDonorName').textContent = details.allocations[0].donor_id; // Or donor_name if available
        document.getElementById('modalDonorId').textContent = details.allocations[0].donor_id;
        document.getElementById('modalConsent').textContent = details.allocations[0].donor_consent;
    }
        
        // 2. Method Badge
        const badgeDiv = document.getElementById('modalMethodBadge');
        if(details.kinship_method && details.kinship_method.toLowerCase().includes('kinship')) {
            badgeDiv.innerHTML = `<span class="badge-method badge-kinship"><i class="fas fa-check"></i> ${details.kinship_method}</span>`;
        } else {
            badgeDiv.innerHTML = `<span class="badge-method badge-no-kinship"><i class="fas fa-info-circle"></i> ${details.kinship_method || 'N/A'}</span>`;
        }

        // 3. Schedule
        document.getElementById('modalSchedule').textContent = "Every " + (details.feeding_interval || 'N/A') + " hours";
        const startStr = (details.feeding_start_date || '') + ' ' + (details.feeding_start_time || '');
        document.getElementById('modalStartTime').textContent = startStr.trim() ? "Start: " + startStr.trim() : '';

        // 4. Populate Allocation Table
        const tbody = document.getElementById('allocationTableBody');
        tbody.innerHTML = '';
        
        if (details.allocations && details.allocations.length > 0) {
            details.allocations.forEach(alloc => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600; color:#0f172a;">#A${alloc.allocation_id}</td>
                    <td>#P${alloc.post_id || 'N/A'}</td>
                    <td>${alloc.volume} ml</td>
                    <td>${alloc.time}</td>
                    <td>
                        <strong>${alloc.nurse_name}</strong><br>
                        <span style="color:#64748b; font-size:11px;">${alloc.nurse_id}</span>
                    </td>
                    <td>
                        <div style="font-size:12px; line-height:1.4;">
                            <strong style="color:#0ea5e9;">${alloc.donor_id}</strong><br>
                            <i class="fas fa-phone fa-xs"></i> ${alloc.donor_contact}<br>
                            <span class="badge-method badge-kinship" style="font-size:9px; padding:2px 5px;">
                                ${alloc.donor_consent}
                            </span>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td colspan="5" style="text-align:center; padding:15px; color:#999;">No allocations yet for this request.</td>`;
            tbody.appendChild(tr);
        }

        document.getElementById('allocationDetailModal').style.display = 'flex';
    }

    function closeAllocationModal() {
        document.getElementById('allocationDetailModal').style.display = 'none';
    }
    
    // Close modal on outside click
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    });
    
    // Sort logic
    function sortTable(n) { console.log("Sorting column " + n); }
</script>

@endsection