@extends('layouts.shariah')

@section('title', 'Milk Compliance Audit')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_view-milk-processing.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{{-- ========================================================= --}}
{{-- DUMMY DATA SIMULATION (No Backend Required) --}}
{{-- ========================================================= --}}
@php
    $currentId = request('id', 5); 

    // Define Dummy Data
    $milk = (object) [
        'milk_ID' => $currentId,
        'formatted_id' => 'M26-00' . $currentId,
        'milk_volume' => 200,
        'milk_Status' => 'Post-Pasteurization', 
        'milk_shariahApproval' => null, 
        'milk_shariahRemarks' => '',
        'donor' => (object) ['dn_FullName' => 'Mariam Isa'],
        
        // Stage Dates
        'stage1_end' => '2026-01-15 09:00:00',
        'stage2_end' => '2026-01-15 14:00:00',
        'stage3_end' => '2026-01-16 10:00:00',
        'stage4_end' => '2026-01-17 16:00:00',
        'stage5_end' => '2026-01-18 09:00:00',
        
        // Expiry
        'expiry_date' => '2026-07-15'
    ];

    $isReadyForDecision = str_contains(strtolower($milk->milk_Status), 'post-pasteurization');
@endphp

<div class="container">
    <div class="main-content">
        
        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Compliance Audit: {{ $milk->formatted_id }}</h1>
                <p class="page-subtitle">Donor: {{ $milk->donor->dn_FullName }}</p>
            </div>
            <a href="{{ route('shariah.shariah_manage-milk-records') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        {{-- TOP OVERVIEW CARD --}}
        <div class="overview-card">
            <div class="overview-item">
                <div class="icon-box blue"><i class="fas fa-flask"></i></div>
                <div>
                    <label>Current Volume</label>
                    <span>{{ $milk->milk_volume }} mL</span>
                </div>
            </div>
            <div class="overview-item">
                <div class="icon-box purple"><i class="fas fa-heartbeat"></i></div>
                <div>
                    <label>Clinical Status</label>
                    <span class="status-text">{{ ucfirst($milk->milk_Status) }}</span>
                </div>
            </div>
            <div class="overview-item">
                <div class="icon-box green"><i class="fas fa-shield-halved"></i></div>
                <div>
                    <label>Shariah Status</label>
                    <span class="status-text">
                        {{ is_null($milk->milk_shariahApproval) ? 'Pending Review' : 'Decided' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- TIMELINE SUMMARY --}}
        <div class="audit-timeline">
            
            {{-- STAGE 1 --}}
            <div class="timeline-item completed">
                <div class="timeline-marker"><i class="fas fa-vial"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 1: Pre-Pasteurization & Screening</h3>
                        <span class="date">{{ \Carbon\Carbon::parse($milk->stage1_end)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="timeline-body">
                        {{-- UPDATED: Just simple text --}}
                        <p class="text-success" style="font-weight: 600;">
                            <i class="fas fa-check-circle"></i> Labelling Complete
                        </p>
                    </div>
                </div>
            </div>

            {{-- STAGE 2 --}}
            <div class="timeline-item completed">
                <div class="timeline-marker"><i class="fas fa-snowflake"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 2: Thawing</h3>
                        <span class="date">{{ \Carbon\Carbon::parse($milk->stage2_end)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="timeline-body">
                        <p>Milk thawed under controlled temperature (4°C).</p>
                        <span class="badge-pass">Thawing Complete</span>
                    </div>
                </div>
            </div>

            {{-- STAGE 3 --}}
            <div class="timeline-item completed">
                <div class="timeline-marker"><i class="fas fa-fire-burner"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 3: Pasteurization</h3>
                        <span class="date">{{ \Carbon\Carbon::parse($milk->stage3_end)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="timeline-body">
                        <p><strong>Expiry Date Generated:</strong> <span style="color:#dc2626; font-weight:bold;">{{ \Carbon\Carbon::parse($milk->expiry_date)->format('d M Y') }}</span></p>
                        <p>Re-bottled into 30ml standard batches.</p>
                    </div>
                </div>
            </div>

            {{-- STAGE 4 --}}
            <div class="timeline-item completed">
                <div class="timeline-marker"><i class="fas fa-microscope"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 4: Microbiology Test</h3>
                        <span class="date">{{ \Carbon\Carbon::parse($milk->stage4_end)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="timeline-body">
                        
                        {{-- UPDATED: Detailed Table & Volume Summary --}}
                        <table class="table-micro">
                            <thead>
                                <tr>
                                    <th>Bottle ID</th>
                                    <th>Total Viable</th>
                                    <th>Entero.</th>
                                    <th>Staph.</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>M26-005-P1</td>
                                    <td>5,000</td>
                                    <td>100</td>
                                    <td>50</td>
                                    <td><span class="badge-pass">Pass</span></td>
                                </tr>
                                <tr>
                                    <td>M26-005-P2</td>
                                    <td>8,500</td>
                                    <td>200</td>
                                    <td>80</td>
                                    <td><span class="badge-pass">Pass</span></td>
                                </tr>
                                 <tr>
                                    <td>M26-005-P3</td>
                                    <td>120,000</td> {{-- Fail Example --}}
                                    <td>500</td>
                                    <td>100</td>
                                    <td><span class="badge-fail" style="background:#fee2e2; color:#b91c1c; padding:2px 6px; border-radius:4px; font-weight:bold; font-size:11px;">Contaminated</span></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="micro-summary" style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                            <span class="badge-pass" style="font-size: 13px;">
                                <i class="fas fa-check-double"></i> 180 mL Safe / Not Contaminated
                            </span>
                            <small style="display:block; margin-top:4px; color:#64748b;">(20 mL discarded due to contamination)</small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- STAGE 5 --}}
            <div class="timeline-item {{ $milk->stage5_end ? 'completed' : '' }}">
                <div class="timeline-marker"><i class="fas fa-box-archive"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 5: Final Storage</h3>
                        <span class="date">{{ \Carbon\Carbon::parse($milk->stage5_end)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="timeline-body">
                        <p>Stored in <strong>Freezer 2 - Drawer A01</strong>. Ready for distribution.</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- DECISION SECTION --}}
        <div class="decision-card {{ $isReadyForDecision ? 'active-decision' : 'locked-decision' }}">
            <div class="decision-header">
                <h2><i class="fas fa-scale-balanced"></i> Shariah Compliance Decision</h2>
            </div>

            @if($isReadyForDecision)
                <div class="decision-body">
                    <p class="decision-instruction">
                        Please review the timeline above. Ensure all stages (Screening to Storage) meet compliance standards before approving.
                    </p>
                    
                    <form id="shariahForm" onsubmit="event.preventDefault();">
                        <label for="shariah-remarks">Remarks (Optional)</label>
                        <textarea id="shariah-remarks" rows="3" placeholder="Enter any notes regarding the compliance...">{{ $milk->milk_shariahRemarks }}</textarea>
                    </form>

                    <div class="decision-actions">
                        <button type="button" class="btn-decision decline" onclick="submitShariahDecision(0)">
                            <i class="fas fa-ban"></i> Non-Compliant (Decline)
                        </button>
                        <button type="button" class="btn-decision approve" onclick="submitShariahDecision(1)">
                            <i class="fas fa-check-circle"></i> Compliant (Approve)
                        </button>
                    </div>
                </div>
            @else
                <div class="decision-body locked-body">
                    <i class="fas fa-lock fa-3x"></i>
                    <h3>Approval Locked</h3>
                    <p>This milk batch is currently in the <strong>{{ ucfirst($milk->milk_Status) }}</strong> stage.</p>
                    <p>Shariah approval is only available once the milk reaches <strong>Post-Pasteurization</strong> status.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    /* Mini Table for Microbiology */
    .table-micro {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 10px;
    }
    .table-micro th {
        text-align: left;
        color: #64748b;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
        padding: 6px;
    }
    .table-micro td {
        padding: 6px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-micro tr:last-child td {
        border-bottom: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitShariahDecision(isApproved) {
        const action = isApproved ? 'Approve' : 'Decline';
        const color = isApproved ? '#16a34a' : '#dc2626';

        Swal.fire({
            title: `Confirm ${action}?`,
            text: `You are about to mark this milk as ${isApproved ? 'Shariah Compliant' : 'Non-Compliant'}.`,
            icon: isApproved ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: color,
            confirmButtonText: `Yes, ${action}`
        }).then((result) => {
            if (result.isConfirmed) {
                // SIMULATE SUCCESS FOR FRONTEND
                Swal.fire('Success', `Milk record ${action.toLowerCase()}d successfully.`, 'success')
                .then(() => {
                    window.location.href = "{{ route('shariah.shariah_manage-milk-records') }}";
                });
            }
        });
    }
</script>
@endsection