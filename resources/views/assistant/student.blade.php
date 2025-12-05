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
    <a href="{{ route('assistant.student') }}" class="nav-link active">
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

    <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
        <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
        <div class="input-group">
            <input type="text" class="form-control" id="searchStudentInput" placeholder="Search by name or ID">
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

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    <h3>Manage Student Record</h3>
    
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
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
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
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
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
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
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
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
</div>

<!-- Additional Statistics: Books & Fines -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Books Currently Borrowed</p>
                        <h3 class="mb-2">{{ $booksCurrentlyBorrowed ?? 0 }}</h3>
                        <small class="text-muted">Active loans</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-book text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Overdue Books</p>
                        <h3 class="mb-2 text-danger">{{ $overdueBooksCount ?? 0 }}</h3>
                        <small class="text-muted">Past due date</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Fines Collected</p>
                        <h3 class="mb-2 text-success">₱{{ number_format($totalFinesCollected ?? 0, 2) }}</h3>
                        <small class="text-muted">This month</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-coins text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 dashboard-card shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Pending Fines</p>
                        <h3 class="mb-2 text-warning">₱{{ number_format($pendingFines ?? 0, 2) }}</h3>
                        <small class="text-muted">Unpaid fines</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Student Overdue</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm table-column-filter" data-filter-table="studentTable" data-filter-column="6" style="max-width: 200px;">
                <option value="">All Status</option>
                <option value="Payment Required">Payment Required</option>
                <option value="Overdue">Overdue</option>
                <option value="No Fines">No Fines</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle filterable-table" data-filter-id="studentTable">
            <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Book Title</th>
                  <th>Due Date</th>
                  <th>Fine Due</th>
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
                    <td>
                      <strong>{{ $record['book']->title ?? 'N/A' }}</strong>
                      @if($record['book']->author)
                        <br><small class="text-muted">by {{ $record['book']->author }}</small>
                      @endif
                    </td>
                    <td>
                      @if($record['due_date'])
                        {{ \Carbon\Carbon::parse($record['due_date'])->format('M d, Y') }}
                        @if($record['is_overdue'])
                          <br><small class="text-danger">
                            {{ \Carbon\Carbon::parse($record['due_date'])->diffForHumans() }}
                          </small>
                        @endif
                      @else
                        <span class="text-muted">N/A</span>
                      @endif
                    </td>
                    <td>
                      @if($record['fine_due'] > 0)
                        <span class="text-danger fw-bold">₱{{ number_format($record['fine_due'], 2) }}</span>
                      @else
                        <span class="text-muted">₱0.00</span>
                      @endif
                    </td>
                    <td>
                      @if($record['requires_payment'])
                        <span class="badge bg-danger">Payment Required</span>
                      @elseif($record['is_overdue'])
                        <span class="badge bg-warning">Overdue</span>
                      @else
                        <span class="badge bg-success">No Fines</span>
                      @endif
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        @if($record['requires_payment'])
                          <form action="{{ route('assistant.reservation.settleFine', $record['reservation']->id) }}" 
                                method="POST" 
                                class="d-inline settle-fine-form"
                                id="settleForm{{ $record['reservation']->id }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-success settle-btn" 
                                    data-reservation-id="{{ $record['reservation']->id }}"
                                    data-fine-amount="{{ number_format($record['fine_due'], 2) }}"
                                    title="Settle Fine: ₱{{ number_format($record['fine_due'], 2) }}">
                              <i class="fas fa-check-circle me-1"></i> Settle (₱{{ number_format($record['fine_due'], 2) }})
                            </button>
                          </form>
                        @else
                          <span class="text-muted">No action needed</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">No borrowing records found.</td>
                  </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Returned Books Table -->
<div class="card card-body mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Returned Books History</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm table-column-filter" data-filter-table="returnedBooksTable" data-filter-column="6" style="max-width: 200px;">
                <option value="">All Books</option>
                <option value="With Fine">With Fine</option>
                <option value="No Fine">No Fine</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle filterable-table" data-filter-id="returnedBooksTable">
            <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Book Title</th>
                  <th>Borrowed Date</th>
                  <th>Returned Date</th>
                  <th>Fine Status</th>
                  <th>Fine Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returnedBooks as $record)
                  <tr>
                    <td>{{ $record['user']->userId }}</td>
                    <td>{{ $record['user']->first_name }} {{ $record['user']->last_name }}</td>
                    <td>{{ $record['user']->email }}</td>
                    <td>
                      <strong>{{ $record['book']->title ?? 'N/A' }}</strong>
                      @if($record['book']->author)
                        <br><small class="text-muted">by {{ $record['book']->author }}</small>
                      @endif
                    </td>
                    <td>
                      @if($record['pickup_date'])
                        {{ \Carbon\Carbon::parse($record['pickup_date'])->format('M d, Y') }}
                      @else
                        <span class="text-muted">N/A</span>
                      @endif
                    </td>
                    <td>
                      @if($record['return_date'])
                        {{ \Carbon\Carbon::parse($record['return_date'])->format('M d, Y') }}
                        <br><small class="text-muted">
                          {{ \Carbon\Carbon::parse($record['return_date'])->diffForHumans() }}
                        </small>
                      @else
                        <span class="text-muted">N/A</span>
                      @endif
                    </td>
                    <td>
                      @if($record['had_fine'])
                        @if($record['fine_paid'])
                          <span class="badge bg-success">Fine Paid</span>
                        @else
                          <span class="badge bg-warning">Fine Unpaid</span>
                        @endif
                      @else
                        <span class="badge bg-info">No Fine</span>
                      @endif
                    </td>
                    <td>
                      @if($record['fine_amount'] > 0)
                        <span class="text-danger fw-bold">₱{{ number_format($record['fine_amount'], 2) }}</span>
                        @if($record['fine_paid_at'])
                          <br><small class="text-success">
                            Paid: {{ \Carbon\Carbon::parse($record['fine_paid_at'])->format('M d, Y') }}
                          </small>
                        @endif
                      @else
                        <span class="text-muted">₱0.00</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">No returned books found.</td>
                  </tr>
                @endforelse
            </tbody>
        </table>
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
        
        // Handle settle fine form submission
        const settleForms = document.querySelectorAll('.settle-fine-form');
        settleForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('.settle-btn');
                const fineAmount = button.getAttribute('data-fine-amount');
                const reservationId = button.getAttribute('data-reservation-id');
                
                if (!confirm('Settle fine of ₱' + fineAmount + ' for reservation ID ' + reservationId + '?')) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                
                return true;
            });
        });
    });
</script>
@include('assistant.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
