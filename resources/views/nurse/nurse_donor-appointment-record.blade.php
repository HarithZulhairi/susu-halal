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

      {{-- =============================== MILK APPOINTMENTS =============================== --}}
    <div class="appointment-section">
      <div class="header-controls">
        <h2>Milk Donation Appointment</h2>

        <input type="text" class="search-box" placeholder="Search appointments...">
      </div>

      <div class="tabs">
        <button class="tab-button active" data-filter="all">All</button>
        <button class="tab-button" data-filter="this-month">This Month</button>
        <button class="tab-button" data-filter="pending">Pending</button>
      </div>

      <div class="table-container">
        <table class="records-table milk-table">
          <thead>
            <tr>
              <th>REFERENCE NUMBER</th>
              <th>DONOR ID</th>
              <th>DATE</th>
              <th>AMOUNT</th>
              <th>STATUS</th>
              <th>TYPE</th>
              <th>LOCATION</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @foreach($milkAppointments as $app)
            <tr 
              data-status="{{ strtolower($app['status']) }}"
              data-date="{{ \Carbon\Carbon::parse($app['date'])->format('Y-m') }}"
            >
              <td>{{ $app['reference'] }}</td>
              <td>#{{ $app['donor_id'] }}</td>

              <td>{{ \Carbon\Carbon::parse($app['date'])->format('d.m.Y') }}</td>

              <td>{{ $app['amount'] ? $app['amount'].' ml' : '-' }}</td>

              <td>
                <span class="status-tag {{ strtolower($app['status']) }}">
                  {{ $app['status'] }}
                </span>
              </td>

              <td>{{ $app['type'] }}</td>

              <td>{{ strtoupper(str_replace('_', ' ', $app['location'] ?? '-')) }}</td>

              <td class="actions">
                <button class="btn-view"
                  onclick="openViewModal({
                    reference: '{{ $app['reference'] }}',
                    donor_id: '{{ $app['donor_id'] }}',
                    date: '{{ \Carbon\Carbon::parse($app['date'])->format('d.m.Y') }}',
                    amount: '{{ $app['amount'] ?? '-' }}',
                    status: '{{ $app['status'] }}',
                    type: '{{ $app['type'] }}',
                    location: '{{ strtoupper(str_replace('_', ' ', $app['location'] ?? '-')) }}'
                  })">
                  <i class="fas fa-eye"></i>
                </button>

                @if($app['status'] == 'Pending')
                <button class="btn-confirm"
                  onclick="openConfirmModal('{{ $app['reference'] }}', 'milk')">
                  Confirm
                </button>
                @else
                <button class="btn-confirm confirmed" disabled>Completed</button>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>



    {{-- =============================== PUMPING KIT APPOINTMENTS =============================== --}}
    <div class="appointment-section">
      <div class="header-controls">
        <h2>Pumping Kit Appointment</h2>

        <input type="text" class="search-box" placeholder="Search appointments...">
      </div>

      <div class="tabs">
        <button class="tab-button active" data-filter="all">All</button>
        <button class="tab-button" data-filter="this-month">This Month</button>
        <button class="tab-button" data-filter="pending">Pending</button>
      </div>

      <div class="table-container">
        <table class="records-table kit-table">
          <thead>
            <tr>
              <th>REFERENCE NUMBER</th>
              <th>DONOR ID</th>
              <th>DATE</th>
              <th>STATUS</th>
              <th>LOCATION</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pumpingAppointments as $app)
            <tr 
              data-status="{{ strtolower($app['status']) }}"
              data-date="{{ \Carbon\Carbon::parse($app['date'])->format('Y-m') }}"
            >
              <td>{{ $app['reference'] }}</td>
              <td>#{{ $app['donor_id'] }}</td>
              <td>{{ \Carbon\Carbon::parse($app['date'])->format('d.m.Y') }}</td>
              
              <td>
                <span class="status-tag {{ strtolower($app['status']) }}">
                  {{ $app['status'] }}
                </span>
              </td>

              <td>{{ strtoupper(str_replace('_', ' ', $app['location'] ?? '-')) }}</td>

              <td>
                @if($app['status'] == 'Pending')
                <button class="btn-confirm"
                  onclick="openConfirmModal('{{ $app['reference'] }}', 'pk')">
                  Confirm
                </button>
                @else
                <button class="btn-confirm confirmed" disabled>Completed</button>
                @endif
              </td>

            </tr>
            @endforeach

          </tbody>
        </table>
      </div>

    </div>

    {{-- =============================== VIEW MODAL =============================== --}}
