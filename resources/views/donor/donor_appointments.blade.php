@extends('layouts.donor')

@section('title', 'Donor Appointments')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_appointments.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">

    <div class="main-content">
        <div class="appointments-page">
            <div class="page-header">
                <h1>My Appointments</h1>
            </div>

            <div class="appointment-cards">
                <div class="appointment-card">
                    <h3>Milk Donation</h3>
                     <a href="{{ route('donor.appointment-form') }}" class="btn-book">Book Appointment</a>
                </div>
                <div class="appointment-card">
                    <h3>Pumping Kit</h3>
                     <a href="{{ route('donor.pumping-kit-form') }}" class="btn-book">Book Appointment</a>
                </div>
            </div>

            <div class="appointments-section">
                <div class="section-header">
                    <h2>My Appointments</h2>
                    <div class="header-actions">
                        <button class="btn-icon" title="Search"><i class="fas fa-search"></i> Search</button>
                        <button class="btn-icon" title="Filter"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active">All Appointment <span class="badge">3</span></button>
                    <button class="tab">Confirmed <span class="badge">1</span></button>
                    <button class="tab">Pending <span class="badge">1</span></button>
                    <button class="tab">Canceled <span class="badge">1</span></button>
                </div>

                <div class="table-container">
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>REFERENCE ID</th>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>TYPE</th>
                                <th>LOCATION</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="ref-id">MDN-2025-015</span></td>
                                <td>15.5.2025</td>
                                <td>1000 ml</td>
                                <td><span class="status confirmed">Confirmed</span></td>
                                <td>MILK DROP OFF</td>
                                <td>Main Foyer</td>
                                <td class="actions">
                                    <button class="btn-view" title="View" 
                                        onclick="openAppointmentModal({
                                            referenceId: 'MDN-2025-015',
                                            date: '15.5.2025',
                                            time: '10:00 AM',
                                            amount: '1000 ml',
                                            status: 'Confirmed',
                                            type: 'MILK DROP OFF',
                                            location: 'Main Foyer',
                                            contactPerson: 'Nurse Fatimah',
                                            contactPhone: '+60 12-345 6789',
                                            notes: 'Please bring your donor ID and ensure milk is properly labeled'
                                        })">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    <button class="btn-calendar" title="Calendar"><i class="fas fa-calendar-alt"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="ref-id">MDN-2025-016</span></td>
                                <td>21.5.2025</td>
                                <td>1000 ml</td>
                                <td><span class="status pending">Pending</span></td>
                                <td>MILK DROP OFF</td>
                                <td>Front Counter</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"
                                        onclick="openAppointmentModal({
                                            referenceId: 'MDN-2025-016',
                                            date: '21.5.2025',
                                            time: '02:30 PM',
                                            amount: '1000 ml',
                                            status: 'Pending',
                                            type: 'MILK DROP OFF',
                                            location: 'Front Counter',
                                            contactPerson: 'Nurse Sarah',
                                            contactPhone: '+60 12-987 6543',
                                            notes: 'Awaiting confirmation. You will receive an SMS once confirmed.'
                                        })">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                     <button class="btn-calendar" title="Calendar"><i class="fas fa-calendar-alt"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="ref-id">MDN-2025-017</span></td>
                                <td>25.5.2025</td>
                                <td>Pumping Kit</td>
                                <td><span class="status canceled">Canceled</span></td>
                                <td>PUMP KIT RETURN</td>
                                <td>Logistics Dept</td>
                                <td class="actions">
                                    <button class="btn-view" title="View"
                                        onclick="openAppointmentModal({
                                            referenceId: 'MDN-2025-017',
                                            date: '25.5.2025',
                                            time: '11:00 AM',
                                            amount: 'Pumping Kit',
                                            status: 'Canceled',
                                            type: 'PUMP KIT RETURN',
                                            location: 'Logistics Dept',
                                            contactPerson: 'Staff Ahmad',
                                            contactPhone: '+60 11-234 5678',
                                            notes: 'Appointment canceled by donor. Please rebook if you wish to return the kit.'
                                        })">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="appointments-section">
                <div class="section-header">
                    <h2>Appointment History</h2>
                    <div class="header-actions">
                        <button class="btn-icon" title="Search"><i class="fas fa-search"></i> Search</button>
                        <button class="btn-show">Show</button>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active">All Appointment <span class="badge">2</span></button>
                    <button class="tab">Completed <span class="badge">1</span></button>
                    <button class="tab">Canceled <span class="badge">1</span></button>
                </div>

                <div class="table-container">
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>REFERENCE ID</th>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>TYPE</th>
                                <th>LOCATION</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No appointment history yet
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================== APPOINTMENT DETAILS MODAL ============================= -->
<div id="appointmentModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Appointment Details</h2>
            <button class="modal-close-btn" onclick="closeAppointmentModal()">Close</button>
        </div>

        <div class="modal-body">
            <p><strong>Reference ID:</strong> <span id="modal-reference-id"></span></p>
            <p><strong>Date:</strong> <span id="modal-date"></span></p>
            <p><strong>Time:</strong> <span id="modal-time"></span></p>
            <p><strong>Status:</strong> <span id="modal-status"></span></p>

            <hr>

            <h3>Appointment Details</h3>
            <p><strong>Type:</strong> <span id="modal-type"></span></p>
            <p><strong>Amount:</strong> <span id="modal-amount"></span></p>
            <p><strong>Location:</strong> <span id="modal-location"></span></p>

            <hr>

            <h3>Contact Information</h3>
            <p><strong>Contact Person:</strong> <span id="modal-contact-person"></span></p>
            <p><strong>Contact Phone:</strong> <span id="modal-contact-phone"></span></p>

            <hr>

            <h3>Additional Notes</h3>
            <p><strong>Notes:</strong> <span id="modal-notes"></span></p>
        </div>
    </div>
</div>

<script>
    function openAppointmentModal(data) {
        document.getElementById("modal-reference-id").textContent = data.referenceId;
        document.getElementById("modal-date").textContent = data.date;
        document.getElementById("modal-time").textContent = data.time;
        document.getElementById("modal-status").textContent = data.status;
        document.getElementById("modal-type").textContent = data.type;
        document.getElementById("modal-amount").textContent = data.amount;
        document.getElementById("modal-location").textContent = data.location;
        document.getElementById("modal-contact-person").textContent = data.contactPerson;
        document.getElementById("modal-contact-phone").textContent = data.contactPhone;
        document.getElementById("modal-notes").textContent = data.notes;

        document.getElementById("appointmentModal").style.display = "flex";
    }

    function closeAppointmentModal() {
        document.getElementById("appointmentModal").style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function(e) {
        let modal = document.getElementById("appointmentModal");
        if (e.target === modal) {
            modal.style.display = "none";
        }
    }
</script>

@endsection