<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Books Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
     @vite(['resources/css/book.css'])
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
            <input type="text" class="form-control" id="searchEbookInput" placeholder="Search e-book">
            <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="d-flex flex-column text-end">
            <span class="fw-bold fs-6">Library MS</span> <small class="text-muted" style="font-size:0.85rem;">Management System</small>
        </div>
         <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
    </div>
  </div>


        <section id="education"> 
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">Education & Learning</h3>
            </div>

            <div class="row row-cols-2 row-cols-md-6 g-3">
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover1.jpg" class="card-img-top" alt="How to Study Smart" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">How to Study Smart</h5>
                            <small class="card-subtitle text-muted">Adam Robinson</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Mindset" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Mindset</h5>
                            <small class="card-subtitle text-muted">Carol S. Dweck</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover3.jpg" class="card-img-top" alt="Make It Stick" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Make It Stick</h5>
                            <small class="card-subtitle text-muted">Peter C. Brown</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Learning Habit" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">The Learning Habit</h5>
                            <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="science">
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">Science & Technology</h3>
            </div>
             <div class="row row-cols-2 row-cols-md-6 g-3">
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover1.jpg" class="card-img-top" alt="How to Study Smart" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">How to Study Smart</h5>
                            <small class="card-subtitle text-muted">Adam Robinson</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Mindset" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Mindset</h5>
                            <small class="card-subtitle text-muted">Carol S. Dweck</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover3.jpg" class="card-img-top" alt="Make It Stick" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Make It Stick</h5>
                            <small class="card-subtitle text-muted">Peter C. Brown</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Learning Habit" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">The Learning Habit</h5>
                            <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="literature">
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">Literature / Fiction</h3>
            </div>
             <div class="row row-cols-2 row-cols-md-6 g-3">
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover1.jpg" class="card-img-top" alt="How to Study Smart" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">How to Study Smart</h5>
                            <small class="card-subtitle text-muted">Adam Robinson</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Mindset" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Mindset</h5>
                            <small class="card-subtitle text-muted">Carol S. Dweck</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover3.jpg" class="card-img-top" alt="Make It Stick" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Make It Stick</h5>
                            <small class="card-subtitle text-muted">Peter C. Brown</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Learning Habit" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">The Learning Habit</h5>
                            <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="history">
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h3 class="mb-2 mb-md-0">History</h3>
            </div>
             <div class="row row-cols-2 row-cols-md-6 g-3">
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover1.jpg" class="card-img-top" alt="How to Study Smart" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">How to Study Smart</h5>
                            <small class="card-subtitle text-muted">Adam Robinson</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Mindset" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Mindset</h5>
                            <small class="card-subtitle text-muted">Carol S. Dweck</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover3.jpg" class="card-img-top" alt="Make It Stick" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Make It Stick</h5>
                            <small class="card-subtitle text-muted">Peter C. Brown</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Learning Habit" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">The Learning Habit</h5>
                            <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="selfhelp">
            <div class="section-header d-flex flex-wrap justify-content-between align-items-center mb-3 ">
                <h3 class="mb-2 mb-md-0">Self-Help / Motivation</h3>
            </div>
             <div class="row row-cols-2 row-cols-md-6 g-3">
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover1.jpg" class="card-img-top" alt="How to Study Smart" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">How to Study Smart</h5>
                            <small class="card-subtitle text-muted">Adam Robinson</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="Mindset" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Mindset</h5>
                            <small class="card-subtitle text-muted">Carol S. Dweck</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover3.jpg" class="card-img-top" alt="Make It Stick" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">Make It Stick</h5>
                            <small class="card-subtitle text-muted">Peter C. Brown</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card card-wrapper shadow dashboard-card">
                        <img src="../images/bookcover2.jpg" class="card-img-top" alt="The Learning Habit" />
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">The Learning Habit</h5>
                            <small class="card-subtitle text-muted">Stephanie Donaldson-Pressman</small>
                        </div>
                         <button class="btn btn-outline-success btn-sm m-2">Read Now</button>
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
