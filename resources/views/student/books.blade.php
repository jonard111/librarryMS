<!-- resources/views/student/books.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books</title>
  {{-- Vite CSS for this page ONLY --}}
    @vite(['resources/js/app.js', 'resources/css/book.css', 'resources/css/design.css'])

    {{-- CDN libraries --}}

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
        <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('student.borrowed') }}" class="nav-link {{ request()->routeIs('student.borrowed') ? 'active' : '' }}">
            <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="nav-link {{ request()->routeIs('student.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell me-2"></i><span>Notification</span>
        </a>
        <a href="{{ route('student.books') }}" class="nav-link {{ request()->routeIs('student.books') ? 'active' : '' }}">
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
        <div class="d-flex align-items-center gap-2">
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
            <h3 class="mb-0 fw-semibold text-success">Books</h3>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="text-end">
                <span class="fw-bold text-success d-block">Library MS</span>
                <small class="text-muted">Management System</small>
            </div>
            <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
        </div>
    </div>

    <!-- Most Popular Books Section -->
    <section>
        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0">Most Popular Books</h5>
            <a href="{{ route('student.books.all') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div class="row row-cols-2 row-cols-md-6 g-3">
            @forelse($popularBooks ?? collect() as $book)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card card-wrapper position-relative dashboard-card shadow">
                        <span class="badge-status badge-completed">Copies: {{ $book->copies }}</span>
                        <div style="width: 100%; height: 220px; overflow: hidden; background-color: #f0f0f0;">
                            <img src="{{ $book->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" 
                                 class="card-img-top" 
                                 alt="{{ $book->title }}"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;" 
                                 onerror="this.onerror=null; this.src='{{ Vite::asset('resources/images/bookcover3.jpg') }}';" />
                        </div>
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1 text-truncate">{{ $book->title }}</h5>
                            <small class="card-subtitle text-muted">{{ $book->author }}</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm m-2 reserve-book-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#reserveBookModal"
                                data-book-id="{{ $book->id }}"
                                data-book-title="{{ $book->title }}"
                                data-book-author="{{ $book->author }}"
                                data-book-copies="{{ $book->copies }}"
                                data-book-cover="{{ $book->coverUrl() ?? '' }}"
                                data-book-isbn="{{ $book->isbn ?? '' }}"
                                data-book-publisher="{{ $book->publisher ?? '' }}"
                                data-book-category="{{ $book->category }}">
                            Reserve Now
                        </button>
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

    <!-- Most Popular E-Books Section -->
    <section>
        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0">Most Popular E-Books</h5>
            <a href="{{ route('student.ebooks.all') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div class="row row-cols-2 row-cols-md-6 g-3">
            @forelse($popularEbooks ?? collect() as $ebook)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card card-wrapper position-relative dashboard-card shadow">
                        <span class="badge-status badge-completed">NEW</span>
                        <div style="width: 100%; height: 220px; overflow: hidden; background-color: #f0f0f0;">
                            <img src="{{ $ebook->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" 
                                 class="card-img-top" 
                                 alt="{{ $ebook->title }}"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;" 
                                 onerror="this.onerror=null; this.src='{{ Vite::asset('resources/images/bookcover3.jpg') }}';" />
                        </div>
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1 text-truncate">{{ $ebook->title }}</h5>
                            <small class="card-subtitle text-muted">{{ $ebook->author }}</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm m-2 read-ebook-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#readEbookModal"
                                data-ebook-id="{{ $ebook->id }}"
                                data-ebook-title="{{ $ebook->title }}"
                                data-ebook-author="{{ $ebook->author }}"
                                data-ebook-cover="{{ $ebook->coverUrl() ?? '' }}"
                                data-ebook-file="{{ $ebook->fileUrl() ?? '' }}"
                                data-ebook-category="{{ $ebook->category }}"
                                data-ebook-views="{{ $ebook->views }}">
                            Read Now
                        </button>
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

@include('student.partials.profile-modal')

<!-- Reserve Book Modal -->
<div class="modal fade" id="reserveBookModal" tabindex="-1" aria-labelledby="reserveBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveBookModalLabel">
                    <i class="fas fa-bookmark me-2 text-success"></i> Reserve Book
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reserveBookForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <img id="reserveBookCover" src="" alt="Book Cover" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-8">
                            <h4 id="reserveBookTitle" class="mb-3"></h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-user me-2"></i><strong>Author:</strong> <span id="reserveBookAuthor"></span>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-copy me-2"></i><strong>Available Copies:</strong> 
                                <span class="badge bg-success" id="reserveBookCopies"></span>
                            </p>
                            <p class="mb-2" id="reserveBookIsbnContainer" style="display: none;">
                                <i class="fas fa-barcode me-2"></i><strong>ISBN:</strong> <span id="reserveBookIsbn"></span>
                            </p>
                            <p class="mb-2" id="reserveBookPublisherContainer" style="display: none;">
                                <i class="fas fa-building me-2"></i><strong>Publisher:</strong> <span id="reserveBookPublisher"></span>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-book me-2"></i><strong>Category:</strong> 
                                <span class="badge bg-primary" id="reserveBookCategory"></span>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>By clicking "Confirm Reservation", you are requesting to reserve this book. You will be notified once your reservation is approved.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-bookmark me-2"></i> Confirm Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Read Ebook Modal -->
