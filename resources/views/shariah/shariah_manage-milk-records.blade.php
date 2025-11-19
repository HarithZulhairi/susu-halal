@extends('layouts.shariah')

@section('title', 'Shariah Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shariah_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <div class="container">
        <div class="main-content">
            <div class="page-header">
                <h1>Milk Records Management</h1>
                <p>Milk Processing and Records</p>
            </div>

            <div class="table-controls">
                <div class="search-filter">
                    <button class="btn-search"><i class="fas fa-search"></i> Search</button>
                    <button class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
                </div>
                <button class="btn-review"><i class="fas fa-plus"></i> Review Milk</button>
            </div>

            <div class="table-wrapper">
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>MILK DONOR</th>
                            <th>CLINICAL STATUS</th>
                            <th>SHARIAH APPROVAL</th>
                            <th>SHARIAH REMARKS</th>
                            <th>SHARIAH APPROVAL DATE</th>
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
                            <td><span class="status approved">Approved</span></td>
                            <td>Passed</td>
                            <td class="date green">Jan 12, 2024</td>
                            <td class="actions">
                                <button class="btn-view" onclick="openModal(1)" title="View"><i class="fas fa-eye"></i></button>
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
                            <td><span class="status approved">Approved</span></td>
                            <td>Passed</td>
                            <td class="date green">Feb 05, 2024</td>
                            <td class="actions">
                                <button class="btn-view" onclick="openModal(2)" title="View"><i class="fas fa-eye"></i></button>
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
                            <td><span class="status pending">Pending</span></td>
                            <td>Under Review</td>
                            <td>-</td>
                            <td class="actions">
                                <button class="btn-view" onclick="openModal(3)" title="View"><i class="fas fa-eye"></i></button>
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
                                        <div class="donor-details">Aishah Radhi Binti Izzuddin</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="status-badge status-labelling">Labelling</span></td>
                            <td><span class="status approved">Approved</span></td>
                            <td>Passed</td>
                            <td class="date green">Dec 18, 2023</td>
                            <td class="actions">
                                <button class="btn-view" onclick="openModal(4)" title="View"><i class="fas fa-eye"></i></button>
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
                                        <div class="donor-details">Nor Atiqah Humaira Binti Ishak</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="status-badge status-dispatching">Dispatching</span></td>
                            <td><span class="status rejected">Rejected</span></td>
                            <td>Smoker</td>
                            <td class="date red">Mar 22, 2024</td>
                            <td class="actions">
                                <button class="btn-view" onclick="openModal(5)" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="milkModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Milk Record Details</h2>
                <button class="modal-close-btn" onclick="closeModal()">Close</button>
            </div>

            <div class="modal-body">
                <p><strong>Milk ID:</strong> <span id="modal-milk-id"></span></p>
                <p><strong>Donor Name:</strong> <span id="modal-donor-name"></span></p>
                <p><strong>Clinical Status:</strong> <span id="modal-clinical-status"></span></p>
                <p><strong>Volume:</strong> <span id="modal-volume"></span></p>
                <p><strong>Storage Location:</strong> <span id="modal-storage"></span></p>
                
                <hr>
                
                <p><strong>Collection Date:</strong> <span id="modal-col-date"></span></p>
                <p><strong>Expiry Date:</strong> <span id="modal-exp-date"></span></p>
                
                <hr>
                
                <h3><i class="fas fa-balance-scale"></i> Shariah Review</h3>
                <p><strong>Approval Status:</strong> <span id="modal-shariah-status"></span></p>
                <p><strong>Approval Date:</strong> <span id="modal-app-date"></span></p>
                <div id="modal-remarks-container" style="margin-top: 10px; padding: 12px; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #2563eb;">
                    <strong>Remarks:</strong> <br>
                    <span id="modal-remarks"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const milkData = {
            1: {
                milkId: 'Milk ID #1',
                donorName: 'Sarah Ahmad Binti Fauzi',
                clinicalStatus: 'Labelling',
                shariahApproval: 'Approved',
                shariahRemarks: 'All Shariah requirements met. Donor is eligible and follows Islamic guidelines.',
                approvalDate: 'Jan 12, 2024',
                volume: '250 ml',
                collectionDate: 'Jan 10, 2024',
                expiryDate: 'Jul 10, 2024',
                storageLocation: 'Freezer A-12'
            },
            2: {
                milkId: 'Milk ID #2',
                donorName: 'Maryam Binti Othman',
                clinicalStatus: 'Storaging',
                shariahApproval: 'Approved',
                shariahRemarks: 'Verified halal compliance. Donor maintains proper Islamic practices.',
                approvalDate: 'Feb 05, 2024',
                volume: '300 ml',
                collectionDate: 'Feb 03, 2024',
                expiryDate: 'Aug 03, 2024',
                storageLocation: 'Freezer B-05'
            },
            3: {
                milkId: 'Milk ID #3',
                donorName: 'Fatimah Az-zahra Binti Mohd Nor',
                clinicalStatus: 'Screening',
                shariahApproval: 'Pending',
                shariahRemarks: 'Under review. Awaiting final Shariah committee approval.',
                approvalDate: '-',
                volume: '200 ml',
                collectionDate: 'Mar 15, 2024',
                expiryDate: '-',
                storageLocation: 'Pending assignment'
            },
            4: {
                milkId: 'Milk ID #4',
                donorName: 'Aishah Radhi Binti Izzuddin',
                clinicalStatus: 'Labelling',
                shariahApproval: 'Approved',
                shariahRemarks: 'Excellent compliance with Shariah standards. Donor highly recommended.',
                approvalDate: 'Dec 18, 2023',
                volume: '280 ml',
                collectionDate: 'Dec 16, 2023',
                expiryDate: 'Jun 16, 2024',
                storageLocation: 'Freezer A-23'
            },
            5: {
                milkId: 'Milk ID #5',
                donorName: 'Nor Atiqah Humaira Binti Ishak',
                clinicalStatus: 'Dispatching',
                shariahApproval: 'Rejected',
                shariahRemarks: 'Donor is a smoker. Does not meet health and Shariah requirements for milk donation.',
                approvalDate: 'Mar 22, 2024',
                volume: '150 ml',
                collectionDate: 'Mar 20, 2024',
                expiryDate: '-',
                storageLocation: 'Not applicable'
            }
        };

        function openModal(id) {
            const data = milkData[id];
            
            // Populate Text Fields
            document.getElementById('modal-milk-id').innerText = data.milkId;
            document.getElementById('modal-donor-name').innerText = data.donorName;
            document.getElementById('modal-clinical-status').innerText = data.clinicalStatus;
            document.getElementById('modal-volume').innerText = data.volume;
            document.getElementById('modal-storage').innerText = data.storageLocation;
            document.getElementById('modal-col-date').innerText = data.collectionDate;
            document.getElementById('modal-exp-date').innerText = data.expiryDate;
            document.getElementById('modal-shariah-status').innerText = data.shariahApproval;
            document.getElementById('modal-app-date').innerText = data.approvalDate;
            document.getElementById('modal-remarks').innerText = data.shariahRemarks;

            // Visual updates for remarks based on status
            const remarksContainer = document.getElementById('modal-remarks-container');
            if(data.shariahApproval === 'Rejected') {
                remarksContainer.style.background = '#fee2e2';
                remarksContainer.style.borderLeftColor = '#dc2626';
            } else if (data.shariahApproval === 'Approved') {
                remarksContainer.style.background = '#f0f9ff';
                remarksContainer.style.borderLeftColor = '#2563eb';
            } else {
                remarksContainer.style.background = '#f0f9ff';
                remarksContainer.style.borderLeftColor = '#2563eb';
            }

            // Show Modal
            document.getElementById('milkModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('milkModal').style.display = 'none';
        }

        // Close when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('milkModal');
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>

@endsection