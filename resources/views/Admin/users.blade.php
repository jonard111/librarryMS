<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  </head>
<body>

<input type="checkbox" id="sidebar-toggle">
<div class="sidebar">
  <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#adminProfileModal">
    <div class="profile-info">
      <i class="fas fa-user-circle"></i>
      <div class="profile-text">
        <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
      </div>
    </div>
  </a>

  <nav class="nav flex-column text-start">
    <a href="{{ route('admin.dashboard') }}" class="nav-link">
      <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('admin.users') }}" class="nav-link active">
      <i class="fas fa-users me-2"></i><span>Manage Users</span>
    </a>
    <a href="{{ route('admin.announcement') }}" class="nav-link">
      <i class="fas fa-bullhorn me-2"></i><span>Announcements</span>
    </a>
    <a href="{{ route('admin.reports') }}" class="nav-link">
      <i class="fas fa-file-alt me-2"></i><span>Reports</span>
    </a>
    <a href="{{ route('admin.books') }}" class="nav-link">
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

<div class="content flex-grow-1">

  <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
      <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
      <div class="input-group">
        <input type="text" class="form-control" id="searchUserInput" placeholder="Search user...">
        <button class="btn btn-outline-secondary" type="button">
          <i class="fas fa-search"></i>
        </button>
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

  <!-- Display success message -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

        <h3 class="mb-0 fw-semibold text-success">Manage Users</h3><br>

  <!-- Approved Users Table -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="fw-bold">Approved Users</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Registration Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($approvedUsers as $user)
            <tr>
              <td>{{ $user->userId }}</td>
              <td>{{ $user->first_name }} {{ $user->last_name }}</td>
              <td>{{ $user->email }}</td>
              <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
              <td><span class="badge bg-success">{{ ucfirst($user->account_status) }}</span></td>
              <td>{{ $user->registration_date->format('Y-m-d') }}</td>
              <td>
                <button class="btn btn-sm btn-outline-primary edit-user-btn" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editUserModal"
                        data-user-id="{{ $user->userId }}"
                        data-first-name="{{ $user->first_name }}"
                        data-last-name="{{ $user->last_name }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ $user->role }}"
                        data-account-status="{{ $user->account_status }}">
                  <i class="fas fa-edit"></i> Edit
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Pending Users Table -->
  <div class="card shadow-sm mb-5">
    <div class="card-header bg-transparent border-0 pt-4">
      <h5 class="fw-bold">Account Requests</h5>
      <p class="text-muted small mb-0">Pending user registrations awaiting approval.</p>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Role</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pendingUsers as $user)
            <tr>
              <td>{{ ucfirst($user->role) }}</td>
              <td>{{ $user->first_name }}</td>
              <td>{{ $user->last_name }}</td>
              <td>{{ $user->email }}</td>
              <td class="d-flex gap-2">
                <form action="{{ route('admin.user.approve', $user->userId) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-check"></i>
                  </button>
                </form>
                <form action="{{ route('admin.user.reject', $user->userId) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center">No pending users.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">
          <i class="fas fa-user-edit me-2"></i> Edit User Information
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editUserForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label for="editFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editFirstName" name="first_name" required>
          </div>
          <div class="mb-3">
            <label for="editLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editLastName" name="last_name" required>
          </div>
          <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="editRole" class="form-label">Role</label>
            <select class="form-select" id="editRole" name="role" required>
              <option value="student">Student</option>
              <option value="faculty">Faculty</option>
              <option value="headlibrarian">Head Librarian</option>
              <option value="assistant">Assistant</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editAccountStatus" class="form-label">Account Status</label>
            <select class="form-select" id="editAccountStatus" name="account_status" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-user-btn');
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        const firstName = this.getAttribute('data-first-name');
        const lastName = this.getAttribute('data-last-name');
        const email = this.getAttribute('data-email');
        const role = this.getAttribute('data-role');
        const accountStatus = this.getAttribute('data-account-status');
        
        // Set form action
        document.getElementById('editUserForm').action = '{{ route("admin.user.update", ":id") }}'.replace(':id', userId);
        
        // Populate form fields
        document.getElementById('editFirstName').value = firstName;
        document.getElementById('editLastName').value = lastName;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role;
        document.getElementById('editAccountStatus').value = accountStatus;
      });
    });

    // Search functionality for users
    const searchUserInput = document.getElementById('searchUserInput');
    if (searchUserInput) {
        searchUserInput.addEventListener('input', function() {
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
@include('Admin.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
