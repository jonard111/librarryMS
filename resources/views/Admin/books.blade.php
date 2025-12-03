<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>

    {{-- Vite CSS for this page ONLY --}}
    @vite(['resources/js/app.js', 'resources/css/book.css', 'resources/css/design.css'])
    {{-- CDN libraries --}}
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
            <a href="{{ url('/admin') }}" class="nav-link">
                <i class="fas fa-chart-bar me-2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ url('/admin/users') }}" class="nav-link">
                <i class="fas fa-users me-2"></i> <span>Manage Users</span>
            </a>
            <a href="{{ url('/admin/announcement') }}" class="nav-link">
                <i class="fas fa-bullhorn me-2"></i><span>Announcements</span>
            </a>
            <a href="{{ url('/admin/reports') }}" class="nav-link">
                <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
            </a>
            <a href="{{ url('/admin/books') }}" class="nav-link active">
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

        {{-- HEADER --}}
        <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">

            <div class="d-flex align-items-center gap-2">
                <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
                <h3 class="mb-0 fw-semibold text-success">Books</h3>
            </div>

            <div class="d-flex align-items-center">
                <div class="text-end">
                    <span class="fw-bold text-success d-block">Library MS</span>
                    <small class="text-muted">Management System</small>
                </div>
                <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
            </div>
        </div>

        {{-- SECTION 1 --}}
        <section>
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-2 mb-md-0">Most Popular Books</h5>
                <a href="{{ route('admin.books.all') }}" class="btn btn-outline-primary btn-sm">View All</a>
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
                            <div class="p-2">
                                <button class="btn btn-sm btn-outline-primary w-100 view-details-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewBookDetailsModal"
                                        data-book-id="{{ $book->id }}"
                                        data-book-title="{{ $book->title }}"
                                        data-book-author="{{ $book->author }}"
                                        data-book-copies="{{ $book->copies }}"
                                        data-book-cover="{{ $book->coverUrl() ?? '' }}"
                                        data-book-isbn="{{ $book->isbn ?? '' }}"
                                        data-book-publisher="{{ $book->publisher ?? '' }}"
                                        data-book-category="{{ $book->category }}">
                                    <i class="fas fa-eye me-2"></i> View Details
                                </button>
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

        {{-- SECTION 2 --}}
        <section>
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-2 mb-md-0">Most Popular E-Books</h5>
                <a href="{{ route('admin.ebooks.all') }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>

            <div class="row row-cols-2 row-cols-md-6 g-3">
                @forelse($popularEbooks ?? collect() as $ebook)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card card-wrapper position-relative dashboard-card shadow">
                            <span class="badge-status badge-completed">Views: {{ $ebook->views }}</span>
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
                            <div class="p-2">
                                <button class="btn btn-sm btn-outline-primary w-100 view-ebook-details-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewEbookDetailsModal"
                                        data-ebook-id="{{ $ebook->id }}"
                                        data-ebook-title="{{ $ebook->title }}"
                                        data-ebook-author="{{ $ebook->author }}"
                                        data-ebook-views="{{ $ebook->views }}"
                                        data-ebook-cover="{{ $ebook->coverUrl() ?? '' }}"
                                        data-ebook-category="{{ $ebook->category }}"
                                        data-ebook-file="{{ $ebook->fileUrl() ?? '' }}">
                                    <i class="fas fa-eye me-2"></i> View Details
                                </button>
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

<!-- View Book Details Modal -->
<div class="modal fade" id="viewBookDetailsModal" tabindex="-1" aria-labelledby="viewBookDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBookDetailsModalLabel">
                    <i class="fas fa-book me-2 text-primary"></i> Book Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <img id="viewBookCover" src="" alt="Book Cover" class="img-fluid rounded shadow" style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h4 id="viewBookTitle" class="mb-3"></h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user me-2"></i><strong>Author:</strong> <span id="viewBookAuthor"></span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-copy me-2"></i><strong>Available Copies:</strong> 
                            <span class="badge bg-success" id="viewBookCopies"></span>
                        </p>
                        <p class="mb-2" id="viewBookIsbnContainer" style="display: none;">
                            <i class="fas fa-barcode me-2"></i><strong>ISBN:</strong> <span id="viewBookIsbn"></span>
                        </p>
                        <p class="mb-2" id="viewBookPublisherContainer" style="display: none;">
                            <i class="fas fa-building me-2"></i><strong>Publisher:</strong> <span id="viewBookPublisher"></span>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-book me-2"></i><strong>Category:</strong> 
                            <span class="badge bg-primary" id="viewBookCategory"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- View Ebook Details Modal -->
