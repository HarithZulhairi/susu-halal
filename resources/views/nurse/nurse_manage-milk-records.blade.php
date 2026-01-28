@extends('layouts.nurse')

@section('title', 'Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/nurse_manage-milk-records.css') }}">
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
                    <button class="btn btn-search"><i class="fas fa-search"></i> Search &amp; Filter</button>

                    <!-- OPEN MODAL BTN -->
                    <button class="btn btn-add-records" id="openAddRecord">
                        <i class="fas fa-plus"></i> Add Milk
                    </button>

                </div>

            </div>

            <!-- FILTER / SEARCH PANEL (inline, hidden by default) -->
            <div id="filterPanel" class="filter-panel" role="region" aria-label="Search and filters">
                <form id="filterForm" method="GET" action="{{ url()->current() }}" autocomplete="off">
                    <input id="searchInput" name="searchInput" value="{{ request('searchInput') }}" class="form-control" type="search" placeholder="Search by Donor name or Milk ID">

                    <select id="filterStatus" name="filterStatus" class="form-control">
                        <option value="">All Clinical Status</option>
                        
                        <option value="Not Yet Started" {{ request('filterStatus') == 'Not Yet Started' ? 'selected' : '' }}>
                            Not Yet Started
                        </option>

                        <option value="Labelling Completed" {{ request('filterStatus') == 'Labelling Completed' ? 'selected' : '' }}>
                            Labelling Completed
                        </option>

                        <option value="Thawing Completed" {{ request('filterStatus') == 'Thawing Completed' ? 'selected' : '' }}>
                            Thawing Completed
                        </option>

                        <option value="Pasteurization Completed" {{ request('filterStatus') == 'Pasteurization Completed' ? 'selected' : '' }}>
                            Pasteurization Completed
                        </option>

                        <option value="Microbiology Completed" {{ request('filterStatus') == 'Microbiology Completed' ? 'selected' : '' }}>
                            Microbiology Completed
                        </option>

                        <option value="Storage Completed" {{ request('filterStatus') == 'Storage Completed' ? 'selected' : '' }}>
                            Storage Completed
                        </option>
                    </select>

                    <div style="display:flex; gap:8px;">
                        <input id="volumeMin" name="volumeMin" value="{{ request('volumeMin') }}" class="form-control" type="number" min="0" placeholder="Min mL">
                        <input id="volumeMax" name="volumeMax" value="{{ request('volumeMax') }}" class="form-control" type="number" min="0" placeholder="Max mL">
                    </div>

                    <select id="filterShariah" name="filterShariah" class="form-control">
                        <option value="">All Shariah</option>
                        <option value="Not Yet Reviewed" {{ request('filterShariah') == 'Not Yet Reviewed' ? 'selected' : '' }}>Not Yet Reviewed</option>
                        <option value="Approved" {{ request('filterShariah') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('filterShariah') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <div class="filter-actions">
                        <button id="applyFilters" class="btn" type="submit">Apply</button>
                        <button id="clearFilters" class="btn" type="button" onclick="window.location='{{ url()->current() }}'">Clear</button>
                    </div>
                </form>
            </div>

            <div class="records-list">
                <div class="record-header">
                    <button class="sortable-header" data-key="donor" title="Sort by Donor">
                        MILK DONOR <span class="sort-indicator"></span>
                    </button>
                    <button class="sortable-header" data-key="status" title="Sort by Clinical Status">
                        CLINICAL STATUS <span class="sort-indicator"></span>
                    </button>
                    <button class="sortable-header" data-key="volume" title="Sort by Volume">
                        VOLUME <span class="sort-indicator"></span>
                    </button>
                    <button class="sortable-header" data-key="shariah" title="Sort by Shariah Approval">
                        SHARIAH APPROVAL <span class="sort-indicator"></span>
                    </button>
                    <span>ACTIONS</span>
                </div>

                @forelse($milks as $milk)
                    <div class="record-item" 
                            data-milk-id="{{ $milk->milk_ID }}"
                            data-name="{{ strtolower($milk->donor?->dn_FullName ?? '') }}"
                            data-status="{{ strtolower($milk->milk_Status ?? 'not yet started') }}" 
                            data-expiry="{{ $milk->milk_expiryDate }}"
                            data-shariah="{{ strtolower($milk->milk_shariahApproval ?? 'not yet reviewed') }}"
                            data-shariah-date="{{ $milk->milk_shariahApprovalDate ?? '' }}"
                            data-shariah-remarks="{{ $milk->milk_shariahRemarks ?? '' }}">
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
                            @php
                                $rawStatus = $milk->milk_Status ?? 'Not Yet Started';
                                $fullCls = strtolower(str_replace(' ', '-', $rawStatus));
                                $baseCls = strtolower(explode(' ', $rawStatus)[0] ?? 'pending');
                            @endphp
                            <a href="{{ route('labtech.labtech_process-milk', $milk->milk_ID) }}"
                            class="status-tag status-{{ $baseCls }} status-{{ $fullCls }} status-clickable"
                            title="Click to continue processing this milk">
                                {{ ucfirst($rawStatus) }}
                            </a>
                        </div>

                        <div class="volume-data">{{ $milk->milk_volume }} mL</div>

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
                            @php
                                $payload = [
                                    'milkId' => $milk->formatted_id,
                                    'donorName' => $milk->donor?->dn_FullName ?? 'N/A',
                                    'status' => ucfirst($milk->milk_Status ?? 'Not Yet Started'),
                                    'volume' => $milk->milk_volume . ' mL',
                                    
                                    // Shariah Info
                                    'shariah' => is_null($milk->milk_shariahApproval) ? 'Not Yet Reviewed' : ($milk->milk_shariahApproval ? 'Approved' : 'Rejected'),
                                    'shariahRemarks' => $milk->milk_shariahRemarks ?? '-',
                                    'shariahApprovalDate' => $milk->milk_shariahApprovalDate ? \Carbon\Carbon::parse($milk->milk_shariahApprovalDate)->format('M d, Y') : '-',
                                    
                                    // NEW: Pass the Bottle Collections
                                    'preBottles' => $milk->preBottles,
                                    'postBottles' => $milk->postBottles
                                ];
                            @endphp
                            <button class="btn-view" title="View" data-payload='@json($payload)'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            
                        </div>
                    </div>
                @empty
                    <div class="record-item text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No milk records yet. Add one to begin!</p>
                    </div>
                @endforelse
                
                <!-- Pagination controls (client-side) -->
                <div id="paginationControls" class="pagination-controls"></div>
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
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <p><strong>Milk ID:</strong> <span id="view-milk-id" style="color: #1A5F7A; font-weight: bold;"></span></p>
                    <p><strong>Donor:</strong> <span id="view-donor-name"></span></p>
                    <p><strong>Volume:</strong> <span id="view-volume"></span></p>
                    <p><strong>Status:</strong> <span id="view-status"></span></p>
                </div>
            </div>

            <div class="view-tabs" style="display: flex; gap: 10px; border-bottom: 1px solid #e2e8f0; margin-bottom: 15px;">
                <button class="view-tab-btn active" onclick="switchViewTab('raw')">Raw Milk (Stage 1-2)</button>
                <button class="view-tab-btn" onclick="switchViewTab('processed')">Processed (Stage 3-5)</button>
                <button class="view-tab-btn" onclick="switchViewTab('qc')">Quality Control</button>
            </div>

            <div id="view-tab-raw" class="view-tab-content active" style="padding-top: 10px;">
                <h3>Pre-Pasteurization Bottles</h3>
                <table class="view-table" style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                    <thead style="background: #f1f5f9;">
                        <tr>
                            <th style="padding: 8px; text-align: left;">Code</th>
                            <th style="padding: 8px; text-align: left;">Volume (mL)</th>
                            <th style="padding: 8px; text-align: left;">Thawed?</th>
                        </tr>
                    </thead>
                    <tbody id="view-pre-bottles-list">
                        </tbody>
                </table>
            </div>

            <div id="view-tab-processed" class="view-tab-content" style="padding-top: 10px; display: none;">
                <h3>Pasteurized Bottles</h3>
                <table class="view-table" style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                    <thead style="background: #f1f5f9;">
                        <tr>
                            <th style="padding: 8px; text-align: left;">Code</th>
                            <th style="padding: 8px; text-align: left;">Volume (mL)</th>
                            <th style="padding: 8px; text-align: left;">Expiry</th>
                            <th style="padding: 8px; text-align: left;">Micro Result</th>
                            <th style="padding: 8px; text-align: left;">Storage</th>
                        </tr>
                    </thead>
                    <tbody id="view-post-bottles-list">
                        </tbody>
                </table>
            </div>

            <div id="view-tab-qc" class="view-tab-content" style="display: none; padding-top: 10px;">
                <h3>Quality Control</h3>
                <p><strong>Shariah Approval:</strong> <span id="view-shariah"></span></p>
                <p><strong>Approval Date:</strong> <span id="view-shariah-date"></span></p>
                <p><strong>Remarks:</strong> <span id="view-shariah-remarks"></span></p>
            </div>
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

    // Sorting: single-column toggle (asc/desc). Default: milk id desc
    (function setupSorting() {
        const listContainer = document.querySelector('.records-list');
        if (!listContainer) return;

        const headerButtons = Array.from(document.querySelectorAll('.record-header .sortable-header'));

        // helper: get value by key from a .record-item
        function getValueForKey(row, key) {
            if (key === 'donor') {
                return row.querySelector('.donor-name')?.textContent?.trim() || '';
            }
            if (key === 'status') {
                return row.querySelector('.clinical-status .status-tag')?.textContent?.trim() || '';
            }
            if (key === 'volume') {
                const v = row.querySelector('.volume-data')?.textContent || '';
                // extract numeric
                const m = v.match(/([0-9]+(\.[0-9]+)?)/);
                return m ? parseFloat(m[0]) : 0;
            }
            if (key === 'expiry') {
                const text = row.querySelector('.expiry-date')?.textContent?.trim() || '';
                // Try parse 'Mon dd, YYYY' format
                const d = Date.parse(text);
                return isNaN(d) ? (text === '-' ? Infinity : 0) : d;
            }
            if (key === 'shariah') {
                return row.querySelector('.shariah-status .status-tag')?.textContent?.trim() || '';
            }
            if (key === 'milkId') {
                const id = row.dataset.milkId;
                return id ? Number(id) : 0;
            }
            return '';
        }

        // perform sort and re-append rows
        function sortBy(key, direction = 'desc') {
            const rows = Array.from(listContainer.querySelectorAll('.record-item'));
            const multiplier = direction === 'asc' ? 1 : -1;

            rows.sort((a, b) => {
                const va = getValueForKey(a, key);
                const vb = getValueForKey(b, key);

                // numeric comparison when values are numbers
                if (typeof va === 'number' && typeof vb === 'number') {
                    return (va - vb) * multiplier;
                }

                // date numbers
                if (typeof va === 'number' && typeof vb === 'number') {
                    return (va - vb) * multiplier;
                }

                const sa = String(va).toLowerCase();
                const sb = String(vb).toLowerCase();
                if (sa < sb) return -1 * multiplier;
                if (sa > sb) return 1 * multiplier;
                return 0;
            });

            // re-append in sorted order
            const listInner = listContainer; // .records-list contains header plus items; ensure we append after header
            // find the element after which items start: the header div
            const header = listInner.querySelector('.record-header');
            rows.forEach(r => header.after(r));
        }

        // Track current active header and direction
        let activeKey = 'milkId';
        let activeDir = 'desc';

        function clearIndicators() {
            headerButtons.forEach(btn => {
                btn.classList.remove('sorted-asc', 'sorted-desc');
                btn.querySelector('.sort-indicator').textContent = '';
            });
        }

        headerButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.key;
                if (!key) return;

                if (activeKey === key) {
                    activeDir = activeDir === 'asc' ? 'desc' : 'asc';
                } else {
                    activeKey = key;
                    activeDir = 'asc';
                }

                clearIndicators();
                btn.classList.add(activeDir === 'asc' ? 'sorted-asc' : 'sorted-desc');
                btn.querySelector('.sort-indicator').textContent = activeDir === 'asc' ? '▲' : '▼';

                sortBy(activeKey, activeDir);
            });
        });

        // initial default: milkId desc
        // If there are rows, ensure milkId sort applied
        if (document.querySelectorAll('.record-item').length > 0) {
            // Create a fake header button for milkId to set indicator
            clearIndicators();
            activeKey = 'milkId';
            activeDir = 'desc';
            // no header for milkId; show descending indicator on first sortable (visual hint)
            if (headerButtons[0]) {
                headerButtons[0].querySelector('.sort-indicator').textContent = '▼';
            }
            sortBy(activeKey, activeDir);
        }
    })();

    // Pagination: client-side paging (10 items per page)
    (function setupPagination() {
        const listContainer = document.querySelector('.records-list');
        const controls = document.getElementById('paginationControls');
        if (!listContainer || !controls) return;

        const rowsSelector = '.record-item';
        const perPage = 10;
        let currentPage = 1;

        function getRows() {
            // Only include rows that are not filtered-out
            return Array.from(listContainer.querySelectorAll(rowsSelector)).filter(r => r.dataset.filtered === undefined || r.dataset.filtered === '1');
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

            rows.forEach(r => r.style.display = 'none');

            const start = (currentPage - 1) * perPage;
            const pageRows = rows.slice(start, start + perPage);
            pageRows.forEach(r => r.style.display = '');

            renderControls(rows);
        }

        // expose a rebuild function so other handlers (delete, sort) can refresh paging
        // Accept optional `page` to reset to a specific page (e.g. 1 after applying filters)
        window.__rebuildPagination = function (page) {
            const rows = getRows();
            const totalPages = Math.max(1, Math.ceil(rows.length / perPage));
            if (typeof page === 'number' && page >= 1) {
                currentPage = page > totalPages ? totalPages : page;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            renderPage(currentPage);
        };

        // initial render
        renderPage(1);
    })();

    // Filter / Search logic (inline panel)
    (function setupFilteringInline() {
        const panel = document.getElementById('filterPanel');
        const btnSearch = document.querySelector('.btn-search');
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const volumeMin = document.getElementById('volumeMin');
        const volumeMax = document.getElementById('volumeMax');
        const expiryFrom = document.getElementById('expiryFrom');
        const expiryTo = document.getElementById('expiryTo');
        const filterShariah = document.getElementById('filterShariah');
        const applyBtn = document.getElementById('applyFilters');
        const clearBtn = document.getElementById('clearFilters');
        const listContainer = document.querySelector('.records-list');

        if (!panel || !btnSearch || !searchInput) return;

        function togglePanel() {
            panel.classList.toggle('active');
            if (panel.classList.contains('active')) searchInput.focus();
        }

        btnSearch.addEventListener('click', togglePanel);

        // For server-side filtering: submit the form normally (GET) and let
        // the controller return filtered results. Keep the panel toggle.
        form.addEventListener('submit', function(e) {
            // allow normal submission to server
            // close the panel for clarity after submit
            panel.classList.remove('active');
        });

        // Clear button simply reloads the current page without query params
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '{{ url()->current() }}';
        });
    })();

    window.addEventListener("click", (e) => {
        if (e.target === addModal) addModal.style.display = "none";
        if (e.target === viewModal) viewModal.style.display = "none";
    });

    // Bind view buttons: parse JSON payload from data-payload and open modal
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const payload = btn.getAttribute('data-payload');
            if (!payload) return console.error('No payload on view button');
            try {
                const data = JSON.parse(payload);
                openViewMilkModal(data);
            } catch (err) {
                console.error('Failed to parse view payload', err, payload);
            }
        });
    });

    // Bind delete buttons: confirm then DELETE via AJAX
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const record = btn.closest('.record-item');
            if (!record) return console.error('Delete button not within a record item');
            const milkId = record.dataset.milkId;

            Swal.fire({
                title: 'Delete milk record?',
                text: 'This action cannot be undone. The milk record will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (!result.isConfirmed) return;

                // perform DELETE
                fetch(`/labtech/manage-milk-records/${milkId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) return res.json().then(err => { throw err });
                    return res.json();
                })
                .then(json => {
                        if (json && json.success) {
                            Swal.fire('Deleted!', 'Milk record removed.', 'success');
                                // remove from DOM
                                record.remove();
                                // rebuild pagination to adjust pages
                                if (window.__rebuildPagination) window.__rebuildPagination();
                        } else {
                        Swal.fire('Error', json.message || 'Could not delete milk record.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Delete failed', err);
                    const msg = err && err.message ? err.message : 'Request failed';
                    Swal.fire('Error', msg, 'error');
                });
            });
        });
    });
});

function openViewMilkModal(data) {
    // base fields
    document.getElementById('view-milk-id').textContent = data.milkId || '-';
    document.getElementById('view-donor-name').textContent = data.donorName || '-';
    document.getElementById('view-status').textContent = data.status || '-';
    document.getElementById('view-volume').textContent = data.volume || '-';
    document.getElementById('view-shariah').textContent = data.shariah || '-';
    document.getElementById('view-shariah-remarks').textContent = data.shariahRemarks || '-';
    document.getElementById('view-shariah-date').textContent = data.shariahApprovalDate || '-';

    // Stage datetimes: helper to combine date+time or show '-'
    function fmt(dt, tm) {
        if (!dt && !tm) return '-';
        if (!tm) return dt || '-';
        if (!dt) return tm || '-';
        return dt + ' ' + tm;
    }

    document.getElementById('view-stage1-start').textContent = fmt(data.milk_stage1StartDate, data.milk_stage1StartTime);
    document.getElementById('view-stage1-end').textContent = fmt(data.milk_stage1EndDate, data.milk_stage1EndTime);
    // screening result: may be JSON/string/array/object — format cleanly
    (function renderStage1Result() {
        const outEl = document.getElementById('view-stage1-result');
        const s1r = data.milk_stage1Result;
        if (s1r === null || s1r === undefined || s1r === '') {
            outEl.textContent = '-';
            return;
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function formatObjectAsLines(obj) {
            // prefer name/result style when possible
            const nameKey = Object.keys(obj).find(k => /name|test|contents|parameter|item/i.test(k));
            const resultKey = Object.keys(obj).find(k => /result|status|tolerance|outcome|pass|value/i.test(k));
            if (nameKey && resultKey) {
                return [`${obj[nameKey]} - ${obj[resultKey]}`];
            }

            // fallback: each key on its own line as 'key: value'
            return Object.entries(obj).map(([k, v]) => `${k}: ${typeof v === 'object' ? JSON.stringify(v) : v}`);
        }

        function buildLines(val) {
            // val may be string/json/array/object
            if (typeof val === 'string') {
                // try parse JSON
                try {
                    const parsed = JSON.parse(val);
                    return buildLines(parsed);
                } catch (e) {
                    return [val];
                }
            }

            if (Array.isArray(val)) {
                const lines = [];
                val.forEach(item => {
                    if (typeof item === 'object' && item !== null) {
                        // if object has name/result keys, format single line
                        const oLines = formatObjectAsLines(item);
                        lines.push(...oLines);
                    } else {
                        lines.push(String(item));
                    }
                });
                return lines;
            }

            if (typeof val === 'object') {
                return formatObjectAsLines(val);
            }

            return [String(val)];
        }

        try {
            const lines = buildLines(s1r);
            if (!lines || lines.length === 0) {
                outEl.textContent = '-';
                return;
            }
            outEl.innerHTML = lines.map(l => `<div>${escapeHtml(l)}</div>`).join('');
        } catch (e) {
            outEl.textContent = '-';
        }
    })();

    document.getElementById('view-stage2-start').textContent = fmt(data.milk_stage2StartDate, data.milk_stage2StartTime);
    document.getElementById('view-stage2-end').textContent = fmt(data.milk_stage2EndDate, data.milk_stage2EndTime);
    document.getElementById('view-stage3-start').textContent = fmt(data.milk_stage3StartDate, data.milk_stage3StartTime);
    document.getElementById('view-stage3-end').textContent = fmt(data.milk_stage3EndDate, data.milk_stage3EndTime);

    // show modal
    document.getElementById('viewMilkModal').style.display = 'flex';
}

function closeViewMilkModal() {
    document.getElementById("viewMilkModal").style.display = "none";
}

// ============== AJAX FORM SUBMISSION  ==============
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

<script>
// Poll milk statuses every 10 seconds and update the status tag in-place
document.addEventListener('DOMContentLoaded', function () {
    const endpoint = "{{ route('labtech.milk-statuses') }}";

    async function fetchAndUpdateStatuses() {
        try {
            const res = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return console.error('Failed to fetch milk statuses');
            const data = await res.json();

            // keep a small cache to avoid re-posting completion repeatedly during this session
            const postedCompletion = new Set();

            data.forEach(item => {
                const id = item.milk_ID;
                let status = item.milk_Status || '';
                const el = document.querySelector(`.record-item[data-milk-id="${id}"] .clinical-status a.status-tag`);
                if (!el) return;

                // normalize status values for logic
                const statusNorm = (status || '').toLowerCase();
                const baseStage = statusNorm.split(' ')[0] || '';

                // If the stage is screening, labelling or distributing, check end datetime to compute remaining
                let endDate = null;
                let endTime = null;
                let hasResults = false;
                if (baseStage === 'labelling') {
                    endDate = item.milk_stage2EndDate;
                    endTime = item.milk_stage2EndTime;
                } else if (baseStage === 'distributing') {
                    endDate = item.milk_stage3EndDate;
                    endTime = item.milk_stage3EndTime;
                } else if (baseStage === 'screening') {
                    endDate = item.milk_stage1EndDate;
                    endTime = item.milk_stage1EndTime;
                    // detect if screening results exist (non-empty JSON)
                    try {
                        const r = item.milk_stage1Result;
                        if (r) {
                            // server returns raw JSON string; attempt to parse or check length
                            if (typeof r === 'string') {
                                const parsed = JSON.parse(r);
                                hasResults = Array.isArray(parsed) && parsed.length > 0;
                            } else if (Array.isArray(r)) {
                                hasResults = r.length > 0;
                            }
                        }
                    } catch (e) {
                        hasResults = false;
                    }
                }

                // If we have an end datetime, compute remaining
                if (endDate && endTime) {
                    // normalize time like 'HH:MM' -> 'HH:MM:00'
                    const normalizedTime = endTime.length === 8 ? endTime : endTime + ':00';
                    const endDT = new Date(`${endDate}T${normalizedTime}`);
                    const now = new Date();

                    if (!isNaN(endDT.getTime())) {
                        const diffMs = endDT - now;

                        if (diffMs <= 0) {
                            // duration finished
                            if (baseStage === 'screening') {
                                // For screening: if results exist, show completed; otherwise show waiting for results
                                if (hasResults) {
                                    const completedLabel = 'Screening Completed';
                                    el.textContent = completedLabel;
                                    const fullCls = completedLabel.toLowerCase().replace(/\s+/g, '-');
                                    const baseCls = 'screening';
                                    const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                                    el.className = preserved.join(' ');
                                    el.classList.add(`status-${baseCls}`);
                                    el.classList.add(`status-${fullCls}`);
                                } else {
                                    // No results yet: show waiting message, do NOT auto-post
                                    const waitingLabel = 'Screening • Waiting for results';
                                    el.textContent = waitingLabel;
                                    const normalized = 'screening-waiting-for-results';
                                    const baseCls = 'screening';
                                    const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                                    el.className = preserved.join(' ');
                                    el.classList.add(`status-${baseCls}`);
                                    el.classList.add(`status-${normalized}`);
                                }
                                // remove endTs if present so ticker stops
                                try { delete el.dataset.endTs; } catch (e) {}
                                try { delete el.dataset.baseStage; } catch (e) {}
                                return;
                            }

                            // labelling/distributing: if DB not already marked completed, post completion
                            if (!/completed/i.test(statusNorm) && !postedCompletion.has(id + '-' + baseStage)) {
                                const completeUrl = `/labtech/process-milk/${id}/${baseStage === 'labelling' ? 'labelling-complete' : 'distributing-complete'}`;
                                fetch(completeUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({})
                                })
                                .then(r => r.json())
                                .then(resp => {
                                    if (resp && resp.success) {
                                        postedCompletion.add(id + '-' + baseStage);
                                        const completedLabel = baseStage === 'labelling' ? 'Labelling Completed' : 'Distributing Completed';
                                        el.textContent = completedLabel;
                                        const fullCls = (completedLabel || '').toLowerCase().replace(/\s+/g, '-');
                                        const baseCls = baseStage || 'pending';
                                        const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                                        el.className = preserved.join(' ');
                                        el.classList.add(`status-${baseCls}`);
                                        el.classList.add(`status-${fullCls}`);
                                    }
                                })
                                .catch(err => console.error('Failed to POST completion:', err));
                            } else {
                                // already shows completed or posted in this session — ensure UI reflects completed
                                if (/completed/i.test(statusNorm)) {
                                    const completedLabel = status;
                                    const fullCls = (completedLabel || '').toLowerCase().replace(/\s+/g, '-');
                                    const baseCls = baseStage || 'pending';
                                    const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                                    el.className = preserved.join(' ');
                                    el.classList.add(`status-${baseCls}`);
                                    el.classList.add(`status-${fullCls}`);
                                }
                            }
                        } else {
                            // still counting: display short remaining time on the pill
                            const totalSeconds = Math.floor(diffMs / 1000);
                            const hours = Math.floor(totalSeconds / 3600);
                            const minutes = Math.floor((totalSeconds % 3600) / 60);
                            const seconds = totalSeconds % 60;
                            const formatted = `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
                            const label = `${baseStage.charAt(0).toUpperCase() + baseStage.slice(1)} • ${formatted}`;
                            el.textContent = label;

                            // store end timestamp and base stage for the per-second ticker
                            try { el.dataset.endTs = endDT.getTime(); } catch(e) {}
                            try { el.dataset.baseStage = baseStage.charAt(0).toUpperCase() + baseStage.slice(1); } catch(e) {}
                            // also store whether screening has results so ticker can decide behaviour
                            try { if (baseStage === 'screening') el.dataset.hasResults = hasResults ? 'true' : 'false'; } catch(e) {}

                            // ensure classes still present
                            const normalized = (status || 'pending').toLowerCase().replace(/\s+/g, '-');
                            const base = (status || 'pending').toLowerCase().split(' ')[0].replace(/\s+/g, '-');
                            const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                            el.className = preserved.join(' ');
                            el.classList.add(`status-${base}`);
                            el.classList.add(`status-${normalized}`);
                        }
                        return; // processed this item
                    }
                }

                // Fallback: update text and classes as before
                el.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : 'Not Yet Started';
                const normalized = (status || 'pending').toLowerCase().replace(/\s+/g, '-');
                const base = (status || 'pending').toLowerCase().split(' ')[0].replace(/\s+/g, '-');
                const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                el.className = preserved.join(' ');
                el.classList.add(`status-${base}`);
                el.classList.add(`status-${normalized}`);
            });
        } catch (e) {
            console.error('Error polling milk statuses', e);
        }
    }

    // initial load
    fetchAndUpdateStatuses();
    // poll every 10 seconds
    setInterval(fetchAndUpdateStatuses, 10000);
});
</script>

    <script>
    // Per-second ticker: update any status-pill that has `data-end-ts` set by the poller
    document.addEventListener('DOMContentLoaded', function () {
        const postedCompletion = window.__milkPostedCompletion || new Set();
        window.__milkPostedCompletion = postedCompletion;

        setInterval(() => {
            document.querySelectorAll('.clinical-status a.status-tag[data-end-ts]').forEach(el => {
                const endTs = Number(el.dataset.endTs);
                if (!endTs) return;
                const now = Date.now();
                const diffMs = endTs - now;
                const record = el.closest('.record-item');
                const id = record ? record.dataset.milkId : null;
                const baseStage = el.dataset.baseStage || (el.textContent || '').split(' • ')[0];

                if (diffMs <= 0) {
                    // send completion POST once per session or handle screening specially
                    if (!id) { delete el.dataset.endTs; delete el.dataset.baseStage; return; }
                    const baseLower = (baseStage || '').toLowerCase();
                    const key = id + '-' + baseLower;
                    const hasResults = el.dataset.hasResults === 'true';

                    if (baseLower === 'screening') {
                        // Screening: if results exist, mark completed in UI; otherwise show waiting message
                        if (hasResults) {
                            const completedLabel = 'Screening Completed';
                            delete el.dataset.endTs; delete el.dataset.baseStage; delete el.dataset.hasResults;
                            el.textContent = completedLabel;
                            const fullCls = completedLabel.toLowerCase().replace(/\s+/g, '-');
                            const baseCls = 'screening';
                            const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                            el.className = preserved.join(' ');
                            el.classList.add(`status-${baseCls}`);
                            el.classList.add(`status-${fullCls}`);
                        } else {
                            const waitingLabel = 'Screening • Waiting for results';
                            const normalized = 'screening-waiting-for-results';
                            delete el.dataset.endTs; delete el.dataset.baseStage; // stop ticker for this item
                            el.textContent = waitingLabel;
                            const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                            el.className = preserved.join(' ');
                            el.classList.add('status-screening');
                            el.classList.add(`status-${normalized}`);
                        }
                        return;
                    }

                    if (postedCompletion.has(key)) {
                        delete el.dataset.endTs; delete el.dataset.baseStage; return;
                    }

                    const completeUrl = `/labtech/process-milk/${id}/${baseLower === 'labelling' ? 'labelling-complete' : 'distributing-complete'}`;
                    fetch(completeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(r => r.json())
                    .then(resp => {
                        if (resp && resp.success) {
                            postedCompletion.add(key);
                            // update UI to completed
                            const completedLabel = baseLower === 'labelling' ? 'Labelling Completed' : 'Distributing Completed';
                            delete el.dataset.endTs; delete el.dataset.baseStage;
                            el.textContent = completedLabel;
                            const fullCls = (completedLabel || '').toLowerCase().replace(/\s+/g, '-');
                            const baseCls = baseLower;
                            const preserved = el.className.split(' ').filter(c => c === 'status-tag' || c === 'status-clickable' || !c.startsWith('status-'));
                            el.className = preserved.join(' ');
                            el.classList.add(`status-${baseCls}`);
                            el.classList.add(`status-${fullCls}`);
                        }
                    })
                    .catch(err => {
                        // keep data attributes so next tick will retry
                        console.error('Completion POST failed (ticker):', err);
                    });
                } else {
                    // still counting — update display
                    const totalSeconds = Math.floor(diffMs / 1000);
                    const hours = Math.floor(totalSeconds / 3600);
                    const minutes = Math.floor((totalSeconds % 3600) / 60);
                    const seconds = totalSeconds % 60;
                    const formatted = `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
                    el.textContent = `${baseStage} • ${formatted}`;
                }
            });
        }, 1000);
    });

    function switchViewTab(tabName) {
        // Hide all
        document.querySelectorAll('.view-tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.view-tab-btn').forEach(el => el.classList.remove('active'));

        // Show target
        document.getElementById('view-tab-' + tabName).style.display = 'block';
        // Find button (simple heuristic: rely on onclick text or index, here purely visual for simplicity)
        const btns = document.querySelectorAll('.view-tab-btn');
        if(tabName === 'raw') btns[0].classList.add('active');
        if(tabName === 'processed') btns[1].classList.add('active');
        if(tabName === 'qc') btns[2].classList.add('active');
    }

    function openViewMilkModal(data) {
        // 1. Basic Info
        document.getElementById('view-milk-id').textContent = data.milkId;
        document.getElementById('view-donor-name').textContent = data.donorName;
        document.getElementById('view-volume').textContent = data.volume;
        document.getElementById('view-status').textContent = data.status;
        
        // 2. Shariah Info
        document.getElementById('view-shariah').textContent = data.shariah;
        document.getElementById('view-shariah-date').textContent = data.shariahApprovalDate;
        document.getElementById('view-shariah-remarks').textContent = data.shariahRemarks;

        // 3. Populate Pre-Bottles (Raw)
        const preList = document.getElementById('view-pre-bottles-list');
        preList.innerHTML = '';
        if (data.preBottles && data.preBottles.length > 0) {
            data.preBottles.forEach(b => {
                const thawed = b.pre_is_thawed ? '<span style="color:green">Yes</span>' : '<span style="color:gray">No</span>';
                preList.innerHTML += `<tr>
                    <td>${b.pre_bottle_code}</td>
                    <td>${b.pre_volume}</td>
                    <td>${thawed}</td>
                </tr>`;
            });
        } else {
            preList.innerHTML = '<tr><td colspan="3" class="text-muted">No raw bottles recorded.</td></tr>';
        }

        // 4. Populate Post-Bottles (Processed)
        const postList = document.getElementById('view-post-bottles-list');
        postList.innerHTML = '';
        if (data.postBottles && data.postBottles.length > 0) {
            data.postBottles.forEach(b => {
                // Check micro status color
                let microColor = 'gray';
                if (b.post_micro_status === 'Passed' || b.post_micro_status === 'Not Contaminated') microColor = 'green';
                if (b.post_micro_status === 'Failed' || b.post_micro_status === 'Contaminated') microColor = 'red';

                postList.innerHTML += `<tr>
                    <td>${b.post_bottle_code}</td>
                    <td>${b.post_volume}</td>
                    <td>${b.post_expiry_date || '-'}</td>
                    <td style="color:${microColor}; font-weight:bold;">${b.post_micro_status || 'Pending'}</td>
                    <td>${b.post_storage_location || '-'}</td>
                </tr>`;
            });
        } else {
            postList.innerHTML = '<tr><td colspan="5" class="text-muted">No processed bottles yet.</td></tr>';
        }

        // Reset to first tab
        switchViewTab('raw');
        
        document.getElementById('viewMilkModal').style.display = 'flex';
    }
    </script>

@endsection