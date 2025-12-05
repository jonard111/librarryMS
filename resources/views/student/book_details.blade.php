<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - Book Details</title>
    @vite(['resources/js/app.js', 'resources/css/book.css', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
            <a href="{{ route('student.dashboard') }}" class="nav-link">
                <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('student.books') }}" class="nav-link active">
                <i class="fas fa-book-open me-2"></i><span>Books</span>
            </a>
            <a href="{{ route('student.borrowed') }}" class="nav-link">
                <i class="fas fa-file-alt me-2"></i><span>My Borrowed Books</span>
            </a>
            <a href="{{ route('student.notifications') }}" class="nav-link">
                <i class="fas fa-bell me-2"></i><span>Notifications</span>
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

    <div class="content flex-grow-1 p-4">
        <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <div class="d-flex align-items-center gap-2">
                <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
                <a href="{{ route('student.books') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Back to Books
                </a>
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

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <img src="{{ $book->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" 
                         class="card-img-top" 
                         alt="{{ $book->title }}" 
                         style="max-height: 500px; object-fit: cover;">
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-3">{{ $book->title }}</h2>
                        <p class="text-muted mb-3">
                            <i class="fas fa-user me-2"></i><strong>Author:</strong> {{ $book->author }}
                        </p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-book me-2"></i><strong>Category:</strong> 
                                    <span class="badge bg-primary">{{ ucfirst($book->category) }}</span>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-copy me-2"></i><strong>Available Copies:</strong> 
                                    <span class="badge bg-success">{{ $book->copies }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($book->isbn)
                                <p class="mb-2">
                                    <i class="fas fa-barcode me-2"></i><strong>ISBN:</strong> {{ $book->isbn }}
                                </p>
                                @endif
                                @if($book->publisher)
                                <p class="mb-2">
                                    <i class="fas fa-building me-2"></i><strong>Publisher:</strong> {{ $book->publisher }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Reading List Actions -->
                        <div class="mb-3">
                            @if($inReadingList ?? false)
                                <form action="{{ route('student.reading-list.remove', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-warning">
                                        <i class="fas fa-bookmark me-2"></i>Remove from Reading List
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('student.reading-list.add', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-bookmark me-2"></i>Add to Reading List
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($hasReservation)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>You already have an active reservation for this book.
                            </div>
                        @else
                            <form action="{{ route('student.books.reserve', $book->id) }}" method="POST" onsubmit="return confirmReservation(this, event)">
                                @csrf
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
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-bookmark me-2"></i>Reserve Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('student.partials.profile-modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmReservation(form, event) {
            const durationValue = document.getElementById('loanDurationValue').value;
            const durationUnit = document.querySelector('[name="loan_duration_unit"]').value;
            const bookTitle = '{{ $book->title }}';
            
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
            const confirmMessage = `Are you sure you want to reserve "${bookTitle}" for ${durationValue} ${durationUnit === 'hour' ? 'hour(s)' : 'day(s)'}?`;
            
            if (!confirm(confirmMessage)) {
                event.preventDefault();
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>

