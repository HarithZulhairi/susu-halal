@extends('layouts.doctor')

@section('title', 'New Milk Request')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_request-form.css') }}"> 

<div class="request-page">
  <div class="form-header">
    <h1>🍼 New Milk Request</h1>
    <p>Create donor milk feeding request for NICU patients</p>
  </div>

  <form class="milk-request-form" method="POST" action="{{ route('doctor.doctor_milk-request-store') }}">
    @csrf

    <section class="form-section">
      <h2>👶 Patient Information</h2>
      <div class="form-group">
        <label for="patient_id">Select Patient <span class="required">*</span></label>
        {{-- ADDED ID for JS targeting --}}
        <select id="patient_id" name="pr_ID" required>
          <option value="">Select...</option>
          @foreach($parents as $parent)
            {{-- ADDED data-dob attribute here --}}
            <option value="{{ $parent->pr_ID }}" data-dob="{{ $parent->pr_BabyDOB }}">
              {{ $parent->formattedID }} - {{ $parent->pr_BabyName }}
            </option>
          @endforeach
        </select>
      </div>
    </section>

    <section class="form-section">
      <h2>🩺 Clinical Information</h2>
      
      <div class="grid-2">
        {{-- Baby Weight --}}
        <div class="form-group">
          <label for="weight">Current Weight (kg) <span class="required">*</span></label>
          <input type="text" id="weight" name="weight" placeholder="e.g. 2.5" required>
        </div>

        {{-- Volume Calculation --}}
        <div class="form-group">
          <label id="recommended_label">Total Daily Volume (Calculated): <span class="calc-volume">Enter weight to calculate</span></label>
          <input type="number" id="entered_volume" name="entered_volume" placeholder="Enter total volume (ml)" min="1" required>
        </div>
      </div>

      <div class="grid-2">
        {{-- Baby Age Input (Auto-calculated) --}}
        <div class="form-group">
            <label>Current Baby Age (Auto-calculated)</label>
            <input type="text" id="baby_age" name="baby_age" placeholder="Select patient..."  style="background-color: #f3f4f6; font-weight: bold; color: #1A5F7A;" readonly required>
        </div>

        {{-- Gestational Age --}}
        <div class="form-group">
            <label>Gestational Age at Birth (Weeks)</label>
            <input type="number" name="gestational_age" placeholder="e.g. 32" min="20" max="42">
        </div>
      </div>
    </section>
    

    <section class="form-section">
        <h2>💧 Milk Dispensing Method</h2>
        
        <div class="form-group">
            <label>Establish Milk Kinship (Mahram)? <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-box">
                    <input type="radio" name="kinship_method" value="yes" onchange="calculateDispensing()">
                    <div class="radio-content">
                        <strong><i class="fas fa-users"></i> Involves Milk Kinship</strong>
                        <span>Full Nursing (Mahram established)</span>
                    </div>
                </label>

                <label class="radio-box">
                    <input type="radio" name="kinship_method" value="no" onchange="calculateDispensing()" checked>
                    <div class="radio-content">
                        <strong><i class="fas fa-ban"></i> No Milk Kinship</strong>
                        <span>Restricted Drip / Direct Method</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="feeding_tube">Drip Feeding Method</label>
                <select id="feeding_tube" name="feeding_tube" class="form-control">
                    <option value="">-- Select Drip Method --</option>
                    <option value="Orogastric">Orogastric</option>
                    <option value="Nasogastric">Nasogastric</option>
                    <option value="Orojenhunal">Orojenhunal</option>
                    <option value="Nasojejunal">Nasojejunal</option>
                    <option value="Gastrostomy">Gastrostomy</option>
                </select>
            </div>

            <div class="form-group">
                <label for="oral_feeding">Direct Feeding Method</label>
                <select id="oral_feeding" name="oral_feeding" class="form-control">
                    <option value="">-- Select Direct Method --</option>
                    <option value="Cup">Cup</option>
                    <option value="Syringe">Syringe</option>
                    <option value="Bottle">Bottle</option>
                    <option value="Breastfeeding">Breastfeeding</option>
                    <option value="Tube">Tube</option>
                </select>
            </div>
        </div>

        <div class="calculation-result" id="calculation-result" style="display:none;">
            <h3><i class="fas fa-calculator"></i> Feeding Plan Calculation</h3>
            
            <div id="kinship-yes-result" style="display:none;">
                <p><strong>Method:</strong> Full Nursing (5+ Satisfying Feeds)</p>
                <div class="result-row">
                    <span>Volume per Feed (every 2 hours):</span>
                    <strong class="highlight-value" id="vol-per-feed-yes">0 ml</strong>
                </div>
                <small class="text-muted">Total Volume / 12 feeds</small>
            </div>

            <div id="kinship-no-result" style="display:none;">
                <p><strong>Method:</strong> Drip Method (Preventing Kinship)</p>
                
                <div class="mode-selector" style="margin-bottom: 15px; display: flex; gap: 10px;">
                    <label>
                        <input type="radio" name="calc_mode" value="auto" checked onchange="calculateDispensing()"> 
                        Standard (80/20 Split)
                    </label>
                    <label>
                        <input type="radio" name="calc_mode" value="manual" onchange="calculateDispensing()"> 
                        Manual Adjustment
                    </label>
                </div>

                <div class="result-grid">
                    <div class="result-item">
                        <span>Drip Feeding:</span>
                        <strong class="highlight-value" id="vol-drip-display">0 ml</strong>
                        <input type="number" id="manual_drip" class="manual-calc-input" style="display:none;" step="0.1">
                        <small>(Bulk Volume)</small>
                    </div>
                    <div class="result-item">
                        <span>Direct Feeding (every 2h):</span>
                        <strong class="highlight-value" id="vol-oral-display">0 ml</strong>
                        <input type="number" id="manual_oral" class="manual-calc-input" style="display:none;" step="0.1">
                        <small>(Restricted Amount)</small>
                    </div>
                </div>
                <p class="calc-explanation" id="calc-explanation"></p>
            </div>
        </div>

        {{-- Calculated values (submitted to backend) --}}
        <input type="hidden" name="volume_per_feed" id="volume_per_feed">
        <input type="hidden" name="drip_total" id="drip_total">
        <input type="hidden" name="oral_total" id="oral_total">
        <input type="hidden" name="oral_per_feed" id="oral_per_feed">



    </section>

    <section class="form-section">
      <h2>🗓️ Feeding Schedule</h2>

      <div class="grid-2">
        <div class="form-group">
          <label for="feeding_date">Feeding Start Date</label>
          <input type="date" id="feeding_date" name="feeding_date">
        </div>

        <div class="form-group">
          <label for="start_time">Start Time <span class="required">*</span></label>
          <input type="time" id="start_time" name="start_time" required>
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label for="feeds_per_day">Number of Feedings per Day <span class="required">*</span></label>
          <input type="number" id="feeds_per_day" name="feeds_per_day" min="1" value="12" readonly style="background:#f9fafb;">
        </div>

        <div class="form-group">
          <label for="interval_hours">Interval Between Feedings (hours) <span class="required">*</span></label>
          <input type="number" id="interval_hours" name="interval_hours" min="1" value="2" readonly style="background:#f9fafb;">
        </div>
      </div>
    </section>

    <div class="form-actions">
      <button type="button" class="btn-cancel">Cancel</button>
      <button type="submit" class="btn-submit">Submit Request</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- Volume Calculation Logic ---
    document.getElementById("weight").addEventListener("input", function () {
        let weight = parseFloat(this.value);
        let labelSpan = document.querySelector(".calc-volume");

        if (!isNaN(weight) && weight > 0) {
            let totalDaily = Math.round(weight * 150); // Formula: 150ml per kg per day
            labelSpan.textContent = `~${totalDaily} ml/day`; 
            
            calculateDispensing(); // Recalculate if weight changes
        } else {
            labelSpan.textContent = "Enter weight to calculate";
        }
    });

    document.getElementById("entered_volume").addEventListener("input", calculateDispensing);
    
    const radios = document.querySelectorAll('input[name="kinship_method"]');
    radios.forEach(radio => radio.addEventListener('change', calculateDispensing));

    // Add event listeners for manual input typing
        document.getElementById("manual_drip").addEventListener("input", function() { syncManual('drip'); });
        document.getElementById("manual_oral").addEventListener("input", function() { syncManual('oral'); });

        function calculateDispensing() {
            const totalVolume = parseFloat(document.getElementById("entered_volume").value);
            const kinship = document.querySelector('input[name="kinship_method"]:checked')?.value;
            const mode = document.querySelector('input[name="calc_mode"]:checked')?.value;
            
            const resultBox = document.getElementById("calculation-result");
            const resYes = document.getElementById("kinship-yes-result");
            const resNo = document.getElementById("kinship-no-result");

            if (isNaN(totalVolume) || totalVolume <= 0) {
                resultBox.style.display = "none";
                return;
            }

            resultBox.style.display = "block";

            if (kinship === 'yes') {
                resYes.style.display = "block";
                resNo.style.display = "none";
                const perFeed = (totalVolume / 12).toFixed(2);
                document.getElementById("vol-per-feed-yes").textContent = `${perFeed} ml`;
                updateHiddenInputs(0, 0, perFeed, perFeed);
            } else {
                resYes.style.display = "none";
                resNo.style.display = "block";
                
                const dripDisplay = document.getElementById("vol-drip-display");
                const oralDisplay = document.getElementById("vol-oral-display");
                const dripInput = document.getElementById("manual_drip");
                const oralInput = document.getElementById("manual_oral");

                if (mode === 'auto') {
                    // Standard 80/20 Logic
                    dripDisplay.style.display = "block";
                    oralDisplay.style.display = "block";
                    dripInput.style.display = "none";
                    oralInput.style.display = "none";

                    const dripTotal = (totalVolume * 0.8).toFixed(2);
                    const oralTotal = (totalVolume * 0.2).toFixed(2);
                    const oralPerFeed = (oralTotal / 12).toFixed(2);

                    dripDisplay.textContent = `${dripTotal} ml`;
                    oralDisplay.textContent = `${oralPerFeed} ml`;
                    
                    document.getElementById("calc-explanation").innerHTML = `<strong>Auto Mode:</strong> 80% Drip (${dripTotal}ml) and 20% Direct split into 12 feeds.`;
                    updateHiddenInputs(dripTotal, oralTotal, oralPerFeed, oralPerFeed);
                } else {
                    // Manual Mode Logic
                    dripDisplay.style.display = "none";
                    oralDisplay.style.display = "none";
                    dripInput.style.display = "block";
                    oralInput.style.display = "block";

                    // Initialize manual inputs if empty
                    if (!dripInput.value) {
                        dripInput.value = (totalVolume * 0.8).toFixed(2);
                        oralInput.value = (totalVolume * 0.2).toFixed(2);
                    }
                    syncManual('init'); 
                }
            }
        }

        function syncManual(source) {
            const total = parseFloat(document.getElementById("entered_volume").value);
            let d = document.getElementById("manual_drip");
            let o = document.getElementById("manual_oral");

            if (source === 'drip') {
                o.value = (total - (parseFloat(d.value) || 0)).toFixed(2);
            } else if (source === 'oral') {
                d.value = (total - (parseFloat(o.value) || 0)).toFixed(2);
            }

            const oralPerFeed = (parseFloat(o.value) / 12).toFixed(2);
            document.getElementById("calc-explanation").innerHTML = `<strong>Manual Mode:</strong> Direct ${o.value}ml ÷ 12 feeds = <strong>${oralPerFeed} ml/feed</strong>`;
            updateHiddenInputs(d.value, o.value, oralPerFeed, oralPerFeed);
        }

        function updateHiddenInputs(drip, oral, oralFeed, volFeed) {
            document.getElementById("drip_total").value = drip;
            document.getElementById("oral_total").value = oral;
            document.getElementById("oral_per_feed").value = oralFeed;
            document.getElementById("volume_per_feed").value = volFeed;
        }

    // --- Consent Simulation Logic ---
    document.getElementById('patient_id').addEventListener('change', function() {
        const display = document.getElementById('consent-status-display');
        
        if(this.value) {
            display.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying Kinship...';
            display.className = 'consent-status pending';
            
            setTimeout(() => {
                display.innerHTML = '<i class="fas fa-check-double"></i> Kinship Verified & Consented';
                display.className = 'consent-status verified';
                document.getElementById('parent-consent-val').innerHTML = '<i class="fas fa-check-circle"></i> Approved';
                document.getElementById('donor-consent-val').innerHTML = '<i class="fas fa-check-circle"></i> Approved';
            }, 800);
        } else {
            display.innerHTML = 'Select a patient to verify consent.';
            display.className = 'consent-status';
            document.getElementById('parent-consent-val').textContent = '-';
            document.getElementById('donor-consent-val').textContent = '-';
        }
    });

    // Run once on load to set initial state
    document.addEventListener("DOMContentLoaded", function() {
        calculateDispensing();
    });


    document.getElementById('patient_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const dobString = selectedOption.getAttribute('data-dob'); 
        const ageInput = document.getElementById('baby_age');

        if (dobString) {
            const birthDate = new Date(dobString);
            const today = new Date();

            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();
            let days = today.getDate() - birthDate.getDate();

            // Adjust if days are negative
            if (days < 0) {
                months--;
                // Get last day of previous month
                const lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                days += lastMonth.getDate();
            }

            // Adjust if months are negative
            if (months < 0) {
                years--;
                months += 12;
            }

            // Create the string: "X years X months X days"
            const ageResult = `${years} years ${months} months ${days} days`;
            ageInput.value = ageResult;
        } else {
            ageInput.value = '';
        }
    });

    // --- Form Submission ---
    document.querySelector(".milk-request-form").addEventListener("submit", function(e) {
        e.preventDefault();
        
        const totalVolume = document.getElementById("entered_volume").value;
        if(!totalVolume) {
             Swal.fire({ icon: 'error', title: 'Missing Info', text: 'Please enter total milk volume.' });
             return;
        }

        let form = this;
        let formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(async res => {
            let data = await res.json();
            if (!res.ok) throw data;
            return data;
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Submitted!',
                    text: 'Milk request recorded successfully.',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location.href = "{{ route('doctor.doctor_milk-request') }}";
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: 'Please check your inputs.' });
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message ?? 'Submission failed.'
            });
        });
    });
</script>
@endsection