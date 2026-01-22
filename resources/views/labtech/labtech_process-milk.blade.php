@extends('layouts.labtech')

@section('title', 'Milk Processing')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_process-milk.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
  <div class="main-content">

    <div class="page-header">
      <h1>Process Record - {{ $milk->formatted_id }}</h1>
      <a href="{{ route('labtech.labtech_manage-milk-records') }}" style="text-decoration: none;"><button class="btn-back"><i class="fas fa-arrow-left"></i> Back</button></a>
    </div>

    {{-- === Tab Navigation (5 Stages) === --}}
    <div class="stage-tabs">
      <button class="stage-tab active" data-stage="stage1">1. Pre-Pasteurization</button>
      <button class="stage-tab" data-stage="stage2">2. Thawing</button>
      <button class="stage-tab" data-stage="stage3">3. Pasteurization</button>
      <button class="stage-tab" data-stage="stage4">4. Microbiology</button>
      <button class="stage-tab" data-stage="stage5">5. Post-Pasteurization</button>
    </div>

    {{-- ================================================================================== --}}
    {{-- STAGE 1: PRE-PASTEURIZATION (LABELLING) --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content active" id="stage1-content"
         data-start="{{ $milk->milk_stage1StartDate ?? '' }}"
         data-starttime="{{ $milk->milk_stage1StartTime ?? '' }}"
         data-end="{{ $milk->milk_stage1EndDate ?? '' }}"
         data-endtime="{{ $milk->milk_stage1EndTime ?? '' }}">
      
      <h2>Stage 1: Pre-Pasteurization</h2>
      <h3>Labelling & Bottling</h3>
      <img src="{{ asset('images/lab_pre_pasteurization.png') }}" alt="Labelling" style="width: 270px; height: auto;">

      <form method="POST" action="#">
        @csrf
        <div class="form-grid">
          <div>
            <label>Date Processed</label>
            <input type="date" name="milk_stage1StartDate" value="{{ $milk->milk_stage1StartDate ?? date('Y-m-d') }}" required>
          </div>
          <div>
            <label>Time Processed</label>
            <input type="time" name="milk_stage1StartTime" value="{{ $milk->milk_stage1StartTime ?? date('H:i') }}" required>
          </div>
        </div>

        {{-- BOTTLE TABLE --}}
        <div class="data-table-container" style="display: block; margin-top:20px;">
            <div class="table-header-info">
                <span class="animals-selected">Bottle Labelling Details</span>
                <button type="button" class="btn-add-row" onclick="addBottleRow()">
                    <i class="fas fa-plus"></i> Add Bottle
                </button>
            </div>
            <table class="data-table" id="bottle-table">
                <thead>
                    <tr>
                        <th>Bottle #</th>
                        <th>Volume (ml)</th>
                        <th>Barcode ID (Preview)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Load existing bottles or Default Row --}}
                    @php
                        $bottles = $milk->milk_stage1Result ? json_decode($milk->milk_stage1Result, true) : [];
                    @endphp
                    @if(count($bottles) > 0)
                        @foreach($bottles as $idx => $b)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td contenteditable="true" class="volume-cell">{{ $b['volume'] }}</td>
                            <td>{{ $milk->formatted_id }}-B{{ $idx + 1 }}</td>
                            <td class="actions"><button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>1</td>
                            <td contenteditable="true" class="volume-cell">0</td>
                            <td>{{ $milk->formatted_id }}-B1</td>
                            <td class="actions"><button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8fafc; font-weight:bold;">
                        <td colspan="1" style="text-align:right;">Total Volume:</td>
                        <td id="total-volume-display">0 ml</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="stage-footer">
            <button type="button" class="btn-submit-stage" onclick="saveStage1Data()">
                <i class="fas fa-save"></i> Save Labelling Data
            </button>
        </div>
      </form>

      <div class="button-row">
        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="btn-back-nav"><i class="fas fa-arrow-left"></i> Back</a>
        <button class="btn-next" onclick="switchStage('stage2')">Next Stage <i class="fas fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ================================================================================== --}}
    {{-- STAGE 2: THAWING (UPDATED - SIMPLE TRACKING) --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage2-content">
        <h2>Stage 2: Thawing</h2>
        <h3>Thawing Confirmation</h3>
        <img src="{{ asset('images/lab_thawing.png') }}" alt="Thawing" style="width: 150px; height: auto; margin-bottom: 20px;">

        <div style="margin-bottom: 15px; text-align: center;">
            <p class="text-muted">Please mark the bottles that have been successfully thawed.</p>
        </div>

        {{-- THAWING TABLE --}}
        <div class="data-table-container" style="display: block; margin-top:0;">
            <table class="data-table" id="thawing-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Bottle ID</th>
                        <th style="width: 30%;">Volume</th>
                        <th style="width: 40%;">Thawed Successfully?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="no-bottles-msg">
                        <td colspan="3" style="padding: 20px; color: #64748b;">
                            No bottles found. Please add bottles in Stage 1 first.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="stage-footer">
            <button type="button" class="btn-submit-stage" onclick="saveStage2Data()">
                <i class="fas fa-save"></i> Save Thawing Status
            </button>
        </div>

        <div class="button-row">
            <button class="btn-back-stage" onclick="switchStage('stage1')"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn-next" onclick="switchStage('stage3')">Next Stage <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

   {{-- ================================================================================== --}}
    {{-- STAGE 3: PASTEURIZATION (UPDATED) --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage3-content"
        data-start="{{ $milk->milk_stage3StartDate ?? '' }}"
        data-end="{{ $milk->milk_stage3EndDate ?? '' }}">
    <h2>Stage 3: Pasteurization</h2>
    <h3>Heat Treatment & Re-Bottling</h3>
    <img src="{{ asset('images/lab_pasteurization.png') }}" alt="Pasteurization" style="width: 270px; height: auto;">

    <form method="POST" action="#">
        @csrf

        {{-- NEW: PASTEURIZED BOTTLES TABLE --}}
        <div class="data-table-container" style="display: block;">
            <div class="table-header-info">
                <span class="animals-selected">Pasteurized Bottles (Standard 30ml)</span>
                <button type="button" class="btn-add-row" onclick="addPasteurBottleRow()">
                    <i class="fas fa-plus"></i> Add 30ml Bottle
                </button>
            </div>
            <table class="data-table" id="pasteur-table">
                <thead>
                    <tr>
                        <th>Bottle ID</th>
                        <th>Volume (Fixed)</th>
                        <th>Pasteurization Date</th>
                        <th>Expiry Date (6 Months)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Rows generated by JS --}}
                    <tr id="pasteur-empty-msg">
                        <td colspan="5" class="text-muted" style="padding:20px;">No pasteurized bottles added yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="stage-footer">
        @if(!$milk->milk_stage3StartDate)
            {{-- You might want to remove simulateSubmit(3) if you want them to save the table instead --}}
            <button type="button" class="btn-submit-stage" onclick="saveStage3Data()">
                <i class="fas fa-save"></i> Save Pasteurization Data
            </button>
        @else
            <div class="time-status active">IN PROGRESS / COMPLETED</div>
        @endif
        </div>
    </form>

    <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('stage2')"><i class="fas fa-arrow-left"></i> Previous</button>
        <button class="btn-next" onclick="switchStage('stage4')">Next Stage <i class="fas fa-arrow-right"></i></button>
    </div>
    </div>

    {{-- ================================================================================== --}}
    {{-- STAGE 4: MICROBIOLOGY TEST (UPDATED) --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage4-content"
        data-start="{{ $milk->milk_stage4StartDate ?? '' }}"
        data-end="{{ $milk->milk_stage4EndDate ?? '' }}">
    
    <h2>Stage 4: Microbiology Test</h2>
    <h3>Quality Assurance & Bacterial Count</h3>
    <img src="{{ asset('images/lab_microbiology_test.png') }}" alt="Microbiology" style="width: 270px; height: auto;">

    <form method="POST" action="#">
        @csrf
        
        {{-- HIDDEN INPUTS (Optional, if backend needs them) --}}
        <input type="hidden" name="milk_stage4StartDate" value="{{ date('Y-m-d') }}">

        {{-- MICROBIOLOGY TABLE --}}
        <div class="data-table-container" style="display: block; margin-top:20px;">
            <div class="table-header-info">
                <span class="animals-selected">Bacteria Colony Forming Units (CFU/ml)</span>
                <small class="text-muted" style="margin-left: 10px; font-style:italic;">* Enter counts to calculate results</small>
            </div>
            <table class="data-table" id="micro-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Bottle ID</th>
                        <th style="width: 20%;">Total Viable<br><small>(Limit: < 100,000)</small></th>
                        <th style="width: 20%;">Enterobacteriaceae<br><small>(Limit: < 10,000)</small></th>
                        <th style="width: 20%;">Staphylococcus<br><small>(Limit: < 10,000)</small></th>
                        <th style="width: 25%;">Result Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="micro-empty-msg">
                        <td colspan="5" class="text-muted" style="padding:20px;">
                            No pasteurized bottles found. Complete Stage 3 first.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="stage-footer">
            <button type="button" class="btn-submit-stage" onclick="saveStage4Data()">
                <i class="fas fa-save"></i> Save Test Results
            </button>
        </div>
    </form>

    <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('stage3')"><i class="fas fa-arrow-left"></i> Previous</button>
        <button class="btn-next" onclick="switchStage('stage5')">Next Stage <i class="fas fa-arrow-right"></i></button>
    </div>
    </div>

    {{-- ================================================================================== --}}
    {{-- STAGE 5: POST-PASTEURIZATION (UPDATED) --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage5-content"
        data-start="{{ $milk->milk_stage5StartDate ?? '' }}"
        data-end="{{ $milk->milk_stage5EndDate ?? '' }}">
    
    <h2>Stage 5: Post-Pasteurization</h2>
    <h3>Final Storage & Approval</h3>
    <img src="{{ asset('images/lab_post_pasteurization.png') }}" alt="Storage" style="width: 270px; height: auto; margin-bottom: 20px;">

    <form method="POST" action="#">
        @csrf
        
        {{-- 1. DRAWER ID INPUT --}}
        <div style="max-width: 300px; margin: 0 auto 30px; text-align: center;">
            <label style="display:block; font-weight:700; color:#1A5F7A; margin-bottom:8px; text-transform:uppercase; letter-spacing:1px;">
                <i class="fas fa-box-archive"></i> Storage Drawer ID
            </label>
            <input type="text" id="drawer-id" name="drawer_id" class="form-control" 
                placeholder="e.g., DRW-A01" 
                style="width:100%; padding:12px; border:2px solid #e2e8f0; border-radius:12px; text-align:center; font-weight:bold; font-size:16px; outline:none; transition:0.3s;">
        </div>

        {{-- 2. FILTERED BOTTLE TABLE --}}
        <div class="data-table-container" style="display: block;">
            <div class="table-header-info">
                <span class="animals-selected">Approved Bottles for Storage</span>
                <small class="text-success" style="margin-left: 10px; font-weight:bold; font-style:italic;">
                    <i class="fas fa-check-circle"></i> Only non-contaminated bottles listed
                </small>
            </div>
            <table class="data-table" id="storage-table">
                <thead>
                    <tr>
                        <th>Bottle ID</th>
                        <th>Expiry Date <br><small>(From Stage 3)</small></th>
                        <th>Quality Status <br><small>(From Stage 4)</small></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="storage-empty-msg">
                        <td colspan="3" class="text-muted" style="padding:20px;">
                            No approved bottles found yet. Please complete Microbiology test (Stage 4).
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="stage-footer">
        @if(!$milk->milk_stage5StartDate)
            <button type="button" class="btn-submit-stage" onclick="saveStage5Data()">
                <i class="fas fa-check-circle"></i> Complete Process & Save
            </button>
        @else
            <div class="time-status completed">PROCESS COMPLETED</div>
        @endif
        </div>
    </form>

    <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('stage4')"><i class="fas fa-arrow-left"></i> Previous</button>
        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="btn-submit-stage" style="text-decoration:none; display:inline-block; width:auto; background: #64748b;">Done</a>
    </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // 0. GLOBAL VARIABLES & STATE
    // ==========================================
    const milkIdFormatted = "{{ $milk->formatted_id }}";
    
    // Global state for Stage 2 timers
    // Key = Bottle ID, Value = { startTime, endTime, status, volume }
    let bottleState = {}; 

    // ==========================================
    // 1. NAVIGATION & TAB LOGIC
    // ==========================================
    function switchStage(stageId) {
        // Remove active class from all contents and tabs
        document.querySelectorAll('.stage-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.stage-tab').forEach(el => el.classList.remove('active'));
        
        // Add active class to target
        document.getElementById(stageId + '-content').classList.add('active');
        document.querySelector(`[data-stage="${stageId}"]`).classList.add('active');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // [NEW LOGIC] When entering Stage 2, load the bottles from Stage 1
        if(stageId === 'stage2') {
            loadStage2Bottles();
        }
    }

    // Attach click events to tabs
    document.querySelectorAll('.stage-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            switchStage(this.dataset.stage);
        });
    });

    // ==========================================
    // 2. STAGE 1: BOTTLING LOGIC
    // ==========================================
    function addBottleRow() {
        const tbody = document.querySelector('#bottle-table tbody');
        const count = tbody.rows.length + 1;
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${count}</td>
            <td contenteditable="true" class="volume-cell">0</td>
            <td>${milkIdFormatted}-B${count}</td>
            <td class="actions"><button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>
        `;
        calculateTotalVolume();
    }

    function deleteRow(btn) {
        const row = btn.closest('tr');
        const table = row.closest('table');
        row.remove();
        
        // Renumber logic depends on table type
        if(table.id === 'bottle-table') {
            renumberBottles();
            calculateTotalVolume();
        }
    }

    function renumberBottles() {
        const rows = document.querySelectorAll('#bottle-table tbody tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
            row.cells[2].innerText = `${milkIdFormatted}-B${index + 1}`;
        });
    }

    // Monitor volume changes in Stage 1
    document.querySelector('#bottle-table').addEventListener('input', function(e) {
        if(e.target.classList.contains('volume-cell')) {
            calculateTotalVolume();
        }
    });

    function calculateTotalVolume() {
        let total = 0;
        document.querySelectorAll('.volume-cell').forEach(cell => {
            const val = parseFloat(cell.innerText) || 0;
            total += val;
        });
        document.getElementById('total-volume-display').innerText = total + ' ml';
    }

    // ==========================================
    // 3. STAGE 2: THAWING LOGIC (UPDATED)
    // ==========================================
    
    // Global state to remember which bottles are checked
    let thawingState = {}; 

    function loadStage2Bottles() {
        const stage1Rows = document.querySelectorAll('#bottle-table tbody tr');
        const stage2Body = document.querySelector('#thawing-table tbody');
        
        // Clear existing rows
        stage2Body.innerHTML = '';

        if(stage1Rows.length === 0) {
            stage2Body.innerHTML = `<tr><td colspan="3" class="text-muted" style="padding:20px;">No bottles found in Stage 1.</td></tr>`;
            return;
        }

        stage1Rows.forEach(row => {
            const bottleId = row.cells[2].innerText.trim(); // Get ID from Stage 1
            const volume = row.cells[1].innerText.trim();   // Get Volume from Stage 1

            // Check if previously marked as thawed
            const isChecked = thawingState[bottleId] === true ? 'checked' : '';
            const statusText = thawingState[bottleId] === true ? '<span class="text-success fw-bold">Yes</span>' : '<span class="text-muted">No</span>';

            // Create Row
            const tr = document.createElement('tr');
            tr.setAttribute('data-bottle-id', bottleId);
            tr.innerHTML = `
                <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
                <td>${volume} ml</td>
                <td>
                    <div style="display:flex; align-items:center; justify-content:center; gap:10px;">
                        <label class="switch">
                            <input type="checkbox" onchange="toggleThaw('${bottleId}', this)" ${isChecked}>
                            <span class="slider round"></span>
                        </label>
                        <span id="status-text-${bottleId}">${statusText}</span>
                    </div>
                </td>
            `;
            stage2Body.appendChild(tr);
        });
    }

    function toggleThaw(bottleId, checkbox) {
        // Update State
        thawingState[bottleId] = checkbox.checked;

        // Update Text Label
        const textSpan = document.getElementById(`status-text-${bottleId}`);
        if(checkbox.checked) {
            textSpan.innerHTML = '<span style="color:#10b981; font-weight:bold;">Yes</span>';
        } else {
            textSpan.innerHTML = '<span style="color:#64748b;">No</span>';
        }
    }

    function saveStage2Data() {
        // Convert thawingState object to array for saving
        const rows = document.querySelectorAll('#thawing-table tbody tr');
        let data = [];
        let allThawed = true;

        if(rows.length === 0 || (rows.length === 1 && rows[0].innerText.includes('No bottles'))) {
             Swal.fire('Error', 'No bottles to save.', 'error');
             return;
        }

        rows.forEach(row => {
            const bottleId = row.getAttribute('data-bottle-id');
            const isThawed = thawingState[bottleId] === true;
            
            if(!isThawed) allThawed = false;

            data.push({
                bottle_id: bottleId,
                is_thawed: isThawed
            });
        });

        if(!allThawed) {
            Swal.fire({
                title: 'Warning',
                text: 'Some bottles are marked as "No" (Not Thawed). Continue anyway?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, save status'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Saving Stage 2 Data:", data);
                    Swal.fire('Saved', 'Thawing status updated!', 'success');
                }
            });
        } else {
            console.log("Saving Stage 2 Data:", data);
            Swal.fire('Saved', 'All bottles marked as Thawed!', 'success');
        }
    }

    // ==========================================
    // 3.5 STAGE 3: PASTEURIZATION BOTTLE LOGIC
    // ==========================================

    // Helper to format Date to YYYY-MM-DD
    function formatDate(date) {
        const d = new Date(date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        const year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    function addPasteurBottleRow() {
        const tbody = document.querySelector('#pasteur-table tbody');
        
        // Remove empty message if it exists
        const emptyMsg = document.getElementById('pasteur-empty-msg');
        if(emptyMsg) emptyMsg.remove();

        // Calculate count based on current rows
        const count = tbody.rows.length + 1;
        
        // --- 1. SET VOLUME ---
        const fixedVolume = 30;

        // --- 2. SET DATES ---
        const today = new Date();
        const expiry = new Date();
        // Add 6 months to today
        expiry.setMonth(today.getMonth() + 6);

        const todayStr = formatDate(today);
        const expiryStr = formatDate(expiry);

        // --- 3. CREATE ROW ---
        // We use "-P" to distinguish Pasteurization bottles from Stage 1 bottles
        const bottleId = `${milkIdFormatted}-P${count}`;

        const row = tbody.insertRow();
        row.innerHTML = `
            <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
            
            <td>
                <input type="number" value="${fixedVolume}" readonly 
                       style="border:none; background:transparent; text-align:center; font-weight:bold; width:50px;"> ml
            </td>
            
            <td>
                <input type="date" value="${todayStr}" readonly 
                       style="border:none; background:transparent; text-align:center;">
            </td>
            
            <td>
                <input type="date" value="${expiryStr}" readonly 
                       style="border:none; background:transparent; text-align:center; color:#dc2626; font-weight:bold;">
            </td>
            
            <td class="actions">
                <button type="button" onclick="deletePasteurRow(this)"><i class="fas fa-trash"></i></button>
            </td>
        `;
    }

    function deletePasteurRow(btn) {
        const row = btn.closest('tr');
        const tbody = row.closest('tbody');
        row.remove();
        
        // If table empty, show message
        if(tbody.rows.length === 0) {
            tbody.innerHTML = `<tr id="pasteur-empty-msg"><td colspan="5" class="text-muted" style="padding:20px;">No pasteurized bottles added yet.</td></tr>`;
        } else {
            // Optional: Renumber P1, P2...
            renumberPasteurBottles();
        }
    }

    function renumberPasteurBottles() {
        const rows = document.querySelectorAll('#pasteur-table tbody tr');
        rows.forEach((row, index) => {
            // Skip if it's the empty message row
            if(row.id === 'pasteur-empty-msg') return;
            
            const newIndex = index + 1;
            // Update First Column text
            row.cells[0].innerText = `${milkIdFormatted}-P${newIndex}`;
        });
    }

    function saveStage3Data() {
        const rows = document.querySelectorAll('#pasteur-table tbody tr');
        let data = [];
        
        rows.forEach(row => {
            if(row.id === 'pasteur-empty-msg') return;

            // Extract data from inputs or text
            data.push({
                bottle_id: row.cells[0].innerText,
                volume: 30,
                pasteurization_date: row.querySelector('input[type="date"]').value, // 1st date input
                expiry_date: row.querySelectorAll('input[type="date"]')[1].value    // 2nd date input
            });
        });

        if(data.length === 0) {
            Swal.fire('Warning', 'Please add at least one pasteurized bottle.', 'warning');
            return;
        }

        console.log("Saving Stage 3 (Pasteurization) Data:", data);
        Swal.fire('Saved', 'Pasteurization records saved with generated Expiry Dates!', 'success');
    }

    // ==========================================
    // 4. STAGE 4: MICROBIOLOGY LOGIC (UPDATED)
    // ==========================================

    // Updated Switch Stage to load data when entering Stage 4
    function switchStage(stageId) {
        document.querySelectorAll('.stage-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.stage-tab').forEach(el => el.classList.remove('active'));
        
        document.getElementById(stageId + '-content').classList.add('active');
        document.querySelector(`[data-stage="${stageId}"]`).classList.add('active');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });

        if(stageId === 'stage2') loadStage2Bottles();
        if(stageId === 'stage4') loadStage4Bottles(); // Add this line
    }

    function loadStage4Bottles() {
        // Source: Stage 3 (Pasteurization) Table
        const sourceRows = document.querySelectorAll('#pasteur-table tbody tr');
        const targetBody = document.querySelector('#micro-table tbody');
        
        targetBody.innerHTML = ''; // Clear existing

        // Check if source is empty
        const isSourceEmpty = (sourceRows.length === 0) || (sourceRows.length === 1 && sourceRows[0].id === 'pasteur-empty-msg');

        if(isSourceEmpty) {
            targetBody.innerHTML = `<tr id="micro-empty-msg"><td colspan="5" class="text-muted" style="padding:20px;">No pasteurized bottles found in Stage 3.</td></tr>`;
            return;
        }

        sourceRows.forEach(row => {
            if(row.id === 'pasteur-empty-msg') return;

            const bottleId = row.cells[0].innerText;

            const tr = document.createElement('tr');
            tr.setAttribute('data-bottle-id', bottleId);
            
            // We use oninput="checkContamination(this)" to trigger calc immediately when typing
            tr.innerHTML = `
                <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
                <td>
                    <input type="number" class="cfu-input total-viable" placeholder="0" min="0" oninput="checkContamination(this)">
                </td>
                <td>
                    <input type="number" class="cfu-input entero" placeholder="0" min="0" oninput="checkContamination(this)">
                </td>
                <td>
                    <input type="number" class="cfu-input staph" placeholder="0" min="0" oninput="checkContamination(this)">
                </td>
                <td class="result-cell">
                    <span class="badge-status badge-pending">Pending Input</span>
                </td>
            `;
            targetBody.appendChild(tr);
        });
    }

    function checkContamination(inputElement) {
        const row = inputElement.closest('tr');
        
        // Get values (default to 0 if empty)
        const totalViable = parseFloat(row.querySelector('.total-viable').value) || 0;
        const entero = parseFloat(row.querySelector('.entero').value) || 0;
        const staph = parseFloat(row.querySelector('.staph').value) || 0;
        
        const resultCell = row.querySelector('.result-cell');

        // THRESHOLDS based on the image provided:
        // Total Viable >= 10^5 (100,000)
        // Enterobacteriaceae >= 10^4 (10,000)
        // Staphylococcus >= 10^4 (10,000)

        const limitTotal = 100000;
        const limitEntero = 10000;
        const limitStaph = 10000;

        let isContaminated = false;

        // Check conditions
        if (totalViable >= limitTotal || entero >= limitEntero || staph >= limitStaph) {
            isContaminated = true;
        }

        // Update UI
        if (isContaminated) {
            resultCell.innerHTML = `<span class="badge-status badge-fail"><i class="fas fa-times-circle"></i> Contaminated</span>`;
        } else {
            // Check if inputs are actually filled to show "Passed", otherwise keep pending/neutral if 0
            if(row.querySelector('.total-viable').value !== '') {
                resultCell.innerHTML = `<span class="badge-status badge-pass"><i class="fas fa-check-circle"></i> Not Contaminated</span>`;
            } else {
                resultCell.innerHTML = `<span class="badge-status badge-pending">Pending</span>`;
            }
        }
    }

    function saveStage4Data() {
        const rows = document.querySelectorAll('#micro-table tbody tr');
        let data = [];
        let hasErrors = false;

        rows.forEach(row => {
            if(row.id === 'micro-empty-msg') return;

            const resultText = row.querySelector('.badge-status').innerText;
            
            // Simple validation
            if(resultText.includes('Pending')) {
                hasErrors = true;
            }

            data.push({
                bottle_id: row.cells[0].innerText,
                total_viable: row.querySelector('.total-viable').value,
                entero: row.querySelector('.entero').value,
                staph: row.querySelector('.staph').value,
                result: resultText
            });
        });

        if(hasErrors) {
            Swal.fire('Incomplete', 'Please enter values for all bottles.', 'warning');
            return;
        }

        if(data.length === 0) {
            Swal.fire('Warning', 'No data to save.', 'warning');
            return;
        }

        console.log("Saving Stage 4 Data:", data);
        Swal.fire('Saved', 'Microbiology results recorded successfully!', 'success');
    }

    // ==========================================
    // 5. STAGE 5: STORAGE LOGIC (UPDATED)
    // ==========================================

    // Updated switchStage to include Stage 5 loading
    function switchStage(stageId) {
        document.querySelectorAll('.stage-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.stage-tab').forEach(el => el.classList.remove('active'));
        
        document.getElementById(stageId + '-content').classList.add('active');
        document.querySelector(`[data-stage="${stageId}"]`).classList.add('active');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });

        if(stageId === 'stage2') loadStage2Bottles();
        if(stageId === 'stage4') loadStage4Bottles();
        if(stageId === 'stage5') loadStage5Bottles(); // Add this line
    }

    function loadStage5Bottles() {
        const microRows = document.querySelectorAll('#micro-table tbody tr');
        const pasteurRows = document.querySelectorAll('#pasteur-table tbody tr');
        const targetBody = document.querySelector('#storage-table tbody');
        
        targetBody.innerHTML = ''; // Clear existing

        // 1. Create a Map of [BottleID] -> [ExpiryDate] from Stage 3
        let expiryMap = {};
        pasteurRows.forEach(row => {
            if(row.id === 'pasteur-empty-msg') return;
            const id = row.cells[0].innerText; // Bottle ID column
            // Expiry date is the 4th column (index 3), inside an input
            const expiryInput = row.cells[3].querySelector('input'); 
            if(expiryInput) {
                expiryMap[id] = expiryInput.value;
            }
        });

        let approvedCount = 0;

        // 2. Filter Stage 4 Rows
        microRows.forEach(row => {
            if(row.id === 'micro-empty-msg') return;

            // Check if the badge indicates pass (Not Contaminated)
            const passBadge = row.querySelector('.badge-pass');
            
            if(passBadge) {
                approvedCount++;
                const bottleId = row.dataset.bottleId; // We stored this in data attribute in Stage 4
                const expiry = expiryMap[bottleId] || 'N/A';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
                    <td style="color:#dc2626; font-weight:bold;">${expiry}</td>
                    <td>
                        <span class="badge-status badge-pass">
                            <i class="fas fa-check-circle"></i> Safe / Not Contaminated
                        </span>
                    </td>
                `;
                targetBody.appendChild(tr);
            }
        });

        // 3. Show message if no bottles passed or no data
        if(approvedCount === 0) {
            targetBody.innerHTML = `
                <tr id="storage-empty-msg">
                    <td colspan="3" class="text-muted" style="padding:20px;">
                        No bottles approved for storage. All bottles either contaminated or not tested yet.
                    </td>
                </tr>`;
        }
    }

    function saveStage5Data() {
        const drawerId = document.getElementById('drawer-id').value.trim();
        const rows = document.querySelectorAll('#storage-table tbody tr');
        
        if(!drawerId) {
            Swal.fire('Missing Info', 'Please enter a Storage Drawer ID.', 'warning');
            return;
        }

        let data = [];
        rows.forEach(row => {
            if(row.id === 'storage-empty-msg') return;
            data.push({
                bottle_id: row.cells[0].innerText,
                expiry_date: row.cells[1].innerText,
                drawer_id: drawerId
            });
        });

        if(data.length === 0) {
            Swal.fire('Error', 'No valid bottles to store.', 'error');
            return;
        }

        console.log("Saving Stage 5 Data:", data);
        Swal.fire('Process Complete!', 'Milk record finalized and stored.', 'success').then(() => {
            // Optional: Redirect
            // window.location.href = "{{ route('labtech.labtech_manage-milk-records') }}";
        });
    }

    // Initial run
    calculateTotalVolume();
</script>
@endsection