@extends('layouts.donor')

@section('title', 'Milk Donation Appointment')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_appointment-form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        <form id="milkDonationForm" method="POST" action="#">
            @csrf

            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label for="milk_amount_select">Amount of Milk to Donate</label>
                        <select id="milk_amount_select" name="milk_amount_select" class="form-control" required onchange="toggleCustomAmount()">
                            <option value="">Select amount</option>
                            <option value="1000">1 L (1000 ml)</option>
                            <option value="2000">2 L (2000 ml)</option>
                            <option value="3000">3 L (3000 ml)</option>
                            <option value="other">Other (Specify below)</option>
                        </select>
                    </div>

                    <div class="form-group" id="custom_amount_group" style="display: none;">
                        <label for="milk_amount_custom">Custom Amount (ml)</label>
                        <div class="input-with-unit">
                            <input type="number" id="milk_amount_custom" name="milk_amount_custom" class="form-control" min="1" placeholder="Enter amount">
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
                <a href="{{ route('donor.confirm-appointment') }}" class="btn-submit">Continue</a>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Run on page load to set initial state
        toggleLocationFields();
        toggleCustomAmount(); // Ensure custom amount is hidden/shown correctly on load
    });

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
</script>
@endsection