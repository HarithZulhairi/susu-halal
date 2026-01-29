@extends('layouts.shariah')

@section('title', 'Milk Compliance Audit')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_view-milk-processing.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@php
    // Determine if ready (Storage Completed)
    $isReadyForDecision = ($milk->milk_Status === 'Storage Completed');
    
    // Helper function for dates
    function fmtDate($date) {
        return $date ? \Carbon\Carbon::parse($date)->format('d M Y, H:i') : 'Pending';
    }
@endphp

<div class="container">
    <div class="main-content">
        
        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Compliance Audit: {{ $milk->formatted_id }}</h1>
                <p class="page-subtitle" style="font-size: 18px; color: #64748b; margin: 10px 0 0 0;">Donor: {{ $milk->donor->dn_FullName ?? 'Unknown' }}</p>
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
                    @php 
                        $totalPostVol = $milk->postBottles->sum('post_volume');
                        $displayVol = $totalPostVol > 0 ? $totalPostVol : $milk->milk_volume;
                    @endphp
                    <span>{{ $displayVol }} mL</span>
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
                        @if(is_null($milk->milk_shariahApproval))
                            Pending Review
                        @elseif($milk->milk_shariahApproval == 1)
                            <span style="color:green"><i class="fas fa-check"></i> Compliant</span>
                        @else
                            <span style="color:red"><i class="fas fa-times"></i> Rejected</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- TIMELINE SUMMARY --}}
        <div class="audit-timeline">
            
            {{-- STAGE 1 --}}
            <div class="timeline-item {{ $milk->milk_stage1EndDate ? 'completed' : '' }}">
                <div class="timeline-marker"><i class="fas fa-vial"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 1: Pre-Pasteurization & Screening</h3>
                        <span class="date">{{ fmtDate($milk->milk_stage1EndDate) }}</span>
                    </div>
                    <div class="timeline-body">
                        @if($milk->milk_stage1EndDate)
                            <p class="text-success" style="font-weight: 600; margin-bottom: 10px;">
                                <i class="fas fa-check-circle"></i> Labelling Complete
                            </p>
                            
                            {{-- DETAILED TABLE --}}
                            <table class="table-micro">
                                <thead>
                                    <tr>
                                        <th>Raw Bottle ID</th>
                                        <th>Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($milk->preBottles as $pb)
                                    <tr>
                                        <td>{{ $pb->pre_bottle_code }}</td>
                                        <td>{{ $pb->pre_volume }} mL</td>
                                    </tr>
                                    @endforeach
                                    <tr style="background-color: #f8fafc; font-weight: bold;">
                                        <td style="text-align: right;">Total:</td>
                                        <td>{{ $milk->preBottles->sum('pre_volume') }} mL</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Process not started or in progress.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STAGE 2 --}}
            <div class="timeline-item {{ $milk->milk_stage2StartDate ? 'completed' : '' }}">
                <div class="timeline-marker"><i class="fas fa-snowflake"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 2: Thawing</h3>
                        <span class="date">{{ fmtDate($milk->milk_stage2StartDate) }}</span>
                    </div>
                    <div class="timeline-body">
                        @if($milk->milk_stage2StartDate)
                            <p style="margin-bottom: 10px;">Milk thawed under controlled temperature.</p>
                            
                            {{-- DETAILED TABLE --}}
                            <table class="table-micro">
                                <thead>
                                    <tr>
                                        <th>Bottle ID</th>
                                        <th>Thawing Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($milk->preBottles as $pb)
                                    <tr>
                                        <td>{{ $pb->pre_bottle_code }}</td>
                                        <td>
                                            @if($pb->pre_is_thawed)
                                                <span class="badge-pass"><i class="fas fa-check"></i> Thawed</span>
                                            @else
                                                <span class="badge-fail">Not Thawed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Pending.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STAGE 3 --}}
            <div class="timeline-item {{ $milk->milk_stage3StartDate ? 'completed' : '' }}">
                <div class="timeline-marker"><i class="fas fa-fire-burner"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 3: Pasteurization</h3>
                        <span class="date">{{ fmtDate($milk->milk_stage3StartDate) }}</span>
                    </div>
                    <div class="timeline-body">
                        @if($milk->milk_stage3StartDate)
                            <p style="margin-bottom: 10px;">Re-bottled into standard batches.</p>
                            
                            {{-- DETAILED TABLE --}}
                            <table class="table-micro">
                                <thead>
                                    <tr>
                                        <th>Post Bottle ID</th>
                                        <th>Volume</th>
                                        <th>Pasteurized Date</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($milk->postBottles as $pb)
                                    <tr>
                                        <td>{{ $pb->post_bottle_code }}</td>
                                        <td>{{ $pb->post_volume }} mL</td>
                                        <td>{{ \Carbon\Carbon::parse($pb->post_pasteurization_date)->format('d M Y') }}</td>
                                        <td style="color:#dc2626; font-weight:bold;">{{ \Carbon\Carbon::parse($pb->post_expiry_date)->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Pending.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STAGE 4 --}}
            <div class="timeline-item {{ $milk->milk_stage4StartDate ? 'completed' : '' }}">
                <div class="timeline-marker"><i class="fas fa-microscope"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 4: Microbiology Test</h3>
                        <span class="date">{{ fmtDate($milk->milk_stage4StartDate) }}</span>
                    </div>
                    <div class="timeline-body">
                        @if($milk->milk_stage4StartDate)
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
                                    @foreach($milk->postBottles as $pb)
                                    <tr>
                                        <td>{{ $pb->post_bottle_code }}</td>
                                        <td>{{ $pb->post_micro_total_viable ?? '-' }}</td>
                                        <td>{{ $pb->post_micro_entero ?? '-' }}</td>
                                        <td>{{ $pb->post_micro_staph ?? '-' }}</td>
                                        <td>
                                            @if(str_contains($pb->post_micro_status, 'Contaminated') || str_contains($pb->post_micro_status, 'Fail'))
                                                <span class="badge-fail" style="background:#fee2e2; color:#b91c1c; padding:2px 6px; border-radius:4px; font-weight:bold; font-size:11px;">Contaminated</span>
                                            @else
                                                <span class="badge-pass">Pass</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Pending.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STAGE 5 --}}
            <div class="timeline-item {{ $milk->milk_stage5StartDate ? 'completed' : '' }}">
                <div class="timeline-marker"><i class="fas fa-box-archive"></i></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h3>Stage 5: Final Storage</h3>
                        <span class="date">{{ fmtDate($milk->milk_stage5StartDate) }}</span>
                    </div>
                    <div class="timeline-body">
                        @if($milk->milk_stage5StartDate)
                            <p style="margin-bottom: 10px;">Only passed bottles are stored.</p>
                            
                             {{-- DETAILED TABLE --}}
                             <table class="table-micro">
                                <thead>
                                    <tr>
                                        <th>Bottle ID</th>
                                        <th>Storage Location</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($milk->postBottles as $pb)
                                    {{-- Only show bottles that have a storage location (meaning they passed) --}}
                                    @if($pb->post_storage_location)
                                        <tr>
                                            <td>{{ $pb->post_bottle_code }}</td>
                                            <td style="font-weight: bold; color: #1A5F7A;">{{ $pb->post_storage_location }}</td>
                                            <td><span class="badge-pass">Stored</span></td>
                                        </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Pending.</p>
                        @endif
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
                    
                    <form id="shariahForm">
                        <label for="shariah-remarks">Remarks (Optional)</label>
                        <textarea id="shariah-remarks" rows="3" class="form-control" placeholder="Enter any notes regarding the compliance...">{{ $milk->milk_shariahRemarks }}</textarea>
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
                    <div style="text-align: center; color: #94a3b8; padding: 20px;">
                        <i class="fas fa-lock fa-3x" style="margin-bottom: 15px;"></i>
                        <h3>Approval Locked</h3>
                        <p>This milk batch is currently in the <strong>{{ ucfirst($milk->milk_Status) }}</strong> stage.</p>
                        <p>Shariah approval is only available once the milk reaches <strong>Storage Completed</strong> status.</p>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitShariahDecision(isApproved) {
        const action = isApproved ? 'Approve' : 'Decline';
        const color = isApproved ? '#16a34a' : '#dc2626';
        const remarks = document.getElementById('shariah-remarks').value;

        Swal.fire({
            title: `Confirm ${action}?`,
            text: `You are about to mark this milk as ${isApproved ? 'Shariah Compliant' : 'Non-Compliant'}.`,
            icon: isApproved ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: color,
            confirmButtonText: `Yes, ${action}`
        }).then((result) => {
            if (result.isConfirmed) {
                // IMPORTANT: Manually fetch the ID from the Blade object
                const milkId = "{{ $milk->milk_ID }}";
                const url = "{{ route('shariah.update-decision', ':id') }}".replace(':id', milkId);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        approval: isApproved ? 1 : 0,
                        remarks: remarks
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Success', data.message, 'success')
                        .then(() => {
                            window.location.href = "{{ route('shariah.shariah_manage-milk-records') }}";
                        });
                    } else {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Network request failed', 'error');
                });
            }
        });
    }
</script>
@endsection