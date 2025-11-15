@extends('layouts.donor')

@section('title', 'Confirm Appointment')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donor_confirm-appointment.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        <form id="confirmAppointmentForm" method="POST" action="#">
            @csrf
            
            <div class="details-grid">
                {{-- ... (Details Grid remains unchanged) ... --}}
                <div class="details-column">
                    <h3>Donation Details</h3>
                    
                    {{-- 1. Amount of Milk --}}
                    <div class="detail-item">
                        <label>Amount of Milk to Donate (ml)</label>
                        <div class="detail-value">
                            {{ $appointment->milk_amount ?? '1000' }} ml
                        </div>
                        <input type="hidden" name="milk_amount" value="{{ $appointment->milk_amount ?? '1000' }}">
                    </div>

                    {{-- 2. Delivery Method (Formerly Donation Method) --}}
                    <div class="detail-item">
                        <label>Delivery Method</label>
                        <div class="detail-value">
                            {{ ucfirst(str_replace('_', ' ', $appointment->delivery_method ?? 'Delivery')) }}
                        </div>
                        <input type="hidden" name="delivery_method" value="{{ $appointment->delivery_method ?? 'delivery' }}">
                    </div>

                    {{-- 3. Location/Address Details (Moves to the Left for better grouping) --}}
                    <div class="detail-item">
                        <label>Delivery/Pick up Point</label>
                        <div class="detail-value">
                            @if (($appointment->delivery_method ?? 'delivery') == 'pick_up')
                                {{ $appointment->collection_address ?? 'User Pick up Address' }}
                            @else
                                {{ ucfirst(str_replace('_', ' ', $appointment->location ?? 'Main Foyer')) }}
                            @endif
                        </div>
                        <input type="hidden" name="location" value="{{ $appointment->location ?? 'main_foyer' }}">
                        <input type="hidden" name="collection_address" value="{{ $appointment->collection_address ?? '' }}">
                    </div>
                </div>

                <div class="details-column">
                    <h3>Schedule Details</h3>
                    
                    {{-- 4. Preferred Date & Time --}}
                    <div class="detail-item">
                        <label>Preferred Date & Time</label>
                        <div class="detail-value">
                            {{ $appointment->appointment_datetime ?? 'May 15, 2025 at 10:00 AM' }}
                        </div>
                        <input type="hidden" name="appointment_datetime" value="{{ $appointment->appointment_datetime ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="detail-item full-width">
                <label>Remarks</label>
                <div class="detail-value remarks-box">
                    {{ $appointment->remarks ?? 'No additional remarks provided.' }}
                </div>
                <input type="hidden" name="remarks" value="{{ $appointment->remarks ?? '' }}">
            </div>

            <div class="form-actions">
                <button type="button" class="btn-back" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back to Edit
                </button>
                
                <a href="{{ route('donor.appointments') }}" class="btn-confirm">
                    <i class="fas fa-check-circle"></i> Confirm Appointment
                </a>
            </div>
        </form>
    </div>
</div>

@endsection