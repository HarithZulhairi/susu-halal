@extends('layouts.hmmc')

@section('title', 'Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hmmc_manage-milk-records.css') }}">
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
                </div>
            </div>

            <div id="filterPanel" class="filter-panel" role="region" aria-label="Search and filters">
                <form id="filterForm" method="GET" action="{{ url()->current() }}" autocomplete="off">
                    <input id="searchInput" name="searchInput" value="{{ request('searchInput') }}" class="form-control" type="search" placeholder="Search by Donor name or Milk ID">

                    <select id="filterStatus" name="filterStatus" class="form-control">
                        <option value="">All Clinical Status</option>
                        <option value="Not Yet Started" {{ request('filterStatus') == 'Not Yet Started' ? 'selected' : '' }}>Not Yet Started</option>
                        <option value="Screening" {{ request('filterStatus') == 'Screening' ? 'selected' : '' }}>Screening</option>
                        <option value="Screening Completed" {{ request('filterStatus') == 'Screening Completed' ? 'selected' : '' }}>Screening Completed</option>
                        <option value="Labelling" {{ request('filterStatus') == 'Labelling' ? 'selected' : '' }}>Labelling</option>
                        <option value="Labelling Completed" {{ request('filterStatus') == 'Labelling Completed' ? 'selected' : '' }}>Labelling Completed</option>
                        <option value="Distributing" {{ request('filterStatus') == 'Distributing' ? 'selected' : '' }}>Distributing</option>
                        <option value="Distributing Completed" {{ request('filterStatus') == 'Distributing Completed' ? 'selected' : '' }}>Distributing Completed</option>
                    </select>

                    <div style="display:flex; gap:8px;">
                        <input id="volumeMin" name="volumeMin" value="{{ request('volumeMin') }}" class="form-control" type="number" min="0" placeholder="Min mL">
                        <input id="volumeMax" name="volumeMax" value="{{ request('volumeMax') }}" class="form-control" type="number" min="0" placeholder="Max mL">
                    </div>

                    <div style="display:flex; gap:8px;">
                        <input id="expiryFrom" name="expiryFrom" value="{{ request('expiryFrom') }}" class="form-control" type="date">
                        <input id="expiryTo" name="expiryTo" value="{{ request('expiryTo') }}" class="form-control" type="date">
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
                    <button class="sortable-header" data-key="expiry" title="Sort by Expiration Date">
                        EXPIRATION DATE <span class="sort-indicator"></span>
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
                            data-volume="{{ $milk->milk_volume }}"
                            data-expiry="{{ $milk->milk_expiryDate }}"
                            data-shariah="{{ strtolower($milk->milk_shariahApproval ?? 'not yet reviewed') }}"
                            data-shariah-remarks="{{ $milk->milk_shariahRemarks ?? '-' }}"
                            data-shariah-date="{{ $milk->milk_shariahApprovalDate ?? '-' }}">
                        
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
                            <span class="status-tag status-{{ $baseCls }} status-{{ $fullCls }}">
                                {{ ucfirst($rawStatus) }}
                            </span>
                        </div>

                        <div class="volume-data">{{ $milk->milk_volume }} mL</div>

                        <div class="expiry-date">
                            @if($milk->milk_expiryDate)
                                {{ \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') }}
                                @if(\Carbon\Carbon::parse($milk->milk_expiryDate)->isPast())
                                    <span class="expired-text">(expired)</span>
                                @endif
                            @else
                                -
                            @endif
                        </div>

                        <div class="shariah-status">
                            @php $approval = $milk->milk_shariahApproval; @endphp
                            <span class="status-tag {{ is_null($approval) ? 'status-pending' : ($approval ? 'status-approved' : 'status-rejected') }}">
                                {{ is_null($approval) ? 'Not Yet Reviewed' : ($approval ? 'Approved' : 'Rejected') }}
                            </span>
                        </div>

                        <div class="actions">
                            @php
                                $payload = [
                                    'milkId' => $milk->formatted_id,
                                    'donorName' => $milk->donor?->dn_FullName ?? 'N/A',
                                    'status' => ucfirst($milk->milk_Status ?? 'Not Yet Started'),
                                    'volume' => $milk->milk_volume . ' mL',
                                    'expiry' => $milk->milk_expiryDate ? \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') : 'Not set',
                                    'shariah' => is_null($milk->milk_shariahApproval) ? 'Not Yet Reviewed' : ($milk->milk_shariahApproval ? 'Approved' : 'Rejected'),
                                    'shariahRemarks' => $milk->milk_shariahRemarks ?? 'N/A',
                                    'shariahDate' => $milk->milk_shariahApprovalDate ? \Carbon\Carbon::parse($milk->milk_shariahApprovalDate)->format('M d, Y') : 'N/A',
                                    'milk_stage1StartDate' => $milk->milk_stage1StartDate ?? '',
                                    'milk_stage1StartTime' => $milk->milk_stage1StartTime ?? '',
                                    'milk_stage1EndDate' => $milk->milk_stage1EndDate ?? '',
                                    'milk_stage1EndTime' => $milk->milk_stage1EndTime ?? '',
                                    'milk_stage1Result' => $milk->milk_stage1Result ?? null,
                                    'milk_stage2StartDate' => $milk->milk_stage2StartDate ?? '',
                                    'milk_stage2StartTime' => $milk->milk_stage2StartTime ?? '',
                                    'milk_stage2EndDate' => $milk->milk_stage2EndDate ?? '',
                                    'milk_stage2EndTime' => $milk->milk_stage2EndTime ?? '',
                                    'milk_stage3StartDate' => $milk->milk_stage3StartDate ?? '',
                                    'milk_stage3StartTime' => $milk->milk_stage3StartTime ?? '',
                                    'milk_stage3EndDate' => $milk->milk_stage3EndDate ?? '',
                                    'milk_stage3EndTime' => $milk->milk_stage3EndTime ?? ''
                                ];
                            @endphp
                            <button class="btn-view" title="View" data-payload='@json($payload)'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteMilkRecord({{ $milk->milk_ID }})" title="Delete Record">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="record-item text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No milk records available.</p>
                    </div>
                @endforelse
                
                <div id="paginationControls" class="pagination-controls"></div>
            </div>
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

            <h3>Processing Stages</h3>
            <div class="stage-block">
                <div class="stage-row">
                    <strong>Screening:</strong>
                    <span id="view-stage1-start"></span>
                    <span class="dash">—</span>
                    <span id="view-stage1-end"></span>
                </div>
            </div>

            <div class="stage-block">
                <div class="stage-row">
                    <strong>Screening Result:</strong>
                    <span id="view-stage1-result"></span>
                </div>
            </div>

            <div class="stage-block">
                <div class="stage-row">
                    <strong>Labelling:</strong>
                    <span id="view-stage2-start"></span>
                    <span class="dash">—</span>
                    <span id="view-stage2-end"></span>
                </div>
            </div>

            <div class="stage-block">
                <div class="stage-row">
                    <strong>Distributing:</strong>
                    <span id="view-stage3-start"></span>
                    <span class="dash">—</span>
                    <span id="view-stage3-end"></span>
                </div>
            </div>

            <hr>

            <h3>Quality Control</h3>
            <p><strong>Shariah Approval:</strong>
                <span id="view-shariah"></span>
            </p>
            <p><strong>Shariah Approval Date:</strong>
                <span id="view-shariahDate"></span>
            </p>
            <p><strong>Shariah Remarks:</strong>
                <span id="view-shariahRemarks"></span>
            </p>

            
        </div>
    </div>
</div>

{{-- ===========================
      JAVASCRIPT
=========================== --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    
    const viewModal = document.getElementById("viewMilkModal");

    // Sorting logic
    (function setupSorting() {
        const listContainer = document.querySelector('.records-list');
        if (!listContainer) return;

        const headerButtons = Array.from(document.querySelectorAll('.record-header .sortable-header'));

        function getValueForKey(row, key) {
            if (key === 'donor') return row.querySelector('.donor-name')?.textContent?.trim() || '';
            if (key === 'status') return row.querySelector('.clinical-status .status-tag')?.textContent?.trim() || '';
            if (key === 'volume') {
                const v = row.querySelector('.volume-data')?.textContent || '';
                const m = v.match(/([0-9]+(\.[0-9]+)?)/);
                return m ? parseFloat(m[0]) : 0;
            }
            if (key === 'expiry') {
                const text = row.querySelector('.expiry-date')?.textContent?.trim() || '';
                const d = Date.parse(text);
                return isNaN(d) ? (text === '-' ? Infinity : 0) : d;
            }
            if (key === 'shariah') return row.querySelector('.shariah-status .status-tag')?.textContent?.trim() || '';
            if (key === 'milkId') return Number(row.dataset.milkId) || 0;
            return '';
        }

        function sortBy(key, direction = 'desc') {
            const rows = Array.from(listContainer.querySelectorAll('.record-item'));
            const multiplier = direction === 'asc' ? 1 : -1;

            rows.sort((a, b) => {
                const va = getValueForKey(a, key);
                const vb = getValueForKey(b, key);

                if (typeof va === 'number' && typeof vb === 'number') return (va - vb) * multiplier;

                const sa = String(va).toLowerCase();
                const sb = String(vb).toLowerCase();
                if (sa < sb) return -1 * multiplier;
                if (sa > sb) return 1 * multiplier;
                return 0;
            });

            const listInner = listContainer;
            const header = listInner.querySelector('.record-header');
            rows.forEach(r => header.after(r));
        }

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

        // Initial sort
        if (document.querySelectorAll('.record-item').length > 0) {
            clearIndicators();
            activeKey = 'milkId';
            activeDir = 'desc';
            if (headerButtons[0]) {
                headerButtons[0].querySelector('.sort-indicator').textContent = '▼';
            }
            sortBy(activeKey, activeDir);
        }
    })();

    // Pagination logic
    (function setupPagination() {
        const listContainer = document.querySelector('.records-list');
        const controls = document.getElementById('paginationControls');
        if (!listContainer || !controls) return;

        const rowsSelector = '.record-item';
        const perPage = 10;
        let currentPage = 1;

        function getRows() {
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

        renderPage(1);
    })();

    // Filter / Search logic
    (function setupFilteringInline() {
        const panel = document.getElementById('filterPanel');
        const btnSearch = document.querySelector('.btn-search');
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearFilters');

        if (!panel || !btnSearch || !searchInput) return;

        function togglePanel() {
            panel.classList.toggle('active');
            if (panel.classList.contains('active')) searchInput.focus();
        }

        btnSearch.addEventListener('click', togglePanel);

        form.addEventListener('submit', function(e) {
            panel.classList.remove('active');
        });

        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '{{ url()->current() }}';
        });
    })();

    window.addEventListener("click", (e) => {
        if (e.target === viewModal) viewModal.style.display = "none";
    });

    // Bind View Buttons
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
});

function openViewMilkModal(data) {
    // 1. Populate Base Fields
    document.getElementById('view-milk-id').textContent = data.milkId || '-';
    document.getElementById('view-donor-name').textContent = data.donorName || '-';
    document.getElementById('view-volume').textContent = data.volume || '-';
    document.getElementById('view-expiry').textContent = data.expiry || '-';
    
    // --- FIX 1: Restore Status Badge Styling (Grey/Colored background) ---
    const statusEl = document.getElementById('view-status');
    const rawStatus = data.status || 'Not Yet Started'; // Default to Not Yet Started if null
    
    statusEl.textContent = rawStatus;
    
    // Reset to base classes
    
    // Generate dynamic classes (e.g., "Not Yet Started" -> "status-not-yet-started")
    const normalized = rawStatus.toLowerCase().replace(/\s+/g, '-'); 
    const base = rawStatus.toLowerCase().split(' ')[0].replace(/\s+/g, '-'); 
    

    // --- Shariah Fields ---
    document.getElementById('view-shariah').textContent = data.shariah || '-';
    
    // Handle Tooltip/Title for Shariah
    const shariahDetails = `Date: ${data.shariahDate || '-'}\nRemarks: ${data.shariahRemarks || '-'}`;
    document.getElementById('view-shariah').title = shariahDetails;
    
    // Populate separate fields if they exist in your HTML
    const shariahDateEl = document.getElementById('view-shariahDate');
    if(shariahDateEl) shariahDateEl.textContent = data.shariahDate || '-';
    
    const shariahRemEl = document.getElementById('view-shariahRemarks');
    if(shariahRemEl) shariahRemEl.textContent = data.shariahRemarks || '-';

    // 2. Helper for Date Formatting
    function fmt(dt, tm) {
        if (!dt && !tm) return '-';
        if (!tm) return dt || '-';
        if (!dt) return tm || '-';
        return dt + ' ' + tm;
    }

    // 3. Populate Dates
    document.getElementById('view-stage1-start').textContent = fmt(data.milk_stage1StartDate, data.milk_stage1StartTime);
    document.getElementById('view-stage1-end').textContent = fmt(data.milk_stage1EndDate, data.milk_stage1EndTime);
    
    // --- FIX 2: JSON Parsing for Screening Results (Your requested fix) ---
    (function renderStage1Result() {
        const outEl = document.getElementById('view-stage1-result');
        let val = data.milk_stage1Result;

        if (!val) { 
            outEl.textContent = '-'; 
            return; 
        }

        // Step A: If it's a string, try to parse it into an Object/Array
        if (typeof val === 'string') {
            try {
                val = JSON.parse(val);
            } catch (e) {
                outEl.textContent = val; // Display raw string if parsing fails
                return;
            }
        }

        // Step B: Helper to format a single test object
        function formatItem(item) {
            const name = item.contents || item.test || item.parameter || item.name;
            const result = item.tolerance || item.result || item.value || item.status;

            if (name && result) {
                return `${name} - ${result}`;
            }
            return JSON.stringify(item).replace(/[{}"]/g, '').replace(/:/g, ': ');
        }

        // Step C: Render based on type
        if (Array.isArray(val)) {
            const formattedList = val.map(item => `<div>${formatItem(item)}</div>`).join('');
            outEl.innerHTML = formattedList;
        } else if (typeof val === 'object') {
            outEl.textContent = formatItem(val);
        } else {
            outEl.textContent = String(val);
        }
    })();

    document.getElementById('view-stage2-start').textContent = fmt(data.milk_stage2StartDate, data.milk_stage2StartTime);
    document.getElementById('view-stage2-end').textContent = fmt(data.milk_stage2EndDate, data.milk_stage2EndTime);

    document.getElementById('view-stage3-start').textContent = fmt(data.milk_stage3StartDate, data.milk_stage3StartTime);
    document.getElementById('view-stage3-end').textContent = fmt(data.milk_stage3EndDate, data.milk_stage3EndTime);

    document.getElementById('viewMilkModal').style.display = 'flex';
}

function closeViewMilkModal() {
    document.getElementById("viewMilkModal").style.display = "none";
}
</script>

<script>
// Polling for updates (Optional for Shariah view, but good for real-time status checks)
document.addEventListener('DOMContentLoaded', function () {
    const endpoint = "{{ route('labtech.milk-statuses') }}"; 

    async function fetchAndUpdateStatuses() {
        try {
            const res = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();

            data.forEach(item => {
                const id = item.milk_ID;
                let status = item.milk_Status || '';
                const el = document.querySelector(`.record-item[data-milk-id="${id}"] .clinical-status span.status-tag`);
                if (!el) return;

                // Update status text
                el.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : 'Not Yet Started';
                
                // Update CSS classes
                const normalized = (status || 'pending').toLowerCase().replace(/\s+/g, '-');
                const base = (status || 'pending').toLowerCase().split(' ')[0].replace(/\s+/g, '-');
                
                // Reset classes to base tag + new status
                el.className = 'status-tag'; 
                el.classList.add(`status-${base}`);
                el.classList.add(`status-${normalized}`);
            });
        } catch (e) {
            // ignore errors in polling
        }
    }

    setInterval(fetchAndUpdateStatuses, 10000);
});

// --- Delete Logic ---
    function deleteMilkRecord(id) {
        Swal.fire({
            title: 'Delete Record?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/hmmc/manage-milk-records/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire(
                            'Deleted!', 
                            'Milk record has been deleted.', 
                            'success'
                        ).then(() => {
                            location.reload(); // This refreshes the page
                        });
                    } else {
                        Swal.fire('Error', 'Failed to delete record.', 'error');
                    }
                })
                .catch(e => Swal.fire('Error', 'Network error', 'error'));
            }
        });
    }
</script>

@endsection