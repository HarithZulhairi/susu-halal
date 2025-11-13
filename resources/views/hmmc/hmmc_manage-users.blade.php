@extends('layouts.hmmc')

@section('title', 'User Management')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hmmc_manage-users.css') }}">

    <div class="main-content">
        <div class="page-header">
            <div class="header-top">
                <h1>User Management</h1>
                <div class="header-actions">
                    <button class="btn-export">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button class="btn-add-user" onclick="openRoleModal()">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Users</h3>
                    <div class="stat-number">248</div>
                    <div class="stat-change positive">↑ 12% from last month</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Active Users</h3>
                    <div class="stat-number">195</div>
                    <div class="stat-change positive">↑ 8% from last month</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending Approval</h3>
                    <div class="stat-number">12</div>
                    <div class="stat-change neutral">↑ 3 new today</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="stat-content">
                    <h3>Inactive Users</h3>
                    <div class="stat-number">41</div>
                    <div class="stat-change negative">↓ 5% from last month</div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-container">
            <div class="table-header">
                <h2>All Users</h2>
                <div class="table-controls">
                    <button class="btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn-more-options">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active">All Users <span class="tab-count">248</span></button>
                <button class="tab">Donors <span class="tab-count">156</span></button>
                <button class="tab">Recipients <span class="tab-count">68</span></button>
                <button class="tab">Staff <span class="tab-count">24</span></button>
            </div>

            <!-- Table -->
            <table class="records-table">
                <thead>
                    <tr>
                        <th>USER</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>LAST LOGIN</th>
                        <th>REGISTRATION DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar teal">SA</div>
                                <div>
                                    <div class="user-name">Sarah Ahmad</div>
                                    <div class="user-email">sarah.ahmad@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge role-donor">Donor</span></td>
                        <td><span class="badge status-active">Active</span></td>
                        <td>May 15, 2024</td>
                        <td>Jan 12, 2024</td>
                        <td class="actions">
                            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar orange">MK</div>
                                <div>
                                    <div class="user-name">Maryam Khalil</div>
                                    <div class="user-email">maryam.kh@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge role-recipient">Recipient</span></td>
                        <td><span class="badge status-active">Active</span></td>
                        <td>May 14, 2024</td>
                        <td>Feb 05, 2024</td>
                        <td class="actions">
                            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar purple">FH</div>
                                <div>
                                    <div class="user-name">Fatima Hassan</div>
                                    <div class="user-email">fatima.h@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge role-donor">Donor</span></td>
                        <td><span class="badge status-pending">Pending</span></td>
                        <td>Never</td>
                        <td>May 10, 2024</td>
                        <td class="actions">
                            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar pink">AJ</div>
                                <div>
                                    <div class="user-name">Aisha Jabbar</div>
                                    <div class="user-email">aisha.j@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge role-staff">Staff</span></td>
                        <td><span class="badge status-active">Active</span></td>
                        <td>May 15, 2024</td>
                        <td>Dec 18, 2023</td>
                        <td class="actions">
                            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar cyan">NR</div>
                                <div>
                                    <div class="user-name">Noor Rahman</div>
                                    <div class="user-email">noor.rahman@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge role-donor">Donor</span></td>
                        <td><span class="badge status-inactive">Inactive</span></td>
                        <td>Apr 28, 2024</td>
                        <td>Mar 22, 2024</td>
                        <td class="actions">
                            <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                            <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                        <div class="role-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Hmmc Admin</h3>
                        <p>Manage User</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'parent']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-baby"></i>
                        </div>
                        <h3>Parent</h3>
                        <p>Milk donor or recipient</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'shariah']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-book-quran"></i>
                        </div>
                        <h3>Shariah Committee</h3>
                        <p>Islamic compliance expert</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'nurse']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-user-nurse"></i>
                        </div>
                        <h3>Nurse</h3>
                        <p>Healthcare professional</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'doctor']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3>Doctor</h3>
                        <p>Medical practitioner</p>
                    </div>
                </a>

                <a href="{{ route('hmmc.create-new-user', ['role' => 'labtech']) }}" class="role-card">
                    <div class="role-card-content">
                        <div class="role-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>Lab Technician</h3>
                        <p>Laboratory specialist</p>
                    </div>
                </a>
            </div>

            <button class="btn-cancel-modal" onclick="closeRoleModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </div>

    <script>
        function openRoleModal() {
            document.getElementById('roleModal').style.display = 'flex';
        }

        function closeRoleModal() {
            document.getElementById('roleModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('roleModal');
            if (event.target === modal) {
                closeRoleModal();
            }
        }
    </script>
@endsection