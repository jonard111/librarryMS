<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements</title>

  @vite(['resources/js/app.js', 'resources/css/design.css'])

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

<input type="checkbox" id="sidebar-toggle">

<!-- SIDEBAR -->
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
            <i class="fas fa-chart-bar me-2"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link">
            <i class="fas fa-users me-2"></i> <span>Manage Users</span>
        </a>
        <a href="{{ route('admin.announcement') }}" class="nav-link active">
            <i class="fas fa-bullhorn me-2"></i><span>Announcements</span>
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-link">
            <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
        </a>
        <a href="{{ route('admin.books') }}" class="nav-link ">
            <i class="fas fa-book-open me-2"></i><span>Books</span>
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

    <!-- TOP BAR -->
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        
        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>

            <div class="input-group">
              <input type="text" class="form-control" id="searchAnnouncementInput" placeholder="Search Announcement">
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

    <!-- MAIN CONTENT -->
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Something went wrong:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0">Announcements</h5>

           <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                <i class="fas fa-plus-circle"></i> Create Announcement
           </button>

        </div>

        <!-- OVERVIEW CARDS -->
        <div class="card shadow border-0 p-3 mb-4">
            <div class="card-body">
                <h6 class="mb-3">Announcements Overview</h6>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="text-muted small">Active</div>
                        <div class="fw-semibold">{{ $stats['active'] }}</div>
                    </div>
                    <span class="status-pill status-active text-success fw-bold">Active</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Expired</div>
                        <div class="fw-semibold">{{ $stats['expired'] }}</div>
                    </div>
                    <span class="status-pill status-expired text-danger fw-bold">Expired</span>
                </div>
            </div>
        </div>

        <!-- ANNOUNCEMENTS LIST -->
        <h5 class="mb-3">Recent Announcements</h5>
        <div class="row g-3">
            @forelse ($announcements as $announcement)
                @php
                    $badge = $announcement->badgeClass();
                    $statusLabel = $announcement->statusLabel();
                    $audience = $announcement->audience ? implode(', ', array_map('ucfirst', $announcement->audience)) : 'All Users';
                    $postedBy = $announcement->creator ? $announcement->creator->first_name . ' ' . $announcement->creator->last_name : 'System';
                    $publishDate = optional($announcement->publish_at)->format('M d, Y');
                @endphp
                <div class="col-md-6">
                    <div class="card shadow border-0 p-3 h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>{{ $announcement->title }}</h6>
                                    <div class="text-muted small">Posted by {{ $postedBy }} • {{ $publishDate }} • {{ $audience }}</div>
                                </div>
                                <span class="status-pill text-white bg-{{ $badge }} fw-bold text-uppercase px-3 py-1 rounded-pill">{{ $statusLabel }}</span>
                            </div>

                            <p class="mt-3">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->body), 180) }}</p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="small text-muted">
                                    {{ $announcement->publish_at ? $announcement->publish_at->diffForHumans() : 'No schedule' }}
                                </div>

                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal-{{ $announcement->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.announcement.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Delete this announcement?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editAnnouncementModal-{{ $announcement->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-bullhorn me-2"></i>Edit Announcement
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.announcement.update', $announcement) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Message</label>
                                        <textarea name="body" rows="4" class="form-control" required>{{ old('body', $announcement->body) }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Audience</label>
                                            <div class="border rounded p-3 bg-light">
                                                @foreach ($audienceOptions as $audienceOption)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="audience[]" value="{{ $audienceOption }}"
                                                            {{ in_array($audienceOption, $announcement->audience ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label">{{ ucfirst($audienceOption) }}</label>
                                                    </div>
                                                @endforeach
                                                <small class="text-muted">Leave all unchecked to send to everyone.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                @php $statusOptions = ['draft' => 'Draft', 'scheduled' => 'Scheduled', 'published' => 'Published', 'archived' => 'Archived']; @endphp
                                                @foreach ($statusOptions as $value => $label)
                                                    <option value="{{ $value }}" @selected($announcement->status === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Publish At</label>
                                            <input type="datetime-local" name="publish_at" class="form-control"
                                                value="{{ optional($announcement->publish_at)->format('Y-m-d\TH:i') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Expires At</label>
                                            <input type="datetime-local" name="expires_at" class="form-control"
                                                value="{{ optional($announcement->expires_at)->format('Y-m-d\TH:i') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-success">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-4 text-center text-muted">
                        No announcements posted yet.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Announcement Modal - Vue Component -->
<div id="app">
    <announcement-modal
        store-url="{{ route('admin.announcement.store') }}"
        :audience-options='@json($audienceOptions)'
        modal-id="createAnnouncementModal"
    ></announcement-modal>
</div>



</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for announcements
    document.addEventListener('DOMContentLoaded', function() {
        const searchAnnouncementInput = document.getElementById('searchAnnouncementInput');
        if (searchAnnouncementInput) {
            searchAnnouncementInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const announcementCards = document.querySelectorAll('.card, .list-group-item, table tbody tr');
                
                announcementCards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    const matches = cardText.includes(searchTerm);
                    card.style.display = matches ? '' : 'none';
                });
            });
        }
    });
</script>
@include('Admin.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