<div class="modal fade" id="viewEbookDetailsModal" tabindex="-1" aria-labelledby="viewEbookDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEbookDetailsModalLabel">
                    <i class="fas fa-book-reader me-2 text-primary"></i> E-Book Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <img id="viewEbookCover" src="" alt="Ebook Cover" class="img-fluid rounded shadow" style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h4 id="viewEbookTitle" class="mb-3"></h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user me-2"></i><strong>Author:</strong> <span id="viewEbookAuthor"></span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-eye me-2"></i><strong>Views:</strong> 
                            <span class="badge bg-info" id="viewEbookViews"></span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-book me-2"></i><strong>Category:</strong> 
                            <span class="badge bg-primary" id="viewEbookCategory"></span>
                        </p>
                        <p class="mb-0" id="viewEbookFileContainer" style="display: none;">
                            <i class="fas fa-file-pdf me-2"></i><strong>File:</strong> 
                            <a id="viewEbookFileLink" href="" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download/View File
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        viewDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookId = this.getAttribute('data-book-id');
                const title = this.getAttribute('data-book-title');
                const author = this.getAttribute('data-book-author');
                const copies = this.getAttribute('data-book-copies');
                const cover = this.getAttribute('data-book-cover') || '{{ Vite::asset('resources/images/bookcover3.jpg') }}';
                const isbn = this.getAttribute('data-book-isbn');
                const publisher = this.getAttribute('data-book-publisher');
                const category = this.getAttribute('data-book-category');
                
                document.getElementById('viewBookTitle').textContent = title;
                document.getElementById('viewBookAuthor').textContent = author;
                document.getElementById('viewBookCopies').textContent = copies;
                document.getElementById('viewBookCategory').textContent = category.charAt(0).toUpperCase() + category.slice(1);
                document.getElementById('viewBookCover').src = cover;
                
                if (isbn) {
                    document.getElementById('viewBookIsbn').textContent = isbn;
                    document.getElementById('viewBookIsbnContainer').style.display = 'block';
                } else {
                    document.getElementById('viewBookIsbnContainer').style.display = 'none';
                }
                
                if (publisher) {
                    document.getElementById('viewBookPublisher').textContent = publisher;
                    document.getElementById('viewBookPublisherContainer').style.display = 'block';
                } else {
                    document.getElementById('viewBookPublisherContainer').style.display = 'none';
                }
            });
        });
    });

    // Ebook Details Modal
    const viewEbookDetailsButtons = document.querySelectorAll('.view-ebook-details-btn');
    viewEbookDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const ebookId = this.getAttribute('data-ebook-id');
            const title = this.getAttribute('data-ebook-title');
            const author = this.getAttribute('data-ebook-author');
            const views = this.getAttribute('data-ebook-views');
            const cover = this.getAttribute('data-ebook-cover') || '{{ Vite::asset('resources/images/bookcover3.jpg') }}';
            const category = this.getAttribute('data-ebook-category');
            const fileUrl = this.getAttribute('data-ebook-file');
            
            document.getElementById('viewEbookTitle').textContent = title;
            document.getElementById('viewEbookAuthor').textContent = author;
            document.getElementById('viewEbookViews').textContent = views;
            document.getElementById('viewEbookCategory').textContent = category.charAt(0).toUpperCase() + category.slice(1);
            document.getElementById('viewEbookCover').src = cover;
            
            if (fileUrl) {
                document.getElementById('viewEbookFileContainer').style.display = 'block';
                document.getElementById('viewEbookFileLink').href = fileUrl;
            } else {
                document.getElementById('viewEbookFileContainer').style.display = 'none';
            }
        });
    });
</script>
@include('Admin.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
