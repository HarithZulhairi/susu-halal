@extends('layouts.nurse')

@section('title', 'Milk Request Records')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/nurse_allocate-milk.css') }}">

<div class="container">
  <div class="main-content">

    <div class="page-header">
      <h1>Milk Request Records</h1>
      <p>Milk Processing and Records</p>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Milk Processing and Records</h2>
        <div class="actions">
          <button class="btn"><i class="fas fa-search"></i> Search</button>
          <button class="btn"><i class="fas fa-filter"></i> Filter</button>
          <button class="btn"><i class="fas fa-ellipsis-h"></i></button>
        </div>
      </div>

      <table class="records-table">
        <thead>
          <tr>
            <th>Patient Name</th>
            <th>NICU</th>
            <th>Date Requested</th>
            <th>Request Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ([
            ['id' => 'P001', 'name' => 'Sarah Ahmad Binti Fauzi', 'nicu' => '3B', 'date' => 'Jan 12, 2024', 'status' => 'approved'],
            ['id' => 'P002', 'name' => 'Maryam Binti Othman', 'nicu' => '2A', 'date' => 'Feb 05, 2024', 'status' => 'approved'],
            ['id' => 'P003', 'name' => 'Fatimah Az-zahra Binti Mohd Nor', 'nicu' => '1C', 'date' => 'May 10, 2024', 'status' => 'pending'],
            ['id' => 'P004', 'name' => 'Aishah Radhi Binti Izzuddin', 'nicu' => '4B', 'date' => 'Dec 18, 2023', 'status' => 'approved'],
            ['id' => 'P005', 'name' => 'Nor Atiqah Humaira Binti Ishak', 'nicu' => '2B', 'date' => 'Mar 22, 2024', 'status' => 'rejected']
          ] as $patient)
          <tr>
            <td>
              <div class="patient-info">
                <i class="fas fa-bottle-droplet milk-icon"></i>
                <div>
                  <strong>{{ $patient['id'] }}</strong><br>
                  <span>{{ $patient['name'] }}</span>
                </div>
              </div>
            </td>
            <td>{{ $patient['nicu'] }}</td>
            <td>{{ $patient['date'] }}</td>
            <td><span class="status {{ $patient['status'] }}">{{ ucfirst($patient['status']) }}</span></td>
            <td class="actions">
              <button class="btn-done" title="Done"><i class="fas fa-check"></i></button>
                <button class="btn-view" title="View" 
                    onclick="openViewModal('{{ $patient['id'] }}', '{{ $patient['name'] }}', '{{ $patient['nicu'] }}', '{{ $patient['date'] }}')">
                    <i class="fas fa-eye"></i>
                </button>
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div id="viewModal" class="modal-overlay" style="display:none;">
      <div class="modal-content">
        
        <div class="modal-header">
          <h2>Request Details</h2>
          <button class="modal-close-btn" onclick="closeViewModal()">Close</button>
        </div>

        <div class="modal-body">
          <p><strong>Patient ID:</strong> <span id="modalPatientId"></span></p>
          <p><strong>Name:</strong> <span id="modalPatientName"></span></p>
          <p><strong>NICU:</strong> <span id="modalPatientNicu"></span></p>
          <p><strong>Date:</strong> <span id="modalPatientDate"></span></p>

          <hr>

          <h3><i class="fas fa-clipboard-list"></i> Allocate Milk</h3>
          <div id="milkListContainer">
             </div>

          <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
            <button onclick="closeViewModal()" style="padding: 10px 20px; border: 1px solid #ccc; background: white; border-radius: 8px; cursor:pointer;">Cancel</button>
             <button class="modal-close-btn" style="margin:0; background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">Save Allocation</button>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>

<script>
    function openViewModal(id, name, nicu, date) {
        // 1. Populate Basic Info
        document.getElementById("modalPatientId").textContent = id;
        document.getElementById("modalPatientName").textContent = name;
        document.getElementById("modalPatientNicu").textContent = nicu;
        document.getElementById("modalPatientDate").textContent = date;

        // 2. Generate Mock Milk Data (Replace with AJAX later)
        const milkIds = ["MILK-001", "MILK-002", "MILK-003", "MILK-004"];
        const container = document.getElementById("milkListContainer");
        container.innerHTML = "";

        milkIds.forEach(milkId => {
            const row = document.createElement("div");
            row.className = "milk-row"; // Uses existing milk-row style
            row.innerHTML = `
                <input type="checkbox" class="milk-check" id="check-${milkId}">
                <label for="check-${milkId}" style="flex:1; font-weight:500;">${milkId}</label>
                <input type="datetime-local" class="milk-datetime">
            `;
            container.appendChild(row);
        });

        // 3. Add Event Listeners for toggling date input
        container.querySelectorAll(".milk-check").forEach(check => {
            check.addEventListener("change", function() {
                const dt = this.parentElement.querySelector(".milk-datetime");
                dt.style.display = this.checked ? "block" : "none";
            });
        });

        // 4. Show Modal
        document.getElementById("viewModal").style.display = "flex";
    }

    function closeViewModal() {
        document.getElementById("viewModal").style.display = "none";
    }

    // Close on outside click
    window.addEventListener("click", function(e) {
        const modal = document.getElementById("viewModal");
        if (e.target === modal) {
            closeViewModal();
        }
    });
</script>

@endsection