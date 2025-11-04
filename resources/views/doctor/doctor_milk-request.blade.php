<!-- resources/views/doctor/milk-records.blade.php -->
@extends('layouts.doctor')

@section('title', 'Milk Request Records')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/doctor_milk-request.css') }}">

  <div class="page-header">
    <h1>Milk Request Records</h1>
    <p>Manage and track all milk processing requests</p>
  </div>

  <div class="card">
    <div class="card-header">
      <h3>Milk Processing and Records</h3>
      <input type="text" class="search-input" placeholder="🔍 Search records...">
    </div>

    <table class="records-table">
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>NICU Cubicle No.</th>
          <th>Date Requested</th>
          <th>Date Time to Give</th>
          <th>Request Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>P001</strong><br>Sarah Ahmad Binti Fauzi</td>
          <td>A101</td>
          <td>Jan 12, 2024</td>
          <td>Jan 14, 2024</td>
          <td><span class="status approved">Approved</span></td>
          <td><button class="btn-view">👁</button><button class="btn-delete">🗑</button></td>
        </tr>
        <tr>
          <td><strong>P002</strong><br>Ahmad Jebon Bin Arif</td>
          <td>A102</td>
          <td>Jan 12, 2024</td>
          <td>Jan 15, 2024</td>
          <td><span class="status waiting">Waiting</span></td>
          <td><button class="btn-view">👁</button><button class="btn-delete">🗑</button></td>
        </tr>
        <tr>
          <td><strong>P003</strong><br>Sarah Aiman Bin Yusof</td>
          <td>A103</td>
          <td>Jan 12, 2024</td>
          <td>Jan 16, 2024</td>
          <td><span class="status rejected">Rejected</span></td>
          <td><button class="btn-view">👁</button><button class="btn-delete">🗑</button></td>
        </tr>
        <tr>
          <td><strong>P004</strong><br>Nurul Aisyah Binti Hassan</td>
          <td>A104</td>
          <td>Jan 12, 2024</td>
          <td>Jan 16, 2024</td>
          <td><span class="status allocated">Allocated</span></td>
          <td><button class="btn-view">👁</button><button class="btn-delete">🗑</button></td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection
