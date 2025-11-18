@extends('layouts.nurse')

@section('title', 'Manage Milk Records')

@section('content')
<link rel="stylesheet" href="{{ asset('css/nurse_manage-milk-records.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="main-content">

        <div class="page-header">
            <h1>Milk Records Management</h1>
            <p>Milk Processing and Records</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Milk Processing and Records</h2>
                <div class="actions-header">
                    <button class="btn btn-search"><i class="fas fa-search"></i> Search</button>
                    <button class="btn btn-filter"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>

            <div class="records-list">
                @foreach ([
                    ['id' => 1, 'donor' => 'Sarah Ahmad Binti Fauzi', 'status' => 'Labelling', 'eligibility' => 'Passed'],
                    ['id' => 2, 'donor' => 'Maryam Binti Othman', 'status' => 'Storaging', 'eligibility' => 'Passed'],
                    ['id' => 3, 'donor' => 'Fatimah Az-zahra Binti Mohd Nor', 'status' => 'Screening', 'eligibility' => 'Passed'],
                    ['id' => 4, 'donor' => 'Aishah Radhi Binti Izzuddin', 'status' => 'Labelling', 'eligibility' => 'Passed'],
                    ['id' => 5, 'donor' => 'Nor Atiqah Humaira Binti Ishak', 'status' => 'Dispatching', 'eligibility' => 'Failed due to smoker'],
                ] as $record)
                <div class="record-item">
                    <div class="milk-donor-info">
                        <div class="milk-icon-wrapper">
                            <i class="fas fa-bottle-droplet milk-icon"></i>
                        </div>
                        <div>
                            <span class="milk-id">Milk ID #{{ $record['id'] }}</span>
                            <span class="donor-name">{{ $record['donor'] }}</span>
                        </div>
                    </div>
                    <div class="clinical-status">
                        <span class="status-tag status-{{ strtolower($record['status']) }}">
                            {{ $record['status'] }}
                        </span>
                    </div>
                    <div class="eligibility-status">
                        <span class="eligibility-tag eligibility-{{ strtolower(explode(' ', $record['eligibility'])[0]) }}">
                            {{ $record['eligibility'] }}
                        </span>
                    </div>
                    <div class="actions">
                        <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                        <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>


@endsection
