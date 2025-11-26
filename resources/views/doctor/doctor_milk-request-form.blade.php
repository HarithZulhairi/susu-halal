@extends('layouts.doctor')

@section('title', 'New Milk Request')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doctor_request-form.css') }}">

<div class="request-page">
  <div class="form-header">
    <h1>🍼 New Milk Request</h1>
    <p>Create donor milk feeding request for NICU patients {{ auth()->user()->id }}</p>
  </div>

  <form class="milk-request-form" method="POST" action="{{ route('doctor.doctor_milk-request-store') }}">
    @csrf

    <!-- Patient Information -->
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

    <!-- Clinical Information -->
    <section class="form-section">
      <h2>🩺 Clinical Information</h2>
      <div class="grid-2">
        <div class="form-group">
          <label for="weight">Current Weight (kg) <span class="required">*</span></label>
          <input type="text" id="weight" name="weight" placeholder="e.g. 2.5">
        </div>
        <div class="form-group">
        <label id="recommended_label">Calculated Volume per Feeding: <span class="calc-volume">Enter weight to calculate</span></label>
        <input type="number" id="entered_volume" name="entered_volume" placeholder="Enter volume (ml)" min="1">
        </div>
      </div>
      
    </section>

    <!-- Feeding Schedule -->
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
      <input type="number" id="feeds_per_day" name="feeds_per_day" min="1" placeholder="e.g. 6" required>
    </div>

    <div class="form-group">
      <label for="interval_hours">Interval Between Feedings (hours) <span class="required">*</span></label>
      <input type="number" id="interval_hours" name="interval_hours" min="1" placeholder="e.g. 4" required>
    </div>
  </div>
</section>


    <!-- Form Buttons -->
    <div class="form-actions">
      <button type="button" class="btn-cancel">Cancel</button>
      <button type="submit" class="btn-submit">Submit Request</button>
    </div>
  </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.getElementById("feeds_per_day").addEventListener("input", validateFeedingSchedule);
document.getElementById("interval_hours").addEventListener("input", validateFeedingSchedule);

function validateFeedingSchedule() {
    let feeds = parseInt(document.getElementById("feeds_per_day").value);
    let interval = parseFloat(document.getElementById("interval_hours").value);

    if (!isNaN(feeds) && !isNaN(interval)) {
        let totalHours = feeds * interval;

        if (totalHours > 24) {
            alert("⚠️ Total feeding duration exceeds 24 hours. Please adjust feeds per day or interval.");
        }
    }
}

document.getElementById("weight").addEventListener("input", function () {
    let weight = parseFloat(this.value);

    let labelSpan = document.querySelector(".calc-volume");

    if (!isNaN(weight) && weight > 0) {
        let recommended = weight * 150; // Formula: 150 ml per kg
        labelSpan.textContent = recommended + " ml";

        // Set max allowed value for the input
        document.getElementById("entered_volume").setAttribute("max", recommended);
    } else {
        labelSpan.textContent = "Enter weight to calculate";
        document.getElementById("entered_volume").removeAttribute("max");
    }
});

document.querySelector(".milk-request-form").addEventListener("submit", function(e) {
    e.preventDefault();

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

        if (!res.ok) {
            throw data;
        }
        return data;
    })

    .then(data => {
        if (data.success) {

            Swal.fire({
                icon: 'success',
                title: 'Successfully Submitted!',
                text: 'Milk request has been recorded.',
                confirmButtonColor: '#28a745'
            }).then(() => {
                window.location.href = "{{ route('doctor.doctor_milk-request') }}";
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: 'Please check your inputs.'
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: err.message ?? 'Please check all required fields.'
        });
    });
});

</script>


@endsection
