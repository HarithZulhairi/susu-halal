@extends('layouts.doctor')

@section('title', 'Donor Candidates')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/doctor_donor-candidates.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="page-header">
        <h1>Donor Candidates</h1>
        <p>Manage and review donor candidate applications</p>
    </div>

    <div class="candidates-container">
        <div class="candidates-card">
            <div class="card-header">
                <h2>HMMC Donor Candidates</h2>
                <div class="header-actions">
                    <button class="btn-search">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </button>
                    <button class="btn-filter">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <div class="tabs">
                <button class="tab active" data-tab="all">
                    All Appointment
                    <span class="badge">69</span>
                </button>
                <button class="tab" data-tab="month">
                    This Month
                    <span class="badge">13</span>
                </button>
                <button class="tab" data-tab="pending">
                    Pending
                    <span class="badge">11</span>
                </button>
            </div>

            <div class="table-container">
                <table class="candidates-table">
                    <thead>
                        <tr>
                            <th>Candidate ID</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="candidate-id">#112</td>
                            <td class="full-name">
                                <div class="name-cell">
                                    <div class="avatar">SB</div>
                                    <span>Sarimah Binti Boroburu</span>
                                </div>
                            </td>
                            <td class="email">sarimah.boroburu@email.com</td>
                            <td>
                                <span class="status-badge pending">Pending</span>
                            </td>
                            <td class="actions">
                                <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="candidate-id">#4</td>
                            <td class="full-name">
                                <div class="name-cell">
                                    <div class="avatar">FA</div>
                                    <span>Fatimah Asi</span>
                                </div>
                            </td>
                            <td class="email">fatimah.asi@email.com</td>
                            <td>
                                <span class="status-badge pending">Pending</span>
                            </td>
                            <td class="actions">
                                <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="candidate-id">#89</td>
                            <td class="full-name">
                                <div class="name-cell">
                                    <div class="avatar">NA</div>
                                    <span>Nurul Ain Abdullah</span>
                                </div>
                            </td>
                            <td class="email">nurul.ain@email.com</td>
                            <td>
                                <span class="status-badge approved">Approved</span>
                            </td>
                            <td class="actions">
                                <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="candidate-id">#56</td>
                            <td class="full-name">
                                <div class="name-cell">
                                    <div class="avatar">SH</div>
                                    <span>Siti Hajar Ismail</span>
                                </div>
                            </td>
                            <td class="email">siti.hajar@email.com</td>
                            <td>
                                <span class="status-badge rejected">Rejected</span>
                            </td>
                            <td class="actions">
                                <button class="btn-view" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                <button class="btn-more" title="More"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="showing-info">
                    Showing 1-4 of 69 candidates
                </div>
                <div class="pagination">
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection