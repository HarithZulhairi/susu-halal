@extends('layouts.doctor')

@section('title', 'Manage Milk Records (Doctor)')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>Milk Records Management</h1>
            <p>Milk Processing and Records</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Milk Records List</h2>
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
                        <option value="Labelling Completed" {{ request('filterStatus') == 'Labelling Completed' ? 'selected' : '' }}>Labelling Completed</option>
                        <option value="Thawing Completed" {{ request('filterStatus') == 'Thawing Completed' ? 'selected' : '' }}>Thawing Completed</option>
                        <option value="Pasteurization Completed" {{ request('filterStatus') == 'Pasteurization Completed' ? 'selected' : '' }}>Pasteurization Completed</option>
                        <option value="Microbiology Completed" {{ request('filterStatus') == 'Microbiology Completed' ? 'selected' : '' }}>Microbiology Completed</option>
                        <option value="Storage Completed" {{ request('filterStatus') == 'Storage Completed' ? 'selected' : '' }}>Storage Completed (Ready for Review)</option>
                    </select>

                    <div style="display:flex; gap:8px;">
                        <input id="volumeMin" name="volumeMin" value="{{ request('volumeMin') }}" class="form-control" type="number" min="0" placeholder="Min mL">
                        <input id="volumeMax" name="volumeMax" value="{{ request('volumeMax') }}" class="form-control" type="number" min="0" placeholder="Max mL">
                    </div>

                    <select id="filterShariah" name="filterShariah" class="form-control">
                        <option value="">All Shariah Status</option>
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
                    <button class="sortable-header" data-key="donor">MILK DONOR <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="status">CLINICAL STATUS <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="volume">VOLUME <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="shariah">SHARIAH APPROVAL <span class="sort-indicator"></span></button>
                    <span>ACTIONS</span>
                </div>

                @forelse($milks as $milk)
                    <div class="record-item" 
                         data-milk-id="{{ $milk->milk_ID }}"
                         data-name="{{ strtolower($milk->donor?->dn_FullName ?? '') }}"
                         data-status="{{ strtolower($milk->milk_Status ?? 'not yet started') }}" 
                         data-volume="{{ $milk->milk_volume }}"
                         data-shariah="{{ strtolower($milk->milk_shariahApproval ?? 'not yet reviewed') }}">
                        
                        {{-- 1. Donor --}}
                        <div class="milk-donor-info">
                            <div class="milk-icon-wrapper"><i class="fas fa-bottle-droplet milk-icon"></i></div>
                            <div>
                                <span class="milk-id">{{ $milk->formatted_id }}</span>
                                <span class="donor-name">{{ $milk->donor?->dn_FullName ?? 'Unknown Donor' }}</span>
                            </div>
                        </div>

                        {{-- 2. Clinical Status --}}
                        <div class="clinical-status">
                            @php
                                $rawStatus = $milk->milk_Status ?? 'Not Yet Started';
                                $fullCls = strtolower(str_replace(' ', '-', $rawStatus));
                                $baseCls = strtolower(explode(' ', $rawStatus)[0] ?? 'pending');
                            @endphp
                            <span class="status-tag status-{{ $baseCls }} status-{{ $fullCls }} status-disabled">
                                {{ ucfirst($rawStatus) }}
                            </span>
                        </div>

                        {{-- 3. Volume --}}
                        <div class="volume-data">{{ $milk->milk_volume }} mL</div>

                        {{-- 4. Shariah Approval --}}
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

                        {{-- 5. Actions --}}
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
                                    'postBottles' => $milk->postBottles
                                ];
                            @endphp
                            <button class="btn-view" title="Quick View" data-payload='@json($payload)'>
                                <i class="fas fa-eye"></i>
                            </button>
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
                    <tbody id="view-pre-bottles-list"></tbody>
                </table>
            </div>

            <div id="view-tab-processed" class="view-tab-content" style="padding-top: 10px; display: none;">
                <h3>Pasteurized Bottles</h3>
                <table class="view-table" style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                    <thead style="background: #f1f5f9;">
                        <tr>
                            <th style="padding: 8px; text-align: left;">Code</th>
                            <th style="padding: 8px; text-align: left;">Vol</th>
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
    // --- Modal Logic ---
    const viewModal = document.getElementById("viewMilkModal");
    window.addEventListener("click", (e) => { if (e.target === viewModal) viewModal.style.display = "none"; });
    function closeViewMilkModal() { viewModal.style.display = "none"; }

    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
            try {
                const data = JSON.parse(btn.getAttribute('data-payload'));
                openViewMilkModal(data);
            } catch (err) { console.error('Payload error', err); }
        });
    });

    function switchViewTab(tabName) {
        document.querySelectorAll('.view-tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.view-tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('view-tab-' + tabName).style.display = 'block';
        
        const btns = document.querySelectorAll('.view-tab-btn');
        if(tabName === 'raw') btns[0].classList.add('active');
        if(tabName === 'processed') btns[1].classList.add('active');
        if(tabName === 'qc') btns[2].classList.add('active');
    }

    function openViewMilkModal(data) {
        document.getElementById('view-milk-id').textContent = data.milkId;
        document.getElementById('view-donor-name').textContent = data.donorName;
        document.getElementById('view-volume').textContent = data.volume;
        document.getElementById('view-status').textContent = data.status;
        document.getElementById('view-shariah').textContent = data.shariah;
        document.getElementById('view-shariah-date').textContent = data.shariahApprovalDate;
        document.getElementById('view-shariah-remarks').textContent = data.shariahRemarks;

        const preList = document.getElementById('view-pre-bottles-list');
        preList.innerHTML = '';
        if (data.preBottles && data.preBottles.length > 0) {
            data.preBottles.forEach(b => {
                const thawed = b.pre_is_thawed ? '<span style="color:green">Yes</span>' : '<span style="color:gray">No</span>';
                preList.innerHTML += `<tr><td>${b.pre_bottle_code}</td><td>${b.pre_volume}</td><td>${thawed}</td></tr>`;
            });
        } else { preList.innerHTML = '<tr><td colspan="3" class="text-muted">No data.</td></tr>'; }

        const postList = document.getElementById('view-post-bottles-list');
        postList.innerHTML = '';
        if (data.postBottles && data.postBottles.length > 0) {
            data.postBottles.forEach(b => {
                let microColor = 'gray';
                if (b.post_micro_status?.includes('Pass') || b.post_micro_status?.includes('Not')) microColor = 'green';
                if (b.post_micro_status?.includes('Fail') || b.post_micro_status?.includes('Contaminated')) microColor = 'red';
                postList.innerHTML += `<tr><td>${b.post_bottle_code}</td><td>${b.post_volume}</td><td>${b.post_expiry_date || '-'}</td><td style="color:${microColor}; font-weight:bold;">${b.post_micro_status || 'Pending'}</td><td>${b.post_storage_location || '-'}</td></tr>`;
            });
        } else { postList.innerHTML = '<tr><td colspan="5" class="text-muted">No data.</td></tr>'; }

        switchViewTab('raw');
        viewModal.style.display = 'flex';
    }

    // --- Search & Filter Panel ---
    const panel = document.getElementById('filterPanel');
    document.querySelector('.btn-search').addEventListener('click', () => {
        panel.classList.toggle('active');
        if(panel.classList.contains('active')) document.getElementById('searchInput').focus();
    });
    document.getElementById('clearFilters').addEventListener('click', () => { window.location.href = '{{ url()->current() }}'; });

    // ==========================================
    //  SORTING LOGIC (With Visual Indicators)
    // ==========================================
    (function setupSorting() {
        const listContainer = document.querySelector('.records-list');
        if (!listContainer) return;
        const headerButtons = Array.from(document.querySelectorAll('.record-header .sortable-header'));

        function getValueForKey(row, key) {
            if (key === 'donor') return row.querySelector('.donor-name')?.textContent?.trim() || '';
            if (key === 'status') return row.querySelector('.clinical-status .status-tag')?.textContent?.trim() || '';
            if (key === 'volume') {
                const v = row.querySelector('.volume-data')?.textContent || '';
                return parseFloat(v.replace(/[^0-9.]/g, '')) || 0;
            }
            if (key === 'shariah') return row.querySelector('.shariah-status')?.textContent?.trim() || '';
            return ''; // Default
        }

        function sortBy(key, direction = 'asc') {
            const rows = Array.from(listContainer.querySelectorAll('.record-item'));
            const multiplier = direction === 'asc' ? 1 : -1;

            rows.sort((a, b) => {
                const va = getValueForKey(a, key);
                const vb = getValueForKey(b, key);

                if (typeof va === 'number' && typeof vb === 'number') {
                    return (va - vb) * multiplier;
                }
                return va.toString().localeCompare(vb.toString()) * multiplier;
            });

            // Re-append rows after header
            const header = listContainer.querySelector('.record-header');
            rows.forEach(r => header.after(r));

            // Reset pagination to Page 1 after sorting
            if (window.__rebuildPagination) window.__rebuildPagination(1);
        }

        function clearIndicators() {
            headerButtons.forEach(btn => {
                btn.classList.remove('sorted-asc', 'sorted-desc');
                const ind = btn.querySelector('.sort-indicator');
                if(ind) ind.textContent = '';
            });
        }

        // Initialize state
        let activeKey = ''; 
        let activeDir = 'asc';

        headerButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.key;
                if (!key) return;

                if (activeKey === key) {
                    activeDir = (activeDir === 'asc') ? 'desc' : 'asc';
                } else {
                    activeKey = key;
                    activeDir = 'asc';
                }

                clearIndicators();
                btn.classList.add(activeDir === 'asc' ? 'sorted-asc' : 'sorted-desc');
                const ind = btn.querySelector('.sort-indicator');
                if(ind) ind.textContent = activeDir === 'asc' ? '▲' : '▼';

                sortBy(activeKey, activeDir);
            });
        });
    })();


    // ==========================================
    //  PAGINATION LOGIC (Numbered Pages)
    // ==========================================
    (function setupPagination() {
        const listContainer = document.querySelector('.records-list');
        const controls = document.getElementById('paginationControls');
        const perPage = 10;
        let currentPage = 1;

        if (!listContainer || !controls) return;

        function getRows() {
            // Get all rows (ignoring any potential future client-side filter logic for now)
            return Array.from(listContainer.querySelectorAll('.record-item'));
        }

        function renderPage(page) {
            const rows = getRows();
            const totalPages = Math.max(1, Math.ceil(rows.length / perPage));

            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPage = page;

            // Hide all rows first
            rows.forEach(r => r.style.display = 'none');

            // Show current slice
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            rows.slice(start, end).forEach(r => r.style.display = 'grid'); // Use grid to match layout

            renderControls(totalPages);
        }

        function renderControls(totalPages) {
            controls.innerHTML = '';

            // Prev Button
            const prev = document.createElement('button');
            prev.className = 'page-btn';
            prev.innerHTML = '&lsaquo; Prev';
            prev.disabled = currentPage === 1;
            prev.onclick = () => renderPage(currentPage - 1);
            controls.appendChild(prev);

            // Numbered Buttons
            // Logic to show a window of pages (e.g. 1 2 ... 5 6 7 ... 10) can be added here
            // For now, simple loop for all pages (or limited if lots of pages)
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = 'page-btn';
                if (i === currentPage) btn.classList.add('active');
                btn.textContent = i;
                btn.onclick = () => renderPage(i);
                controls.appendChild(btn);
            }

            // Next Button
            const next = document.createElement('button');
            next.className = 'page-btn';
            next.innerHTML = 'Next &rsaquo;';
            next.disabled = currentPage === totalPages;
            next.onclick = () => renderPage(currentPage + 1);
            controls.appendChild(next);
        }

        // Expose function for sorting to reset page
        window.__rebuildPagination = function(page) {
            renderPage(page || 1);
        };

        // Initial Render
        renderPage(1);
    })();
</script>
@endsection