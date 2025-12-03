<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../Css/book.css">
 @vite(['resources/js/app.js', 'resources/css/design.css'])
  </head>
<body>

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
      <a href="{{ route('faculty.dashboard') }}" class="nav-link active">
        <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span> 
      </a>
       <a href="{{ route('faculty.borrowedBooks') }}" class="nav-link ">
         <i class="fas fa-file-alt me-2"></i><span> My Borrowed Books</span>
     </a>
      <a href="{{ route('faculty.requestBooks') }}" class="nav-link ">
        <i class="fas fa-file-alt me-2"></i><span>Request Books</span> 
      </a>
      <a href="{{ route('faculty.announcement') }}" class="nav-link ">
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

    <div class="top-header d-flex justify-content-between align-items-center mb-4 bg-white border-bottom">
      <div class="d-flex align-items-center">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none me-2">
          <i class="fas fa-bars"></i>
        </label>
        <h3 class="mb-0 fw-semibold text-success">Welcome Faculty</h3>
      </div>

      <div class="d-flex align-items-center gap-2">
        <div class="text-end">
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted">Management System</small>
        </div>
         <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
      </div>
    </div>

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

   <section >
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3 ">
                <h3 class="mb-2 mb-md-0">My Reading List</h3>
            </div>
             <div class="row row-cols-2 row-cols-md-6 g-3">
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover1.jpg" class="card-img-top" alt="How to Study Smart" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">How to Study Smart</h5>
                            <small class="card-subtitle text-muted">Adam Robinson</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Continue Reading</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Mindset" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Mindset</h5>
                            <small class="card-subtitle text-muted">Carol S. Dweck</small>
                        </div>
                          <button class="btn btn-outline-success btn-sm m-2">Continue Reading</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover3.jpg" class="card-img-top" alt="Make It Stick" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Make It Stick</h5>
                            <small class="card-subtitle text-muted">Peter C. Brown</small>
                        </div>
                          <button class="btn btn-outline-success btn-sm m-2">Continue Reading</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Learning Habit" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">The Learning Habit</h5>
                            <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Continue Reading</button>
                    </div>
                </div>
            </div>
        </section>

</div>

@include('faculty.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
