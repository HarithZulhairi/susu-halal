@extends('layouts.nurse')

@section('title', 'Donor Candidates')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/nurse_donor-candidates.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="container">

    <div class="main-content">
    <div class="page-header">
        <h1>Donor-To-Be</h1>
        <p>Manage and review applicants for becoming a donor to be approved.</p>
    </div>

    <div class="candidates-container">
        <div class="candidates-card">
            <div class="card-header">
                <h2>HMMC Donor Candidates</h2>
                <div class="header-actions">
                </div>
            </div>

             <!-- ================= Tabs ================= -->
            <div class="tabs">
            <button class="tab active" data-filter="all">
                All Candidates <span class="badge">{{ $total }}</span>
            </button>

            <button class="tab" data-filter="month">
                This Month 
                <span class="badge">{{ $dtb->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count() }}</span>
            </button>

            <button class="tab" data-filter="pending">
                Pending Review 
                <span class="badge">{{ $pending }}</span>
            </button>
            </div>

             <!-- ================= Table ================= -->
        <div class="table-container">
          <table class="candidates-table">
            <thead>
              <tr>
                <th>Candidate ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Screening Remark</th>
                <th>Eligible Status</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>

              @forelse($dtb as $c)

                @php
                    // Safely reference donor (in case relation missing)
                    $donor = $c->donor ?? null;

                    $donorName = $donor->dn_FullName ?? '-';
                    $donorEmail = $donor->dn_Email ?: '-';
                    $donorPhone = $donor->dn_Contact ?: '-';
                    $donorNric  = $donor->dn_NRIC ?: '-';
                    $donorAddress = $donor->dn_Address ?: '-';

                    $screeningRemark = $c->dtb_ScreeningRemark ?: '-';
                    $screeningStatus = $c->dtb_ScreeningStatus ?? 'Pending';

                    $eligible = $screeningStatus === 'passed' ? 'Passed'
                            : ($screeningStatus === 'failed' ? 'Failed' : 'Pending');

                    // Avatar initials (safe)
                    $initials = '-';
                    if($donorName && $donorName !== '-') {
                        $parts = preg_split('/\s+/', trim($donorName));
                        $first = strtoupper(substr($parts[0] ?? '', 0, 1));
                        $last  = strtoupper(substr(end($parts) ?? '', 0, 1));
                        $initials = trim($first . $last) ?: strtoupper(substr($donorName,0,1));
                    }
                @endphp

              <tr 
                data-id="{{ $c->dtb_id }}"
                data-name="{{ $donorName }}"
                data-email="{{ $donorEmail ?? '-' }}"
                data-phone="{{ $donorPhone }}"
                data-ic="{{ $donorNric}}"
                data-address="{{ $donorAddress }}"
                data-screening="{{ strtolower($screeningStatus) }}"
                data-eligible="{{ strtolower($eligible) }}"
                data-applied="{{ $c->created_at->format('d.m.Y') }}"
              >
                <td class="candidate-id">#{{ $c->dtb_id }}</td>

                <td class="full-name">
                  <div class="name-cell">
                    <div class="avatar">
                      {{ $initials }}
                    </div>
                    <span>{{ $donorName}}</span>
                  </div>
                </td>

                <td class="email">{{ $donorEmail }}</td>

                <td>
                  <span class="status-badge {{ $screeningRemark }}">
                    {{ $screeningRemark }}
                  </span>
                </td>

                <td>
                  <span class="status-badge {{ strtolower($eligible) }}">
                    {{ $eligible }}
                  </span>
                </td>

                <td class="actions">
                  <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                  <button class="btn-action btn-approve" title="Approve Screening"><i class="fas fa-check"></i></button>
                  <button class="btn-action btn-reject" title="Reject Screening"><i class="fas fa-times"></i></button>
                </td>
              </tr>

              @empty
                <tr>
                  <td colspan="6" style="text-align:center; padding:40px; color:#9ca3af;">
                    No candidates found.
                  </td>
                </tr>
              @endforelse

            </tbody>

          </table>
        </div>

        <!-- ================= Table Footer ================= -->
        <div class="table-footer">
          <div class="showing-info">
            Showing {{ $dtb->count() }} candidates
          </div>
        </div>

      </div>
    </div>

  </div>
</div>


{{-- ========================================================= --}}
{{-- ======================= MODALS ========================== --}}
{{-- ========================================================= --}}

<!-- View Details Modal -->
<div id="candidateModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Candidate Details</h2>
      <button class="modal-close-btn">Close</button>
    </div>

    <div class="modal-body">
      <p><strong>Full Name:</strong> <span id="c-fullname"></span></p>
      <p><strong>Email:</strong> <span id="c-email"></span></p>
      <p><strong>Phone:</strong> <span id="c-phone"></span></p>
      <p><strong>IC Number:</strong> <span id="c-ic"></span></p>
      <p><strong>Address:</strong> <span id="c-address"></span></p>
      <hr>
      <p><strong>Screening Status:</strong> <span id="c-screening"></span></p>
      <p><strong>Eligibility Status:</strong> <span id="c-eligible"></span></p>
      <p><strong>Date Applied:</strong> <span id="c-applied"></span></p>
    </div>
  </div>
