@extends('layouts.nurse')

@section('title', 'Milk Distribution')

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
        margin-bottom: 0;
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
        margin-bottom: -2px;
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
    /* Additions for Dispense Modal */
    .dispense-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 15px;
    }
    .dispense-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #f1f5f9;
        gap: 15px;
        transition: background 0.2s;
    }
    .dispense-item:last-child { border-bottom: none; }
    .dispense-item.checked { background-color: #f0fdf4; }
    
    .auto-fill-info {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        display: none; /* Hidden until checked */
    }
    .dispense-item.checked .auto-fill-info { display: block; }
  </style>

  {{-- DUMMY DATA --}}
  @php
      $request = [
          (object)[
              'request_ID' => 101,
              'patient_name' => 'Baby Adam',
              'parent_id' => 'P-2024-001',
              'formattedID' => 'P-2024-001', 
              'cubicle' => 'NICU-A1',
              'date_requested' => '2026-01-20',
              'feed_time' => '2026-01-21 08:00',
              'status' => 'Waiting',
              'weight' => 2.5,
              'age' => '5 Days',
              'gestational' => '32 Weeks',
              'total_vol' => 375,
              'recommended_volume' => 375, 
              'kinship' => 'no',
              'feeds' => 12,
              'interval' => 2,
              'tube_method' => 'Orogastric',
              'oral_method' => 'Syringe',
              'parent' => (object)[ 
                  'pr_BabyName' => 'Baby Adam',
                  'formattedID' => 'P-2024-001',
                  'pr_NICU' => 'NICU-A1',
                  'pr_BabyCurrentWeight' => 2.5,
                  'pr_BabyDOB' => '2026-01-15'
              ],
              'created_at' => \Carbon\Carbon::parse('2026-01-20'),
              'feeding_start_date' => '2026-01-21',
              'feeding_start_time' => '08:00',
              // Mock allocated items for this request
              'allocated_items' => [
                  (object)['id' => 'M26-001', 'vol' => 30],
                  (object)['id' => 'M26-002', 'vol' => 30],
              ]
          ],
          (object)[
              'request_ID' => 102,
              'patient_name' => 'Baby Sarah',
              'parent_id' => 'P-2024-005',
              'formattedID' => 'P-2024-005',
              'cubicle' => 'NICU-B3',
              'date_requested' => '2026-01-21',
              'feed_time' => '2026-01-22 10:00',
              'status' => 'Approved',
              'weight' => 3.0,
              'age' => '2 Weeks',
              'gestational' => '36 Weeks',
              'total_vol' => 450,
              'recommended_volume' => 450,
              'kinship' => 'yes',
              'feeds' => 8,
              'interval' => 3,
              'tube_method' => '-',
              'oral_method' => 'Bottle',
              'parent' => (object)[
                  'pr_BabyName' => 'Baby Sarah',
                  'formattedID' => 'P-2024-005',
                  'pr_NICU' => 'NICU-B3',
                  'pr_BabyCurrentWeight' => 3.0,
                  'pr_BabyDOB' => '2026-01-07'
              ],
              'created_at' => \Carbon\Carbon::parse('2026-01-21'),
              'feeding_start_date' => '2026-01-22',
              'feeding_start_time' => '10:00',
              'allocated_items' => [] // None yet
          ],
      ];
      
      $milks = [
          (object)['milk_ID' => 1, 'formattedID' => 'M26-001', 'milk_volume' => 150, 'milk_expiryDate' => '2026-07-20'],
          (object)['milk_ID' => 2, 'formattedID' => 'M26-002', 'milk_volume' => 200, 'milk_expiryDate' => '2026-07-21'],
          (object)['milk_ID' => 3, 'formattedID' => 'M26-003', 'milk_volume' => 120, 'milk_expiryDate' => '2026-07-22'],
      ];
  @endphp

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
            <form onsubmit="event.preventDefault();" class="search-form">
                <input type="text" class="search-input" placeholder="Search Name or ID...">
                <button type="submit" class="btn"><i class="fas fa-search"></i> Search</button>
            </form>
          </div>
        </div>

        <div class="tabs-container">
            <a href="#" class="tab-link active"><i class="fas fa-list"></i> All</a>
            <a href="#" class="tab-link"><i class="fas fa-clock"></i> Waiting</a>
            <a href="#" class="tab-link"><i class="fas fa-check-circle"></i> Approved</a>
            <a href="#" class="tab-link"><i class="fas fa-ban"></i> Rejected</a>
        </div>

        <table class="records-table" id="requestTable">
          <thead>
            <tr>
              <th onclick="sortTable(0)">Patient Name <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(1)">NICU Cubicle <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(2)">Date Requested <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(3)">Feeding Time <i class="fas fa-sort sort-icon"></i></th>
              <th onclick="sortTable(4)">Status <i class="fas fa-sort sort-icon"></i></th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($requests as $req)
              <tr>
                <td>
                  <div class="patient-info">
                    <i class="fas fa-baby milk-icon"></i>
                    <div>
                      <strong>{{ $req->formatted_id }}</strong><br>
                      <span>{{ $req->patient_name }}</span>
                    </div>
                  </div>
                </td>
                <td>{{ $req->cubicle }}</td>
                <td>{{ $req->date_requested }}</td>
                <td>{{ $req->feed_time }}</td>
                <td>
                  <span class="status {{ strtolower($req->status) }}">{{ $req->status }}</span>
                </td>
                <td>
                   {{-- 1. View Details Icon --}}
                  <button type="button" class="btn-view" 
                        onclick='openViewModal(@json($req))' 
                        title="View Details">
                    <i class="fas fa-eye"></i>
                  </button>
                  
                  {{-- 2. Allocate/Edit Icon --}}
                  @if($req->status != 'Approved')
                    <button type="button" class="btn-view"
                        style="color: #f59e0b;" 
                        onclick='openMilkModal(this)'
                        data-id="{{ $req->request_ID }}"
                        data-status="{{ $req->status }}"
                        data-formatted-id="{{ $req->formatted_id }}"
                        data-patient-name="{{ $req->patient_name }}"
                        data-weight="{{ $req->weight }}"
                        data-dob="{{ $req->parent->pr_BabyDOB }}"
                        data-ward="{{ $req->cubicle }}"
                        data-volume="{{ $req->total_vol }}"
                        title="Allocate Milk">
                        <i class="fas fa-edit"></i>
                    </button>
                  @endif

                  {{-- 3. NEW: Dispense/Confirm Milk Icon --}}
                  {{-- Only show if allocated items exist --}}
                  @if(count($req->allocated_items) > 0)
                    <button type="button" class="btn-view" 
                        style="color: #0891b2;"
                        onclick='openDispenseModal(@json($req))'
                        title="Dispense Milk">
                        <i class="fas fa-clipboard-check"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>

  {{-- ========================================================= --}}
  {{-- VIEW DETAILS MODAL --}}
  {{-- ========================================================= --}}
  <div id="viewRequestModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="fas fa-file-medical"></i> Milk Request Details</h2>
        <button class="modal-close-btn" onclick="closeViewModal()">Close</button>
      </div>
      <div class="modal-body">
        <div class="info-section">
            <h3><i class="fas fa-baby"></i> Patient Information</h3>
            <div class="info-grid">
                <div class="info-item"> <label>Patient Name</label> <p id="view-patient-name">-</p> </div>
                <div class="info-item"> <label>Patient ID</label> <p id="view-formatted-id">-</p> </div>
                <div class="info-item"> <label>NICU Location</label> <p id="view-cubicle">-</p> </div>
            </div>
        </div>
        <div class="info-section">
            <h3><i class="fas fa-stethoscope"></i> Clinical Information</h3>
            <div class="info-grid">
                <div class="info-item"> <label>Current Weight</label> <p><span id="view-weight">-</span> kg</p> </div>
                <div class="info-item"> <label>Age</label> <p id="view-age">-</p> </div>
                <div class="info-item"> <label>Gestational Age</label> <p id="view-gestational">-</p> </div>
                <div class="info-item"> <label>Total Daily Volume</label> <p class="highlight"><span id="view-total-vol">-</span> ml</p> </div>
            </div>
        </div>
        <div class="info-section consent-section">
            <h3><i class="fas fa-handshake"></i> Milk Kinship Consent</h3>

            <div class="consent-grid">
                @if($req->parent_consent === 'Approved')
                    <div class="consent-badge approved">
                        <i class="fas fa-check-circle"></i> Parent Consent Approved
                    </div>

                @elseif($req->parent_consent === 'Pending')
                    <div class="consent-badge pending">
                        <i class="fas fa-clock"></i> Consent Pending
                    </div>

                @elseif($req->parent_consent === 'Rejected')
                    <div class="consent-badge rejected">
                        <i class="fas fa-times-circle"></i> Consent Rejected
                    </div>

                @else
                    <div class="consent-badge unknown">
                        <i class="fas fa-question-circle"></i> Consent Not Recorded
                    </div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <h3><i class="fas fa-prescription-bottle"></i> Dispensing Method</h3>
            <div id="method-kinship-yes" class="method-box" style="display:block; background:#f0fdf4; border-color:#86efac; margin-bottom: 15px;">
                <h4><i class="fas fa-users"></i> Method A: Milk Kinship (Mahram)</h4>
                <p>Full Nursing Method</p>
                <hr>
                <div class="info-grid">
                    <div class="info-item"> <label>Volume Per Feed</label> <p class="highlight"><span id="view-kinship-vol">-</span> ml</p> </div>
                    <div class="info-item"> <label>Frequency</label> <p>Every <span id="view-kinship-interval">-</span> Hours</p> </div>
                </div>
            </div>
            <div id="method-kinship-no" class="method-box" style="display:block; background:#fff7ed; border-color:#fed7aa;">
                <h4><i class="fas fa-ban"></i> Method B: No Milk Kinship</h4>
                <p>Restricted Feeding (Drip Method)</p>
                <hr>
                <div class="info-grid">
                    <div class="info-item"> <label>Drip / Tube Feed</label> <p class="highlight"><span id="view-drip-vol">-</span> ml</p> <small class="text-muted">Via: <span id="view-tube-method">-</span></small> </div>
                    <div class="info-item"> <label>Direct Oral Feed</label> <p class="highlight"><span id="view-oral-vol">-</span> ml</p> <small class="text-muted">Via: <span id="view-oral-method">-</span></small> </div>
                </div>
            </div>
        </div>
        <div class="info-section">
            <h3><i class="fas fa-calendar-alt"></i> Feeding Schedule</h3>
            <div class="info-grid">
                <div class="info-item"> <label>Start Date</label> <p id="view-start-date">-</p> </div>
                <div class="info-item"> <label>Start Time</label> <p id="view-start-time">-</p> </div>
                <div class="info-item"> <label>Feeds Per Day</label> <p id="view-feeds">-</p> </div>
                <div class="info-item"> <label>Interval</label> <p id="view-interval">- Hours</p> </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeViewModal()">Close</button>
      </div>
    </div>
  </div>

  {{-- ========================================================= --}}
  {{-- ALLOCATE MILK MODAL --}}
  {{-- ========================================================= --}}
  <div id="milkModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Allocate Milk</h2>
        <button class="modal-close-btn" onclick="closeMilkModal()">Close</button>
      </div>
      <div class="modal-body">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
          <div style="background: white; padding: 10px; border-radius: 50%; color: #0ea5e9;"> <i class="fas fa-user fa-lg"></i> </div>
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
            <label>Milk Unit ID (Select from Inventory)</label>
            <div id="milkListSelect" class="milk-list">
              @foreach($milks as $milk)
              <div class="milk-item" data-id="{{ $milk->milk_ID }}">
                  <div style="display: flex; align-items: flex-start; gap: 10px; width: 100%;">
                      <input type="checkbox" class="milk-checkbox" value="{{ $milk->milk_ID }}" data-volume="30" style="margin-top: 5px; cursor: pointer;">
                      <div style="flex-grow: 1;">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                              <strong>{{ $milk->formattedID }} — 30 ml</strong>
                              <span class="badge-consent"><i class="fas fa-check-circle"></i> Donor Consent</span>
                          </div>
                          <span style="font-size: 12px; color: #666;">Expires {{ \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') }}</span>
                      </div>
                  </div>
              </div>
              @endforeach
            </div>
            <p class="total-volume-display" style="text-align: right; margin-top: 10px; font-size: 14px;">
              <strong>Total Selected Volume:</strong> <span id="totalVolume" style="color: #2563eb; font-size: 16px;">0</span> ml
            </p>
          </div>
          <div class="modal-section">
            <label>Storage Location</label>
            <input type="text" class="form-control" id="storageLocation" value="NICU Storage Room A" readonly>
          </div>
          <button type="submit" id="btnAllocateSubmit" class="modal-close-btn" style="width:100%; background:#10b981; color:white; margin-top:10px;"> CONFIRM ALLOCATION</button>
        </form>
      </div>
    </div>
  </div>

  {{-- ========================================================= --}}
  {{-- 3. DISPENSE MILK MODAL (NEW) --}}
  {{-- ========================================================= --}}
  <div id="dispenseModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="fas fa-clipboard-check"></i> Dispense Milk</h2>
        <button class="modal-close-btn" onclick="closeDispenseModal()">Close</button>
      </div>
      <div class="modal-body">
        
        <p style="color:#64748b; margin-bottom: 15px;">Check the bottles you are dispensing to <strong><span id="dispensePatientName"></span></strong>.</p>

        <div class="dispense-list" id="dispenseListContainer">
            {{-- Items will be injected via JS --}}
        </div>

        <button type="button" class="btn btn-primary" style="width:100%; margin-top:10px;" onclick="saveDispense()">
            Complete Dispensing
        </button>
      </div>
    </div>
  </div>

  <script>
    // --- 1. VIEW DETAILS LOGIC ---
    function openViewModal(data) {
        document.getElementById('view-patient-name').textContent = data.patient_name;
        document.getElementById('view-formatted-id').textContent = data.formatted_id;
        document.getElementById('view-cubicle').textContent = data.cubicle;
        document.getElementById('view-weight').textContent = data.weight;
        document.getElementById('view-age').textContent = data.age;
        document.getElementById('view-gestational').textContent = data.gestational;
        document.getElementById('view-total-vol').textContent = data.total_daily_volume;
        document.getElementById('view-start-date').textContent = data.date_requested;
        document.getElementById('view-start-time').textContent = data.feed_time.split(' ')[1];
        document.getElementById('view-feeds').textContent = data.feeding_perday;
        document.getElementById('view-interval').textContent = data.feeding_interval;

        let perFeed = (data.total_vol / data.feeds).toFixed(1);
        document.getElementById('view-kinship-vol').textContent = perFeed;
        document.getElementById('view-kinship-interval').textContent = data.interval;

        let dripVol = (data.total_vol * 0.8).toFixed(1);
        let oralTotal = data.total_vol * 0.2;
        let oralPerFeed = (oralTotal / data.feeds).toFixed(1);
        document.getElementById('view-drip-vol').textContent = dripVol;
        document.getElementById('view-oral-vol').textContent = oralPerFeed;
        document.getElementById('view-tube-method').textContent = data.tube_method;
        document.getElementById('view-oral-method').textContent = data.oral_method;

        document.getElementById('viewRequestModal').style.display = 'flex';
    }

    function closeViewModal() {
        document.getElementById('viewRequestModal').style.display = 'none';
    }

    // --- 2. ALLOCATION MODAL LOGIC ---
    let selectedMilkUnits = [];
    let selectedRequestId = null; 

    function updateTotalVolume() {
        let total = selectedMilkUnits.reduce((sum, item) => sum + parseFloat(item.volume), 0);
        document.getElementById("totalVolume").textContent = total;
    }

    function handleSelectionChange(checkbox, milkItemDiv) {
        const id = checkbox.value;
        const volume = checkbox.getAttribute("data-volume");

        if (checkbox.checked) {
            if (!selectedMilkUnits.find(m => m.id == id)) {
                selectedMilkUnits.push({ id, volume });
            }
            milkItemDiv.classList.add("selected");
        } else {
            selectedMilkUnits = selectedMilkUnits.filter(m => m.id != id);
            milkItemDiv.classList.remove("selected");
        }
        updateTotalVolume();
    }

    document.querySelectorAll(".milk-item").forEach(item => {
        const checkbox = item.querySelector(".milk-checkbox");
        if(checkbox){
            item.addEventListener("click", function(e) {
                if (e.target !== checkbox) {
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
        document.getElementById('modalPatientID').textContent = button.getAttribute('data-patient-id');
        document.getElementById('modalPatientName').textContent = button.getAttribute('data-patient-name');
        document.getElementById('modalWeight').value = button.getAttribute('data-weight');
        document.getElementById('modalDob').value = button.getAttribute('data-dob');
        document.getElementById('modalWard').value = button.getAttribute('data-ward');
        document.getElementById('modalVolume').value = button.getAttribute('data-volume');

        selectedMilkUnits = [];
        document.getElementById("totalVolume").textContent = "0";
        document.querySelectorAll('.milk-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.milk-item').forEach(item => item.classList.remove('selected'));

        document.getElementById('milkModal').style.display = 'flex';
    }

    function closeMilkModal() {
        document.getElementById('milkModal').style.display = 'none';
    }

    document.getElementById('milkAllocationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (selectedMilkUnits.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one Milk Unit.'});
            return;
        }
        closeMilkModal();
        Swal.fire({ 
            icon: 'success', 
            title: 'Allocated!', 
            text: 'Milk allocated successfully!', 
            confirmButtonColor: '#0ea5e9'
        }).then(() => {
            location.reload(); 
        });
    });

    // --- 3. DISPENSE MODAL LOGIC (NEW) ---
    function openDispenseModal(reqData) {
        document.getElementById('dispensePatientName').textContent = reqData.patient_name;
        const container = document.getElementById('dispenseListContainer');
        container.innerHTML = '';

        reqData.allocated_items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'dispense-item';
            // Current User Name (Simulated)
            const nurseName = "{{ Auth::check() ? Auth::user()->name : 'Nurse Sarah' }}";
            
            div.innerHTML = `
                <input type="checkbox" class="dispense-check" style="width:18px; height:18px; cursor:pointer;" onchange="toggleDispense(this)">
                <div style="flex:1;">
                    <div style="font-weight:600; color:#1A5F7A;">${item.id}</div>
                    <div style="font-size:13px; color:#64748b;">Volume: ${item.vol} ml</div>
                    <div class="auto-fill-info">
                        <i class="fas fa-clock"></i> <span class="dispense-time">--:--</span> &nbsp;|&nbsp; 
                        <i class="fas fa-user-nurse"></i> ${nurseName}
                    </div>
                </div>
            `;
            container.appendChild(div);
        });

        document.getElementById('dispenseModal').style.display = 'flex';
    }

    function closeDispenseModal() {
        document.getElementById('dispenseModal').style.display = 'none';
    }

    function toggleDispense(checkbox) {
        const item = checkbox.closest('.dispense-item');
        const timeSpan = item.querySelector('.dispense-time');
        
        if (checkbox.checked) {
            item.classList.add('checked');
            // Auto-fill current time
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            timeSpan.textContent = timeString;
        } else {
            item.classList.remove('checked');
            timeSpan.textContent = '--:--';
        }
    }

    function saveDispense() {
        // Simple validation or saving logic
        const checked = document.querySelectorAll('.dispense-check:checked');
        if (checked.length === 0) {
            Swal.fire('No Selection', 'Please check at least one milk bottle to dispense.', 'warning');
            return;
        }

        closeDispenseModal();
        Swal.fire({
            icon: 'success',
            title: 'Dispensed!',
            text: `${checked.length} bottles marked as dispensed.`,
            confirmButtonColor: '#0ea5e9'
        }).then(() => {
            location.reload();
        });
    }

    window.addEventListener("click", function(e) {
        if (e.target === document.getElementById("milkModal")) closeMilkModal();
        if (e.target === document.getElementById("viewRequestModal")) closeViewModal();
        if (e.target === document.getElementById("dispenseModal")) closeDispenseModal();
    });
  </script>
@endsection