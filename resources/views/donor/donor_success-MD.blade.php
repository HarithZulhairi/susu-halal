@extends('layouts.donor')

@section('content')

<div class="container" style="text-align:center; margin-top:50px;">
    <h1>Appointment Confirmed!</h1>
    <p>Thank you for your contribution 💖</p>

    <h3>Your Reference Number:</h3>
    <div style="font-size:24px; font-weight:bold; margin:10px;">
        {{ $ref }}
    </div>

    <a href="{{ route('donor.appointments') }}" class="btn btn-primary">
        View My Appointments
    </a>
</div>

@endsection
