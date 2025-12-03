<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

  <input type="checkbox" id="sidebar-toggle">

  <!-- Sidebar -->
  <div class="sidebar">
      <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
          <div class="profile-info">
              <i class="fas fa-user-circle"></i>
              <div class="profile-text">
                  <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
              </div>
          </div>
      </a>

      <nav class="nav flex-column text-start">
        <a href="{{ route('head.dashboard') }}" class="nav-link">
          <i class="fas fa-chart-bar me-2"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('head.studentRecord') }}" class="nav-link">
          <i class="fas fa-users me-2"></i> <span>Student Record</span>
        </a>
        <a href="{{ route('head.announcement') }}" class="nav-link active">
          <i class="fas fa-bullhorn me-2"></i> <span>Announcements</span>
        </a>
        <a href="{{ route('head.reports') }}" class="nav-link">
          <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
        </a>
        <a href="{{ route('head.books') }}" class="nav-link">
          <i class="fas fa-book-open me-2"></i> <span>Books</span>
        </a>
        <a href="{{ route('head.reservation') }}" class="nav-link">
          <i class="fas fa-bookmark me-2"></i> <span>Reservation</span>
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

  <!-- Content -->
  <div class="content flex-grow-1">

    <!-- Top Header -->
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
      <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
          <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
          <div class="input-group">
              <input type="text" class="form-control" id="searchAnnouncementInput" placeholder="Search announcement">
              <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
          </div>
      </div>

      <div class="d-flex align-items-center gap-2">
          <div class="d-flex flex-column text-end">
              <span class="fw-bold text-success d-block">Library MS</span> 
              <small class="text-muted" style="font-size:0.85rem;">Management System</small>
          </div>
          <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
      </div>
    </div>

    <!-- Overview Cards -->
    <div class="container">
      @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      @if ($errors->any())
          <div class="alert alert-danger">
              <strong>Something went wrong:</strong>
              <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
          <h5 class="mb-2 mb-md-0">Announcements</h5>
          <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
              <i class="fas fa-plus-circle"></i> Create Announcement
          </button>
      </div>

      @php
          $activeCount = $announcements->filter->isActive()->count();
          $expiredCount = $announcements->count() - $activeCount;
      @endphp

      <div class="card shadow border-0 p-3 mb-4">
        <div class="card-body">
          <h6 class="mb-3">Announcements Overview</h6>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <div class="text-muted small">Active</div>
              <div style="font-weight:600;">{{ $activeCount }}</div>
            </div>
            <span class="status-pill status-active text-success fw-bold">Active</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Expired</div>
              <div style="font-weight:600;">{{ $expiredCount }}</div>
            </div>
            <span class="status-pill status-expired text-danger fw-bold">Expired</span>
          </div>
        </div>
      </div>

      <!-- Recent Announcements -->
      <h5 class="mb-3">Recent Announcements</h5>
      <div class="card shadow border-0">
        @include('components.announcement-feed', ['announcements' => $announcements])
      </div>
    </div>

    <!-- Create Announcement Modal - Vue Component -->
<div id="app">
    <announcement-modal
        store-url="{{ route('head.announcement.store') }}"
        :audience-options='@json($audienceOptions)'
        modal-id="createAnnouncementModal"
    ></announcement-modal>
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
@include('head.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
