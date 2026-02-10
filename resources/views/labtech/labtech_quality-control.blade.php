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

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="switchTab('active')">
                    <i class="fas fa-flask"></i> Active Bottles 
                    <span class="tab-count">{{ $activeBottles->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('disposed')">
                    <i class="fas fa-trash-alt"></i> Disposed Bottles 
                    <span class="tab-count">{{ $disposedBottles->count() }}</span>
                </button>
            </div>

            <!-- Active Bottles Tab -->
            <div id="activeTab" class="tab-content active">
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
                        @forelse($activeBottles as $bottle)
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
                                        <button class="btn-view btn-dispose-red" onclick='markAsDisposed("{{ $bottle->post_bottle_code }}")' title="Dispose Bottle">
                                            <i class="fas fa-trash-alt" style="color: #dc2626;"></i> Dispose
                                        </button>
                                    @else
                                        <button class="btn-view" onclick='openQCModal(@json($bottle))' title="Perform QC Test">
                                            <i class="fas fa-microscope"></i> Test
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="padding: 40px; text-align: center; color: #6b7280;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No active bottles</p>
                                <p style="font-size: 14px;">All bottles have been tested or disposed</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- No Results Message (for search) -->
                    <div id="noResultsMessage" style="display:none; padding: 40px; text-align: center; color: #6b7280;">
                        <i class="fas fa-search" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No bottles found</p>
                        <p style="font-size: 14px;">Try adjusting your search terms</p>
                    </div>

                    <!-- Pagination for Active Bottles -->
                    <div id="activePaginationControls" class="pagination-controls"></div>
                </div>
            </div>

            <!-- Disposed Bottles Tab -->
            <div id="disposedTab" class="tab-content">
                <div class="records-list">
                    <div class="record-header">
                        <button class="sortable-header">POST BOTTLE CODE</button>
                        <button class="sortable-header">LOCATION</button>
                        <button class="sortable-header">EXPIRY DATE</button>
                        <button class="sortable-header">DISPOSED DATE</button>
                        <button class="sortable-header">QC STATUS</button>
                        <span>DETAILS</span>
                    </div>

                    <div id="disposedBottlesList">
                        @forelse($disposedBottles as $bottle)
                            <div class="record-item disposed-item" data-bottle-code="{{ $bottle->post_bottle_code }}" 
                                 data-donor-name="{{ $bottle->milk->donor->dn_FullName ?? 'Unknown' }}" 
                                 data-location="{{ $bottle->post_storage_location ?? 'N/A' }}"
                                 data-status="{{ $bottle->post_micro_status ?? 'Pending' }}">
                                <div class="milk-donor-info">
                                    <div class="milk-icon-wrapper" style="background:#fee2e2; color:#991b1b;">
                                        <i class="fas fa-trash-alt"></i>
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
                                    <button class="btn-view" onclick='viewDisposedDetails(@json($bottle))' title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 40px; text-align: center; color: #6b7280;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No disposed bottles</p>
                                <p style="font-size: 14px;">No bottles have been disposed yet</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination for Disposed Bottles -->
                    <div id="disposedPaginationControls" class="pagination-controls"></div>
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

{{-- ========================================================= --}}
{{-- DISPOSED BOTTLE DETAILS MODAL --}}
{{-- ========================================================= --}}
<div id="disposedModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2><i class="fas fa-info-circle"></i> Disposed Bottle Details</h2>
            <button class="modal-close-btn" onclick="closeDisposedModal()">Close</button>
        </div>

        <div class="modal-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Bottle Code</label>
                    <p id="disposed-bottle-code">-</p>
                </div>
                <div class="info-item">
                    <label>Donor Name</label>
                    <p id="disposed-donor-name">-</p>
                </div>
                <div class="info-item">
                    <label>Storage Location</label>
                    <p id="disposed-location">-</p>
                </div>
                <div class="info-item">
                    <label>Expiry Date</label>
                    <p id="disposed-expiry">-</p>
                </div>
                <div class="info-item">
                    <label>Disposed Date</label>
                    <p id="disposed-date">-</p>
                </div>
                <div class="info-item">
                    <label>QC Status</label>
                    <p id="disposed-status">-</p>
                </div>
            </div>

            <div style="margin-top: 20px;" id="qc-results-section">
                <h3 style="margin-bottom: 15px; color: #334155;">Microbiology Results</h3>
                <table class="table-qc">
                    <thead>
                        <tr>
                            <th>Test Type</th>
                            <th>Count (CFU/ml)</th>
                            <th>Limit</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight:600; color:#334155;">Total Viable Count</td>
                            <td id="disposed-tvc" style="text-align: center; font-weight: 600;">-</td>
                            <td>< 100,000</td>
                            <td id="disposed-tvc-result">-</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600; color:#334155;">Enterobacteriaceae</td>
                            <td id="disposed-entero" style="text-align: center; font-weight: 600;">-</td>
                            <td>< 10,000</td>
                            <td id="disposed-entero-result">-</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600; color:#334155;">Staphylococcus</td>
                            <td id="disposed-staph" style="text-align: center; font-weight: 600;">-</td>
                            <td>< 10,000</td>
                            <td id="disposed-staph-result">-</td>
                        </tr>
                    </tbody>
                </table>
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
    
    /* Red Dispose Button */
    .btn-dispose-red {
        color: #dc2626 !important;
    }
    .btn-dispose-red:hover {
        background: #fef2f2 !important;
        color: #b91c1c !important;
    }
    
    /* Disposed Status */
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
    
    /* Modal overlay */
    .modal-overlay {
        z-index: 9999;
    }

    /* Tab Navigation */
    .tab-navigation {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e5e7eb;
        padding: 0 20px;
        background: #f9fafb;
    }

    .tab-btn {
        padding: 14px 24px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        top: 2px;
    }

    .tab-btn:hover {
        color: #1A5F7A;
        background: #f3f4f6;
    }

    .tab-btn.active {
        color: #1A5F7A;
        border-bottom-color: #1A5F7A;
        background: white;
    }

    .tab-count {
        background: #e5e7eb;
        color: #4b5563;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .tab-btn.active .tab-count {
        background: #1A5F7A;
        color: white;
    }

    /* Tab Content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Disposed Item Styling */
    .disposed-item {
        opacity: 0.8;
        background: #fafafa;
    }

    .disposed-item:hover {
        opacity: 1;
    }

    /* Pagination Controls Styling - Matching Milk Records */
    .pagination-controls {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .pagination-controls .page-btn {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        background: white;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        min-width: 38px;
        text-align: center;
        transition: all 0.2s;
    }

    .pagination-controls .page-btn:hover:not(:disabled):not(.active) {
        background: #f3f4f6;
        border-color: #9ca3af;
        color: #374151;
    }

    .pagination-controls .page-btn.active {
        background: #1A5F7A;
        border-color: #1A5F7A;
        color: white;
        font-weight: 600;
        cursor: default;
    }

    .pagination-controls .page-btn:disabled {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #d1d5db;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Info Grid for Disposed Details Modal */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-item label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .info-item p {
        font-size: 16px;
        color: #1f2937;
        font-weight: 600;
        margin: 0;
    }
</style>

<script>
    // Limits
    const LIMIT_TVC = 100000;
    const LIMIT_ENTERO = 10000;
    const LIMIT_STAPH = 10000;

    let currentBottle = null;
    let currentTab = '{{ request()->get("tab", "active") }}';

    // ==========================================
    // TAB SWITCHING
    // ==========================================
    function switchTab(tab) {
        currentTab = tab;
        
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.target.closest('.tab-btn').classList.add('active');
        
        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        if (tab === 'active') {
            document.getElementById('activeTab').classList.add('active');
        } else {
            document.getElementById('disposedTab').classList.add('active');
        }

        // Clear search when switching tabs
        clearSearch();

        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    }

    // Initialize correct tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (currentTab === 'disposed') {
            document.querySelectorAll('.tab-btn')[1].click();
        }
    });

    // ==========================================
    // SEARCH FUNCTIONALITY
    // ==========================================
    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const bottlesList = currentTab === 'active' ? 'bottlesList' : 'disposedBottlesList';
        const bottles = document.querySelectorAll(`#${bottlesList} .record-item`);
        const noResultsMessage = document.getElementById('noResultsMessage');
        const clearButton = document.querySelector('.btn-clear');
        
        let visibleCount = 0;

        bottles.forEach(bottle => {
            const bottleCode = bottle.getAttribute('data-bottle-code').toLowerCase();
            const donorName = bottle.getAttribute('data-donor-name').toLowerCase();
            const location = bottle.getAttribute('data-location').toLowerCase();
            const status = bottle.getAttribute('data-status').toLowerCase();
            
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

        // Show/hide no results message (only for active tab)
        if (currentTab === 'active') {
            if (visibleCount === 0 && searchTerm !== '') {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
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
                        openQCModal(currentBottle);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An error occurred while saving.', 'error');
                    openQCModal(currentBottle);
                });
            } else {
                openQCModal(currentBottle);
            }
        });
    }

    function markAsDisposed(bottleCode) {
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

    // ==========================================
    // DISPOSED BOTTLE DETAILS MODAL
    // ==========================================
    function viewDisposedDetails(bottle) {
        document.getElementById('disposed-bottle-code').textContent = bottle.post_bottle_code;
        document.getElementById('disposed-donor-name').textContent = bottle.milk?.donor?.dn_FullName || 'Unknown';
        document.getElementById('disposed-location').textContent = bottle.post_storage_location || 'N/A';
        document.getElementById('disposed-expiry').textContent = bottle.post_expiry_date || 'N/A';
        document.getElementById('disposed-date').textContent = bottle.updated_at ? new Date(bottle.updated_at).toLocaleDateString() : 'N/A';
        
        // Status
        const statusEl = document.getElementById('disposed-status');
        if (bottle.post_micro_status === 'NOT CONTAMINATED') {
            statusEl.innerHTML = '<span class="status-tag status-approved">Not Contaminated</span>';
        } else if (bottle.post_micro_status === 'CONTAMINATED') {
            statusEl.innerHTML = '<span class="status-tag status-rejected">Contaminated</span>';
        } else {
            statusEl.innerHTML = '<span class="status-tag status-pending">Pending QC</span>';
        }

        // QC Results
        if (bottle.post_micro_total_viable || bottle.post_micro_entero || bottle.post_micro_staph) {
            document.getElementById('disposed-tvc').textContent = bottle.post_micro_total_viable || 'N/A';
            document.getElementById('disposed-entero').textContent = bottle.post_micro_entero || 'N/A';
            document.getElementById('disposed-staph').textContent = bottle.post_micro_staph || 'N/A';

            // Results
            const tvcResult = (bottle.post_micro_total_viable || 0) >= LIMIT_TVC;
            const enteroResult = (bottle.post_micro_entero || 0) >= LIMIT_ENTERO;
            const staphResult = (bottle.post_micro_staph || 0) >= LIMIT_STAPH;

            document.getElementById('disposed-tvc-result').innerHTML = tvcResult 
                ? '<span class="badge-result badge-contaminated"><i class="fas fa-times-circle"></i> Fail</span>'
                : '<span class="badge-result badge-safe"><i class="fas fa-check-circle"></i> Pass</span>';
            
            document.getElementById('disposed-entero-result').innerHTML = enteroResult 
                ? '<span class="badge-result badge-contaminated"><i class="fas fa-times-circle"></i> Fail</span>'
                : '<span class="badge-result badge-safe"><i class="fas fa-check-circle"></i> Pass</span>';
            
            document.getElementById('disposed-staph-result').innerHTML = staphResult 
                ? '<span class="badge-result badge-contaminated"><i class="fas fa-times-circle"></i> Fail</span>'
                : '<span class="badge-result badge-safe"><i class="fas fa-check-circle"></i> Pass</span>';
            
            document.getElementById('qc-results-section').style.display = 'block';
        } else {
            document.getElementById('qc-results-section').style.display = 'none';
        }

        document.getElementById('disposedModal').style.display = 'flex';
    }

    function closeDisposedModal() {
        document.getElementById('disposedModal').style.display = 'none';
    }
    // ==========================================
    // CLIENT-SIDE PAGINATION (matching milk records)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        // Setup pagination for Active Bottles
        setupPagination('activeTab', 'bottlesList', 'activePaginationControls');
        
        // Setup pagination for Disposed Bottles
        setupPagination('disposedTab', 'disposedBottlesList', 'disposedPaginationControls');
    });

    function setupPagination(tabId, listId, controlsId) {
        const tab = document.getElementById(tabId);
        const listContainer = document.getElementById(listId);
        const controls = document.getElementById(controlsId);
        
        if (!tab || !listContainer || !controls) return;

        const rowsSelector = '.record-item';
        const perPage = 10;
        let currentPage = 1;

        function getRows() {
            // Only get visible rows (not hidden by search)
            return Array.from(listContainer.querySelectorAll(rowsSelector))
                .filter(r => r.style.display !== 'none');
        }

        function renderControls(rows) {
            const totalPages = Math.max(1, Math.ceil(rows.length / perPage));
            controls.innerHTML = '';

            const prev = document.createElement('button');
            prev.className = 'page-btn';
            prev.textContent = '‹ Prev';
            prev.disabled = currentPage <= 1;
            prev.addEventListener('click', () => renderPage(currentPage - 1));
            controls.appendChild(prev);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = 'page-btn';
                btn.textContent = String(i);
                if (i === currentPage) btn.classList.add('active');
                btn.addEventListener('click', () => renderPage(i));
                controls.appendChild(btn);
            }

            const next = document.createElement('button');
            next.className = 'page-btn';
            next.textContent = 'Next ›';
            next.disabled = currentPage >= totalPages;
            next.addEventListener('click', () => renderPage(currentPage + 1));
            controls.appendChild(next);
        }

        function renderPage(page) {
            const rows = getRows();
            const totalPages = Math.max(1, Math.ceil(rows.length / perPage));
            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPage = page;

            // Hide all rows first
            Array.from(listContainer.querySelectorAll(rowsSelector)).forEach(r => {
                // Only hide if not already hidden by search
                if (r.style.display !== 'none') {
                    r.classList.add('pagination-hidden');
                }
            });

            const start = (currentPage - 1) * perPage;
            const pageRows = rows.slice(start, start + perPage);
            pageRows.forEach(r => r.classList.remove('pagination-hidden'));

            renderControls(rows);
        }

        // Expose rebuild function for search functionality
        window[`__rebuild${tabId}Pagination`] = function(page) {
            const rows = getRows();
            const totalPages = Math.max(1, Math.ceil(rows.length / perPage));
            if (typeof page === 'number' && page >= 1) {
                currentPage = page > totalPages ? totalPages : page;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            renderPage(currentPage);
        };

        // Initial render
        renderPage(1);
    }

    // Update performSearch to work with pagination
    const originalPerformSearch = performSearch;
    performSearch = function() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const bottlesList = currentTab === 'active' ? 'bottlesList' : 'disposedBottlesList';
        const bottles = document.querySelectorAll(`#${bottlesList} .record-item`);
        const noResultsMessage = document.getElementById('noResultsMessage');
        const clearButton = document.querySelector('.btn-clear');
        
        let visibleCount = 0;

        bottles.forEach(bottle => {
            const bottleCode = bottle.getAttribute('data-bottle-code').toLowerCase();
            const donorName = bottle.getAttribute('data-donor-name').toLowerCase();
            const location = bottle.getAttribute('data-location').toLowerCase();
            const status = bottle.getAttribute('data-status').toLowerCase();
            
            const matches = bottleCode.includes(searchTerm) || 
                          donorName.includes(searchTerm) || 
                          location.includes(searchTerm) ||
                          status.includes(searchTerm);
            
            if (matches || searchTerm === '') {
                bottle.style.display = '';
                bottle.classList.remove('pagination-hidden');
                visibleCount++;
            } else {
                bottle.style.display = 'none';
                bottle.classList.add('pagination-hidden');
            }
        });

        // Show/hide no results message (only for active tab)
        if (currentTab === 'active') {
            if (visibleCount === 0 && searchTerm !== '') {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }

        // Show/hide clear button
        if (searchTerm !== '') {
            clearButton.style.display = 'inline-block';
        } else {
            clearButton.style.display = 'none';
        }

        // Rebuild pagination for current tab
        if (currentTab === 'active') {
            if (window.__rebuildactiveTabPagination) window.__rebuildactiveTabPagination(1);
        } else {
            if (window.__rebuilddisposedTabPagination) window.__rebuilddisposedTabPagination(1);
        }
    };

    // Update switchTab to rebuild pagination
    const originalSwitchTab = switchTab;
    switchTab = function(tab) {
        currentTab = tab;
        
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.target.closest('.tab-btn').classList.add('active');
        
        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        if (tab === 'active') {
            document.getElementById('activeTab').classList.add('active');
            if (window.__rebuildactiveTabPagination) window.__rebuildactiveTabPagination();
        } else {
            document.getElementById('disposedTab').classList.add('active');
            if (window.__rebuilddisposedTabPagination) window.__rebuilddisposedTabPagination();
        }

        // Clear search when switching tabs
        clearSearch();

        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    };
</script>

<style>
    /* Hide items that are hidden by pagination */
    .record-item.pagination-hidden {
        display: none !important;
    }
</style>

@endsection