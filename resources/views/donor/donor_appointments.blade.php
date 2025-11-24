@extends('layouts.donor')

@section('title', 'Donor Appointments')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_appointments.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<style>
.success-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.success-modal {
    background: white;
    padding: 35px;
    border-radius: 16px;
    text-align: center;
    max-width: 550px;
    width: 90%;
    animation: popin 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.icon-circle {
    background: #4CAF50;
    width: 95px;
    height: 95px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 18px;
    color: white;
    font-size: 46px;
}

.success-modal h2 {
    font-size: 22px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

.success-modal p {
    color: #555;
    font-size: 15px;
    margin-bottom: 12px;
}

.btn-close-success {
    margin-top: 22px;
    padding: 12px 22px;
    background: #4CAF50;
    border: none;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.2s;
}

.btn-close-success:hover {
    background: #3b8d40;
}

@keyframes popin {
    from { transform: scale(0.85); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>




<div class="container">
@if(session('success_created'))
<div id="successModalCreated" class="success-modal-overlay">
    <div class="success-modal">
        <div class="icon-circle">
            <i class="fas fa-check"></i>
        </div>

        <h2>Appointment Submitted Successfully!</h2>

        <p>Your appointment request has been received. We will contact you soon to confirm the schedule.</p>

        <p style="margin-top: 10px; font-weight: bold;">
            Reference Number: {{ session('reference') }}
        </p>

        <button class="btn-close-success" onclick="document.getElementById('successModalCreated').style.display='none'">
            Close
        </button>
    </div>
</div>
@endif

@if(session('success_updated'))
<div id="successModalUpdated" class="success-modal-overlay">
    <div class="success-modal">
        <div class="icon-circle">
            <i class="fas fa-check"></i>
        </div>

        <h2>Your Reschedule Has Been Accepted</h2>

        <p>Your reschedule request has been received. We will contact you soon to confirm the schedule.</p>

        <p style="margin-top: 10px; font-weight: bold;">
            Reference Number: {{ session('reference') }}
        </p>

        <button class="btn-close-success" onclick="document.getElementById('successModalUpdated').style.display='none'">
            Close
        </button>
    </div>
</div>
@endif


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

            <div class="appointments-section filter-section">
                <div class="section-header">
                    <h2>My Appointments</h2>
                    <div class="header-actions">
                        <input type="text" 
                            class="search-box" 
                            placeholder="Search appointments..."
                            style="
                                    padding: 8px 12px;
                                    border: 1px solid #ccc;
                                    border-radius: 6px;
                                    width: 250px;
                        ">

                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active" data-filter="all">All Appointment <span class="badge">{{ $confirmed + $pending }}</span></button>
                    <button class="tab" data-filter="confirmed">Confirmed <span class="badge">{{ $confirmed }}</span></button>
                    <button class="tab" data-filter="pending">Pending <span class="badge">{{ $pending }}</span></button>

                </div>

                <div class="table-container">
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>REFERENCE NUMBER</th>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>TYPE</th>
                                <th>LOCATION</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr class="no-records" style="display:none;">
                        <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                            No appointments yet.
                        </td>
                        </tr>

                        <tr class="no-search-results" style="display:none;">
                            <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                No results found.
                            </td>
                        </tr>
                        @forelse($currentAppointments as $appointment)
                            <tr data-status="{{ strtolower($appointment->status) }}">
                                <td><span class="ref-id">{{ $appointment->reference_num }}</span></td>

                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('d.m.Y') }}</td>

                                <td>
                                    {{ $appointment->milk_amount ? $appointment->milk_amount . ' ml' : '-' }}
                                </td>

                                <td>
                                    <span class="status {{ strtolower($appointment->status) }}">
                                        {{ ucfirst($appointment->status ?? 'Pending') }}
                                    </span>
                                </td>

                                <td>{{ strtoupper(str_replace('_', ' ', $appointment->type) ?? 'PUMPING KIT') }}</td>


                                <td>{{ strtoupper(str_replace('_', ' ', $appointment->location ?? $appointment->collection_address ?? '-')) }}</td>


                                <td class="actions">
                                    <button class="btn-view"
                                        onclick="openAppointmentModal({
                                            referenceId: '{{ $appointment->reference_num }}',
                                            date: '{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('d.m.Y') }}',
                                            time: '{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('h:i A') }}',
                                            amount: '{{ $appointment->milk_amount ? $appointment->milk_amount . " ml" : "-" }}',
                                            status: '{{ ucfirst($appointment->status ?? "Pending") }}',
                                            type: '{{ strtoupper(str_replace('_', ' ', $appointment->type) ?? 'PUMPING KIT') }}',
                                            location: '{{ strtoupper(str_replace('_', ' ', $appointment->location ?? $appointment->collection_address ?? '-')) }}',
                                            contactPerson: 'Staff',
                                            contactPhone: '+60 12-345 6789',
                                            notes: '{{ e($appointment->remarks ?? "No additional notes.") }}'
                                        })">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="btn-edit"
                                        onclick="openEditModal({
                                            id: '{{ $appointment->appointment_category == "Milk Donation" ? $appointment->ma_ID : $appointment->pk_ID }}',
                                            type: '{{ $appointment->appointment_category }}',
                                            referenceId: '{{ $appointment->reference_num }}',
                                            datetime: '{{ $appointment->appointment_datetime }}',
                                        })">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn-cancel"
                                         onclick="openCancelModal({
                                            id: '{{ $appointment->appointment_category == "Milk Donation" ? $appointment->ma_ID : $appointment->pk_ID }}',
                                            type: '{{ $appointment->appointment_category }}',
                                            referenceId: '{{ $appointment->reference_num }}',
                                            datetime: '{{ $appointment->appointment_datetime }}'
                                        })">
                                        <i class="fas fa-times"></i>
                                    </button>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No appointments yet.
                                </td>
                            </tr>
                            @endforelse

                    </tbody>
                    </table>
                </div>
            </div>

            <div class="appointments-section filter-section">
                <div class="section-header">
                    <h2>Appointment History</h2>
                    <div class="header-actions">
                        <input type="text" 
                            class="search-box" 
                            placeholder="Search appointments..."
                            style="
                                    padding: 8px 12px;
                                    border: 1px solid #ccc;
                                    border-radius: 6px;
                                    width: 250px;
                        ">
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active" data-filter="all">All Appointment <span class="badge">{{ $completed + $canceled }}</span></button>
                    <button class="tab" data-filter="completed">Completed <span class="badge">{{ $completed }}</span></button>
                    <button class="tab" data-filter="canceled">Canceled <span class="badge">{{ $canceled }}</span></button>
                </div>

                <div class="table-container">
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>REFERENCE NUMBER</th>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>TYPE</th>
                                <th>LOCATION</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr class="no-records" style="display:none;">
                        <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                            No appointments yet.
                        </td>
                        </tr>

                        <tr class="no-search-results" style="display:none;">
                            <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                No results found.
                            </td>
                        </tr>

                        @forelse($historyAppointments  as $hisappointment)
                            <tr data-status="{{ strtolower($hisappointment->status) }}">
                                <td><span class="ref-id">{{ $hisappointment->reference_num }}</span></td>

                                <td>{{ \Carbon\Carbon::parse($hisappointment->appointment_datetime)->format('d.m.Y') }}</td>

                                <td>
                                    {{ $hisappointment->milk_amount ? $hisappointment->milk_amount . ' ml' : '-' }}
                                </td>

                                <td>
                                    <span class="status {{ strtolower($hisappointment->status) }}">
                                        {{ ucfirst($hisappointment->status ?? 'Pending') }}
                                    </span>
                                </td>

                                <td>{{ strtoupper(str_replace('_', ' ', $hisappointment->type) ?? 'PUMPING KIT') }}</td>


                                <td>{{ strtoupper(str_replace('_', ' ', $hisappointment->location ?? $hisappointment->collection_address ?? '-')) }}</td>


                                <td class="actions">
                                    <button class="btn-view"
                                        onclick="openAppointmentModal({
                                            referenceId: '{{ $hisappointment->reference_num }}',
                                            date: '{{ \Carbon\Carbon::parse($hisappointment->appointment_datetime)->format('d.m.Y') }}',
                                            time: '{{ \Carbon\Carbon::parse($hisappointment->appointment_datetime)->format('h:i A') }}',
                                            amount: '{{ $hisappointment->milk_amount ? $hisappointment->milk_amount . " ml" : "-" }}',
                                            status: '{{ ucfirst($hisappointment->status ?? "Pending") }}',
                                            type: '{{ strtoupper(str_replace('_', ' ', $hisappointment->type) ?? 'PUMPING KIT') }}',
                                            location: '{{ strtoupper(str_replace('_', ' ', $hisappointment->location ?? $hisappointment->collection_address ?? '-')) }}',
                                            contactPerson: 'Staff',
                                            contactPhone: '+60 12-345 6789',
                                            notes: '{{ e($hisappointment->remarks ?? "No additional notes.") }}'
                                        })">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No appointments yet.
                                </td>
                            </tr>
                            @endforelse

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
            <p><strong>Reference Number:</strong> <span id="modal-reference-id"></span></p>
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

