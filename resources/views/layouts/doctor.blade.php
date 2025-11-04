<!-- resources/views/layouts/doctor.blade.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/doctor.css', 'resources/js/app.js'])
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Doctor Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/doctor.css') }}">
</head>
<body>
  <div class="container">
    @include('components.sidebar')

    <main class="main-content">
      @yield('content')
    </main>
  </div>
</body>
</html>
