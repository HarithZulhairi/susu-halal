<!-- resources/views/doctor/milk-records.blade.php -->
@extends('layouts.doctor')

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
            <h3>Milk Processing and Records</h3>
          </div>
          
          <div class="header-right">
            <button class="btn-add">
              <i class="fas fa-plus"></i> Add Request
            </button>
            <input type="text" class="search-input" placeholder="🔍 Search records...">
          </div>
        </div>

        <table class="records-table">
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>NICU</th>
              <th>Date Requested</th>
              <th>Date Time to Give</th>
              <th>Request Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody> 
            <tr>
              <td><strong>P001</strong><br>Sarah Ahmad Binti Fauzi</td>
              <td>A101</td>
              <td>Jan 12, 2024</td>
              <td>Jan 14, 2024 • 08:30 AM</td>
              <td><span class="status approved">Approved</span></td>
              <td class="actions">
                <button class="btn-view" title="View" 
                  onclick="openMilkRequestModal({
                    patientId: 'P001',
                    patientName: 'Sarah Ahmad Binti Fauzi',
                    nicu: 'A101',
                    dateRequested: 'Jan 12, 2024',
                    dateTimeToGive: 'Jan 14, 2024 • 08:30 AM',
                    status: 'Approved',
                    requestedVolume: '180ml',
                    doctorName: 'Dr. Ahmad Hassan',
                    notes: 'Patient requires urgent feeding support',
                    allergyInfo: 'None',
                    weight: '2.5 kg'
                  })">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
              </td>
            </tr>
            <tr>
              <td><strong>P002</strong><br>Ahmad Jebon Bin Arif</td>
              <td>A102</td>
              <td>Jan 12, 2024</td>
              <td>Jan 15, 2024 • 09:45 AM</td>
              <td><span class="status waiting">Waiting</span></td>
              <td class="actions">
                <button class="btn-view" title="View"
                  onclick="openMilkRequestModal({
                    patientId: 'P002',
                    patientName: 'Ahmad Jebon Bin Arif',
                    nicu: 'A102',
                    dateRequested: 'Jan 12, 2024',
                    dateTimeToGive: 'Jan 15, 2024 • 09:45 AM',
                    status: 'Waiting',
                    requestedVolume: '150ml',
                    doctorName: 'Dr. Siti Nurhaliza',
                    notes: 'Monitor feeding tolerance',
                    allergyInfo: 'Lactose sensitivity',
                    weight: '2.2 kg'
                  })">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
              </td>
            </tr>
            <tr>
              <td><strong>P003</strong><br>Sarah Aiman Bin Yusof</td>
              <td>A103</td>
              <td>Jan 12, 2024</td>
              <td>Jan 16, 2024 • 02:00 PM</td>
              <td><span class="status rejected">Rejected</span></td>
              <td class="actions">
                <button class="btn-view" title="View"
                  onclick="openMilkRequestModal({
                    patientId: 'P003',
                    patientName: 'Sarah Aiman Bin Yusof',
                    nicu: 'A103',
                    dateRequested: 'Jan 12, 2024',
                    dateTimeToGive: 'Jan 16, 2024 • 02:00 PM',
                    status: 'Rejected',
                    requestedVolume: '200ml',
                    doctorName: 'Dr. Rahman Abdullah',
                    notes: 'Request rejected due to insufficient stock',
                    allergyInfo: 'None',
                    weight: '2.8 kg'
                  })">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
              </td>
            </tr>
            <tr>
              <td><strong>P004</strong><br>Nurul Aisyah Binti Hassan</td>
              <td>A104</td>
              <td>Jan 12, 2024</td>
              <td>Jan 16, 2024 • 03:30 PM</td>
              <td><span class="status allocated">Allocated</span></td>
              <td class="actions">
                <button class="btn-view" title="View"
                  onclick="openMilkRequestModal({
                    patientId: 'P004',
                    patientName: 'Nurul Aisyah Binti Hassan',
                    nicu: 'A104',
                    dateRequested: 'Jan 12, 2024',
                    dateTimeToGive: 'Jan 16, 2024 • 03:30 PM',
                    status: 'Allocated',
                    requestedVolume: '220ml',
                    doctorName: 'Dr. Fatimah Zahra',
                    notes: 'Milk allocated and ready for feeding',
                    allergyInfo: 'None',
                    weight: '3.1 kg'
                  })">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
              </td>
            </tr>
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
  </script>

@endsection