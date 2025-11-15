@extends('layouts.hmmc')

@section('title', 'User Management')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- IMPORTANT: Added CSRF token for DELETE --}}
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
                            <th onclick="sortTable(4)">REGISTRATION DATE <i class="fas fa-sort"></i></th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    {{-- DEMO ENTRY 1: PASSED SCREENING --}}
                    <tr data-role="donor" data-screening-status="passed">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar green">SA</div>
                                <div>
                                    <div class="user-name">Sarah Al-Ghazali</div>
                                    <div class="user-email">sarah.a@example.com</div>
                                    <div class="user-username">@sarahG</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge role-donor">
                                <i class="fas fa-hand-holding-heart"></i> Donor
                            </span>
                        </td>
                        <td>
                            <span class="contact-info">
                                <i class="fas fa-phone"></i> +6012-345 6789
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-approved">Active</span>
                        </td>
                        <td class="screening-cell">
                            <span class="screening-badge screening-passed" title="All medical screening requirements met.">
                                Passed
                            </span>
                        </td>
                        <td>
                            <span class="date-info">
                                <i class="far fa-calendar-alt"></i> Nov 15, 2025
                            </span>
                        </td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button class="icon-btn view-btn" title="View Details" onclick="viewUser('1000', 'donor')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn edit-btn" title="Edit" onclick="editUser('1000', 'donor')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn delete-btn" title="Delete" onclick="confirmDelete('1000', 'donor', 'Sarah Al-Ghazali')"><i class="fas fa-trash"></i></button>
                                <button class="icon-btn more-btn" title="More Options"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </td> 
                    </tr>
                    {{-- DEMO ENTRY 2: FAILED SCREENING --}}
                    <tr data-role="donor" data-screening-status="failed">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar red">AA</div>
                                <div>
                                    <div class="user-name">Aisha Binti Abu</div>
                                    <div class="user-email">aisha.abu@example.com</div>
                                    <div class="user-username">@aishaAbu</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge role-donor">
                                <i class="fas fa-hand-holding-heart"></i> Donor
                            </span>
                        </td>
                        <td>
                            <span class="contact-info">
                                <i class="fas fa-phone"></i> +6019-987 6543
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-rejected">Inactive</span>
                        </td>
                        <td class="screening-cell">
                            <span class="screening-badge screening-failed" title="Screening failed due to blood test anomaly.">
                                Failed
                            </span>
                            {{-- Explicitly showing the remark/note for failed status --}}
                            <div class="screening-remark-short">
                                <i class="fas fa-exclamation-circle"></i> Incomplete blood work, retest required.
                            </div>
                        </td>
                        <td>
                            <span class="date-info">
                                <i class="far fa-calendar-alt"></i> Oct 28, 2025
                            </span>
                        </td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button class="icon-btn view-btn" title="View Details" onclick="viewUser('1001', 'donor')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn edit-btn" title="Edit" onclick="editUser('1001', 'donor')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn delete-btn" title="Delete" onclick="confirmDelete('1001', 'donor', 'Aisha Binti Abu')"><i class="fas fa-trash"></i></button>
                                <button class="icon-btn more-btn" title="More Options"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </td> 
                    </tr>
                    {{-- DEMO ENTRY 3: PENDING SCREENING --}}
                    <tr data-role="donor" data-screening-status="pending">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar purple">NM</div>
                                <div>
                                    <div class="user-name">Nurul Malik</div>
                                    <div class="user-email">nurul.m@example.com</div>
                                    <div class="user-username">@nurulM</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge role-donor">
                                <i class="fas fa-hand-holding-heart"></i> Donor
                            </span>
                        </td>
                        <td>
                            <span class="contact-info">
                                <i class="fas fa-phone"></i> +6013-112 3456
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-pending">Pending</span>
                        </td>
                        <td class="screening-cell">
                            <span class="screening-badge screening-pending" title="Awaiting blood test results.">
                                Pending
                            </span>
                        </td>
                        <td>
                            <span class="date-info">
                                <i class="far fa-calendar-alt"></i> Nov 10, 2025
                            </span>
                        </td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button class="icon-btn view-btn" title="View Details" onclick="viewUser('1002', 'donor')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn edit-btn" title="Edit" onclick="editUser('1002', 'donor')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn delete-btn" title="Delete" onclick="confirmDelete('1002', 'donor', 'Nurul Malik')"><i class="fas fa-trash"></i></button>
                                <button class="icon-btn more-btn" title="More Options"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </td> 
                    </tr>
                    {{-- END DEMO ENTRIES --}}
                    
                    @if($allUsers->count() > 0)
                        @foreach ($allUsers as $user)
                            @php
                                $status = $user->status;
                                // --- Placeholder Data Start (Replace with actual backend fields) ---
                                // Adjusting placeholder logic to skip the first 3 iterations as they are static now
                                $i = $loop->iteration + 3; 
                                $screeningStatus = $user->role == 'donor' ? (($i % 3 == 0 ? 'passed' : ($i % 3 == 1 ? 'failed' : 'pending'))) : 'na';
                                $screeningRemark = 'Incomplete documentation required.';
                                // --- Placeholder Data End ---
                            @endphp
                            <tr data-role="{{ $user->role }}" data-screening-status="{{ $screeningStatus }}">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar {{ ['teal', 'blue', 'green', 'purple', 'orange'][array_rand(['teal', 'blue', 'green', 'purple', 'orange'])] }}">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                            <div class="user-username">{{ '@' . $user->username }}</div>
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
                                        <i class="fas fa-phone"></i> {{ $user->contact }}
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
                                                @if($screeningStatus == 'failed') title="{{ $screeningRemark }}" @endif>
                                            {{ ucfirst($screeningStatus) }}
                                        </span>
                                        @if($screeningStatus == 'failed' && $screeningRemark)
                                            <div class="screening-remark-short">
                                                <i class="fas fa-exclamation-circle"></i> {{ $screeningRemark }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="screening-badge screening-na">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="date-info">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <div class="action-buttons">
                                        <button class="icon-btn view-btn" title="View Details"
                                            onclick="viewUser('{{ $user->original_id }}', '{{ $user->original_table }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button class="icon-btn edit-btn" title="Edit"
                                            onclick="editUser('{{ $user->original_id }}', '{{ $user->original_table }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="icon-btn delete-btn" title="Delete"
                                            onclick="confirmDelete('{{ $user->original_id }}', '{{ $user->original_table }}', '{{ $user->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="icon-btn more-btn" title="More Options">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
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

    <script>
        let deleteUserId, deleteUserTable;

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
            if (event.target === roleModal) {
                closeRoleModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
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
                
                // Also apply current filter to prevent showing unmatched roles
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
            document.getElementById('noResults').style.display = found ? 'none' : 'table-row';
        }

        // Filter by role (Dropdown)
        function filterByRole() {
            const select = document.getElementById('roleFilter');
            const role = select.value;
            
            // Sync with tabs
            const tabs = document.querySelectorAll('.tabs .tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            const targetTab = document.querySelector(`.tabs .tab[data-tab="${role === 'admin' || role === 'doctor' || role === 'nurse' || role === 'labtech' || role === 'shariah' ? 'staff' : role}"]`);
            if (targetTab) {
                targetTab.classList.add('active');
            } else if (role === 'all') {
                document.querySelector('.tabs .tab[data-tab="all"]').classList.add('active');
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
                } else if (role === 'staff') { // This case should theoretically not happen via dropdown, but handled for safety
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
             document.getElementById('noResults').style.display = found ? 'none' : 'table-row';
        }

        // Tab filtering
        function filterTab(type) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.currentTarget.classList.add('active'); // Use currentTarget to target the button itself

            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');
            const staffRoles = ['admin', 'doctor', 'nurse', 'labtech', 'shariah'];
            let found = false;

            // Sync with dropdown
            const roleDropdown = document.getElementById('roleFilter');
            roleDropdown.value = (type === 'donors' ? 'donor' : (type === 'parents' ? 'parent' : (type === 'staff' ? 'all' : type))); // Tabs don't match dropdown exactly, use 'all' for staff

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
                } else if (type === 'donor') {
                    isVisible = rowRole === 'donor';
                } else if (type === 'parent') {
                    isVisible = rowRole === 'parent';
                }
                
                if (isVisible && isMatch) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            }
            document.getElementById('noResults').style.display = found ? 'none' : 'table-row';
        }


        // Sort table (Keep the implementation)
        function sortTable(columnIndex) {
            const table = document.getElementById('usersTable');
            let switching = true;
            let shouldSwitch, i;
            let dir = 'asc';
            let switchcount = 0;

            while (switching) {
                switching = false;
                const rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    const x = rows[i].getElementsByTagName('TD')[columnIndex];
                    const y = rows[i + 1].getElementsByTagName('TD')[columnIndex];

                    if (dir === 'asc') {
                        if (x.textContent.toLowerCase() > y.textContent.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === 'desc') {
                        if (x.textContent.toLowerCase() < y.textContent.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount === 0 && dir === 'asc') {
                        dir = 'desc';
                        switching = true;
                        switchcount++;
                    }
                }
            }
        }

        function editUser(userId, userTable) {
            window.location.href = `/admin/users/${userTable}/${userId}/edit`;
        }


        function confirmDelete(userId, userTable, userName) {
            deleteUserId = userId;
            deleteUserTable = userTable;
            document.getElementById('deleteMessage').textContent = `Are you sure you want to delete ${userName}? This action cannot be undone.`;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function executeDelete() {
            fetch(`/admin/users/${deleteUserTable}/${deleteUserId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success';
                    successMsg.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:16px 24px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:9999;';
                    successMsg.innerHTML = '<i class="fas fa-check-circle"></i> User deleted successfully!';
                    document.body.appendChild(successMsg);
                    setTimeout(() => {
                        successMsg.remove();
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error deleting user. Please try again.');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Error deleting user. Please try again.');
            });
        }

        function viewUser(userId, userTable) {
            window.location.href = `/admin/users/${userTable}/${userId}`;
        }

        function exportUsers() {
            // Add export functionality here
            alert('Export functionality coming soon!');
        }
    </script>
@endsection