<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Category</title>
    @vite(['resources/css/book.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    </head>

<body class="bg-light d-flex">
    
    <input type="checkbox" id="sidebar-toggle">

<div class="sidebar d-flex flex-column">
    <div class="text-center mb-4 profile-info">
      <h2>Category</h2>
    </div>
    <nav class="nav flex-column text-start flex-grow-1">
        <a href="#education" class="nav-link">
            <i class="fas fa-book me-2"></i><span> Education & Learning</span>
        </a>
        <a href="#science" class="nav-link">
            <i class="fas fa-atom me-2"></i> <span>Science & Technology</span> 
        </a>
        <a href="#literature" class="nav-link">
            <i class="fas fa-feather-alt me-2"></i> <span>Literature / Fiction</span>
        </a>
        <a href="#history" class="nav-link">
            <i class="fas fa-landmark me-2"></i> <span>History</span> 
        </a>
        <a href="#selfhelp" class="nav-link">
            <i class="fas fa-lightbulb me-2"></i> <span>Self-Help / Motivation</span> 
        </a>
        <a href="{{ route('admin.books') }}" class="nav-link mt-auto">
            <i class="fas fa-arrow-left me-2 text-danger"></i> <span>Back</span> 
        </a>
    </nav>
</div>

<div class="content flex-grow-1 p-4">
    
    <!-- Top Header -->
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

    <div class="sidebar-overlay"></div>

    @if(isset($categories) && isset($booksByCategory))
    @foreach($categories as $slug => $label)
        <section id="{{ $slug }}"> 
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">{{ $label }}</h3>
            </div>

            <div class="row row-cols-2 row-cols-md-6 g-3">
                @forelse(($booksByCategory->get($slug) ?? collect()) as $book)
                    <div class="col-12 col-sm-6 col-md-3 mb-4">
                        <div class="card card-wrapper shadow dashboard-card h-100" style="position: relative;">
                            <span class="badge-status badge-completed text-white">Copies: {{ $book->copies }}</span>
                            <div style="width: 100%; height: 220px; overflow: hidden; background-color: #f0f0f0;">
                                <img src="{{ $book->coverUrl() ?? 'https://placehold.co/300x420?text=No+Cover' }}" 
                                     class="card-img-top" 
                                     alt="{{ $book->title }}"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;" 
                                     onerror="this.onerror=null; this.src='https://placehold.co/300x420?text=No+Cover';" />
                            </div>
                            <div class="card-body p-2">
                                <h5 class="card-title mb-1 text-truncate">{{ $book->title }}</h5>
                                <small class="card-subtitle text-muted">{{ $book->author }}</small>
                                <div class="mt-2">
                                    <small class="d-block text-muted">ISBN: {{ $book->isbn ?? 'N/A' }}</small>
                                    <small class="d-block text-muted">Publisher: {{ $book->publisher ?? 'N/A' }}</small>
                                </div>
                                <div class="mt-3">
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
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border text-center mb-0">
                            <i class="fas fa-info-circle me-2"></i>No books recorded in this category yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        @if (!$loop->last)
            <hr>
        @endif
    @endforeach
    @else
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>Error: Data not loaded. Please check the controller.
        </div>
    @endif

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
                const cover = this.getAttribute('data-book-cover') || 'https://placehold.co/300x420?text=No+Cover';
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

        // Search functionality for books
        const searchBookInput = document.getElementById('searchBookInput');
        if (searchBookInput) {
            searchBookInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const bookCards = document.querySelectorAll('.card.card-wrapper');
                
                bookCards.forEach(card => {
                    const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
                    const author = card.querySelector('.card-subtitle')?.textContent.toLowerCase() || '';
                    const cardText = card.textContent.toLowerCase();
                    const isbn = cardText.includes('isbn:') ? 
                        cardText.split('isbn:')[1]?.split('publisher')[0]?.trim() || '' : '';
                    const publisher = cardText.includes('publisher:') ? 
                        cardText.split('publisher:')[1]?.split('view')[0]?.trim() || '' : '';
                    
                    const matches = title.includes(searchTerm) || 
                                   author.includes(searchTerm) || 
                                   isbn.includes(searchTerm) || 
                                   publisher.includes(searchTerm);
                    
                    const cardContainer = card.closest('.col-12, .col-sm-6, .col-md-3');
                    if (cardContainer) {
                        cardContainer.style.display = matches ? '' : 'none';
                    }
                });
            });
        }
    });
</script>
</body>
</html>
