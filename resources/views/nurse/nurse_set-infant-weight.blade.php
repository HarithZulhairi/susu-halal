@extends('layouts.nurse')

@section('title', 'Infant Weight Setting')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/nurse_set-infant-weight.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                        <button class="btn"><i class="fas fa-ellipsis-h"></i></button>
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
                        @foreach ([
                            ['id' => 'P001', 'name' => 'Sarah Ahmad Binti Fauzi', 'nicu' => 'A101', 'last_updated' => 'Jan 12, 2024', 'weight' => '2.0'],
                            ['id' => 'P002', 'name' => 'Ahmad Jebon Bin Arif', 'nicu' => 'A102', 'last_updated' => 'Jan 12, 2024', 'weight' => '4.5']
                        ] as $infant)
                            <tr>
                                <td>
                                    <div class="patient-info">
                                        <div class="patient-avatar"><i class="fa-solid fa-baby"></i></div>
                                        <div class="patient-details">
                                            <strong>{{ $infant['id'] }}</strong>
                                            <span>{{ $infant['name'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $infant['nicu'] }}</td>
                                <td>{{ $infant['last_updated'] }}</td>
                                <td>
                                    <div class="weight-display">
                                        <i class="fa-solid fa-weight-scale"></i>
                                        <span>{{ $infant['weight'] }} kg</span>
                                    </div>
                                </td>
                                <td class="actions">
                                    <button class="btn-view" title="View" 
                                        onclick="openViewModal('{{ $infant['id'] }}', '{{ $infant['name'] }}', '{{ $infant['nicu'] }}', '{{ $infant['last_updated'] }}', '{{ $infant['weight'] }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" title="Update Weight" onclick="openWeightModal('{{ $infant['id'] }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn-more" title="More"><i class="fa-solid fa-ellipsis-vertical"></i></button>
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
                    
                    <div class="modal-section">
                        <label>Select Patient ID <span class="text-danger">*</span></label>
                        <select id="patientSelect" class="form-select" required onchange="updatePatientInfo()">
                            <option value="">Select a patient...</option>
                            <option value="P001" data-name="Sarah Ahmad Binti Fauzi" data-mrn="MRN-2024-001" data-dob="2024-01-01" data-diagnosis="Premature Birth" data-cubicle="A101">P001 - Sarah Ahmad Binti Fauzi</option>
                            <option value="P002" data-name="Ahmad Jebon Bin Arif" data-mrn="MRN-2024-002" data-dob="2024-01-05" data-diagnosis="Low Birth Weight" data-cubicle="A102">P002 - Ahmad Jebon Bin Arif</option>
                        </select>
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
                        <input type="number" id="weightInput" class="form-control" placeholder="e.g., 2.45" step="0.01" min="0" required>
                    </div>

                    <button type="submit" class="modal-close-btn">Save Record</button>
                </form>
            </div>
        </div>
    </div>

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
        function openWeightModal(preSelectId = null) {
            const modal = document.getElementById('weightModal');
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

        // Handle Form Submit
        document.getElementById('weightEntryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Weight recorded successfully!');
            closeWeightModal();
        });
    </script>
@endsection