@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/doctor_milk-request.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div>
            <h1>Welcome, {{ auth()->user()->name }}<br>
            <p class="muted">Shariah-compliant Human Milk Bank • Doctor Dashboard</p>
            </h1>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Recipients</span>
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>  
            <div class="stat-value">{{ $totalPatients ?? 124 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Active Donor</span>
                <div class="stat-icon green">
                    <i class="fas fa-file-medical"></i>
                </div>
            </div>
            <div class="stat-value">{{ $activeDonors ?? 42 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Milk Requests</span>
                <div class="stat-icon orange">
                    <i class="fas fa-baby"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingRequests ?? 8 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Approved Requests</span>
                <div class="stat-icon red">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-value">{{ $approvedRequests ?? 0 }}</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Prescription Statistics -->
        <div class="card chart-card">
            <div class="card-header">
                <h2>Prescription & Milk Request Trends</h2>
                <a href="#" class="view-report">
                    View Report
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="chart-body" style="height: 300px; position: relative;">
                <canvas id="doctorStatsChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card quick-stats-card">
            <h2>Quick Actions</h2>
            <div class="quick-stats-list">
                <a href="{{ route('doctor.donor-candidate-list') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-calendar-plus"></i></div>
                        <div class="quick-stat-label">View Donor List</div>
                    </div>
                    <span class="quick-stat-badge primary">View Now</span>
                </a>
                <a href="{{ route('doctor.doctor_milk-request-form') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-box"></i></div>
                        <div class="quick-stat-label">Request Milk</div>
                    </div>
                    <span class="quick-stat-badge primary">Request Now</span>
                </a>
                <a href="{{ route('doctor.doctor_milk-request') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-list"></i></div>
                        <div class="quick-stat-label">View Milk Record</div>
                    </div>
                    <span class="quick-stat-badge primary">View Now</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="quick-stat-item" style="text-decoration: none;">
                    <div class="quick-stat-info">
                        <div class="quick-stat-value"><i class="fas fa-user-edit"></i></div>
                        <div class="quick-stat-label">Update Profile</div>
                    </div>
                    <span class="quick-stat-badge primary">Edit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Milk Records Table -->
    <div class="card">
        <div class="card-header">
            <div class="header-left">
                <h3>Recent Milk Requests</h3>
            </div>
            
            <div class="header-right">
                <input type="text" id="searchInput" class="search-input" placeholder="🔍 Search by Patient ID or Name...">
            </div>
        </div>

        <table class="records-table">
            <thead>
                <tr>
                    <th data-sort="name">Patient Name <span class="sort-arrow"></span></th>
                    <th data-sort="nicu">NICU <span class="sort-arrow"></span></th>
                    <th data-sort="created_at">Date Requested <span class="sort-arrow"></span></th>
                    <th data-sort="feeding_time">Date Time to Give <span class="sort-arrow"></span></th>
                    <th data-sort="status">Request Status <span class="sort-arrow"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody> 
                @foreach($recentRequests as $req)
                <tr>
                    <td>
                        <strong>{{ $req->parent->formattedID ?? 'N/A' }}</strong><br>
                        {{ $req->parent->pr_BabyName ?? 'Unknown Baby' }}
                    </td>

                    <td>{{ $req->parent->pr_NICU ?? '-' }}</td>

                    <td>{{ $req->created_at->format('d/m/Y') }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($req->feeding_start_date)->format('d/m/Y') }}
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const ctx = document.getElementById('doctorStatsChart');

// gradient fill for purple line
const gradientBlue = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientBlue.addColorStop(0, 'rgba(99, 102, 241, 0.5)');
gradientBlue.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

// gradient fill for green line
const gradientGreen = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'Prescriptions',
                data: @json($prescriptionsData),
                borderColor: '#6366F1',
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#6366F1',
                pointHoverRadius: 7,
            },
            {
                label: 'Milk Requests',
                data: @json($milkRequestsData),
                borderColor: '#10B981',
                backgroundColor: gradientGreen,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#10B981',
                pointHoverRadius: 7,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: '#444', boxWidth: 12, padding: 15, font: { size: 13 } }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111',
                bodyColor: '#333',
                borderColor: '#E2E8F0',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: context => `${context.dataset.label}: ${context.formattedValue}`
                }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#555', stepSize: 20 } },
            x: { grid: { display: false }, ticks: { color: '#555' } }
        },
        animations: {
            tension: { duration: 2000, easing: 'easeOutElastic', from: 0.5, to: 0.4 }
        }
    }
});

// Milk Request Modal Functions
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

// Sorting functionality
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

    // Search functionality
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