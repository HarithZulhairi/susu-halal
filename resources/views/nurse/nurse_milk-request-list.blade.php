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
    .stamp-info { 
        font-size: 11px; 
        color: #059669; /* Success Green */
        background: #ecfdf5; 
        padding: 2px 8px; 
        border-radius: 4px; 
        border: 1px solid #a7f3d0;
        font-weight: 600; 
        display: none; 
        white-space: nowrap;
    }

    /* Flex adjustments for the rows */
    .sub-feed-item, .dispense-item {
        min-height: 40px;
    }

    .feed-check:checked ~ div .stamp-info { 
        display: inline-block; 
    }
    
    .bottle-group { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 10px; overflow: hidden; }
    .bottle-header { 
        background: #f1f5f9; padding: 10px 15px; cursor: pointer; 
        display: flex; justify-content: space-between; align-items: center;
        font-weight: 600; color: #1e293b;
    }
    .sub-feed-list { display: none; background: white; padding: 10px; border-top: 1px solid #e2e8f0; }
    .sub-feed-item { 
        display: flex; align-items: center; gap: 10px; padding: 8px; 
        border-bottom: 1px dashed #f1f5f9; 
    }
    .sub-feed-item:last-child { border-bottom: none; }
    .bottle-group.active .sub-feed-list { display: block; }
    .bottle-group.active .fa-chevron-down { transform: rotate(180deg); }
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
                        <div class="patient-info">
                            <i class="fas fa-baby milk-icon" style="font-size: 20px; padding: 8px;"></i>
                            <div>
                                {{-- DIRECT BLADE ACCESS --}}
                                <strong>{{ $req->parent->formattedID ?? '-' }}</strong><br>
                                <span>{{ $req->parent->pr_BabyName ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $req->parent->pr_NICU ?? '-' }}</td>
                    <td>{{ $req->created_at->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->feeding_start_time)->format('H:i') }}</td>
                    <td>{{ $req->total_daily_volume }} ml</td>
                    <td>
                        @if($req->status === 'Fully Dispensed') <span class="status success">Fully Dispensed</span>
                        @elseif($req->status === 'Allocated') <span class="status allocated">Allocated</span>
                        @else <span class="status {{ strtolower($req->status) }}">{{ $req->status ?? 'Waiting' }}</span>
                        @endif
                    </td>
                    <td class="actions">
                        {{-- PASS RAW MODEL TO JS --}}
                        <button type="button" class="btn-view" style="color: #16a34a;" onclick='openViewModal(@json($req))'><i class="fas fa-eye"></i></button>

                        <!-- <button type="button" class="btn-view" style="color: #16a34a;" onclick='openViewModal(this)'
                        data-id="{{ $req->request_ID }}" 
                        data-formatted-id="{{ $req->parent->formattedID }}" 
                        data-patient-name="{{ $req->parent->pr_BabyName }}" 
                        data-weight="{{ $req->current_weight }}" 
                        data-dob="{{ $req->parent->pr_BabyDOB }}" 
                        data-ward="{{ $req->parent->pr_NICU }}" 
                        data-volume="{{ $req->total_daily_volume }}">
                                  
                            <i class="fas fa-eye"></i>    
                                      
                        </button> -->

                        <button type="button" class="btn-view" style="color: #f59e0b;" onclick='openMilkModal(this)' 
                        data-id="{{ $req->request_ID }}" 
                        data-formatted-id="{{ $req->parent->formattedID }}" 
                        data-patient-name="{{ $req->parent->pr_BabyName }}" 
                        data-weight="{{ $req->current_weight }}" 
                        data-dob="{{ $req->parent->pr_BabyDOB }}" 
                        data-ward="{{ $req->parent->pr_NICU }}" 
                        data-volume="{{ $req->total_daily_volume }}">
                            
                            <i class="fas fa-plus"></i><i class="fas fa-prescription-bottle"></i>
                    
                        </button>

                        @if($req->status === 'Allocated')
                            {{-- PASS RAW MODEL TO JS (Allocations included via "with" in controller) --}}
                            <button type="button" class="btn-view" style="color: #0891b2;" 
                                onclick='openDispenseModal(@json($req))'>
                                <i class="fas fa-clipboard-check"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;">No requests found.</td></tr>
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
                            <p class="highlight"><span id="view-kinship-vol"></span></p> 
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
                            <p class="highlight"><span id="view-drip-vol"></span></p> 
                            <small style="color:#64748b;">Via: <b><span id="view-tube-method"></span></b></small>
                        </div>
                        <div class="info-item"> 
                            <label>Direct Oral Feed</label> 
                            <p class="highlight"><span id="view-oral-vol"></span></p> 
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
                <span style="font-size: 14px; color: #64748b;" id="modalPatientName"></span>
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

    {{-- 3. REDESIGNED DISPENSE MODAL --}}
        <div id="dispenseModal" class="modal-overlay" style="display: none;">
            <div class="modal-content" style="max-width: 800px;">
                <div class="modal-header">
                    <h2><i class="fas fa-clipboard-check"></i> Dispense Milk Allocation</h2>
                    <button class="modal-close-btn" onclick="closeDispenseModal()">Close</button>
                </div>
                
                    <div class="modal-body">
                        <form id="dispenseForm" method="POST" action="{{ route('nurse.log-feed-record') }}">
                            @csrf
                            <input type="hidden" name="request_id" id="dispenseRequestID" value="">
                            {{-- STEP 1: SELECTION SCREEN --}}
                            <div id="dispense-step-selection">
                                <div style="background: #f0f9ff; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bae6fd; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <p style="margin:0; font-size: 14px; color: #0369a1;">Total Volume: <strong>{{ $req->drip_total }} ml</strong></p>
                                        <p style="margin:0; font-size: 12px; color: #0c4a6e;">Select bottles for <strong>Tube Feeding</strong>.</p>
                                    </div>
                                    {{-- REAL TIME COUNTER --}}
                                    <div style="text-align: right; background: white; padding: 8px 15px; border-radius: 8px; border: 1px solid #bae6fd;">
                                        <label style="font-size: 10px; color: #64748b; display: block; text-transform: uppercase;">Selected Tube Vol</label>
                                        <strong style="font-size: 18px; color: #ef4444;"><span id="current-tube-total">0</span> ml</strong>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h3><i class="fas fa-tasks"></i> Select Bottles </h3>
                                    <div id="bottle-selection-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-height: 300px; overflow-y: auto; padding: 5px;"></div>
                                </div>

                                <button type="button" class="btn-filter" style="width:100%; padding: 15px;" onclick="confirmBottleAllocation()">CONFIRM ALLOCATION</button>
                            </div>

                            {{-- STEP 2: CONFIRMED VIEW --}}
                            <div id="dispense-step-confirmed" style="display: none;">
                                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #1A5F7A;">
                                    <p style="margin:0; font-size: 14px; color: #64748b;">Patient: <strong id="dispensePatientName" style="color:#1e293b;">Baby Default</strong></p>
                                </div>

                                <div class="info-section">
                                    <h3 style="color: #ef4444;"><i class="fas fa-syringe"></i> 1. Tube / Drip Feeding Results (<span id="final-tube-vol">0</span> ml)</h3>
                                    <div id="tube-confirmed-list" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 15px;"></div>
                                    <div class="dispense-item">
                                        <label style="display:flex; width:100%; align-items:center; justify-content: space-between; cursor:pointer;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <input type="checkbox" class="feed-check" onchange="markAsDone(this)">
                                                <strong>Verify Full Tube Feed</strong>
                                            </div>
                                            <div class="stamp-info">--:--</div>
                                        </label>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h3 style="color: #10b981;"><i class="fas fa-baby-carriage"></i> 2. Oral Feeding Results (<span id="final-oral-vol">0</span> ml)</h3>
                                    <div id="oral-confirmed-container"></div>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div id="final-action-buttons" style="display: flex; gap: 10px; margin-top: 15px;">
                                    <button type="button" class="btn-reset" style="flex: 1;" onclick="resetToSelection()">RE-ALLOCATE BOTTLES</button>
                                    <button type="button" class="btn-filter" style="flex: 1;" onclick="finalizeDispensing()">CONFIRM & LOCK</button>
                                </div>
                            </div>
                        </form>
                    </div>
                
                
            </div>
        </div>

  <script>
    // --- 1. VIEW DETAILS LOGIC ---
    function capitalize(value) {
        if (!value || typeof value !== 'string') return '-';
        return value.charAt(0).toUpperCase() + value.slice(1);
    }

    function openViewModal(data) {
        // Helper to safely access nested properties
        const parent = data.parent || {};
        const doctor = data.doctor || {};
        
        // --- Populate Basic Data ---
        document.getElementById('view-patient-name').textContent = parent.pr_BabyName || '-';
        document.getElementById('view-dob').textContent = parent.pr_BabyDOB || '-';
        document.getElementById('view-formatted-id').textContent = "#P" + parent.pr_ID || '-';
        document.getElementById('view-cubicle').textContent = parent.pr_NICU || '-';
        
        // --- New Fields ---
        document.getElementById('view-doctor').textContent = doctor.dr_Name || 'Unknown';
        document.getElementById('view-allergy').textContent = parent.pr_Allergy || 'None';
        document.getElementById('view-status').textContent = data.status || '-';

        document.getElementById('view-weight').textContent = data.current_weight || '-';
        document.getElementById('view-age').textContent = data.current_baby_age || '-'; // Assuming you have an accessor for this
        document.getElementById('view-gestational').textContent = data.gestational_age || '-';
        document.getElementById('view-total-vol').textContent = data.total_daily_volume || '-';

        // --- Schedule ---
        // Note: You might need simple formatting here if the raw date is YYYY-MM-DD
        document.getElementById('view-start-date').textContent = data.feeding_start_date || '-';
        document.getElementById('view-start-time').textContent = data.feeding_start_time || '-';
        document.getElementById('view-feeds').textContent = data.feeding_perday || '-';
        document.getElementById('view-interval').textContent = data.feeding_interval || '-';

        // --- Consent Logic ---
        const consentContainer = document.getElementById('consent-badge-container');
        const status = parent.pr_ConsentStatus;
        let badgeHtml = '';

        if (status === 'Approved') {
            badgeHtml = `<div class="consent-badge approved"><i class="fas fa-check-circle"></i> Parent Consent Approved</div>`;
        } else if (status === 'Pending') {
            badgeHtml = `<div class="consent-badge pending"><i class="fas fa-clock"></i> Consent Pending</div>`;
        } else if (status === 'Rejected') {
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
            // Populate Kinship Fields
            document.getElementById('view-kinship-vol').textContent = data.volume_per_feed ? data.volume_per_feed + ' ml' : '-';
            document.getElementById('view-kinship-oral-method').textContent = capitalize(data.oral_feeding);
        } else {
            kinshipYes.style.display = 'none';
            kinshipNo.style.display = 'block';
            // Populate Standard Fields
            document.getElementById('view-drip-vol').textContent = data.drip_total ? data.drip_total + ' ml' : '-';
            document.getElementById('view-oral-vol').textContent = data.oral_total ? data.oral_total + ' ml' : '-';
            document.getElementById('view-tube-method').textContent = capitalize(data.feeding_tube);
            document.getElementById('view-oral-method').textContent = capitalize(data.oral_feeding);
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
        document.getElementById('modalPatientID').textContent = button.getAttribute('data-formatted-id');
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

        let totalBottles = 15;
        const bottleVol = 30;

        function openDispenseModal(data) {
            // 1. Reset UI States
            document.getElementById('dispense-step-selection').style.display = 'block';
            document.getElementById('dispense-step-confirmed').style.display = 'none';
            document.getElementById('final-action-buttons').style.display = 'flex'; 
            document.getElementById('current-tube-total').textContent = "0";

            // 2. Populate Bottle Grid from Data
            const selectionGrid = document.getElementById('bottle-selection-grid');
            selectionGrid.innerHTML = '';

            // Safety check for allocations array
            const allocations = data.allocations || [];

            if (allocations.length === 0) {
                selectionGrid.innerHTML = '<p style="grid-column: span 3; text-align: center; color: #94a3b8; padding: 20px;">No bottles allocated for this request.</p>';
            } else {
                allocations.forEach(item => {
                    // Determine status
                    const isDispensed = item.dispense_date !== null;
                    const bottleCode = item.post_bottles ? item.post_bottles.post_bottle_code : 'Unknown';
                    const volume = item.total_selected_milk || 0;
                    
                    // Skip already dispensed bottles if you only want to select fresh ones for the plan
                    // (Remove this if check if you want to see all)
                    if (isDispensed) return; 

                    selectionGrid.innerHTML += `
                        <label style="border: 1px solid #e2e8f0; padding: 10px; border-radius: 6px; display: flex; align-items: center; gap: 8px; cursor: pointer; background: white; transition: all 0.2s;">
                            <input type="checkbox" class="tube-alloc-check" 
                                value="${item.allocation_ID}" 
                                data-vol="${volume}" 
                                onchange="updateTubeCounter()">
                            
                            <div style="line-height: 1.2;">
                                <span style="font-size: 12px; font-weight: 700; color: #1e293b; display: block;">${bottleCode}</span>
                                <span style="font-size: 11px; color: #64748b;">${volume} ml</span>
                            </div>
                        </label>
                    `;
                });
            }
            
            // 3. Show Modal
            document.getElementById('dispenseModal').style.display = 'flex';
        }

        // Real-time counter logic
        function updateTubeCounter() {
            let total = 0;
            const checkboxes = document.querySelectorAll('.tube-alloc-check:checked');
            
            checkboxes.forEach(cb => {
                // Parse the volume from the data attribute we added above
                total += parseFloat(cb.getAttribute('data-vol')) || 0;
            });

            document.getElementById('current-tube-total').textContent = total;
        }

        function confirmBottleAllocation() {
            const allChecks = document.querySelectorAll('.tube-alloc-check');
            let tubeBottles = [];
            let oralBottles = [];

            allChecks.forEach(cb => {
                if (cb.checked) tubeBottles.push(cb.value);
                else oralBottles.push(cb.value);
            });

            // Update Totals Labels
            document.getElementById('final-tube-vol').textContent = tubeBottles.length * bottleVol;
            document.getElementById('final-oral-vol').textContent = oralBottles.length * bottleVol;

            // Populate Tube List
            const tubeList = document.getElementById('tube-confirmed-list');
            tubeList.innerHTML = tubeBottles.map(id => 
                `<div style="font-size:11px; background:#fee2e2; border:1px solid #fecaca; padding:4px; border-radius:4px; text-align:center;">#${id} (30ml)</div>`
            ).join('') || '<p style="color:#94a3b8; font-size:12px;">No bottles allocated for tube feeding.</p>';

            // Populate Oral Container
            const oralContainer = document.getElementById('oral-confirmed-container');
            oralContainer.innerHTML = '';
            oralBottles.forEach(id => {
                const bottleDiv = document.createElement('div');
                bottleDiv.className = 'bottle-group';
                bottleDiv.innerHTML = `
                    <div class="bottle-header" onclick="this.parentElement.classList.toggle('active')">
                        <span><i class="fas fa-prescription-bottle"></i> Bottle #${id} (30ml) - Oral</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="sub-feed-list">${generateSubFeedsForId(id)}</div>
                `;
                oralContainer.appendChild(bottleDiv);
            });

            document.getElementById('dispense-step-selection').style.display = 'none';
            document.getElementById('dispense-step-confirmed').style.display = 'block';
        }

        function finalizeDispensing() {
            closeDispenseModal();
            Swal.fire({
                title: 'Finalize Allocation?',
                text: "The distribution methods will be locked.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1A5F7A',
                confirmButtonText: 'Yes, Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hide the buttons as requested
                    document.getElementById('final-action-buttons').style.display = 'none';
                    Swal.fire('Confirmed!', 'Bottle allocation has been locked.', 'success');
                }
            });
        }

        function generateSubFeedsForId(id) {
            let html = '';
            for (let i = 1; i <= 4; i++) {
                html += `
                    <div class="sub-feed-item" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" class="feed-check" onchange="markAsDone(this)">
                            <div style="font-size:12px;">7.5 ml feed</div>
                        </div>
                        <div class="stamp-info">--:--</div>
                    </div>
                `;
            }
            return html;
        }

        function markAsDone(checkbox, allocationId, volume) {
            if (!checkbox.checked) return;

            fetch("{{ route('nurse.log-feed-record') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    allocation_id: allocationId,
                    fed_volume: volume // e.g., 7.5
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update UI with the nurse name and time returned by backend
                    const parent = checkbox.closest('.sub-feed-item');
                    const stamp = parent.querySelector('.stamp-info');
                    stamp.innerHTML = `<i class="fas fa-check"></i> ${data.nurse_name} at ${data.time}`;
                    stamp.style.display = 'inline-block';
                    checkbox.disabled = true;
                }
            });
        }

        function resetToSelection() {
            document.getElementById('dispense-step-selection').style.display = 'block';
            document.getElementById('dispense-step-confirmed').style.display = 'none';
        } 

    // --- CALCULATION LOGIC ---
    function recalcDispenseTotals() {
        let totalVol = 0;
        let totalOral = 0;
        let totalTube = 0;

        // Loop through all checked boxes
        document.querySelectorAll('.dispense-check:checked:not([disabled])').forEach(cb => {
            const vol = parseFloat(cb.getAttribute('data-vol')) || 0;
            totalVol += vol;

            // Find the sibling dropdown to see method
            // We traverse up to parent row, then find the select with class 'dispense-method'
            const row = cb.closest('.dispense-item');
            const methodSelect = row.querySelector('.dispense-method');
            
            if(methodSelect) {
                if(methodSelect.value === 'oral') {
                    totalOral += vol;
                } else {
                    totalTube += vol;
                }
            }
        });

        // Update UI
        document.getElementById('dispenseTotalSelected').textContent = totalVol + " ml";
        document.getElementById('calc_oral_vol').value = totalOral + " ml";
        document.getElementById('calc_tube_vol').value = totalTube + " ml";
    }
    
    function validateDispenseForm() {
        const checked = document.querySelectorAll('.dispense-check:checked');
        if (checked.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No Bottles Selected', text: 'Please check the bottles used for this feed.' });
            return false;
        }
        return true;
    }

    function updateDispenseTotal(total_vol) {
        const checked = document.querySelectorAll('.dispense-check:checked');
        let total = 0;
        checked.forEach(cb => {
            total += parseFloat(cb.getAttribute('data-vol')) || 0;
        });
        document.getElementById('dispenseTotalSelected').textContent = total + ' / ' + total_vol + ' ml';
    }

    function closeDispenseModal() {
        document.getElementById('dispenseModal').style.display = 'none';
    }

    function toggleDispense(checkbox) {
        // Find the parent container
        const item = checkbox.closest('.dispense-item');
        // Find the time span within this container
        const timeSpan = item.querySelector('.dispense-time');
        
        if (checkbox.checked) {
            // Add highlight class
            item.classList.add('checked');
            
            // Get current time
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            // Update time text
            timeSpan.textContent = timeString;
            // Make the status text bold and green for better visibility
            timeSpan.style.color = 'green';
            timeSpan.style.fontWeight = 'bold';
        } else {
            // Remove highlight class
            item.classList.remove('checked');
            
            // Reset time text
            timeSpan.textContent = '--:--';
            // Reset styles
            timeSpan.style.color = '';
            timeSpan.style.fontWeight = '';
        }

        // Recalculate totals after toggle
        recalcDispenseTotals();
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