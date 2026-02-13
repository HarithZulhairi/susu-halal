<!-- resources/views/layouts/doctor.blade.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Doctor Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/hmmc_sidebar.css') }}">
  <!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" 
      integrity="sha512-...CORRECT_HASH..." 
      crossorigin="anonymous">
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
  <div class="container">
    <aside class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/hmmc_logo_clear.png') }}" alt="HALIMATUSSAADIA Mother's Milk Centre Logo" style="width: 270px; height: auto;">
  </div>

  <div class="sidebar-section">
    <h4>HMMC</h4>
    <ul>
      <li><a href="{{ route('hmmc.dashboard') }}"><i class="fa-solid fa-house"></i> Dashboard</a></li>
      <li>
        <a href="{{ route('hmmc.manage-users') }}"><i class="fa-solid fa-droplet"></i> User Management</a>
      </li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Management</h4>
    <ul>
      <li><a href="{{ route('hmmc.hmmc_manage-milk-records') }}"><i class="fa-solid fa-file-lines"></i> Milk Processing</a></li>
      <li><a href="{{ route('hmmc.hmmc_milk-request') }}"><i class="fa-solid fa-prescription-bottle"></i> Milk Requests</a></li>
      <li><a href="{{ route('hmmc.hmmc_infant-list') }}"><i class="fa-solid fa-baby"></i> Infants List</a></li>
      <li><a href="{{ route('hmmc.infants-request') }}"><i class="fa-solid fa-route"></i> Milk Traceability</a></li>
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

  <script>
    // Common Success/Error Popup
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
        });
    @endif
  </script>
</body>
</html>
