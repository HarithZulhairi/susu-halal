@extends('layouts.nurse')

@section('title', 'Milk Distribution')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/nurse_milk-request-list.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Flash Messages --}}
  @if(session('success'))
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({ icon: 'success', title: 'Success', text: "{{ session('success') }}", confirmButtonColor: '#0ea5e9' });
      });
  </script>
  @endif

  <style>
        /* --- SORTING STYLES --- */
    th { cursor: pointer; user-select: none; position: relative; }
    th:hover { background-color: #f1f5f9; }
    .sort-icon { font-size: 0.8em; margin-left: 5px; color: #cbd5e1; }
    .sort-active { color: #0ea5e9; }

    /* --- PAGINATION STYLES --- */
    .pagination-wrapper { display: flex; justify-content: flex-end; padding: 15px 20px; background: #fff; border-top: 1px solid #e2e8f0; }
    .page-item .page-link { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; text-decoration: none;  margin: 0 2px; }
    .page-item.active .page-link { background-color: #0ea5e9; border-color: #0ea5e9; color: white; }
    .page-item.disabled .page-link { color: #cbd5e1; pointer-events: none; background-color: #f8fafc; }
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between { display: none; }
    nav[role="navigation"] { width: 100%; display: flex; justify-content: space-between; align-items: center; }

    /* --- SEARCH FORM STYLES --- */
    .search-form { display: flex; align-items: center; gap: 5px; }
    .search-input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;  outline: none; transition: border-color 0.2s; }
    .search-input:focus { border-color: #0ea5e9; }

    /* --- FILTER BAR STYLES --- */
    .filter-bar {
        background: white; padding: 15px 20px; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;
        display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 12px; font-weight: 600; color: #64748b; }
    .filter-input {
        padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;
        font-size: 13px; outline: none; color: #334155;
    }
    .filter-input:focus { border-color: #1A5F7A; }
    
    .btn-filter {
        background: #1A5F7A; color: white; border: none; padding: 9px 16px; 
        border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px;
        transition: background 0.2s;
    }
    .btn-filter:hover { background: #134b61; }

    .btn-reset {
        background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; 
        padding: 9px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px;
        text-decoration: none; transition: background 0.2s;
    }
    .btn-reset:hover { background: #e2e8f0; }

    /* --- VIEW MODAL STYLES --- */
    .info-section {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 16px; margin-bottom: 20px;
    }
    .info-section h3 {
        margin: 0 0 15px 0; font-size: 16px; color: #1A5F7A;
        border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;
        display: flex; align-items: center; gap: 8px;
    }
    .info-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;
    }
    .info-item label {
        display: block; font-size: 11px; color: #64748b; text-transform: uppercase;
        font-weight: 600; margin-bottom: 4px;
    }
    .info-item p { margin: 0; font-weight: 500; color: #334155; }
    .info-item p.highlight { font-weight: 700; color: #0ea5e9; font-size: 16px; }
    
    /* Method Boxes */
    .method-box {
        padding: 15px; border-radius: 8px; border-left: 4px solid; margin-bottom: 15px;
    }
    .method-box h4 { margin: 0 0 5px 0; color: #1e293b;  font-weight: 700; }
    .method-box hr { border: 0; border-top: 1px dashed #cbd5e1; margin: 10px 0; }
    .method-green { background: #f0fdf4; border-color: #22c55e; }
    .method-orange { background: #fff7ed; border-color: #f97316; }

    /* Consent Badges */
    .consent-badge {
        padding: 8px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;
        display: flex; align-items: center; gap: 6px; width: fit-content;
    }
    .consent-badge.approved { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .consent-badge.pending { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
    .consent-badge.rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .consent-badge.unknown { background: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; }

    /* Dispense Modal */
    .dispense-list {
        max-height: 300px; overflow-y: auto; border: 1px solid #e2e8f0;
        border-radius: 8px; padding: 10px; margin-bottom: 15px;
    }
    .dispense-item {
        display: flex; align-items: center; padding: 10px;
        border-bottom: 1px solid #f1f5f9; gap: 15px; transition: background 0.2s;
    }
    .dispense-item:last-child { border-bottom: none; }
    .dispense-item.checked { background-color: #f0fdf4; }
    .auto-fill-info { font-size: 12px; color: #64748b; margin-top: 4px; display: none; }
    .dispense-item.checked .auto-fill-info { display: block; }

    /* New Styles for Dispense Inputs */
    .dispense-inputs { background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e2e8f0; }
    .dispense-group { margin-bottom: 10px; }
    .dispense-group label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
    .dispense-group input { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; }
    .total-bar { display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #e0f2fe; border-radius: 6px; margin-top: 10px; font-weight: bold; color: #0369a1; }
  </style>

  <div class="container">
    <div class="main-content">

      <div class="page-header">
        <h1>Milk Distribution</h1>
        <p>Manage allocations and dispensing for patient requests</p>
      </div>

      <form method="GET" action="{{ route('nurse.nurse_milk-request-list') }}" class="filter-bar">
          <div class="filter-group">
              <label>Search Patient</label>
              <input type="text" name="search" class="filter-input" value="{{ request('search') }}" placeholder="ID or Name">
          </div>
          <div class="filter-group">
              <label>Status</label>
              <select name="status" class="filter-input">
                  <option value="">All Statuses</option>
                  <option value="Waiting" {{ request('status') == 'Waiting' ? 'selected' : '' }}>Waiting</option>
                  <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                  <option value="Allocated" {{ request('status') == 'Allocated' ? 'selected' : '' }}>Allocated</option>
                  <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
              </select>
          </div>
          <div class="filter-group">
              <label>Volume (ml)</label>
              <div style="display:flex; gap:5px;">
                  <input type="number" name="vol_min" class="filter-input" style="width: 70px;" placeholder="Min" value="{{ request('vol_min') }}">
                  <input type="number" name="vol_max" class="filter-input" style="width: 70px;" placeholder="Max" value="{{ request('vol_max') }}">
              </div>
          </div>
          <div class="filter-group">
              <label>Date Requested</label>
              <div style="display:flex; gap:5px;">
                  <input type="date" name="req_date_from" class="filter-input" value="{{ request('req_date_from') }}">
                  <span style="align-self:center; color:#cbd5e1;">-</span>
                  <input type="date" name="req_date_to" class="filter-input" value="{{ request('req_date_to') }}">
              </div>
          </div>
          <div class="filter-group">
              <label>Feeding Date</label>
              <div style="display:flex; gap:5px;">
                  <input type="date" name="feed_date_from" class="filter-input" value="{{ request('feed_date_from') }}">
                  <span style="align-self:center; color:#cbd5e1;">-</span>
                  <input type="date" name="feed_date_to" class="filter-input" value="{{ request('feed_date_to') }}">
              </div>
          </div>
          <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Apply</button>
          <a href="{{ route('nurse.nurse_milk-request-list') }}" class="btn-reset"><i class="fas fa-undo"></i> Reset</a>
      </form>

      <div class="card">
        <div class="card-header" style="border-bottom: none; padding-bottom: 5px;">
          <h2>Request List</h2>
        </div>

        <div class="table-responsive">
            <table class="records-table">
              <thead>
                <tr>
                    <th onclick="sortTable(0)">Patient Name <i class="fas fa-sort sort-icon"></i></th>
                    <th onclick="sortTable(1)">NICU <i class="fas fa-sort sort-icon"></i></th>
                    <th onclick="sortTable(2)">Date Requested <i class="fas fa-sort sort-icon"></i></th>
                    <th onclick="sortTable(3)">Feeding Time <i class="fas fa-sort sort-icon"></i></th>
                    <th onclick="sortTable(4)">Daily Volume <i class="fas fa-sort sort-icon"></i></th>
                    <th>Status</th>
                    <th style="text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody> 
                @forelse($requests as $req)
                <tr>
                    <td>
                        <strong>{{ $req->json_data['formatted_id'] }}</strong><br>
                        {{ $req->json_data['patient_name'] }}
                    </td>
                    <td>{{ $req->json_data['cubicle'] }}</td>
                    <td>{{ $req->json_data['date_requested'] }}</td>
                    <td>{{ $req->json_data['feed_time'] }}</td>
                    <td>{{ $req->json_data['total_vol'] }} ml</td>
                    <td>
                        <span class="status {{ strtolower($req->status ?? 'waiting') }}">
                            {{ $req->status ?? 'Waiting' }}
                        </span>
                    </td>
                    <td class="actions">
                        {{-- 1. View Details --}}
                        <button type="button" class="btn-view" onclick='openViewModal(@json($req->json_data))' title="View Details" style="color: #16a34a;">
                            <i class="fas fa-eye"></i>
                        </button>
                        
                        {{-- 2. Allocate (Logic: Not Approved/Rejected) --}}
                        @if($req->status != 'Approved' && $req->status != 'Rejected')
                            <button type="button" class="btn-view" style="color: #f59e0b;" 
                                onclick='openMilkModal(this)'
                                data-id="{{ $req->request_ID }}"
                                data-patient-id="{{ $req->json_data['formatted_id'] }}"
                                data-patient-name="{{ $req->json_data['patient_name'] }}"
                                data-weight="{{ $req->json_data['weight'] }}"
                                data-dob="{{ $req->parent->pr_BabyDOB ?? '' }}"
                                data-ward="{{ $req->json_data['cubicle'] }}"
                                data-volume="{{ $req->json_data['total_vol'] }}"
                                
                                >
                                <i class="fa-solid fa-plus"></i><i class="fas fa-prescription-bottle"></i>
                            </button>
                        @endif

                        {{-- 3. Dispense (Check allocated items count) --}}
                        @if(isset($req->json_data['allocated_items']) && count($req->json_data['allocated_items']) > 0)
                            <button type="button" class="btn-view" style="color: #0891b2;" 
                                onclick='openDispenseModal(@json($req->json_data))'>
                                <i class="fas fa-clipboard-check"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px; color: #94a3b8;">
                        <i class="fas fa-search fa-2x" style="margin-bottom:10px; opacity:0.5;"></i><br>
                        No requests found.
                    </td>
                </tr>
                @endforelse
              </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $requests->links() }}
        </div>

      </div>
    </div>
  </div>

  {{-- ========================================================= --}}
  {{-- 1. VIEW DETAILS MODAL (FIXED) --}}
  {{-- ========================================================= --}}
  <div id="viewRequestModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-file-medical"></i> Milk Request Details</h2>
            <button class="modal-close-btn" onclick="closeViewModal()">Close</button>
        </div>
        
        <div class="modal-body">
            
            {{-- 1. Patient & Medical Info --}}
            <div class="info-section">
                <h3><i class="fas fa-baby"></i> Patient & Medical</h3>
                <div class="info-grid">
                    <div class="info-item"> <label>Patient Name</label> <p id="view-patient-name">-</p> </div>
                    <div class="info-item"> <label>Patient ID</label> <p id="view-formatted-id">-</p> </div>
                    <div class="info-item"> <label>Date of Birth</label> <p id="view-dob">-</p> </div>
                    <div class="info-item"> <label>Age</label> <p id="view-age">-</p> </div>
                    <div class="info-item"> <label>NICU Location</label> <p id="view-cubicle">-</p> </div>
                    <div class="info-item"> <label>Allergies</label> <p id="view-allergy" style="color:#ef4444; font-weight:bold;">-</p> </div>
                    <div class="info-item"> <label>Attending Doctor</label> <p id="view-doctor">-</p> </div>
                    <div class="info-item"> <label>Request Status</label> <p id="view-status">-</p> </div>
                </div>
            </div>

            {{-- 2. Clinical Info --}}
            <div class="info-section">
                <h3><i class="fas fa-stethoscope"></i> Clinical Information</h3>
                <div class="info-grid">
                    <div class="info-item"> <label>Current Weight</label> <p><span id="view-weight">-</span> kg</p> </div>
                    <div class="info-item"> <label>Gestational Weeks</label> <p id="view-gestational">-</p> </div>
                    <div class="info-item"> <label>Total Daily Volume</label> <p class="highlight"><span id="view-total-vol">-</span> ml</p> </div>
                </div>
            </div>
            
            {{-- 3. Consent Status --}}
            <div class="info-section consent-section">
                <h3><i class="fas fa-handshake"></i> Milk Kinship Consent</h3>
                <div id="consent-badge-container"></div>
            </div>

            {{-- 4. Dispensing Method --}}
            <div class="info-section">
                <h3><i class="fas fa-prescription-bottle"></i> Dispensing Method</h3>
                
                {{-- Method A: Kinship --}}
                <div id="method-kinship-yes" class="method-box method-green" style="display:none;">
                    <h4><i class="fas fa-users"></i> Method A: Milk Kinship (Full Nursing)</h4>
                    <div class="info-grid">
                        <div class="info-item"> 
                            <label>Volume Per Feed</label> 
                            <p class="highlight"><span id="view-kinship-vol"></span> ml</p> 
                            <small style="color:#64748b;">Direct Oral Feed Via: <b><span id="view-kinship-oral-method"></span></b></small>
                        
                        </div>
                    </div>
                </div>

                {{-- Method B: No Kinship --}}
                <div id="method-kinship-no" class="method-box method-orange" style="display:none;">
                    <h4><i class="fas fa-ban"></i> Method B: No Kinship (Restricted)</h4>
                    <div class="info-grid">
                        <div class="info-item"> 
                            <label>Drip / Tube Feed</label> 
                            <p class="highlight"><span id="view-drip-vol"></span> ml</p> 
                            <small style="color:#64748b;">Via: <b><span id="view-tube-method"></span></b></small>
                        </div>
                        <div class="info-item"> 
                            <label>Direct Oral Feed</label> 
                            <p class="highlight"><span id="view-oral-vol"></span> ml</p> 
                            <small style="color:#64748b;">Via: <b><span id="view-oral-method"></span></b></small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Schedule --}}
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
    </div>
</div>

  {{-- 2. Allocate Milk Modal --}}
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
        <form id="milkAllocationForm" method="POST" action="{{ route('nurse.allocate.milk') }}">
            @csrf
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
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <label style="margin:0;">Milk Unit ID (Select from Inventory)</label>
                    
                    <label style="font-size:13px; color:#0ea5e9; cursor:pointer; display:flex; align-items:center; gap:5px;">
                        <input type="checkbox" id="selectAllMilk"> Select all
                    </label>
                </div>

                <div id="milkListSelect" class="milk-list">
                    @foreach($postbottles as $pb)
                    <div class="milk-item" data-id="{{ $pb->post_ID }}">
                        <div style="display: flex; align-items: flex-start; gap: 10px; width: 100%;">
                            {{-- Add name="milk_ids[]" for easier selection, but we use JS mainly --}}
                            <input type="checkbox" class="milk-checkbox" value="{{ $pb->post_ID }}" data-volume="{{ $pb->post_volume }}" style="margin-top: 5px; cursor: pointer;">
                            <div style="flex-grow: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <strong>{{ $pb->post_bottle_code }} — {{ $pb->post_volume }} ml</strong>
                                    <span class="badge-consent"><i class="fas fa-check-circle"></i> Donor Consent</span>
                                </div>
                                <span style="font-size: 12px; color: #666;">Expires {{ \Carbon\Carbon::parse($pb->post_expiry_date)->format('d M Y') }}</span>
                                <div style="font-size: 12px; color: #666; margin-top: 4px;">
                                    <div>#D{{ $pb->milk->donor->dn_ID }} -  {{ $pb->milk->donor->dn_FullName }}</div>
                                    <div>{{ $pb->milk->donor->dn_Contact }}</div>
                                    <div>{{ $pb->milk->donor->dn_Email }}</div>
                                </div>
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

  {{-- 3. DISPENSE MODAL (Redesigned) --}}
  <div id="dispenseModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Dispense Milk</h2>
        <button class="modal-close-btn" onclick="closeDispenseModal()">Close</button>
      </div>
      
      <form id="dispenseForm" method="POST" action="{{ route('nurse.dispense.milk') }}" onsubmit="return validateDispenseForm()">
          @csrf
          <div class="modal-body">
            <p style="color:#64748b; margin-bottom: 15px;">Dispensing for: <strong><span id="dispensePatientName"></span></strong></p>
            
            {{-- DYNAMIC INPUTS SECTION --}}
            <div class="dispense-inputs" id="dispenseInputSection">
                {{-- JS will inject Oral/Tube inputs here based on Kinship --}}
            </div>

            <p style="font-size:12px; font-weight:600; color:#64748b; margin-bottom:5px;">Select Bottles Used:</p>
            <div class="dispense-list" id="dispenseListContainer">
                {{-- JS populates bottles --}}
            </div>

            <div class="total-bar">
                <span>Total Selected:</span>
                <span id="dispenseTotalSelected">0 ml</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">Complete Dispensing</button>
          </div>
      </form>
    </div>
  </div>

  <script>
    // --- 1. VIEW DETAILS LOGIC ---
    function openViewModal(data) {
        // --- Populate Basic Data ---
        document.getElementById('view-patient-name').textContent = data.patient_name;
        document.getElementById('view-dob').textContent = data.patient_dob;
        document.getElementById('view-formatted-id').textContent = data.formatted_id;
        document.getElementById('view-cubicle').textContent = data.cubicle;
        
        // New Fields
        document.getElementById('view-doctor').textContent = data.doctor_name;
        document.getElementById('view-allergy').textContent = data.allergy_info;
        document.getElementById('view-status').textContent = data.status;

        document.getElementById('view-weight').textContent = data.weight;
        document.getElementById('view-age').textContent = data.age;
        document.getElementById('view-gestational').textContent = data.gestational;
        document.getElementById('view-total-vol').textContent = data.total_vol;

        document.getElementById('view-start-date').textContent = data.date_requested;
        document.getElementById('view-start-time').textContent = data.feed_time.split(' ')[1] || '-';
        document.getElementById('view-feeds').textContent = data.feeds;
        document.getElementById('view-interval').textContent = data.interval;

        // --- Consent Logic ---
        const consentContainer = document.getElementById('consent-badge-container');
        let badgeHtml = '';
        if (data.parent_consent === 'Approved') {
            badgeHtml = `<div class="consent-badge approved"><i class="fas fa-check-circle"></i> Parent Consent Approved</div>`;
        } else if (data.parent_consent === 'Pending') {
            badgeHtml = `<div class="consent-badge pending"><i class="fas fa-clock"></i> Consent Pending</div>`;
        } else if (data.parent_consent === 'Rejected') {
            badgeHtml = `<div class="consent-badge rejected"><i class="fas fa-times-circle"></i> Consent Rejected</div>`;
        } else {
            badgeHtml = `<div class="consent-badge unknown"><i class="fas fa-question-circle"></i> Status Unknown</div>`;
        }
        consentContainer.innerHTML = badgeHtml;

        // --- Dispensing Logic ---
        const kinshipYes = document.getElementById('method-kinship-yes');
        const kinshipNo = document.getElementById('method-kinship-no');

        if (data.kinship_method === 'yes') {
            kinshipYes.style.display = 'block';
            kinshipNo.style.display = 'none';
            document.getElementById('view-kinship-vol').textContent = data.volume_per_feed || '-';
            document.getElementById('view-kinship-oral-method').textContent = data.oral_method || 'N/A';
        } else {
            kinshipYes.style.display = 'none';
            kinshipNo.style.display = 'block';
            document.getElementById('view-drip-vol').textContent = data.drip_total || '0';
            document.getElementById('view-oral-vol').textContent = data.oral_per_feed || '0';
            document.getElementById('view-tube-method').textContent = data.tube_method || 'N/A';
            document.getElementById('view-oral-method').textContent = data.oral_method || 'N/A';
        }

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

    // Helper to toggle a single milk item
    function toggleMilkSelection(checkbox, isChecked) {
        const id = checkbox.value;
        const volume = checkbox.getAttribute("data-volume");
        const itemDiv = checkbox.closest(".milk-item");

        checkbox.checked = isChecked; // Force visual state

        if (isChecked) {
            // Add if not exists
            if (!selectedMilkUnits.find(m => m.id == id)) {
                selectedMilkUnits.push({ id, volume });
            }
            itemDiv.classList.add("selected");
        } else {
            // Remove
            selectedMilkUnits = selectedMilkUnits.filter(m => m.id != id);
            itemDiv.classList.remove("selected");
        }
    }

    // Individual Checkbox Event
    document.querySelectorAll(".milk-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            toggleMilkSelection(this, this.checked);
            updateTotalVolume();
            
            // Uncheck "Select All" if one is unchecked
            if(!this.checked) document.getElementById('selectAllMilk').checked = false;
        });
    });

    // SELECT ALL Event
    document.getElementById('selectAllMilk').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.milk-checkbox').forEach(cb => {
            toggleMilkSelection(cb, isChecked);
        });
        updateTotalVolume();
    });

    // OPEN MODAL
    function openMilkModal(button) {
        selectedRequestId = button.getAttribute('data-id');
        // ... (Existing populate logic: Name, ID, Weight, etc.) ...
        document.getElementById('modalPatientID').textContent = button.getAttribute('data-patient-id');
        document.getElementById('modalPatientName').textContent = button.getAttribute('data-patient-name');
        document.getElementById('modalWeight').value = button.getAttribute('data-weight');
        document.getElementById('modalDob').value = button.getAttribute('data-dob');
        document.getElementById('modalWard').value = button.getAttribute('data-ward');
        document.getElementById('modalVolume').value = button.getAttribute('data-volume');

        // Reset
        selectedMilkUnits = [];
        document.getElementById("totalVolume").textContent = "0";
        document.getElementById("selectAllMilk").checked = false;
        document.querySelectorAll('.milk-checkbox').forEach(cb => {
            cb.checked = false;
            cb.closest('.milk-item').classList.remove('selected');
        });

        document.getElementById('milkModal').style.display = 'flex';
    }

    // SUBMIT ALLOCATION
    document.getElementById('milkAllocationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (selectedMilkUnits.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one Milk Unit.'});
            return;
        }

        // Prepare Data for Backend
        const payload = {
            request_id: selectedRequestId,
            storage_location: document.getElementById('storageLocation').value,
            selected_milk: selectedMilkUnits, // Array of {id, volume}
            // Add total volume of ALL bottles combined if needed, or backend can calc
            total_volume: document.getElementById('totalVolume').textContent 
        };

        // Send Request
        fetch("{{ route('nurse.allocate.milk') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeMilkModal();
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Milk Allocated!', 
                    text: data.message, 
                    confirmButtonColor: '#0ea5e9'
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Allocation failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Server error occurred', 'error');
        });
    });

    function closeMilkModal() {
        document.getElementById('milkModal').style.display = 'none';
    }

    // --- 3. DISPENSE MODAL LOGIC ---
    function openDispenseModal(reqData, kinshipMethod) {
        document.getElementById('dispensePatientName').textContent = reqData.parent?.pr_BabyName || '-';
        
        // 1. Build Inputs based on Kinship
        const inputSection = document.getElementById('dispenseInputSection');
        inputSection.innerHTML = '';

        if (kinshipMethod === 'yes') {
            // Kinship YES: Only Oral
            inputSection.innerHTML = `
                <div class="dispense-group">
                    <label>Oral Feeding Volume (ml)</label>
                    <input type="number" name="oral_volume" required placeholder="Enter amount fed orally">
                </div>
            `;
        } else {
            // Kinship NO: Both
            inputSection.innerHTML = `
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                    <div class="dispense-group">
                        <label>Oral Feeding (ml)</label>
                        <input type="number" name="oral_volume" placeholder="0">
                    </div>
                    <div class="dispense-group">
                        <label>Tube/Drip Feeding (ml)</label>
                        <input type="number" name="tube_volume" placeholder="0">
                    </div>
                </div>
            `;
        }

        // 2. Build Bottle List
        const container = document.getElementById('dispenseListContainer');
        container.innerHTML = '';
        
        // SAFE CHECK for allocations array
        const allocations = reqData.allocations || [];
        
        allocations.forEach((item, index) => {
            const isDispensed = item.dispense_date !== null;
            const bottleCode = item.post_bottles ? item.post_bottles.post_bottle_code : 'Unknown';
            const volume = item.total_selected_milk || 0;

            const inputHtml = isDispensed ? '' : 
                `<input type="checkbox" name="items[${index}][allocation_id]" value="${item.allocation_ID}" 
                  class="dispense-check" data-vol="${volume}" style="width:18px; height:18px;" onchange="updateDispenseTotal()">`;

            const div = document.createElement('div');
            div.className = `dispense-item ${isDispensed ? 'checked' : ''}`;
            div.innerHTML = `
                <label style="display:flex; width:100%; align-items:center; cursor:pointer;">
                    ${inputHtml}
                    <div style="flex:1; margin-left:10px;">
                        <div style="font-weight:600; color:#1A5F7A;">${bottleCode} ${isDispensed ? '(Dispensed)' : ''}</div>
                        <div style="font-size:13px; color:#64748b;">Volume: ${volume} ml</div>
                    </div>
                </label>
            `;
            container.appendChild(div);
        });

        updateDispenseTotal(); // Reset count
        document.getElementById('dispenseModal').style.display = 'flex';
    }
    
    function validateDispenseForm() {
        const checked = document.querySelectorAll('.dispense-check:checked');
        if (checked.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No Bottles Selected', text: 'Please check the bottles used for this feed.' });
            return false;
        }
        return true;
    }

    function updateDispenseTotal() {
        const checked = document.querySelectorAll('.dispense-check:checked');
        let total = 0;
        checked.forEach(cb => {
            total += parseFloat(cb.getAttribute('data-vol')) || 0;
        });
        document.getElementById('dispenseTotalSelected').textContent = total + ' ml';
    }

    function closeDispenseModal() {
        document.getElementById('dispenseModal').style.display = 'none';
    }

    function toggleDispense(checkbox) {
        const item = checkbox.closest('.dispense-item');
        const timeSpan = item.querySelector('.dispense-time');
        
        if (checkbox.checked) {
            item.classList.add('checked');
            const now = new Date();
            timeSpan.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else {
            item.classList.remove('checked');
            timeSpan.textContent = '--:--';
        }
    }

    function saveDispense() {
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
        }).then(() => location.reload());
    }
    

    

    // Modal Closing Logic (Outside Click)
    window.addEventListener("click", function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    });

    // --- 4. TABLE SORTING LOGIC ---
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.querySelector(".records-table");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc"; 
        
        // Loop until no switching has been done:
        while (switching) {
            switching = false;
            rows = table.rows;
            
            // Loop through all table rows (except the first, which contains table headers):
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                
                // Get the two elements you want to compare
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                
                // Compare content
                let xContent = x.innerText.toLowerCase();
                let yContent = y.innerText.toLowerCase();

                // Check if rows should switch based on direction
                if (dir == "asc") {
                    if (xContent > yContent) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (xContent < yContent) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            
            if (shouldSwitch) {
                // Do the switch
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;      
            } else {
                // If no switch done AND direction is "asc", switch to "desc" and rerun
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
        
        // Update the Visual Arrows
        updateSortIcons(n, dir);
    }

    function updateSortIcons(columnIndex, direction) {
        // 1. Reset all icons to default 'fa-sort' (gray)
        document.querySelectorAll(".records-table th .sort-icon").forEach(icon => {
            icon.className = "fas fa-sort sort-icon"; // Reset class
            icon.classList.remove("sort-active"); // Remove color
        });

        // 2. Target the specific header clicked
        const header = document.querySelectorAll(".records-table th")[columnIndex];
        const icon = header.querySelector(".sort-icon");
        
        // 3. Update class based on direction
        if (direction === "asc") {
            icon.className = "fas fa-sort-up sort-icon sort-active";
        } else {
            icon.className = "fas fa-sort-down sort-icon sort-active";
        }
    }
  </script>
@endsection