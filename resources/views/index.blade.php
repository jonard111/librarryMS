<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNSC Library Management System</title>
     @vite(['resources/js/app.js', 'resources/css/style.css'])
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    </head>
<body>
    <!-- Header -->
    <header class="header-nav sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white-blur">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="#home">
                    <div class="logo-icon m-3">
                        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="" width="70px">
                        
                    </div>
                    <div>
                        <h1 class="logo-title mb-0">DNSC LibraryMS</h1>
                        <small class="logo-subtitle text-muted">Digital Library System</small>
                    </div>
                </a>
                
                <!-- Mobile Toggle -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="bi bi-list"></i>
                </button>
                
                <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#about">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#features">Features</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#contact">Contact</a>
        </li>
        <!-- Announcement Nav Item -->
        <li class="nav-item">
            <a class="nav-link" href="#announcements">
                <i class="bi me-1"></i> Announcements
            </a>
        </li>
    </ul>
</div>
                    
                    <!-- CTA Button -->
                    <a href="{{ route('register.form') }}"> <button class="btn btn-gradient-green text-white rounded-pill px-4">
                        Access Library Now
                    </button>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-bg-pattern"></div>
        <div class="container">
            <div class="row align-items-center min-vh-100 py-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="hero-content animate-fade-in">
                        <!-- Badge -->
                        <div class="hero-badge mb-4">
                            <div class="status-dot"></div>
                            <span>Now Live • DNSC Digital Library</span>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="hero-title mb-4">
                            Modern Library
                            <span class="text-gradient">Management</span>
                            <span class="d-block">System</span>
                        </h1>
                        
                        <!-- Description -->
                        <p class="hero-description mb-5">
                            Transform your library experience with our comprehensive digital platform. 
                            Manage books, access e-resources, and streamline operations with cutting-edge technology.
                        </p>
                        
                        <br><br><br>
                        
                        <!-- Stats -->
                        <div class="hero-stats row pt-4">
                            <div class="col-4 text-center text-lg-start">
                                <div class="stat-number">10,000+</div>
                                <div class="stat-label">Digital Books</div>
                            </div>
                            <div class="col-4 text-center text-lg-start">
                                <div class="stat-number">5,000+</div>
                                <div class="stat-label">Active Students</div>
                            </div>
                            <div class="col-4 text-center text-lg-start">
                                <div class="stat-number">99.9%</div>
                                <div class="stat-label">Uptime</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 order-1 order-lg-2 mb-5 mb-lg-0">
                    <div class="hero-image-container">
                        <div class="hero-image-bg"></div>
                        <div class="hero-image">
                            <img src="{{ Vite::asset('resources/images/library1.jpg') }}"
                                 alt="Modern library interior" class="img-fluid">
                            
                            <!-- Floating Stats -->
                            <div class="floating-stat floating-stat-1">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon me-3">
                                        <i class="bi bi-book text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Available Now</div>
                                        <small class="text-muted">2,847 Books</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="floating-stat floating-stat-2">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon me-3">
                                        <div class="online-indicator">
                                            <div class="online-dot"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Online Users</div>
                                        <small class="text-muted">1,245 Active</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Announcements Section (Feature-style) -->
