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
                    @if($milk->preBottles->count() > 0)
                        @foreach($milk->preBottles as $index => $bottle)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                
                                <td contenteditable="true" class="volume-cell">{{ $bottle->pre_volume }}</td>
                                
                                <td>{{ $bottle->pre_bottle_code }}</td>
                                
                                <td class="actions">
                                    <button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
                                </td>
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
                        <td colspan="1" style="text-align:right; padding: 14px 12px;">Total Volume:</td>
                        <td style="padding: 14px 12px;">
                            <span id="total-volume-display">0</span> / {{ $milk->milk_volume }} ml
                        </td>
                        <td colspan="2" style="padding: 14px 12px;">
                            <small id="volume-warning" class="text-danger" style="display:none;">
                                Exceeds Limit!
                            </small>
                        </td>
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
        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="btn-back-nav text"><i class="fas fa-arrow-left"></i> Back</a>
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
                    @if($milk->preBottles->count() > 0)
                        @foreach($milk->preBottles as $bottle)
                            <tr data-bottle-id="{{ $bottle->pre_bottle_code }}">
                                <td style="font-weight:bold; color:#1A5F7A;">{{ $bottle->pre_bottle_code }}</td>
                                <td>{{ $bottle->pre_volume }} ml</td>
                                <td>
                                    <div style="display:flex; align-items:center; justify-content:center; gap:10px;">
                                        <label class="switch">
                                            <input type="checkbox" 
                                                onchange="toggleThaw('{{ $bottle->pre_bottle_code }}', this)" 
                                                {{ $bottle->pre_is_thawed ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                        <span id="status-text-{{ $bottle->pre_bottle_code }}">
                                            @if($bottle->pre_is_thawed)
                                                <span class="text-success fw-bold">Yes</span>
                                            @else
                                                <span class="text-muted">No</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="no-bottles-msg">
                            <td colspan="3" style="padding: 20px; color: #64748b;">
                                No bottles found. Please add bottles in Stage 1 first.
                            </td>
                        </tr>
                    @endif
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
                    @if($milk->postBottles->count() > 0)
                        @foreach($milk->postBottles as $bottle)
                            <tr>
                                <td style="font-weight:bold; color:#1A5F7A;">{{ $bottle->post_bottle_code }}</td>
                                
                                <td>
                                    <input type="number" value="{{ $bottle->post_volume }}" readonly 
                                        style="border:none; background:transparent; text-align:center; font-weight:bold; width:50px;"> ml
                                </td>
                                
                                <td>
                                    <input type="date" value="{{ $bottle->post_pasteurization_date ? \Carbon\Carbon::parse($bottle->post_pasteurization_date)->format('Y-m-d') : '' }}" 
                                        readonly style="border:none; background:transparent; text-align:center;">
                                </td>
                                
                                <td>
                                    <input type="date" value="{{ $bottle->post_expiry_date ? \Carbon\Carbon::parse($bottle->post_expiry_date)->format('Y-m-d') : '' }}" 
                                        readonly style="border:none; background:transparent; text-align:center; color:#dc2626; font-weight:bold;">
                                </td>
                                
                                <td class="actions">
                                    <button type="button" onclick="deletePasteurRow(this)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="pasteur-empty-msg">
                            <td colspan="5" class="text-muted" style="padding:20px;">No pasteurized bottles added yet.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8fafc; font-weight:bold;">
                        <td style="text-align:right; padding: 14px 12px;">Total Volume:</td>
                        <td style="padding: 14px 12px;">
                            <span id="pasteur-total-volume-display">0</span> / {{ $milk->milk_volume }} ml
                        </td>
                        <td colspan="3" style="padding: 14px 12px;">
                            <small id="pasteur-volume-warning" class="text-danger" style="display:none;">
                                Exceeds Limit!
                            </small>
                        </td>
                    </tr>
                </tfoot>
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
                    @if($milk->postBottles->count() > 0)
                        @foreach($milk->postBottles as $bottle)
                            <tr data-bottle-id="{{ $bottle->post_bottle_code }}">
                                <td style="font-weight:bold; color:#1A5F7A;">{{ $bottle->post_bottle_code }}</td>
                                
                                <td>
                                    <input type="number" class="cfu-input total-viable" 
                                        value="{{ $bottle->post_micro_total_viable }}" 
                                        placeholder="0" min="0" oninput="checkContamination(this)">
                                </td>
                                
                                <td>
                                    <input type="number" class="cfu-input entero" 
                                        value="{{ $bottle->post_micro_entero }}" 
                                        placeholder="0" min="0" oninput="checkContamination(this)">
                                </td>
                                
                                <td>
                                    <input type="number" class="cfu-input staph" 
                                        value="{{ $bottle->post_micro_staph }}" 
                                        placeholder="0" min="0" oninput="checkContamination(this)">
                                </td>
                                
                                <td class="result-cell">
                                    @if($bottle->post_micro_status == 'Contaminated' || $bottle->post_micro_status == 'Failed')
                                        <span class="badge-status badge-fail"><i class="fas fa-times-circle"></i> Contaminated</span>
                                    @elseif($bottle->post_micro_status == 'Not Contaminated' || $bottle->post_micro_status == 'Passed')
                                        <span class="badge-status badge-pass"><i class="fas fa-check-circle"></i> Not Contaminated</span>
                                    @else
                                        <span class="badge-status badge-pending">Pending Input</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="micro-empty-msg">
                            <td colspan="5" class="text-muted" style="padding:20px;">
                                No pasteurized bottles found. Complete Stage 3 first.
                            </td>
                        </tr>
                    @endif
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

        @php
            // Try to get existing drawer ID from the first post bottle
            $existingDrawer = $milk->postBottles->first()->post_storage_location ?? '';
        @endphp
        
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
                <thead>...</thead>
                <tbody>
                    @php $approvedCount = 0; @endphp
                    @foreach($milk->postBottles as $bottle)
                        {{-- Only show bottles that passed Micro test --}}
                        @if(in_array($bottle->post_micro_status, ['Passed', 'Not Contaminated']))
                            @php $approvedCount++; @endphp
                            <tr>
                                <td style="font-weight:bold; color:#1A5F7A;">{{ $bottle->post_bottle_code }}</td>
                                <td style="color:#dc2626; font-weight:bold;">{{ $bottle->post_expiry_date }}</td>
                                <td>
                                    <span class="badge-status badge-pass">
                                        <i class="fas fa-check-circle"></i> Safe / Not Contaminated
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    @if($approvedCount === 0)
                        <tr id="storage-empty-msg">
                            <td colspan="3" class="text-muted" style="padding:20px;">
                                No approved bottles found yet.
                            </td>
                        </tr>
                    @endif
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
    const saveRouteBase = "/labtech/process-milk/{{ $milk->milk_ID }}/"; 
    // START NEW: Store the max volume
    const maxMilkVolume = {{ $milk->milk_volume }}; 
    // END NEW
    
    let bottleState = {}; 

    // ==========================================
    // 1. INITIALIZATION & RESTORE STATE
    // ==========================================
    // ==========================================
    // 1. INITIALIZATION & RESTORE STATE
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotalVolume();        // Stage 1 Calc
        calculatePasteurTotalVolume(); // Stage 3 Calc (Add this line)

        // --- NEW: AUTO-CALCULATE MICRO STATUS ON LOAD ---
        // This ensures the badge matches the numbers loaded from the database
        const microRows = document.querySelectorAll('#micro-table tbody tr');
        microRows.forEach(row => {
            // Skip the "empty" message row
            if(row.id === 'micro-empty-msg') return;
            
            // Find one of the inputs to trigger the check function
            const input = row.querySelector('.cfu-input');
            if(input) {
                checkContamination(input);
            }
        });
        // ------------------------------------------------

        const savedStage = sessionStorage.getItem('reloadStage');
        const savedMessage = sessionStorage.getItem('reloadMessage');

        if (savedStage) {
            switchStage(savedStage);
            if (savedMessage) {
                Swal.fire({
                    icon: 'success', title: 'Saved', text: savedMessage,
                    timer: 2000, showConfirmButton: false
                });
            }
            sessionStorage.removeItem('reloadStage');
            sessionStorage.removeItem('reloadMessage');
        }
    });

    // ... [KEEP NAVIGATION LOGIC (switchStage) AS IS] ...
    function switchStage(stageId) {
        document.querySelectorAll('.stage-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.stage-tab').forEach(el => el.classList.remove('active'));
        
        const content = document.getElementById(stageId + '-content');
        const tab = document.querySelector(`[data-stage="${stageId}"]`);

        if(content) content.classList.add('active');
        if(tab) tab.classList.add('active');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });

        if(stageId === 'stage2') loadStage2Bottles();
        if(stageId === 'stage4') loadStage4Bottles();
        if(stageId === 'stage5') loadStage5Bottles();
    }
    
    document.querySelectorAll('.stage-tab').forEach(btn => {
        btn.addEventListener('click', function() { switchStage(this.dataset.stage); });
    });

    // ... [KEEP ROW ADD/DELETE LOGIC AS IS] ...
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

    document.querySelector('#bottle-table').addEventListener('input', function(e) {
        if(e.target.classList.contains('volume-cell')) {
            calculateTotalVolume();
        }
    });

    // UPDATED: Volume Calculation with Limit Check
    function calculateTotalVolume() {
        let total = 0;
        document.querySelectorAll('.volume-cell').forEach(cell => {
            const val = parseFloat(cell.innerText) || 0;
            total += val;
        });
        
        const display = document.getElementById('total-volume-display');
        const warning = document.getElementById('volume-warning');
        
        display.innerText = total;

        if (total > maxMilkVolume) {
            display.style.color = '#dc2626'; // Red
            display.style.fontWeight = 'bold';
            if(warning) warning.style.display = 'inline';
        } else {
            display.style.color = '#334155'; // Normal
            display.style.fontWeight = 'normal';
            if(warning) warning.style.display = 'none';
        }
        return total;
    }

    // ... [KEEP STAGE 2 UTILS AS IS] ...
    function toggleThaw(bottleId, checkbox) {
        const textSpan = document.getElementById(`status-text-${bottleId}`);
        if(checkbox.checked) {
            textSpan.innerHTML = '<span class="text-success fw-bold">Yes</span>';
        } else {
            textSpan.innerHTML = '<span class="text-muted">No</span>';
        }
    }

    function loadStage2Bottles() {
        const stage1Rows = document.querySelectorAll('#bottle-table tbody tr');
        const stage2Body = document.querySelector('#thawing-table tbody');
        const existingRows = stage2Body.querySelectorAll('tr');
        if(existingRows.length > 0 && existingRows[0].id !== 'no-bottles-msg') return;

        stage2Body.innerHTML = '';
        if(stage1Rows.length === 0) {
            stage2Body.innerHTML = `<tr><td colspan="3" class="text-muted" style="padding:20px;">No bottles found in Stage 1.</td></tr>`;
            return;
        }
        stage1Rows.forEach(row => {
            const bottleId = row.cells[2].innerText.trim(); 
            const volume = row.cells[1].innerText.trim(); 
            const tr = document.createElement('tr');
            tr.setAttribute('data-bottle-id', bottleId);
            tr.innerHTML = `
                <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
                <td>${volume} ml</td>
                <td>
                    <div style="display:flex; align-items:center; justify-content:center; gap:10px;">
                        <label class="switch">
                            <input type="checkbox" onchange="toggleThaw('${bottleId}', this)">
                            <span class="slider round"></span>
                        </label>
                        <span id="status-text-${bottleId}"><span class="text-muted">No</span></span>
                    </div>
                </td>
            `;
            stage2Body.appendChild(tr);
        });
    }

    // ... [KEEP STAGE 3 UTILS] ...
    function formatDate(date) {
        const d = new Date(date);
        let month = '' + (d.getMonth() + 1), day = '' + d.getDate(), year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    function addPasteurBottleRow() {
    const tbody = document.querySelector('#pasteur-table tbody');
    const emptyMsg = document.getElementById('pasteur-empty-msg');
    if(emptyMsg) emptyMsg.remove();
    
    const count = tbody.rows.length + 1;
    const fixedVolume = 30; // This is your fixed volume
    const today = new Date();
    const expiry = new Date(); expiry.setMonth(today.getMonth() + 6);
    
    const row = tbody.insertRow();
    row.innerHTML = `
        <td style="font-weight:bold; color:#1A5F7A;">${milkIdFormatted}-P${count}</td>
        <td>
            {{-- This is the Volume Input we are calculating --}}
            <input type="number" value="${fixedVolume}" readonly 
                   style="border:none; background:transparent; text-align:center; font-weight:bold; width:50px;"> ml
        </td>
        <td><input type="date" value="${formatDate(today)}" readonly style="border:none; background:transparent; text-align:center;"></td>
        <td><input type="date" value="${formatDate(expiry)}" readonly style="border:none; background:transparent; text-align:center; color:#dc2626; font-weight:bold;"></td>
        <td class="actions"><button type="button" onclick="deletePasteurRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
    
    // --- CALL CALCULATION HERE ---
    calculatePasteurTotalVolume();
}

    function deletePasteurRow(btn) {
    const row = btn.closest('tr');
    const tbody = row.closest('tbody');
    row.remove();
    
    if(tbody.rows.length === 0) {
        tbody.innerHTML = `<tr id="pasteur-empty-msg"><td colspan="5" class="text-muted" style="padding:20px;">No pasteurized bottles added yet.</td></tr>`;
    } else {
        renumberPasteurBottles();
    }
    
    // --- CALL CALCULATION HERE ---
    calculatePasteurTotalVolume();
}

    function renumberPasteurBottles() {
        const rows = document.querySelectorAll('#pasteur-table tbody tr');
        rows.forEach((row, index) => {
            if(row.id === 'pasteur-empty-msg') return;
            row.cells[0].innerText = `${milkIdFormatted}-P${index + 1}`;
        });
    }

    function calculatePasteurTotalVolume() {
    let total = 0;
    
    // Select all rows in the Stage 3 table body
    const rows = document.querySelectorAll('#pasteur-table tbody tr');

    rows.forEach(row => {
        // Skip the "No bottles" message row
        if(row.id === 'pasteur-empty-msg') return;

        // Select the input specifically in the 2nd column (Volume column)
        const volumeInput = row.querySelector('td:nth-child(2) input');
        
        if (volumeInput) {
            total += parseFloat(volumeInput.value) || 0;
        }
    });

    // Update the Footer Display
    const display = document.getElementById('pasteur-total-volume-display');
    const warning = document.getElementById('pasteur-volume-warning');

    if (display) {
        display.innerText = total;

        // Check Limit (using your global maxMilkVolume variable)
        if (typeof maxMilkVolume !== 'undefined' && total > maxMilkVolume) {
            display.style.color = '#dc2626'; // Red
            display.style.fontWeight = 'bold';
            if(warning) warning.style.display = 'inline';
        } else {
            display.style.color = '#334155'; // Normal
            display.style.fontWeight = 'normal';
            if(warning) warning.style.display = 'none';
        }
    }
}

    // ... [KEEP STAGE 4 & 5 UTILS AS IS] ...
    function loadStage4Bottles() {
        const sourceRows = document.querySelectorAll('#pasteur-table tbody tr');
        const targetBody = document.querySelector('#micro-table tbody');
        const existingRows = targetBody.querySelectorAll('tr');
        if(existingRows.length > 0 && existingRows[0].id !== 'micro-empty-msg') return;

        targetBody.innerHTML = '';
        if((sourceRows.length === 0) || (sourceRows.length === 1 && sourceRows[0].id === 'pasteur-empty-msg')) {
            targetBody.innerHTML = `<tr id="micro-empty-msg"><td colspan="5" class="text-muted" style="padding:20px;">No pasteurized bottles found in Stage 3.</td></tr>`;
            return;
        }

        sourceRows.forEach(row => {
            if(row.id === 'pasteur-empty-msg') return;
            const bottleId = row.cells[0].innerText;
            const tr = document.createElement('tr');
            tr.setAttribute('data-bottle-id', bottleId);
            tr.innerHTML = `
                <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
                <td><input type="number" class="cfu-input total-viable" placeholder="0" min="0" oninput="checkContamination(this)"></td>
                <td><input type="number" class="cfu-input entero" placeholder="0" min="0" oninput="checkContamination(this)"></td>
                <td><input type="number" class="cfu-input staph" placeholder="0" min="0" oninput="checkContamination(this)"></td>
                <td class="result-cell"><span class="badge-status badge-pending">Pending Input</span></td>
            `;
            targetBody.appendChild(tr);
        });
    }

    function checkContamination(inputElement) {
        const row = inputElement.closest('tr');
        const totalViable = parseFloat(row.querySelector('.total-viable').value) || 0;
        const entero = parseFloat(row.querySelector('.entero').value) || 0;
        const staph = parseFloat(row.querySelector('.staph').value) || 0;
        const resultCell = row.querySelector('.result-cell');

        const limitTotal = 100000;
        const limitEntero = 10000;
        const limitStaph = 10000;

        let isContaminated = false;
        if (totalViable >= limitTotal || entero >= limitEntero || staph >= limitStaph) {
            isContaminated = true;
        }

        if (isContaminated) {
            resultCell.innerHTML = `<span class="badge-status badge-fail"><i class="fas fa-times-circle"></i> Contaminated</span>`;
        } else {
            if(row.querySelector('.total-viable').value !== '') {
                resultCell.innerHTML = `<span class="badge-status badge-pass"><i class="fas fa-check-circle"></i> Not Contaminated</span>`;
            } else {
                resultCell.innerHTML = `<span class="badge-status badge-pending">Pending</span>`;
            }
        }
    }

    function loadStage5Bottles() {
        const microRows = document.querySelectorAll('#micro-table tbody tr');
        const pasteurRows = document.querySelectorAll('#pasteur-table tbody tr');
        const targetBody = document.querySelector('#storage-table tbody');
        const existingRows = targetBody.querySelectorAll('tr');
        if(existingRows.length > 0 && existingRows[0].id !== 'storage-empty-msg') return;

        targetBody.innerHTML = '';
        let expiryMap = {};
        pasteurRows.forEach(row => {
            if(row.id === 'pasteur-empty-msg') return;
            const id = row.cells[0].innerText;
            const expiryInput = row.cells[3].querySelector('input'); 
            if(expiryInput) expiryMap[id] = expiryInput.value;
        });

        let approvedCount = 0;
        microRows.forEach(row => {
            if(row.id === 'micro-empty-msg') return;
            const passBadge = row.querySelector('.badge-pass');
            if(passBadge) {
                approvedCount++;
                const bottleId = row.dataset.bottleId;
                const expiry = expiryMap[bottleId] || 'N/A';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:bold; color:#1A5F7A;">${bottleId}</td>
                    <td style="color:#dc2626; font-weight:bold;">${expiry}</td>
                    <td><span class="badge-status badge-pass"><i class="fas fa-check-circle"></i> Safe / Not Contaminated</span></td>
                `;
                targetBody.appendChild(tr);
            }
        });

        if(approvedCount === 0) {
            targetBody.innerHTML = `<tr id="storage-empty-msg"><td colspan="3" class="text-muted" style="padding:20px;">No approved bottles found yet.</td></tr>`;
        }
    }

    // ==========================================
    // 4. SAVE & RELOAD FUNCTIONS
    // ==========================================

    function reloadOnStage(stageName, message) {
        sessionStorage.setItem('reloadStage', stageName);
        sessionStorage.setItem('reloadMessage', message);
        location.reload();
    }

    // UPDATED: SAVE STAGE 1 WITH VALIDATION
    function saveStage1Data() {
        const date = document.querySelector('input[name="milk_stage1StartDate"]').value;
        const time = document.querySelector('input[name="milk_stage1StartTime"]').value;
        const rows = document.querySelectorAll('#bottle-table tbody tr');
        let bottles = [];
        let totalVol = 0;

        rows.forEach(row => {
            if(row.cells.length < 3) return;
            const vol = parseFloat(row.querySelector('.volume-cell').innerText);
            const id = row.cells[2].innerText;
            if(vol >= 0) {
                bottles.push({ bottle_id: id, volume: vol });
                totalVol += vol;
            }
        });

        if(bottles.length === 0) return Swal.fire('Error', 'Add at least one bottle.', 'error');

        // Check Limit
        if(totalVol > maxMilkVolume) {
            return Swal.fire('Limit Exceeded', `Total volume (${totalVol} ml) exceeds the milk batch limit (${maxMilkVolume} ml).`, 'error');
        }

        fetch("{{ route('labtech.save-stage1', $milk->milk_ID) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ milk_stage1StartDate: date, milk_stage1StartTime: time, bottles: bottles })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                reloadOnStage('stage1', 'Labelling data saved successfully!');
            } else {
                Swal.fire('Error', data.message || 'Failed to save', 'error');
            }
        });
    }

    // ... [KEEP STAGE 2 SAVE AS IS] ...
    function saveStage2Data() {
        const rows = document.querySelectorAll('#thawing-table tbody tr');
        let bottles = [];
        rows.forEach(row => {
            if(row.id === 'no-bottles-msg') return;
            const id = row.dataset.bottleId;
            const isThawed = row.querySelector('input[type="checkbox"]').checked;
            bottles.push({ bottle_id: id, is_thawed: isThawed });
        });

        if(bottles.length === 0) return Swal.fire('Error', 'No bottles to save', 'error');

        fetch("{{ route('labtech.save-stage2', $milk->milk_ID) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ bottles: bottles })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                reloadOnStage('stage2', 'Thawing status saved successfully!');
            }
        });
    }

    // UPDATED: SAVE STAGE 3 WITH VALIDATION
    function saveStage3Data() {
        const rows = document.querySelectorAll('#pasteur-table tbody tr');
        let bottles = [];
        let totalVol = 0;

        rows.forEach(row => {
            if(row.id === 'pasteur-empty-msg') return;
            const id = row.cells[0].innerText;
            const pDate = row.cells[2].querySelector('input').value;
            const eDate = row.cells[3].querySelector('input').value;
            // Assuming volume is fixed 30 or read from input
            const volInput = row.cells[1].querySelector('input');
            const vol = volInput ? parseFloat(volInput.value) : 30; 
            
            bottles.push({ bottle_id: id, volume: vol, pasteurization_date: pDate, expiry_date: eDate });
            totalVol += vol;
        });

        if(bottles.length === 0) return Swal.fire('Error', 'Add pasteurized bottles first', 'error');

        // Check Limit
        if(totalVol > maxMilkVolume) {
            return Swal.fire('Limit Exceeded', `Total pasteurized volume (${totalVol} ml) exceeds the milk batch limit (${maxMilkVolume} ml).`, 'error');
        }

        fetch("{{ route('labtech.save-stage3', $milk->milk_ID) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ bottles: bottles })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                reloadOnStage('stage3', 'Pasteurization data saved successfully!');
            } else {
                Swal.fire('Error', data.message || 'Failed to save', 'error');
            }
        });
    }

    // ... [KEEP STAGE 4 & 5 SAVE AS IS] ...
    function saveStage4Data() {
        const rows = document.querySelectorAll('#micro-table tbody tr');
        let bottles = [];
        rows.forEach(row => {
            if(row.id === 'micro-empty-msg') return;
            const id = row.cells[0].innerText;
            const total = row.querySelector('.total-viable').value;
            const entero = row.querySelector('.entero').value;
            const staph = row.querySelector('.staph').value;
            const result = row.querySelector('.badge-status').innerText.trim();
            bottles.push({ bottle_id: id, total_viable: total, entero: entero, staph: staph, result: result });
        });

        fetch("{{ route('labtech.save-stage4', $milk->milk_ID) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ bottles: bottles })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                reloadOnStage('stage4', 'Microbiology results saved successfully!');
            }
        });
    }

    function saveStage5Data() {
        const drawer = document.getElementById('drawer-id').value;
        const rows = document.querySelectorAll('#storage-table tbody tr');
        let bottles = [];
        rows.forEach(row => {
            if(row.id === 'storage-empty-msg') return;
            bottles.push({ bottle_id: row.cells[0].innerText });
        });

        if(!drawer) return Swal.fire('Error', 'Enter Drawer ID', 'error');

        fetch("{{ route('labtech.save-stage5', $milk->milk_ID) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ drawer_id: drawer, bottles: bottles })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success', title: 'Completed', text: 'Process Finished!',
                    timer: 2000, showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('labtech.labtech_manage-milk-records') }}";
                });
            }
        });
    }
</script>
@endsection