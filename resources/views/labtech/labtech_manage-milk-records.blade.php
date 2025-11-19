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
                    <span>ELIGIBILITY</span>
                    <span>ACTIONS</span>
                </div>

                @foreach ([
                    ['id' => 1, 'donor' => 'Sarah Ahmad Binti Fauzi', 'status' => 'Labelling', 'volume' => '5mL', 'expiry' => 'May 15, 2024', 'eligibility' => 'Passed'],
                    ['id' => 2, 'donor' => 'Maryam Binti Othman', 'status' => 'Storaging', 'volume' => '5mL', 'expiry' => 'May 14, 2024', 'eligibility' => 'Passed'],
                    ['id' => 3, 'donor' => 'Fatimah Az-zahra Binti Mohd Nor', 'status' => 'Screening', 'volume' => '5mL', 'expiry' => 'Jun 23, 2024', 'eligibility' => 'Passed'],
                    ['id' => 4, 'donor' => 'Aishah Radhi Binti Izzuddin', 'status' => 'Labelling', 'volume' => '5mL', 'expiry' => 'May 15, 2024', 'eligibility' => 'Passed'],
                    ['id' => 5, 'donor' => 'Nor Atiqah Humaira Binti Ishak', 'status' => 'Dispatching', 'volume' => '5mL', 'expiry' => 'Apr 28, 2024', 'eligibility' => 'Passed'],
                ] as $record)
                <div class="record-item">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper">
                            <i class="fas fa-bottle-droplet milk-icon"></i>
                        </div>
                        <div>
                            <span class="milk-id">Milk ID #{{ $record['id'] }}</span>
                            <span class="donor-name">{{ $record['donor'] }}</span>
                        </div>
                    </div>

                    <div class="clinical-status">
                        <span class="status-tag status-{{ strtolower($record['status']) }}">{{ $record['status'] }}</span>
                    </div>

                    <div class="volume-data">{{ $record['volume'] }}</div>
                    <div class="expiry-date">{{ $record['expiry'] }}</div>

                    <div class="eligibility-status">
                        <span class="eligibility-tag eligibility-{{ strtolower($record['eligibility']) }}">{{ $record['eligibility'] }}</span>
                    </div>

                    <div class="actions">
                        <button class="btn-view" title="View"
                            onclick="openViewMilkModal({
                                milkId: 'Milk ID #{{ $record['id'] }}',
                                donorName: '{{ $record['donor'] }}',
                                status: '{{ $record['status'] }}',
                                volume: '{{ $record['volume'] }}',
                                expiry: '{{ $record['expiry'] }}',
                                eligibility: '{{ $record['eligibility'] }}'
                            })">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                        <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
                @endforeach
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
            <form id="addRecordForm">
                
                <!-- Donor ID -->
                <div class="modal-section">
                    <label>
                        <i class="fas fa-user"></i> Donor ID 
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="donor_id" required>
                        <option value="" selected disabled>-- Select Donor ID --</option>
                        <option value="D001">D001 - Sarah Ahmad</option>
                        <option value="D002">D002 - Maryam Othman</option>
                        <option value="D003">D003 - Fatimah Az-zahra</option>
                        <option value="D004">D004 - Aishah Radhi</option>
                        <option value="D005">D005 - Nor Atiqah</option>
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
                    <input type="date" name="expiry_date" class="form-control" required>
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
            <p><strong>Eligibility:</strong> <span id="view-eligibility"></span></p>
        </div>
    </div>
</div>


{{-- ===========================
      POPUP SCRIPT
=========================== --}}
<script>
// Open View Modal
function openViewMilkModal(data) {
    document.getElementById("view-milk-id").textContent = data.milkId;
    document.getElementById("view-donor-name").textContent = data.donorName;
    document.getElementById("view-status").textContent = data.status;
    document.getElementById("view-volume").textContent = data.volume;
    document.getElementById("view-expiry").textContent = data.expiry;
    document.getElementById("view-eligibility").textContent = data.eligibility;
    
    document.getElementById("viewMilkModal").style.display = "flex";
}

// Close View Modal
function closeViewMilkModal() {
    document.getElementById("viewMilkModal").style.display = "none";
}

// Add Record Modal Controls
document.addEventListener("DOMContentLoaded", () => {
    const openAdd = document.getElementById("openAddRecord");
    const addModal = document.getElementById("addRecordModal");

    // Open Add Record modal
    openAdd.addEventListener("click", () => {
        addModal.style.display = "flex";
    });

    // Close modal when clicking outside
    window.onclick = function(e) {
        if (e.target === addModal) {
            addModal.style.display = "none";
        }
        if (e.target === document.getElementById("viewMilkModal")) {
            closeViewMilkModal();
        }
    }

    // Form submission
    document.getElementById("addRecordForm").addEventListener("submit", (e) => {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        console.log("Form submitted:", data);
        
        // TODO: Send data to server
        // After successful submission:
        alert("Milk record added successfully!");
        addModal.style.display = "none";
        e.target.reset();
    });
});
</script>

@endsection