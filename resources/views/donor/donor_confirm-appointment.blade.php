@extends('layouts.donor')

@section('title', 'Confirm Appointment')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_confirm-appointment.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">

    <div class="main-content">
<div class="confirm-appointment-page">
    <div class="page-header">
        <h1>Milk Donation Appointment Form</h1>
    </div>

    <div class="confirmation-container">
        <div class="confirmation-header">
            <h2>Confirm Your Appointment Details</h2>
            
            {{-- MODIFIED: Added wrapper for green instruction box style --}}
            <div class="instruction-box">
                <p>Please review your appointment details carefully before submitting.</p>
                <p>Our milk bank team will contact you shortly to confirm your schedule and provide further instructions.</p>
            </div>
            {{-- END MODIFIED --}}
        </div>

        <form id="confirmAppointmentForm" method="POST" action="{{ route('donor.store-milk-appointment') }}">
            @csrf


            <div class="details-grid">
                {{-- ... (Details Grid remains unchanged) ... --}}
                <div class="details-column">
                    <h3>Donation Details</h3>
                    
                    {{-- 1. Amount of Milk --}}
                    <div class="detail-item">
                        <label>Amount of Milk to Donate (ml)</label>
                        <div class="detail-value", id="confirm_amount">
                            {{ $appointment->milk_amount ?? '1000' }} ml
                        </div>
                        <input type="hidden" name="milk_amount" id="milk_amount">
                    </div>

                    {{-- 2. Delivery Method (Formerly Donation Method) --}}
                    <div class="detail-item">
                        <label>Delivery Method</label>
                        <div class="detail-value", id="confirm_delivery">
                            {{ ucfirst(str_replace('_', ' ', $appointment->delivery_method ?? 'Delivery')) }}
                        </div>
                        <input type="hidden" name="delivery_method" id="delivery_method">
                    </div>

                {{-- Delivery Location (Delivery Only) --}}
                <div class="detail-item" id="location_block" style="display:none;">
                    <label>Delivery Location</label>
                    <div class="detail-value" id="confirm_location"></div>
                    <input type="hidden" name="location" id="location">
                </div>

                {{-- Pick-Up Address (Pick Up Only) --}}
                <div class="detail-item" id="address_block" style="display:none;">
                    <label>Pick-Up Address</label>
                    <div class="detail-value" id="confirm_address"></div>
                    <input type="hidden" name="collection_address" id="collection_address">
                </div>

            </div>


                <div class="details-column">
                    <h3>Schedule Details</h3>
                    
                    {{-- 4. Preferred Date & Time --}}
                    <div class="detail-item">
                        <label>Preferred Date & Time</label>
                        <div class="detail-value", id="confirm_datetime">
                            {{ $appointment->appointment_datetime ?? 'May 15, 2025 at 10:00 AM' }}
                        </div>
                        <input type="hidden" name="appointment_datetime" id="appointment_datetime">
                    </div>
                </div>
            </div>

            <div class="detail-item full-width">
                <label>Remarks</label>
                <div class="detail-value remarks-box", id="confirm_remarks">
                    {{ $appointment->remarks ?? 'No additional remarks provided.' }}
                </div>
                <input type="hidden" name="remarks" id="remarks">
            </div>

            <div class="form-actions">
                <button type="button" class="btn-back" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back to Edit
                </button>
                
                <button type="submit" class="btn-confirm" onclick="sessionStorage.removeItem('milkDonationAppointment')">
                    <i class="fas fa-check-circle"></i> Confirm Appointment
                </a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stored = sessionStorage.getItem('milkDonationAppointment');
    if (!stored) return;

    const data = JSON.parse(stored);

    console.log(data);

    // Example: Fill the page with the stored values
    document.getElementById('confirm_amount').innerText =
    data.milk_amount_select === "other" ? (data.milk_amount_custom + " ml") : (data.milk_amount_select + " ml");

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


    
    let deliveryMethod = "";
    if (data.delivery_method === "delivery") {
        deliveryMethod = "Delivery";
    } else if (data.delivery_method === "pick_up") {
        deliveryMethod = "Pick Up";
    }
    
    document.getElementById('confirm_delivery').innerText = deliveryMethod;

    if (data.delivery_method === "delivery") {

        document.getElementById('location_block').style.display = 'block';
        document.getElementById('address_block').style.display = 'none';
        
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

    } else {
        document.getElementById('location_block').style.display = 'none';
        document.getElementById('address_block').style.display = 'block';

        document.getElementById('confirm_address').innerText = data.collection_address;
    }

    document.getElementById('confirm_remarks').innerText = data.remarks;
    
    //Set Data to be Stored in DB
    // Set values for database submission
    document.getElementById('milk_amount').value =
        data.milk_amount_select === "other" ? data.milk_amount_custom : data.milk_amount_select;

    document.getElementById('delivery_method').value = data.delivery_method;
    document.getElementById('appointment_datetime').value = data.appointment_datetime;

    document.getElementById('location').value = data.location || null;
    document.getElementById('collection_address').value = data.collection_address || null;

    document.getElementById('remarks').value = data.remarks;


});
</script>


@endsection