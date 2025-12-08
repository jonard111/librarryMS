<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Record Management</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  </head>
<body>

  <input type="checkbox" id="sidebar-toggle" />

  {{-- SIDEBAR (Head Librarian Navigation) --}}
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

  <div class="sidebar-overlay"></div>

  {{-- CONTENT AREA --}}
  <div class="content flex-grow-1">
    
    {{-- TOP HEADER --}}
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
          {{-- FIXED: Use text-primary --}}
          <span class="fw-bold text-success d-block">Library MS</span> 
          <small class="text-muted" style="font-size:0.85rem;">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
      </div>
    </div>

    {{-- ALERT MESSAGES (Preserved) --}}
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

    {{-- STUDENT REGISTRATION STATS CARDS --}}
    <div class="row g-4 mb-4">
      <h3 class="mb-0 fw-semibold text-success">Student Registration Overview</h3>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm stats-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted small mb-1">Total Student</p>
              <h3 class="mb-2">{{ $totalStudents }}</h3>
              <div class="d-flex align-items-center">
                {{-- FIXED: Use text-primary for positive change --}}
                <span class="{{ $totalChange >= 0 ? 'text-primary' : 'text-danger' }} small">{{ $totalChange >= 0 ? '+' : '' }}{{ $totalChange }}%</span>
                <span class="text-muted small ms-1">vs last month</span>
              </div>
            </div>
            <div class="stats-icon text-success">
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
            <div class="stats-icon text-success">
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
            <div class="stats-icon text-success">
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
            <div class="stats-icon text-success">
              <i class="fas fa-calendar-plus"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    {{-- LOAN STATUS STATS CARDS --}}
    <div class="row g-4 mb-4">
        <h5 class="mb-0 fw-semibold text-primary">Current Loan Status</h5>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 dashboard-card shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Books Currently Borrowed</p>
                            <h3 class="mb-2">{{ $booksCurrentlyBorrowed ?? 0 }}</h3>
                            <small class="text-muted">Active loans</small>
                        </div>
                        <div class="stats-icon text-primary">
                            <i class="fas fa-book"></i>
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
                        <div class="stats-icon text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
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
                        <div class="stats-icon text-success">
                            <i class="fas fa-coins"></i>
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
                        <div class="stats-icon text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- TABLE 1: OVERDUE / ACTIVE LOANS --}}
    <div class="card card-body mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Student Overdue & Active Loan Records</h5>
            <div class="d-flex gap-2">
                {{-- Filter controls preserved --}}
                <select class="form-select form-select-sm table-column-filter" data-filter-table="headStudentTable" data-filter-column="6" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="Payment Required">Payment Required</option>
                    <option value="Overdue">Overdue</option>
                    <option value="Active Loan">Active Loan</option>
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
                                {{ \Carbon\Carbon::parse($record['due_date'])->diffForHumans(null, true) }} overdue
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
                            <span class="badge bg-warning text-dark">Overdue</span>
                          @else
                            <span class="badge bg-primary">Active Loan</span>
                          @endif
                        </td>
                        <td>
                          <div class="btn-group" role="group">
                            {{-- Action for Head Librarian to record settlement/return directly --}}
                            @if($record['requires_payment'])
                              <form action="{{ route('head.reservation.settleFine', $record['reservation']->id) }}" 
                                    method="POST" 
                                    class="d-inline settle-fine-form"
                                    onsubmit="return confirm('Settle fine of ₱{{ number_format($record['fine_due'], 2) }} for {{ $record['user']->last_name }}?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="btn btn-sm btn-success" 
                                        title="Settle Fine">
                                  <i class="fas fa-money-bill-wave me-1"></i> Settle
                                </button>
                              </form>
                            @elseif($record['is_overdue'] || $record['reservation']->status === 'picked_up')
                              <form action="{{ route('head.reservation.return', $record['reservation']->id) }}" 
                                    method="POST" 
                                    class="d-inline"
                                    onsubmit="return confirm('Confirm book return for {{ $record['book']->title }}?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="btn btn-sm btn-info" 
                                        title="Return Book">
                                  <i class="fas fa-undo-alt me-1"></i> Return
                                </button>
                              </form>
                            @endif
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="text-center text-muted py-4">No active or overdue student loans found.</td>
                      </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- TABLE 2: RETURNED BOOKS HISTORY --}}
    <div class="card card-body mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Returned Books History</h5>
            <div class="d-flex gap-2">
                {{-- Filter controls preserved --}}
                <select class="form-select form-select-sm table-column-filter" data-filter-table="returnedBooksTable" data-filter-column="6" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="Fine Paid">Fine Paid</option>
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
                          {{ $record['pickup_date'] ? \Carbon\Carbon::parse($record['pickup_date'])->format('M d, Y') : 'N/A' }}
                        </td>
                        <td>
                          {{ $record['return_date'] ? \Carbon\Carbon::parse($record['return_date'])->format('M d, Y') : 'N/A' }}
                          <br><small class="text-muted">
                            {{ \Carbon\Carbon::parse($record['return_date'])->diffForHumans(null, false) }}
                          </small>
                        </td>
                        <td>
                          @if($record['had_fine'])
                            @if($record['fine_paid'])
                              <span class="badge bg-success">Fine Paid</span>
                            @else
                              <span class="badge bg-danger">Fine Unpaid</span>
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
                        <td colspan="8" class="text-center text-muted py-4">No returned books history found.</td>
                      </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


  </div>

  {{-- MODALS (Simplified to rely on native form submissions) --}}
  
@include('head.partials.profile-modal')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- JavaScript for Filtering and Form Submission ---
    document.addEventListener('DOMContentLoaded', function() {
        const searchStudentInput = document.getElementById('searchStudentInput');
        
        // --- Global Search (Preserved) ---
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
        
        // --- Column Filtering (Preserved) ---
        document.querySelectorAll('.table-column-filter').forEach(select => {
            select.addEventListener('change', function() {
                const filterValue = this.value.toLowerCase();
                const tableId = this.getAttribute('data-filter-table');
                const columnIndex = parseInt(this.getAttribute('data-filter-column'));
                const table = document.querySelector(`[data-filter-id="${tableId}"]`);
                
                if (table) {
                    table.querySelectorAll('tbody tr').forEach(row => {
                        const cell = row.children[columnIndex];
                        if (cell) {
                            // Check if cell is the Status column (column index 6 in the main table)
                            let cellText = cell.textContent.toLowerCase().trim();
                            
                            // Adjusting filter text for status labels
                            if (columnIndex === 6) {
                                if (cell.querySelector('.badge.bg-danger')) cellText = 'payment required';
                                else if (cell.querySelector('.badge.bg-warning')) cellText = 'overdue';
                                else if (cell.querySelector('.badge.bg-primary')) cellText = 'active loan';
                            }

                            if (filterValue === '' || cellText.includes(filterValue)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@include('head.partials.profile-modal') 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>