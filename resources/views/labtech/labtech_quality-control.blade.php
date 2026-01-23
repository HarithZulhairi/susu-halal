@extends('layouts.labtech')

@section('title', 'Inventory Quality Control')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_quality-control.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{{-- ========================================================= --}}
{{-- DUMMY DATA: Only Post-Pasteurization (Stored) Batches --}}
{{-- ========================================================= --}}
@php
    $batches = [
        (object)[
            'id' => 201,
            'batch_id' => 'M26-005',
            'donor' => 'Mariam Isa',
            'volume' => 200,
            'expiry' => '2026-07-23',
            'location' => 'Freezer 2 - Drawer A01',
            'status' => 'Pending QC', 
            'bottles' => [
                (object)['id' => 'M26-005-P1', 'status' => 'Pending'],
                (object)['id' => 'M26-005-P2', 'status' => 'Pending'],
                (object)['id' => 'M26-005-P3', 'status' => 'Pending']
            ]
        ],
        (object)[
            'id' => 202,
            'batch_id' => 'M26-008',
            'donor' => 'Siti Aminah',
            'volume' => 150,
            'expiry' => '2026-08-01',
            'location' => 'Freezer 1 - Drawer B02',
            'status' => 'Safe', 
            'bottles' => [
                (object)['id' => 'M26-008-P1', 'status' => 'Safe'],
                (object)['id' => 'M26-008-P2', 'status' => 'Safe']
            ]
        ]
    ];
