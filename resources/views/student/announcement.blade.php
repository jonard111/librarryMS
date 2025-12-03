<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements</title>
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
        <a href="{{ url('/student/dashboard') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
        </a>
        <a href="{{ url('/student/borrowed_books') }}" class="nav-link">
            <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
        </a>
        <a href="{{ url('/student/announcement') }}" class="nav-link active">
            <i class="fas fa-bullhorn me-2"></i><span>Announcements</span>
        </a>
        <a href="{{ url('/student/notification') }}" class="nav-link">
            <i class="fas fa-bell me-2"></i><span>Notification</span>
        </a>
        <a href="{{ url('/student/books') }}" class="nav-link">
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
        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
            <div class="input-group">
                <input type="text" class="form-control" id="searchAnnouncementInput" placeholder="Search announcement">
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

    <div class="recent-announcements mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Recent Announcements</h3>
        </div>

        <div class="card shadow border-0">
            <div class="list-group list-group-flush" id="announcementList">

                @php
                    $announcements = [
                        ['title' => 'Library Closed on Holiday', 'date' => 'Sep 28, 2025', 'status' => 'Active', 'details' => '#', 'badge' => 'success', 'desc' => 'Posted by Admin • Sep 28, 2025 • The library will be closed on Oct 1...'],
                        ['title' => 'Extended Hours for Finals Week', 'date' => 'Sep 20, 2025', 'status' => 'Active', 'details' => '#', 'badge' => 'success', 'desc' => 'Posted by Admin • Sep 20, 2025 • Extended hours until 10 PM...'],
                        ['title' => 'New Book Donation Drive', 'date' => 'Sep 01, 2025', 'status' => 'Expired', 'details' => '#', 'badge' => 'secondary', 'desc' => 'Posted by Admin • Sep 01, 2025 • Collected over 500 books...']
                    ];
                @endphp

                @foreach ($announcements as $announcement)
                    <div class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center me-3" style="flex-grow: 1; min-width: 0;">
                            <div class="me-3">
                                <h6 class="mb-0 text-truncate fw-bold" style="max-width: 250px;">
                                    {{ $announcement['title'] }}
                                </h6>
                                <span class="text-muted small text-truncate d-none d-lg-inline-block">
                                    {{ $announcement['desc'] }}
                                </span>
                                <span class="text-muted small d-lg-none">
                                    {{ $announcement['date'] }}
                                </span>
                            </div>
                            <span class="badge bg-{{ $announcement['badge'] }} flex-shrink-0 d-none d-md-inline">{{ $announcement['status'] }}</span>
                        </div>

                        <a href="{{ $announcement['details'] }}" class="btn btn-sm btn-outline-secondary flex-shrink-0 ms-2">
                            Details →
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for announcements
    document.addEventListener('DOMContentLoaded', function() {
        const searchAnnouncementInput = document.getElementById('searchAnnouncementInput');
        if (searchAnnouncementInput) {
            searchAnnouncementInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const announcementCards = document.querySelectorAll('.card, .list-group-item, table tbody tr');
                
                announcementCards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    const matches = cardText.includes(searchTerm);
                    card.style.display = matches ? '' : 'none';
                });
            });
        }
    });
</script>
@include('student.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
