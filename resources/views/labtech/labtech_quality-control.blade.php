@extends('layouts.labtech')

@section('title', 'Inventory Quality Control')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_quality-control.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                    <div class="search-container">
                        <input type="text" id="searchInput" class="search-input" placeholder="Search by bottle code, donor name, location...">
                        <button class="btn btn-search" onclick="performSearch()">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <button class="btn btn-clear" onclick="clearSearch()" style="display:none;">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="records-list">
                <div class="record-header">
                    <button class="sortable-header">POST BOTTLE CODE</button>
                    <button class="sortable-header">LOCATION</button>
                    <button class="sortable-header">EXPIRY DATE</button>
                    <button class="sortable-header">LAST QC DATE</button>
                    <button class="sortable-header">QC STATUS</button>
                    <span>ACTION</span>
                </div>

                <div id="bottlesList">
                    @foreach($postBottles as $bottle)
                        <div class="record-item" data-bottle-code="{{ $bottle->post_bottle_code }}" 
                             data-donor-name="{{ $bottle->milk->donor->dn_FullName ?? 'Unknown' }}" 
                             data-location="{{ $bottle->post_storage_location ?? 'N/A' }}"
                             data-status="{{ $bottle->post_micro_status ?? 'Pending' }}">
                            <div class="milk-donor-info">
                                <div class="milk-icon-wrapper" style="background:#e0f2fe; color:#0369a1;">
                                    <i class="fas fa-box-archive"></i>
                                </div>
                                <div>
                                    <span class="milk-id">{{ $bottle->post_bottle_code }}</span>
                                    <span class="donor-name">{{ $bottle->milk->donor->dn_FullName ?? 'Unknown' }}</span>
                                </div>
                            </div>

                            <div>{{ $bottle->post_storage_location ?? 'N/A' }}</div>
                            <div class="expiry-date">{{ $bottle->post_expiry_date }}</div>
                            <div>{{ $bottle->updated_at ? $bottle->updated_at->format('Y-m-d') : '-' }}</div> 

                            <div class="clinical-status">
                                @if($bottle->post_micro_status == 'NOT CONTAMINATED')
                                    <span class="status-tag status-approved">Not Contaminated</span>
                                @elseif($bottle->post_micro_status == 'CONTAMINATED')
                                    <span class="status-tag status-rejected">Contaminated</span>
                                @else
                                    <span class="status-tag status-pending">Pending QC</span>
                                @endif
                            </div>

                            <div class="actions">
                                @if($bottle->post_micro_status == 'CONTAMINATED')
                                    @if($bottle->is_disposed)
                                        <span class="status-disposed">Disposed</span>
                                    @else
                                        <button class="btn-view btn-dispose-red" onclick='markAsDisposed("{{ $bottle->post_bottle_code }}")' title="Dispose Bottle">
                                            <i class="fas fa-trash-alt" style="color: #dc2626;"></i> Dispose
                                        </button>
                                    @endif
                                @else
                                    <button class="btn-view" onclick='openQCModal(@json($bottle))' title="Perform QC Test">
                                        <i class="fas fa-microscope"></i> Test
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div id="noResultsMessage" style="display:none; padding: 40px; text-align: center; color: #6b7280;">
                    <i class="fas fa-search" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No bottles found</p>
                    <p style="font-size: 14px;">Try adjusting your search terms</p>
                </div>
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
            <h2><i class="fas fa-microscope"></i> Bottle Quality Control</h2>
            <button class="modal-close-btn" onclick="closeQCModal()">Close</button>
        </div>

        <div class="modal-body">
            <div style="display:flex; justify-content:space-between; margin-bottom:20px; background:#f8fafc; padding:15px; border-radius:8px;">
                <div>
                    <small>Bottle ID</small>
                    <h3 style="margin:0; color:#1A5F7A;" id="modal-bottle-id">-</h3>
                </div>
                <div style="text-align:right;">
                    <small>Current Status</small>
                    <h3 style="margin:0;" id="modal-bottle-status">-</h3>
                </div>
            </div>

            <p style="color:#64748b; margin-bottom:15px;">
                <i class="fas fa-info-circle"></i> Enter the colony counts (CFU/ml) for this bottle. 
                If <strong>any</strong> count exceeds the limit, the bottle will be marked 
                <span style="color:#dc2626; font-weight:bold;">CONTAMINATED</span>.
            </p>

            <table class="table-qc">
                <thead>
                    <tr>
                        <th>Test Type</th>
                        <th>Count (CFU/ml)</th>
                        <th>Limit</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody id="qc-bottle-list">
                    <tr>
                        <td style="font-weight:600; color:#334155;">Total Viable Count</td>
                        <td><input type="number" class="qc-input inp-tvc" oninput="checkContamination()" placeholder="-"></td>
                        <td>< 100,000</td>
                        <td id="res-tvc"><span class="badge-result" style="background:#f1f5f9; color:#64748b;">Pending</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; color:#334155;">Enterobacteriaceae</td>
                        <td><input type="number" class="qc-input inp-entero" oninput="checkContamination()" placeholder="-"></td>
                        <td>< 10,000</td>
                        <td id="res-entero"><span class="badge-result" style="background:#f1f5f9; color:#64748b;">Pending</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; color:#334155;">Staphylococcus</td>
                        <td><input type="number" class="qc-input inp-staph" oninput="checkContamination()" placeholder="-"></td>
                        <td>< 10,000</td>
                        <td id="res-staph"><span class="badge-result" style="background:#f1f5f9; color:#64748b;">Pending</span></td>
                    </tr>
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

    .badge-result { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
    .badge-safe { background: #dcfce7; color: #166534; }
    .badge-contaminated { background: #fee2e2; color: #991b1b; }
    
    /* Red Dispose Button - styled like Test button but red */
    .btn-dispose-red {
        color: #dc2626 !important;
    }
    .btn-dispose-red:hover {
        background: #fef2f2 !important;
        color: #b91c1c !important;
    }
    
    /* Disposed Status - styled like Contaminated status */
    .status-disposed {
        background: #d1d5db;
        color: #4b5563;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    /* Search Container */
    .search-container {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .search-input {
        padding: 8px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        width: 300px;
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .btn-clear {
        background: #6b7280;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .btn-clear:hover {
        background: #4b5563;
    }
    
    /* Make modal overlay higher z-index to ensure it's on top */
    .modal-overlay {
        z-index: 9999;
    }
</style>

<script>
    // Limits
    const LIMIT_TVC = 100000;
    const LIMIT_ENTERO = 10000;
    const LIMIT_STAPH = 10000;

    let currentBottle = null;

    // ==========================================
    // SEARCH FUNCTIONALITY
    // ==========================================
    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const bottles = document.querySelectorAll('.record-item');
        const noResultsMessage = document.getElementById('noResultsMessage');
        const clearButton = document.querySelector('.btn-clear');
        
        let visibleCount = 0;

        bottles.forEach(bottle => {
            const bottleCode = bottle.getAttribute('data-bottle-code').toLowerCase();
            const donorName = bottle.getAttribute('data-donor-name').toLowerCase();
            const location = bottle.getAttribute('data-location').toLowerCase();
            const status = bottle.getAttribute('data-status').toLowerCase();
            
            // Search across all fields
            const matches = bottleCode.includes(searchTerm) || 
                          donorName.includes(searchTerm) || 
                          location.includes(searchTerm) ||
                          status.includes(searchTerm);
            
            if (matches || searchTerm === '') {
                bottle.style.display = '';
                visibleCount++;
            } else {
                bottle.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }

        // Show/hide clear button
        if (searchTerm !== '') {
            clearButton.style.display = 'inline-block';
        } else {
            clearButton.style.display = 'none';
        }
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        performSearch();
    }

    // Allow Enter key to trigger search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // Real-time search as user types
            searchInput.addEventListener('input', function() {
                performSearch();
            });
        }
    });

    // ==========================================
    // QC MODAL FUNCTIONS
    // ==========================================
    function openQCModal(bottle) {
        currentBottle = bottle;
        document.getElementById('modal-bottle-id').textContent = bottle.post_bottle_code;
        
        const statusEl = document.getElementById('modal-bottle-status');
        statusEl.textContent = bottle.post_micro_status || 'Pending QC';
        
        if(bottle.post_micro_status === 'NOT CONTAMINATED') statusEl.style.color = "#16a34a";
        else if(bottle.post_micro_status === 'CONTAMINATED') statusEl.style.color = "#dc2626";
        else statusEl.style.color = "#d97706";
        
        // Pre-fill existing values if available
        document.querySelector('.inp-tvc').value = bottle.post_micro_total_viable || '';
        document.querySelector('.inp-entero').value = bottle.post_micro_entero || '';
        document.querySelector('.inp-staph').value = bottle.post_micro_staph || '';
        
        // Trigger validation to show current status
        checkContamination();

        document.getElementById('qcModal').style.display = 'flex';
    }

    function closeQCModal() {
        document.getElementById('qcModal').style.display = 'none';
    }

    function checkContamination() {
        const totalViable = parseFloat(document.querySelector('.inp-tvc').value) || 0;
        const entero = parseFloat(document.querySelector('.inp-entero').value) || 0;
        const staph = parseFloat(document.querySelector('.inp-staph').value) || 0;
        
        setInputColor(document.querySelector('.inp-tvc'), totalViable, LIMIT_TVC, 'res-tvc');
        setInputColor(document.querySelector('.inp-entero'), entero, LIMIT_ENTERO, 'res-entero');
        setInputColor(document.querySelector('.inp-staph'), staph, LIMIT_STAPH, 'res-staph');

        const hasSomeValue = document.querySelector('.inp-tvc').value !== '' || 
                            document.querySelector('.inp-entero').value !== '' || 
                            document.querySelector('.inp-staph').value !== '';

        let isContaminated = false;
        if (totalViable >= LIMIT_TVC || entero >= LIMIT_ENTERO || staph >= LIMIT_STAPH) {
            isContaminated = true;
        }

        updateBottleStatusUI(isContaminated, hasSomeValue);
    }

    function setInputColor(input, value, limit, resultCellId) {
        const resultCell = document.getElementById(resultCellId);
        
        if (value >= limit && input.value !== '') {
            input.classList.add('danger');
            resultCell.innerHTML = `<span class="badge-result badge-contaminated"><i class="fas fa-times-circle"></i> Fail</span>`;
        } else if (input.value !== '') {
            input.classList.remove('danger');
            resultCell.innerHTML = `<span class="badge-result badge-safe"><i class="fas fa-check-circle"></i> Pass</span>`;
        } else {
            input.classList.remove('danger');
            resultCell.innerHTML = `<span class="badge-result" style="background:#f1f5f9; color:#64748b;">Pending</span>`;
        }
    }

    function updateBottleStatusUI(isContaminated, hasSomeValue) {
        const statusEl = document.getElementById('modal-bottle-status');
        
        if (isContaminated && hasSomeValue) {
            statusEl.textContent = "CONTAMINATED";
            statusEl.style.color = "#dc2626";
        } else if (hasSomeValue) {
            statusEl.textContent = "NOT CONTAMINATED";
            statusEl.style.color = "#16a34a";
        } else {
            statusEl.textContent = "Pending...";
            statusEl.style.color = "#d97706";
        }
    }

    function saveQCResults() {
        const statusText = document.getElementById('modal-bottle-status').textContent;
        const isContaminated = statusText === "CONTAMINATED";

        const totalViable = document.querySelector('.inp-tvc').value;
        const entero = document.querySelector('.inp-entero').value;
        const staph = document.querySelector('.inp-staph').value;

        if(!totalViable || !entero || !staph) {
            Swal.fire('Incomplete', 'Please enter all test results.', 'warning');
            closeQCModal();
            return;
        }

        // CLOSE THE QC MODAL IMMEDIATELY BEFORE SHOWING CONFIRMATION
        closeQCModal();

        Swal.fire({
            title: isContaminated ? 'Mark Bottle as Contaminated?' : 'Confirm Safe Bottle?',
            text: isContaminated ? 'This bottle will be marked for disposal.' : 'Bottle will remain in inventory.',
            icon: isContaminated ? 'error' : 'success',
            showCancelButton: true,
            confirmButtonColor: isContaminated ? '#d33' : '#10b981',
            confirmButtonText: 'Yes, Submit Result'
        }).then((result) => {
            if (result.isConfirmed) {
                const bottleData = {
                    bottles: [{
                        bottle_id: currentBottle.post_bottle_code,
                        total_viable: parseFloat(totalViable),
                        entero: parseFloat(entero),
                        staph: parseFloat(staph),
                        result: isContaminated ? 'CONTAMINATED' : 'NOT CONTAMINATED'
                    }]
                };

                fetch('{{ route("labtech.updateMicrobiologyResults") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(bottleData)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        if(isContaminated) {
                            // Prompt to dispose contaminated bottle
                            Swal.fire({
                                title: '⚠️ Bottle Marked as Contaminated',
                                html: '<div style="font-size: 16px; color: #374151; margin-top: 10px;">Please dispose of this contaminated bottle immediately for safety.</div>',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc2626',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: '<i class="fas fa-trash-alt"></i> Mark as Disposed Now',
                                cancelButtonText: 'Dispose Later'
                            }).then((disposeResult) => {
                                if (disposeResult.isConfirmed) {
                                    markAsDisposed(currentBottle.post_bottle_code);
                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Saved!',
                                text: 'QC Results have been updated successfully.',
                                icon: 'success',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    } else {
                        Swal.fire('Error', data.message || 'Failed to save results.', 'error');
                        // Reopen modal if save failed
                        openQCModal(currentBottle);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An error occurred while saving.', 'error');
                    // Reopen modal if request failed
                    openQCModal(currentBottle);
                });
            } else {
                // If user cancels, reopen the QC modal
                openQCModal(currentBottle);
            }
        });
    }

    function markAsDisposed(bottleCode) {
        // Show loading state
        Swal.fire({
            title: 'Marking as Disposed...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("labtech.markBottleDisposed") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ bottle_code: bottleCode })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    title: '✓ Disposed!',
                    text: 'Bottle has been marked as disposed successfully.',
                    icon: 'success',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', 'Failed to mark bottle as disposed.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'An error occurred.', 'error');
        });
    }
</script>

@endsection