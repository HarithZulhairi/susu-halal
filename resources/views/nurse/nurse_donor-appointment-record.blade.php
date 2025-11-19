@extends('layouts.nurse')

@section('title', 'Nurse Appointment Record')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/nurse_donor-appointment-record.css') }}">

<div class="container">
  <div class="main-content">
    <div class="page-header">
      <h1>Donor Appointment Record</h1>
    </div>

        <div class="appointment-section">
      <div class="header-controls">
        <h2>Milk Donation Appointment</h2>
        <div class="actions-header">
          <button class="btn-icon"><i class="fas fa-search"></i> Search</button>
          <button class="btn-icon"><i class="fas fa-filter"></i> Filter</button>
        </div>
      </div>

      <div class="tabs">
        <button class="tab-button active" data-tab="all-milk">All Appointment <span class="count">(69)</span></button>
        <button class="tab-button" data-tab="this-month-milk">This Month <span class="count">(13)</span></button>
        <button class="tab-button" data-tab="pending-milk">Pending <span class="count">(11)</span></button>
      </div>

      <div class="table-container">
        <table class="records-table">
          <thead>
            <tr>
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
              <td>15.5.2024</td>
              <td>1000ml</td>
              <td><span class="status-tag pending">Pending</span></td>
              <td>MILK DROP OFF</td>
              <td>Main Center</td>
              <td class="actions">
                <button class="btn-view" 
                  title="View"
                  onclick="openDonorModal({
                    date: '15.5.2024',
                    amount: '1000ml',
                    status: 'Pending',
                    type: 'MILK DROP OFF',
                    location: 'Main Center'
                  })">
                  <i class="fas fa-eye"></i>
                </button>

                {{-- <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button> --}}
                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
              </td>
            </tr>
            <tr>
              <td>24.4.2024</td>
              <td>1000ml</td>
              <td><span class="status-tag confirmed">Confirmed</span></td>
              <td>COLLECTION</td>
              <td>No.50 Kg Beruas</td>
              <td class="actions">
                <button class="btn-view" 
                  title="View"
                  onclick="openDonorModal({
                    date: '24.4.2024',
                    amount: '1000ml',
                    status: 'Confirmed',
                    type: 'COLLECTION',
                    location: 'No.50 Kg Beruas'
                  })">
                  <i class="fas fa-eye"></i>
                </button>

                {{-- <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button> --}}
                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

        <div class="appointment-section">
      <div class="header-controls">
        <h2>Pumping Kit Appointment</h2>
        <div class="actions-header">
          <button class="btn-icon"><i class="fas fa-search"></i> Search</button>
          <button class="btn-icon"><i class="fas fa-filter"></i> Filter</button>
        </div>
      </div>

      <div class="tabs">
        <button class="tab-button active" data-tab="all-kit">All Appointment <span class="count">(69)</span></button>
        <button class="tab-button" data-tab="this-month-kit">This Month <span class="count">(13)</span></button>
        <button class="tab-button" data-tab="pending-kit">Pending <span class="count">(11)</span></button>
      </div>

      <div class="table-container">
        <table class="records-table">
          <thead>
            <tr>
              <th>REFERENCE NO</th>
              <th>DONOR ID</th>
              <th>DATE</th>
              <th>STATUS</th>
              <th>LOCATION</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#MB-2025-0458</td>
              <td>#243</td>
              <td>15.5.2024</td>
              <td><span class="status-tag pending">Pending</span></td>
              <td>Main Center</td>
              <td><button class="btn-confirm" data-id="#MB-2025-0458">Confirm</button></td>
            </tr>
            <tr>
              <td>#MB-2025-0459</td>
              <td>#456</td>
              <td>8.5.2025</td>
              <td><span class="status-tag confirmed">Confirmed</span></td>
              <td>North Branch</td>
              <td><button class="btn-confirm confirmed" disabled>Confirmed</button></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
    <!-- ================== REUSABLE MODAL ================== -->
    <div id="donorModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
            <h2>Appointment Details</h2>
            <button class="modal-close-btn" onclick="closeDonorModal()">Close</button>
        </div>

        <div class="modal-body">
          <p><strong>Date</strong> <span id="modal-date"></span></p>
          <p><strong>Amount</strong> <span id="modal-amount"></span></p>
          <p><strong>Status</strong> <span id="modal-status"></span></p>
          <p><strong>Type</strong> <span id="modal-type"></span></p>
          <p><strong>Location</strong> <span id="modal-location"></span></p>
        </div>

      </div>
    </div>

  </div>
</div>

<script>

// -------- OPEN MODAL ----------
function openDonorModal(data) {
    document.getElementById("modal-date").innerText = data.date;
    document.getElementById("modal-amount").innerText = data.amount;
    document.getElementById("modal-status").innerText = data.status;
    document.getElementById("modal-type").innerText = data.type;
    document.getElementById("modal-location").innerText = data.location;

    document.getElementById("donorModal").style.display = "flex";
}

// -------- CLOSE MODAL ----------
function closeDonorModal() {
    document.getElementById("donorModal").style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
  const tabSections = document.querySelectorAll('.appointment-section');

  // Tab Switching Logic
  tabSections.forEach(section => {
    const tabButtons = section.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });
  });

  // Confirm button logic
  document.querySelectorAll('.btn-confirm:not(.confirmed)').forEach(button => {
    button.addEventListener('click', function() {
      const appointmentId = this.getAttribute('data-id');
      alert(`Appointment ${appointmentId} Confirmed!`);
      this.classList.add('confirmed');
      this.textContent = 'Confirmed';
      this.disabled = true;
    });
  });
});
</script>
@endsection

