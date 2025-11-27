@extends('layouts.shariah')

@section('title', 'View Milk Processing Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_view-milk-processing.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">
        <div class="page-content-wrapper"> 
            
            <div class="page-header">
                <div class="header-left">
                    <h1>Milk ID #{{ $milk->milk_ID }} - {{ $milk->donor->dn_FullName ?? 'Unknown Donor' }}</h1>
                </div>
                <button class="btn-back" onclick="window.history.back()"><i class="fas fa-arrow-left"></i> Back</button>
            </div>

            <div class="stage-card">
                <div class="stage-title">
                    <h2>Screening Stage</h2>
                    <p class="stage-subtitle">RESULTS</p>
                </div>

                <div class="stage-content-wrapper">
                    <div class="stage-image-section">
                        <img src="{{ asset('images/lab_screening.jpg') }}" alt="Screening Process">
                    </div>

                    <div class="stage-details">
                        <div class="details-grid">
                            <div class="detail-item">
                                <label>Start Date:</label>
                                <p>{{ $milk->milk_stage1StartDate ? \Carbon\Carbon::parse($milk->milk_stage1StartDate)->format('d F Y') : '-' }}</p>
                            </div>
                            <div class="detail-item">
                                <label>End Date:</label>
                                <p>{{ $milk->milk_stage1EndDate ? \Carbon\Carbon::parse($milk->milk_stage1EndDate)->format('d F Y') : '-' }}</p>
                            </div>
                            <div class="detail-item">
                                <label>Start Time:</label>
                                <p>{{ $milk->milk_stage1StartTime ? \Carbon\Carbon::parse($milk->milk_stage1StartTime)->format('H:i') : '-' }}</p>
                            </div>
                            <div class="detail-item">
                                <label>End Time:</label>
                                <p>{{ $milk->milk_stage1EndTime ? \Carbon\Carbon::parse($milk->milk_stage1EndTime)->format('H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="time-status-section">
                    <p class="time-label">Status: 
                        @if($milk->milk_stage1EndDate && \Carbon\Carbon::parse($milk->milk_stage1EndDate)->isPast())
                            <span class="status-badge completed">COMPLETED</span>
                        @elseif($milk->milk_stage1StartDate)
                            <span class="status-badge active">IN PROGRESS</span>
                        @else
                            <span class="status-badge pending">NOT STARTED</span>
                        @endif
                    </p>
                </div>

                <div class="results-section">
                    <h3>Microbiological Results:</h3>
                    
                    <div class="results-table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Test Parameter</th>  
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($screeningResults as $index => $result)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        
                                        <td>
                                            {{ $result['contents'] ?? $result['test'] ?? $result['name'] ?? 'N/A' }}
                                        </td>

                                        <td style="text-align: center;">
                                            @php
                                                $status = $result['tolerance'] ?? $result['status'] ?? 'Unknown';
                                                $class = strtolower($status) === 'passed' ? 'passed' : (strtolower($status) === 'failed' ? 'failed' : 'pending');
                                            @endphp
                                            <span class="status {{ $class }}">{{ ucfirst($status) }}</span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center; color:#666; padding: 20px;">
                                            No screening results recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="shariah-decision-section">
                    <form id="shariahForm" class="remarks-section">
                        @csrf
                        <input type="hidden" id="milkId" value="{{ $milk->milk_ID }}">
                        
                        <label for="shariah-remarks">
                            <i class="fas fa-pen-to-square" style="color: #64748b;"></i> 
                            Shariah Remarks
                        </label>
                        <textarea id="shariah-remarks" name="shariah_remarks" rows="4" placeholder="Enter detailed remarks regarding the compliance status...">{{ $milk->milk_shariahRemarks }}</textarea>
                    </form>
                </div>

                <div class="shariah-actions">
                    <button type="button" class="btn-decline" onclick="submitShariahDecision(0)">
                        <i class="fas fa-times-circle"></i> Decline Milk
                    </button>
                    <button type="button" class="btn-approve" onclick="submitShariahDecision(1)">
                        <i class="fas fa-check-circle"></i> Approve Milk
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitShariahDecision(isApproved) {
        const milkId = document.getElementById('milkId').value;
        const remarks = document.getElementById('shariah-remarks').value;
        const action = isApproved ? 'Approve' : 'Decline';
        const confirmColor = isApproved ? '#16a34a' : '#dc2626';

        Swal.fire({
            title: `Confirm ${action}?`,
            text: `Are you sure you want to ${action.toLowerCase()} this milk record?`,
            icon: isApproved ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            confirmButtonText: `Yes, ${action} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX Request
                fetch("{{ route('shariah.update-decision') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        milk_id: milkId,
                        approval: isApproved,
                        remarks: remarks
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Success!', `Milk record has been ${action.toLowerCase()}d.`, 'success')
                        .then(() => {
                            // Redirect back to list or reload
                            window.location.href = "{{ route('shariah.shariah_manage-milk-records') }}";
                        });
                    } else {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Server error.', 'error');
                });
            }
        });
    }
</script>

@endsection