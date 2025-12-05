<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Borrowed Books</title>
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
      <a href="{{ route('faculty.dashboard') }}" class="nav-link ">
        <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span> 
      </a>
       <a href="{{ route('faculty.borrowedBooks') }}" class="nav-link active">
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
     <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">


      <div class="d-flex align-items-center gap-2">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
        <h3 class="mb-0 fw-semibold text-success">My borrowed Books</h3>
      </div>
     <div class="d-flex align-items-center gap-2">
        <div class="text-end">
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted">Management System</small>
        </div>
     <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
      </div>
    </div>

<div class="row g-4 mb-4">

  <div class="col-md-6 col-lg-3">
    <div class="dashboard-card dashboard-card-green text-center">
      <h5 class="card-title">Borrowed Books</h5>
      <p class="stat fw-bold fs-3">8</p>
      <small class="text-muted">Currently borrowed</small>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="dashboard-card dashboard-card-blue text-center">
      <h5 class="card-title">Request Books</h5>
      <p class="stat fw-bold fs-3">0</p>
      <small class="text-muted">Pending Approval</small>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="dashboard-card dashboard-card-orange text-center">
      <h5 class="card-title">Penalties</h5>
      <p class="stat fw-bold fs-3">0</p>
      <small class="text-muted">Late Returned</small>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="dashboard-card dashboard-card-purple text-center">
      <h5 class="card-title"> Approved Books
      </h5>
      <p class="stat fw-bold fs-3">0</p>
      <small class="text-muted">Access online books</small>
    </div>
  </div>

</div>

<!-- Table -->
    <div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Recent Borrowed</h5>
      <select class="form-select form-select-sm table-column-filter" data-filter-table="facultyBorrowedTable" data-filter-column="4" style="max-width: 180px;">
        <option value="">All Status</option>
        <option value="Picked Up">Picked Up</option>
        <option value="Returned">Returned</option>
      </select>
    </div>
    <div class="table-responsive table-striped">
      <table class="table table-hover table-striped align-middle filterable-table" data-filter-id="facultyBorrowedTable">
        <thead class="table-light">
      <tr>
        <th>Book Name</th>
        <th>Date/Time Borrowed</th>
        <th>Date/Time Returned</th>
        <th>Penalties</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td><span class="badge bg-danger">Pending</span></td>
      </tr>
      <tr>
       <td>None</td>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td><span class="badge bg-danger">Pending</span></td>
      </tr>
      <tr>
       <td>None</td>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td><span class="badge bg-danger">Pending</span></td>
      </tr>
      <tr>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td> <span class="badge bg-danger">Pending</span></td>
      </tr>
            <tr>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td><span class="badge bg-danger">Pending</span></td>
      </tr>
            <tr>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td>None</td>
        <td><span class="badge bg-danger">Pending</span></td>
      </tr>
    </tbody>
  </table>
</div>

    </div>

  
      </div>
  </div>
</div>
@include('faculty.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
