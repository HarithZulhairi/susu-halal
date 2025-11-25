<!-- resources/views/layouts/doctor.blade.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Donor Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/donor_sidebar.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Font Awesome CDN -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  integrity="sha512-xh6O/z3OJU5yExlqQxFJZrVfdkY3ZzA5C4lX2pB2VVDzE1d1ZVnVNQ6mvdx4+hIY1Y1DOfZ1f2k+LhFhSkk5Xg=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>


<style>
    :root {
        --primary: #1a5f7a;
        --secondary: #57cc99;
        --gradient: linear-gradient(135deg, #1a5f7a 0%, #57cc99 100%);
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .float-animation { animation: float 6s ease-in-out infinite; }
    .gradient-text {
        background: var(--gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

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
      <li><a href="{{ route('profile.show') }}"><i class="fa-solid fa-user"></i> Profile</a></li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Management</h4>
    <ul>
      <li><a href="{{ route('donor.appointments') }}"><i class="fa-solid fa-file-lines"></i> My Appointments</a></li>
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
