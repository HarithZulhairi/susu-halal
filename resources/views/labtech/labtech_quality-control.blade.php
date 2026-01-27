@extends('layouts.labtech')

@section('title', 'Inventory Quality Control')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_quality-control.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{{-- ========================================================= --}}
{{-- DUMMY DATA --}}
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
    <div class="modal-content" style="max-width: 1200px;">
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
                        <th>Total Viable <br><small>(Limit: < 100,000)</small></th>
                        <th>Enterobacteriaceae <br><small>(Limit: < 10,000)</small></th>
                        <th>Staphylococcus <br><small>(Limit: < 10,000)</small></th>
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
    .qc-input.danger { border-color: #ef4444; background-color: #fef2f2; color: #b91c1c; }
    /* REMOVED: .qc-input.safe (Green color removed as requested) */

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
        
        if(batch.status === 'Safe') statusEl.style.color = "#16a34a";
        else if(batch.status === 'Contaminated') statusEl.style.color = "#dc2626";
        else statusEl.style.color = "#d97706";
        
        const tbody = document.getElementById('qc-bottle-list');
        tbody.innerHTML = '';

        batch.bottles.forEach((bottle, index) => {
            const tr = document.createElement('tr');
            tr.setAttribute('data-bottle-id', bottle.id);
            tr.innerHTML = `
                <td style="font-weight:600; color:#334155;">${bottle.id}</td>
                <td><input type="number" class="qc-input inp-tvc" data-idx="${index}" oninput="checkContamination(${index})" placeholder="-"></td>
                <td><input type="number" class="qc-input inp-entero" data-idx="${index}" oninput="checkContamination(${index})" placeholder="-"></td>
                <td><input type="number" class="qc-input inp-staph" data-idx="${index}" oninput="checkContamination(${index})" placeholder="-"></td>
                <td id="res-${index}"><span class="badge-result" style="background:#f1f5f9; color:#64748b;">Pending</span></td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('qcModal').style.display = 'flex';
    }

    function closeQCModal() {
        document.getElementById('qcModal').style.display = 'none';
    }

    // ==========================================
    // SIMPLIFIED VALIDATION LOGIC (FROM PROCESS MILK PAGE)
    // ==========================================
    function checkContamination(idx) {
        const row = document.querySelector(`tr[data-bottle-id]`).parentElement.rows[idx];
        
        // Get values (default to 0 if empty)
        const totalViable = parseFloat(row.querySelector('.inp-tvc').value) || 0;
        const entero = parseFloat(row.querySelector('.inp-entero').value) || 0;
        const staph = parseFloat(row.querySelector('.inp-staph').value) || 0;
        
        const resultCell = document.getElementById(`res-${idx}`);
        
        // Visual feedback for inputs
        setInputColor(row.querySelector('.inp-tvc'), totalViable, LIMIT_TVC);
        setInputColor(row.querySelector('.inp-entero'), entero, LIMIT_ENTERO);
        setInputColor(row.querySelector('.inp-staph'), staph, LIMIT_STAPH);

        // Check conditions
        let isContaminated = false;
        if (totalViable >= LIMIT_TVC || entero >= LIMIT_ENTERO || staph >= LIMIT_STAPH) {
            isContaminated = true;
        }

        // Update UI - IMMEDIATE result display (no waiting for all fields)
        if (isContaminated) {
            resultCell.innerHTML = `<span class="badge-result badge-contaminated"><i class="fas fa-times-circle"></i> Contaminated</span>`;
            resultCell.setAttribute('data-status', 'fail');
        } else {
            // Check if at least one field has a value (not just default 0)
            const hasSomeValue = row.querySelector('.inp-tvc').value !== '' || 
                                row.querySelector('.inp-entero').value !== '' || 
                                row.querySelector('.inp-staph').value !== '';
            
            if (hasSomeValue) {
                resultCell.innerHTML = `<span class="badge-result badge-safe"><i class="fas fa-check-circle"></i> Safe</span>`;
                resultCell.setAttribute('data-status', 'safe');
            } else {
                resultCell.innerHTML = `<span class="badge-result" style="background:#f1f5f9; color:#64748b;">Pending</span>`;
                resultCell.removeAttribute('data-status');
            }
        }

        checkBatchStatus();
    }

    function setInputColor(input, value, limit) {
        // Only apply red if value exceeds limit
        if (value >= limit) {
            input.classList.add('danger');
        } else {
            input.classList.remove('danger');
        }
    }

    function checkBatchStatus() {
        const resultCells = document.querySelectorAll('[id^="res-"]');
        let batchContaminated = false;
        let allRowsHaveStatus = true;
        let allRowsSafe = true;

        resultCells.forEach(cell => {
            const status = cell.getAttribute('data-status');
            
            if (status === 'fail') {
                batchContaminated = true;
                allRowsSafe = false;
            }
            
            // Check if this row has any status
            if (!status) {
                allRowsHaveStatus = false;
            }
        });

        updateBatchStatusUI(batchContaminated, allRowsHaveStatus, allRowsSafe);
    }

    function updateBatchStatusUI(isContaminated, allRowsHaveStatus, allRowsSafe) {
        const statusEl = document.getElementById('modal-batch-status');
        
        if (isContaminated) {
            statusEl.textContent = "CONTAMINATED";
            statusEl.style.color = "#dc2626"; // Red
        } else if (allRowsHaveStatus && allRowsSafe) {
            statusEl.textContent = "Safe";
            statusEl.style.color = "#16a34a"; // Green
        } else {
            statusEl.textContent = "Pending...";
            statusEl.style.color = "#d97706"; // Orange
        }
    }

    function saveQCResults() {
        const statusText = document.getElementById('modal-batch-status').textContent;
        const isContaminated = statusText === "CONTAMINATED";

        if(statusText === 'Pending...' || statusText === '-' || statusText === 'Pending QC') {
            Swal.fire('Incomplete', 'Please enter test results for all bottles.', 'warning');
            return;
        }

        Swal.fire({
            title: isContaminated ? 'Mark Batch as Contaminated?' : 'Confirm Safe Batch?',
            text: isContaminated ? 'Discard batch due to contamination.' : 'Batch remains in inventory.',
            icon: isContaminated ? 'error' : 'success',
            showCancelButton: true,
            confirmButtonColor: isContaminated ? '#d33' : '#10b981',
            confirmButtonText: 'Yes, Submit Result'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Saved!', 'QC Results have been updated.', 'success').then(() => {
                    closeQCModal();
                });
            }
        });
    }
</script>

@endsection