<div class="modal fade" id="readEbookModal" tabindex="-1" aria-labelledby="readEbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="readEbookModalLabel">
                    <i class="fas fa-book-reader me-2 text-primary"></i> <span id="readEbookTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <img id="readEbookCover" src="" alt="Ebook Cover" class="img-fluid rounded shadow mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-user me-2"></i><span id="readEbookAuthor"></span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-eye me-2"></i><strong>Views:</strong> <span id="readEbookViews"></span>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-book me-2"></i><strong>Category:</strong> 
                            <span class="badge bg-primary" id="readEbookCategory"></span>
                        </p>
                    </div>
                    <div class="col-md-9">
                        <div id="readEbookContent">
                            <iframe id="readEbookIframe" src="" class="w-100" style="height: 70vh; border: 1px solid #ddd; border-radius: 8px; display: none;"></iframe>
                            <div id="readEbookDownload" class="alert alert-info" style="display: none;">
                                <i class="fas fa-info-circle me-2"></i>This e-book format requires a compatible reader.
                                <a id="readEbookDownloadLink" href="" target="_blank" class="btn btn-primary btn-sm ms-2" download>
                                    <i class="fas fa-download me-2"></i>Download E-Book
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reserve Book Modal
        const reserveButtons = document.querySelectorAll('.reserve-book-btn');
        reserveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookId = this.getAttribute('data-book-id');
                const title = this.getAttribute('data-book-title');
                const author = this.getAttribute('data-book-author');
                const copies = this.getAttribute('data-book-copies');
                const cover = this.getAttribute('data-book-cover') || '{{ Vite::asset("resources/images/bookcover3.jpg") }}';
                const isbn = this.getAttribute('data-book-isbn');
                const publisher = this.getAttribute('data-book-publisher');
                const category = this.getAttribute('data-book-category');
                
                document.getElementById('reserveBookForm').action = '{{ route("student.books.reserve", ":id") }}'.replace(':id', bookId);
                document.getElementById('reserveBookTitle').textContent = title;
                document.getElementById('reserveBookAuthor').textContent = author;
                document.getElementById('reserveBookCopies').textContent = copies;
                document.getElementById('reserveBookCategory').textContent = category.charAt(0).toUpperCase() + category.slice(1);
                document.getElementById('reserveBookCover').src = cover;
                
                if (isbn) {
                    document.getElementById('reserveBookIsbn').textContent = isbn;
                    document.getElementById('reserveBookIsbnContainer').style.display = 'block';
                } else {
                    document.getElementById('reserveBookIsbnContainer').style.display = 'none';
                }
                
                if (publisher) {
                    document.getElementById('reserveBookPublisher').textContent = publisher;
                    document.getElementById('reserveBookPublisherContainer').style.display = 'block';
                } else {
                    document.getElementById('reserveBookPublisherContainer').style.display = 'none';
                }
            });
        });

        // Read Ebook Modal
        const readButtons = document.querySelectorAll('.read-ebook-btn');
        readButtons.forEach(button => {
            button.addEventListener('click', function() {
                const ebookId = this.getAttribute('data-ebook-id');
                const title = this.getAttribute('data-ebook-title');
                const author = this.getAttribute('data-ebook-author');
                const cover = this.getAttribute('data-ebook-cover') || '{{ Vite::asset("resources/images/bookcover3.jpg") }}';
                const fileUrl = this.getAttribute('data-ebook-file');
                const category = this.getAttribute('data-ebook-category');
                const views = this.getAttribute('data-ebook-views');
                
                document.getElementById('readEbookTitle').textContent = title;
                document.getElementById('readEbookAuthor').textContent = author;
                document.getElementById('readEbookCategory').textContent = category.charAt(0).toUpperCase() + category.slice(1);
                document.getElementById('readEbookViews').textContent = views;
                document.getElementById('readEbookCover').src = cover;
                
                const iframe = document.getElementById('readEbookIframe');
                const downloadDiv = document.getElementById('readEbookDownload');
                const downloadLink = document.getElementById('readEbookDownloadLink');
                
                if (fileUrl) {
                    const fileExtension = fileUrl.split('.').pop().toLowerCase();
                    if (fileExtension === 'pdf') {
                        iframe.src = fileUrl;
                        iframe.style.display = 'block';
                        downloadDiv.style.display = 'none';
                    } else {
                        iframe.style.display = 'none';
                        downloadLink.href = fileUrl;
                        downloadDiv.style.display = 'block';
                    }
                } else {
                    iframe.style.display = 'none';
                    downloadDiv.style.display = 'none';
                }
                
                // Increment view count via AJAX
                fetch('{{ route("student.ebooks.show", ":id") }}'.replace(':id', ebookId), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.views) {
                        document.getElementById('readEbookViews').textContent = data.views;
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@include('student.partials.profile-modal')
</body>
</html>
