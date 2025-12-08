<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Books & Requests</title>
    @vite(['resources/css/design.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <a href="{{ route('student.borrowed') }}" class="nav-link active">
          <i class="fas fa-file-alt me-2"></i><span> My Borrowed Books</span>
      </a>
      <a href="{{ route('student.notifications') }}" class="nav-link">
        <i class="fas fa-bell me-2"></i><span>Notification</span> 
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
    {{-- Top Header (Theme Fix) --}}
      <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
          <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
          <div class="input-group">
              <input type="text" class="form-control" id="searchBorrowedInput" placeholder="Search my books">
              <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <div class="text-end">
            <span class="fw-bold text-primary d-block">Library MS</span> {{-- THEME FIX --}}
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

    {{-- Borrowed Books Table (Active Loans & History) --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-3"><i class="fas fa-book me-2 text-success"></i>Active Loans & History</h5>
            <div class="d-flex align-items-center gap-2">
                <select class="form-select form-select-sm table-column-filter" data-filter-table="recentborrowedTable" data-filter-column="4" style="max-width: 180px;">
                    <option value="">All Status</option>
                    <option value="Returned">Returned</option>
                    <option value="Picked Up">Picked Up</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle filterable-table" data-filter-id="recentborrowedTable">
            <thead class="table-light">
              <tr>
                <th>Book Name</th>
                <th>Borrowed On</th> {{-- Updated Header --}}
                <th>Due Date</th> {{-- Added Due Date --}}
                <th>Fine (₱)</th> {{-- Updated Header --}}
                <th>Status</th>
                <th>Returned On</th> {{-- Updated Header --}}
              </tr>
            </thead>
            <tbody>
              @forelse($borrowedBooks ?? collect() as $reservation)
                @php
                    $isOverdue = $reservation->due_date && $reservation->due_date->isPast() && $reservation->status == 'picked_up';
                    $fineAmount = $reservation->fine_amount ?? '0.00'; // Assumes fine_amount accessor exists in Model
                @endphp
              <tr>
                <td>{{ $reservation->book->title ?? 'N/A' }}</td>
                <td>{{ $reservation->pickup_date ? $reservation->pickup_date->format('M d, Y') : 'N/A' }}</td>
                <td>
                    @if ($reservation->due_date)
                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                            {{ $reservation->due_date->format('M d, Y') }}
                        </span>
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <span class="{{ $isOverdue ? 'text-danger' : 'text-muted' }}">
                        ₱{{ number_format($fineAmount, 2) }}
                    </span>
                </td>
                <td>
                    @if($reservation->status == 'picked_up')
                        <span class="badge bg-primary">Picked Up</span> {{-- THEME FIX --}}
                    @elseif($reservation->status == 'returned')
                        <span class="badge bg-success">Returned</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                    @endif
                </td>
                <td>{{ $reservation->return_date ? $reservation->return_date->format('M d, Y') : 'N/A' }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4"> {{-- Corrected colspan --}}
                  <i class="fas fa-info-circle me-2 text-muted"></i>No borrowed books yet.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Request Books Table --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-success"></i>My Book Requests</h5>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">Total: {{ $bookRequests->count() ?? 0 }} request(s)</small>
                      <select class="form-select form-select-sm table-column-filter" data-filter-table="bookRequestsTable" data-filter-column="4" style="max-width: 180px;">
                          <option value="">All Status</option>
                          <option value="Pending">Pending</option>
                          <option value="Ready for Pickup">Ready for Pickup</option>
                          <option value="Rejected">Rejected</option>
                          <option value="Cancelled">Cancelled</option>
                      </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle filterable-table" data-filter-id="bookRequestsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Requested On</th>
                            <th>Status</th>
                            <th>Pickup/Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $requestIndex = 1; @endphp
                        @forelse($bookRequests ?? collect() as $request)
                            <tr>
                                <th>{{ $requestIndex++ }}</th>
                                <td>{{ $request->book->title ?? 'Book Not Found' }}</td>
                                <td>{{ $request->book->author ?? 'N/A' }}</td>
                                <td>{{ $request->reservation_date ? $request->reservation_date->format('M d, Y') : ($request->created_at ? $request->created_at->format('M d, Y') : 'N/A') }}</td>
                                <td data-filter-value="{{ $request->status == 'approved' ? 'Ready for Pickup' : ucfirst($request->status) }}">
                                  @if($request->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                  @elseif($request->status == 'approved')
                                    <span class="badge bg-primary">Ready for Pickup</span>
                                  @elseif($request->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                  @elseif($request->status == 'cancelled')
                                    <span class="badge bg-secondary">Cancelled</span>
                                  @else
                                    <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                  @endif
                                </td>
                                <td>
                                  @if($request->status == 'approved')
                                      Awaiting Pickup
                                  @elseif($request->status == 'rejected' || $request->status == 'cancelled')
                                      Closed
                                  @else
                                      Waiting Confirmation
                                  @endif
                                </td>
                                <td>
                                  @if(in_array($request->status ?? '', ['pending', 'approved']))
                                    <form action="{{ route('student.requests.cancel', $request->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="badge bg-danger text-decoration-none border-0" style="cursor: pointer;">Cancel</button>
                                    </form>
                                  @endif
                                </td>
                            </tr>
                        @empty
                          <tr>
                            <td colspan="7" class="text-center py-4 table-empty-state">
                              <i class="fas fa-info-circle me-2 text-muted"></i>No book requests yet.
                            </td>
                          </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@include('student.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for borrowed books (Simple full-table text search)
    document.addEventListener('DOMContentLoaded', function() {
        const searchBorrowedInput = document.getElementById('searchBorrowedInput');
        if (searchBorrowedInput) {
            searchBorrowedInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('table.filterable-table tbody tr'); // Target filterable tables
                
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const matches = rowText.includes(searchTerm);
                    // Only hide if it's not the empty state row
                    if (!row.classList.contains('table-empty-state')) {
                        row.style.display = matches ? '' : 'none';
                    }
                });
            });
        }

        // Column Filtering Logic (Universal script substitute)
        document.querySelectorAll('.table-column-filter').forEach(select => {
            select.addEventListener('change', function() {
                const filterValue = this.value.toLowerCase();
                const tableId = this.getAttribute('data-filter-table');
                const columnIndex = parseInt(this.getAttribute('data-filter-column'));
                const table = document.querySelector(`[data-filter-id="${tableId}"]`);
                
                if (table) {
                    table.querySelectorAll('tbody tr').forEach(row => {
                        const cell = row.children[columnIndex];
                        
                        // Skip empty state row
                        if (row.classList.contains('table-empty-state')) {
                            return;
                        }

                        if (cell) {
                            // Extract text, preferring data-filter-value if set (for complex cells like badges)
                            const cellText = row.querySelector('td:nth-child(' + (columnIndex + 1) + ')').getAttribute('data-filter-value') || cell.textContent;
                            const comparisonText = cellText.toLowerCase().trim();
                            
                            // Check for match
                            if (filterValue === '' || comparisonText.includes(filterValue)) {
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
</body>
</html>