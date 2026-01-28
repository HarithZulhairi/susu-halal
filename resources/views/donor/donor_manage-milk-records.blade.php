@extends('layouts.donor')

@section('title', 'My Milk Records')

@section('content')
<!-- Reusing LabTech CSS for consistent design -->
<link rel="stylesheet" href="{{ asset('css/labtech_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>My Milk Records</h1>
            <p>Track your milk donations and processing status</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Milk Records</h2>
                <div class="actions-header">
                    <button class="btn btn-search"><i class="fas fa-search"></i> Search &amp; Filter</button>
                    <!-- No Add Button for Donor -->
                </div>
            </div>

            <!-- FILTER / SEARCH PANEL (inline, hidden by default) -->
            <div id="filterPanel" class="filter-panel" role="region" aria-label="Search and filters">
                <form id="filterForm" method="GET" action="{{ url()->current() }}" autocomplete="off">
                    <input id="searchInput" name="searchInput" value="{{ request('searchInput') }}" class="form-control" type="search" placeholder="Search by Milk ID">

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
                    <!-- Donors know their own name, maybe redundant but keeping for structure. 
                         Or we can hide it. User said "see THEIR milk only". 
                         Let's keep consistent columns for layout stability. -->
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
                            <!-- Read-only status tag, no link -->
                            <span class="status-tag status-{{ $baseCls }} status-{{ $fullCls }}">
                                {{ ucfirst($rawStatus) }}
                            </span>
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
                                    'shariah' => is_null($milk->milk_shariahApproval) ? 'Not Yet Reviewed' : ($milk->milk_shariahApproval ? 'Approved' : 'Rejected'),
                                    'shariahRemarks' => $milk->milk_shariahRemarks ?? '-',
                                    'shariahApprovalDate' => $milk->milk_shariahApprovalDate ? \Carbon\Carbon::parse($milk->milk_shariahApprovalDate)->format('M d, Y') : '-',
                                    'preBottles' => $milk->preBottles,
                                    'postBottles' => $milk->postBottles,
                                    
                                    // Stage info for modal
                                    'milk_stage1StartDate' => $milk->milk_stage1StartDate,
                                    'milk_stage1StartTime' => $milk->milk_stage1StartTime,
                                    'milk_stage1EndDate' => $milk->milk_stage1EndDate,
                                    'milk_stage1EndTime' => $milk->milk_stage1EndTime,
                                    'milk_stage1Result' => $milk->milk_stage1Result,
                                    
                                    'milk_stage2StartDate' => $milk->milk_stage2StartDate,
                                    'milk_stage2StartTime' => $milk->milk_stage2StartTime,
                                    'milk_stage2EndDate' => $milk->milk_stage2EndDate,
                                    'milk_stage2EndTime' => $milk->milk_stage2EndTime,
                                    
                                    'milk_stage3StartDate' => $milk->milk_stage3StartDate,
                                    'milk_stage3StartTime' => $milk->milk_stage3StartTime,
                                    'milk_stage3EndDate' => $milk->milk_stage3EndDate,
                                    'milk_stage3EndTime' => $milk->milk_stage3EndTime,
                                ];
                            @endphp
                            <button class="btn-view" title="View" data-payload='@json($payload)'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <!-- No delete button -->
                        </div>
                    </div>
                @empty
                    <div class="record-item text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No milk records found.</p>
                    </div>
                @endforelse
                
                <div id="paginationControls" class="pagination-controls"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== VIEW MILK RECORD MODAL ===================== --}}
