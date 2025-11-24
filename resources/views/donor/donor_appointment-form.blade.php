@extends('layouts.donor')

@section('title', 'Milk Donation Appointment')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_appointment-form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">

    <div class="main-content">
        
<div class="appointment-form-page">
    <div class="page-header">
        <h1>Milk Donation Appointment Form</h1>
    </div>

    <div class="form-container">
        <div class="form-header">
            <div>
                <h2>Book Your Milk Appointment</h2>
                <p>Please provide the following details to schedule your milk donation.</p>
            </div>
        </div>

        <form id="milkDonationForm" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label for="milk_amount_select">Amount of Milk to Donate</label>
                        <select id="milk_amount_select" name="milk_amount_select" class="form-control" required onchange="toggleCustomAmount()">
                            <option value="">Select amount</option>
                            <option value="1000">1 L (1000 ml)</option>
                            <option value="other">Other (Specify below)</option>
                        </select>
                    </div>

                    <div class="form-group" id="custom_amount_group" style="display: none;">
                        <label for="milk_amount_custom">Custom Amount (ml)</label>
                        <div class="input-with-unit">
                            <input type="number" id="milk_amount_custom" max="1000" name="milk_amount_custom" class="form-control" min="1" placeholder="Enter amount">
                            <span class="unit-label">ml</span>
                        </div>
                    </div>

                    {{-- Removed: Milk Storage Condition --}}
                    {{-- Removed: Container Type --}}

                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label for="appointment_datetime">Preferred Date & Time</label>
                        <input type="datetime-local" id="appointment_datetime" name="appointment_datetime" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Delivery Method</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                {{-- Changed value and text: Drop off -> Delivery --}}
                                <input type="radio" name="delivery_method" value="delivery" required onchange="toggleLocationFields()">
                                <span class="radio-text">Delivery</span>
                            </label>
                            <label class="radio-option">
                                {{-- Changed value and text: Collection -> Pick up --}}
                                <input type="radio" name="delivery_method" value="pick_up" required onchange="toggleLocationFields()">
                                <span class="radio-text">Pick up</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="location-section" id="location_section" style="display: none;">
                <div class="location-content" id="location_group" style="display: none;">
                    <label for="location" class="section-label">Preferred Delivery Location</label>
                    <select id="location" name="location" class="form-control location-input">
                        <option value="">Select drop-off location</option>
                        <option value="main_foyer">Main Foyer</option>
                        <option value="front_counter">Front Counter</option>
                        <option value="reception">Reception Area</option>
                        <option value="milk_bank">Milk Bank Office</option>
                    </select>
                </div>

                <div class="location-content" id="address_group" style="display: none;">
                    <label for="collection_address" class="section-label">Pick up Address</label>
                    <textarea id="collection_address" name="collection_address" class="form-control location-input" rows="3" placeholder="Enter your pick up address..."></textarea>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control" rows="5" placeholder="Add any additional information or special instructions..."></textarea>
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
        const input = document.getElementById("appointment_datetime");
        
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
        toggleLocationFields();
        toggleCustomAmount(); // Ensure custom amount is hidden/shown correctly on load
    });

    function restoreFormData() {
    const stored = sessionStorage.getItem('milkDonationAppointment');
    if (!stored) return;

    const data = JSON.parse(stored);

    // Milk amount
    document.getElementById('milk_amount_select').value = data.milk_amount_select;
    if (data.milk_amount_select === 'other') {
        document.getElementById('milk_amount_custom').value = data.milk_amount_custom;
    }

    // Datetime
    document.getElementById('appointment_datetime').value = data.appointment_datetime;

    // Delivery / Pick Up radio
    if (data.delivery_method) {
        const radio = document.querySelector(`input[name="delivery_method"][value="${data.delivery_method}"]`);
        if (radio) radio.checked = true;
    }

    //Re-run UI toggle AFTER setting radio
    toggleLocationFields();

    // Now fill location or address
    if (data.delivery_method === "delivery") {
        document.getElementById('location').value = data.location;
    } else if (data.delivery_method === "pick_up") {
        document.getElementById('collection_address').value = data.collection_address;
    }

    // Remarks
    document.getElementById('remarks').value = data.remarks;
}


    function toggleCustomAmount() {
        const select = document.getElementById('milk_amount_select');
        const customGroup = document.getElementById('custom_amount_group');
        const customInput = document.getElementById('milk_amount_custom');
        
        if (select.value === 'other') {
            customGroup.style.display = 'block';
            customInput.setAttribute('required', 'required');
        } else {
            customGroup.style.display = 'none';
            customInput.removeAttribute('required');
            customInput.value = ''; // Clear input if hidden
        }
    }

    function toggleLocationFields() {
        const delivery = document.querySelector('input[name="delivery_method"][value="delivery"]');
        const pickUp = document.querySelector('input[name="delivery_method"][value="pick_up"]');
        
        const locationSection = document.getElementById('location_section');
        const locationGroup = document.getElementById('location_group');
        const addressGroup = document.getElementById('address_group');
        
        const locationSelect = locationGroup.querySelector('.location-input');
        const addressTextarea = addressGroup.querySelector('.location-input');

        // Reset all location inputs/requirements
        locationSelect.removeAttribute('required');
        addressTextarea.removeAttribute('required');
        
        if (delivery && delivery.checked) {
            // Delivery (Drop-off) selected: Show drop-off locations (select)
            locationSection.style.display = 'block';
            locationGroup.style.display = 'block';
            addressGroup.style.display = 'none';
            locationSelect.setAttribute('required', 'required');
            addressTextarea.value = '';
            locationSelect.value = ''; // Clear selected location when switching back from pickup

        } else if (pickUp && pickUp.checked) {
            // Pick up (Collection) selected: Show pick up address (textarea)
            locationSection.style.display = 'block';
            locationGroup.style.display = 'none';
            addressGroup.style.display = 'block';
            addressTextarea.setAttribute('required', 'required');
            locationSelect.value = '';
            addressTextarea.value = ''; // Clear address when switching back from drop-off

        } else {
            // Neither selected: Hide everything
            locationSection.style.display = 'none';
            locationGroup.style.display = 'none';
            addressGroup.style.display = 'none';
            locationSelect.value = '';
            addressTextarea.value = '';
        }
    }



    function storeFormData() {

    const milkSelect = document.getElementById('milk_amount_select').value;
    const customInput = document.getElementById('milk_amount_custom').value.trim();
    const appointmentTime = document.getElementById('appointment_datetime').value;
    const deliveryMethod = document.querySelector('input[name="delivery_method"]:checked')?.value;
    const location = document.getElementById('location').value;
    const collectionAddress = document.getElementById('collection_address').value;
    const remarks = document.getElementById('remarks').value;

    // VALIDATION CHECKS
    let errors = [];

    // MUST PICK AMOUNT
    if (!milkSelect && !customInput) {
        errors.push("Please select or enter milk amount.");
    }

    // If custom amount chosen, enforce rules
    if (milkSelect === "other") {
        if (!customInput) {
            errors.push("Custom milk amount cannot be empty.");
        } else if (parseInt(customInput) > 1000) {
            errors.push("Milk amount cannot exceed 1000 ml.");
        }
    }

    // Appointment time required
    if (!appointmentTime) {
        errors.push("Please select an appointment date & time.");
    }

    // Delivery method required
    if (!deliveryMethod) {
        errors.push("Please select a delivery method.");
    }

    // Delivery-specific fields
    if (deliveryMethod === "delivery" && !location) {
        errors.push("Please enter a delivery location.");
    }
    if (deliveryMethod === "pick_up" && !collectionAddress) {
        errors.push("Please enter a pick-up address.");
    }

    //If there are errors, show alert and STOP redirecting
    if (errors.length > 0) {
        alert(errors.join("\n")); // You can replace alert() with your own popup later
        return; // Stop execution
    }

    // SANITIZE amount before storing
    let amount = milkSelect === "other" ? customInput : milkSelect;

    const data = {
        milk_amount_select: milkSelect,
        milk_amount_custom: amount,
        appointment_datetime: appointmentTime,
        delivery_method: deliveryMethod,
        location,
        collection_address: collectionAddress,
        remarks
    };
   
    // Use sessionStorage so it only lasts for this session
    sessionStorage.setItem('milkDonationAppointment', JSON.stringify(data));

    // Redirect to confirmation page
    window.location.href = "{{ route('donor.confirm-appointment') }}";
    }
</script>
@endsection