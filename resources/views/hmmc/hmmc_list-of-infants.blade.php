@extends('layouts.hmmc')

@section('title', 'Infant Weight Setting')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/hmmc_list-of-infants.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .swal2-container { z-index: 9999 !important; }
        .modal-overlay { z-index: 2000; }
        
        .milk-badge {
            cursor: pointer;
            transition: background 0.2s;
        }
        .milk-badge:hover {
            background-color: #bae6fd;
        }

        /* --- SORTING STYLES --- */
        th { cursor: pointer; user-select: none; position: relative; }
        th:hover { background-color: #f1f5f9; }
        .sort-icon { font-size: 0.8em; margin-left: 5px; color: #cbd5e1; }
        .sort-active { color: #0ea5e9; }

        /* --- PAGINATION STYLES --- */
        .pagination-wrapper {
            display: flex;
            justify-content: flex-end;
            padding: 15px 20px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* Basic styling for Laravel default pagination if not using Bootstrap/Tailwind */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
        }

        .page-item .page-link {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
            display: inline-block;
        }

        .page-item.active .page-link {
            background-color: #0ea5e9; /* Your Theme Blue */
            border-color: #0ea5e9;
            color: white;
        }

        .page-item.disabled .page-link {
            color: #cbd5e1;
            pointer-events: none;
            background-color: #f8fafc;
        }

        .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #f1f5f9;
            color: #0ea5e9;
            border-color: #cbd5e1;
        }
        
        /* Hide default "Showing 1 to 10" text if it messes up layout */
        .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between { display: none; }
        nav[role="navigation"] { width: 100%; display: flex; justify-content: space-between; align-items: center; }
    </style>

    <div class="container">
        <div class="main-content">
            <div class="page-header">
                <h1>List of Infants</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Infants Information</h2>
                    <div class="actions">
                        <form action="{{ route('hmmc.hmmc_list-of-infants') }}" method="GET" style="display: flex; gap: 5px;">
                            <input type="text" name="search" class="form-control" placeholder="Search ID or Name..." 
                                   value="{{ request('search') }}" style="padding: 6px; font-size: 14px;">
                            <button type="submit" class="btn"><i class="fas fa-search"></i> Search</button>
                            @if(request('search'))
                                <a href="{{ route('hmmc.hmmc_list-of-infants') }}" class="btn" style="background: #64748b; color: white; text-decoration: none;">Clear</a>
                            @endif
                        </form>
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
                        @if($parents->count() > 0)
                            @foreach ($parents as $parent)
                                <tr>
                                    <td data-order="{{ $parent->pr_ID }}">
                                        <div class="patient-info">
                                            <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                            <div class="patient-details">
                                                <strong>{{ $parent->formatted_id ?? 'P-'.$parent->pr_ID }}</strong>
                                                <span>{{ $parent->pr_BabyName }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-order="{{ $parent->pr_NICU }}">{{ $parent->pr_NICU }}</td>
                                    
                                    <td data-order="{{ $parent->requests->pluck('allocation')->flatten()->count() }}">
                                        <div class="milk-badge-container">
                                            @php $hasMilk = false; @endphp
                                            @foreach($parent->requests as $req)
                                                @foreach($req->allocation as $alloc)
                                                    @if($alloc->milk)
                                                        @php 
                                                            $hasMilk = true; 
                                                            $rawData = $alloc->allocation_milk_date_time;
                                                            $jsonString = is_array($rawData) ? json_encode($rawData) : (string) $rawData;
                                                        @endphp
                                                        <span class="milk-badge" 
                                                              onclick="openMilkDetailModal(
                                                                  '{{ $alloc->milk->formatted_id ?? $alloc->milk->milk_ID }}', 
                                                                  '{{ $alloc->milk->milk_volume }}', 
                                                                  '{{ addslashes($jsonString) }}' 
                                                              )">
                                                            <i class="fas fa-flask"></i> 
                                                            {{ $alloc->milk->formatted_id ?? 'M-'.$alloc->milk->milk_ID }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @if(!$hasMilk) <span class="no-data">No active allocations</span> @endif
                                        </div>
                                    </td>

                                    <td data-order="{{ $parent->updated_at ? $parent->updated_at->timestamp : 0 }}">
                                        {{ $parent->updated_at ? $parent->updated_at->format('d-m-Y h:i A') : '-' }}
                                    </td>

                                    <td data-order="{{ $parent->pr_BabyCurrentWeight }}">
                                        <div class="weight-display">
                                            <i class="fa-solid fa-weight-scale"></i>
                                            <span>{{ $parent->pr_BabyCurrentWeight }} kg</span>
                                        </div>
                                    </td>

                                    <td class="actions">
                                        <button class="btn-view" title="View" 
                                            onclick="openViewModal('{{ $parent->formatted_id ?? $parent->pr_ID }}', '{{ $parent->pr_BabyName }}', '{{ $parent->pr_NICU }}', '{{ $parent->updated_at?->format('d-m-Y h:i A') }}', '{{ $parent->pr_BabyCurrentWeight }}')">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn-edit" title="Update Weight" onclick="openWeightModal('{{ $parent->pr_ID }}')">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">
                                    No records found matching "{{ request('search') }}"
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    {{ $parents->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>

    <div id="viewInfantModal" class="modal-overlay" style="display: none;">
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

    <div id="weightModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header"><h2>Daily Weight Entry</h2><button class="modal-close-btn" onclick="closeWeightModal()">Close</button></div>
            <div class="modal-body">
                <form id="weightEntryForm">
                @csrf
                <input type="hidden" id="parentId" name="pr_ID">    
                    <div class="modal-section"><label>Patient ID <span class="text-danger">*</span></label><input type="text" id="PatientId" name="PatientId" class="form-control" readonly></div>
                    <div class="modal-section"><label>Enter Today's Weight (kg) <span class="text-danger">*</span></label><input type="number" id="pr_BabyCurrentWeight" name="pr_BabyCurrentWeight" class="form-control" placeholder="e.g., 2.45" step="0.01" required></div>
                    <button type="submit" class="modal-close-btn">Save Record</button>
                </form>
            </div>
        </div>
    </div>

    <div id="milkDetailModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header"><h2>Milk Allocation Details</h2><button class="modal-close-btn" onclick="closeMilkDetailModal()">Close</button></div>
            <div class="modal-body">
                <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 id="modalMilkId" style="margin: 0; color: #0369a1;">-</h3><span style="font-size: 13px; color: #64748b;">Milk Unit ID</span>
                </div>
                <div class="modal-section"><label style="font-size: 12px; color: #64748b; font-weight: 600;">VOLUME</label><p id="modalMilkVolume" style="font-size: 18px; font-weight: 700; color: #334155; margin-top: 0;">-</p></div>
                <div class="modal-section"><label style="font-size: 12px; color: #64748b; font-weight: 600;">ALLOCATION DATE & TIME</label><p id="modalMilkDate" style="font-size: 16px; color: #334155; margin-top: 0;">-</p></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sort Table Logic
        let sortDirection = { 0: false }; 
        function sortTable(columnIndex) {
            const table = document.getElementById("infantsTable");
            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.rows);
            const headers = table.querySelectorAll('th');

            if (sortDirection[columnIndex] === undefined) { sortDirection[columnIndex] = true; } 
            else { sortDirection[columnIndex] = !sortDirection[columnIndex]; }

            const isAscending = sortDirection[columnIndex];

            rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].getAttribute('data-order');
                const cellB = rowB.cells[columnIndex].getAttribute('data-order');
                const a = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
                const b = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);
                if (a < b) return isAscending ? -1 : 1;
                if (a > b) return isAscending ? 1 : -1;
                return 0;
            });

            tbody.append(...rows);

            headers.forEach((th, index) => {
                const icon = th.querySelector('.sort-icon');
                if (icon) {
                    icon.className = 'fas fa-sort sort-icon'; 
                    icon.classList.remove('sort-active');
                    if (index === columnIndex) {
                        icon.classList.add('sort-active');
                        if (isAscending) { icon.classList.remove('fa-sort'); icon.classList.add('fa-sort-up'); } 
                        else { icon.classList.remove('fa-sort'); icon.classList.add('fa-sort-down'); }
                    }
                }
            });
        }

        // Modal Functions
        function openMilkDetailModal(id, volume, jsonDate) {
            document.getElementById('modalMilkId').textContent = id;
            document.getElementById('modalMilkVolume').textContent = volume + " ml";
            let formattedDate = 'N/A';
            if (jsonDate) {
                try {
                    let parsed = (typeof jsonDate === 'string') ? JSON.parse(jsonDate) : jsonDate;
                    if (typeof parsed === 'string') parsed = JSON.parse(parsed);
                    if (parsed && parsed.datetime) {
                        const dateObj = new Date(parsed.datetime);
                        if (!isNaN(dateObj.getTime())) {
                            const options = { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
                            formattedDate = dateObj.toLocaleDateString('en-GB', options);
                        } else { formattedDate = parsed.datetime; }
                    }
                } catch (e) { formattedDate = 'Invalid Date Format'; }
            }
            document.getElementById('modalMilkDate').textContent = formattedDate;
            document.getElementById('milkDetailModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeMilkDetailModal() { document.getElementById('milkDetailModal').style.display = 'none'; document.body.style.overflow = 'auto'; }

        function openViewModal(id, name, nicu, updated, weight) {
            document.getElementById('viewPatientId').textContent = id;
            document.getElementById('viewPatientName').textContent = name;
            document.getElementById('viewCubicle').textContent = nicu;
            document.getElementById('viewLastUpdated').textContent = updated;
            document.getElementById('viewWeight').textContent = weight + " kg";
            document.getElementById('viewInfantModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeViewModal() { document.getElementById('viewInfantModal').style.display = 'none'; document.body.style.overflow = 'auto'; }

        function openWeightModal(id) {
            document.getElementById('weightModal').style.display = 'flex';
            document.getElementById('PatientId').value = "#P" + id;
            document.getElementById('parentId').value = id;
            document.body.style.overflow = 'hidden';
        }
        function closeWeightModal() {
            document.getElementById('weightModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('weightEntryForm').reset();
        }

        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        document.getElementById('weightEntryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const prId = document.getElementById('parentId').value;
            const weight = document.getElementById('pr_BabyCurrentWeight').value;
            fetch("{{ route('hmmc.hmmc_list-of-infants.update') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ pr_ID: prId, pr_BabyCurrentWeight: weight })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'Weight updated successfully!', timer: 1500, showConfirmButton: false });
                    closeWeightModal();
                    setTimeout(() => location.reload(), 1500);
                } else throw new Error(res.message);
            })
            .catch(err => Swal.fire('Error', 'Update failed.', 'error'));
        });
    </script>
@endsection