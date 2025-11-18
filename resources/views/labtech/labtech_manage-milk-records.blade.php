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
                            {{-- This div contains the vertically stacked elements, fixed in CSS --}}
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
                        <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                        <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                        <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Placeholder for Modal and Scripts --}}
{{-- <div id="editMilkModal" class="modal">...</div> --}}

{{-- ===========================
      ADD RECORD MODAL
=========================== --}}
<div id="addRecordModal" class="modal-overlay">
    <div class="modal-box">

        <div class="modal-header">
            <h3>Add Milk Record</h3>
            <button class="modal-close">&times;</button>
        </div>

        <form id="addRecordForm">

            <div class="modal-body">

                <!-- Donor ID (Dropdown) -->
                <div class="modal-section mb-3">
                    <label class="form-label"><strong>Donor ID</strong> <span class="text-danger">*</span></label>
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
                <div class="modal-section mb-3">
                    <label class="form-label"><strong>Milk Volume (ml)</strong> <span class="text-danger">*</span></label>
                    <input type="number" name="milk_volume" class="form-control" placeholder="Enter volume in ml" required min="1">
                </div>

                <!-- Milk Expiry Date -->
                <div class="modal-section mb-3">
                    <label class="form-label"><strong>Expiry Date</strong> <span class="text-danger">*</span></label>
                    <input type="date" name="expiry_date" class="form-control" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="save-btn">Submit</button>
                <button type="button" class="modal-close cancel-btn">Cancel</button>
            </div>

        </form>

    </div>
</div>


<!-- ===========================
      POPUP SCRIPT
=========================== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    const addBtn = document.getElementById("openAddRecord");
    const modal = document.getElementById("addRecordModal");
    const closeBtns = document.querySelectorAll(".modal-close");

    // Open popup
    addBtn.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    // Close popup
    closeBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    });

});
</script>

@endsection