@extends('layouts.labtech')

@section('title', 'Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>Milk Records Management</h1>
            <p>Milk Processing and Records</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Milk Processing and Records</h2>
                <div class="actions-header">
                    <button class="btn btn-search"><i class="fas fa-search"></i> Search</button>
                    <button class="btn btn-filter"><i class="fas fa-filter"></i> Filter</button>

                    <!-- OPEN MODAL BTN -->
                    <button class="btn btn-add-records" id="openAddRecord">
                        <i class="fas fa-plus"></i> Add Milk
                    </button>

                </div>
            </div>

            <div class="records-list">
                <div class="record-header">
                    <span>MILK DONOR</span>
                    <span>CLINICAL STATUS</span>
                    <span>VOLUME</span>
                    <span>EXPIRATION DATE</span>
                    <span>SHARIAH APPROVAL</span> <!-- Changed! -->
                    <span>ACTIONS</span>
                </div>

                @forelse($milks as $milk)
                    <div class="record-item">
                        <div class="milk-donor-info">
                            <div class="milk-icon-wrapper">
                                <i class="fas fa-bottle-droplet milk-icon"></i>
                            </div>
                            <div>
                                <span class="milk-id">{{ $milk->formatted_id }}</span>
                                <span class="donor-name">{{ $milk->donor?->dn_FullName ?? 'Unknown Donor' }}</span>
                            </div>
                        </div>

                        <div class="clinical-status">
                            <span class="status-tag status-{{ strtolower($milk->milk_Status ?? 'pending') }}">
                                {{ ucfirst($milk->milk_Status ?? 'Not Yet Started') }}
                            </span>
                        </div>

                        <div class="volume-data">{{ $milk->milk_volume }} mL</div>

                        <div class="expiry-date">
                            {{ $milk->milk_expiryDate ? \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') : '-' }}
                        </div>

                        <!-- SHARIAH APPROVAL COLUMN -->
                        <div class="shariah-status">
                            @php
                                $approval = $milk->milk_shariahApproval;
                            @endphp
                            <span class="status-tag
                                {{ is_null($approval) ? 'status-pending' :
                                ($approval ? 'status-approved' : 'status-rejected') }}">
                                {{ is_null($approval) ? 'Not Yet Reviewed' :
                                ($approval ? 'Approved' : 'Rejected') }}
                            </span>
                        </div>

                        <div class="actions">
                            <button class="btn-view" title="View"
                                onclick="openViewMilkModal({
                                    milkId: '{{ $milk->formatted_id }}',
                                    donorName: '{{ $milk->donor?->dn_FullName ?? 'N/A' }}',
                                    status: '{{ ucfirst($milk->milk_Status ?? 'Not Yet Started') }}',
                                    volume: '{{ $milk->milk_volume }} mL',
                                    expiry: '{{ $milk->milk_expiryDate ? \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') : 'Not set' }}',
                                    shariah: '{{ is_null($milk->milk_shariahApproval) ? 'Not Yet Reviewed' : ($milk->milk_shariahApproval ? 'Approved' : 'Rejected') }}'
                                })">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </div>
                    </div>
                @empty
                    <div class="record-item text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No milk records yet. Add one to begin!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ===========================
      ADD RECORD MODAL
