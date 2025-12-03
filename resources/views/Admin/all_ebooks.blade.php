<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Books category</title>
    @vite(['resources/css/book.css'])
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
      </head>
<div class="contaner">
<body class="bg-light">
    
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


<div class="content flex-grow-1 ">
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
            <span class="fw-bold fs-6">Library MS</span> <small class="text-muted" style="font-size:0.85rem;">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
    </div>
  </div>


<section id="education"> 

  <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="mb-2 mb-md-0">Education & Learning</h3>
  </div>
  <div class="row row-cols-2 row-cols-md-6 g-3">

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 4</span>
        <img src="{{ Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="How to Study Smart" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">How to Study Smart</h5>
          <small class="card-subtitle text-muted">Adam Robinson</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 3</span>
        <img src="{{ Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="Mindset" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">Mindset</h5>
          <small class="card-subtitle text-muted">Carol S. Dweck</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 5</span>
        <img src="{{ Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="Make It Stick" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">Make It Stick</h5>
          <small class="card-subtitle text-muted">Peter C. Brown</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow  dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 4</span>
        <img src="{{ Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="The Learning Habit" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Learning Habit</h5>
          <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
        </div>
      </div>
    </div>
  </div>
</section>


<section id="science">
  <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="mb-2 mb-md-0">Science & Technology</h3>
  </div>
  <div class="row row-cols-2 row-cols-md-6 g-3">

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 6</span>
        <img src="{{ Vite::asset('resources/images/bookcover3.jpg') }}" class="card-img-top" alt="Brief History of Time" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">History of Time</h5>
          <small class="card-subtitle text-muted">Stephen Hawking</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 3</span>
        <img src="../images/bookcover1.jpg" class="card-img-top" alt="Astrophysics for People in a Hurry" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">Astrophysics for People in a Hurry</h5>
          <small class="card-subtitle text-muted">Neil deGrasse Tyson</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 1</span>
        <img src="../images/bookcover3.jpg" class="card-img-top" alt="The Selfish Gene" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Selfish Gene</h5>
          <small class="card-subtitle text-muted">Richard Dawkins</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 9</span>
        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Code Book" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Code Book</h5>
          <small class="card-subtitle text-muted">Simon Singh</small>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="literature">
  <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="mb-2 mb-md-0">Literature / Fiction</h3>
  </div>
  <div class="row row-cols-2 row-cols-md-6 g-3">

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 5</span>
        <img src="../images/bookcover1.jpg" class="card-img-top" alt="To Kill a Mockingbird" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">To Kill a Mockingbird</h5>
          <small class="card-subtitle text-muted">Harper Lee</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 6</span>
        <img src="../images/bookcover3.jpg" class="card-img-top" alt="The Great Gatsby" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Great Gatsby</h5>
          <small class="card-subtitle text-muted">F. Scott Fitzgerald</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 8</span>
        <img src="../images/bookcover1.jpg" class="card-img-top" alt="1984" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">1984</h5>
          <small class="card-subtitle text-muted">George Orwell</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 3</span>
        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Catcher in the Rye" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Catcher in the Rye</h5>
          <small class="card-subtitle text-muted">J.D. Salinger</small>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="history">
  <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="mb-2 mb-md-0">History</h3>
  </div>
  <div class="row row-cols-2 row-cols-md-6 g-3">

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 2</span>
        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Sapiens" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">History of Humankind</h5>
          <small class="card-subtitle text-muted">Yuval Noah Harari</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 8</span>
        <img src="../images/bookcover1.jpg" class="card-img-top" alt="Guns, Germs, and Steel" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">Guns, Germs, and Steel</h5>
          <small class="card-subtitle text-muted">Jared Diamond</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 5</span>
        <img src="../images/bookcover3.jpg" class="card-img-top" alt="The Diary of a Young Girl" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Diary of a Young Girl</h5>
          <small class="card-subtitle text-muted">Anne Frank</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 1</span>
        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Second World War" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Second World War</h5>
          <small class="card-subtitle text-muted">Antony Beevor</small>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="selfhelp" >
  <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3 ">
    <h3 class="mb-2 mb-md-0">Self-Help / Motivation</h3>
  </div>
  <div class="row row-cols-2 row-cols-md-6 g-3">

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 5</span>
        <img src="../images/bookcover1.jpg" class="card-img-top" alt="Atomic Habits" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">Atomic Habits</h5>
          <small class="card-subtitle text-muted">James Clear</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 4</span>
        <img src="../images/bookcover3.jpg" class="card-img-top" alt="The 7 Habits" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The 7 Habits </h5>
          <small class="card-subtitle text-muted">Stephen R. Covey</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 5</span>
        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Power of Now" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">The Power of Now</h5>
          <small class="card-subtitle text-muted">Eckhart Tolle</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="card card-wrapper shadow dashboard-card">
        <span class="badge-status badge-completed">Borrowed: 9</span>
        <img src="../images/bookcover1.jpg" class="card-img-top" alt="You Are a Badass" />
        <div class="card-body p-2">
          <h5 class="card-title mb-1">You Are a Badass</h5>
          <small class="card-subtitle text-muted">Jen Sincero</small>
        </div>
      </div>
    </div>
  </div>
</section>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality for ebooks
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
</body>
</html>
