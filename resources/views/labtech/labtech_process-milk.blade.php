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
    {{-- STAGE 2: THAWING --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage2-content"
         data-start="{{ $milk->milk_stage2StartDate ?? '' }}"
         data-end="{{ $milk->milk_stage2EndDate ?? '' }}">
      <h2>Stage 2: Thawing</h2>
      <h3>Temperature Control</h3>
      <img src="{{ asset('images/lab_thawing.png') }}" alt="Thawing" style="width: 270px; height: auto;">

      <form method="POST" action="#">
        @csrf
        <div class="form-grid">
            <div>
                <label>Start Date</label>
                <input type="date" name="milk_stage2StartDate" value="{{ $milk->milk_stage2StartDate }}" {{ $milk->milk_stage2StartDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Date</label>
                <input type="date" name="milk_stage2EndDate" value="{{ $milk->milk_stage2EndDate }}" {{ $milk->milk_stage2EndDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>Start Time</label>
                <input type="time" name="milk_stage2StartTime" value="{{ $milk->milk_stage2StartTime }}" {{ $milk->milk_stage2StartTime ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Time</label>
                <input type="time" name="milk_stage2EndTime" value="{{ $milk->milk_stage2EndTime }}" {{ $milk->milk_stage2EndTime ? 'readonly' : '' }} required>
            </div>
        </div>

        <div class="stage-footer">
           @if(!$milk->milk_stage2StartDate)
             <button type="button" class="btn-submit-stage swal-submit" onclick="simulateSubmit(2)"><i class="fas fa-play"></i> Start Thawing</button>
           @else
             <div class="time-status active">IN PROGRESS / COMPLETED</div>
           @endif
        </div>
      </form>

      <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('stage1')"><i class="fas fa-arrow-left"></i> Previous</button>
        <button class="btn-next" onclick="switchStage('stage3')">Next Stage <i class="fas fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ================================================================================== --}}
    {{-- STAGE 3: PASTEURIZATION --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage3-content"
         data-start="{{ $milk->milk_stage3StartDate ?? '' }}"
         data-end="{{ $milk->milk_stage3EndDate ?? '' }}">
      <h2>Stage 3: Pasteurization</h2>
      <h3>Heat Treatment</h3>
      <img src="{{ asset('images/lab_pasteurization.png') }}" alt="Pasteurization" style="width: 270px; height: auto;">

      <form method="POST" action="#">
        @csrf
        <div class="form-grid">
            <div>
                <label>Start Date</label>
                <input type="date" name="milk_stage3StartDate" value="{{ $milk->milk_stage3StartDate }}" {{ $milk->milk_stage3StartDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Date</label>
                <input type="date" name="milk_stage3EndDate" value="{{ $milk->milk_stage3EndDate }}" {{ $milk->milk_stage3EndDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>Start Time</label>
                <input type="time" name="milk_stage3StartTime" value="{{ $milk->milk_stage3StartTime }}" {{ $milk->milk_stage3StartTime ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Time</label>
                <input type="time" name="milk_stage3EndTime" value="{{ $milk->milk_stage3EndTime }}" {{ $milk->milk_stage3EndTime ? 'readonly' : '' }} required>
            </div>
        </div>

        <div class="stage-footer">
           @if(!$milk->milk_stage3StartDate)
             <button type="button" class="btn-submit-stage swal-submit" onclick="simulateSubmit(3)"><i class="fas fa-play"></i> Start Pasteurization</button>
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
    {{-- STAGE 4: MICROBIOLOGY TEST --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage4-content"
         data-start="{{ $milk->milk_stage4StartDate ?? '' }}"
         data-end="{{ $milk->milk_stage4EndDate ?? '' }}">
      <h2>Stage 4: Microbiology Test</h2>
      <h3>Quality Assurance</h3>
      <img src="{{ asset('images/lab_microbiology_test.png') }}" alt="Microbiology" style="width: 270px; height: auto;">

      <form method="POST" action="#">
        @csrf
        <div class="form-grid">
            <div>
                <label>Start Date</label>
                <input type="date" name="milk_stage4StartDate" value="{{ $milk->milk_stage4StartDate }}" {{ $milk->milk_stage4StartDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Date</label>
                <input type="date" name="milk_stage4EndDate" value="{{ $milk->milk_stage4EndDate }}" {{ $milk->milk_stage4EndDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>Start Time</label>
                <input type="time" name="milk_stage4StartTime" value="{{ $milk->milk_stage4StartTime }}" {{ $milk->milk_stage4StartTime ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Time</label>
                <input type="time" name="milk_stage4EndTime" value="{{ $milk->milk_stage4EndTime }}" {{ $milk->milk_stage4EndTime ? 'readonly' : '' }} required>
            </div>
        </div>

        {{-- TEST RESULTS TABLE --}}
        <div class="data-table-container" style="display: block; margin-top:20px;">
            <div class="table-header-info">
                <span class="animals-selected">Lab Test Results</span>
                <button type="button" class="btn-add-row" onclick="addTestRow()">
                    <i class="fas fa-plus"></i> Add Test
                </button>
            </div>
            <table class="data-table" id="test-table">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Result Value</th>
                        <th>Status (Pass/Fail)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tests = $milk->milk_stage4Result ? json_decode($milk->milk_stage4Result, true) : [];
                    @endphp
                    @if(count($tests) > 0)
                        @foreach($tests as $t)
                        <tr>
                            <td contenteditable="true">{{ $t['name'] }}</td>
                            <td contenteditable="true">{{ $t['value'] }}</td>
                            <td contenteditable="true">{{ $t['status'] }}</td>
                            <td class="actions"><button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td contenteditable="true">Total Bacteria Count</td>
                            <td contenteditable="true">Pending</td>
                            <td contenteditable="true">Pending</td>
                            <td class="actions"><button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>
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
    {{-- STAGE 5: POST-PASTEURIZATION --}}
    {{-- ================================================================================== --}}
    <div class="process-card stage-content" id="stage5-content"
         data-start="{{ $milk->milk_stage5StartDate ?? '' }}"
         data-end="{{ $milk->milk_stage5EndDate ?? '' }}">
      <h2>Stage 5: Post-Pasteurization</h2>
      <h3>Final Storage & Approval</h3>
      <img src="{{ asset('images/lab_post_pasteurization.png') }}" alt="Storage" style="width: 270px; height: auto;">

      <form method="POST" action="#">
        @csrf
        <div class="form-grid">
            <div>
                <label>Start Date</label>
                <input type="date" name="milk_stage5StartDate" value="{{ $milk->milk_stage5StartDate }}" {{ $milk->milk_stage5StartDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Date</label>
                <input type="date" name="milk_stage5EndDate" value="{{ $milk->milk_stage5EndDate }}" {{ $milk->milk_stage5EndDate ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>Start Time</label>
                <input type="time" name="milk_stage5StartTime" value="{{ $milk->milk_stage5StartTime }}" {{ $milk->milk_stage5StartTime ? 'readonly' : '' }} required>
            </div>
            <div>
                <label>End Time</label>
                <input type="time" name="milk_stage5EndTime" value="{{ $milk->milk_stage5EndTime }}" {{ $milk->milk_stage5EndTime ? 'readonly' : '' }} required>
            </div>
        </div>

        <div class="stage-footer">
           @if(!$milk->milk_stage5StartDate)
             <button type="button" class="btn-submit-stage swal-submit" onclick="simulateSubmit(5)"><i class="fas fa-check-circle"></i> Complete Process</button>
           @else
             <div class="time-status completed">PROCESS COMPLETED</div>
           @endif
        </div>
      </form>

      <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('stage4')"><i class="fas fa-arrow-left"></i> Previous</button>
        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="btn-submit-stage" style="text-decoration:none; display:inline-block; width:auto;">Done</a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const milkIdFormatted = "{{ $milk->formatted_id }}";

    // 1. Tab Switching Logic
    function switchStage(stageId) {
        document.querySelectorAll('.stage-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.stage-tab').forEach(el => el.classList.remove('active'));
        
        document.getElementById(stageId + '-content').classList.add('active');
        document.querySelector(`[data-stage="${stageId}"]`).classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.stage-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            switchStage(this.dataset.stage);
        });
    });

    // 2. Stage 1: Bottle Logic
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

    // Monitor volume changes
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

    // 3. Stage 4: Test Logic
    function addTestRow() {
        const tbody = document.querySelector('#test-table tbody');
        const row = tbody.insertRow();
        row.innerHTML = `
            <td contenteditable="true">New Test</td>
            <td contenteditable="true">Pending</td>
            <td contenteditable="true">Pending</td>
            <td class="actions"><button type="button" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>
        `;
    }

    // 4. Data Simulation (For Testing UI without Backend)
    function saveStage1Data() {
        const rows = document.querySelectorAll('#bottle-table tbody tr');
        let data = [];
        rows.forEach(row => {
            data.push({
                bottle_id: row.cells[2].innerText,
                volume: row.cells[1].innerText
            });
        });

        // SIMULATE SUCCESS
        console.log("Simulating Save Stage 1:", data);
        Swal.fire('Saved', 'Bottles saved successfully! (Simulation)', 'success');
    }

    function saveStage4Data() {
        const rows = document.querySelectorAll('#test-table tbody tr');
        let data = [];
        rows.forEach(row => {
            data.push({
                name: row.cells[0].innerText,
                value: row.cells[1].innerText,
                status: row.cells[2].innerText
            });
        });
        
        // SIMULATE SUCCESS
        console.log("Simulating Save Stage 4:", data);
        Swal.fire('Saved', 'Test results saved! (Simulation)', 'success');
    }

    function simulateSubmit(stage) {
        // SIMULATE SUBMIT FOR OTHER STAGES
        Swal.fire({
            title: `Start Stage ${stage}?`,
            text: "This is a simulation. No data will be sent to the database.",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#10b981",
            confirmButtonText: "Yes, start simulation"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("Started!", `Stage ${stage} process started (Simulation)`, "success");
            }
        });
    }

    // Initial run
    calculateTotalVolume();
</script>
@endsection