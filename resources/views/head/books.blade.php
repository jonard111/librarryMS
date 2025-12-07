<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books</title>
   @vite(['resources/js/app.js', 'resources/css/book.css', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  </head>
<body>
  <div class="container-fluid">
    <input type="checkbox" id="sidebar-toggle">

    <!-- Sidebar -->
    <div class="sidebar">
      <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
          <div class="profile-info">
              <i class="fas fa-user-circle"></i>
              <div class="profile-text">
                  <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
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
        <a href="{{ route('head.books') }}" class="nav-link active">
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
    
    <!-- Content -->
    <div class="content flex-grow-1">
      <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
          <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
          <div class="input-group">
            <input type="text" class="form-control" id="searchBookInput" placeholder="Search book">
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

      <!-- Most Popular Books -->
      <section>
        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
          <h5 class="mb-2 mb-md-0">Most Popular Books</h5>
          <a href="{{ route('head.books.all') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
<hr>
        <div class="row row-cols-2 row-cols-md-6 g-3">
          @forelse($popularBooks ?? collect() as $book)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
              <div class="card card-wrapper position-relative dashboard-card shadow">
                <span class="badge-status badge-completed text-white">Copies: {{ $book->copies }}</span>
                <img src="{{ $book->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="{{ $book->title }}" />
                <div class="card-body p-2">
                  <h5 class="card-title mb-1 text-truncate">{{ $book->title }}</h5>
                  <small class="card-subtitle text-muted">{{ $book->author }}</small>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-light border text-center">
                <i class="fas fa-info-circle me-2"></i>No books available at the moment.
              </div>
            </div>
          @endforelse
        </div>
      </section>

      <!-- Most Popular E-Books -->
      <section>
        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
          <h5 class="mb-2 mb-md-0">Most Popular E-Books</h5>
          <a href="{{ route('head.ebooks.all') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
<hr>
        <div class="row row-cols-2 row-cols-md-6 g-3">
          @forelse($popularEbooks ?? collect() as $ebook)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
              <div class="card card-wrapper position-relative dashboard-card shadow">
               <span class="badge-status badge-completed text-white">Views: {{ $ebook->views }}</span>
                <img src="{{ $ebook->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="{{ $ebook->title }}" />
                <div class="card-body p-2">
                  <h5 class="card-title mb-1 text-truncate">{{ $ebook->title }}</h5>
                  <small class="card-subtitle text-muted">{{ $ebook->author }}</small>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-light border text-center">
                <i class="fas fa-info-circle me-2"></i>No e-books available at the moment.
              </div>
            </div>
          @endforelse
        </div>
      </section>

    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for books
    document.addEventListener('DOMContentLoaded', function() {
        const searchBookInput = document.getElementById('searchBookInput');
        if (searchBookInput) {
            searchBookInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const bookCards = document.querySelectorAll('.card.card-wrapper');
                
                bookCards.forEach(card => {
                    const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
                    const author = card.querySelector('.card-subtitle')?.textContent.toLowerCase() || '';
                    
                    const matches = title.includes(searchTerm) || 
                                   author.includes(searchTerm);
                    
                    const cardContainer = card.closest('.col-12, .col-sm-6, .col-md-3, .col-md-4, .col-lg-3');
                    if (cardContainer) {
                        cardContainer.style.display = matches ? '' : 'none';
                    }
                });
            });
        }
    });
</script>
@include('head.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
