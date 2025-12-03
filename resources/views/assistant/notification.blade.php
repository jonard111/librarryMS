<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification</title>
  @vite(['resources/css/design.css'])
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <a href="{{ route('assistant.notification') }}" class="nav-link active">
        <i class="fas fa-solid fa-bell me-2"></i><span>Notification</span> 
    </a>
    <a href="{{ route('assistant.users') }}" class="nav-link">
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
      <div class="d-flex align-items-center gap-2">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
        <h3 class="mb-0 fw-semibold text-success">Notifications</h3>
      </div>

      <div class="d-flex align-items-center gap-2">
        <div class="text-end">
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
      </div>
  </div>

  <div class="card shadow border-0">
    <div class="list-group list-group-flush" id="notificationList">
      @forelse($notifications as $notification)
        @php
            $badge = $notification->badgeClass();
            $statusLabel = $notification->statusLabel();
            $postedBy = $notification->creator
                ? $notification->creator->first_name . ' ' . $notification->creator->last_name
                : 'System';
            $dateLabel = optional($notification->publish_at)->diffForHumans() ?? 'Just now';
        @endphp
        <div class="list-group-item list-group-item-action py-3 d-flex align-items-center">
          <div class="d-flex align-items-center me-3 flex-grow-1 overflow-hidden">
            <div class="me-3 overflow-hidden">
              <h6 class="mb-0 text-truncate fw-bold">{{ $notification->title }}</h6>
              <span class="text-muted small text-truncate d-none d-lg-inline-block">
                {{ $postedBy }} • {{ optional($notification->publish_at)->format('M d, Y') }} • {{ \Illuminate\Support\Str::limit(strip_tags($notification->body), 160) }}
              </span>
              <span class="text-muted small d-lg-none">{{ $dateLabel }}</span>
            </div>
            <span class="badge bg-{{ $badge }} flex-shrink-0 d-none d-md-inline ms-auto">{{ $statusLabel }}</span>
          </div>
        </div>
      @empty
        <div class="list-group-item text-center py-5 text-muted">
          No notifications yet.
        </div>
      @endforelse
    </div>
  </div>
</div>

@include('assistant.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