@endphp

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>Inventory Quality Control</h1>
            <p>Monthly Surveillance & Microbial Testing</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Stored Batches Surveillance</h2>
                <div class="actions-header">
                    <button class="btn btn-search"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>

            <div class="records-list">
                <div class="record-header">
                    <button class="sortable-header">BATCH ID</button>
                    <button class="sortable-header">LOCATION</button>
                    <button class="sortable-header">EXPIRY DATE</button>
                    <button class="sortable-header">LAST QC DATE</button>
                    <button class="sortable-header">QC STATUS</button>
                    <span>ACTION</span>
                </div>

                @foreach($batches as $batch)
                    <div class="record-item">
                        <div class="milk-donor-info">
                            <div class="milk-icon-wrapper" style="background:#e0f2fe; color:#0369a1;">
                                <i class="fas fa-box-archive"></i>
                            </div>
                            <div>
                                <span class="milk-id">{{ $batch->batch_id }}</span>
                                <span class="donor-name">{{ $batch->donor }}</span>
                            </div>
                        </div>

                        <div>{{ $batch->location }}</div>
                        
                        <div class="expiry-date">{{ $batch->expiry }}</div>
                        
                        <div>{{ date('Y-m-d') }}</div> 

                        <div class="clinical-status">
                            @if($batch->status == 'Safe')
                                <span class="status-tag status-approved">Safe</span>
                            @elseif($batch->status == 'Contaminated')
                                <span class="status-tag status-rejected">Contaminated</span>
                            @else
                                <span class="status-tag status-pending">Pending QC</span>
                            @endif
                        </div>

                        <div class="actions">
                            <button class="btn-view" onclick='openQCModal(@json($batch))' title="Perform QC Test">
                                <i class="fas fa-microscope"></i> Test
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- QC TESTING MODAL --}}
{{-- ========================================================= --}}
<div id="qcModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h2><i class="fas fa-microscope"></i> Batch Quality Control</h2>
            <button class="modal-close-btn" onclick="closeQCModal()">Close</button>
        </div>

        <div class="modal-body">
            <div style="display:flex; justify-content:space-between; margin-bottom:20px; background:#f8fafc; padding:15px; border-radius:8px;">
                <div>
                    <small>Batch ID</small>
                    <h3 style="margin:0; color:#1A5F7A;" id="modal-batch-id">-</h3>
                </div>
                <div style="text-align:right;">
                    <small>Current Status</small>
                    <h3 style="margin:0;" id="modal-batch-status">-</h3>
                </div>
            </div>

            <p style="color:#64748b; margin-bottom:15px;">
                <i class="fas fa-info-circle"></i> Enter the colony counts (CFU/ml) for each bottle. 
                If <strong>any</strong> bottle exceeds the limit, the entire batch will be marked 
                <span style="color:#dc2626; font-weight:bold;">CONTAMINATED</span>.
            </p>

            <table class="table-qc">
                <thead>
                    <tr>
                        <th>Bottle ID</th>
                        <th>Total Viable <br><small>(Limit: &lt; 100,000)</small></th>
                        <th>Enterobacteriaceae <br><small>(Limit: &lt; 10,000)</small></th>
                        <th>Staphylococcus <br><small>(Limit: &lt; 10,000)</small></th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody id="qc-bottle-list">
                    {{-- Rows injected via JS --}}
                </tbody>
            </table>

            <div style="margin-top:20px; text-align:right;">
                <button class="btn" style="background:#1A5F7A; color:white; padding:10px 20px; border-radius:6px;" onclick="saveQCResults()">
                    <i class="fas fa-save"></i> Save Results
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .table-qc { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table-qc th { background: #f1f5f9; padding: 12px; text-align: left; font-size: 13px; color: #475569; border-bottom: 2px solid #e2e8f0; }
    .table-qc td { padding: 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    
    .qc-input { 
        width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; 
        font-weight: 600; text-align: center; transition: all 0.2s;
    }
    .qc-input:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.1); }
    
    /* Validation Colors */
    .qc-input.safe { border-color: #22c55e; background-color: #f0fdf4; color: #15803d; }
    .qc-input.danger { border-color: #ef4444; background-color: #fef2f2; color: #b91c1c; }

    .badge-result { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
    .badge-safe { background: #dcfce7; color: #166534; }
    .badge-contaminated { background: #fee2e2; color: #991b1b; }
</style>

<script>
    // Limits
    const LIMIT_TVC = 100000;
    const LIMIT_ENTERO = 10000;
    const LIMIT_STAPH = 10000;

    let currentBatch = null;

    function openQCModal(batch) {
        currentBatch = batch;
        document.getElementById('modal-batch-id').textContent = batch.batch_id;
        
        const statusEl = document.getElementById('modal-batch-status');
        statusEl.textContent = batch.status;
        statusEl.style.color = batch.status === 'Safe' ? 'green' : (batch.status === 'Contaminated' ? 'red' : 'orange');
        
        const tbody = document.getElementById('qc-bottle-list');
        tbody.innerHTML = '';

        batch.bottles.forEach((bottle, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="font-weight:600; color:#334155;">${bottle.id}</td>
                <td><input type="number" class="qc-input inp-tvc" data-idx="${index}" oninput="validateRow(${index})"></td>
                <td><input type="number" class="qc-input inp-entero" data-idx="${index}" oninput="validateRow(${index})"></td>
                <td><input type="number" class="qc-input inp-staph" data-idx="${index}" oninput="validateRow(${index})"></td>
                <td id="res-${index}"><span class="badge-result" style="background:#f1f5f9; color:#64748b;">-</span></td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('qcModal').style.display = 'flex';
    }

    function closeQCModal() {
        document.getElementById('qcModal').style.display = 'none';
    }

    // Logic: Validate inputs per row. 
    // If ANY input exceeds limit => Row is Contaminated => Whole Batch is Contaminated
    function validateRow(idx) {
        const tvcInput = document.querySelector(`.inp-tvc[data-idx="${idx}"]`);
        const enteroInput = document.querySelector(`.inp-entero[data-idx="${idx}"]`);
        const staphInput = document.querySelector(`.inp-staph[data-idx="${idx}"]`);
        const resCell = document.getElementById(`res-${idx}`);

        const tvc = parseFloat(tvcInput.value) || 0;
        const entero = parseFloat(enteroInput.value) || 0;
        const staph = parseFloat(staphInput.value) || 0;

        // Visual Validation for Inputs
        setInputColor(tvcInput, tvc, LIMIT_TVC);
        setInputColor(enteroInput, entero, LIMIT_ENTERO);
        setInputColor(staphInput, staph, LIMIT_STAPH);

        // Row Result Logic
        const isFail = (tvc >= LIMIT_TVC) || (entero >= LIMIT_ENTERO) || (staph >= LIMIT_STAPH);

        // Only show result if at least one input has value to avoid clutter
        if (tvcInput.value || enteroInput.value || staphInput.value) {
            if (isFail) {
                resCell.innerHTML = `<span class="badge-result badge-contaminated"><i class="fas fa-exclamation-triangle"></i> Contaminated</span>`;
                // If ONE row fails, batch fails immediately
                updateBatchStatus(true); 
            } else {
                // If this row is safe so far
                resCell.innerHTML = `<span class="badge-result badge-safe"><i class="fas fa-check"></i> Safe</span>`;
                // Re-check ALL rows to see if batch is safe or still failed elsewhere
                checkAllRows(); 
            }
        } else {
            resCell.innerHTML = `<span class="badge-result" style="background:#f1f5f9; color:#64748b;">-</span>`;
        }
    }

    function setInputColor(input, value, limit) {
        if (!input.value) {
            input.className = 'qc-input';
            return;
        }
        if (value >= limit) {
            input.className = 'qc-input danger';
        } else {
            input.className = 'qc-input safe';
        }
    }

    function updateBatchStatus(isFail) {
        const statusEl = document.getElementById('modal-batch-status');
        if (isFail) {
            statusEl.textContent = "CONTAMINATED";
            statusEl.style.color = "#dc2626"; // Red
            statusEl.style.fontWeight = "bold";
        } else {
            statusEl.textContent = "Safe";
            statusEl.style.color = "#16a34a"; // Green
            statusEl.style.fontWeight = "bold";
        }
    }

    function checkAllRows() {
        const rows = document.querySelectorAll('#qc-bottle-list tr');
        let anyFail = false;
        let allFilled = true; // Optional: track if form is complete

        rows.forEach((row, idx) => {
            const tvc = parseFloat(row.querySelector('.inp-tvc').value) || 0;
            const entero = parseFloat(row.querySelector('.inp-entero').value) || 0;
            const staph = parseFloat(row.querySelector('.inp-staph').value) || 0;
            
            // Check limits again for every row
            if ((tvc >= LIMIT_TVC) || (entero >= LIMIT_ENTERO) || (staph >= LIMIT_STAPH)) {
                anyFail = true;
            }
        });

        if (anyFail) {
            updateBatchStatus(true);
        } else {
            updateBatchStatus(false);
        }
    }

    function saveQCResults() {
        const statusText = document.getElementById('modal-batch-status').textContent;
        const isContaminated = statusText === "CONTAMINATED";

        if(statusText === '-' || statusText === 'Pending QC') {
             Swal.fire('Incomplete', 'Please fill in test results or ensure status is determined.', 'warning');
             return;
        }

        Swal.fire({
            title: isContaminated ? 'Mark Batch as Contaminated?' : 'Confirm Safe Batch?',
            text: isContaminated 
                ? 'This will discard the entire batch from inventory due to contamination.' 
                : 'This batch will remain in inventory as Safe.',
            icon: isContaminated ? 'error' : 'success',
            showCancelButton: true,
            confirmButtonColor: isContaminated ? '#d33' : '#10b981',
            confirmButtonText: 'Yes, Submit Result'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here: AJAX call to save would go here
                Swal.fire('Saved!', 'QC Results have been updated.', 'success').then(() => {
                    closeQCModal();
                    // location.reload(); // In real app
                });
            }
        });
    }
</script>
@endsection