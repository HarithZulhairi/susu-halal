<!-- resources/views/doctor/milk-records.blade.php -->
@extends('layouts.nurse')

@section('title', 'Milk Request Records')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/nurse_milk-request.css') }}">
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <div class="page-header">
    <h1>Milk Request Records</h1>
    <p>Manage and track all milk processing requests</p>
  </div>

  <div class="card">
    <div class="card-header">
  <div class="header-left">
    <h3>Milk Processing and Records</h3>
  </div>
  
  <div class="header-right">
    <button class="btn-add">
      <i class="fas fa-plus"></i> Add Request
    </button>
    <input type="text" class="search-input" placeholder="🔍 Search records...">
  </div>
</div>


    <table class="records-table">
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>NICU</th>
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
          <td>Jan 14, 2024 • 08:30 AM</td>
          <td><span class="status approved">Approved</span></td>
          <td class="actions">
            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>P002</strong><br>Ahmad Jebon Bin Arif</td>
          <td>A102</td>
          <td>Jan 12, 2024</td>
          <td>Jan 15, 2024 • 09:45 AM</td>
          <td><span class="status waiting">Waiting</span></td>
          <td class="actions">
            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>P003</strong><br>Sarah Aiman Bin Yusof</td>
          <td>A103</td>
          <td>Jan 12, 2024</td>
          <td>Jan 16, 2024 • 02:00 PM</td>
          <td><span class="status rejected">Rejected</span></td>
          <td class="actions">
            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>P004</strong><br>Nurul Aisyah Binti Hassan</td>
          <td>A104</td>
          <td>Jan 12, 2024</td>
          <td>Jan 16, 2024 • 03:30 PM</td>
          <td><span class="status allocated">Allocated</span></td>
          <td class="actions">
            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

@endsection
