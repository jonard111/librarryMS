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
            <a href="{{ route('assistant.manageBooks') }}" class="nav-link mt-auto">
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

        @if(session('ebook_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('ebook_success') }}
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

        <!-- Sections -->
        @foreach($categories as $id => $title)
        <section id="{{ $id }}">
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">{{ $title }}</h3>
            </div>

            <div class="row row-cols-2 row-cols-md-6 g-3">
                @forelse(($ebooksByCategory->get($id) ?? collect()) as $ebook)
                    <div class="col-12 col-sm-6 col-md-3 mb-4">
                        <div class="card card-wrapper shadow dashboard-card h-100">
                            <span class="badge-status badge-completed">Views: {{ $ebook->views }}</span>
                            <img src="{{ $ebook->coverUrl() ?? 'https://placehold.co/300x420?text=No+Cover' }}" class="card-img-top" alt="{{ $ebook->title }}" />
                            <div class="card-body p-2 d-flex flex-column">
                                <h5 class="card-title mb-1 text-truncate">{{ $ebook->title }}</h5>
                                <small class="card-subtitle text-muted">{{ $ebook->author }}</small>
                                <div class="mt-auto d-flex gap-2 mt-3">
                                    @if($ebook->fileUrl())
                                        <a href="{{ $ebook->fileUrl() }}" target="_blank" class="btn btn-sm btn-outline-success flex-fill" title="Open">
                                            <i class="fas fa-external-link-alt"></i> Open
                                        </a>
                                    @endif
                                    <button class="btn btn-sm btn-outline-primary flex-fill edit-ebook-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editEBookModal"
                                            data-ebook-id="{{ $ebook->id }}"
                                            data-ebook-title="{{ $ebook->title }}"
                                            data-ebook-author="{{ $ebook->author }}"
                                            data-ebook-category="{{ $ebook->category }}"
                                            data-ebook-cover="{{ $ebook->coverUrl() ?? '' }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('assistant.ebooks.destroy', $ebook->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to delete this e-book?');">
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
                            <i class="fas fa-info-circle me-2"></i>No e-books recorded in this category yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
        <hr>
        @endforeach

    </div> 

    <!-- Edit E-Book Modal -->
    <div class="modal fade" id="editEBookModal" tabindex="-1" aria-labelledby="editEBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEBookModalLabel">
                        <i class="fas fa-edit me-2 text-primary"></i> Edit E-Book
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editEBookForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editEbookTitle" class="form-label">Book Title</label>
                            <input type="text" class="form-control" id="editEbookTitle" name="title" placeholder="Enter the exact title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEbookAuthor" class="form-label">Author Name</label>
                            <input type="text" class="form-control" id="editEbookAuthor" name="author" placeholder="e.g., James Clear" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEbookFile" class="form-label">E-Book File Upload</label>
                            <input class="form-control" type="file" id="editEbookFile" name="ebook_file" accept=".pdf,.epub,.mobi">
                            <div class="form-text">Accepted formats: PDF, EPUB, MOBI. File size limit: 50MB. Leave empty to keep current file.</div>
                        </div>
                        <div class="mb-3">
                            <label for="editEbookCover" class="form-label">Book Cover Image</label>
                            <div class="mb-2">
                                <img id="editEbookCoverPreview" src="" alt="Current cover" class="img-thumbnail" style="max-height: 150px; display: none;">
                            </div>
                            <input class="form-control" type="file" id="editEbookCover" name="cover" accept="image/jpeg,image/png">
                            <div class="form-text">Accepted formats: JPG, PNG. Max size: 2MB. Leave empty to keep current cover.</div>
                        </div>
                        <div class="mb-3">
                            <label for="editEbookCategory" class="form-label">Category</label>
                            <select class="form-select" id="editEbookCategory" name="category" required>
                                <option selected disabled value="">Choose...</option>
                                @foreach($categories as $slug => $label)
                                    <option value="{{ $slug }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update E-Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-ebook-btn');
            
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const ebookId = this.getAttribute('data-ebook-id');
                    const title = this.getAttribute('data-ebook-title');
                    const author = this.getAttribute('data-ebook-author');
                    const category = this.getAttribute('data-ebook-category');
                    const coverUrl = this.getAttribute('data-ebook-cover');
                    
                    document.getElementById('editEBookForm').action = '{{ route("assistant.ebooks.update", ":id") }}'.replace(':id', ebookId);
                    document.getElementById('editEbookTitle').value = title;
                    document.getElementById('editEbookAuthor').value = author;
                    document.getElementById('editEbookCategory').value = category;
                    
                    const coverPreview = document.getElementById('editEbookCoverPreview');
                    if (coverUrl) {
                        coverPreview.src = coverUrl;
                        coverPreview.style.display = 'block';
                    } else {
                        coverPreview.style.display = 'none';
                    }
                });
            });
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
                
                const matches = title.includes(searchTerm) || 
                               author.includes(searchTerm);
                
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
