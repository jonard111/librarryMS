# Quick Reference Guide

## 🚀 Common Commands

### Development
```bash
# Start development server
php artisan serve

# Watch for changes (Vite)
npm run dev

# Build for production
npm run build
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

### Cache Management
```bash
# Clear all caches
php artisan optimize:clear

# Clear specific cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Storage
```bash
# Create storage link
php artisan storage:link
```

## 📍 Route Quick Reference

### Public Routes
- `/` - Landing page
- `/login` - Login page
- `/register` - Registration page

### Admin Routes (`/admin`)
- `/admin` - Dashboard
- `/admin/users` - User management
- `/admin/announcement` - Announcements
- `/admin/reports` - Reports
- `/admin/all-books` - All books
- `/admin/all-ebooks` - All ebooks

### Student Routes (`/student`)
- `/student/dashboard` - Dashboard
- `/student/books` - Browse books
- `/student/books/all` - All books
- `/student/books/{id}` - Book details
- `/student/books/{id}/reserve` - Reserve book
- `/student/all-ebooks` - All ebooks
- `/student/ebooks/{id}` - Ebook details
- `/student/profile` - Profile
- `/student/borrowed-books` - Borrowed books
- `/student/notifications` - Notifications

### Faculty Routes (`/faculty`)
- `/faculty/dashboard` - Dashboard
- `/faculty/books` - Browse books
- `/faculty/books/{id}` - Book details
- `/faculty/books/{id}/reserve` - Reserve book
- `/faculty/ebooks/{id}` - Ebook details
- `/faculty/borrowed-books` - Borrowed books
- `/faculty/request-books` - Request books

### Assistant Routes (`/assistant`)
- `/assistant/dashboard` - Dashboard
- `/assistant/all-books` - All books
- `/assistant/all-ebooks` - All ebooks
- `/assistant/reservation` - Reservations
- `/assistant/manage-books` - Manage books
- `/assistant/student` - Student list
- `/assistant/users` - Users list

### Head Librarian Routes (`/head`)
- `/head/dashboard` - Dashboard
- `/head/student-record` - Student records
- `/head/announcement` - Announcements
- `/head/reports` - Reports
- `/head/reservation` - Reservations
- `/head/all-books` - All books (CRUD)
- `/head/all-ebooks` - All ebooks (CRUD)

## 🔑 User Roles

| Role | Description | Key Permissions |
|------|-------------|----------------|
| `admin` | System Administrator | User approval, reports, announcements |
| `headlibrarian` | Head Librarian | Full inventory control, reservations, reports |
| `assistant` | Library Assistant | Book management, reservation processing |
| `faculty` | Faculty Member | Browse, reserve books, view ebooks |
| `student` | Student | Browse, reserve books, view ebooks, profile |

## 📊 Reservation Status Flow

```
pending → approved → picked_up → returned
    ↓
cancelled
```

## 🗄 Database Tables

| Table | Description |
|-------|-------------|
| `users` | User accounts |
| `books` | Physical books |
| `ebooks` | Digital books |
| `book_reservations` | Reservation records |
| `announcements` | System announcements |

## 🔍 Model Relationships

### User
- `hasMany` BookReservation
- `hasMany` Announcement (as creator)

### Book
- `hasMany` BookReservation

### BookReservation
- `belongsTo` User
- `belongsTo` Book

### Announcement
- `belongsTo` User (creator)

## 📝 Common Tasks

### Approve a User Account
```sql
UPDATE users SET account_status = 'approved' WHERE userId = 1;
```

### Check User Reservations
```php
$user = User::find(1);
$reservations = $user->reservations;
```

### Get Active Announcements
```php
$announcements = Announcement::published()
    ->visibleForRole('student')
    ->get();
```

### Get Book with Reservations
```php
$book = Book::with('reservations')->find(1);
```

## 🐛 Troubleshooting

### Session Error
```bash
composer install
php artisan optimize:clear
```

### Storage Files Not Accessible
```bash
php artisan storage:link
```

### Migration Error
```bash
php artisan migrate:fresh
```

### Route Not Found
```bash
php artisan route:clear
php artisan route:list
```

## 📂 File Locations

| What | Where |
|------|-------|
| Routes | `routes/web.php` |
| Models | `app/Models/` |
| Controllers | `app/Http/Controllers/[Role]/` |
| Views | `resources/views/[Role]/` |
| Migrations | `database/migrations/` |
| Storage | `storage/app/public/` |
| Logs | `storage/logs/laravel.log` |

## 🎨 Frontend Assets

| Asset | Location |
|-------|----------|
| Vue Components | `resources/js/Components/` |
| Stylesheets | `resources/css/` |
| Images | `resources/images/` |
| Compiled Assets | `public/build/` |

---

**Tip**: Use `php artisan route:list` to see all available routes with their names and middleware.



