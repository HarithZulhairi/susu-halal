@extends('layouts.nurse')

@section('title', 'Infant Weight Setting')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/nurse_set-infant-weight.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .swal2-container {
            z-index: 9999 !important;
        }
        .modal-overlay {
            z-index: 2000;
        }
    </style>

    <div class="container">
        <div class="main-content">
            <div class="page-header">
                <h1>Infant Weight</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>List of Infants</h2>
                    <div class="actions">
                        <button class="btn"><i class="fas fa-search"></i> Search</button>
                        <button class="btn"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>

                <table class="infants-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>NICU Cubicle No.</th>
                            <th>Last Updated</th>
                            <th>Current Weight</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parents as $parent)
                            <tr>
                                <td>
                                    <div class="patient-info">
                                        <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                        <div class="patient-details">
                                            <strong>{{ $parent->formatted_id }}</strong>
                                            <span>{{ $parent->pr_BabyName }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $parent->pr_NICU }}</td>
                                <td>{{ $parent->updated_at?->format('d-m-Y h:i A') }}</td>
                                <td>
                                    <div class="weight-display">
                                        <i class="fa-solid fa-weight-scale"></i>
                                        <span>{{ $parent->pr_BabyCurrentWeight }} kg</span>
                                    </div>
                                </td>
                                <td class="actions">
                                    <button class="btn-view" title="View" 
                                        onclick="openViewModal('{{ $parent->formatted_id }}', '{{ $parent->pr_BabyName }}', '{{ $parent->pr_NICU }}', '{{ $parent->updated_at?->format('d-m-Y h:i A') }}', '{{ $parent->pr_BabyCurrentWeight }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" title="Update Weight" onclick="openWeightModal('{{ $parent->pr_ID }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="viewInfantModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Infant Information</h2>
                <button class="modal-close-btn" onclick="closeViewModal()">Close</button>
            </div>
            <div class="modal-body">
                <p><strong>Patient ID:</strong> <span id="viewPatientId">-</span></p>
                <p><strong>Name:</strong> <span id="viewPatientName">-</span></p>
                <p><strong>NICU Cubicle:</strong> <span id="viewCubicle">-</span></p>
                <hr>
                <p><strong>Last Updated:</strong> <span id="viewLastUpdated">-</span></p>
                <p><strong>Current Weight:</strong> <span id="viewWeight">-</span></p>
            </div>
        </div>
    </div>

    <div id="weightModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            
            <div class="modal-header">
                <h2>Daily Weight Entry</h2>
                <button class="modal-close-btn" onclick="closeWeightModal()">Close</button>
            </div>

            <div class="modal-body">
                <form id="weightEntryForm">
                @csrf
                <input type="hidden" id="parentId" name="pr_ID">    
                    <div class="modal-section">
                        <label>Patient ID <span class="text-danger">*</span></label>
                        <input type="text" id="PatientId" name="PatientId" class="form-control"  readonly>
                    </div>

                    <div id="patientInfoContainer" style="display: none;">
                         <h3><i class="fas fa-info-circle"></i> Patient Details</h3>
                         <p><strong>Name:</strong> <span id="displayPatientName"></span></p>
                         <p><strong>MRN:</strong> <span id="displayMRN"></span></p>
                         <p><strong>Diagnosis:</strong> <span id="displayDiagnosis"></span></p>
                         <p><strong>Cubicle:</strong> <span id="displayCubicle"></span></p>
                    </div>

                    <div class="modal-section">
                        <label>Enter Today's Weight (kg) <span class="text-danger">*</span></label>
                        <input type="number" id="pr_BabyCurrentWeight" name="pr_BabyCurrentWeight" class="form-control" placeholder="e.g., 2.45" step="0.01" required>
                    </div>

                    <button type="submit" class="modal-close-btn">Save Record</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- VIEW MODAL FUNCTIONS ---
        function openViewModal(id, name, nicu, updated, weight) {
            document.getElementById('viewPatientId').textContent = id;
            document.getElementById('viewPatientName').textContent = name;
            document.getElementById('viewCubicle').textContent = nicu;
            document.getElementById('viewLastUpdated').textContent = updated;
            document.getElementById('viewWeight').textContent = weight + " kg";

            const modal = document.getElementById('viewInfantModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeViewModal() {
            document.getElementById('viewInfantModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // --- WEIGHT ENTRY MODAL FUNCTIONS ---
        function openWeightModal(id) {
            const modal = document.getElementById('weightModal');
            document.getElementById('PatientId').value = "#P" + id;
            document.getElementById('parentId').value = id;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';

            if(preSelectId) {
                const select = document.getElementById('patientSelect');
                select.value = preSelectId;
                updatePatientInfo(); // Trigger info update
            }
        }

        function closeWeightModal() {
            document.getElementById('weightModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Reset Form
            document.getElementById('weightEntryForm').reset();
            document.getElementById('patientInfoContainer').style.display = 'none';
        }

        function updatePatientInfo() {
            const select = document.getElementById('patientSelect');
            const container = document.getElementById('patientInfoContainer');
            
            if (select.value) {
                const option = select.options[select.selectedIndex];
                document.getElementById('displayPatientName').textContent = option.dataset.name;
                document.getElementById('displayMRN').textContent = option.dataset.mrn;
                document.getElementById('displayDiagnosis').textContent = option.dataset.diagnosis;
                document.getElementById('displayCubicle').textContent = option.dataset.cubicle;
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        // Close modals on outside click
        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Submit weight update - NOW WORKS!
        document.getElementById('weightEntryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const prId = document.getElementById('parentId').value;
            const weight = document.getElementById('pr_BabyCurrentWeight').value;

            fetch("{{ route('nurse.nurse_infant-weight.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pr_ID: prId,
                    pr_BabyCurrentWeight: weight
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Weight updated successfully!',
                        timer: 1800,
                        showConfirmButton: false
                    });
                    closeWeightModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(res.message || 'Update failed');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Invalid weight. Please try again.', 'error');
            });
        });
    </script>

@endsection