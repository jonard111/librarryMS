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

    <!-- Borrowed Books Table -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3"><i class="fas fa-book me-2"></i>Recent Borrowed</h5>
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle">
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
              @forelse($borrowedBooks ?? collect() as $reservation)
              <tr>
                <td>{{ $reservation->book->title ?? 'N/A' }}</td>
                <td>{{ $reservation->pickup_date ? $reservation->pickup_date->format('Y-m-d H:i') : 'N/A' }}</td>
                <td>{{ $reservation->return_date ? $reservation->return_date->format('Y-m-d H:i') : 'Not Returned' }}</td>
                <td>
                  @if($reservation->return_date && $reservation->due_date && $reservation->return_date->gt($reservation->due_date))
                    {{ $reservation->return_date->diffInDays($reservation->due_date) }} day(s) late
                  @else
                    0
                  @endif
                </td>
                <td>
                  @if($reservation->status == 'picked_up')
                    <span class="badge bg-success">Picked Up</span>
                  @elseif($reservation->status == 'returned')
                    <span class="badge bg-secondary">Returned</span>
                  @else
                    <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-4">
                  <i class="fas fa-info-circle me-2 text-muted"></i>No borrowed books yet.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Request Books Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="fas fa-file-alt me-2"></i>My Book Requests</h5>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">Total: {{ $bookRequests->count() ?? 0 }} request(s)</small>
                    <select class="form-select form-select-sm table-column-filter" data-filter-table="bookRequestsTable" data-filter-column="4" style="max-width: 180px;">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Ready for Pickup">Ready for Pickup</option>
                        <option value="Picked Up">Picked Up</option>
                        <option value="Rejected">Rejected</option>
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
                        @php
                            // Debug: Check if we have requests
                            $hasRequests = isset($bookRequests) && $bookRequests->count() > 0;
                        @endphp
                        @if(!$hasRequests)
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2 text-muted"></i>No book requests yet.
                                    <br><small class="text-muted">Your reservations will appear here once you make a request.</small>
                                </td>
                            </tr>
                        @else
                        @foreach($bookRequests as $index => $request)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $request->book ? $request->book->title : 'Book Not Found (ID: ' . $request->book_id . ')' }}</td>
                            <td>{{ $request->book ? $request->book->author : 'N/A' }}</td>
                            <td>{{ $request->reservation_date ? $request->reservation_date->format('Y-m-d') : ($request->created_at ? $request->created_at->format('Y-m-d') : 'N/A') }}</td>
                            <td>
                              @if($request->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                              @elseif($request->status == 'approved')
                                <span class="badge bg-primary">Ready for Pickup</span>
                              @elseif($request->status == 'picked_up')
                                <span class="badge bg-success">Picked Up</span>
                              @elseif($request->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                              @elseif($request->status == 'cancelled')
                                <span class="badge bg-secondary">Cancelled</span>
                              @else
                                <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                              @endif
                            </td>
                            <td>
                              @if($request->status == 'picked_up' && $request->due_date)
                                {{ $request->due_date->format('Y-m-d') }} (Due Date)
                              @elseif($request->status == 'approved' && $request->pickup_date)
                                {{ $request->pickup_date->format('Y-m-d') }}
                              @elseif($request->status == 'pending')
                                Waiting Confirmation
                              @else
                                N/A
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
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@include('student.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for borrowed books
    document.addEventListener('DOMContentLoaded', function() {
        const searchBorrowedInput = document.getElementById('searchBorrowedInput');
        if (searchBorrowedInput) {
            searchBorrowedInput.addEventListener('input', function() {
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
</body>
</html>
