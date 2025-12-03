# Project Structure Documentation

This document provides a detailed overview of the Library Management System project structure.

## 📂 Directory Structure

```
Laravel-Lms/
│
├── app/                          # Application core
│   ├── Console/                 # Artisan commands
│   ├── Exceptions/              # Exception handlers
│   ├── Http/
│   │   ├── Controllers/         # Request handlers organized by role
│   │   │   ├── Admin/          # Admin functionality
│   │   │   ├── Assistant/      # Assistant functionality
│   │   │   ├── Auth/           # Authentication (Login, Register)
│   │   │   ├── Faculty/        # Faculty functionality
│   │   │   ├── Head/           # Head Librarian functionality
│   │   │   └── Student/        # Student functionality
│   │   └── Middleware/         # Custom middleware
│   │       └── RoleMiddleware.php  # Role-based access control
│   ├── Models/                  # Eloquent models
│   │   ├── Announcement.php    # Announcements model
│   │   ├── Book.php            # Physical books model
│   │   ├── BookReservation.php # Reservations model
│   │   ├── Ebook.php           # Digital books model
│   │   └── User.php            # Users model
│   └── Providers/              # Service providers
│
├── bootstrap/                   # Framework bootstrap files
├── config/                      # Configuration files
├── database/
│   ├── migrations/             # Database schema migrations
│   └── seeders/                # Database seeders
│
├── public/                      # Public web root
│   └── index.php              # Application entry point
│
├── resources/
│   ├── css/                     # Stylesheets
│   ├── js/
│   │   ├── Components/         # Vue.js components
│   │   │   ├── adminDashboard.vue
│   │   │   └── SystemUsageChart.vue
│   │   ├── app.js             # Main JavaScript file
│   │   └── bootstrap.js       # Bootstrap configuration
│   └── views/                  # Blade templates
│       ├── Admin/             # Admin views
│       ├── assistant/         # Assistant views
│       ├── auth/              # Authentication views
│       ├── faculty/           # Faculty views
│       ├── head/              # Head Librarian views
│       ├── student/           # Student views
│       └── layouts/           # Layout templates
│
├── routes/
│   ├── web.php                # Web routes (organized by role)
│   ├── api.php                # API routes
│   └── console.php            # Console routes
│
├── storage/
│   ├── app/
│   │   └── public/            # Public file storage
│   │       ├── book-covers/   # Book cover images
│   │       └── ebooks/        # Ebook files
│   ├── framework/             # Framework files
│   └── logs/                  # Application logs
│
└── tests/                      # Test files
```

## 🗂 Key Files

### Routes (`routes/web.php`)
Routes are organized into clear sections:
- **Public Routes**: Landing page, authentication
- **Admin Routes**: User management, announcements, reports
- **Student Routes**: Books, ebooks, reservations, profile
- **Faculty Routes**: Similar to student with additional features
- **Assistant Routes**: Book management, reservation processing
- **Head Librarian Routes**: Full system access

### Models (`app/Models/`)

#### User Model
- Primary key: `userId` (not `id`)
- Relationships:
  - `reservations()` - Has many BookReservation
  - `announcements()` - Has many Announcement (as creator)
- Helper methods:
  - `getFullNameAttribute()` - Returns full name
  - `isApproved()` - Checks if account is approved

#### Book Model
- Represents physical books
- Relationships:
  - `reservations()` - Has many BookReservation
- Helper methods:
  - `coverUrl()` - Returns book cover image URL

#### Ebook Model
- Represents digital books
- Helper methods:
  - `coverUrl()` - Returns ebook cover image URL
  - `fileUrl()` - Returns ebook file URL

#### BookReservation Model
- Represents book borrowing records
- Relationships:
  - `user()` - Belongs to User
  - `book()` - Belongs to Book
- Status values: pending, approved, rejected, picked_up, returned, cancelled

#### Announcement Model
- Represents system announcements
- Relationships:
  - `creator()` - Belongs to User (created_by)
- Scopes:
  - `scopePublished()` - Published announcements
  - `scopeActive()` - Active announcements
  - `scopeExpired()` - Expired announcements
  - `scopeVisibleForRole()` - Role-based filtering

### Controllers Organization

Controllers are organized by user role:

