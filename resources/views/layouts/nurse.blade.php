<!-- resources/views/layouts/doctor.blade.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Doctor Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/nurse_sidebar.css') }}">
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
    <h4>NURSE</h4>
    <ul>
      <li><a href="{{ route('nurse.dashboard') }}"><i class="fa-solid fa-house"></i> Dashboard</a></li>
      <li><a href="{{ route('profile.show') }}"><i class="fa-solid fa-user"></i> Profile</a></li>
      <li>
        <a href="{{ route('nurse.donor-candidate-list') }}"><i class="fa-solid fa-droplet"></i> Donor Candidates</a>
      </li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Management</h4>
    <ul>
      <li><a href="{{ route('nurse.donor-appointment-record') }}"><i class="fa-solid fa-file-lines"></i> Donor Appointments</a></li>
      <li><a href="{{ route('nurse.manage-milk-records') }}"><i class="fa-solid fa-baby"></i> Milk Records</a></li>
      <li><a href="{{ route('nurse.allocate-milk') }}"><i class="fa-solid fa-gear"></i>Milk Distribution</a></li>
      <li><a href="{{ route('nurse.milk-request-list') }}"><i class="fa-solid fa-gear"></i> Milk Requests</a></li>
      <li><a href="{{ route('nurse.nurse_set-infant-weight') }}"><i class="fa-solid fa-gear"></i> Record Infant Weight</a></li>
    </ul>
  </div>

  <div class="sidebar-section logout-section">
    <ul>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</aside>


    <main class="main-content">
      @yield('content')
    </main>
  </div>
</body>
</html>
