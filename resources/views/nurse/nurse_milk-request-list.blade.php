@extends('layouts.nurse')

@section('title', 'Choose Milk ID')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/nurse_milk-request-list.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <div class="container">
    <div class="main-content">

      <div class="page-header">
        <h1>Choose Milk</h1>
        <p>Milk Request Records</p>
      </div>

      <div class="card">
        <div class="card-header">
          <h2>Milk Request Records</h2>
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
              <th>NICU Cubicle No.</th>
              <th>Date Requested</th>
              <th>Date Time to Give</th>
              <th>Request Status</th>
              <th>Milk Selection</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ([
              ['id' => 'P001', 'name' => 'Sarah Ahmad Binti Fauzi', 'nicu' => 'A101', 'date_requested' => 'Jan 12, 2024', 'date_give' => 'Jan 14, 2024', 'status' => 'Approved'],
              ['id' => 'P002', 'name' => 'Ahmad Jebon Bin Arif', 'nicu' => 'A102', 'date_requested' => 'Jan 12, 2024', 'date_give' => 'Jan 15, 2024', 'status' => 'Waiting']
            ] as $milk)
              <tr>
                <td>
                  <div class="patient-info">
                    <i class="fas fa-bottle-droplet milk-icon"></i>
                    <div>
                      <strong>{{ $milk['id'] }}</strong><br>
                      <span>{{ $milk['name'] }}</span>
                    </div>
                  </div>
                </td>
                <td>{{ $milk['nicu'] }}</td>
                <td>{{ $milk['date_requested'] }}</td>
                <td>{{ $milk['date_give'] }}</td>
                <td>
                  <span class="status {{ strtolower($milk['status']) }}">{{ $milk['status'] }}</span>
                </td>
                <td>
                    <button type="button" class="select-text" onclick="openMilkModal()">SELECT MILK</button>
                </td>
                <td class="actions">
                  <button class="btn-edit" title="Edit"><i class="fas fa-pen"></i></button>
                  <button class="btn-view" title="View"><i class="fas fa-list"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>

<div id="milkModal" class="modal-overlay" style="display: none;">
  <div class="modal-content">
    
    <div class="modal-header">
      <h2>Milk Request Records</h2>
      <button class="modal-close-btn" onclick="closeMilkModal()">Close</button>
    </div>

    <div class="modal-body">
      
      <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
        <div style="background: white; padding: 10px; border-radius: 50%; color: #0ea5e9;">
             <i class="fas fa-user fa-lg"></i>
        </div>
        <div>
            <h3 style="margin: 0; color: #0c4a6e; font-size: 16px;">Patient: P001</h3>
            <span style="font-size: 13px; color: #64748b;">Sarah Ahmad Binti Fauzi</span>
        </div>
      </div>

      <form id="milkAllocationForm">
        
        <div class="modal-section">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label>Medical Record Number</label>
                    <input type="text" class="form-control" value="MRN-2024-001" readonly>
                </div>
                <div>
                    <label>Date of Birth</label>
                    <input type="text" class="form-control" value="2024-01-01" readonly>
                </div>
            </div>
        </div>

        <div class="modal-section">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label>Ward</label>
                    <input type="text" class="form-control" value="NICU - A101" readonly>
                </div>
                 <div>
                    <label>Prescribed Volume (ml)</label>
                    <input type="text" class="form-control" value="43.1 ml" readonly>
                </div>
            </div>
        </div>

        <div class="modal-section">
          <label>Milk Unit ID (Select Multiple)</label>

          <div id="milkList" class="milk-list">
            <div class="milk-item" data-id="MU-2024-001" data-volume="50">
              <strong>MU-2024-001</strong> — 50ml <br> <span style="font-size: 12px; color: #666;">Expires Jan 20, 2024</span>
            </div>
            <div class="milk-item" data-id="MU-2024-002" data-volume="45">
               <strong>MU-2024-002</strong> — 45ml <br> <span style="font-size: 12px; color: #666;">Expires Jan 21, 2024</span>
            </div>
            <div class="milk-item" data-id="MU-2024-003" data-volume="60">
               <strong>MU-2024-003</strong> — 60ml <br> <span style="font-size: 12px; color: #666;">Expires Jan 22, 2024</span>
            </div>
             <div class="milk-item" data-id="MU-2024-004" data-volume="50">
               <strong>MU-2024-004</strong> — 50ml <br> <span style="font-size: 12px; color: #666;">Expires Jan 20, 2024</span>
            </div>
          </div>

          <p class="total-volume-display" style="text-align: right; margin-top: 10px; font-size: 14px;">
            <strong>Total Selected:</strong> <span id="totalVolume" style="color: #2563eb; font-size: 16px;">0</span> ml
          </p>
        </div>

        <div class="modal-section">
          <label>Storage Location</label>
          <input type="text" class="form-control" id="storageLocation" value="NICU Storage Room A" readonly>
        </div>

        <button type="submit" class="modal-close-btn">ALLOCATE MILK UNIT</button>
      </form>
    </div>
  </div>
</div>

<script>

let selectedMilkUnits = [];

function updateTotalVolume() {
  let total = selectedMilkUnits.reduce((sum, item) => sum + parseFloat(item.volume), 0);
  document.getElementById("totalVolume").textContent = total;
}

document.querySelectorAll(".milk-item").forEach(item => {
  item.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const volume = this.getAttribute("data-volume");

      // Check if already selected
      const index = selectedMilkUnits.findIndex(m => m.id === id);

      if (index === -1) {
          // Add to selection
          selectedMilkUnits.push({ id, volume });
          this.classList.add("selected");
      } else {
          // Remove from selection
          selectedMilkUnits.splice(index, 1);
          this.classList.remove("selected");
      }

      updateTotalVolume();
  });
});

function openMilkModal() {
    document.getElementById('milkModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeMilkModal() {
    document.getElementById('milkModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close on overlay click
window.addEventListener('click', function(e) {
    const modal = document.getElementById('milkModal');
    if (e.target === modal) {
        closeMilkModal();
    }
});

// Handle form submission
document.getElementById('milkAllocationForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  if (selectedMilkUnits.length === 0) {
    alert('Please select at least one Milk Unit.');
    return;
  }
  console.log("Selected Milk Units:", selectedMilkUnits);
  alert('Milk units allocated successfully!');
  closeMilkModal();
});
</script>

@endsection