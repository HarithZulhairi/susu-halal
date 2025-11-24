@extends('layouts.donor')

@section('title', 'Pumping Kit Form')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/donor_pumping-kit-form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container">

    <div class="main-content">
    <div class="page-header">
        <h1>Pumping Kit Request Appointment Form</h1>
        <p>Please provide the following details to book your pumping kit at the hospital.</p>
    </div>

    <div class="form-container">
        <div class="form-card">
            <h2>Book Your Pumping Kit</h2>
            
            <form action="{{ route('donor.confirm-pumping-kit-form') }}" method="GET">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="kit_type">Type of Kit Needed</label>
                        <select id="kit_type" name="kit_type" required>
                            <option value="">Select kit type</option>
                            <option value="manual_kit">Manual Pumping Kit</option>
                            <option value="automatic_kit">Automatic Pumping Kit</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="preferred_datetime">Preferred Date & Time</label>
                        <input type="datetime-local" id="preferred_datetime" name="preferred_datetime" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Preferred Location</label>
                    <select id="location" name="location" required>
                        <option value="">Select location</option>
                            <option value="main_foyer">Main Foyer</option>
                        <option value="front_counter">Front Counter</option>
                        <option value="reception">Reception Area</option>
                        <option value="milk_bank">Milk Bank Office</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="reason">Reason for Booking</label>
                    <textarea id="reason" name="reason" class="form-control" rows="5" placeholder="Optional..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                    <button type="button" class="btn-submit" onclick="storeFormData()">Continue</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<script>



    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("preferred_datetime");
        
        const updateMinTime = () => {
            const now = new Date();
            const minTime = new Date(now.getTime() + 5 * 60 * 60 * 1000);
            input.min = minTime.toISOString().slice(0, 16);
        };

        updateMinTime();

        input.addEventListener("input", function() {
            const selected = new Date(this.value);
            const now = new Date();
            const minTime = new Date(now.getTime() + 5 * 60 * 60 * 1000);

            if (selected < minTime) {
                alert("Appointment must be at least 5 hours from now.");
                this.value = "";
            }
        });
    });


    document.addEventListener('DOMContentLoaded', (event) => {
        restoreFormData();// Run on page load to set initial state

    });

    function restoreFormData() {
    const stored = sessionStorage.getItem('pumpingKitAppointment');
    if (!stored) return;

    const data = JSON.parse(stored);

    document.getElementById('kit_type').value = data.kit_type;
    // Datetime
    document.getElementById('preferred_datetime').value = data.appointment_datetime;

    document.getElementById('location').value = data.location;

    document.getElementById('reason').value = data.reason;

}


    function storeFormData() {

    const kitType = document.getElementById('kit_type').value;
    const appointmentTime = document.getElementById('preferred_datetime').value;
    const location = document.getElementById('location').value;
    const reason = document.getElementById('reason').value;

    // VALIDATION CHECKS
    let errors = [];

    // Appointment time required
    if (!kitType) {
        errors.push("Please select a pumping kit type.");
    }

    // Appointment time required
    if (!appointmentTime) {
        errors.push("Please select an appointment date & time.");
    }


    //If there are errors, show alert and STOP redirecting
    if (errors.length > 0) {
        alert(errors.join("\n")); // You can replace alert() with own popup later
        return; // Stop execution
    }


    const data = {
        kit_type: kitType,
        appointment_datetime: appointmentTime,
        location,
        reason
    };
   
    // Use sessionStorage so it only lasts for this session
    sessionStorage.setItem('pumpingKitAppointment', JSON.stringify(data));

    // Redirect to confirmation page
    window.location.href = "{{ route('donor.confirm-pumping-kit-form') }}";
    }
</script>
@endsection