</div>


<!-- Approve Modal -->
<div id="approveModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Approve Screening</h2>
      <button class="modal-close-btn">Close</button>
    </div>

    <form id="approveForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <p>Are you sure you want to approve this screening?</p>

        <div class="modal-section">
          <label>Remarks (optional)</label>
          <textarea id="approve_remarks" name="remarks" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="modal-save-btn">Approve</button>
      </div>
    </form>
  </div>
</div>


<!-- Reject Modal -->
<div id="rejectModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Reject Screening</h2>
      <button class="modal-close-btn">Close</button>
    </div>

    <form id="rejectForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <p>Please provide the reason for rejection.</p>

        <div class="modal-section">
          <label>Reason <span class="text-danger">*</span></label>
          <textarea id="reject_reason" name="reason" class="form-control" rows="4" required></textarea>
        </div>

        <div style="display:flex; gap:12px;">
          <button type="button" class="modal-close-btn">Cancel</button>
          <button type="submit" class="modal-cancel-btn">Reject</button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- ========================================================= --}}
{{-- ======================== SCRIPTS ========================= --}}
{{-- ========================================================= --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

  function openModal(el) { el.style.display = "flex"; }
  function closeModal(el) { el.style.display = "none"; }

  const candidateModal = document.getElementById("candidateModal");
  const approveModal = document.getElementById("approveModal");
  const rejectModal = document.getElementById("rejectModal");

  // Close modals on close button
  document.querySelectorAll(".modal-close-btn").forEach(btn => {
    btn.addEventListener("click", () => closeModal(btn.closest(".modal-overlay")));
  });

  // Close on outside click
  window.onclick = (e) => {
    if (e.target.classList.contains("modal-overlay")) {
      closeModal(e.target);
    }
  };

  // Read row data
  function readRow(row) {
    return {
      id: row.dataset.id,
      name: row.dataset.name,
      email: row.dataset.email,
      phone: row.dataset.phone,
      ic: row.dataset.ic,
      address: row.dataset.address,
      screening: row.dataset.screening,
      eligible: row.dataset.eligible,
      applied: row.dataset.applied
    };
  }

  // Open details modal
  document.querySelectorAll(".btn-view").forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr");
      const d = readRow(row);

      document.getElementById("c-fullname").textContent = d.name;
      document.getElementById("c-email").textContent = d.email;
      document.getElementById("c-phone").textContent = d.phone;
      document.getElementById("c-ic").textContent = d.ic;
      document.getElementById("c-address").textContent = d.address;
      document.getElementById("c-screening").textContent = d.screening;
      document.getElementById("c-eligible").textContent = d.eligible;
      document.getElementById("c-applied").textContent = d.applied;

      openModal(candidateModal);
    });
  });

  // Approve modal
  document.querySelectorAll(".btn-approve").forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr");
      const id = row.dataset.id;

      document.getElementById("approveForm").action = `/nurse/candidates/approve/${id}`;
      document.getElementById("approve_remarks").value = "";

      openModal(approveModal);
    });
  });

  // Reject modal
  document.querySelectorAll(".btn-reject").forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr");
      const id = row.dataset.id;

      document.getElementById("rejectForm").action = `/nurse/candidates/reject/${id}`;
      document.getElementById("reject_reason").value = "";

      openModal(rejectModal);
    });
  });


  // ===================================================
  // FILTERING + SEARCH (like reference page)
  // ===================================================

  const tabs = document.querySelectorAll(".tab");
  const rows = Array.from(document.querySelectorAll("tbody tr"));

  // Hide approve/reject buttons if status is not pending
    rows.forEach(row => {
        const status = row.dataset.screening; // "pending", "approved", "rejected"
        
        if (status !== "pending") {
            const approveBtn = row.querySelector(".btn-approve");
            const rejectBtn = row.querySelector(".btn-reject");

            if (approveBtn) approveBtn.style.display = "none";
            if (rejectBtn) rejectBtn.style.display = "none";
        }
    });

  let activeFilter = "all";
  let searchTerm = "";

  // Add search box dynamically
  const searchBox = document.createElement("input");
  searchBox.className = "search-box";
  searchBox.placeholder = "Search candidates...";
  document.querySelector(".header-actions").prepend(searchBox);

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
      activeFilter = tab.dataset.filter;
      applyFilters();
    });
  });

  searchBox.addEventListener("input", () => {
    searchTerm = searchBox.value.toLowerCase().trim();
    applyFilters();
  });

  function applyFilters() {
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const screening = row.dataset.screening;
      const eligible = row.dataset.eligible;

      let matchesTab = false;
      if (activeFilter === "all") matchesTab = true;
      else if (activeFilter === "pending") {
        matchesTab = (screening === "pending" || eligible === "pending");
      }
      else if (activeFilter === "month") {
        matchesTab = true; // Optional: Replace with real date logic if needed
      }

      const matchesSearch = (text.includes(searchTerm));

      row.style.display = (matchesTab && matchesSearch) ? "" : "none";
    });
  }

});
</script>

@endsection