<!-- Reusing exact same modal structure -->
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
                <div style="margin-bottom:10px; font-size:0.9em; color:#555;">
                   Stage 1 (Screening): <span id="view-stage1-start"></span> - <span id="view-stage1-end"></span><br>
                   Stage 2 (Thawing): <span id="view-stage2-start"></span> - <span id="view-stage2-end"></span>
                </div>

                <div style="margin-bottom:10px;">
                    <strong>Screening Result:</strong>
                    <div id="view-stage1-result" style="background:#eee; padding:5px; border-radius:4px; max-height:100px; overflow-y:auto; font-family:monospace; font-size:0.85em;">-</div>
                </div>

                <table class="view-table" style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                    <thead style="background: #f1f5f9;">
                        <tr>
                            <th style="padding: 8px; text-align: left;">Code</th>
                            <th style="padding: 8px; text-align: left;">Volume (mL)</th>
                            <th style="padding: 8px; text-align: left;">Thawed?</th>
                        </tr>
                    </thead>
                    <tbody id="view-pre-bottles-list"></tbody>
                </table>
            </div>

            <div id="view-tab-processed" class="view-tab-content" style="padding-top: 10px; display: none;">
                <h3>Pasteurized Bottles</h3>
                <div style="margin-bottom:10px; font-size:0.9em; color:#555;">
                   Stage 3 (Pasteurization): <span id="view-stage3-start"></span> - <span id="view-stage3-end"></span>
                </div>
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
                    <tbody id="view-post-bottles-list"></tbody>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============== MODAL OPEN / CLOSE ==============
document.addEventListener("DOMContentLoaded", () => {
    const viewModal = document.getElementById("viewMilkModal");

    // Sorting: single-column toggle (asc/desc)
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
            if (key === 'shariah') return row.querySelector('.shariah-status .status-tag')?.textContent?.trim() || '';
            if (key === 'milkId') {
                const id = row.dataset.milkId;
                return id ? Number(id) : 0;
            }
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
            const header = listContainer.querySelector('.record-header');
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
                if (activeKey === key) activeDir = activeDir === 'asc' ? 'desc' : 'asc';
                else { activeKey = key; activeDir = 'asc'; }
                clearIndicators();
                btn.classList.add(activeDir === 'asc' ? 'sorted-asc' : 'sorted-desc');
                btn.querySelector('.sort-indicator').textContent = activeDir === 'asc' ? '▲' : '▼';
                sortBy(activeKey, activeDir);
            });
        });

        if (document.querySelectorAll('.record-item').length > 0) {
            clearIndicators();
            activeKey = 'milkId';
            activeDir = 'desc';
            if (headerButtons[0]) headerButtons[0].querySelector('.sort-indicator').textContent = '▼';
            sortBy(activeKey, activeDir);
        }
    })();

    // Pagination
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
        renderPage(1);
    })();

    // Filter Panel
    (function setupFilteringInline() {
        const panel = document.getElementById('filterPanel');
        const btnSearch = document.querySelector('.btn-search');
        if (!panel || !btnSearch) return;
        btnSearch.addEventListener('click', () => {
            panel.classList.toggle('active');
            if (panel.classList.contains('active')) document.getElementById('searchInput')?.focus();
        });
        document.getElementById('clearFilters').addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = '{{ url()->current() }}';
        });
    })();

    window.addEventListener("click", (e) => {
        if (e.target === viewModal) viewModal.style.display = "none";
    });

    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
            const payload = btn.getAttribute('data-payload');
            try {
                const data = JSON.parse(payload);
                openViewMilkModal(data);
            } catch (err) {
                console.error('Failed to parse view payload', err);
            }
        });
    });
});

