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
                    <button class="btn btn-search">
                        <i class="fas fa-search"></i> Search &amp; Filter
                    </button>
                </div>
            </div>

            <!-- FILTER PANEL -->
            <div id="filterPanel" class="filter-panel">
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
                        <button type="submit" class="btn">Apply</button>
                        <button type="button" class="btn" onclick="window.location='{{ url()->current() }}'">Clear</button>
                    </div>
                </form>
            </div>

            <div class="records-list">
                <!-- Table Header -->
                <div class="record-header">
                    <button class="sortable-header" data-key="donor">MILK DONOR <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="status">CLINICAL STATUS <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="volume">VOLUME <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="expiry">EXPIRATION DATE <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="shariah">SHARIAH APPROVAL <span class="sort-indicator"></span></button>
                    <span>ACTIONS</span>
                </div>

                @forelse($milks as $milk)
                    @php
                        $viewPayload = [
                            'milkId'     => $milk->formatted_id,
                            'donorName'  => $milk->donor?->dn_FullName ?? 'Unknown Donor',
                            'status'     => ucfirst($milk->milk_Status ?? 'Not Yet Started'),
                            'volume'     => $milk->milk_volume . ' mL',
                            'expiry'     => $milk->milk_expiryDate ? \Carbon\Carbon::parse($milk->milk_expiryDate)->format('M d, Y') : '-',
                            'shariah'    => is_null($milk->milk_shariahApproval) ? 'Not Yet Reviewed' : ($milk->milk_shariahApproval ? 'Approved' : 'Rejected'),
                            'stage1Start' => $milk->milk_stage1StartDate && $milk->milk_stage1StartTime ? $milk->milk_stage1StartDate . ' ' . $milk->milk_stage1StartTime : '-',
                            'stage1End'   => $milk->milk_stage1EndDate && $milk->milk_stage1EndTime ? $milk->milk_stage1EndDate . ' ' . $milk->milk_stage1EndTime : '-',
                            'stage1Result'=> $milk->milk_stage1Result,
                            'stage2Start' => $milk->milk_stage2StartDate && $milk->milk_stage2StartTime ? $milk->milk_stage2StartDate . ' ' . $milk->milk_stage2StartTime : '-',
                            'stage2End'   => $milk->milk_stage2EndDate && $milk->milk_stage2EndTime ? $milk->milk_stage2EndDate . ' ' . $milk->milk_stage2EndTime : '-',
                            'stage3Start' => $milk->milk_stage3StartDate && $milk->milk_stage3StartTime ? $milk->milk_stage3StartDate . ' ' . $milk->milk_stage3StartTime : '-',
                            'stage3End'   => $milk->milk_stage3EndDate && $milk->milk_stage3EndTime ? $milk->milk_stage3EndDate . ' ' . $milk->milk_stage3EndTime : '-',
                        ];
                    @endphp

                    <div class="record-item"
                         data-milk-id="{{ $milk->milk_ID }}"
                         data-name="{{ strtolower($milk->donor?->dn_FullName ?? '') }}"
                         data-status="{{ strtolower($milk->milk_Status ?? 'not yet started') }}"
                         data-volume="{{ $milk->milk_volume }}"
                         data-expiry="{{ $milk->milk_expiryDate }}"
                         data-shariah="{{ strtolower($milk->milk_shariahApproval ?? 'not yet reviewed') }}">

                        <!-- Donor Info -->
                        <div class="milk-donor-info">
                            <div class="milk-icon-wrapper">
                                <i class="fas fa-bottle-droplet milk-icon"></i>
                            </div>
                            <div>
                                <span class="milk-id">{{ $milk->formatted_id }}</span>
                                <span class="donor-name">{{ $milk->donor?->dn_FullName ?? 'Unknown Donor' }}</span>
                            </div>
                        </div>

                        <!-- Clinical Status (NOT clickable) -->
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

                        <!-- Volume -->
                        <div class="volume-data">{{ $milk->milk_volume }} mL</div>

                        <!-- Expiry Date -->
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

                        <!-- Shariah Approval -->
                        <div class="shariah-status">
                            @php $approval = $milk->milk_shariahApproval; @endphp
                            <span class="status-tag {{ is_null($approval) ? 'status-pending' : ($approval ? 'status-approved' : 'status-rejected') }}">
                                {{ is_null($approval) ? 'Not Yet Reviewed' : ($approval ? 'Approved' : 'Rejected') }}
                            </span>
                        </div>

                        <!-- Actions: Only View -->
                        <div class="actions">
                            <button class="btn-view" title="View Details" data-payload="{{ json_encode($viewPayload) }}">
                                <i class="fas fa-eye"></i>
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

