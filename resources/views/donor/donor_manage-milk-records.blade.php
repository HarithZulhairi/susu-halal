@extends('layouts.donor')

@section('title', 'Manage Milk Records (Donor)')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

</style>

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>My Milk Records</h1>
            <p>Track the processing status of your donations</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Donation History</h2>
                <div class="actions-header">
                    <button class="btn btn-search"><i class="fas fa-search"></i> Search &amp; Filter</button>
                </div>
            </div>

            <div id="filterPanel" class="filter-panel" role="region" aria-label="Search and filters">
                <form id="filterForm" method="GET" action="{{ url()->current() }}" autocomplete="off">
                    <input id="searchInput" name="searchInput" value="{{ request('searchInput') }}" class="form-control" type="search" placeholder="Search by Milk ID">

                    <select id="filterStatus" name="filterStatus" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="Not Yet Started" {{ request('filterStatus') == 'Not Yet Started' ? 'selected' : '' }}>Received</option>
                        <option value="Labelling Completed" {{ request('filterStatus') == 'Labelling Completed' ? 'selected' : '' }}>Labelling</option>
                        <option value="Thawing Completed" {{ request('filterStatus') == 'Thawing Completed' ? 'selected' : '' }}>Thawing</option>
                        <option value="Pasteurization Completed" {{ request('filterStatus') == 'Pasteurization Completed' ? 'selected' : '' }}>Pasteurization</option>
                        <option value="Microbiology Completed" {{ request('filterStatus') == 'Microbiology Completed' ? 'selected' : '' }}>Microbiology</option>
                        <option value="Storage Completed" {{ request('filterStatus') == 'Storage Completed' ? 'selected' : '' }}>Stored / Completed</option>
                    </select>

                    <div style="display:flex; gap:8px;">
                        <input id="volumeMin" name="volumeMin" value="{{ request('volumeMin') }}" class="form-control" type="number" min="0" placeholder="Min mL">
                        <input id="volumeMax" name="volumeMax" value="{{ request('volumeMax') }}" class="form-control" type="number" min="0" placeholder="Max mL">
                    </div>

                    <div class="filter-actions">
                        <button id="applyFilters" class="btn" type="submit">Apply</button>
                        <button id="clearFilters" class="btn" type="button" onclick="window.location='{{ url()->current() }}'">Clear</button>
                    </div>
                </form>
            </div>

            <div class="records-list">
                <div class="record-header">
                    <button class="sortable-header" data-key="id">MILK ID <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="status">STATUS <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="volume">VOLUME <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="shariah">SHARIAH <span class="sort-indicator"></span></button>
                    <span>DETAILS</span>
                </div>

                @forelse($milks as $milk)
                    <div class="record-item" 
                         data-id="{{ $milk->formatted_id }}"
                         data-status="{{ strtolower($milk->milk_Status ?? 'not yet started') }}" 
                         data-volume="{{ $milk->milk_volume }}"
                         data-shariah="{{ strtolower($milk->milk_shariahApproval ?? 'not yet reviewed') }}">
                        
                        {{-- 1. Milk ID --}}
                        <div class="milk-donor-info">
                            <div class="milk-icon-wrapper"><i class="fas fa-bottle-droplet milk-icon"></i></div>
                            <div>
                                <span class="milk-id">{{ $milk->formatted_id }}</span>
                                <span class="donor-name" style="font-size:11px;">{{ \Carbon\Carbon::parse($milk->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- 2. Status --}}
                        <div class="clinical-status">
                            @php
                                $rawStatus = $milk->milk_Status ?? 'Received';
                                $fullCls = strtolower(str_replace(' ', '-', $rawStatus));
                                $baseCls = strtolower(explode(' ', $rawStatus)[0] ?? 'pending');
                                $displayStatus = ($rawStatus == 'Not Yet Started') ? 'Received' : ucfirst($rawStatus);
                            @endphp
                            <span class="status-tag status-{{ $baseCls }} status-{{ $fullCls }} status-disabled">
                                {{ $displayStatus }}
                            </span>
                        </div>

                        {{-- 3. Volume --}}
                        <div class="volume-data">{{ $milk->milk_volume }} mL</div>

                        {{-- 4. Shariah --}}
                        <div class="shariah-status">
                            @php $approval = $milk->milk_shariahApproval; @endphp
                            <span class="status-tag {{ is_null($approval) ? 'status-pending' : ($approval ? 'status-approved' : 'status-rejected') }}">
                                {{ is_null($approval) ? 'In Review' : ($approval ? 'Approved' : 'Rejected') }}
                            </span>
                        </div>

                        {{-- 5. Actions --}}
                        <div class="actions">
                            @php
                                $fmt = function($d) { return $d ? \Carbon\Carbon::parse($d)->format('d M Y, h:i A') : null; };
                                $payload = [
                                    'milkId' => $milk->formatted_id,
                                    'status' => $displayStatus,
                                    'volume' => $milk->milk_volume . ' mL',
                                    'shariah' => is_null($milk->milk_shariahApproval) ? 'In Review' : ($milk->milk_shariahApproval ? 'Approved' : 'Rejected'),
                                    'stage1' => $fmt($milk->milk_stage1StartDate),
                                    'stage2' => $fmt($milk->milk_stage2StartDate),
                                    'stage3' => $fmt($milk->milk_stage3StartDate),
                                    'stage4' => $fmt($milk->milk_stage4StartDate),
                                    'stage5' => $fmt($milk->milk_stage5StartDate),
                                ];
                            @endphp
                            <button class="btn-view" title="Track Process" data-payload='@json($payload)'>
                                <i class="fas fa-list-ul"></i> Track
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="record-item text-center text-muted py-5">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>You have no milk records yet.</p>
                    </div>
                @endforelse
                
                {{-- Pagination Container --}}
                <div id="paginationControls" class="pagination-controls"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== VIEW MODAL ===================== --}}
