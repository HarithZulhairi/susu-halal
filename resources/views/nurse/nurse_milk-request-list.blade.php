@extends('layouts.nurse')

@section('title', 'Choose Milk ID')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/nurse_milk-request-list.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* --- SORTING STYLES --- */
    th { cursor: pointer; user-select: none; position: relative; }
    th:hover { background-color: #f1f5f9; }
    .sort-icon { font-size: 0.8em; margin-left: 5px; color: #cbd5e1; }
    .sort-active { color: #0ea5e9; }

    /* --- PAGINATION STYLES --- */
    .pagination-wrapper { display: flex; justify-content: flex-end; padding: 15px 20px; background: #fff; border-top: 1px solid #e2e8f0; }
    .page-item .page-link { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; text-decoration: none; font-size: 14px; margin: 0 2px; }
    .page-item.active .page-link { background-color: #0ea5e9; border-color: #0ea5e9; color: white; }
    .page-item.disabled .page-link { color: #cbd5e1; pointer-events: none; background-color: #f8fafc; }
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between { display: none; }
    nav[role="navigation"] { width: 100%; display: flex; justify-content: space-between; align-items: center; }

    /* --- SEARCH FORM STYLES --- */
    .search-form { display: flex; align-items: center; gap: 5px; }
    .search-input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.2s; }
    .search-input:focus { border-color: #0ea5e9; }

    /* --- TAB STYLES --- */
    .tabs-container {
        display: flex;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 0; /* Attach to top of table/card */
        padding: 0 20px;
        background: #fff;
    }

    .tab-link {
        padding: 15px 20px;
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px; /* Overlap border */
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-link:hover {
        color: #0ea5e9;
        background-color: #f8fafc;
    }

    .tab-link.active {
        color: #0ea5e9;
        border-bottom: 2px solid #0ea5e9;
    }
    
    /* Status Badge in Tabs (Optional) */
    .tab-badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #64748b;
    }
    .tab-link.active .tab-badge {
        background: #0ea5e9;
        color: white;
    }
  </style>

  <div class="container">
    <div class="main-content">

      <div class="page-header">
        <h1>Milk Distribution</h1>
        <p>Choose Milk</p>
      </div>

      <div class="card">
        <div class="card-header" style="border-bottom: none; padding-bottom: 5px;">
          <h2>Milk Request Records</h2>
          
          <div class="actions">
            <form action="{{ route('nurse.nurse_milk-request-list') }}" method="GET" class="search-form">
                <input type="hidden" name="status" value="{{ request('status') }}">
                
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Search Name or ID..." 
                       value="{{ request('search') }}">
                
                <button type="submit" class="btn">
                    <i class="fas fa-search"></i> Search
                </button>
                
                @if(request('search'))
                    <a href="{{ route('nurse.nurse_milk-request-list', ['status' => request('status')]) }}" class="btn" style="background-color: #64748b; text-decoration: none; color: white;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
          </div>
        </div>

        <div class="tabs-container">
            @php $currentStatus = request('status', 'All'); @endphp

            <a href="{{ route('nurse.nurse_milk-request-list', ['status' => 'All', 'search' => request('search')]) }}" 
               class="tab-link {{ $currentStatus == 'All' || !$currentStatus ? 'active' : '' }}">
               <i class="fas fa-list"></i> All
            </a>

            <a href="{{ route('nurse.nurse_milk-request-list', ['status' => 'Waiting', 'search' => request('search')]) }}" 
               class="tab-link {{ $currentStatus == 'Waiting' ? 'active' : '' }}">
               <i class="fas fa-clock"></i> Waiting
            </a>

            <a href="{{ route('nurse.nurse_milk-request-list', ['status' => 'Approved', 'search' => request('search')]) }}" 
               class="tab-link {{ $currentStatus == 'Approved' ? 'active' : '' }}">
               <i class="fas fa-check-circle"></i> Approved (Allocated)
            </a>

            <a href="{{ route('nurse.nurse_milk-request-list', ['status' => 'Rejected', 'search' => request('search')]) }}" 
               class="tab-link {{ $currentStatus == 'Rejected' ? 'active' : '' }}">
               <i class="fas fa-ban"></i> Rejected
            </a>
        </div>
        <table class="records-table" id="requestTable">
          <thead>
            <tr>
              <th onclick="sortTable(0)">Patient Name <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(1)">NICU Cubicle <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(2)">Date Requested <i class="fas fa-sort-down sort-icon sort-active"></i></th> 
              <th onclick="sortTable(3)">Date Time to Give <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(4)">Request Status <i class="fas fa-sort sort-icon"></i></th>
              <th>Action</th> 
            </tr>
          </thead>
          <tbody>
            @if($requests->count() > 0)
                @foreach($requests as $req)
                  <tr>
                    <td data-order="{{ strtolower($req->parent->pr_BabyName ?? '') }}">
                      <div class="patient-info">
                        <i class="fas fa-bottle-droplet milk-icon"></i>
                        <div>
                          <strong>{{ $req->parent->formattedID ?? 'N/A' }}</strong><br>
                          <span>{{ $req->parent->pr_BabyName ?? 'Unknown Baby' }}</span>
                        </div>
                      </div>
                    </td>

                    <td data-order="{{ $req->parent->pr_NICU ?? '0' }}">
                        {{ $req->parent->pr_NICU ?? '-' }}
                    </td>

                    <td data-order="{{ $req->created_at->timestamp }}">
                        {{ $req->created_at->format('M d, Y') }}
                    </td>

                    <td data-order="{{ \Carbon\Carbon::parse($req->feeding_start_date . ' ' . $req->feeding_start_time)->timestamp }}">
                        {{ \Carbon\Carbon::parse($req->feeding_start_date)->format('M d, Y') }}
                        •
                        {{ \Carbon\Carbon::parse($req->feeding_start_time)->format('h:i A') }}
                    </td>

                    <td data-order="{{ $req->status ?? 'Waiting' }}">
                      <span class="status 
                            @if($req->status == 'Approved') approved
                            @elseif($req->status == 'Rejected') rejected
                            @elseif($req->status == 'Allocated') allocated
                            @else waiting
                            @endif
                        ">
                            {{ $req->status ?? 'Waiting' }}
                      </span>
                    </td>

                    <td>
                      <button type="button" class="select-text" 
                            onclick="openMilkModal(this)"
                            data-id="{{ $req->request_ID }}"
                            data-status="{{ $req->status }}"
                            data-patient-id="{{ $req->parent->formattedID ?? 'N/A' }}"
                            data-patient-name="{{ $req->parent->pr_BabyName ?? 'Unknown' }}"
                            data-weight="{{ $req->parent->pr_BabyCurrentWeight ?? '-' }}"
                            data-dob="{{ $req->parent->pr_BabyDOB ?? '-' }}"
                            data-ward="{{ $req->parent->pr_NICU ?? '-' }}"
                            data-volume="{{ $req->recommended_volume }}"
                            data-allocations="{{ json_encode($req->allocation) }}">
                        
                        @if($req->status == 'Approved')
                          <i class="fas fa-eye"></i>
                        @else
                          <i class="fas fa-edit"></i>
                        @endif
                      </button>

                      @if($req->status == 'Approved')
                      <button type="button" class="select-text" 
                            onclick="deleteAllocation({{ $req->request_ID }})"
                            style="margin-left: 5px;">
                        <i class="fa fa-close" style="font-size:larger;color:red"></i>
                      </button>
                      @endif
                    </td>
                  </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-inbox fa-3x" style="color: #cbd5e1; margin-bottom: 10px;"></i><br>
                        No records found for status "<strong>{{ request('status', 'All') }}</strong>"
                        @if(request('search'))
                            matching "{{ request('search') }}"
                        @endif
                    </td>
                </tr>
            @endif
          </tbody>
        </table>

        <div class="pagination-wrapper">
             {{ $requests->links('pagination::bootstrap-4') }}
        </div>
      </div>

    </div>
  </div>

<div id="milkModal" class="modal-overlay" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Milk Request Records</h2>
      <button class="modal-close-btn" onclick="closeMilkModal()">Close</button>
    </div>
    <div class="modal-body">
      <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
        <div style="background: white; padding: 10px; border-radius: 50%; color: #0ea5e9;">
             <i class="fas fa-user fa-lg"></i>
        </div>
        <div>
            <h3 style="margin: 0; color: #0c4a6e; font-size: 16px;">Patient: <span id="modalPatientID"></span></h3>
            <span style="font-size: 13px; color: #64748b;" id="modalPatientName"></span>
        </div>
      </div>
      <form id="milkAllocationForm">
        <div class="modal-section">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div><label>Baby Current Weight</label><input type="text" class="form-control" id="modalWeight" readonly></div>
                <div><label>Date of Birth</label><input type="text" class="form-control" id="modalDob" readonly></div>
            </div>
        </div>
        <div class="modal-section">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div><label>Ward</label><input type="text" class="form-control" id="modalWard" readonly></div>
                 <div><label>Prescribed Volume (ml)</label><input type="text" class="form-control" id="modalVolume" readonly></div>
            </div>
        </div>
        <div class="modal-section">
          <label>Milk Unit ID</label>
          <div id="milkListSelect" class="milk-list">
            @foreach($milks as $milk)
            <div class="milk-item" data-id="{{ $milk->milk_ID }}">
                <div style="display: flex; align-items: flex-start; gap: 10px; width: 100%;">
                    <input type="checkbox" class="milk-checkbox" value="{{ $milk->milk_ID }}" data-volume="{{ $milk->milk_volume }}" style="margin-top: 5px; cursor: pointer;">
                    <div style="flex-grow: 1;">
                        <strong>{{ $milk->formattedID }}</strong> — {{ $milk->milk_volume }} ml <br>
                        <span style="font-size: 12px; color: #666;">Expires {{ \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') }}</span>
                        <div class="allocation-time-wrapper" id="time-wrapper-{{ $milk->milk_ID }}" style="margin-top: 8px;"></div>
                    </div>
                </div>
            </div>
            @endforeach
          </div>
          <div id="milkListReadOnly" class="milk-list" style="display: none; max-height: 300px; overflow-y: auto;"></div>
          <p class="total-volume-display" style="text-align: right; margin-top: 10px; font-size: 14px;">
            <strong>Total Volume:</strong> <span id="totalVolume" style="color: #2563eb; font-size: 16px;">0</span> ml
          </p>
        </div>
        <div class="modal-section">
          <label>Storage Location</label>
          <input type="text" class="form-control" id="storageLocation" value="NICU Storage Room A" readonly>
        </div>
        <button type="submit" id="btnAllocateSubmit" class="modal-close-btn"> ALLOCATE MILK UNIT</button>
      </form>
    </div>
  </div>
</div>

<script>
    // --- SORTING LOGIC ---
    let sortDirection = {}; 

    function sortTable(columnIndex) {
        const table = document.getElementById("requestTable");
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.rows);
        const headers = table.querySelectorAll('th');

        if (sortDirection[columnIndex] === undefined) {
             if(columnIndex === 2) sortDirection[columnIndex] = true; 
             else sortDirection[columnIndex] = true; 
        } else {
             sortDirection[columnIndex] = !sortDirection[columnIndex];
        }

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

        headers.forEach(th => {
            const icon = th.querySelector('.sort-icon');
            if(icon) {
                icon.className = 'fas fa-sort sort-icon'; 
                icon.classList.remove('sort-active');
            }
        });

        const activeIcon = headers[columnIndex].querySelector('.sort-icon');
        activeIcon.classList.add('sort-active');
        if (isAscending) {
            activeIcon.classList.remove('fa-sort');
            activeIcon.classList.add('fa-sort-up');
        } else {
            activeIcon.classList.remove('fa-sort');
            activeIcon.classList.add('fa-sort-down');
        }
    }

    // --- MODAL & CHECKBOX LOGIC (Standard JS) ---
    let selectedMilkUnits = [];
    let selectedRequestId = null; 

    function updateTotalVolume() {
        let total = selectedMilkUnits.reduce((sum, item) => sum + parseFloat(item.volume), 0);
        document.getElementById("totalVolume").textContent = total;
    }

    function handleSelectionChange(checkbox, milkItemDiv) {
        const id = checkbox.value;
        const volume = checkbox.getAttribute("data-volume");
        const timeWrapper = document.getElementById(`time-wrapper-${id}`);

        if (checkbox.checked) {
            if (!selectedMilkUnits.find(m => m.id == id)) {
                selectedMilkUnits.push({ id, volume });
            }
            milkItemDiv.classList.add("selected");
            timeWrapper.innerHTML = `
                <div class="allocation-time">
                    <label style="font-size: 12px; font-weight: bold; color: #0c4a6e;">Allocation Time:</label>
                    <input type="datetime-local" class="milk-datetime form-control" 
                           style="font-size: 12px; padding: 4px;" 
                           data-datetime-id="${id}" required>
                </div>`;
        } else {
            selectedMilkUnits = selectedMilkUnits.filter(m => m.id != id);
            milkItemDiv.classList.remove("selected");
            timeWrapper.innerHTML = "";
        }
        updateTotalVolume();
    }

    document.querySelectorAll(".milk-item").forEach(item => {
        const checkbox = item.querySelector(".milk-checkbox");
        if(checkbox){
            item.addEventListener("click", function(e) {
                if (e.target !== checkbox && !e.target.closest('.allocation-time-wrapper')) {
                    checkbox.checked = !checkbox.checked;
                    handleSelectionChange(checkbox, item);
                }
            });
            checkbox.addEventListener("change", function() {
                handleSelectionChange(this, item);
            });
        }
    });

    function openMilkModal(button) {
        selectedRequestId = button.getAttribute('data-id');
        const status = button.getAttribute('data-status'); 
        
        let allocations = [];
        try {
            const rawAllocations = button.getAttribute('data-allocations');
            if(rawAllocations) {
                allocations = JSON.parse(rawAllocations);
            }
        } catch(e) { console.log("No allocations data found or parse error"); }

        document.getElementById('modalPatientID').textContent = button.getAttribute('data-patient-id');
        document.getElementById('modalPatientName').textContent = button.getAttribute('data-patient-name');
        document.getElementById('modalWeight').value = button.getAttribute('data-weight');
        document.getElementById('modalDob').value = button.getAttribute('data-dob');
        document.getElementById('modalWard').value = "NICU - " + button.getAttribute('data-ward');
        document.getElementById('modalVolume').value = button.getAttribute('data-volume');

        document.getElementById('milkModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';

        const selectList = document.getElementById('milkListSelect');
        const readList = document.getElementById('milkListReadOnly');
        const submitBtn = document.getElementById('btnAllocateSubmit');
        const totalVolDisplay = document.getElementById("totalVolume");

        if (status === 'Approved') {
            selectList.style.display = 'none';
            readList.style.display = 'block';
            submitBtn.style.display = 'none'; 
            
            let html = '';
            let total = 0;

            if(allocations && allocations.length > 0) {
                allocations.forEach(alloc => {
                    let milk = alloc.milk; 
                    let dateTimeInfo = alloc.allocation_milk_date_time;
                    try {
                        if (typeof dateTimeInfo === 'string') dateTimeInfo = JSON.parse(dateTimeInfo);
                        if (typeof dateTimeInfo === 'string') dateTimeInfo = JSON.parse(dateTimeInfo);
                    } catch(e) { console.error("Time Parse Error", e); }

                    let timeStr = dateTimeInfo ? dateTimeInfo.datetime : null;

                    if(milk) {
                        total += parseFloat(milk.milk_volume);
                        html += `
                        <div class="milk-item" style="cursor: default; background-color: #f8fafc; margin-bottom: 5px;">
                            <div style="width: 100%;">
                                <div style="display:flex; justify-content:space-between; align-items: center;">
                                    <div>
                                        <strong>${milk.milk_batchID || milk.formattedID || 'ID: '+milk.milk_ID}</strong>
                                        <div style="font-size:12px; color:#666;">Expires: ${milk.milk_expiryDate || '-'}</div>
                                    </div>
                                    <span style="font-weight:bold; color:#2563eb;">${milk.milk_volume} ml</span>
                                </div>
                                <div style="margin-top:5px; padding-top:5px; border-top:1px dashed #cbd5e1; font-size:13px; color:#0c4a6e;">
                                    <i class="fas fa-clock"></i> Give at: <strong>${formatDateTime(timeStr)}</strong>
                                </div>
                            </div>
                        </div>`;
                    }
                });
            } 
            if(html === '') html = '<div style="padding:15px; color:#666; text-align:center;">No milk allocation details found.</div>';
            readList.innerHTML = html;
            totalVolDisplay.textContent = total;
        } else {
            selectList.style.display = 'block';
            readList.style.display = 'none';
            submitBtn.style.display = 'block'; 
            selectedMilkUnits = [];
            totalVolDisplay.textContent = "0";
            document.querySelectorAll('.milk-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.milk-item').forEach(item => item.classList.remove('selected'));
            document.querySelectorAll('.allocation-time-wrapper').forEach(div => div.innerHTML = '');
        }
    }

    function closeMilkModal() {
        document.getElementById('milkModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function formatDateTime(dateString) {
        if(!dateString) return 'Not Set';
        const date = new Date(dateString);
        if(isNaN(date.getTime())) return dateString; 
        return date.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true });
    }

    window.addEventListener("click", function(e) {
        const modal = document.getElementById("milkModal");
        if (e.target === modal) closeMilkModal();
    });

    document.getElementById('milkAllocationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if(document.getElementById('btnAllocateSubmit').style.display === 'none') return;
        if (selectedMilkUnits.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one Milk Unit.', confirmButtonColor: '#0ea5e9'});
            return;
        }
        let allocationDateTimes = {};
        let missingTime = false;
        selectedMilkUnits.forEach(milk => {
            let input = document.querySelector(`.milk-datetime[data-datetime-id="${milk.id}"]`);
            if (!input || !input.value) missingTime = true;
            else allocationDateTimes[milk.id] = input.value;
        });

        if(missingTime){
            Swal.fire({ icon: 'warning', title: 'Missing Info', text: 'Please select an allocation time for all selected milk units.', confirmButtonColor: '#0ea5e9'});
            return;
        }

        fetch("{{ route('nurse.allocate.milk') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({
                request_id: selectedRequestId,
                selected_milk: selectedMilkUnits,
                allocation_times: allocationDateTimes,
                total_volume: document.getElementById("totalVolume").textContent,
                storage_location: document.getElementById("storageLocation").value
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success || data.message) {
                 closeMilkModal();
                 Swal.fire({ icon: 'success', title: 'Allocated!', text: 'Milk allocated successfully!', confirmButtonColor: '#0ea5e9', confirmButtonText: 'Great!'})
                 .then((result) => { if (result.isConfirmed) location.reload(); });
            } else {
                 Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', confirmButtonColor: '#d33'});
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Error processing request.', confirmButtonColor: '#d33'});
        });
    });

    function deleteAllocation(requestId) {
        Swal.fire({
            title: 'Cancel Allocation?', text: "This will remove the assigned milk units and set the status back to 'Waiting'.",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, revert it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('nurse.allocate.delete') }}", {
                    method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ request_id: requestId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Reverted!', 'The allocation has been deleted.', 'success').then(() => { location.reload(); });
                    } else {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Server error.', 'error');
                });
            }
        });
    }
</script>

@endsection