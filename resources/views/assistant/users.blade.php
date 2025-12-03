<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant Users</title>
    @vite(['resources/js/app.js', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    </head>
<body class="bg-light">

<input type="checkbox" id="sidebar-toggle">

<div class="sidebar">
    <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#assistantProfileModal">
        <div class="profile-info">
            <i class="fas fa-user-circle"></i>
            <div class="profile-text">
                <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
            </div>
        </div>
    </a>

    <nav class="nav flex-column text-start">
        <a href="{{ route('assistant.dashboard') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('assistant.manageBooks') }}" class="nav-link">
            <i class="fas fa-book-open me-2"></i><span>Manage Books</span>
        </a>
        <a href="{{ route('assistant.student') }}" class="nav-link">
            <i class="fas fa-users me-2"></i><span>Student Records</span>
        </a>
        <a href="{{ route('assistant.reservation') }}" class="nav-link">
            <i class="fas fa-bookmark me-2"></i><span>Reservation</span>
        </a>
        <a href="{{ route('assistant.notification') }}" class="nav-link">
            <i class="fas fa-bell me-2"></i><span>Notification</span>
        </a>
        <a href="{{ route('assistant.users') }}" class="nav-link active">
            <i class="fas fa-id-card me-2"></i><span>Users</span>
        </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link fw-bold logoutLink">
    <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
    </form>
    </nav>
</div>

<div class="sidebar-overlay"></div>

<div class="content flex-grow-1">
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
            <div>
                <h3 class="mb-0 fw-semibold text-success">User Directory</h3>
                <small class="text-muted">Track active accounts and quick actions</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="text-end">
                <span class="fw-bold text-success d-block">Library MS</span>
                <small class="text-muted">Management System</small>
            </div>
            <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-3 justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Quick Filters</h5>
                <small class="text-muted">Narrow down results instantly</small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-success btn-sm">All</button>
                <button class="btn btn-outline-success btn-sm">Students</button>
                <button class="btn btn-outline-success btn-sm">Faculty</button>
                <button class="btn btn-outline-success btn-sm">Assistants</button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Active Users</h5>
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-user-plus me-2"></i>Invite User
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Active</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            ['name' => 'Jonard Basillote', 'email' => 'jonard@dnsc.edu.ph', 'role' => 'Assistant', 'status' => 'Online', 'active' => '2 mins ago'],
                            ['name' => 'Ruel Parsan', 'email' => 'ruel@dnsc.edu.ph', 'role' => 'Student', 'status' => 'Pending', 'active' => '1 day ago'],
                            ['name' => 'John Paul Mendoza', 'email' => 'johnpaul@dnsc.edu.ph', 'role' => 'Faculty', 'status' => 'Offline', 'active' => '3 hrs ago'],
                        ] as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td><span class="badge bg-primary">{{ $user['role'] }}</span></td>
                            <td>
                                @if($user['status'] === 'Online')
                                    <span class="badge bg-success">Online</span>
                                @elseif($user['status'] === 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </td>
                            <td>{{ $user['active'] }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-success"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-outline-danger"><i class="fas fa-ban"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('assistant.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

