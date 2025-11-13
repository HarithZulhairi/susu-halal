<!-- resources/views/layouts/doctor.blade.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Doctor Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/donor_sidebar.css') }}">
  <!-- Font Awesome CDN -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  integrity="sha512-xh6O/z3OJU5yExlqQxFJZrVfdkY3ZzA5C4lX2pB2VVDzE1d1ZVnVNQ6mvdx4+hIY1Y1DOfZ1f2k+LhFhSkk5Xg=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>

</head>
<body>
  <div class="container">
    <aside class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/hmmc_logo_clear.png') }}" alt="HALIMATUSSAADIA Mother's Milk Centre Logo" style="width: 270px; height: auto;">
  </div>

  <div class="sidebar-section">
    <h4>Donor</h4>
    <ul>
      <li><a href="{{ route('donor.dashboard') }}"><i class="fa-solid fa-house"></i> Dashboard</a></li>
      <li><a href="{{ route('donor.profile') }}"><i class="fa-solid fa-user"></i> Profile</a></li>
      <li>
        <a href="#"><i class="fa-solid fa-droplet"></i> Request Milk Form</a>
      </li>
      <li><a href="#"><i class="fa-solid fa-chart-line"></i> Reports</a></li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Management</h4>
    <ul>
      <li><a href="{{ route('donor.appointments') }}"><i class="fa-solid fa-file-lines"></i> My Appointments</a></li>
      <li><a href="#"><i class="fa-solid fa-file-lines"></i> Request</a></li>
      <li><a href="#"><i class="fa-solid fa-baby"></i> Recipient</a></li>
      <li><a href="#"><i class="fa-solid fa-gear"></i> Settings</a></li>
      <li>
        <a href="#"><i class="fa-solid fa-list"></i> Request List</a>
      </li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Explore</h4>
    <ul>
      <li><a href="#"><i class="fa-solid fa-book"></i> Audit Logs</a></li>
      <li><a href="#"><i class="fa-solid fa-chart-simple"></i> Activity Monitor</a></li>
    </ul>
  </div>

    <div class="logout-section">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </div>
</aside>


    <main class="main-content">
      @yield('content')
    </main>
  </div>
</body>
</html>
