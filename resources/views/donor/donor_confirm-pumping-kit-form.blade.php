@extends('layouts.donor')

@section('title', 'Confirm Pumping Kit')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/donor_confirm-pumping-kit-form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container">

    <div class="main-content">
        <div class="page-header">
            <h1>Pumping Kit Request Appointment Form</h1>
        </div>

        <div class="confirmation-container">

            <div class="confirmation-header">
                <h2>Confirm Your Appointment Details</h2>

                <div class="instruction-box">
                    <p>Please review your appointment details carefully before submitting.</p>
                    <p>Our milk bank team will contact you shortly to confirm your schedule and provide further instructions.</p>
                </div>
            </div>

            <form id="confirmAppointmentForm" method="POST" action="{{ route('donor.store-pk-appointment') }}">
                @csrf

                <div class="details-grid">
                    
                    <!-- Column 1 -->
                    <div class="details-column">
                        <h3>Pumping Kit Details</h3>

                        <div class="detail-item">
                            <label>Pumping Kit Type</label>
                            <div class="detail-value" id="confirm_type"></div>
                            <input type="hidden" name="kit_type" id="kit_type">
                        </div>

                        <div class="detail-item" id="location_block" style="display:none;">
                            <label>Delivery Location</label>
                            <div class="detail-value" id="confirm_location"></div>
                            <input type="hidden" name="location" id="location">
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="details-column">
                        <h3>Schedule Details</h3>

                        <div class="detail-item">
                            <label>Preferred Date & Time</label>
                            <div class="detail-value" id="confirm_datetime"></div>
                            <input type="hidden" name="appointment_datetime" id="appointment_datetime">
                        </div>

                        <div class="detail-item full-width">
                            <label>Reason for Booking</label>
                            <div class="detail-value remarks-box" id="confirm_reason"></div>
                            <input type="hidden" name="reason" id="reason">
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="form-actions">
                    <button type="button" class="btn-back" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Back to Edit
                    </button>

                    <button type="submit" class="btn-confirm" onclick="sessionStorage.removeItem('pumpingKitAppointment')">
                        <i class="fas fa-check-circle"></i> Confirm Appointment
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


            

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stored = sessionStorage.getItem('pumpingKitAppointment');
    if (!stored) return;

    const data = JSON.parse(stored);

    console.log(data);

    // Example: Fill the page with the stored values
    let kitType = "";
    if (data.kit_type === "manual") {
        kitType = "Automatic Pumping Kit";
    } else if (data.kit_type === "automatic") {
        kitType = "Automatic";
    }

    document.getElementById('confirm_type').innerText = kitType;

// Convert datetime into Malaysian readable format
    const dateObj = new Date(data.appointment_datetime);

    const monthsMY = [
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
    ];

    let hours = dateObj.getHours();
    let minutes = String(dateObj.getMinutes()).padStart(2, "0");

    let ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;

    const formattedDateMY = `${dateObj.getDate()} ${monthsMY[dateObj.getMonth()]} ${dateObj.getFullYear()}, ${hours}.${minutes} ${ampm}`;

    document.getElementById('confirm_datetime').innerText = formattedDateMY;

    let deliveryLocation = "";

        if (data.location === "main_foyer") {
            deliveryLocation = "Main Foyer";
        } else if (data.location === "front_counter") {
            deliveryLocation = "Front Counter";
        } else if (data.location === "reception") {
            deliveryLocation = "Reception Area";
        } else if (data.location === "milk_bank") {
            deliveryLocation = "Milk Bank Office";
        }
    document.getElementById('confirm_location').innerText = deliveryLocation;

    document.getElementById('confirm_reason').innerText = data.reason;

    //Send to be Inserted into the Database
    document.getElementById('kit_type').value = data.kit_type;

    document.getElementById('appointment_datetime').value = data.appointment_datetime;

    document.getElementById('location').value = data.location;

    document.getElementById('reason').value = data.reason;

    });

</script>

@endsection 

