@extends('layouts.hmmc')

@section('title', 'User Management')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hmmc_manage-users.css') }}">

    <div class="main-content">
        <div class="page-header">
            <div class="header-top">
                <h1>User Management</h1>
                <div class="header-actions">
                    <button class="btn-export" onclick="exportUsers()">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button class="btn-add-user" onclick="openRoleModal()">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                </div>
            </div> 
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Users</h3>
                    <div class="stat-number">{{ $totalUsers }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> All registered users
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="stat-content">
                    <h3>Donors</h3>
                    <div class="stat-number">{{ $donors->count() }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-heart"></i> Milk donors
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-baby"></i>
                </div>
                <div class="stat-content">
                    <h3>Recipients</h3>
                    <div class="stat-number">{{ $parents->count() }}</div>
                    <div class="stat-change neutral">
                        <i class="fas fa-users"></i> Parents registered
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-user-nurse"></i>
                </div>
                <div class="stat-content">
                    <h3>Staff Members</h3>
                    <div class="stat-number">{{ $doctors->count() + $nurses->count() + $labtechs->count() + $shariahs->count() + $admins->count() }}</div>
                    <div class="stat-change negative">
                        <i class="fas fa-briefcase-medical"></i> Healthcare team
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>All Users</h2>
                <div class="table-controls">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search users..." onkeyup="searchUsers()" class="btn-search"> 
                    </div>
                    <select id="roleFilter" onchange="filterByRole()" class="btn-filter">
                        <option value="all">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="nurse">Nurse</option>
                        <option value="labtech">Lab Tech</option>
                        <option value="shariah">Shariah</option>
                        <option value="parent">Parent</option>
                        <option value="donor">Donor</option>
                    </select>
                </div>
            </div>

            <div class="tabs">
                <button class="tab active" data-tab="all" onclick="filterTab('all')">
                    All Users <span class="tab-count">{{ $totalUsers }}</span>
                </button>
                <button class="tab" data-tab="donors" onclick="filterTab('donor')">
                    Donors <span class="tab-count">{{ $donors->count() }}</span>
                </button>
                <button class="tab" data-tab="parents" onclick="filterTab('parent')">
                    Recipients <span class="tab-count">{{ $parents->count() }}</span>
                </button>
                <button class="tab" data-tab="staff" onclick="filterTab('staff')">
                    Staff <span class="tab-count">
                        {{ $doctors->count() + $nurses->count() + $labtechs->count() + $shariahs->count() + $admins->count() }}
                    </span>
                </button>
            </div>

            <div class="table-wrapper">
                <table class="records-table" id="usersTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">USER <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(1)">ROLE <i class="fas fa-sort"></i></th>
                            <th>CONTACT</th>
                            <th>STATUS</th>
                            <th>SCREENING STATUS</th> 
                            <th onclick="sortTable(5)">REGISTRATION DATE <i class="fas fa-sort"></i></th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @if($allUsers->count() > 0)
                        @foreach ($allUsers as $user)
                            @php
                                $status = $user->status ?? 'active';
                                // Use actual screening data instead of placeholder
                                $screeningStatus = $user->role == 'donor' ? ($user->screening_status ?? 'pending') : 'na';
                                $screeningRemark = $user->screening_remark ?? null;
                            @endphp
                            <tr data-role="{{ $user->role }}" data-screening-status="{{ $screeningStatus }}">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar {{ ['teal', 'blue', 'green', 'purple', 'orange'][$loop->index % 5] }}">
                                            {{ strtoupper(substr($user->name ?? 'NA', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $user->name ?? 'N/A' }}</div>
                                            <div class="user-email">{{ $user->email ?? 'N/A' }}</div>
                                            <div class="user-username">@{{ $user->username ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge role-{{ $user->role }}">
                                        @if($user->role == 'admin')
                                            <i class="fas fa-user-shield"></i>
                                        @elseif($user->role == 'doctor')
                                            <i class="fas fa-stethoscope"></i>
                                        @elseif($user->role == 'nurse')
                                            <i class="fas fa-user-nurse"></i>
                                        @elseif($user->role == 'labtech')
                                            <i class="fas fa-flask"></i>
                                        @elseif($user->role == 'shariah')
                                            <i class="fas fa-book-quran"></i>
                                        @elseif($user->role == 'parent')
                                            <i class="fas fa-baby"></i>
                                        @elseif($user->role == 'donor')
                                            <i class="fas fa-hand-holding-heart"></i>
                                        @endif
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="contact-info">
                                        <i class="fas fa-phone"></i> {{ $user->contact ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                
                                <td class="screening-cell">
                                    @if ($user->role == 'donor')
                                        <span class="screening-badge screening-{{ $screeningStatus }}" 
                                            @if($screeningStatus == 'failed' && $screeningRemark) title="{{ $screeningRemark }}" @endif>
                                            {{ ucfirst($screeningStatus) }}
                                        </span>
                                        
                                        @if($screeningStatus == 'failed' && $screeningRemark)
                                            <div class="screening-remark-short">
                                                <i class="fas fa-exclamation-circle"></i> 
                                                {{ \Illuminate\Support\Str::limit($screeningRemark, 50) }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="screening-badge screening-na">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="date-info">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ isset($user->created_at) ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <div class="action-buttons">
                                        @if($user->role == 'donor' && ($user->screening_status === 'passed'))
                                            {{-- For approved donors: Show Send Credentials + all other buttons --}}
                                            <button class="icon-btn send-credential-btn" title="Send Credentials"
                                                    onclick="sendCredentials('{{ $user->original_id }}', '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->contact }}')">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>

                                            <button class="icon-btn view-btn" title="View Details"
                                                onclick="window.location.href='{{ route('hmmc.users.show', ['role' => $user->original_table, 'id' => $user->original_id]) }}'">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button class="icon-btn edit-btn" title="Edit"
                                                onclick="window.location.href='{{ route('hmmc.users.edit', ['role' => $user->original_table, 'id' => $user->original_id]) }}'">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="icon-btn delete-btn" title="Delete"
                                                onclick="confirmDelete('{{ $user->original_id }}', '{{ $user->original_table }}', '{{ addslashes($user->name) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @elseif($user->role == 'donor' && ($user->screening_status === 'pending'))

                                            <button class="icon-btn view-btn" title="View Details"
                                                onclick="window.location.href='{{ route('hmmc.users.show', ['role' => $user->original_table, 'id' => $user->original_id]) }}'">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button class="icon-btn delete-btn" title="Delete"
                                                onclick="confirmDelete('{{ $user->original_id }}', '{{ $user->original_table }}', '{{ addslashes($user->name) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                        @else
                                            {{-- For ALL other users (non-donors AND donors who haven't passed screening) --}}
                                            <button class="icon-btn view-btn" title="View Details"
                                                onclick="window.location.href='{{ route('hmmc.users.show', ['role' => $user->original_table, 'id' => $user->original_id]) }}'">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button class="icon-btn edit-btn" title="Edit"
                                                onclick="window.location.href='{{ route('hmmc.users.edit', ['role' => $user->original_table, 'id' => $user->original_id]) }}'">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="icon-btn delete-btn" title="Delete"
                                                onclick="confirmDelete('{{ $user->original_id }}', '{{ $user->original_table }}', '{{ addslashes($user->name) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="noResults">
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>No users found.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Role Selection Modal -->
    <div id="roleModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Select User Role</h2>
            <p class="modal-subtitle">Choose the type of user you want to create</p>
            
            <div class="role-cards">
                <a href="{{ route('hmmc.create-new-user', ['role' => 'admin']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon blue">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3>HMMC Admin</h3>
                        <p>System administrator</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'doctor']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon green">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3>Doctor</h3>
                        <p>Medical practitioner</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'nurse']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon teal">
                            <i class="fas fa-user-nurse"></i>
                        </div>
                        <h3>Nurse</h3>
                        <p>Healthcare professional</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'labtech']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon purple">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>Lab Technician</h3>
                        <p>Laboratory specialist</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'shariah']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon orange">
                            <i class="fas fa-book-quran"></i>
                        </div>
                        <h3>Shariah Committee</h3>
                        <p>Islamic compliance expert</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'parent']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon pink">
                            <i class="fas fa-baby"></i>
                        </div>
                        <h3>Parent</h3>
                        <p>Milk recipient</p>
                    </div>
                </a>
            </div>

            <button class="btn-cancel-modal" onclick="closeRoleModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-icon-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="modal-title">Confirm Deletion</h2>
            <p class="modal-subtitle" id="deleteMessage"></p>
            
            <div class="permanent-warning">
                <strong>Warning:</strong> This action is permanent and cannot be undone. All user data will be permanently deleted.
            </div>
            
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn-delete" onclick="executeDelete()">
                    <i class="fas fa-trash"></i> Delete User
                </button>
            </div>
        </div>
    </div>

    <!-- Credential Sending Modal -->
    <div id="credentialModal" class="modal">
        <div class="modal-content modal-credential">
            
            <div id="donorInfo" class="donor-info-celebrate">
                <!-- Donor info will be inserted here -->
            </div>

            <div class="delivery-methods">
                <div class="method-card">
                    <div class="method-icon email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="method-info">
                        <h4>Email Delivery</h4>
                    </div>
                    <div class="method-status" id="emailStatus">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                
                <div class="method-card">
                    <div class="method-icon whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="method-info">
                        <h4>WhatsApp Message</h4>
                    </div>
                    <div class="method-status" id="whatsappStatus">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="credential-loading-celebrate" id="credentialLoading" style="display: none;">
                <div class="loading-animation">
                    <div class="spinner"></div>
                    <div class="loading-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <h3>Spreading the Joy! ✨</h3>
                <p>We're sending a warm welcome to our new donor...</p>
            </div>
            
            <div class="credential-success-celebrate" id="credentialSuccess" style="display: none;">
                <div class="success-animation">
                    <i class="fas fa-check-circle"></i>
                    <div class="confetti">
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                    </div>
                </div>
                <h3>Success! 🎊</h3>
                <p id="successMessage"></p>
                <div class="success-details" id="successDetails"></div>
            </div>
            
            <div class="credential-error-celebrate" id="credentialError" style="display: none;">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Oops! Let's Try Again</h3>
                <p id="errorMessage"></p>
                <div class="error-suggestions">
                    <p><strong>Quick fixes:</strong></p>
                    <ul>
                        <li>Check internet connection</li>
                        <li>Verify email configuration</li>
                        <li>Ensure donor contact details are correct</li>
                    </ul>
                </div>
            </div>
            
            <div class="modal-actions-celebrate">
                <button class="btn-cancel-celebrate" onclick="cancelSendCredentials()">
                    <i class="fas fa-times"></i> Maybe Later
                </button>
                <button class="btn-confirm-celebrate" onclick="confirmSendCredentials()">
                    <i class="fas fa-paper-plane"></i> Send Welcome!
                </button>
            </div>

        </div>
    </div>

    <script>
        let deleteUserId, deleteUserTable;
        let currentDonorId = null;
        let currentDonorData = null;

        // Modal Functions
        function openRoleModal() {
            document.getElementById('roleModal').style.display = 'flex';
        }

        function closeRoleModal() {
            document.getElementById('roleModal').style.display = 'none';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const roleModal = document.getElementById('roleModal');
            const deleteModal = document.getElementById('deleteModal');
            const credentialModal = document.getElementById('credentialModal');
            
            if (event.target === roleModal) closeRoleModal();
            if (event.target === deleteModal) closeDeleteModal();
            if (event.target === credentialModal) cancelSendCredentials();
        }

        // Search functionality
        function searchUsers() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');
            let found = false;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                if (row.id === 'noResults') continue; 

                const text = row.textContent.toLowerCase();
                const isMatch = text.includes(input);
                
                // Also apply current filter
                const currentRoleFilter = document.getElementById('roleFilter').value;
                const rowRole = row.getAttribute('data-role');
                const staffRoles = ['admin', 'doctor', 'nurse', 'labtech', 'shariah'];
                
                let isFiltered = false;
                if (currentRoleFilter === 'all') {
                    isFiltered = true;
                } else if (currentRoleFilter === 'staff') {
                    isFiltered = staffRoles.includes(rowRole);
                } else {
                    isFiltered = currentRoleFilter === rowRole;
                }

                if (isMatch && isFiltered) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            }
            
            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.style.display = found ? 'none' : 'table-row';
            }
        }

        // Filter by role (Dropdown)
        function filterByRole() {
            const select = document.getElementById('roleFilter');
            const role = select.value;
            
            // Sync with tabs
            const tabs = document.querySelectorAll('.tabs .tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            let targetTab;
            if (role === 'all') {
                targetTab = document.querySelector('.tabs .tab[data-tab="all"]');
            } else if (['admin', 'doctor', 'nurse', 'labtech', 'shariah'].includes(role)) {
                targetTab = document.querySelector('.tabs .tab[data-tab="staff"]');
            } else {
                targetTab = document.querySelector(`.tabs .tab[data-tab="${role}"]`);
            }
            
            if (targetTab) {
                targetTab.classList.add('active');
            }

            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');
            const staffRoles = ['admin', 'doctor', 'nurse', 'labtech', 'shariah'];
            let found = false;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                if (row.id === 'noResults') continue;

                const rowRole = row.getAttribute('data-role');
                let isVisible = false;
                
                if (role === 'all') {
                    isVisible = true;
                } else if (role === 'staff') {
                    isVisible = staffRoles.includes(rowRole);
                } else {
                    isVisible = rowRole === role;
                }
                
                if (isVisible) {
                    // Also check against search input
                    const input = document.getElementById('searchInput').value.toLowerCase();
                    const text = row.textContent.toLowerCase();
                    const isMatch = text.includes(input);
                    
                    if (isMatch) {
                        row.style.display = '';
                        found = true;
                    } else {
                        row.style.display = 'none';
                    }
                } else {
                    row.style.display = 'none';
                }
            }
            
            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.style.display = found ? 'none' : 'table-row';
            }
        }

        // Tab filtering
        function filterTab(type) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.currentTarget.classList.add('active');

            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');
            const staffRoles = ['admin', 'doctor', 'nurse', 'labtech', 'shariah'];
            let found = false;

            // Sync with dropdown
            const roleDropdown = document.getElementById('roleFilter');
            if (type === 'all') {
                roleDropdown.value = 'all';
            } else if (type === 'staff') {
                roleDropdown.value = 'staff';
            } else {
                roleDropdown.value = type;
            }

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                if (row.id === 'noResults') continue;

                const rowRole = row.getAttribute('data-role');
                const input = document.getElementById('searchInput').value.toLowerCase();
                const text = row.textContent.toLowerCase();
                const isMatch = text.includes(input);

                let isVisible = false;

                if (type === 'all') {
                    isVisible = true;
                } else if (type === 'staff') {
                    isVisible = staffRoles.includes(rowRole);
                } else {
                    isVisible = rowRole === type;
                }
                
                if (isVisible && isMatch) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            }
            
            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.style.display = found ? 'none' : 'table-row';
            }
        }

        // Sort table
        function sortTable(columnIndex) {
            const table = document.getElementById('usersTable');
            let switching = true;
            let dir = 'asc';
            let switchcount = 0;

            while (switching) {
                switching = false;
                const rows = table.rows;

                for (let i = 1; i < (rows.length - 1); i++) {
                    const shouldSwitch = false;
                    const x = rows[i].getElementsByTagName('TD')[columnIndex];
                    const y = rows[i + 1].getElementsByTagName('TD')[columnIndex];

                    if (dir === 'asc') {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                            switching = true;
                            break;
                        }
                    } else if (dir === 'desc') {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                            switching = true;
                            break;
                        }
                    }
                }
                
                if (!switching && dir === 'asc') {
                    dir = 'desc';
                    switching = true;
                }
            }
        }

       // User Actions 
        function editUser(userId, userTable) {
            window.location.href = `/hmmc/users/${userTable}/${userId}/edit`;
        }

        function viewUser(userId, userTable) {
            window.location.href = `/hmmc/users/${userTable}/${userId}`;
        }

        function confirmDelete(userId, userTable, userName) {
            deleteUserId = userId;
            deleteUserTable = userTable;
            document.getElementById('deleteMessage').textContent = `Are you sure you want to delete ${userName}? This action cannot be undone.`;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function executeDelete() {
            // Construct the correct URL
            const deleteUrl = `/hmmc/users/${deleteUserTable}/${deleteUserId}`;
            
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    showNotification('User deleted successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Error deleting user');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error deleting user. Please try again.', 'error');
            });
        }
    
        // Credential sending functionality
        function sendCredentials(donorId, donorName, donorEmail, donorContact) {
            currentDonorId = donorId;
            currentDonorData = {
                name: donorName,
                email: donorEmail,
                contact: donorContact
            };

            // Show confirmation modal
            const modal = document.getElementById('credentialModal');
            const donorInfo = document.getElementById('donorInfo');
            
            let infoHtml = `
                <div class="donor-info">
                    <strong>${donorName}</strong><br>
                    <small class="text-muted">ID: ${donorId}</small>
            `;
            
            if (donorEmail && donorEmail !== 'N/A') {
                infoHtml += `<br>📧 ${donorEmail}`;
            }
            
            if (donorContact && donorContact !== 'N/A') {
                infoHtml += `<br>📱 ${donorContact}`;
            }
            
            infoHtml += `</div>`;
            donorInfo.innerHTML = infoHtml;
            
            // Reset states
            document.getElementById('credentialLoading').style.display = 'none';
            document.getElementById('credentialSuccess').style.display = 'none';
            document.getElementById('credentialError').style.display = 'none';
            
            modal.style.display = 'flex';
        }

        function confirmSendCredentials() {
            const modal = document.getElementById('credentialModal');
            const loading = document.getElementById('credentialLoading');
            const success = document.getElementById('credentialSuccess');
            const error = document.getElementById('credentialError');
            
            // Show loading, hide others
            loading.style.display = 'block';
            success.style.display = 'none';
            error.style.display = 'none';
            
            // Send AJAX request
            fetch('{{ route("hmmc.send-credentials") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    donor_id: currentDonorId,
                    donor_name: currentDonorData.name,
                    donor_email: currentDonorData.email,
                    donor_contact: currentDonorData.contact
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        let errorMessage = `HTTP ${response.status}`;
                        try {
                            const errorData = JSON.parse(text);
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            errorMessage = text || errorMessage;
                        }
                        throw new Error(errorMessage);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                loading.style.display = 'none';
                
                if (data.success) {
                    success.style.display = 'block';
                    document.getElementById('successMessage').textContent = data.message;
                    
                    // Update status indicators
                    if (data.email_sent) {
                        document.getElementById('emailStatus').innerHTML = '<i class="fas fa-check" style="color: #10b981;"></i>';
                    }
                    if (data.whatsapp_sent) {
                        document.getElementById('whatsappStatus').innerHTML = '<i class="fas fa-check" style="color: #10b981;"></i>';
                    }
                    
                    // Close modal after 4 seconds
                    setTimeout(() => {
                        modal.style.display = 'none';
                        success.style.display = 'none';
                        // Reset status indicators
                        document.getElementById('emailStatus').innerHTML = '<i class="fas fa-clock"></i>';
                        document.getElementById('whatsappStatus').innerHTML = '<i class="fas fa-clock"></i>';
                    }, 4000);
                } else {
                    throw new Error(data.message || 'Failed to send credentials');
                }
            })
            .catch(err => {
                console.error('Full error:', err);
                loading.style.display = 'none';
                error.style.display = 'block';
                
                let errorMessage = err.message;
                if (err.message.includes('Network response was not ok')) {
                    errorMessage = 'Server error. Please check your email configuration.';
                }
                
                document.getElementById('errorMessage').textContent = errorMessage;
            });
        }

        function sendCredentials(donorId, donorName, donorEmail, donorContact) {
            currentDonorId = donorId;
            currentDonorData = {
                name: donorName,
                email: donorEmail,
                contact: donorContact
            };

            // Show confirmation modal
            const modal = document.getElementById('credentialModal');
            const donorInfo = document.getElementById('donorInfo');
            
            let infoHtml = `
                <div class="donor-avatar-large">
                    <div class="avatar-initials">${donorName.charAt(0).toUpperCase()}</div>
                </div>
                <div class="donor-details">
                    <h3>${donorName}</h3>
                    <div class="donor-contact-info">
            `;
            
            if (donorEmail && donorEmail !== 'N/A') {
                infoHtml += `<div class="contact-item"><i class="fas fa-envelope"></i> ${donorEmail}</div>`;
            }
            
            if (donorContact && donorContact !== 'N/A') {
                infoHtml += `<div class="contact-item"><i class="fas fa-phone"></i> ${donorContact}</div>`;
            }
            
            infoHtml += `</div></div>`;
            donorInfo.innerHTML = infoHtml;
            
            // Reset states
            document.getElementById('credentialLoading').style.display = 'none';
            document.getElementById('credentialSuccess').style.display = 'none';
            document.getElementById('credentialError').style.display = 'none';
            
            modal.style.display = 'flex';
        }

        function cancelSendCredentials() {
            const modal = document.getElementById('credentialModal');
            modal.style.display = 'none';
            
            // Reset states
            document.getElementById('credentialLoading').style.display = 'none';
            document.getElementById('credentialSuccess').style.display = 'none';
            document.getElementById('credentialError').style.display = 'none';
        }

        function exportUsers() {
            // Add export functionality here
            showNotification('Export functionality coming soon!', 'info');
        }

        // Utility Functions
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                color: white;
                font-weight: 500;
                max-width: 300px;
            `;
            
            const bgColors = {
                success: '#10b981',
                error: '#ef4444',
                info: '#3b82f6',
                warning: '#f59e0b'
            };
            
            notification.style.background = bgColors[type] || bgColors.info;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i> ${message}`;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add any initialization code here
        });
    </script>

    <style>
        .notification {
            transition: all 0.3s ease;
        }
        
        .donor-info-container {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .dropdown-content a {
            color: #374151;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .dropdown-content a:hover {
            background-color: #f3f4f6;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .modal-icon-success {
            font-size: 48px;
            color: #10b981;
            margin-bottom: 15px;
        }
    </style>
@endsection