<div id="viewMilkModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 100%;">
        <div class="modal-header">
            <h2>Process Tracking</h2>
            <button class="modal-close-btn" onclick="closeViewMilkModal()">Close</button>
        </div>

        <div class="modal-body">
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <p><strong>Batch ID:</strong> <span id="view-milk-id" style="color: #1A5F7A; font-weight: bold;"></span></p>
                    <p><strong>Volume:</strong> <span id="view-volume"></span></p>
                    <p><strong>Status:</strong> <span id="view-status"></span></p>
                </div>
            </div>

            <ul class="tracking-list">
                <li class="tracking-item">
                    <span class="tracking-stage"><i class="fas fa-flask icon-stage"></i> Stage 1: Screening</span>
                    <span class="tracking-date" id="view-stage1"></span>
                </li>
                <li class="tracking-item">
                    <span class="tracking-stage"><i class="fas fa-snowflake icon-stage"></i> Stage 2: Thawing</span>
                    <span class="tracking-date" id="view-stage2"></span>
                </li>
                <li class="tracking-item">
                    <span class="tracking-stage"><i class="fas fa-fire-burner icon-stage"></i> Stage 3: Pasteurization</span>
                    <span class="tracking-date" id="view-stage3"></span>
                </li>
                <li class="tracking-item">
                    <span class="tracking-stage"><i class="fas fa-microscope icon-stage"></i> Stage 4: Microbiology</span>
                    <span class="tracking-date" id="view-stage4"></span>
                </li>
                <li class="tracking-item">
                    <span class="tracking-stage"><i class="fas fa-box-archive icon-stage"></i> Stage 5: Final Storage</span>
                    <span class="tracking-date" id="view-stage5"></span>
                </li>
            </ul>

            <div class="shariah-box">
                <strong><i class="fas fa-scale-balanced"></i> Shariah Status:</strong> 
                <span id="view-shariah" style="margin-left: 5px; font-weight: bold;"></span>
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

    function openViewMilkModal(data) {
        document.getElementById('view-milk-id').textContent = data.milkId;
        document.getElementById('view-volume').textContent = data.volume;
        document.getElementById('view-status').textContent = data.status;
        
        const shariahEl = document.getElementById('view-shariah');
        shariahEl.textContent = data.shariah;
        if(data.shariah === 'Approved') shariahEl.style.color = 'green';
        else if(data.shariah === 'Rejected') shariahEl.style.color = 'red';
        else shariahEl.style.color = '#d97706';

        const setDate = (id, dateStr) => {
            const el = document.getElementById(id);
            if (dateStr) {
                el.textContent = dateStr;
                el.className = 'tracking-date';
            } else {
                el.textContent = 'Pending';
                el.className = 'tracking-date date-pending';
            }
        };

        setDate('view-stage1', data.stage1);
        setDate('view-stage2', data.stage2);
        setDate('view-stage3', data.stage3);
        setDate('view-stage4', data.stage4);
        setDate('view-stage5', data.stage5);

        viewModal.style.display = 'flex';
    }

    // --- Search & Filter Toggle ---
    const panel = document.getElementById('filterPanel');
    document.querySelector('.btn-search').addEventListener('click', () => {
        panel.classList.toggle('active');
        if(panel.classList.contains('active')) document.getElementById('searchInput').focus();
    });
    document.getElementById('clearFilters').addEventListener('click', () => { window.location.href = '{{ url()->current() }}'; });

    // --- Sorting & Pagination Logic ---
    (function setupList() {
        const container = document.querySelector('.records-list');
        const controls = document.getElementById('paginationControls');
        const perPage = 10;
        let currentPage = 1;

        if(!container) return;

        // Sorting
        document.querySelectorAll('.sortable-header').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.key;
                const rows = Array.from(container.querySelectorAll('.record-item'));
                const isAsc = !btn.classList.contains('sorted-asc');
                
                document.querySelectorAll('.sortable-header').forEach(b => b.classList.remove('sorted-asc', 'sorted-desc'));
                btn.classList.add(isAsc ? 'sorted-asc' : 'sorted-desc');

                rows.sort((a, b) => {
                    let va = a.dataset[key] || '', vb = b.dataset[key] || '';
                    if(key === 'volume') { va = parseFloat(va) || 0; vb = parseFloat(vb) || 0; }
                    if(va < vb) return isAsc ? -1 : 1;
                    if(va > vb) return isAsc ? 1 : -1;
                    return 0;
                });

                const header = container.querySelector('.record-header');
                rows.forEach(r => header.after(r));
                renderPage(1);
            });
        });

        // Pagination
        function renderPage(page) {
            const rows = Array.from(container.querySelectorAll('.record-item'));
            const total = Math.ceil(rows.length / perPage);
            currentPage = page < 1 ? 1 : (page > total && total > 0 ? total : page);
            
            // Hide all rows
            rows.forEach((r, i) => r.style.display = 'none');
            // Show slice
            rows.slice((currentPage-1)*perPage, currentPage*perPage).forEach(r => r.style.display = 'grid');

            // Render Controls
            if(controls) {
                controls.innerHTML = '';
                
                // Prev
                const prev = document.createElement('button');
                prev.className = 'page-btn';
                prev.innerHTML = '&lsaquo; Prev';
                prev.disabled = currentPage === 1;
                prev.onclick = () => renderPage(currentPage - 1);
                controls.appendChild(prev);

                // Page Numbers
                for(let i=1; i<=total; i++) {
                    const btn = document.createElement('button');
                    btn.className = `page-btn ${i===currentPage?'active':''}`;
                    btn.textContent = i;
                    btn.onclick = () => renderPage(i);
                    controls.appendChild(btn);
                }

                // Next
                const next = document.createElement('button');
                next.className = 'page-btn';
                next.innerHTML = 'Next &rsaquo;';
                next.disabled = currentPage === total || total === 0;
                next.onclick = () => renderPage(currentPage + 1);
                controls.appendChild(next);
            }
        }
        
        // Initial Render
        renderPage(1);
    })();
</script>
@endsection