{{-- VIEW MODAL --}}
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
            <div class="stage-block"><div class="stage-row"><strong>Screening:</strong> <span id="view-stage1-start"></span> <span class="dash">—</span> <span id="view-stage1-end"></span></div></div>
            <div class="stage-block"><div class="stage-row"><strong>Screening Result:</strong> <span id="view-stage1-result"></span></div></div>
            <div class="stage-block"><div class="stage-row"><strong>Labelling:</strong> <span id="view-stage2-start"></span> <span class="dash">—</span> <span id="view-stage2-end"></span></div></div>
            <div class="stage-block"><div class="stage-row"><strong>Distributing:</strong> <span id="view-stage3-start"></span> <span class="dash">—</span> <span id="view-stage3-end"></span></div></div>
            <hr>
            <h3>Quality Control</h3>
            <p><strong>Shariah Approval:</strong> <span id="view-shariah"></span></p>
        </div>
    </div>
</div>

{{-- ===========================
      POPUP SCRIPT
=========================== --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const viewModal = document.getElementById("viewMilkModal");

    // Filter panel toggle
    document.querySelector('.btn-search')?.addEventListener('click', () => {
        document.getElementById('filterPanel').classList.toggle('active');
    });

    // Close modal on backdrop click
    window.addEventListener('click', e => {
        if (e.target === viewModal) viewModal.style.display = 'none';
    });

    // View button → open modal
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
            try {
                const data = JSON.parse(btn.dataset.payload);
                openViewMilkModal(data);
            } catch (err) {
                console.error("Payload error:", err);
            }
        });
    });

    // ====================== SORTING ======================
    (function () {
        let sortKey = 'milkId', sortDir = 'desc';
        const headers = document.querySelectorAll('.record-header .sortable-header');

        function getValue(row, key) {
            if (key === 'donor') return row.querySelector('.donor-name')?.textContent.trim() || '';
            if (key === 'status') return row.querySelector('.clinical-status .status-tag')?.textContent.trim() || '';
            if (key === 'volume') return parseFloat(row.querySelector('.volume-data')?.textContent) || 0;
            if (key === 'expiry') {
                const txt = row.querySelector('.expiry-date')?.firstChild?.textContent?.trim() || '';
                return txt === '-' || txt.includes('expired') ? Infinity : Date.parse(txt);
            }
            if (key === 'shariah') return row.querySelector('.shariah-status .status-tag')?.textContent.trim() || '';
            if (key === 'milkId') return parseInt(row.dataset.milkId) || 0;
            return '';
        }

        function sortRows() {
            const rows = Array.from(document.querySelectorAll('.record-item'));
            rows.sort((a, b) => {
                let va = getValue(a, sortKey), vb = getValue(b, sortKey);
                if (typeof va === 'number' && typeof vb === 'number') return sortDir === 'asc' ? va - vb : vb - va;
                va = String(va).toLowerCase(); vb = String(vb).toLowerCase();
                return sortDir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
            });
            const container = document.querySelector('.records-list');
            const header = container.querySelector('.record-header');
            rows.forEach(r => header.after(r));
        }

        headers.forEach(h => h.addEventListener('click', () => {
            const key = h.dataset.key;
            if (sortKey === key) sortDir = sortDir === 'asc' ? 'desc' : 'asc';
            else { sortKey = key; sortDir = 'asc'; }
            document.querySelectorAll('.sort-indicator').forEach(i => i.textContent = '');
            h.querySelector('.sort-indicator').textContent = sortDir === 'asc' ? '▲' : '▼';
            sortRows();
            window.__rebuildPagination?.(1);
        }));

        sortRows(); // initial sort
    })();

    // ====================== PAGINATION ======================
    (function () {
        const perPage = 10;
        let page = 1;

        function render() {
            const rows = Array.from(document.querySelectorAll('.record-item'));
            const total = Math.max(1, Math.ceil(rows.length / perPage));
            if (page > total) page = total;

            rows.forEach((r, i) => r.style.display = (i >= (page-1)*perPage && i < page*perPage) ? '' : 'none');

            const ctrl = document.getElementById('paginationControls');
            ctrl.innerHTML = '';
            // prev
            const prev = Object.assign(document.createElement('button'), {className:'page-btn', textContent:'‹ Prev', disabled:page===1});
            prev.onclick = () => {page--; render();};
            ctrl.appendChild(prev);
            // numbers
            for (let i=1; i<=total; i++) {
                const b = Object.assign(document.createElement('button'), {className:'page-btn', textContent:i});
                if (i===page) b.classList.add('active');
                b.onclick = () => {page=i; render();};
                ctrl.appendChild(b);
            }
            // next
            const next = Object.assign(document.createElement('button'), {className:'page-btn', textContent:'Next ›', disabled:page===total});
            next.onclick = () => {page++; render();};
            ctrl.appendChild(next);
        }
        window.__rebuildPagination = () => {page=1; render();};
        render();
    })();

    // ====================== LIVE STATUS + COUNTDOWN (FIXED!) ======================
    const postedCompletions = new Set();

    function formatTime(ms) {
        if (ms <= 0) return null;
        const h = Math.floor(ms / 3600000);
        const m = Math.floor((ms % 3600000) / 60000);
        const s = Math.floor((ms % 60000) / 1000);
        return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }

    async function pollStatuses() {
        try {
            const res = await fetch("{{ route('milk-statuses') }}", {headers:{'Accept':'application/json'}});
            if (!res.ok) return;
            const items = await res.json();

            items.forEach(item => {
                const row = document.querySelector(`.record-item[data-milk-id="${item.milk_ID}"]`);
                if (!row) return;
                const tag = row.querySelector('.clinical-status .status-tag');
                if (!tag) return;

                let status = item.milk_Status || 'Not Yet Started';
                const base = (status.split(' ')[0] || '').toLowerCase();

                // Find end date/time for current active stage
                let endDate = null, endTime = null;
                if (base === 'screening')   { endDate = item.milk_stage1EndDate;   endTime = item.milk_stage1EndTime; }
                if (base === 'labelling')   { endDate = item.milk_stage2EndDate;   endTime = item.milk_stage2EndTime; }
                if (base === 'distributing'){ endDate = item.milk_stage3EndDate; endTime = item.milk_stage3EndTime; }

                // Build proper Date object
                let endTimestamp = null;
                if (endDate && endTime) {
                    // Normalize time: accept HH:MM or HH:MM:SS
                    const timePart = endTime.length === 8 ? endTime : endTime + ':00';
                    const dtStr = `${endDate}T${timePart}`;
                    const dt = new Date(dtStr);
                    if (!isNaN(dt.getTime())) endTimestamp = dt.getTime();
                }

                // Update UI
                if (endTimestamp) {
                    const remaining = endTimestamp - Date.now();
                    if (remaining <= 0) {
                        // Time finished
                        if (base === 'screening') {
                            status = item.milk_stage1Result ? 'Screening Completed' : 'Screening • Waiting for results';
                        } else {
                            status = base === 'labelling' ? 'Labelling Completed' : 'Distributing Completed';
                        }
                        delete tag.dataset.endTs;
                    } else {
                        // Still counting
                        const timeStr = formatTime(remaining);
                        status = `${status.split(' ')[0]} • ${timeStr}`;
                        tag.dataset.endTs = endTimestamp;
                        tag.dataset.stage = status.split(' ')[0];
                    }
                }

                // Apply text + classes
                tag.textContent = status;
                const norm = status.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-');
                const baseCls = base || 'pending';
                tag.className = `status-tag status-${baseCls} status-${norm}`;
            });
        } catch (e) {
            console.error("Poll error:", e);
        }
    }

    // Initial + every 10 seconds
    pollStatuses();
    setInterval(pollStatuses, 10000);

    // Per-second ticker (updates countdowns)
    setInterval(() => {
        document.querySelectorAll('.clinical-status .status-tag[data-end-ts]').forEach(tag => {
            const end = parseInt(tag.dataset.endTs);
            const diff = end - Date.now();
            if (diff <= 0) {
                const stage = tag.dataset.stage;
                const completed = stage === 'Screening' ? 'Screening Completed' : `${stage} Completed`;
                tag.textContent = completed;
                tag.className = `status-tag status-${stage.toLowerCase()} status-${completed.toLowerCase().replace(/\s+/g,'-')}`;
                delete tag.dataset.endTs;
                delete tag.dataset.stage;
            } else {
                const timeStr = formatTime(diff);
                const stage = tag.dataset.stage;
                tag.textContent = `${stage} • ${timeStr}`;
            }
        });
    }, 1000);
});