function openViewMilkModal(data) {
    document.getElementById('view-milk-id').textContent = data.milkId || '-';
    document.getElementById('view-donor-name').textContent = data.donorName || '-';
    document.getElementById('view-status').textContent = data.status || '-';
    document.getElementById('view-volume').textContent = data.volume || '-';
    document.getElementById('view-shariah').textContent = data.shariah || '-';
    document.getElementById('view-shariah-remarks').textContent = data.shariahRemarks || '-';
    document.getElementById('view-shariah-date').textContent = data.shariahApprovalDate || '-';

    function fmt(dt, tm) {
        if (!dt && !tm) return '-';
        if (!tm) return dt || '-';
        if (!dt) return tm || '-';
        return dt + ' ' + tm;
    }

    document.getElementById('view-stage1-start').textContent = fmt(data.milk_stage1StartDate, data.milk_stage1StartTime);
    document.getElementById('view-stage1-end').textContent = fmt(data.milk_stage1EndDate, data.milk_stage1EndTime);
    
    // Fill stage results (same logic as LabTech but read-only)
    const outEl = document.getElementById('view-stage1-result');
    outEl.textContent = '-'; 
    try {
        if(data.milk_stage1Result) {
            const parsed = typeof data.milk_stage1Result === 'string' ? JSON.parse(data.milk_stage1Result) : data.milk_stage1Result;
            outEl.textContent = JSON.stringify(parsed, null, 2); 
        }
    } catch(e) { outEl.textContent = String(data.milk_stage1Result || '-'); }

    document.getElementById('view-stage2-start').textContent = fmt(data.milk_stage2StartDate, data.milk_stage2StartTime);
    document.getElementById('view-stage2-end').textContent = fmt(data.milk_stage2EndDate, data.milk_stage2EndTime);
    document.getElementById('view-stage3-start').textContent = fmt(data.milk_stage3StartDate, data.milk_stage3StartTime);
    document.getElementById('view-stage3-end').textContent = fmt(data.milk_stage3EndDate, data.milk_stage3EndTime);

    const preList = document.getElementById('view-pre-bottles-list');
    preList.innerHTML = '';
    if (data.preBottles && data.preBottles.length > 0) {
        data.preBottles.forEach(b => {
             const thawed = b.pre_is_thawed ? '<span style="color:green">Yes</span>' : '<span style="color:gray">No</span>';
             preList.innerHTML += `<tr><td>${b.pre_bottle_code}</td><td>${b.pre_volume}</td><td>${thawed}</td></tr>`;
        });
    } else { preList.innerHTML = '<tr><td colspan="3" class="text-muted">No raw bottles recorded.</td></tr>'; }

    const postList = document.getElementById('view-post-bottles-list');
    postList.innerHTML = '';
    if (data.postBottles && data.postBottles.length > 0) {
        data.postBottles.forEach(b => {
            let microColor = 'gray';
            if (b.post_micro_status === 'Passed' || b.post_micro_status === 'Not Contaminated') microColor = 'green';
            else if (b.post_micro_status === 'Failed' || b.post_micro_status === 'Contaminated') microColor = 'red';
            postList.innerHTML += `<tr><td>${b.post_bottle_code}</td><td>${b.post_volume}</td><td>${b.post_expiry_date || '-'}</td><td style="color:${microColor}">${b.post_micro_status || 'Pending'}</td><td>${b.post_storage_location || '-'}</td></tr>`;
        });
    } else { postList.innerHTML = '<tr><td colspan="5" class="text-muted">No processed bottles yet.</td></tr>'; }

    switchViewTab('raw');
    document.getElementById('viewMilkModal').style.display = 'flex';
}

function closeViewMilkModal() {
    document.getElementById("viewMilkModal").style.display = "none";
}

function switchViewTab(tabName) {
    document.querySelectorAll('.view-tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.view-tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('view-tab-' + tabName).style.display = 'block';
    const btns = document.querySelectorAll('.view-tab-btn');
    if(tabName === 'raw') btns[0].classList.add('active');
    if(tabName === 'processed') btns[1].classList.add('active');
    if(tabName === 'qc') btns[2].classList.add('active');
}

// Polling for STATUS updates only (No auto-complete logic)
document.addEventListener('DOMContentLoaded', function () {
    // We can use the generic status endpoint or simpler manual refresh.
    // For now, let's skip complex polling for Donor to keep it simple unless requested.
    // If they want "live" updates, they can refresh.
});
</script>
@endsection