<!-- ========================== EDIT APPOINTMENT DETAILS MODAL ============================= -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        
        <div class="modal-header">
            <h2>Reschedule Appointment</h2>
            <button class="modal-close-btn" onclick="closeEditModal()">Close</button>
        </div>

        
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <h3>Appointment Details</h3>
                <p><strong>Reference Number:</strong> <span id="emodal-reference-id"></span></p>
                <hr>
                <h3>New Date and Time</h3>

                <!-- Editable Date -->

                <div class="form-group">
                    <input type="datetime-local" id="edit_datetime" name="edit_datetime" class="form-control" required>
                </div>
                <hr>

                <h3>Reason</h3>
                <div class="form-group full-width">
                    <textarea id="remarks" name="remarks" class="form-control" rows="2" placeholder="Optional..."></textarea>
                </div>

                <button class="modal-edit-btn">Save Changes</button>
            </div>
        </form>

    </div>
</div>

<!-- ========================== CANCEL APPOINTMENT MODAL ============================= -->
<div id="cancelModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">

        <div class="modal-header">
            <h2>Cancel Appointment</h2>
            <button class="modal-close-btn" onclick="closeCancelModal()">Close</button>
        </div>

        <div class="modal-body">
            <p>Are you sure you want to cancel this appointment?</p>
            <p><strong>Reference Number:</strong> <span id="cancel-reference-id"></span></p>

            <form id="cancelForm" method="POST" action="">
                @csrf
                @method('PUT')

                <button type="submit" class="modal-edit-btn" style="background:#d9534f;">
                    Confirm Cancellation
                </button>
            </form>
        </div>

    </div>