<section id="announcements" class="announcements-section py-5 bg-light">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <div class="section-badge mb-3">
                <i class="bi bi-megaphone text-success me-2"></i>
                <span>Latest Updates</span>
            </div>
            <h2 class="section-title mb-3">
                Library <span class="text-gradient">Announcements</span>
            </h2>
            <p class="text-muted">Stay updated with the latest events, news, and important notices from DNSC Library.</p>
        </div>

        <!-- Announcements Cards -->
        <div class="row g-4">
            <!-- Announcement 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card feature-card-blue h-100">
                    <div class="feature-header">
                        <div class="feature-icon feature-icon-blue">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <span class="badge bg-light text-dark">Upcoming</span>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Library Orientation 2025</h3>
                        <p class="feature-description">
                            Join us for the library orientation on <strong>October 15, 2025</strong>. Learn how to use our new digital resources and services.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Announcement 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card feature-card-green h-100">
                    <div class="feature-header">
                        <div class="feature-icon feature-icon-green">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <span class="badge bg-light text-dark">New</span>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">New E-Books Available</h3>
                        <p class="feature-description">
                            We've added <strong>500+ new e-books</strong> across all subjects. Access them anytime via the digital library.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Announcement 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card feature-card-orange h-100">
                    <div class="feature-header">
                        <div class="feature-icon feature-icon-orange">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <span class="badge bg-light text-dark">Notice</span>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Scheduled Maintenance</h3>
                        <p class="feature-description">
                            The digital library system will undergo maintenance on <strong>October 20, 2025 (8 PM - 10 PM)</strong>. Please plan your research ahead.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Features Section -->
    <section id="features" class="features-section py-5">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <div class="section-badge mb-4">
                    <i class="bi bi-lightning text-success me-2"></i>
                    <span>Powerful Features</span>
                </div>
                
                <h2 class="section-title mb-4">
                    Everything You Need for
                    <span class="text-gradient d-block">Modern Library Management</span>
                </h2>
                
                <p class="section-description">
                    Our comprehensive platform combines traditional library services with cutting-edge 
                    digital tools to create the perfect learning environment.
                </p>
            </div>

            <!-- Main Features -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="feature-card feature-card-blue h-100">
                        <div class="feature-header">
                            <div class="feature-icon feature-icon-blue">
                                <i class="bi bi-book"></i>
                            </div>
                            <span class="badge bg-light text-dark">Core Feature</span>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Book Management</h3>
                            <p class="feature-description">
                                Comprehensive catalog management with real-time availability tracking, 
                                automated check-in/out, and inventory control.
                            </p>
                            <div class="feature-benefits">
                                <span class="benefit-tag">Real-time tracking</span>
                                <span class="benefit-tag">Automated workflows</span>
                                <span class="benefit-tag">Inventory alerts</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-card feature-card-green h-100">
                        <div class="feature-header">
                            <div class="feature-icon feature-icon-green">
                                <i class="bi bi-tablet"></i>
                            </div>
                            <span class="badge bg-light text-dark">Core Feature</span>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">E-Book Access</h3>
                            <p class="feature-description">
                                Digital library with thousands of e-books, journals, and research papers 
                                accessible 24/7 from any device.
                            </p>
                            <div class="feature-benefits">
                                <span class="benefit-tag">24/7 access</span>
                                <span class="benefit-tag">Multi-device sync</span>
                                <span class="benefit-tag">Offline reading</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-card feature-card-purple h-100">
                        <div class="feature-header">
                            <div class="feature-icon feature-icon-purple">
                                <i class="bi bi-bell"></i>
                            </div>
                            <span class="badge bg-light text-dark">Core Feature</span>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Smart Notifications</h3>
                            <p class="feature-description">
                                Automated reminders for due dates, overdue items, new arrivals, 
                                and important library announcements.
                            </p>
                            <div class="feature-benefits">
                                <span class="benefit-tag">Due date alerts</span>
                                <span class="benefit-tag">New book notifications</span>
                                <span class="benefit-tag">Custom reminders</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-card feature-card-orange h-100">
                        <div class="feature-header">
                            <div class="feature-icon feature-icon-orange">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <span class="badge bg-light text-dark">Core Feature</span>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">School Email Integration</h3>
                            <p class="feature-description">
                                Seamless integration with DNSC email system for student registration, 
                                notifications, and account management.
                            </p>
                            <div class="feature-benefits">
                                <span class="benefit-tag">Automatic registration</span>
                                <span class="benefit-tag">Secure authentication</span>
                                <span class="benefit-tag">Unified communications</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Features -->
            <div class="additional-features">
                <div class="text-center mb-4">
                    <h3 class="mb-3">Plus Many More Features</h3>
                    <p class="text-muted">Discover additional tools and capabilities designed to enhance your library experience</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="additional-feature">
                            <div class="additional-feature-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h4>Advanced Search</h4>
                            <p>Powerful search across all resources</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="additional-feature">
                            <div class="additional-feature-icon">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <h4>Analytics Dashboard</h4>
                            <p>Comprehensive usage statistics</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="additional-feature">
                            <div class="additional-feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h4>Secure Access</h4>
                            <p>Role-based permissions and security</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="additional-feature">
                            <div class="additional-feature-icon">
                                <i class="bi bi-lightning"></i>
                            </div>
                            <h4>Fast Performance</h4>
                            <p>Lightning-fast response times</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="additional-feature">
                            <div class="additional-feature-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h4>Multi-User Support</h4>
                            <p>Concurrent user management</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="additional-feature">
                            <div class="additional-feature-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h4>Customizable</h4>
                            <p>Tailored to your needs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section py-5">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <div class="section-badge mb-4">
                    <i class="bi bi-stars text-success me-2"></i>
                    <span>Our Story</span>
                </div>
                
                <h2 class="section-title mb-4">
                    DNSC Library's
                    <span class="text-gradient d-block">Digital Transformation</span>
                </h2>
                
                <p class="section-description">
                    From traditional library operations to a cutting-edge digital learning hub, 
                    discover how we're reshaping the future of academic resources at DNSC.
                </p>
            </div>

            <!-- Main Content -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="about-content">
                        <div class="about-badge mb-4">
                            <div class="status-dot"></div>
                            <span>Since 2020</span>
                        </div>
                        
                        <h3 class="mb-4">Leading the Way in Educational Technology</h3>
                        
                        <p class="mb-4">
                            The Davao del Norte State College Library has undergone a remarkable transformation, 
                            evolving from a traditional repository of books to a dynamic, technology-driven 
                            learning ecosystem that serves our entire academic community.
                        </p>
                        
                        <p class="mb-4">
                            Our digital transformation journey represents more than just technological advancement—it's 
                            a commitment to providing equitable access to knowledge, streamlining academic workflows, 
                            and preparing our students for the digital age.
                        </p>

                        <!-- Transformation Points -->
                        <div class="transformation-points mb-4">
                            <h4 class="mb-3">Key Transformation Milestones:</h4>
                            <div class="milestone">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>Transitioned from manual card catalogs to digital search systems</span>
                            </div>
                            <div class="milestone">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>Implemented automated book lending and return processes</span>
                            </div>
                            <div class="milestone">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>Integrated e-book and digital journal access platforms</span>
                            </div>
                            <div class="milestone">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>Developed mobile-responsive web applications for students</span>
                            </div>
                            <div class="milestone">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>Created comprehensive analytics and reporting dashboards</span>
                            </div>
                            <div class="milestone">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>Established secure, role-based access control systems</span>
                            </div>
                        </div>

                        <button class="btn btn-gradient-green rounded-pill">
                            Learn More About Our Vision
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="about-image-container">
                        <div class="about-image-bg"></div>
                        <div class="about-image">
                            <img src="{{ Vite::asset('resources/images/library.jpg') }}"
                                 alt="Digital books and technology" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="achievement-card text-center">
                        <div class="achievement-icon text-primary">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="achievement-number">50,000+</div>
                        <div class="achievement-label">Digital Resources</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="achievement-card text-center">
                        <div class="achievement-icon text-success">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="achievement-number">15,000+</div>
                        <div class="achievement-label">Students Served</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="achievement-card text-center">
                        <div class="achievement-icon text-info">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <div class="achievement-number">99.8%</div>
                        <div class="achievement-label">System Uptime</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="achievement-card text-center">
                        <div class="achievement-icon text-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="achievement-number">24/7</div>
                        <div class="achievement-label">Access Available</div>
                    </div>
                </div>
            </div>

            <!-- Mission Statement -->
            <div class="mission-statement text-center text-white">
                <div class="mission-content">
                    <span class="badge bg-white bg-opacity-20 text-dark mb-3">Our Mission</span>
                    <h3 class="mb-4">Empowering Knowledge Through Technology</h3>
                    <p class="lead">
                        To provide innovative, accessible, and efficient library services that support 
                        academic excellence, foster research capabilities, and enable lifelong learning 
                        for the DNSC community and beyond.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="scripts/main.js"></script>
</body>
</html>