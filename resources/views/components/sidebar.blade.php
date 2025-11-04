<!-- resources/views/components/sidebar.blade.php -->
<aside class="sidebar">
  <div class="logo">
    <h2>🍼 Barakah Milk Bank</h2>
  </div>

  <div class="sidebar-section">
    <h4>Doctor</h4>
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">User Management</a></li>
      <li><a href="#">Reports</a></li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Management</h4>
    <ul>
      <li><a href="{{ route('doctor.milk-records') }}">Request</a></li>
      <li><a href="#">Recipient</a></li>
      <li><a href="#">Settings</a></li>
      <li class="active"><a href="#">Milk Records</a></li>
    </ul>
  </div>

  <div class="sidebar-section">
    <h4>Explore</h4>
    <ul>
      <li><a href="#">Audit Logs</a></li>
      <li><a href="#">Activity Monitor</a></li>
    </ul>
  </div>
</aside>