</div>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("edit_datetime");
        
        const updateMinTime = () => {
            const now = new Date();
            const minTime = new Date(now.getTime() + 5 * 60 * 60 * 1000);
            input.min = minTime.toISOString().slice(0, 16);
        };

        updateMinTime();

        input.addEventListener("input", function() {
            const selected = new Date(this.value);
            const now = new Date();
            const minTime = new Date(now.getTime() + 5 * 60 * 60 * 1000);

            if (selected < minTime) {
                alert("Appointment must be at least 5 hours from now.");
                this.value = "";
            }
        });
    });

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

    function openEditModal(data) {
    document.getElementById("editModal").style.display = "flex";

    document.getElementById("emodal-reference-id").textContent = data.referenceId;
    document.getElementById("edit_datetime").value = data.datetime;

    let url = data.type === "Milk Donation"
        ? `/donor/appointments/update/milk/${data.id}`
        : `/donor/appointments/update/pk/${data.id}`;


        document.getElementById("editForm").action = url;
   
}

    function openCancelModal(data) {
        console.log("CANCEL MODAL DATA:", data); // Debug log

        document.getElementById("cancelModal").style.display = "flex";

        document.getElementById("cancel-reference-id").textContent = data.referenceId;

        let url = data.type === "Milk Donation"
            ? `/donor/appointments/cancel/milk/${data.id}`
            : `/donor/appointments/cancel/pk/${data.id}`;

        document.getElementById("cancelForm").action = url;
    }

    function closeCancelModal() {
        document.getElementById("cancelModal").style.display = "none";
    }


    function closeAppointmentModal() {
        document.getElementById("appointmentModal").style.display = "none";
    }

    function closeEditModal() {
        document.getElementById("editModal").style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function(e) {
        let appointmentModal = document.getElementById("appointmentModal");
        let editModal = document.getElementById("editModal");
        let cancelModal = document.getElementById("cancelModal");
        if (e.target === appointmentModal) {
            appointmentModal.style.display = "none";
        } else if (e.target === editModal){
            editModal.style.display = "none";
        }else if (e.target === cancelModal){
            editModal.style.display = "none";
        }
    }

document.querySelectorAll(".filter-section").forEach(section => {

    let rows = Array.from(section.querySelectorAll("tbody tr"))
        .filter(r => !r.classList.contains("no-records") &&
                     !r.classList.contains("no-search-results"));

    let emptyTabRow = section.querySelector(".no-records");
    let emptySearchRow = section.querySelector(".no-search-results");

    let tabs = section.querySelectorAll(".tabs .tab");
    let searchBox = section.querySelector(".search-box");

    let activeFilter = "all";
    let searchTerm = "";

    // TAB FILTER
    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");

            activeFilter = this.getAttribute("data-filter");
            applyFilters();
        });
    });

    // LIVE SEARCH (always visible)
    searchBox.addEventListener("input", function () {
        searchTerm = this.value.toLowerCase().trim();
        applyFilters();
    });

    function applyFilters() {
        let visibleCount = 0;

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            let status = row.getAttribute("data-status");

            let matchesTab = (activeFilter === "all" || status === activeFilter);
            let matchesSearch = (searchTerm === "" || text.includes(searchTerm));

            if (matchesTab && matchesSearch) {
                row.style.display = "table-row";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        // Show "no appointments" if the tab is empty
        if (rows.filter(r => r.getAttribute("data-status") === activeFilter).length === 0
            && activeFilter !== "all"
            && searchTerm === "") {

            emptyTabRow.style.display = "table-row";
            emptySearchRow.style.display = "none";
            return;
        }

        emptyTabRow.style.display = "none";

        // Show "no search results"
        emptySearchRow.style.display = (visibleCount === 0 ? "table-row" : "none");
    }
});



</script>

@endsection