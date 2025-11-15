@extends('layouts.donor')

@section('title', 'Confirm Pumping Kit')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/donor_confirm-pumping-kit-form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="page-header">
        <h1>Pumping Kit Request Appointment Form</h1>
    </div>

    <div class="confirm-container">
        <div class="confirm-card">
            <div class="card-header">
                <h2>Confirm Your Appointment Details</h2>
            </div>

            <div class="card-instructions">
                <p>Please review your appointment details carefully before submitting.</p>
                <p>Our milk bank team will contact you shortly to confirm your schedule and provide further instructions</p>
            </div>

            <div class="details-grid">
                <div class="detail-item">
                    <label>Type of Kit Needed</label>
                    <div class="detail-value">{{ $appointment->kit_type ?? 'Manual Pumping Kit' }}</div>
                </div>

                <div class="detail-item">
                    <label>Preferred Date & Time</label>
                    <div class="detail-value">{{ $appointment->preferred_datetime ?? 'January 15, 2025 at 10:00 AM' }}</div>
                </div>

                <div class="detail-item">
                    <label>Reason for Request (optional)</label>
                    <div class="detail-value text-area-value">{{ $appointment->reason ?? 'Need pumping equipment for returning to work' }}</div>
                </div>

                <div class="detail-item">
                    <label>Preferred Location</label>
                    <div class="detail-value">{{ $appointment->preferred_location ?? 'Hospital Milk Bank, Ward 3A' }}</div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('donor.pumping-kit-form') }}" class="btn-edit">
    <i class="fas fa-edit"></i>
    <span>Edit</span>
</a>
                <button type="button" class="btn-confirm" onclick="window.location.href='{{ route('donor.appointments') }}'">
    <span>Confirm</span>
    <i class="fas fa-check"></i>
</button>

            </div>
        </div>
    </div>
@endsection 

