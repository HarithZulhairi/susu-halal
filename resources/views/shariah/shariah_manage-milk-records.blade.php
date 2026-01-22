@extends('layouts.shariah')

@section('title', 'Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* --- Status Badge Styles --- */
    .status-tag {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    /* Clinical Stages */
    .status-screening { background-color: #fff7ed; color: #c2410c; border-color: #ffedd5; }
    .status-thawing { background-color: #eff6ff; color: #1d4ed8; border-color: #dbeafe; }
    .status-pasteurization { background-color: #eef2ff; color: #4338ca; border-color: #e0e7ff; }
    .status-microbiology { background-color: #f0fdf4; color: #15803d; border-color: #dcfce7; }
    .status-post-pasteurization { background-color: #ecfdf5; color: #047857; border-color: #d1fae5; }
    
    /* Pending Status */
    .status-pending { background-color: #f1f5f9; color: #94a3b8; border-color: #e2e8f0; cursor: not-allowed; }

    /* --- Action Button (The "Review" Link) --- */
    .btn-review-link {
        background-color: #1A5F7A;
        color: #ffffff !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(26, 95, 122, 0.2);
        border: none;
        cursor: pointer;
    }

    .btn-review-link:hover {
        background-color: #144d63;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(26, 95, 122, 0.3);
    }

    .btn-review-link i {
        font-size: 14px;
    }

    /* Profile Icon */
    .milk-icon-wrapper {
        background-color: #f1f5f9;
        color: #64748b;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>

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

            <div id="filterPanel" class="filter-panel">
                <form id="filterForm" onsubmit="event.preventDefault();">
                    <input id="searchInput" class="form-control" type="search" placeholder="Search by Donor name or Milk ID">
                    <div class="filter-actions">
                        <button type="submit" class="btn">Apply</button>
                        <button type="button" class="btn">Clear</button>
                    </div>
                </form>
            </div>

            <div class="records-list">
                <div class="record-header">
                    <button class="sortable-header" data-key="donor">MILK DONOR <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="status">CLINICAL STATUS <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="volume">VOLUME <span class="sort-indicator"></span></button>
                    <button class="sortable-header" data-key="expiry">EXPIRATION DATE <span class="sort-indicator"></span></button>
                    {{-- Target Column for Shariah Action --}}
                    <button class="sortable-header" data-key="shariah">SHARIAH APPROVAL <span class="sort-indicator"></span></button>
                </div>

                @php
                    // DUMMY DATA FOR DEMONSTRATION
                    $milks = [
                        [
                            'id' => 1, 'milkId' => 'M26-001', 'donor' => 'Siti Aminah',
                            'status' => 'Pre-Pasteurization', 'css' => 'status-screening',
                            'volume' => 150, 'expiry' => '-', 'shariah' => 'Pending'
                        ],
                        [
                            'id' => 2, 'milkId' => 'M26-002', 'donor' => 'Nurul Huda',
                            'status' => 'Thawing', 'css' => 'status-thawing',
                            'volume' => 200, 'expiry' => '-', 'shariah' => 'Pending'
                        ],
                        [
                            'id' => 3, 'milkId' => 'M26-003', 'donor' => 'Aishah Ahmad',
                            'status' => 'Pasteurization', 'css' => 'status-pasteurization',
                            'volume' => 120, 'expiry' => '22 Jul 2026', 'shariah' => 'Pending'
                        ],
                        [
                            'id' => 4, 'milkId' => 'M26-004', 'donor' => 'Fatima Zahra',
                            'status' => 'Microbiology Test', 'css' => 'status-microbiology',
                            'volume' => 180, 'expiry' => '20 Jul 2026', 'shariah' => 'Pending'
                        ],
                        // THIS ONE IS READY FOR APPROVAL
                        [
                            'id' => 5, 'milkId' => 'M26-005', 'donor' => 'Mariam Isa',
                            'status' => 'Post-Pasteurization', 'css' => 'status-post-pasteurization',
                            'volume' => 200, 'expiry' => '15 Jul 2026', 'shariah' => 'Ready'
                        ]
                    ];
                @endphp

                @foreach($milks as $milk)
                <div class="record-item">
                    {{-- 1. Donor Info --}}
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper"><i class="fas fa-user"></i></div>
                        <div>
                            <span class="milk-id">{{ $milk['milkId'] }}</span>
                            <span class="donor-name">{{ $milk['donor'] }}</span>
                        </div>
                    </div>

                    {{-- 2. Clinical Status --}}
                    <div class="clinical-status">
                        <span class="status-tag {{ $milk['css'] }}">
                            {{ $milk['status'] }}
                        </span>
                    </div>

                    {{-- 3. Volume --}}
                    <div class="volume-data">{{ $milk['volume'] }} mL</div>

                    {{-- 4. Expiry --}}
                    <div class="expiry-date">{{ $milk['expiry'] }}</div>

                    {{-- 5. SHARIAH APPROVAL COLUMN (THE LOGIC) --}}
                    <div class="shariah-status">
                        @if($milk['status'] === 'Post-Pasteurization')
                            {{-- ACTIVE BUTTON: Links to the View/Audit Page --}}
                            {{-- Note: In a real app, pass ID like route('...', ['id' => $milk['id']]) --}}
                            <a href="{{ route('shariah.view-milk-processing') }}?id={{$milk['id']}}" class="btn-review-link">
                                <i class="fas fa-gavel"></i> Review & Decide
                            </a>
                        @else
                            {{-- DISABLED BADGE: Waiting for clinical process --}}
                            <span class="status-tag status-pending">
                                <i class="fas fa-hourglass-start"></i> Pending Clinical
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<script>
    // Simple filter toggle
    document.querySelector('.btn-search').addEventListener('click', function() {
        var panel = document.getElementById('filterPanel');
        panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
    });
</script>
@endsection