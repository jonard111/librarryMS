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
 
    <!-- Sidebar -->
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
            <a href="{{ route('head.books') }}" class="nav-link mt-auto">
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

        @if(session('book_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('book_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>There were some problems with your submission.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @foreach($categories as $slug => $label)
            <section id="{{ $slug }}"> 
                <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h3 class="mb-2 mb-md-0">{{ $label }}</h3>
                    @if ($loop->first)
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addBookModal">
                            <i class="fas fa-plus-circle"></i> Add New Book
                        </button>
                    @endif
                </div>

                <div class="row row-cols-2 row-cols-md-6 g-3">
                    @forelse(($booksByCategory->get($slug) ?? collect()) as $book)
                        <div class="col-12 col-sm-6 col-md-3 mb-4">
                            <div class="card card-wrapper shadow dashboard-card h-100">
                                <span class="badge-status badge-completed text-white">Copies: {{ $book->copies }}</span>
                                <img src="{{ $book->coverUrl() ?? 'https://placehold.co/300x420?text=No+Cover' }}" class="card-img-top" alt="{{ $book->title }}" />
                                <div class="card-body p-2">
                                    <h5 class="card-title mb-1 text-truncate">{{ $book->title }}</h5>
                                    <small class="card-subtitle text-muted">{{ $book->author }}</small>
                                    <div class="mt-2">
                                        <small class="d-block text-muted">ISBN: {{ $book->isbn ?? 'N/A' }}</small>
                                        <small class="d-block text-muted">Publisher: {{ $book->publisher ?? 'N/A' }}</small>
                                    </div>
                                    <div class="mt-3 d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary flex-fill edit-book-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBookModal"
                                                data-book-id="{{ $book->id }}"
                                                data-book-title="{{ $book->title }}"
                                                data-book-author="{{ $book->author }}"
                                                data-book-isbn="{{ $book->isbn ?? '' }}"
                                                data-book-publisher="{{ $book->publisher ?? '' }}"
                                                data-book-category="{{ $book->category }}"
                                                data-book-copies="{{ $book->copies }}"
                                                data-book-cover="{{ $book->coverUrl() ?? '' }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('head.books.destroy', $book->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
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

    </div> 

    <!-- Add New Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">
                        <i class="fas fa-book-medical me-2 text-success"></i> Add New Book Inventory
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addNewBookForm" action="{{ route('head.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bookTitle" class="form-label">Book Title</label>
                            <input type="text" class="form-control" id="bookTitle" name="title" value="{{ old('title') }}" placeholder="Enter the exact title" required>
                        </div>
                        <div class="mb-3">
                            <label for="bookAuthor" class="form-label">Author Name</label>
                            <input type="text" class="form-control" id="bookAuthor" name="author" value="{{ old('author') }}" placeholder="e.g., James Clear" required>
                        </div>
                        <div class="mb-3">
                            <label for="bookIsbn" class="form-label">ISBN-13</label>
                            <input type="text" class="form-control" id="bookIsbn" name="isbn" value="{{ old('isbn') }}" placeholder="e.g., 978-0735211292">
                        </div>
                        <div class="mb-3">
                            <label for="bookCover" class="form-label">Book Cover Image</label>
                            <input class="form-control" type="file" id="bookCover" name="cover" accept="image/jpeg, image/png, image/webp">
                            <div class="form-text">Accepted formats: JPG, PNG, WEBP. Max size: 2MB.</div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="bookCategory" class="form-label">Category</label>
                                <select class="form-select" id="bookCategory" name="category" required>
                                    <option selected disabled value="">Choose...</option>
                                    @foreach($categories as $slug => $label)
                                        <option value="{{ $slug }}" @selected(old('category') === $slug)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="numCopies" class="form-label">Number of Copies</label>
                                <input type="number" class="form-control" id="numCopies" name="copies" min="1" value="{{ old('copies', 1) }}" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="bookPublisher" class="form-label">Publisher</label>
                            <input type="text" class="form-control" id="bookPublisher" name="publisher" value="{{ old('publisher') }}" placeholder="e.g., Penguin Books">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i> Add Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">
                        <i class="fas fa-edit me-2 text-primary"></i> Edit Book
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBookForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editBookTitle" class="form-label">Book Title</label>
                            <input type="text" class="form-control" id="editBookTitle" name="title" placeholder="Enter the exact title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBookAuthor" class="form-label">Author Name</label>
                            <input type="text" class="form-control" id="editBookAuthor" name="author" placeholder="e.g., James Clear" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBookIsbn" class="form-label">ISBN-13</label>
                            <input type="text" class="form-control" id="editBookIsbn" name="isbn" placeholder="e.g., 978-0735211292">
                        </div>
                        <div class="mb-3">
                            <label for="editBookCover" class="form-label">Book Cover Image</label>
                            <div class="mb-2">
                                <img id="editBookCoverPreview" src="" alt="Current cover" class="img-thumbnail" style="max-height: 150px; display: none;">
                            </div>
                            <input class="form-control" type="file" id="editBookCover" name="cover" accept="image/jpeg, image/png, image/webp">
                            <div class="form-text">Accepted formats: JPG, PNG, WEBP. Max size: 2MB. Leave empty to keep current cover.</div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="editBookCategory" class="form-label">Category</label>
                                <select class="form-select" id="editBookCategory" name="category" required>
                                    <option selected disabled value="">Choose...</option>
                                    @foreach($categories as $slug => $label)
                                        <option value="{{ $slug }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editNumCopies" class="form-label">Number of Copies</label>
                                <input type="number" class="form-control" id="editNumCopies" name="copies" min="1" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="editBookPublisher" class="form-label">Publisher</label>
                            <input type="text" class="form-control" id="editBookPublisher" name="publisher" placeholder="e.g., Penguin Books">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-book-btn');
            
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const bookId = this.getAttribute('data-book-id');
                    const title = this.getAttribute('data-book-title');
                    const author = this.getAttribute('data-book-author');
                    const isbn = this.getAttribute('data-book-isbn') || '';
                    const publisher = this.getAttribute('data-book-publisher') || '';
                    const category = this.getAttribute('data-book-category');
                    const copies = this.getAttribute('data-book-copies');
                    const coverUrl = this.getAttribute('data-book-cover');
                    
                    document.getElementById('editBookForm').action = '{{ route("head.books.update", ":id") }}'.replace(':id', bookId);
                    document.getElementById('editBookTitle').value = title;
                    document.getElementById('editBookAuthor').value = author;
                    document.getElementById('editBookIsbn').value = isbn;
                    document.getElementById('editBookPublisher').value = publisher;
                    document.getElementById('editBookCategory').value = category;
                    document.getElementById('editNumCopies').value = copies;
                    
                    const coverPreview = document.getElementById('editBookCoverPreview');
                    if (coverUrl) {
                        coverPreview.src = coverUrl;
                        coverPreview.style.display = 'block';
                    } else {
                        coverPreview.style.display = 'none';
                    }
                });
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
                        cardText.split('publisher:')[1]?.split('edit')[0]?.trim() || '' : '';
                    
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
    </script>
</body>
</html>