<div id="viewModal" class="modal-overlay">
  <div class="modal-content">
    <h2>Appointment Details</h2>

    <p><strong>Reference:</strong> <span id="v-reference"></span></p>
    <p><strong>Donor ID:</strong> <span id="v-donor"></span></p>
    <p><strong>Date:</strong> <span id="v-date"></span></p>
    <p><strong>Amount:</strong> <span id="v-amount"></span></p>
    <p><strong>Status:</strong> <span id="v-status"></span></p>
    <p><strong>Type:</strong> <span id="v-type"></span></p>
    <p><strong>Location:</strong> <span id="v-location"></span></p>

    <button class="modal-close-btn" onclick="closeViewModal()">Close</button>
  </div>
</div>



{{-- =============================== CONFIRM MODAL =============================== --}}
<div id="confirmModal" class="modal-overlay">
  <div class="modal-content">
    <h2>Confirm Appointment?</h2>
    <p>Are you sure you want to mark this appointment as <strong>Completed</strong>?</p>

    <p><strong>Reference:</strong> <span id="c-reference"></span></p>

    <form id="confirmForm" method="POST">
      @csrf
      @method('PUT')
      <button type="submit" class="modal-edit-btn">Confirm</button>
    </form>

    <button class="modal-close-btn" onclick="closeConfirmModal()">Cancel</button>
  </div>
</div>




<script>
/* ---------------- VIEW MODAL ---------------- */
function openViewModal(data){
  document.getElementById("v-reference").innerText = data.reference;
  document.getElementById("v-donor").innerText = '#' + data.donor_id;
  document.getElementById("v-date").innerText = data.date;
  document.getElementById("v-amount").innerText = data.amount;
  document.getElementById("v-status").innerText = data.status;
  document.getElementById("v-type").innerText = data.type;
  document.getElementById("v-location").innerText = data.location;

  document.getElementById("viewModal").style.display = "flex";
}

function closeViewModal(){
  document.getElementById("viewModal").style.display = "none";
}



/* ---------------- CONFIRM MODAL ---------------- */
function openConfirmModal(reference, type){
  document.getElementById("c-reference").innerText = reference;

  let url = type === "milk"
      ? `/nurse/appointments/confirm/milk/${reference}`
      : `/nurse/appointments/confirm/pk/${reference}`;

  document.getElementById("confirmForm").action = url;

  document.getElementById("confirmModal").style.display = "flex";
}

function closeConfirmModal(){
  document.getElementById("confirmModal").style.display = "none";
}



/* ---------------- FILTERING (tabs + search) ---------------- */
document.querySelectorAll(".appointment-section").forEach(section => {

  let rows = section.querySelectorAll("tbody tr");
  let searchBox = section.querySelector(".search-box");
  let tabs = section.querySelectorAll(".tab-button");

  let currentFilter = "all";
  let searchTerm = "";

  // SEARCH
  searchBox.addEventListener("input", function(){
      searchTerm = this.value.toLowerCase();
      applyFilters();
  });

  // TABS
  tabs.forEach(tab => {
    tab.addEventListener("click", function(){
      tabs.forEach(t => t.classList.remove("active"));
      this.classList.add("active");

      currentFilter = this.dataset.filter;
      applyFilters();
    });
  });

  // Apply filters
  function applyFilters(){
    let now = new Date();
    let ym = now.getFullYear() + "-" + String(now.getMonth()+1).padStart(2,"0");

    rows.forEach(row => {
      let status = row.dataset.status;
      let dateYM = row.dataset.date;
      let text = row.innerText.toLowerCase();

      let pass = true;

      // filter by tab
      if(currentFilter === "pending" && status !== "pending") pass = false;
      if(currentFilter === "this-month" && dateYM !== ym) pass = false;

      // search
      if(searchTerm && !text.includes(searchTerm)) pass = false;

      row.style.display = pass ? "" : "none";
    });
  }

});

</script>
@endsection

