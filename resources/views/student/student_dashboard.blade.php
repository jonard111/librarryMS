<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </head>
<body>

<input type="checkbox" id="sidebar-toggle">

<div class="sidebar">
    <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#studentProfileModal">
      <div class="profile-info">
        <i class="fas fa-user-circle"></i>
        <div class="profile-text">
          <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
      </div>
    </a>

    <nav class="nav flex-column text-start">
      <a href="{{ route('student.dashboard') }}" class="nav-link active">
        <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
      </a>
      <a href="{{ route('student.borrowed') }}" class="nav-link">
        <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
      </a>
      <a href="{{ route('student.notifications') }}" class="nav-link">
        <i class="fas fa-bell me-2"></i><span>Notifications</span>
      </a>
      <a href="{{ route('student.books') }}" class="nav-link">
        <i class="fas fa-book-open me-2"></i><span>Books</span>
      </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link fw-bold logoutLink">
    <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
    </form>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
      </form>
    </nav>
</div>

<div class="sidebar-overlay"></div>

<div class="content flex-grow-1">

    <div class="top-header d-flex justify-content-between align-items-center mb-4 bg-white border-bottom">
      <div class="d-flex align-items-center">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none me-2">
          <i class="fas fa-bars"></i>
        </label>
        <h3 class="mb-0 fw-semibold text-success">Welcome {{ auth()->user()->first_name }}</h3>
      </div>

      <div class="d-flex align-items-center gap-2">
        <div class="text-end">
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
      </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Borrowed Books</p>
              <h3 class="mb-2">{{ $borrowedBooksCount }}</h3>
              <small class="text-muted">Currently borrowed</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-book-open"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Requests</p>
              <h3 class="mb-2">{{ $pendingRequests }}</h3>
              <small class="text-muted">Pending approvals</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-clock"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Announcements</p>
              <h3 class="mb-2">{{ $announcementsCount }}</h3>
              <small class="text-muted">Active announcements</small>
            </div>
            <div class="stats-icon">
              <i class="fas fa-bullhorn"></i>
            </div>
          </div>
        </div>
      </div>
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

    <br>

    <!-- Alerts Section -->
    @if(isset($overdueBooks) && $overdueBooks->count() > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Books</h5>
            <p class="mb-2">You have <strong>{{ $overdueBooks->count() }}</strong> overdue book(s). Please return them as soon as possible to avoid additional fines.</p>
            <ul class="mb-0">
                @foreach($overdueBooks->take(3) as $overdue)
                    <li><strong>{{ $overdue->book->title ?? 'N/A' }}</strong> - Due: {{ $overdue->due_date ? $overdue->due_date->format('M d, Y') : 'N/A' }}</li>
                @endforeach
            </ul>
            <a href="{{ route('student.borrowed') }}" class="btn btn-sm btn-outline-danger mt-2">View All</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(isset($booksDueSoon) && $booksDueSoon->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-clock me-2"></i>Books Due Soon</h5>
            <p class="mb-2">You have <strong>{{ $booksDueSoon->count() }}</strong> book(s) due within the next 3 days.</p>
            <ul class="mb-0">
                @foreach($booksDueSoon->take(3) as $due)
                    <li><strong>{{ $due->book->title ?? 'N/A' }}</strong> - Due: {{ $due->due_date ? $due->due_date->format('M d, Y') : 'N/A' }}</li>
                @endforeach
            </ul>
            <a href="{{ route('student.borrowed') }}" class="btn btn-sm btn-outline-warning mt-2">View All</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(isset($readyForPickup) && $readyForPickup->count() > 0)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Books Ready for Pickup</h5>
            <p class="mb-2">You have <strong>{{ $readyForPickup->count() }}</strong> approved book(s) ready for pickup!</p>
            <ul class="mb-0">
                @foreach($readyForPickup as $pickup)
                    <li><strong>{{ $pickup->book->title ?? 'N/A' }}</strong></li>
                @endforeach
            </ul>
            <a href="{{ route('student.borrowed') }}" class="btn btn-sm btn-outline-success mt-2">View Details</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Recent Announcements -->
    @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
        <section class="mb-4">
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0"><i class="fas fa-bullhorn me-2"></i>Recent Announcements</h3>
                <a href="{{ route('student.notifications') }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="row g-3">
                @foreach($recentAnnouncements as $announcement)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-{{ $announcement->type === 'info' ? 'info' : ($announcement->type === 'warning' ? 'warning' : 'primary') }}">
                                        {{ ucfirst($announcement->type ?? 'General') }}
                                    </span>
                                    <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                </div>
                                <h6 class="card-title">{{ $announcement->title }}</h6>
                                <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($announcement->content, 100) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

@include('student.partials.profile-modal')
</body>
</html>