// ====================== VIEW MODAL ======================
function openViewMilkModal(data) {
    document.getElementById('view-milk-id').textContent     = data.milkId || '-';
    document.getElementById('view-donor-name').textContent = data.donorName || '-';
    document.getElementById('view-status').textContent     = data.status || '-';
    document.getElementById('view-volume').textContent     = data.volume || '-';
    document.getElementById('view-expiry').textContent     = data.expiry || '-';
    document.getElementById('view-shariah').textContent    = data.shariah || '-';

    document.getElementById('view-stage1-start').textContent = data.stage1Start || '-';
    document.getElementById('view-stage1-end').textContent   = data.stage1End || '-';
    document.getElementById('view-stage2-start').textContent = data.stage2Start || '-';
    document.getElementById('view-stage2-end').textContent   = data.stage2End || '-';
    document.getElementById('view-stage3-start').textContent = data.stage3Start || '-';
    document.getElementById('view-stage3-end').textContent   = data.stage3End || '-';

    document.getElementById('viewMilkModal').style.display = 'flex';

    // FIXED SCREENING RESULT DISPLAY
    const resultEl = document.getElementById('view-stage1-result');
    if (!data.stage1Result || data.stage1Result === '' || data.stage1Result === 'null') {
        resultEl.textContent = '-';
        return;
    }

    try {
        const parsed = typeof data.stage1Result === 'string' ? JSON.parse(data.stage1Result) : data.stage1Result;

        if (Array.isArray(parsed) && parsed.length > 0) {
            const lines = parsed.map(item => {
                // Your format: { "contents": "E Coli", "tolerance": "Passed" }
                if (item.contents && item.tolerance) {
                    return `• ${item.contents} - ${item.tolerance}`;
                }
                // Fallback for any other key names
                const keys = Object.keys(item);
                if (keys.length >= 2) {
                    const name = item.contents || item.name || item.test || keys[0];
                    const value = item.tolerance || item.result || item.status || item.pass || keys[1];
                    return `• ${name} - ${value}`;
                }
                return `• ${JSON.stringify(item)}`;
            });
            resultEl.innerHTML = lines.join('<br>');
        } else {
            resultEl.textContent = 'Completed';
        }
    } catch (e) {
        resultEl.textContent = data.stage1Result; // fallback raw
    }

    
}

function closeViewMilkModal() {
    document.getElementById('viewMilkModal').style.display = 'none';
}
</script>


@endsection
