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

    <section>
        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0">Most Popular Books</h5>
            <a href="{{ route('student.books.all') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        {{-- **CHANGE HERE: Adjusted row-cols to display max 4 items** --}}
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 g-3">
            @forelse($popularBooks ?? collect() as $book)
                {{-- **CHANGE HERE: Simplified col classes for consistency with 4 per row** --}}
                <div class="col mb-4">
                    <div class="card card-wrapper position-relative dashboard-card shadow">
                        <span class="badge-status badge-completed text-white">Copies: {{ $book->copies }}</span>
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
                        @if(isset($book->hasReservation) && $book->hasReservation)
                            <button class="btn btn-outline-secondary btn-sm m-2" disabled title="You already have a reservation for this book">
                                <i class="fas fa-check-circle me-1"></i> Already Reserved
                            </button>
                        @else
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
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border text-center">
                        <i class="fas fa-info-circle me-2"></i>No popular books available at the moment.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <section>
        <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0">Most Popular E-Books</h5>
            <a href="{{ route('student.ebooks.all') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        {{-- **CHANGE HERE: Adjusted row-cols to display max 4 items** --}}
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 g-3">
            @forelse($popularEbooks ?? collect() as $ebook)
                {{-- **CHANGE HERE: Simplified col classes for consistency with 4 per row** --}}
                <div class="col mb-4">
                    <div class="card card-wrapper position-relative dashboard-card shadow">
                        <span class="badge-status badge-completed text-white">Views: {{ $ebook->views }}</span>
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
                        <i class="fas fa-info-circle me-2"></i>No popular e-books available at the moment.
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>


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
                            Students may borrow up to 30 days or 72 hours at a time.
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
                fetch(`/student/books/${bookId}/check-reservation`, {
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
                    const actionUrl = '{{ route("student.books.reserve", ":id") }}'.replace(':id', bookId);
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
                    const actionUrl = '{{ route("student.books.reserve", ":id") }}'.replace(':id', bookId);
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