<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ebook->title }} - E-Book</title>
    @vite(['resources/js/app.js', 'resources/css/book.css', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .ebook-viewer {
            width: 100%;
            height: 80vh;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
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

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow">
                    <img src="{{ $ebook->coverUrl() ?? Vite::asset('resources/images/bookcover3.jpg') }}" 
                         class="card-img-top" 
                         alt="{{ $ebook->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ebook->title }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user me-2"></i>{{ $ebook->author }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-eye me-2"></i><strong>Views:</strong> {{ $ebook->views }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-book me-2"></i><strong>Category:</strong> 
                            <span class="badge bg-primary">{{ ucfirst($ebook->category) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-book-reader me-2"></i>{{ $ebook->title }}
                        </h4>
                    </div>
                    <div class="card-body">
                        @if($ebook->fileUrl())
                            @if(pathinfo($ebook->file_path, PATHINFO_EXTENSION) === 'pdf')
                                <iframe src="{{ $ebook->fileUrl() }}" class="ebook-viewer"></iframe>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>This e-book format requires a compatible reader.
                                    <a href="{{ $ebook->fileUrl() }}" target="_blank" class="btn btn-primary btn-sm ms-2" download>
                                        <i class="fas fa-download me-2"></i>Download E-Book
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>E-book file is not available.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('student.partials.profile-modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