```
app/Http/Controllers/
├── Admin/
│   ├── AdminController.php          # Dashboard, users, reports
│   └── AnnouncementController.php  # Announcement management
├── Assistant/
│   └── AssistantController.php     # Book management, reservations
├── Auth/
│   ├── LoginController.php         # Authentication
│   └── RegisterController.php    # User registration
├── Faculty/
│   └── FacultyController.php       # Faculty dashboard and features
├── Head/
│   ├── AnnouncementController.php   # Announcement management
│   ├── InventoryController.php     # Book/ebook CRUD
│   ├── ReportsController.php       # Report generation
│   ├── ReservationController.php   # Reservation management
│   └── StudentRecordController.php  # Student records
└── Student/
    ├── AnnouncementController.php   # View announcements
    ├── BookController.php           # Browse and reserve books
    ├── EbookController.php         # View ebooks
    ├── NotificationController.php  # View notifications
    └── ProfileController.php      # Profile management
```

## 🔐 Middleware

### RoleMiddleware
Located at `app/Http/Middleware/RoleMiddleware.php`

- Checks if user is authenticated
- Verifies user has the required role
- Returns 403 if unauthorized

Usage in routes:
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});
```

## 📊 Database Schema

### Users Table
- `userId` (primary key, integer)
- `first_name`, `last_name`, `email`
- `password`, `role`, `account_status`
- `registration_date`, `created_at`, `updated_at`

### Books Table
- `id` (primary key)
- `title`, `author`, `isbn`, `publisher`, `category`
- `copies`, `cover_path`
- `created_at`, `updated_at`

### Ebooks Table
- `id` (primary key)
- `title`, `author`, `category`
- `file_path`, `cover_path`, `views`
- `created_at`, `updated_at`

### Book Reservations Table
- `id` (primary key)
- `user_id` (foreign key → users.userId)
- `book_id` (foreign key → books.id)
- `status`, `reservation_date`
- `pickup_date`, `due_date`, `return_date`
- `notes`, `created_at`, `updated_at`

### Announcements Table
- `id` (primary key)
- `title`, `type`, `body`
- `audience` (JSON array)
- `status`, `publish_at`, `expires_at`
- `created_by` (foreign key → users.userId)
- `created_at`, `updated_at`

## 🎨 Frontend Structure

### Vue Components
Located in `resources/js/Components/`:
- `adminDashboard.vue` - Admin dashboard with charts
- `SystemUsageChart.vue` - Usage statistics chart

### Stylesheets
- `app.css` - Main stylesheet
- `book.css` - Book-specific styles
- `design.css` - Design system styles
- `style.css` - Additional styles

### Views Organization
Views are organized by role in `resources/views/`:
- Each role has its own directory
- Shared components in `components/`
- Layouts in `layouts/`

## 🔄 Workflow Patterns

### User Registration Flow
1. User registers → Account status: `pending`
2. Admin approves → Account status: `approved`
3. User can login

### Book Reservation Flow
1. Student/Faculty reserves book → Status: `pending`
2. Assistant/Head approves → Status: `approved`
3. Book picked up → Status: `picked_up`
4. Book returned → Status: `returned`

### Announcement Flow
1. Create announcement → Status: `draft` or `published`
2. If published, visible to target audience
3. Expires based on `expires_at` date

## 📝 Naming Conventions

- **Controllers**: PascalCase (e.g., `AdminController`)
- **Models**: PascalCase, singular (e.g., `Book`, `User`)
- **Routes**: kebab-case (e.g., `/admin/users`)
- **Route Names**: dot notation (e.g., `admin.users`)
- **Methods**: camelCase (e.g., `getFullNameAttribute`)
- **Variables**: camelCase (e.g., `$popularBooks`)

## 🔍 Finding Code

### To find a route:
Check `routes/web.php` - routes are organized by role with clear comments

### To find a controller:
Check `app/Http/Controllers/[Role]/` based on the route prefix

### To find a model:
Check `app/Models/` - all models are in the root of this directory

### To find a view:
Check `resources/views/[Role]/` based on the controller namespace

### To find middleware:
Check `app/Http/Middleware/`

## 🛠 Maintenance Tips

1. **Adding a new role**: 
   - Add role to User model enum
   - Create controller directory
   - Add routes section in `web.php`
   - Create view directory

2. **Adding a new model**:
   - Create in `app/Models/`
   - Add PHPDoc comments
   - Define relationships
   - Create migration

3. **Adding a new feature**:
   - Create controller method
   - Add route in appropriate section
   - Create/update view
   - Update model if needed

---

This structure ensures the codebase is maintainable, scalable, and easy to navigate.



