@extends('layouts.nurse')

@section('title', 'Milk Request Records')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/nurse_milk-request.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container">
        <div class="main-content">
            <div class="page-header">
                <h1>Milk Request Records</h1>
                <p>Manage and track all milk processing requests</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="header-left">
                        <h3>Milk Processing and Records</h3>
                    </div>

                    <div class="header-right">
                        <button class="btn-add">
                            <i class="fas fa-plus"></i> Add Request
                        </button>
                        <input type="text" class="search-input" placeholder="🔍 Search records...">
                    </div>
                </div>

                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>NICU</th>
                            <th>Date Requested</th>
                            <th>Date Time to Give</th>
                            <th>Request Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>P001</strong><br>Sarah Ahmad Binti Fauzi</td>
                            <td>A101</td>
                            <td>Jan 12, 2024</td>
                            <td>Jan 14, 2024 • 08:30 AM</td>
                            <td><span class="status approved">Approved</span></td>
                            <td class="actions">
                                <button class="btn-view" title="View"
                                    onclick="openRequestModal('P001', 'Sarah Ahmad Binti Fauzi', 'A101', 'Jan 12, 2024', 'Jan 14, 2024 • 08:30 AM', 'Approved')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>P002</strong><br>Ahmad Jebon Bin Arif</td>
                            <td>A102</td>
                            <td>Jan 12, 2024</td>
                            <td>Jan 15, 2024 • 09:45 AM</td>
                            <td><span class="status waiting">Waiting</span></td>
                            <td class="actions">
                                <button class="btn-view" title="View"
                                    onclick="openRequestModal('P002', 'Ahmad Jebon Bin Arif', 'A102', 'Jan 12, 2024', 'Jan 15, 2024 • 09:45 AM', 'Waiting')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>P003</strong><br>Sarah Aiman Bin Yusof</td>
                            <td>A103</td>
                            <td>Jan 12, 2024</td>
                            <td>Jan 16, 2024 • 02:00 PM</td>
                            <td><span class="status rejected">Rejected</span></td>
                            <td class="actions">
                                <button class="btn-view" title="View"
                                    onclick="openRequestModal('P003', 'Sarah Aiman Bin Yusof', 'A103', 'Jan 12, 2024', 'Jan 16, 2024 • 02:00 PM', 'Rejected')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>P004</strong><br>Nurul Aisyah Binti Hassan</td>
                            <td>A104</td>
                            <td>Jan 12, 2024</td>
                            <td>Jan 16, 2024 • 03:30 PM</td>
                            <td><span class="status allocated">Allocated</span></td>
                            <td class="actions">
                                <button class="btn-view" title="View"
                                    onclick="openRequestModal('P004', 'Nurul Aisyah Binti Hassan', 'A104', 'Jan 12, 2024', 'Jan 16, 2024 • 03:30 PM', 'Allocated')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="requestModal" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Request Details</h2>
            <button class="modal-close-btn" onclick="closeRequestModal()">Close</button>
        </div>

            <div class="modal-body">
                <p><strong>Patient ID:</strong> <span id="modal-patient-id"></span></p>
                <p><strong>Patient Name:</strong> <span id="modal-patient-name"></span></p>
                <p><strong>NICU Room:</strong> <span id="modal-nicu"></span></p>
                <hr>
                <p><strong>Date Requested:</strong> <span id="modal-date-req"></span></p>
                <p><strong>Time to Give:</strong> <span id="modal-date-give"></span></p>
                <p><strong>Current Status:</strong> <span id="modal-status"></span></p>
            </div>
        </div>
    </div>

    <script>
        function openRequestModal(id, name, nicu, dateReq, dateGive, status) {
            // Populate Data
            document.getElementById("modal-patient-id").innerText = id;
            document.getElementById("modal-patient-name").innerText = name;
            document.getElementById("modal-nicu").innerText = nicu;
            document.getElementById("modal-date-req").innerText = dateReq;
            document.getElementById("modal-date-give").innerText = dateGive;
            document.getElementById("modal-status").innerText = status;

            // Show Modal
            document.getElementById("requestModal").style.display = "flex";
        }

        function closeRequestModal() {
            document.getElementById("requestModal").style.display = "none";
        }

        // Close when clicking outside modal
        window.addEventListener("click", function(e) {
            const modal = document.getElementById("requestModal");
            if (e.target === modal) modal.style.display = "none";
        });
    </script>
@endsection