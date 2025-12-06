<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assistant Dashboard</title>
     @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  </head>
<body>

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
    <a href="{{ route('assistant.dashboard') }}" class="nav-link active">
      <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span> 
    </a>
     <a href="{{ route('assistant.manageBooks') }}" class="nav-link">
      <i class="fas fa-book-open me-2"></i><span> Manage Books</span>
    </a>
    <a href="{{ route('assistant.student') }}" class="nav-link">
      <i class="fas fa-users me-2"></i><span>Student Records</span> 
    </a> 
     <a href="{{ route('assistant.reservation') }}" class="nav-link">
      <i class="fas fa-bookmark me-2"></i><span>Reservation</span> 
    </a>
    <a href="{{ route('assistant.notification') }}" class="nav-link">
        <i class="fas fa-solid fa-bell me-2"></i><span>Notification</span> 
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
        {{-- Updated color class to match Coastal Blue theme --}}
        <h3 class="mb-0 fw-semibold text-success">Welcome Assistant</h3>
      </div>

    <div class="d-flex align-items-center gap-2">
        <div class="text-end">
          {{-- Updated color class to match Coastal Blue theme --}}
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
      </div>
    </div>

   <div class="row g-4 mb-4">
      {{-- 1. Total Books --}}
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Total Books</p>
              <h3 class="mb-2">{{ $totalBooks }}</h3>
              <small class="text-muted">Available in library</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-book"></i>
            </div>
          </div>
        </div>
      </div>

      {{-- 2. Active Reservations --}}
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Active Reservations</p>
              <h3 class="mb-2">{{ $activeReservations }}</h3>
              <small class="text-muted">Pending & approved</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-file-alt"></i>
            </div>
          </div>
        </div>
      </div>
    
      {{-- 3. Books Borrowed --}}
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Books Borrowed</p>
              <h3 class="mb-2">{{ $booksBorrowed }}</h3>
              <small class="text-muted">Currently borrowed</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-book-open"></i>
            </div>
          </div>
        </div>
      </div>
    
      {{-- 4. E-Books --}}
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">E-Books</p>
              <h3 class="mb-2">{{ $totalEbooks }}</h3>
              <small class="text-muted">Available e-books</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-laptop"></i>
            </div>
          </div>
        </div>
      </div>
   </div>

{{-- ENHANCEMENT: Action/Quick Link Cards --}}
{{-- Note: These statistics are ideally derived from running the same queries found in AssistantController@reservation to be fully dynamic. Assuming the main stats are accurate, these link to the relevant page. --}}
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('assistant.reservation') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm stats-card dashboard-card-blue">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 fw-bold">Pending Requests</p>
                        <h3 class="mb-2 text-primary">{{ $activeReservations }}</h3>
                        <small class="text-muted">Needs immediate approval</small>
                    </div>
                    <div class="stats-icon dashboard-icon-blue">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="{{ route('assistant.student') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm stats-card dashboard-card-orange">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 fw-bold">Overdue Books</p>
                        {{-- Note: Requires running the overdue count query from AssistantController@reservation --}}
                        <h3 class="mb-2 text-danger">{{ \App\Models\BookReservation::where('status', 'picked_up')->whereNull('return_date')->where('due_date', '<', now())->count() }}</h3>
                        <small class="text-muted">Students requiring attention</small>
                    </div>
                    <div class="stats-icon dashboard-icon-orange">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


<div class="card shadow-sm card-body mt-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Recent Library Transactions</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Activity</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- FIXED: Loop through recentActivities passed from the controller --}}
                    @forelse($recentActivities as $activity)
                        @php
                            // Mapping logic for icons and colors based on activity type
                            $activityMap = match($activity->activity_type) {
                                'book_borrowed'     => ['icon' => 'book-reader', 'color' => 'primary'],
                                'book_returned'     => ['icon' => 'undo-alt', 'color' => 'success'],
                                'fine_settled'      => ['icon' => 'wallet', 'color' => 'warning'],
                                'new_inventory'     => ['icon' => 'book-medical', 'color' => 'info'],
                                'user_update'       => ['icon' => 'user-edit', 'color' => 'secondary'],
                                'reservation_approved' => ['icon' => 'check-circle', 'color' => 'success'],
                                'reservation_pending'  => ['icon' => 'clock', 'color' => 'info'],
                                default             => ['icon' => 'info-circle', 'color' => 'muted'],
                            };
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('M d, H:i A') }}</td>
                            <td>
                                <i class="fas fa-{{ $activityMap['icon'] }} text-{{ $activityMap['color'] }}"></i> 
                                {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                            </td>
                            <td>
                                {{ $activity->details }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No recent transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('assistant.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>