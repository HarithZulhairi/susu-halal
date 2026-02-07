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
        <select id="patient_id" name="pr_ID" required>
          <option value="">Select...</option>
          @foreach($parents as $parent)
            <option value="{{ $parent->pr_ID }}">
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
        {{-- Baby Age Input --}}
        <div class="form-group">
            <label>Baby Age <span class="required">*</span></label>
            <div class="age-input-group">
                <input type="number" name="baby_age" placeholder="e.g. 5" min="0" required style="flex: 2;">
                <select name="age_unit" style="flex: 1;">
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                </select>
            </div>
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
                        <span>Restricted Feed / Drip Method</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="feeding_tube">Feeding Tube Method</label>
                <select id="feeding_tube" name="feeding_tube" class="form-control">
                    <option value="">-- Select Tube Method --</option>
                    <option value="orogastric">Orogastric</option>
                    <option value="nasogastric">Nasogastric</option>
                    <option value="orojenhunal">Orojenhunal</option>
                    <option value="nasojejunal">Nasojejunal</option>
                    <option value="gastrostomy">Gastrostomy</option>
                </select>
            </div>

            <div class="form-group">
                <label for="oral_feeding">Oral Feeding Method</label>
                <select id="oral_feeding" name="oral_feeding" class="form-control">
                    <option value="">-- Select Oral Method --</option>
                    <option value="cup">Cup</option>
                    <option value="syringe">Syringe</option>
                    <option value="bottle">Bottle</option>
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
                <div class="result-grid">
                    <div class="result-item">
                        <span>Drip / Tube Feeding:</span>
                        <strong class="highlight-value" id="vol-drip">0 ml</strong>
                        <small>(Bulk Volume)</small>
                    </div>
                    <div class="result-item">
                        <span>Direct Oral Feed (every 2h):</span>
                        <strong class="highlight-value" id="vol-oral">0 ml</strong>
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

    function calculateDispensing() {
        const totalVolume = parseFloat(document.getElementById("entered_volume").value);
        const method = document.querySelector('input[name="kinship_method"]:checked').value;

        const resultBox = document.getElementById("calculation-result");
        const resYes = document.getElementById("kinship-yes-result");
        const resNo  = document.getElementById("kinship-no-result");

        // Hidden inputs
        const volumePerFeedInput = document.getElementById("volume_per_feed");
        const dripTotalInput     = document.getElementById("drip_total");
        const oralTotalInput     = document.getElementById("oral_total");
        const oralPerFeedInput   = document.getElementById("oral_per_feed");

        if (isNaN(totalVolume) || totalVolume <= 0) {
            resultBox.style.display = "none";
            return;
        }

        resultBox.style.display = "block";

        // === Common base calculations ===
        const perFeedKinship = (totalVolume / 12).toFixed(2);
        const dripTotal     = (totalVolume * 0.8).toFixed(2);
        const oralTotal     = (totalVolume * 0.2).toFixed(2);
        const oralPerFeed   = (oralTotal / 12).toFixed(2);

        if (method === 'yes') {
            /**
             * ✅ KINSHIP INVOLVED
             */
            resYes.style.display = "block";
            resNo.style.display  = "none";

            // UI
            document.getElementById("vol-per-feed-yes").textContent = `${perFeedKinship} ml`;

        } else {
            /**
             * ❌ NO KINSHIP
             */
            resYes.style.display = "none";
            resNo.style.display  = "block";

            // UI
            document.getElementById("vol-drip").textContent = `${dripTotal} ml`;
            document.getElementById("vol-oral").textContent = `${oralPerFeed} ml`;

            document.getElementById("calc-explanation").innerHTML =
                `<strong>Calculation:</strong>
                Total ${totalVolume}ml → ${dripTotal}ml (Drip) + ${oralTotal}ml (Oral)<br>
                Oral ${oralTotal}ml ÷ 12 feeds = <strong>${oralPerFeed} ml/feed</strong>`;
        }

        // 🔐 STORE EVERYTHING (ALWAYS)
        volumePerFeedInput.value = perFeedKinship;
        dripTotalInput.value     = dripTotal;
        oralTotalInput.value     = oralTotal;
        oralPerFeedInput.value   = oralPerFeed;
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