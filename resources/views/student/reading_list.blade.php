<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Reading List</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

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
      <a href="{{ route('student.notifications') }}" class="nav-link">
        <i class="fas fa-bell me-2"></i><span>Notifications</span>
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
    <div class="top-header d-flex justify-content-between align-items-center mb-4 bg-white border-bottom">
      <div class="d-flex align-items-center">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none me-2">
          <i class="fas fa-bars"></i>
        </label>
        <h3 class="mb-0 fw-semibold text-success"><i class="fas fa-bookmark me-2"></i>My Reading List</h3>
      </div>

      <div class="d-flex align-items-center gap-2">
        <div class="text-end">
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row row-cols-2 row-cols-md-6 g-3">
        @forelse($readingList as $readingListItem)
          @if($readingListItem->book)
            <div class="col-12 col-sm-6 col-md-3 mb-4">
              <div class="card card-wrapper shadow dashboard-card position-relative">
                <span class="badge-status badge-completed">Saved</span>
                <div style="width: 100%; height: 220px; overflow: hidden; background-color: #f0f0f0;">
                  <img src="{{ $readingListItem->book->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" 
                       class="card-img-top" 
                       alt="{{ $readingListItem->book->title }}"
                       style="width: 100%; height: 100%; object-fit: cover; display: block;" 
                       onerror="this.onerror=null; this.src='{{ Vite::asset('resources/images/bookcover3.jpg') }}';" />
                </div>
                <div class="card-body p-2">
                  <h5 class="card-title mb-1 text-truncate">{{ $readingListItem->book->title }}</h5>
                  <small class="card-subtitle text-muted">{{ $readingListItem->book->author }}</small>
                </div>
                <div class="d-flex gap-2 m-2">
                  <a href="{{ route('student.books.show', $readingListItem->book->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                    <i class="fas fa-eye me-1"></i> View
                  </a>
                  <form action="{{ route('student.reading-list.remove', $readingListItem->book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this book from your reading list?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Remove from reading list">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @endif
        @empty
          <div class="col-12">
            <div class="alert alert-light border text-center py-5">
              <i class="fas fa-bookmark fa-3x me-2 text-muted mb-3"></i>
              <h5 class="mb-2">Your reading list is empty</h5>
              <p class="text-muted mb-3">Start adding books you want to read!</p>
              <a href="{{ route('student.books') }}" class="btn btn-outline-primary">
                <i class="fas fa-book me-2"></i>Browse Books
              </a>
            </div>
          </div>
        @endforelse
    </div>

    @if($readingList->hasPages())
        <div class="mt-4">
            {{ $readingList->links() }}
        </div>
    @endif

</div>

@include('student.partials.profile-modal')
</body>
</html>

