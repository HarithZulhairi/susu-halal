@extends('layouts.hmmc')

@section('title', 'Manage Milk Records')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/hmmc_manage-milk-records.css') }}">
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="main-content">
        <div class="page-header">
            <h1>Milk Records Management</h1>
            <p>Milk Processing and Records</p>
        </div>

        <div class="table-controls">
            <div class="search-filter">
                <button class="btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <button class="btn-more-options">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <div class="table-wrapper">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>MILKS DONOR</th>
                        <th>CLINICAL STATUS</th>
                        <th>SHARIAH APPROVAL</th>
                        <th>SHARIAH ID</th>
                        <th>EXPIRATION DATE</th>
                        <th>ELIGIBILITY</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="donor-info">
                                <div class="donor-avatar">1</div>
                                <div>
                                    <div class="donor-name">Milk ID #1</div>
                                    <div class="donor-details">Sarah Ahmad Binti Fauzi</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge status-labelling">Labelling</span></td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                        <td>SH-2024-001</td>
                        <td>May 15, 2024</td>
                        <td>Passed</td>
                        <td class="actions">
                            <button class="btn-view" title="View"
                                onclick="openMilkDetailsModal({
                                    milkId: 'Milk ID #1',
                                    donorName: 'Sarah Ahmad Binti Fauzi',
                                    clinicalStatus: 'Labelling',
                                    shariahStatus: 'Approved',
                                    shariahId: 'SH-2024-001',
                                    expiry: 'May 15, 2024',
                                    eligibility: 'Passed',
                                    volume: '250ml',
                                    collectionDate: 'May 10, 2024',
                                    storageLocation: 'Freezer A-12'
                                })">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="donor-info">
                                <div class="donor-avatar">2</div>
                                <div>
                                    <div class="donor-name">Milk ID #2</div>
                                    <div class="donor-details">Maryam Binti Othman</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge status-storaging">Storaging</span></td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                        <td>SH-2024-002</td>
                        <td>May 14, 2024</td>
                        <td>Passed</td>
                        <td class="actions">
                            <button class="btn-view" title="View"
                                onclick="openMilkDetailsModal({
                                    milkId: 'Milk ID #2',
                                    donorName: 'Maryam Binti Othman',
                                    clinicalStatus: 'Storaging',
                                    shariahStatus: 'Approved',
                                    shariahId: 'SH-2024-002',
                                    expiry: 'May 14, 2024',
                                    eligibility: 'Passed',
                                    volume: '300ml',
                                    collectionDate: 'May 09, 2024',
                                    storageLocation: 'Freezer B-05'
                                })">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="donor-info">
                                <div class="donor-avatar">3</div>
                                <div>
                                    <div class="donor-name">Milk ID #3</div>
                                    <div class="donor-details">Fatimah Az-zahra Binti Mohd Nor</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge status-screening">Screening</span></td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>SH-2024-003</td>
                        <td>Jun 23, 2024</td>
                        <td>Passed</td>
                        <td class="actions">
                            <button class="btn-view" title="View"
                                onclick="openMilkDetailsModal({
                                    milkId: 'Milk ID #3',
                                    donorName: 'Fatimah Az-zahra Binti Mohd Nor',
                                    clinicalStatus: 'Screening',
                                    shariahStatus: 'Pending',
                                    shariahId: 'SH-2024-003',
                                    expiry: 'Jun 23, 2024',
                                    eligibility: 'Passed',
                                    volume: '200ml',
                                    collectionDate: 'Jun 18, 2024',
                                    storageLocation: 'Freezer C-08'
                                })">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="donor-info">
                                <div class="donor-avatar">4</div>
                                <div>
                                    <div class="donor-name">Milk ID #4</div>
                                    <div class="donor-details">Aishah Fathihah Binti Izzuddin</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge status-labelling">Labelling</span></td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                        <td>SH-2024-004</td>
                        <td>May 15, 2024</td>
                        <td>Passed</td>
                        <td class="actions">
                            <button class="btn-view" title="View"
                                onclick="openMilkDetailsModal({
                                    milkId: 'Milk ID #4',
                                    donorName: 'Aishah Fathihah Binti Izzuddin',
                                    clinicalStatus: 'Labelling',
                                    shariahStatus: 'Approved',
                                    shariahId: 'SH-2024-004',
                                    expiry: 'May 15, 2024',
                                    eligibility: 'Passed',
                                    volume: '280ml',
                                    collectionDate: 'May 10, 2024',
                                    storageLocation: 'Freezer A-15'
                                })">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="donor-info">
                                <div class="donor-avatar">5</div>
                                <div>
                                    <div class="donor-name">Milk ID #5</div>
                                    <div class="donor-details">Nor Afiqah Humaira Binti Ishak</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge status-dispatching">Dispatching</span></td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                        <td>SH-2024-005</td>
                        <td>Apr 28, 2024</td>
                        <td>Failed due to smoker</td>
                        <td class="actions">
                            <button class="btn-view" title="View"
                                onclick="openMilkDetailsModal({
                                    milkId: 'Milk ID #5',
                                    donorName: 'Nor Afiqah Humaira Binti Ishak',
                                    clinicalStatus: 'Dispatching',
                                    shariahStatus: 'Rejected',
                                    shariahId: 'SH-2024-005',
                                    expiry: 'Apr 28, 2024',
                                    eligibility: 'Failed due to smoker',
                                    volume: '150ml',
                                    collectionDate: 'Apr 23, 2024',
                                    storageLocation: 'Freezer D-01'
                                })">
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

    <!-- VIEW DETAILS MODAL - USING NEW REUSABLE STRUCTURE -->
    <div id="milkDetailsModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
            <h2>Milk Record Details</h2>
            <button class="modal-close-btn" onclick="closeMilkDetailsModal()">Close</button>
        </div>

            <div class="modal-body">
                <p><strong>Milk ID:</strong> <span id="modalMilkId"></span></p>
                <p><strong>Donor Name:</strong> <span id="modalDonorName"></span></p>
                <p><strong>Volume:</strong> <span id="modalVolume"></span></p>
                <p><strong>Collection Date:</strong> <span id="modalCollectionDate"></span></p>
                
                <hr>
                
                <h3>Clinical Information</h3>
                <p><strong>Clinical Status:</strong> <span id="modalClinicalStatus"></span></p>
                <p><strong>Eligibility:</strong> <span id="modalEligibility"></span></p>
                
                <hr>
                
                <h3>Shariah Compliance</h3>
                <p><strong>Shariah Approval:</strong> <span id="modalShariah"></span></p>
                <p><strong>Shariah ID:</strong> <span id="modalShariahId"></span></p>
                
                <hr>
                
                <h3>Storage Details</h3>
                <p><strong>Storage Location:</strong> <span id="modalStorageLocation"></span></p>
                <p><strong>Expiration Date:</strong> <span id="modalExpiry"></span></p>
            </div>

        </div>
    </div>

    <script>
        function openMilkDetailsModal(record) {
            document.getElementById("modalMilkId").textContent = record.milkId;
            document.getElementById("modalDonorName").textContent = record.donorName;
            document.getElementById("modalVolume").textContent = record.volume;
            document.getElementById("modalCollectionDate").textContent = record.collectionDate;
            document.getElementById("modalClinicalStatus").textContent = record.clinicalStatus;
            document.getElementById("modalShariah").textContent = record.shariahStatus;
            document.getElementById("modalShariahId").textContent = record.shariahId;
            document.getElementById("modalExpiry").textContent = record.expiry;
            document.getElementById("modalEligibility").textContent = record.eligibility;
            document.getElementById("modalStorageLocation").textContent = record.storageLocation;

            document.getElementById("milkDetailsModal").style.display = "flex";
        }

        function closeMilkDetailsModal() {
            document.getElementById("milkDetailsModal").style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(e) {
            let modal = document.getElementById("milkDetailsModal");
            if (e.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>

@endsection