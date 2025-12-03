<!-- resources/views/student/request_books.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Books</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/design.css'])
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
        <a href="{{ route('student.borrowed') }}" class="nav-link">
            <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
        </a>
        <a href="{{ route('student.requests') }}" class="nav-link active">
            <i class="fas fa-file-alt me-2"></i><span>Request Books</span>
        </a>
        <a href="{{ route('student.announcements') }}" class="nav-link">
            <i class="fas fa-bullhorn me-2"></i><span>Announcements</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="nav-link">
            <i class="fas fa-bell me-2"></i><span>Notification</span>
        </a>
        <a href="{{ route('student.books') }}" class="nav-link">
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
                <input type="text" class="form-control" id="searchRequestInput" placeholder="Search my request books">
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

    <!-- Dashboard cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card shadow border-0 dashboard-card h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">🔖Borrowed Books</h5>
                    <p class="stat text-primary fw-bold fs-3">8</p>
                    <small class="text-muted">Currently borrowed</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow border-0 dashboard-card h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">📚Request Books</h5>
                    <p class="stat text-warning fw-bold fs-3">1</p>
                    <small class="text-muted">Pending Approval</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow border-0 dashboard-card h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">📢Penalties</h5>
                    <p class="stat text-primary fw-bold fs-3">0</p>
                    <p class="fs-6 text-muted">Late Returned</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow border-0 dashboard-card h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-book-open me-2"></i> Approved Books</h5>
                    <p class="stat text-warning fw-bold fs-3">4</p>
                    <p class="fs-6 text-muted">Access online books</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Books Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">My Book Request</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                        <tr>
                            <th>1</th>
                            <td>Prayer Journal</td>
                            <td>John Doe</td>
                            <td>2025-10-01</td>
                            <td><span class="badge bg-success">Picked Up</span></td>
                            <td>2025-10-05</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>2</th>
                            <td>Memory</td>
                            <td>Angel</td>
                            <td>2025-10-02</td>
                            <td><span class="badge bg-primary">Ready for Pickup</span></td>
                            <td>2025-10-15 (Due Date)</td>
                            <td><a href="#" class="badge bg-danger text-decoration-none">Cancel</a></td>
                        </tr>
                        <tr>
                            <th>3</th>
                            <td>Memory</td>
                            <td>Angel</td>
                            <td>2025-10-03</td>
                            <td><span class="badge bg-success">Picked Up</span></td>
                            <td>2025-10-04</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>4</th>
                            <td>Journal</td>
                            <td>John Doe</td>
                            <td>2025-10-06</td>
                            <td><span class="badge bg-primary">Ready for Pickup</span></td>
                            <td>2025-10-19 (Due Date)</td>
                            <td><a href="#" class="badge bg-danger text-decoration-none">Cancel</a></td>
                        </tr>
                        <tr>
                            <th>5</th>
                            <td>The Book of Lost Names</td>
                            <td>Kristin Harmel</td>
                            <td>2025-10-10</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>Waiting Confirmation</td>
                            <td><a href="#" class="badge bg-danger text-decoration-none">Cancel</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('student.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for request books
    document.addEventListener('DOMContentLoaded', function() {
        const searchRequestInput = document.getElementById('searchRequestInput');
        if (searchRequestInput) {
            searchRequestInput.addEventListener('input', function() {
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
