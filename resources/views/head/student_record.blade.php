<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Record</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  </head>
<body>

  <input type="checkbox" id="sidebar-toggle" />

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
        <div class="text-center mb-4 profile-info">
            <i class="fas fa-user-circle fa-3x mb-2"></i> 
            <h2 class="mt-2">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
    </a>
    <nav class="nav flex-column text-start">
      <a href="{{ route('head.dashboard') }}" class="nav-link">
        <i class="fas fa-chart-bar me-2"></i> <span>Dashboard</span>
      </a>
      <a href="{{ route('head.studentRecord') }}" class="nav-link active">
        <i class="fas fa-users me-2"></i> <span>Student Record</span>
      </a>
      <a href="{{ route('head.announcement') }}" class="nav-link">
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

  <div class="content flex-grow-1">
    <!-- Top Header -->
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
      <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
        <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
        <div class="input-group">
          <input type="text" class="form-control" id="searchStudentInput" placeholder="Search by name or ID">
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

    <div class="sidebar-overlay"></div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
      <h3 class="mb-0 fw-semibold text-success">Manage Student Record</h3>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Total Student</p>
              <h3 class="mb-2">{{ $totalStudents }}</h3>
              <div class="d-flex align-items-center">
                <span class="{{ $totalChange >= 0 ? 'text-success' : 'text-danger' }} small">{{ $totalChange >= 0 ? '+' : '' }}{{ $totalChange }}%</span>
                <span class="text-muted small ms-1">vs last month</span>
              </div>
            </div>
            <div class="stats-icon">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Active Student</p>
              <h3 class="mb-2">{{ $activeStudents }}</h3>
              <div class="d-flex align-items-center">
                <span class="{{ $activeChange >= 0 ? 'text-success' : 'text-danger' }} small">{{ $activeChange >= 0 ? '+' : '' }}{{ $activeChange }}%</span>
                <span class="text-muted small ms-1">vs last month</span>
              </div>
            </div>
            <div class="stats-icon">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Inactive Students</p>
              <h3 class="mb-2">{{ $inactiveStudents }}</h3>
              <div class="d-flex align-items-center">
                <span class="{{ $inactiveChange >= 0 ? 'text-success' : 'text-danger' }} small">{{ $inactiveChange >= 0 ? '+' : '' }}{{ $inactiveChange }}%</span>
                <span class="text-muted small ms-1">vs last month</span>
              </div>
            </div>
            <div class="stats-icon">
              <i class="fas fa-user-times"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">New Students</p>
              <h3 class="mb-2">{{ $newStudents }}</h3>
              <div class="d-flex align-items-center">
                <span class="{{ $newChange >= 0 ? 'text-success' : 'text-danger' }} small">{{ $newChange >= 0 ? '+' : '' }}{{ $newChange }}%</span>
                <span class="text-muted small ms-1">vs last month</span>
              </div>
            </div>
            <div class="stats-icon">
              <i class="fas fa-calendar-plus"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Borrow Record Table -->
    <div class="card card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Borrow Record</h5>
        <div class="d-flex gap-2">
          <select class="form-select form-select-sm table-column-filter" data-filter-table="headStudentTable" data-filter-column="5" style="max-width: 200px;">
            <option value="">All Status</option>
            <option value="Unpaid">Unpaid</option>
            <option value="Paid">Paid</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle filterable-table" data-filter-id="headStudentTable">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Borrowed</th>
              <th>Overdue</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($borrowingRecords as $record)
              <tr>
                <td>{{ $record['user']->userId }}</td>
                <td>{{ $record['user']->first_name }} {{ $record['user']->last_name }}</td>
                <td>{{ $record['user']->email }}</td>
                <td>{{ $record['borrowed_count'] }}</td>
                <td>{{ $record['overdue_count'] }}</td>
                <td>
                  @if($record['has_overdue'])
                    <span class="badge bg-danger">Unpaid</span>
                  @else
                    <span class="badge bg-success">Paid</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group" role="group">
                    @if($record['has_overdue'])
                      <button class="btn btn-sm btn-outline-success" title="Mark as Paid">
                        <i class="fas fa-check-circle me-1"></i> Mark as Paid
                      </button>
                    @else
                      <button class="btn btn-sm btn-outline-primary" title="View Receipt">
                        <i class="fas fa-receipt me-1"></i> Receipt
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">No borrowing records found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Search functionality for student records
    document.addEventListener('DOMContentLoaded', function() {
        const searchStudentInput = document.getElementById('searchStudentInput');
        if (searchStudentInput) {
            searchStudentInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('table tbody tr');
                
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const matches = rowText.includes(searchTerm);
                    row.style.display = matches ? '' : 'none';
                });
            });
        }
    });
  </script>
@include('head.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
