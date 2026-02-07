@extends('layouts.doctor')

@section('title', 'Milk Request Records')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/doctor_milk-request.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* Filter Bar Styles */
    .filter-bar {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end; /* Align buttons with inputs */
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }
    .filter-input {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
        color: #334155;
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
    
    /* Pagination Styles Override */
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    /* Laravel Pagination Default Styles Fix */
    .pagination { display: flex; gap: 5px; list-style: none; padding: 0; }
    .page-item .page-link {
        padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; 
        color: #64748b; text-decoration: none; font-size: 14px; background: white;
    }
    .page-item.active .page-link {
        background: #1A5F7A; color: white; border-color: #1A5F7A;
    }
    .page-item.disabled .page-link { opacity: 0.6; pointer-events: none; }
  </style>

  <div class="container">

    <div class="main-content">
      <div class="page-header">
        <h1>Milk Request Records</h1>
        <p>Manage and track all milk processing requests</p>
      </div>

      <form method="GET" action="{{ route('doctor.doctor_milk-request') }}" class="filter-bar">
          
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
          <a href="{{ route('doctor.doctor_milk-request') }}" class="btn-reset"><i class="fas fa-undo"></i> Reset</a>
      </form>

      <div class="card">
        <div class="card-header">
          <div class="header-left">
            <h3>Request List</h3>
          </div>
        </div>

        <div class="table-responsive">
            <table class="records-table">
              <thead>
                <tr>
                    <th data-sort="name">Patient Name</th>
                    <th data-sort="nicu">NICU</th>
                    <th data-sort="created_at">Date Requested</th>
                    <th data-sort="feeding_time">Feeding Time</th>
                    <th data-sort="status">Status</th>
                    <th data-sort="volume">Volume (ml)</th>
                    <th>Actions</th>
                </tr>
              </thead>
              <tbody> 
                @forelse($requests as $req)
                <tr>
                    <td>
                        <strong>{{ $req->parent->formattedID ?? 'N/A' }}</strong><br>
                        {{ $req->parent->pr_BabyName ?? 'Unknown Baby' }}
                    </td>

                    <td>{{ $req->parent->pr_NICU ?? '-' }}</td>

                    <td>{{ $req->created_at->format('M d, Y') }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($req->feeding_start_date)->format('M d, Y') }}
                        •
                        {{ \Carbon\Carbon::parse($req->feeding_start_time)->format('h:i A') }}
                    </td>

                    <td>
                        <span class="status {{ strtolower($req->status ?? 'waiting') }}">
                            {{ $req->status ?? 'Waiting' }}
                        </span>
                    </td>

                    <td style="font-weight: 600; color: #334155;">
                        {{ $req->total_daily_volume }} ml
                    </td>

                    <td class="actions">
                        <button class="btn-view" title="View Details"
                          onclick="openMilkRequestModal({
                            patientId: '{{ $req->parent->formattedID ?? 'N/A' }}',
                            patientName: '{{ $req->parent->pr_BabyName ?? '' }}',
                            patientDOB: '{{ $req->parent->pr_BabyDOB ?? '' }}',
                            patientAge: '{{ $req->current_baby_age ?? 'N/A' }}',
                            nicu: '{{ $req->parent->pr_NICU ?? '' }}',
                            dateRequested: '{{ $req->created_at->format('M d, Y') }}',
                            dateTimeToGive: '{{ \Carbon\Carbon::parse($req->feeding_start_date)->format('M d, Y') }} • {{ \Carbon\Carbon::parse($req->feeding_start_time)->format('h:i A') }}',
                            gestationAge: '{{ $req->gestational_age ?? 'N/A' }} weeks',
                            dripTotal: '{{ $req->drip_total ?? 0 }} ml',
                            oralTotal: '{{ $req->oral_total ?? 0 }} ml',
                            oralPerFeed: '{{ $req->oral_per_feed ?? 0 }} ml',
                            feedingMethod: '{{ $req->feeding_tube ?? 'N/A' }}',
                            oralMethod: '{{ $req->oral_feeding ?? 'N/A' }}',
                            status: '{{ $req->status ?? 'Waiting' }}',
                            requestedVolume: '{{ $req->total_daily_volume }} ml',
                            doctorName: '{{ $req->doctor->dr_Name ?? 'N/A' }}',
                            allergyInfo: '{{ $req->parent->pr_Allergy ?? 'None' }}',
                            currentWeight: '{{ $req->current_weight }} kg',
                            birthWeight: '{{ $req->parent->pr_BabyBirthWeight ?? 'N/A' }} kg'
                          })">
                          <i class="fas fa-eye"></i>
                        </button>

                        <button class="btn-delete" 
                                title="Delete"
                                onclick="confirmDelete({{ $req->request_ID }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px; color: #94a3b8;">
                        <i class="fas fa-search fa-2x" style="margin-bottom:10px; opacity:0.5;"></i><br>
                        No requests found matching your filters.
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

  <div id="milkRequestModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
            <h2>Milk Request Details</h2>
            <button class="modal-close-btn" onclick="closeMilkRequestModal()">Close</button>
        </div>

      <div class="modal-body">
        <h3>Patient Information</h3>
        <p><strong>Patient ID:</strong> <span id="modal-patient-id"></span></p>
        <p><strong>Patient Name:</strong> <span id="modal-patient-name"></span></p>
        <p><strong>Patient Age:</strong> <span id="modal-patient-age"></span></p>
        <p><strong>Patient DOB:</strong> <span id="modal-patient-dob"></span></p>
        <p><strong>NICU Ward:</strong> <span id="modal-nicu"></span></p>
        <p><strong>Birth Weight:</strong> <span id="modal-birth-weight"></span></p>
        <p><strong>Current Weight:</strong> <span id="modal-current-weight"></span></p>

        <hr>

        <h3>Request Information</h3>
        <p><strong>Date Requested:</strong> <span id="modal-date-requested"></span></p>
        <p><strong>Scheduled Feeding Time:</strong> <span id="modal-datetime-give"></span></p>
        <p><strong>Requested Volume:</strong> <span id="modal-volume"></span></p>
        <p><strong>Request Status:</strong> <span id="modal-status"></span></p>
        <p><strong>Gestational Age:</strong> <span id="modal-gestation-age"></span></p>
        
        <p><strong>Feeding Method (Tube):</strong> <span id="modal-feeding-method"></span></p>
        <p><strong>Oral Method:</strong> <span id="modal-oral-method"></span></p>

        <p><strong>Drip Total:</strong> <span id="modal-drip-total"></span></p>
        <p><strong>Oral Total:</strong> <span id="modal-oral-total"></span></p>
        <p><strong>Oral Per Feed:</strong> <span id="modal-oral-per-feed"></span></p>

        <hr>

        <h3>Medical Information</h3>
        <p><strong>Attending Doctor:</strong> <span id="modal-doctor-name"></span></p>
        <p><strong>Allergy Information:</strong> <span id="modal-allergy"></span></p>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // --- Modal Logic ---
    function openMilkRequestModal(data) {
        document.getElementById("modal-patient-id").textContent = data.patientId;
        document.getElementById("modal-patient-name").textContent = data.patientName;
        document.getElementById("modal-patient-age").textContent = data.patientAge;
        document.getElementById("modal-patient-dob").textContent = data.patientDOB;
        document.getElementById("modal-nicu").textContent = data.nicu;
        document.getElementById("modal-birth-weight").textContent = data.birthWeight;
        document.getElementById("modal-current-weight").textContent = data.currentWeight;
        
        document.getElementById("modal-date-requested").textContent = data.dateRequested;
        document.getElementById("modal-datetime-give").textContent = data.dateTimeToGive;
        document.getElementById("modal-volume").textContent = data.requestedVolume;
        document.getElementById("modal-status").textContent = data.status;
        document.getElementById("modal-gestation-age").textContent = data.gestationAge;
        
        document.getElementById("modal-feeding-method").textContent = data.feedingMethod;
        document.getElementById("modal-oral-method").textContent = data.oralMethod;

        document.getElementById("modal-drip-total").textContent = data.dripTotal;
        document.getElementById("modal-oral-total").textContent = data.oralTotal;
        document.getElementById("modal-oral-per-feed").textContent = data.oralPerFeed;
        
        document.getElementById("modal-doctor-name").textContent = data.doctorName;
        document.getElementById("modal-allergy").textContent = data.allergyInfo;

        document.getElementById("milkRequestModal").style.display = "flex";
    }

    function closeMilkRequestModal() {
        document.getElementById("milkRequestModal").style.display = "none";
    }

    window.onclick = function(e) {
      let modal = document.getElementById("milkRequestModal");
      if (e.target === modal) {
        modal.style.display = "none";
      }
    }

    // --- Delete Logic ---
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This milk request will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('/doctor/milk-request') }}/" + id + "/delete", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Deleted!", "Milk request has been deleted.", "success");
                        setTimeout(() => location.reload(), 1500);
                    }
                });
            }
        });
    }
  </script>

@endsection