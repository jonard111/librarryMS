<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    @vite(['resources/js/app.js', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    </head>
<body class="bg-light">

<input type="checkbox" id="sidebar-toggle">

<div class="sidebar">
    <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#studentProfileModal">
        <div class="profile-info">
            <i class="fas fa-user-circle"></i>
            <div class="profile-text">
                <h2>{{ $user->first_name }} {{ $user->last_name }}</h2>
            </div>
        </div>
    </a>

    <nav class="nav flex-column text-start">
        <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('student.borrowed') }}" class="nav-link {{ request()->routeIs('student.borrowed') ? 'active' : '' }}">
            <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="nav-link {{ request()->routeIs('student.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell me-2"></i><span>Notification</span>
        </a>
        <a href="{{ route('student.books') }}" class="nav-link {{ request()->routeIs('student.books') ? 'active' : '' }}">
            <i class="fas fa-book-open me-2"></i><span>Books</span>
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
        <div class="d-flex align-items-center gap-2">
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
            <h3 class="mb-0 fw-semibold text-success">My Profile</h3>
        </div>
        <div class="d-flex align-items-center">
            <div class="text-end">
                <span class="fw-bold text-success d-block">Library MS</span>
                <small class="text-muted">Management System</small>
            </div>
            <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
        </div>
    </div>

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-user-circle text-success mb-3" style="font-size:4rem;"></i>
                        <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <div class="list-group text-start small">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Role</span>
                                <span class="badge bg-success text-uppercase">{{ $user->role }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Account Status</span>
                                <span class="badge bg-primary text-uppercase">{{ $user->account_status }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Member Since</span>
                                <span>{{ optional($user->registration_date)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Update Information</h5>
                        <form method="POST" action="{{ route('student.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">New Password <span class="text-muted">(optional)</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@include('student.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