=========================== --}}
<div id="addRecordModal" class="modal-overlay">
    <div class="modal-content">
        <h2>Add Milk Record</h2>

        <div class="modal-body">
            <form id="addRecordForm" method="POST" action="{{ route('labtech.labtech_store-manage-milk-records') }}">
                @csrf

                <!-- Donor ID -->
                <div class="modal-section">
                    <label>
                        <i class="fas fa-user"></i> Donor ID 
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="dn_ID" required>
                        <option value="" selected disabled>-- Select Donor ID --</option>
                        @foreach($donors as $donor)
                            <option value="{{ $donor->dn_ID }}">
                                {{ $donor->formatted_id }} - {{ $donor->dn_FullName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Milk Volume -->
                <div class="modal-section">
                    <label>
                        <i class="fas fa-flask"></i> Milk Volume (ml) 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="milk_volume" class="form-control" 
                           placeholder="Enter volume in ml" required min="1" step="0.1">
                </div>

                <!-- Expiry Date -->
                <div class="modal-section">
                    <label>
                        <i class="fas fa-calendar-alt"></i> Expiry Date 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="milk_expiryDate" class="form-control" required>
                </div>

                <!-- Clinical Status -->
                <!-- <div class="modal-section">
                    <label>
                        <i class="fas fa-heartbeat"></i> Clinical Status 
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="clinical_status" required>
                        <option value="" selected disabled>-- Select Clinical Status --</option>
                        <option value="Screening">Screening</option>
                        <option value="Labelling">Labelling</option>
                        <option value="Storaging">Storaging</option>
                        <option value="Dispatching">Dispatching</option>
                    </select>
                </div> -->

                <button type="submit" class="modal-close-btn">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>


{{-- ===================== VIEW MILK RECORD MODAL ===================== --}}
<div id="viewMilkModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
                <h2>Milk Record Details</h2>
                <button class="modal-close-btn" onclick="closeViewMilkModal()">Close</button>
            </div>

        <div class="modal-body">
            <p><strong>Milk ID:</strong> <span id="view-milk-id"></span></p>
            <p><strong>Donor Name:</strong> <span id="view-donor-name"></span></p>
            
            <hr>
            
            <h3>Processing Information</h3>
            <p><strong>Clinical Status:</strong> <span id="view-status"></span></p>
            <p><strong>Volume:</strong> <span id="view-volume"></span></p>
            <p><strong>Expiry Date:</strong> <span id="view-expiry"></span></p>
            
            <hr>
            
            <h3>Quality Control</h3>
            <p><strong>Shariah Approval:</strong> 
                <span id="view-shariah"></span> <!-- Changed from eligibility -->
            </p>
        </div>
    </div>
</div>


{{-- ===========================
      POPUP SCRIPT
=========================== --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============== MODAL OPEN / CLOSE ==============
document.addEventListener("DOMContentLoaded", () => {
    const openAdd   = document.getElementById("openAddRecord");
    const addModal  = document.getElementById("addRecordModal");
    const viewModal = document.getElementById("viewMilkModal");

    openAdd.addEventListener("click", () => {
        addModal.style.display = "flex";
    });

    window.addEventListener("click", (e) => {
        if (e.target === addModal) addModal.style.display = "none";
        if (e.target === viewModal) viewModal.style.display = "none";
    });
});

function openViewMilkModal(data) {
    document.getElementById("view-milk-id").textContent       = data.milkId;
    document.getElementById("view-donor-name").textContent    = data.donorName;
    document.getElementById("view-status").textContent        = data.status;
    document.getElementById("view-volume").text

Content = data.volume;
    document.getElementById("view-expiry").textContent        = data.expiry;
    document.getElementById("view-shariahy").textContent   = data.eligibility;
    document.getElementById("viewMilkModal").style.display = "flex";
}

function closeViewMilkModal() {
    document.getElementById("viewMilkModal").style.display = "none";
}

// ============== AJAX FORM SUBMISSION (FIXED: ERROR SHOWS IN FRONT!) ==============
document.getElementById('addRecordForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err });
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Milk Received!',
            html: '<strong>Record saved successfully!</strong><br><small>Ready to begin process</small>',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Great!',
            confirmButtonColor: '#28a745'
        });

        document.getElementById('addRecordModal').style.display = 'none';
        this.reset();
        setTimeout(() => location.reload(), 2500);
    })
    .catch(error => {
        // THIS IS THE FIX: Close modal BEFORE showing error
        document.getElementById('addRecordModal').style.display = 'none';

        let msg = 'Please correct the errors and try again.';
        if (error.errors) {
            msg = Object.values(error.errors).flat().join('<br>');
        }

        // Now error appears clearly in front
        Swal.fire({
            icon: 'error',
            title: 'Invalid Data',
            html: msg,
            confirmButtonColor: '#d33',
            width: '500px',
            allowOutsideClick: false
        });
    });
});
</script>

@endsection