@extends('layouts.hmmc')

@section('title', 'List of Infants')

@section('content')
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/hmmc_list-of-infants.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container">
        <div class="main-content">
            
            <div class="page-header">
                <h1>List of All Infants</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Recently Registered</h2>
                    <div class="actions">
                        <button class="btn"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                        <button class="btn"><i class="fa-solid fa-filter"></i> Filter</button>
                        <button class="btn btn-more"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                </div>

                <table class="infants-table records-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Donor ID</th>
                            <th>Patient ID</th>
                            <th>Volume</th>
                            <th>Date Requested</th>
                            <th>Date Time to Allocate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $requestDetailData = [
                            'doctor_id' => 'DR-401',
                            'doctor_name' => 'Dr. Sarah Mahmud',
                            'recommended' => '180 ml (based on weight-based formula)',
                            'milk_list' => [
                                ['volume' => '90ml Bottle A', 'expiry' => 'Expires: May 18, 2024'],
                                ['volume' => '90ml Bottle B', 'expiry' => 'Expires: May 19, 2024'],
                            ],
                            'feeding_history' => [
                                [ 'datetime' => 'May 15, 2024 • 08:00 AM', 'nurse_id' => 'NRS-1001'],
                                [ 'datetime' => 'May 15, 2024 • 12:00 PM', 'nurse_id' => 'NRS-1003'],
                            ]
                        ];
                        @endphp

                        @foreach ([
                            ['request_id'=>'REQ-001','donor_id'=>'D-001','patient_id'=>'P-101','volume'=>'250ml','date_requested'=>'May 15, 2024','date_allocate'=>'May 15, 2024 • 03:30 PM','status'=>'waiting'],
                            ['request_id'=>'REQ-002','donor_id'=>'D-002','patient_id'=>'P-102','volume'=>'250ml','date_requested'=>'May 15, 2024','date_allocate'=>'May 15, 2024 • 03:30 PM','status'=>'approved'],
                            ['request_id'=>'REQ-003','donor_id'=>'D-003','patient_id'=>'P-103','volume'=>'250ml','date_requested'=>'May 15, 2024','date_allocate'=>'May 15, 2024 • 03:30 PM','status'=>'allocated'],
                            ['request_id'=>'REQ-004','donor_id'=>'D-004','patient_id'=>'P-104','volume'=>'250ml','date_requested'=>'May 15, 2024','date_allocate'=>'May 15, 2024 • 03:30 PM','status'=>'canceled']
                        ] as $request)
                            <tr>
                                <td data-label="Request ID">{{ $request['request_id'] }}</td>
                                <td data-label="Donor ID">{{ $request['donor_id'] }}</td>
                                <td data-label="Patient ID">{{ $request['patient_id'] ?? 'P-001' }}</td>
                                <td data-label="Volume">{{ $request['volume'] }}</td>
                                <td data-label="Date Requested">{{ $request['date_requested'] }}</td>
                                <td data-label="Date Time to Allocate">{{ $request['date_allocate'] }}</td>
                                <td data-label="Status">
                                    <span class="status-badge {{ $request['status'] }}">{{ ucfirst($request['status']) }}</span>
                                </td>
                                <td class="actions" data-label="Actions">
                                    <button class="btn-view" title="View"
                                        onclick='openModal({!! json_encode(array_merge($request, $requestDetailData)) !!})'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($request['status'] !== 'canceled')
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
    
    <!-- ========================== VIEW MODAL ============================= -->
    <div id="viewModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
            <h2>Infant Milk Request Details</h2>
            <button class="modal-close-btn" onclick="closeModal()">Close</button>
        </div>

            <div class="modal-body">
                <p><strong>Request ID:</strong> <span id="modal-request-id"></span></p>
                <p><strong>Donor ID:</strong> <span id="modal-donor-id"></span></p>
                <p><strong>Patient ID:</strong> <span id="modal-patient-id"></span></p>

                <hr>

                <h3>Doctor Prescription</h3>
                <p><strong>Doctor ID:</strong> <span id="modal-doctor-id"></span></p>
                <p><strong>Doctor Name:</strong> <span id="modal-doctor-name"></span></p>
                <p><strong>Recommended Volume:</strong> <span id="modal-recommended"></span></p>

                <hr>

                <h3>Milk Allocation</h3>
                <ul id="modal-milk-list"></ul>

                <hr>

                <h3>Feeding History</h3>
                <div id="modal-fed-history"></div>
            </div>

        </div>
    </div>
    
    <script>
    function openModal(data) {
        document.getElementById("modal-request-id").textContent = data.request_id;
        document.getElementById("modal-donor-id").textContent = data.donor_id;
        document.getElementById("modal-patient-id").textContent = data.patient_id;

        document.getElementById("modal-doctor-id").textContent = data.doctor_id;
        document.getElementById("modal-doctor-name").textContent = data.doctor_name;
        document.getElementById("modal-recommended").textContent = data.recommended;

        // Display milk list
        let milkList = document.getElementById("modal-milk-list");
        milkList.innerHTML = "";
        data.milk_list.forEach(m => {
            milkList.innerHTML += `<li>${m.volume} - ${m.expiry}</li>`;
        });

        // Display feeding history
        let history = document.getElementById("modal-fed-history");
        history.innerHTML = "";
        data.feeding_history.forEach(h => {
            history.innerHTML += `
                <p><strong>${h.datetime}</strong> — Fed by Nurse ${h.nurse_id}</p>
            `;
        });

        document.getElementById("viewModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("viewModal").style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function(e) {
        let modal = document.getElementById("viewModal");
        if (e.target === modal) {
            modal.style.display = "none";
        }
    }
    </script>

@endsection