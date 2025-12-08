<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Librarian - Reservation & Borrow/Return</title>
    @vite(['resources/js/app.js', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    </head>
<body>
    
    <input type="checkbox" id="sidebar-toggle">

    {{-- SIDEBAR (Head Librarian Navigation) --}}
    <div class="sidebar">
        <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
            <div class="profile-info">
                <i class="fas fa-user-circle"></i>
                <div class="profile-text">
                    <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
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
            <a href="{{ route('head.announcement') }}" class="nav-link">
                <i class="fas fa-bullhorn me-2"></i> <span>Announcements</span>
            </a>
            <a href="{{ route('head.reports') }}" class="nav-link">
                <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
            </a>
            <a href="{{ route('head.books') }}" class="nav-link">
                <i class="fas fa-book-open me-2"></i> <span>Books</span>
            </a>
            <a href="{{ route('head.reservation') }}" class="nav-link active">
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

    <div class="content flex-grow-1">

        {{-- TOP HEADER --}}
        <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
                <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by name or ID" id="searchInput">
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

        {{-- ALERT MESSAGES --}}
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

        {{-- DASHBOARD CARDS --}}
        <div class="row g-4 mb-4">
            <h3 class="mb-0 fw-semibold text-success">Manage Reservation & Borrow/Return</h3>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm stats-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Active Reservations</p>
                            <h3 class="mb-0">{{ $activeReservations }}</h3>
                        </div>
                       <div class="stats-icon"> <i class="fas fa-calendar-plus text-success fs-2"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Books Borrowed</p>
                            <h3 class="mb-0">{{ $booksBorrowed }}</h3>
                        </div>
                        <i class="fas fa-book text-info fs-3"></i> 
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Books Returned</p>
                            <h3 class="mb-0">{{ $booksReturned }}</h3>
                        </div>
                        <i class="fas fa-book-reader text-primary fs-3"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Overdue Books</p>
                            <h3 class="mb-0 text-danger">{{ $overdueBooks }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE 1: RESERVATIONS (Pending/Approved/History) --}}
        <div class="card shadow-sm card-body mb-4">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h5 class="card-title mb-0">Book Reservations</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm table-column-filter" data-filter-table="headReservationsTable" data-filter-column="4" style="max-width: 200px;">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Picked Up">Picked Up</option>
                        <option value="Returned">Returned</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle filterable-table" data-filter-id="headReservationsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student Name</th>
                            <th>Book Title</th>
                            <th>Reservation Date</th>
                            <th>Loan/Due Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->user->first_name }} {{ $reservation->user->last_name }}</td>
                            <td>{{ $reservation->book->title }}</td>
                            <td>{{ $reservation->reservation_date ? $reservation->reservation_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                {{-- Show Due Date if picked up, otherwise show loan duration --}}
                                @if($reservation->status === 'picked_up')
                                    <small class="text-muted">Due:</small> <strong>{{ $reservation->due_date ? $reservation->due_date->format('M d, Y') : 'N/A' }}</strong>
                                @else
                                    {{ $reservation->loan_duration_label ?? 'N/A' }} 
                                @endif
                            </td>
                            <td>
                                @if($reservation->status === 'picked_up')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Picked Up</span>
                                @elseif($reservation->status === 'approved')
                                    <span class="badge bg-primary"><i class="fas fa-check"></i> Approved</span>
                                @elseif($reservation->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> Pending</span>
                                @elseif($reservation->status === 'returned')
                                    <span class="badge bg-secondary"><i class="fas fa-undo"></i> Returned</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> {{ ucfirst($reservation->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($reservation->status === 'pending')
                                        {{-- FIXED ROUTE: Use head.reservation.approveRequest --}}
                                        <form action="{{ route('head.reservation.approveRequest', $reservation->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary btn-sm" title="Approve Request">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                    @elseif($reservation->status === 'approved')
                                        {{-- FIXED ROUTE: Use head.reservation.approve --}}
                                        <form action="{{ route('head.reservation.approve', $reservation->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm" title="Mark as Picked Up">
                                                <i class="fas fa-check-circle"></i> Picked Up
                                            </button>
                                        </form>
                                    @elseif($reservation->status === 'picked_up')
                                         <button class="btn btn-sm btn-outline-secondary" disabled>
                                             <i class="fas fa-check"></i> Active
                                         </button>
                                    @endif
                                    
                                    <button class="btn btn-sm btn-outline-danger" title="Delete Reservation" onclick="deleteReservation({{ $reservation->id }})">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>No reservations found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABLE 2: ACTIVE BORROWERS (Including Fine Management) --}}
        <div class="card shadow-sm card-body">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h5 class="card-title mb-0">Current Active Borrowers (Return & Fine Management)</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm table-column-filter" data-filter-table="activeBorrowersTable" data-filter-column="6" style="max-width: 200px;">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Payment Required">Payment Required</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle" data-filter-id="activeBorrowersTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student Name</th>
                            <th>Book Title</th>
                            <th>Date Borrowed</th>
                            <th>Due Date</th>
                            <th>Loan Period</th>
                            <th>Fine Due</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeBorrowers as $borrower)
                        <tr>
                            <td>{{ $borrower->user->first_name }} {{ $borrower->user->last_name }}</td>
                            <td>{{ $borrower->book->title }}</td>
                            <td>{{ $borrower->pickup_date ? $borrower->pickup_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $borrower->due_date ? $borrower->due_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $borrower->loan_duration_label }}</td>
                            <td>
                                @php
                                  $currentFine = $borrower->calculateFine() ?? 0;
                                  $requiresPayment = $currentFine > 0 && !$borrower->fine_paid_at;
                                @endphp
                                @if($requiresPayment)
                                  <span class="text-danger fw-bold">₱{{ number_format($currentFine, 2) }}</span>
                                @else
                                  <span class="text-muted">₱0.00</span>
                                @endif
                            </td>
                            <td>
                                @if($requiresPayment)
                                    <span class="badge bg-danger p-2">Payment Required</span>
                                @elseif($borrower->isOverdue())
                                    <span class="badge bg-warning text-dark p-2">Overdue</span>
                                @else
                                    <span class="badge bg-success p-2">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    {{-- ACTION 1: SETTLE FINE (If payment required) --}}
                                    @if($requiresPayment)
                                        {{-- FIXED ROUTE: Use head.reservation.settleFine --}}
                                        <form action="{{ route('head.reservation.settleFine', $borrower->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Settle fine of ₱{{ number_format($currentFine, 2) }} for {{ $borrower->user->last_name }}?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-coins me-1"></i> Settle Fine
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- ACTION 2: RETURN BOOK --}}
                                    {{-- FIXED ROUTE: Use head.reservation.return --}}
                                    <form action="{{ route('head.reservation.return', $borrower->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirm book return for {{ $borrower->book->title }}?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-info btn-sm" @if($requiresPayment) disabled title="Settle fine first" @endif>
                                            <i class="fas fa-undo-alt me-1"></i> Return
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>No active borrowers found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('head.partials.profile-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality for reservations
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('table tbody tr');
                
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const matches = rowText.includes(searchTerm);
                    // Hide/show the row based on the match
                    row.style.display = matches ? '' : 'none';
                });
            });
        }

        // DELETE function updated to use head route
        function deleteReservation(id) {
            if (confirm('Are you sure you want to delete this reservation? This cannot be undone.')) {
                fetch(`{{ url('head/reservation') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => location.reload());
            }
        }

        // Column Filtering function (Preserved)
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
                            const cellText = cell.textContent.toLowerCase().trim();
                            
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
    </script>
</body>
</html>