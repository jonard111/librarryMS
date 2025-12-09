# Library Management System (LMS)

A comprehensive Laravel-based Library Management System designed for educational institutions to manage books, ebooks, reservations, and user accounts efficiently.

##  Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [User Roles](#user-roles)
- [Usage](#usage)
- [Development](#development)

##  Features

### Core Functionality
- **Book Management**: Add, edit, and manage physical books with ISBN, publisher, category, and cover images
- **Ebook Management**: Upload and manage digital books with file storage and view tracking
- **Reservation System**: Complete book reservation workflow (pending → approved → picked up → returned)
- **User Management**: Multi-role user system with approval workflow
- **Announcements**: Role-based announcements with scheduling and expiration
- **Reports**: Generate comprehensive reports for library operations

### User Roles
- **Admin**: Full system access, user approval, reports
- **Head Librarian**: Inventory management, reservations, reports, announcements
- **Assistant**: Book management, reservation processing
- **Faculty**: Browse and reserve books, view ebooks
- **Student**: Browse and reserve books, view ebooks, manage profile

##  Technology Stack

- **Backend**: Laravel 9.19
- **Frontend**: Vue.js 3, Bootstrap 5, Chart.js
- **Build Tool**: Vite 4
- **Database**: MySQL
- **PHP**: 8.0.2+

## Project Structure

```
Laravel-Lms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin-specific controllers
│   │   │   ├── Assistant/      # Assistant-specific controllers
│   │   │   ├── Auth/            # Authentication controllers
│   │   │   ├── Faculty/         # Faculty-specific controllers
│   │   │   ├── Head/            # Head Librarian controllers
│   │   │   └── Student/         # Student-specific controllers
│   │   └── Middleware/          # Custom middleware (RoleMiddleware)
│   └── Models/
│       ├── Announcement.php     # Announcement model
│       ├── Book.php             # Physical book model
│       ├── BookReservation.php  # Reservation model
│       ├── Ebook.php            # Digital book model
│       └── User.php             # User model
├── database/
│   └── migrations/              # Database migrations
├── resources/
│   ├── css/                      # Stylesheets
│   ├── js/
│   │   ├── Components/           # Vue components
│   │   └── app.js               # Main JS file
│   └── views/
│       ├── Admin/                # Admin views
│       ├── assistant/           # Assistant views
│       ├── faculty/              # Faculty views
│       ├── head/                 # Head Librarian views
│       ├── student/              # Student views
│       └── layouts/              # Layout templates
├── routes/
│   └── web.php                   # Web routes (organized by role)
└── storage/
    └── app/
        └── public/               # Public file storage (book covers, ebooks)
```

##  Installation

### Prerequisites
- PHP >= 8.0.2
- Composer
- Node.js & NPM
- MySQL
- Git

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd Laravel-Lms
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Node Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials and other settings.

### Step 5: Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE library_lms;

# Run migrations
php artisan migrate
```

### Step 6: Storage Link
```bash
php artisan storage:link
```

### Step 7: Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Step 8: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

##  Configuration

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Library Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_lms
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### File Storage

The application stores:
- Book cover images in `storage/app/public/book-covers/`
- Ebook files in `storage/app/public/ebooks/`

Ensure `storage/app/public` is linked to `public/storage`:
```bash
php artisan storage:link
```

## 🗄 Database Setup

### Migrations

The system includes the following main tables:
- `users` - User accounts with roles
- `books` - Physical books
- `ebooks` - Digital books
- `book_reservations` - Reservation records
- `announcements` - System announcements

Run migrations:
```bash
php artisan migrate
```

### Seeding (Optional)
```bash
php artisan db:seed
```

##  User Roles

### Admin
- Approve/reject user registrations
- Manage all users
- Create announcements
- View reports
- Browse books and ebooks

### Head Librarian
- Full inventory management (add/edit/delete books & ebooks)
- Manage reservations (approve, return, cancel)
- Create announcements
- Generate reports
- View student records

### Assistant
- Manage books and ebooks (edit/delete)
- Process reservations
- View users and students
- View announcements and notifications

### Faculty
- Browse and search books
- Reserve books
- View ebooks
- View announcements
- Manage profile

### Student
- Browse and search books
- Reserve books
- View ebooks
- View announcements
- Manage profile

##  Usage

### Creating Your First Admin Account

1. Register a new account with role "admin"
2. The account will be in "pending" status
3. You need to manually approve it in the database:
   ```sql
   UPDATE users SET account_status = 'approved' WHERE email = 'your-email@example.com';
   ```
4. Login with your credentials

### Book Management

**Adding a Book (Head Librarian):**
1. Navigate to Head Dashboard → Books → All Books
2. Click "Add New Book"
3. Fill in book details (title, author, ISBN, publisher, category, copies)
4. Upload cover image
5. Save

**Reserving a Book (Student/Faculty):**
1. Browse books
2. Click on a book to view details
3. Click "Reserve" button
4. Wait for approval from Assistant/Head Librarian

### Reservation Workflow

1. **Pending**: User submits reservation request
2. **Approved**: Assistant/Head Librarian approves the request
3. **Picked Up**: Book is physically collected
4. **Returned**: Book is returned to library
5. **Cancelled**: Reservation is cancelled

## 🔧 Development

### Code Organization

- **Routes**: Organized by role in `routes/web.php` with clear section comments
- **Controllers**: Grouped by role in `app/Http/Controllers/`
- **Models**: Located in `app/Models/` with PHPDoc documentation
- **Views**: Organized by role in `resources/views/`

### Running Tests
```bash
php artisan test
```

### Clearing Cache
```bash
php artisan optimize:clear
```

### Code Style
The project follows PSR-12 coding standards. Use Laravel Pint:
```bash
./vendor/bin/pint
```

##  Notes

- All user accounts require admin approval before login
- Book covers and ebook files are stored in `storage/app/public/`
- Session driver is set to 'file' by default
- The system uses role-based middleware for access control

##  Contributing

1. Follow PSR-12 coding standards
2. Use camelCase for variables and methods
3. Add PHPDoc comments to classes and methods
4. Keep routes organized by role
5. Write descriptive commit messages

##  License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues or questions, please check:
- Laravel Documentation: https://laravel.com/docs
- Vue.js Documentation: https://vuejs.org/

---

**Built with  using Laravel & Vue.js**
