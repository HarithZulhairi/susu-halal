@extends('layouts.donor')

@section('title', 'Pumping Kit Form')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/donor_pumping-kit-form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="page-header">
        <h1>Pumping Kit Request Appointment Form</h1>
        <p>Please provide the following details to book your pumping kit at the hospital.</p>
    </div>

    <div class="form-container">
        <div class="form-card">
            <h2>Book Your Pumping Kit</h2>
            
            <form action="" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="kit_type">Type of Kit Needed</label>
                        <select id="kit_type" name="kit_type" required>
                            <option value="">Select kit type</option>
                            <option value="manual">Manual Pumping Kit</option>
                            <option value="automatic">Automatic Pumping Kit</option>
                            <option value="storage">Milk Storage Only</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="preferred_datetime">Preferred Date & Time</label>
                        <input type="datetime-local" id="preferred_datetime" name="preferred_datetime" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="preferred_location">Preferred Location</label>
                    <input type="text" id="preferred_location" name="preferred_location" placeholder="Enter preferred location" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-continue">
                        <span>Continue</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection