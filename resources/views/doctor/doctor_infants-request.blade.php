@extends('layouts.doctor')

@section('title', "Infant Milk Traceability")

@section('content')
<link rel="stylesheet" href="{{ asset('css/nurse_infants-request.css') }}">
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
    }
</style>

{{-- DUMMY DATA BLOCK REMOVED --}}

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>Infant Milk Traceability</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Infant Milk Information</h2>
                <div class="actions">
                    <input type="text" class="form-control" placeholder="Search Patient Name or ID..." id="searchBox" style="padding:6px;font-size:14px; width:300px;">
                </div>
            </div>

            <table class="infants-table" id="infantsTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">Patient Name <i class="fas fa-sort-down sort-icon sort-active"></i></th>
                        <th onclick="sortTable(1)">NICU Cubicle No. <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(2)">Total Milk Allocation <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(3)">Last Updated <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(4)">Baby Gender <i class="fas fa-sort sort-icon"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($infants as $infant)
                    <tr>
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                <div class="patient-details">
                                    <strong>{{ $infant->id }}</strong>
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
                            @if($infant->baby_gender === 'Male')
                                <div class="weight-display" style="color:#1e40af; border:1px solid #93c5fd;">
                                    <i class="fa-solid fa-mars" style="color:#3b82f6;"></i>
                                    <span>{{ $infant->baby_gender }}</span>
                                </div>
                            @elseif($infant->baby_gender === 'Female')
                                <div class="weight-display" style="background-color:#fce7f3; color:#9d174d; border:1px solid #fbcfe8;">
                                    <i class="fa-solid fa-venus" style="color:#ec4899;"></i>
                                    <span>{{ $infant->baby_gender }}</span>
                                </div>
                            @else
                                <div class="weight-display" style="background-color:#e0e0e0; color:#4b5563; border:1px solid #d1d5db;">
                                    <i class="fa-solid fa-genderless" style="color:#6b7280;"></i>
                                    <span>{{ $infant->baby_gender }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="actions">
                            {{-- Download PDF Icon --}}
                            <button class="btn-pdf" title="Download Traceability Report" onclick="downloadReport('{{ $infant->raw_id }}')">
                                <i class="fa-solid fa-file-pdf"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 20px; color: #64748b;">No traceability records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- implements pagination -->
            <div id="paginationControls" class="pagination-controls" style="margin-top:15px; text-align:center;"></div>
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
            
            {{-- 1. Patient & Consent Info --}}
            <div class="section-title"><i class="fas fa-baby"></i> Patient & Consent Information</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Patient Name</label>
                    <p id="modalPatientName">-</p>
                </div>
                <div class="detail-item">
                    <label>Patient ID</label>
                    <p id="modalPatientId">-</p>
                </div>
                <div class="detail-item">
                    <label>NICU Location</label>
                    <p id="modalNICU">-</p>
                </div>
                <div class="detail-item">
                    <label>Parent Consent</label>
                    <p id="modalParentConsent" style="color:#166534; font-weight:700;">-</p>
                </div>
            </div>

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

            {{-- 3. Dispensing Method & Doctor --}}
            <div class="section-title"><i class="fas fa-prescription-bottle-alt"></i> Dispensing & Assignment</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dispensing Kinship</label>
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
                <div class="detail-item">
                    <label>Status</label>
                    <p id="modalStatus" style="color:#166534; font-weight:700;">-</p>
                </div>
                <div class="detail-item">
                    <label>Method Feeding</label>
                    <p id="modalDirectOral"></p>

                    @if('modalFeedingTube')
                        <p id="modalFeedingTube"></p>
                    @endif

                </div>
                <div class="detail-item">
                    <label>Volume Feed</label>
                    <p id="modalVolumeFeedOral" ></p>

                    @if('modalVolumeFeedingTube')
                        <p id="modalVolumeFeedingTube"></p>
                    @endif

                </div>
            </div>

            {{-- 4. Allocation History Table --}}
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
        const url = "{{ route('nurse.milk-report') }}?patient_id=" + patientId;
        window.open(url, "_blank");
    }

    function openAllocationModal(details, totalVol) {
        // 1. Populate Patient Info
        document.getElementById('modalPatientName').textContent = details.patient_name;
        document.getElementById('modalPatientId').textContent = details.patient_id;
        document.getElementById('modalNICU').textContent = details.patient_nicu;
        document.getElementById('modalParentConsent').textContent = details.parent_consent;

        // 2. Populate Donor Info
        document.getElementById('modalDonorName').textContent = details.donor_name;
        document.getElementById('modalDonorId').textContent = details.donor_id;
        document.getElementById('modalConsent').textContent = details.consent;
        document.getElementById('modalTotalVol').textContent = totalVol + " ml";
        
        // 3. Method Badge
        const badgeDiv = document.getElementById('modalMethodBadge');
        if(details.method === 'Milk Kinship') {
            badgeDiv.innerHTML = `<span class="badge-method badge-kinship"><i class="fas fa-check"></i> Milk Kinship</span>`;
        } else {
            badgeDiv.innerHTML = `<span class="badge-method badge-no-kinship"><i class="fas fa-ban"></i> No Milk Kinship</span>`;
        }

        // make this bold details.tube_volume
        const tubeVolumeElement = document.getElementById('modalVolumeFeedingTube');
        const feedingTubeElement = document.getElementById('modalFeedingTube');
        const oralVolumeElement = document.getElementById('modalVolumeFeedOral');
        const directOralElement = document.getElementById('modalDirectOral');
        

        // 4. Schedule & Doctor
        document.getElementById('modalSchedule').textContent = details.schedule;
        document.getElementById('modalStartTime').textContent = "Start: " + details.start_time;
        document.getElementById('modalDoctorName').textContent = details.doctor_name;
        document.getElementById('modalDoctorId').textContent = details.doctor_id;
        document.getElementById('modalStatus').textContent = details.status;

        directOralElement.innerHTML = `Direct Oral Feeding: <strong>${details.direct_oral}</strong>`;
        oralVolumeElement.innerHTML = `Oral Feeding Volume: <strong>${details.oral_volume}</strong>`;

        if(details.feeding_tube) {
            tubeVolumeElement.innerHTML = `Feeding Tube: <strong>${details.tube_volume}</strong>`;
            feedingTubeElement.innerHTML = `Feeding Tube: <strong>${details.feeding_tube}</strong>`;
            document.getElementById('modalVolumeFeedingTube').style.display = 'block';
            document.getElementById('modalFeedingTube').style.display = 'block';
        } else {
            document.getElementById('modalFeedingTube').textContent = "";
            document.getElementById('modalFeedingTube').style.display = 'none';
            document.getElementById('modalVolumeFeedingTube').style.display = 'none';
        }

        // 5. Populate Table
        const tbody = document.getElementById('allocationTableBody');
        tbody.innerHTML = '';
        
        if (details.allocations && details.allocations.length > 0) {
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
        } else {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding:10px;">No allocation records found.</td></tr>';
        }

        document.getElementById('allocationDetailModal').style.display = 'flex';
    }

    function closeAllocationModal() {
        document.getElementById('allocationDetailModal').style.display = 'none';
    }

    /* === Sorting Logic === */
    let sortDirection = { 0: false };

    function sortTable(columnIndex) {
        const table = document.getElementById("infantsTable");
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.rows);
        const headers = table.querySelectorAll('th');

        sortDirection[columnIndex] = !sortDirection[columnIndex];
        const asc = sortDirection[columnIndex];

        rows.sort((a, b) => {
            const A = a.cells[columnIndex].innerText.trim();
            const B = b.cells[columnIndex].innerText.trim();

            return asc
                ? (A.localeCompare(B))
                : (B.localeCompare(A));
        });

        tbody.append(...rows);

        headers.forEach((th, idx) => {
            const icon = th.querySelector('.sort-icon');
            if (!icon) return;

            icon.className = 'fas fa-sort sort-icon';

            if (idx === columnIndex) {
                icon.classList.add('sort-active');
                icon.classList.remove('fa-sort');
                icon.classList.add(asc ? 'fa-sort-up' : 'fa-sort-down');
            }
        });
    }

    /* === SEARCH FUNCTION === */
    document.getElementById("searchBox").addEventListener("input", function () {
        const term = this.value.toLowerCase().trim();
        const table = document.getElementById("infantsTable");
        const rows = table.tBodies[0].rows;

        for (let row of rows) {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? "" : "none";
        }
    });

    // ==========================================
    //  PAGINATION LOGIC (Numbered Pages)
    // ==========================================
    // ==========================================
    //  PAGINATION LOGIC (Table Rows)
    // ==========================================
    (function setupPagination() {
        // Correct Selector: The Table Body
        const tableBody = document.querySelector('#infantsTable tbody');
        const controls = document.getElementById('paginationControls');
        const perPage = 10;
        let currentPage = 1;

        if (!tableBody || !controls) return;

        // Helper to get actual rows (ignoring potential future non-row elements)
        function getRows() {
            // Convert HTMLCollection to Array for slicing
            // Filter only rows that are not hidden by search logic (optional but good)
            return Array.from(tableBody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
        }

        function renderPage(page) {
            // We need ALL rows to calculate totals, but only VISIBLE rows for paging if search is active
            // For basic pagination without search interaction:
            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            
            // If you want search + pagination to work together, use getRows() 
            // but you need to reset display:none on all before reapplying logic.
            // For simpler logic (Pagination ON TOP of full list):
            
            const totalPages = Math.max(1, Math.ceil(allRows.length / perPage));

            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPage = page;

            // Hide all rows first
            allRows.forEach(r => r.style.display = 'none');

            // Show current slice
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            
            // Display as table-row ('') is standard for <tr>
            allRows.slice(start, end).forEach(r => r.style.display = ''); 

            renderControls(totalPages);
        }

        function renderControls(totalPages) {
            controls.innerHTML = '';

            // Prev Button
            const prev = document.createElement('button');
            prev.className = 'page-btn';
            prev.innerHTML = '&lsaquo; Prev';
            prev.disabled = currentPage === 1;
            prev.style.margin = "0 5px";
            prev.onclick = () => renderPage(currentPage - 1);
            controls.appendChild(prev);

            // Numbered Buttons
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = 'page-btn';
                btn.style.margin = "0 2px";
                btn.style.padding = "5px 10px";
                
                if (i === currentPage) {
                    btn.classList.add('active');
                    btn.style.backgroundColor = "#0ea5e9";
                    btn.style.color = "white";
                    btn.style.border = "1px solid #0ea5e9";
                } else {
                    btn.style.backgroundColor = "white";
                    btn.style.border = "1px solid #ddd";
                }
                
                btn.textContent = i;
                btn.onclick = () => renderPage(i);
                controls.appendChild(btn);
            }

            // Next Button
            const next = document.createElement('button');
            next.className = 'page-btn';
            next.innerHTML = 'Next &rsaquo;';
            next.disabled = currentPage === totalPages;
            next.style.margin = "0 5px";
            next.onclick = () => renderPage(currentPage + 1);
            controls.appendChild(next);
        }

        // Hook into Search to reset pagination
        const searchBox = document.getElementById("searchBox");
        if(searchBox) {
            searchBox.addEventListener("input", function () {
                // If searching, we might want to disable pagination or 
                // just show all matching rows. 
                // Simple approach: Show all if searching, paginate if empty.
                const term = this.value.toLowerCase().trim();
                const allRows = Array.from(tableBody.querySelectorAll('tr'));

                if (term === "") {
                    renderPage(1); // Restore pagination
                } else {
                    controls.innerHTML = ''; // Hide controls
                    allRows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(term) ? "" : "none";
                    });
                }
            });
        }

        // Expose function if needed
        window.__rebuildPagination = function(page) {
            renderPage(page || 1);
        };

        // Initial Render
        renderPage(1);
    })();
</script>
@endsection