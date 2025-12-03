<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 @vite(['resources/js/app.js', 'resources/css/design.css'])
  </head>
<body class="bg-light">

<input type="checkbox" id="sidebar-toggle">

<div class="sidebar">
  <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#facultyProfileModal">
    <div class="profile-info">
      <i class="fas fa-user-circle"></i>
      <div class="profile-text">
        <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
      </div>
    </div>
  </a>

  
  <nav class="nav flex-column text-start">
      <a href="{{ route('faculty.dashboard') }}" class="nav-link ">
        <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span> 
      </a>
       <a href="{{ route('faculty.borrowedBooks') }}" class="nav-link ">
         <i class="fas fa-file-alt me-2"></i><span> My Borrowed Books</span>
     </a>
      <a href="{{ route('faculty.requestBooks') }}" class="nav-link ">
        <i class="fas fa-file-alt me-2"></i><span>Request Books</span> 
      </a>
      <a href="{{ route('faculty.announcement') }}" class="nav-link active">
        <i class="fas fa-bullhorn me-2"></i><span>Announcements</span> 
      </a>
       <a href="{{ route('faculty.notification') }}" class="nav-link">
        <i class="fas fa-solid fa-bell me-2"></i><span>Notification</span> 
      </a>
       <a href="{{ route('faculty.books') }}" class="nav-link">
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
       <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
      </div>
    </div>



    <div class="recent-announcements mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Recent Announcements</h3>
        </div>

    <div class="card shadow border-0">
        @include('components.announcement-feed', ['announcements' => $announcements])
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
@include('faculty.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
