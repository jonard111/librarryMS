<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/design.css'])
  </head>
<body class="bg-light">

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
        <a href="{{ route('student.dashboard') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('student.borrowed') }}" class="nav-link">
            <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="nav-link active">
            <i class="fas fa-bell me-2"></i><span>Notifications</span>
        </a>
        <a href="{{ route('student.books') }}" class="nav-link">
            <i class="fas fa-book-open me-2"></i><span>Books</span>
        </a>
        <a href="{{ route('logout') }}" class="nav-link fw-bold logoutLink"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-2"></i><span>Logout</span>
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
            <div class="input-group">
                <input type="text" class="form-control" id="searchNotificationInput" placeholder="Search notifications">
                <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
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

    <!-- Notifications Section -->
    <div class="notifications-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications</h3>
        </div>

        <div class="card shadow border-0">
            <div class="list-group list-group-flush">
                @forelse($notifications ?? collect() as $notification)
                    @php
                        $postedBy = $notification->creator
                            ? $notification->creator->first_name . ' ' . $notification->creator->last_name
                            : 'System';
                        $dateLabel = optional($notification->publish_at)->diffForHumans() ?? 'Just now';
                        
                        // Type badge colors and labels
                        $typeInfo = match($notification->type ?? 'announcement') {
                            'announcement' => ['color' => 'primary', 'label' => 'Announcement', 'icon' => 'bullhorn'],
                            'penalties' => ['color' => 'danger', 'label' => 'Penalties', 'icon' => 'exclamation-triangle'],
                            'reminder' => ['color' => 'warning', 'label' => 'Reminder', 'icon' => 'bell'],
                            'alert' => ['color' => 'warning', 'label' => 'Alert', 'icon' => 'exclamation-circle'],
                            'book_update' => ['color' => 'info', 'label' => 'Book Update', 'icon' => 'book'],
                            'reservation' => ['color' => 'success', 'label' => 'Reservation', 'icon' => 'bookmark'],
                            'overdue' => ['color' => 'danger', 'label' => 'Overdue', 'icon' => 'clock'],
                            default => ['color' => 'secondary', 'label' => 'Notification', 'icon' => 'bell']
                        };
                    @endphp
                    <div class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center me-3 flex-grow-1 overflow-hidden">
                            <div class="me-3 overflow-hidden">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="mb-0 text-truncate fw-bold">{{ $notification->title }}</h6>
                                    <span class="badge bg-{{ $typeInfo['color'] }} flex-shrink-0">
                                        <i class="fas fa-{{ $typeInfo['icon'] }} me-1"></i>{{ $typeInfo['label'] }}
                                    </span>
                                </div>
                                <span class="text-muted small text-truncate d-none d-lg-inline-block">
                                    {{ $postedBy }} • {{ optional($notification->publish_at)->format('M d, Y') }} • {{ \Illuminate\Support\Str::limit(strip_tags($notification->body ?? ''), 160) }}
                                </span>
                                <span class="text-muted small d-lg-none">
                                    {{ $dateLabel }}
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary flex-shrink-0 ms-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#notificationModal{{ $notification->id }}">
                            <i class="fas fa-eye me-1"></i>View Details
                        </button>
                    </div>
                @empty
                    <div class="list-group-item text-center py-5 text-muted">
                        <i class="fas fa-info-circle me-2"></i>No notifications yet. You're all caught up!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Notification Details Modals -->
@foreach($notifications ?? collect() as $notification)
    @php
        $postedBy = $notification->creator
            ? $notification->creator->first_name . ' ' . $notification->creator->last_name
            : 'System';
        $publishDate = optional($notification->publish_at)->format('M d, Y');
        $publishTime = optional($notification->publish_at)->format('h:i A');
        $audience = $notification->audience ? implode(', ', array_map('ucfirst', $notification->audience)) : 'All Users';
        
        $typeInfo = match($notification->type ?? 'announcement') {
            'announcement' => ['color' => 'primary', 'label' => 'Announcement', 'icon' => 'bullhorn'],
            'penalties' => ['color' => 'danger', 'label' => 'Penalties', 'icon' => 'exclamation-triangle'],
            'reminder' => ['color' => 'warning', 'label' => 'Reminder', 'icon' => 'bell'],
            'alert' => ['color' => 'warning', 'label' => 'Alert', 'icon' => 'exclamation-circle'],
            'book_update' => ['color' => 'info', 'label' => 'Book Update', 'icon' => 'book'],
            'reservation' => ['color' => 'success', 'label' => 'Reservation', 'icon' => 'bookmark'],
            'overdue' => ['color' => 'danger', 'label' => 'Overdue', 'icon' => 'clock'],
            default => ['color' => 'secondary', 'label' => 'Notification', 'icon' => 'bell']
        };
    @endphp
    <div class="modal fade" id="notificationModal{{ $notification->id }}" tabindex="-1" aria-labelledby="notificationModalLabel{{ $notification->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel{{ $notification->id }}">
                        <i class="fas fa-{{ $typeInfo['icon'] }} me-2 text-{{ $typeInfo['color'] }}"></i>{{ $notification->title }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="badge bg-{{ $typeInfo['color'] }} me-2">
                            <i class="fas fa-{{ $typeInfo['icon'] }} me-1"></i>{{ $typeInfo['label'] }}
                        </span>
                        @if($notification->expires_at)
                            <span class="badge bg-secondary">
                                <i class="fas fa-calendar-times me-1"></i>Expires: {{ $notification->expires_at->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="fas fa-user me-2"></i><strong>Posted by:</strong> {{ $postedBy }}
                        </p>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar me-2"></i><strong>Published:</strong> {{ $publishDate }} at {{ $publishTime }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-users me-2"></i><strong>Audience:</strong> {{ $audience }}
                        </p>
                    </div>
                    
                    <hr>
                    
                    <div class="notification-content">
                        <h6 class="mb-2">Details:</h6>
                        <div class="text-muted">
                            {!! nl2br(e($notification->body)) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@include('student.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for notifications
    document.addEventListener('DOMContentLoaded', function() {
        const searchNotificationInput = document.getElementById('searchNotificationInput');
        if (searchNotificationInput) {
            searchNotificationInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const notificationCards = document.querySelectorAll('.card, .list-group-item');
                
                notificationCards.forEach(card => {
                    const title = card.querySelector('h5, .card-title, strong')?.textContent.toLowerCase() || '';
                    const content = card.textContent.toLowerCase();
                    
                    const matches = title.includes(searchTerm) || 
                                   content.includes(searchTerm);
                    
                    card.style.display = matches ? '' : 'none';
                });
            });
        }
    });
</script>
</body>
</html>
