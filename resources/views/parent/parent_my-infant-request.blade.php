@extends('layouts.parent')

@section('title', "My Infant's Milk Requests")

@section('content')
<link rel="stylesheet" href="{{ asset('css/hmmc_list-of-infants.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .swal2-container { z-index: 9999 !important; }
    .modal-overlay { z-index: 2000; }
</style>

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
                        <th onclick="sortTable(2)">Milk Allocation <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(3)">Last Updated Weight <i class="fas fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(4)">Current Weight <i class="fas fa-sort sort-icon"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td data-order="{{ $parent->pr_ID }}">
                            <div class="patient-info">
                                <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                <div class="patient-details">
                                    <strong>#P{{ $parent->pr_ID }}</strong>
                                    <span>{{ $parent->pr_BabyName }}</span>
                                </div>
                            </div>
                        </td>

                        <td data-order="{{ $parent->pr_NICU }}">
                            {{ $parent->pr_NICU }}
                        </td>

                        <td data-order="{{ $parent->requests->pluck('allocation')->flatten()->count() }}">
                            <div class="milk-badge-container">

                                @php $hasMilk = false; @endphp

                                @foreach($parent->requests as $req)
                                    @foreach($req->allocation as $alloc)
                                        @if($alloc->milk)
                                            @php
                                                $hasMilk = true;
                                                $jsonString = is_array($alloc->allocation_milk_date_time)
                                                    ? json_encode($alloc->allocation_milk_date_time)
                                                    : $alloc->allocation_milk_date_time;
                                            @endphp

                                            <span class="milk-badge"
                                                onclick="openMilkDetailModal(
                                                    '{{ $alloc->milk->formatted_id ?? 'M-'.$alloc->milk->milk_ID }}',
                                                    '{{ $alloc->milk->milk_volume }}',
                                                    '{{ addslashes($jsonString) }}'
                                                )">
                                                <i class="fas fa-flask"></i>
                                                {{ $alloc->milk->formatted_id ?? 'M-'.$alloc->milk->milk_ID }}
                                            </span>
                                        @endif
                                    @endforeach
                                @endforeach

                                @if(!$hasMilk)
                                    <span class="no-data">No allocations yet</span>
                                @endif
                            </div>
                        </td>

                        <td data-order="{{ $parent->updated_at ? $parent->updated_at->timestamp : 0 }}">
                            {{ $parent->updated_at?->format('d-m-Y h:i A') ?? '-' }}
                        </td>

                        <td data-order="{{ $parent->pr_BabyCurrentWeight }}">
                            <div class="weight-display">
                                <i class="fa-solid fa-weight-scale"></i>
                                <span>{{ $parent->pr_BabyCurrentWeight }} kg</span>
                            </div>
                        </td>

                        <td class="actions">
                            <button class="btn-view" title="View"
                                onclick="openViewModal(
                                    '{{ '#P'.$parent->pr_ID }}',
                                    '{{ $parent->pr_BabyName }}',
                                    '{{ $parent->pr_NICU }}',
                                    '{{ $parent->updated_at?->format('d-m-Y h:i A') }}',
                                    '{{ $parent->pr_BabyCurrentWeight }}'
                                )">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>

            {{-- No pagination for a single infant --}}
        </div>

    </div>
</div>

{{-- ======================= MODALS ======================= --}}

{{-- View Infant Modal --}}
<div id="viewInfantModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header"><h2>Infant Information</h2><button class="modal-close-btn" onclick="closeViewModal()">Close</button></div>
        <div class="modal-body">
            <p><strong>Patient ID:</strong> <span id="viewPatientId">-</span></p>
            <p><strong>Name:</strong> <span id="viewPatientName">-</span></p>
            <p><strong>NICU Cubicle:</strong> <span id="viewCubicle">-</span></p>
            <hr>
            <p><strong>Last Updated:</strong> <span id="viewLastUpdated">-</span></p>
            <p><strong>Current Weight:</strong> <span id="viewWeight">-</span></p>
        </div>
    </div>
</div>

{{-- Milk Detail Modal --}}
<div id="milkDetailModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div class="modal-header"><h2>Milk Allocation Details</h2><button class="modal-close-btn" onclick="closeMilkDetailModal()">Close</button></div>
        <div class="modal-body">
            <div style="background:#f0f9ff;padding:15px;border-radius:8px;margin-bottom:20px;">
                <h3 id="modalMilkId" style="margin:0;color:#0369a1;">-</h3>
                <span style="font-size:13px;color:#64748b;">Milk Unit ID</span>
            </div>

            <div class="modal-section">
                <label style="font-size:12px;color:#64748b;font-weight:600;">VOLUME</label>
                <p id="modalMilkVolume" style="font-size:18px;font-weight:700;color:#334155;margin-top:0;">-</p>
            </div>

            <div class="modal-section">
                <label style="font-size:12px;color:#64748b;font-weight:600;">ALLOCATION DATE & TIME</label>
                <p id="modalMilkDate" style="font-size:16px;color:#334155;margin-top:0;">-</p>
            </div>
        </div>
    </div>
</div>

<script>
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
            const A = a.cells[columnIndex].getAttribute('data-order');
            const B = b.cells[columnIndex].getAttribute('data-order');

            return asc
                ? (A > B ? 1 : -1)
                : (A < B ? 1 : -1);
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

    /* === Infant Modal === */
    function openViewModal(id, name, nicu, updated, weight) {
        document.getElementById('viewPatientId').textContent = id;
        document.getElementById('viewPatientName').textContent = name;
        document.getElementById('viewCubicle').textContent = nicu;
        document.getElementById('viewLastUpdated').textContent = updated;
        document.getElementById('viewWeight').textContent = weight + " kg";
        document.getElementById('viewInfantModal').style.display = 'flex';
    }

    function closeViewModal() {
        document.getElementById('viewInfantModal').style.display = 'none';
    }

    /* === Milk Detail Modal === */
    function openMilkDetailModal(id, volume, jsonDate) {
        document.getElementById('modalMilkId').textContent = id;
        document.getElementById('modalMilkVolume').textContent = volume + " ml";

        let formatted = "N/A";
        try {
            let parsed = JSON.parse(jsonDate);
            if (parsed.datetime) {
                formatted = new Date(parsed.datetime)
                    .toLocaleString('en-GB', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        hour12: true
                    });
            }
        } catch(e) {}

        document.getElementById('modalMilkDate').textContent = formatted;
        document.getElementById('milkDetailModal').style.display = 'flex';
    }

    function closeMilkDetailModal() {
        document.getElementById('milkDetailModal').style.display = 'none';
    }
</script>

@endsection
