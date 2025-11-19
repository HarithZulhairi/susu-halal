@extends('layouts.parent')

@section("title", "My Infant's Milk Requests")

@section('content')
    <link rel="stylesheet" href="{{ asset('css/parent_my-infant-request.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container">
        <div class="main-content">
            
            <div class="page-header">
                <h1>My Infant's Milk Requests</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Recent Donations</h2>
                    <div class="actions">
                        <button class="btn">
                            <i class="fa-solid fa-magnifying-glass"></i> Search
                        </button>
                        <button class="btn">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <button class="btn btn-more">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                    </div>
                </div>

                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Donor ID</th>
                            <th>Volume</th>
                            <th>Date Requested</th>
                            <th>Allocation Date Time</th>
                            <th>Status</th>
                            <th>Milk Kinship</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['request_id' => 'REQ-001', 'donor_id' => 'D-001', 'volume' => '250ml', 'date_requested' => 'May 15, 2024', 'date_allocate' => 'May 15, 2024 • 09:45 AM', 'status' => 'waiting', 'milk_kinship' => 'yes'],
                            ['request_id' => 'REQ-002', 'donor_id' => 'D-002', 'volume' => '250ml', 'date_requested' => 'May 15, 2024', 'date_allocate' => 'May 15, 2024 • 09:45 AM', 'status' => 'approved', 'milk_kinship' => 'no'],
                            ['request_id' => 'REQ-003', 'donor_id' => 'D-003', 'volume' => '250ml', 'date_requested' => 'May 15, 2024', 'date_allocate' => 'May 15, 2024 • 09:45 AM', 'status' => 'allocated', 'milk_kinship' => 'no'],
                            ['request_id' => 'REQ-004', 'donor_id' => 'D-004', 'volume' => '250ml', 'date_requested' => 'May 15, 2024', 'date_allocate' => 'May 15, 2024 • 09:45 AM', 'status' => 'canceled', 'milk_kinship' => 'yes']
                        ] as $request)
                            <tr>
                                <td data-label="Request ID">{{ $request['request_id'] }}</td>
                                <td data-label="Donor ID">{{ $request['donor_id'] }}</td>
                                <td data-label="Volume">{{ $request['volume'] }}</td>
                                <td data-label="Date Requested">{{ $request['date_requested'] }}</td>
                                <td data-label="Date Time to Allocate">{{ $request['date_allocate'] }}</td>
                                <td data-label="Status">
                                    <span class="status-badge {{ $request['status'] }}">
                                        {{ ucfirst($request['status']) }}
                                    </span>
                                </td>
                                <td data-label="Milk Kinship">
                                    <span class="kinship-badge {{ $request['milk_kinship'] }}">
                                        {{ ucfirst($request['milk_kinship']) }}
                                    </span>
                                </td>
                                <td class="actions" data-label="Actions">
                                    <button class="btn-view" title="View"
                                        onclick="openRequestModal('{{ $request['request_id'] }}', '{{ $request['donor_id'] }}', '{{ $request['volume'] }}', '{{ $request['date_requested'] }}', '{{ $request['date_allocate'] }}', '{{ $request['status'] }}', '{{ $request['milk_kinship'] }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($request['status'] === 'waiting' || $request['status'] === 'approved')
                                        <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    @endif
                                    <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="requestModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Request Details</h2>
                <button class="modal-close-btn" onclick="closeRequestModal()">Close</button>
            </div>
            
            <div class="modal-body">
                <p><strong>Request ID:</strong> <span id="modal-req-id"></span></p>
                <p><strong>Donor ID:</strong> <span id="modal-donor-id"></span></p>
                <p><strong>Volume Requested:</strong> <span id="modal-volume"></span></p>
                <hr>
                <p><strong>Date Requested:</strong> <span id="modal-date-req"></span></p>
                <p><strong>Allocation Date:</strong> <span id="modal-date-alloc"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
                <p><strong>Milk Kinship:</strong> <span id="modal-kinship"></span></p>
            </div>
        </div>
    </div>

    <script>
        function openRequestModal(reqId, donorId, volume, dateReq, dateAlloc, status, kinship) {
            // 1. Populate Data
            document.getElementById("modal-req-id").innerText = reqId;
            document.getElementById("modal-donor-id").innerText = donorId;
            document.getElementById("modal-volume").innerText = volume;
            document.getElementById("modal-date-req").innerText = dateReq;
            document.getElementById("modal-date-alloc").innerText = dateAlloc;
            
            // Capitalize first letter safely
            document.getElementById("modal-status").innerText = status ? status.charAt(0).toUpperCase() + status.slice(1) : '';
            document.getElementById("modal-kinship").innerText = kinship ? kinship.charAt(0).toUpperCase() + kinship.slice(1) : '';

            // 2. Show Modal
            document.getElementById("requestModal").style.display = "flex";
        }

        function closeRequestModal() {
            document.getElementById("requestModal").style.display = "none";
        }

        // 3. Close when clicking outside modal
        window.addEventListener("click", function(e) {
            const modal = document.getElementById("requestModal");
            if (e.target === modal) {
                closeRequestModal();
            }
        });
    </script>
@endsection