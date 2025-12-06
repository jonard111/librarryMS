<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Books Category</title>
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

    <!-- Top Header -->
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3"> 
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
            <div class="input-group">
                <input type="text" class="form-control" id="searchEbookInput" placeholder="Search e-book">
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

    @foreach($categories as $slug => $label)
        <section id="{{ $slug }}"> 
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">{{ $label }}</h3>
            </div>

            <div class="row row-cols-2 row-cols-md-6 g-3">
                @forelse(($ebooksByCategory->get($slug) ?? collect()) as $ebook)
                    <div class="col-12 col-sm-6 col-md-3 mb-4">
                        <div class="card card-wrapper shadow dashboard-card h-100" style="position: relative;">
                            <span class="badge-status badge-completed text-white">Views: {{ $ebook->views }}</span>
                            <div style="width: 100%; height: 220px; overflow: hidden; background-color: #f0f0f0;">
                                <img src="{{ $ebook->coverUrl() ?? 'https://placehold.co/300x420?text=No+Cover' }}" 
                                     class="card-img-top" 
                                     alt="{{ $ebook->title }}"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;" 
                                     onerror="this.onerror=null; this.src='https://placehold.co/300x420?text=No+Cover';" />
                            </div>
                            <div class="card-body p-2">
                                <h5 class="card-title mb-1 text-truncate">{{ $ebook->title }}</h5>
                                <small class="card-subtitle text-muted">{{ $ebook->author }}</small>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-success w-100 read-ebook-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#readEbookModal"
                                            data-ebook-id="{{ $ebook->id }}"
                                            data-ebook-title="{{ $ebook->title }}"
                                            data-ebook-author="{{ $ebook->author }}"
                                            data-ebook-cover="{{ $ebook->coverUrl() ?? '' }}"
                                            data-ebook-file="{{ $ebook->fileUrl() ?? '' }}"
                                            data-ebook-category="{{ $ebook->category }}"
                                            data-ebook-views="{{ $ebook->views }}">
                                        <i class="fas fa-book-reader me-2"></i> Read Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border text-center mb-0">
                            <i class="fas fa-info-circle me-2"></i>No e-books recorded in this category yet.
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
                fetch('{{ route("faculty.ebooks.show", ":id") }}'.replace(':id', ebookId), {
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

        // Search functionality for ebooks
        const searchEbookInput = document.getElementById('searchEbookInput');
        if (searchEbookInput) {
            searchEbookInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const ebookCards = document.querySelectorAll('.card.card-wrapper');
                
                ebookCards.forEach(card => {
                    const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
                    const author = card.querySelector('.card-subtitle')?.textContent.toLowerCase() || '';
                    const cardText = card.textContent.toLowerCase();
                    
                    const matches = title.includes(searchTerm) || 
                                   author.includes(searchTerm);
                    
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
