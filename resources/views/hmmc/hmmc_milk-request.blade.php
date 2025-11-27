<!-- resources/views/doctor/milk-records.blade.php -->
@extends('layouts.hmmc')

@section('title', 'Milk Request Records')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/doctor_milk-request.css') }}">
  <!-- Add Font Awesome -->
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
            <h3>Milk Request</h3>
          </div>
          
          <div class="header-right">
            <!-- <button class="btn-add">
              <i class="fas fa-plus"></i> Add Request
            </button> -->
            <input type="text" id="searchInput" class="search-input" placeholder="🔍 Search by Patient ID or Name...">
          </div>
        </div>

        <table class="records-table">
          <thead>
            <tr>
                <th data-sort="id">Request ID <span class="sort-arrow"></span></th>
                <th data-sort="name">Patient Name <span class="sort-arrow"></span></th>
                <th data-sort="nicu">NICU <span class="sort-arrow"></span></th>
                <th data-sort="created_at">Date Requested <span class="sort-arrow"></span></th>
                <th data-sort="feeding_time">Date Time to Give <span class="sort-arrow"></span></th>
                <th data-sort="status">Request Status <span class="sort-arrow"></span></th>
                <th>Actions</th>
            </tr>
          </thead>
          <tbody> 
            @foreach($requests as $req)
            <tr>
                <td><strong>{{ $req->formattedID }}</strong></td>

                <td>
                    <strong>{{ $req->parent->formattedID ?? 'N/A' }}</strong><br>
                    {{ $req->parent->pr_BabyName ?? 'Unknown Baby' }}
                </td>

                <td>{{ $req->parent->pr_NICU ?? '-' }}</td>

                <td>{{ $req->created_at->format('M d, Y') }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($req->feeding_start_date)->format('M d, Y') }}
                    •
                    {{ \Carbon\Carbon::parse($req->feeding_start_time)->format('h:i A') }}
                </td>

                <td>
                    <span class="status 
                        @if($req->status == 'Approved') approved
                        @elseif($req->status == 'Rejected') rejected
                        @elseif($req->status == 'Allocated') allocated
                        @else waiting
                        @endif
                    ">
                        {{ $req->status ?? 'Waiting' }}
                    </span>
                </td>

                <td class="actions">
                    <button class="btn-view" title="View"
                      onclick="openMilkRequestModal({
                        patientId: '{{ $req->parent->formattedID ?? 'N/A' }}',
                        patientName: '{{ $req->parent->pr_BabyName ?? '' }}',
                        nicu: '{{ $req->parent->pr_NICU ?? '' }}',
                        dateRequested: '{{ $req->created_at->format('M d, Y') }}',
                        dateTimeToGive: '{{ \Carbon\Carbon::parse($req->feeding_start_date)->format('M d, Y') }} • {{ \Carbon\Carbon::parse($req->feeding_start_time)->format('h:i A') }}',
                        status: '{{ $req->status ?? 'Waiting' }}',
                        requestedVolume: '{{ $req->recommended_volume }} ml',
                        doctorName: '{{ $req->doctor->dr_Name ?? 'N/A' }}',
                        notes: '{{ $req->notes ?? 'No notes' }}',
                        allergyInfo: '{{ $req->parent->pr_Allergy ?? 'None' }}',
                        weight: '{{ $req->current_weight }} kg'
                      })">
                      <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn-delete" 
                            title="Delete"
                            onclick="confirmDelete({{ $req->request_ID }})">
                        <i class="fas fa-trash"></i>
                    </button>

                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ========================== VIEW MODAL ============================= -->
  <div id="milkRequestModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
            <h2>Milk Request Details</h2>
            <button class="modal-close-btn" onclick="closeMilkRequestModal()">Close</button>
        </div>

      <div class="modal-body">
        <p><strong>Patient ID:</strong> <span id="modal-patient-id"></span></p>
        <p><strong>Patient Name:</strong> <span id="modal-patient-name"></span></p>
        <p><strong>NICU Ward:</strong> <span id="modal-nicu"></span></p>
        <p><strong>Weight:</strong> <span id="modal-weight"></span></p>

        <hr>

        <h3>Request Information</h3>
        <p><strong>Date Requested:</strong> <span id="modal-date-requested"></span></p>
        <p><strong>Scheduled Feeding Time:</strong> <span id="modal-datetime-give"></span></p>
        <p><strong>Requested Volume:</strong> <span id="modal-volume"></span></p>
        <p><strong>Request Status:</strong> <span id="modal-status"></span></p>

        <hr>

        <h3>Medical Information</h3>
        <p><strong>Attending Doctor:</strong> <span id="modal-doctor-name"></span></p>
        <p><strong>Allergy Information:</strong> <span id="modal-allergy"></span></p>
        <p><strong>Additional Notes:</strong> <span id="modal-notes"></span></p>
      </div>

    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function openMilkRequestModal(data) {
      document.getElementById("modal-patient-id").textContent = data.patientId;
      document.getElementById("modal-patient-name").textContent = data.patientName;
      document.getElementById("modal-nicu").textContent = data.nicu;
      document.getElementById("modal-weight").textContent = data.weight;
      document.getElementById("modal-date-requested").textContent = data.dateRequested;
      document.getElementById("modal-datetime-give").textContent = data.dateTimeToGive;
      document.getElementById("modal-volume").textContent = data.requestedVolume;
      document.getElementById("modal-status").textContent = data.status;
      document.getElementById("modal-doctor-name").textContent = data.doctorName;
      document.getElementById("modal-allergy").textContent = data.allergyInfo;
      document.getElementById("modal-notes").textContent = data.notes;

      document.getElementById("milkRequestModal").style.display = "flex";
    }

    function closeMilkRequestModal() {
      document.getElementById("milkRequestModal").style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function(e) {
      let modal = document.getElementById("milkRequestModal");
      if (e.target === modal) {
        modal.style.display = "none";
      }
    }

    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This milk request will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {

                fetch("{{ url('/doctor/milk-request') }}/" + id + "/delete", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Milk request has been deleted.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {

    const table = document.querySelector(".records-table tbody");
    let currentSort = { column: "created_at", direction: "desc" };

    // Apply default sorting on load
    sortTable("created_at");

    document.querySelectorAll("th[data-sort]").forEach(th => {
        th.style.cursor = "pointer";

        th.addEventListener("click", function () {
            let column = this.getAttribute("data-sort");

            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
            } else {
                currentSort.column = column;
                currentSort.direction = "asc";
            }

            sortTable(column);
        });
    });

    function updateArrows() {
        document.querySelectorAll("th[data-sort]").forEach(th => {
            th.classList.remove("active-sort");
            th.querySelector(".sort-arrow").textContent = "";
        });

        let activeTh = document.querySelector(`th[data-sort="${currentSort.column}"]`);
        if (!activeTh) return;

        activeTh.classList.add("active-sort");

        let arrow = currentSort.direction === "asc" ? "▲" : "▼";
        activeTh.querySelector(".sort-arrow").textContent = arrow;
    }

    function sortTable(column) {
        let rows = Array.from(table.querySelectorAll("tr"));

        rows.sort((a, b) => {
            let valA, valB;

            switch (column) {
                case "name":
                    valA = a.cells[0].innerText.toLowerCase();
                    valB = b.cells[0].innerText.toLowerCase();
                    break;

                case "nicu":
                    valA = a.cells[1].innerText.toLowerCase();
                    valB = b.cells[1].innerText.toLowerCase();
                    break;

                case "created_at":
                    valA = new Date(a.cells[2].innerText);
                    valB = new Date(b.cells[2].innerText);
                    break;

                case "feeding_time":
                    valA = new Date(a.cells[3].innerText.replace("•",""));
                    valB = new Date(b.cells[3].innerText.replace("•",""));
                    break;

                case "status":
                    valA = a.cells[4].innerText.toLowerCase();
                    valB = b.cells[4].innerText.toLowerCase();
                    break;
            }

            if (currentSort.direction === "asc") {
                return valA > valB ? 1 : -1;
            } else {
                return valA < valB ? 1 : -1;
            }
        });

        table.innerHTML = "";
        rows.forEach(r => table.appendChild(r));

        updateArrows();
    }

});

document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchInput");
    const tableRows  = document.querySelectorAll(".records-table tbody tr");

    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();

        tableRows.forEach(row => {
            const patientID  = row.cells[0].querySelector("strong")?.innerText.toLowerCase() || "";
            const babyName   = row.cells[0].innerText.toLowerCase();

            if (patientID.includes(keyword) || babyName.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

});
  </script>

@endsection