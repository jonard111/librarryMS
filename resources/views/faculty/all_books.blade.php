<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Category (Faculty)</title>
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
        <a href="{{ route('faculty.books') }}" class="nav-link mt-auto">
            <i class="fas fa-arrow-left me-2 text-danger"></i> <span>Back</span> 
        </a>
    </nav>
</div>

<div class="content flex-grow-1 p-4">
    
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>There were some problems with your submission.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @foreach($categories as $slug => $label)
        <section id="{{ $slug }}"> 
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">{{ $label }}</h3>
            </div>

            <div class="row row-cols-2 row-cols-md-6 g-3">
                @forelse(($booksByCategory->get($slug) ?? collect()) as $book)
                    <div class="col-12 col-sm-6 col-md-3 mb-4">
                        <div class="card card-wrapper shadow dashboard-card h-100" style="position: relative;">
                            <span class="badge-status badge bg-success text-white">Copies: {{ $book->copies }}</span>
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
                                    @if(isset($book->hasReservation) && $book->hasReservation)
                                        <button class="btn btn-sm btn-outline-secondary w-100" disabled title="You already have a reservation for this book">
                                            <i class="fas fa-check-circle me-2"></i> Already Reserved
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success w-100 reserve-book-btn" 
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
                                            <i class="fas fa-bookmark me-2"></i> Reserve Now
                                        </button>
                                    @endif
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
            <form id="reserveBookForm" action="" method="POST" onsubmit="return validateReservationForm(event)">
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
                    <div class="mb-3">
                        <label for="loanDurationValue" class="form-label">How long would you like to borrow this book?</label>
                        <div class="input-group">
                            <input 
                                type="number" 
                                class="form-control @error('loan_duration_value') is-invalid @enderror" 
                                id="loanDurationValue" 
                                name="loan_duration_value" 
                                value="{{ old('loan_duration_value', 7) }}" 
                                min="1" 
                                required>
                            <select 
                                class="form-select @error('loan_duration_unit') is-invalid @enderror" 
                                name="loan_duration_unit" 
                                required>
                                <option value="day" @selected(old('loan_duration_unit', 'day') === 'day')>Day(s)</option>
                                <option value="hour" @selected(old('loan_duration_unit') === 'hour')>Hour(s)</option>
                            </select>
                        </div>
                        <div class="form-text">
                            faculty may borrow up to 30 days or 72 hours at a time.
                        </div>
                        @error('loan_duration_value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('loan_duration_unit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
       // Validate form before submission
    function validateReservationForm(event) {
        const form = document.getElementById('reserveBookForm');
        const action = form.getAttribute('action') || form.action;
        const durationValue = document.getElementById('loanDurationValue').value;
        const durationUnit = document.querySelector('[name="loan_duration_unit"]').value;
        
        console.log('Form validation:', {
            action: action,
            durationValue: durationValue,
            durationUnit: durationUnit
        });
        
        // Check if form action is set
        if (!action || action === '' || action === window.location.href) {
            event.preventDefault();
            alert('Error: Form action not set. Please close the modal and try again.');
            console.error('Form action error:', action);
            return false;
        }
        
        // Validate duration
        if (!durationValue || durationValue < 1) {
            event.preventDefault();
            alert('Please enter a valid loan duration.');
            return false;
        }
        
        const maxAllowed = durationUnit === 'hour' ? 72 : 30;
        if (parseInt(durationValue) > maxAllowed) {
            event.preventDefault();
            alert(durationUnit === 'hour' 
                ? 'Hourly loans are limited to 72 hours.' 
                : 'Daily loans are limited to 30 days.');
            return false;
        }
        
        // Show confirmation dialog
        const bookTitle = document.getElementById('reserveBookTitle').textContent;
        const confirmMessage = `Are you sure you want to reserve "${bookTitle}" for ${durationValue} ${durationUnit === 'hour' ? 'hour(s)' : 'day(s)'}?`;
        
        if (!confirm(confirmMessage)) {
            event.preventDefault();
            return false;
        }
        
        console.log('Form is valid, submitting to:', action);
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Reserve Book Modal
        const reserveButtons = document.querySelectorAll('.reserve-book-btn');
        const reserveForm = document.getElementById('reserveBookForm');
        
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
                
                // Check if user already has a reservation for this book
                fetch(`/faculty/books/${bookId}/check-reservation`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.hasReservation) {
                        alert(data.message || 'You already have a reservation for this book. Please check "My Borrowed Books" for details.');
                        // Close modal if open
                        const modal = bootstrap.Modal.getInstance(document.getElementById('reserveBookModal'));
                        if (modal) {
                            modal.hide();
                        }
                        return;
                    }
                    
                    // Set form action
                    const actionUrl = '{{ route("faculty.books.reserve", ":id") }}'.replace(':id', bookId);
                    reserveForm.setAttribute('action', actionUrl);
                    
                    // Populate modal fields
                    document.getElementById('reserveBookTitle').textContent = title;
                    document.getElementById('reserveBookAuthor').textContent = author;
                    document.getElementById('reserveBookCopies').textContent = copies;
                    document.getElementById('reserveBookCategory').textContent = category.charAt(0).toUpperCase() + category.slice(1);
                    document.getElementById('reserveBookCover').src = cover;
                    
                    // Reset form fields
                    document.getElementById('loanDurationValue').value = 7;
                    document.querySelector('[name="loan_duration_unit"]').value = 'day';
                    
                    // Show/hide optional fields
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
                    
                    console.log('Modal opened for book:', { bookId, title, actionUrl });
                })
                .catch(error => {
                    console.error('Error checking reservation:', error);
                    // Continue with modal opening even if check fails
                    const actionUrl = '{{ route("faculty.books.reserve", ":id") }}'.replace(':id', bookId);
                    reserveForm.setAttribute('action', actionUrl);
                });
            });
        });
        
        // Add direct submit handler
        if (reserveForm) {
            reserveForm.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                const action = this.action;
                if (!action || action === '' || action === window.location.href) {
                    e.preventDefault();
                    alert('Error: Cannot submit form. Form action is not set correctly.');
                    console.error('Form action error:', action);
                    return false;
                }
                console.log('Form submitting to:', action);
            });
        }

        // Search functionality for books (unchanged)
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
                        cardText.split('publisher:')[1]?.split('reserve')[0]?.trim() || '' : '';
                    
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