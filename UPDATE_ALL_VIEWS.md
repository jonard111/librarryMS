# Update All Views with Profile Modal - Instructions

## Pattern to Apply

For each view file, apply these changes:

### 1. Update Vite to include Vue.js
**Find:**
```blade
@vite(['resources/css/design.css'])
```
**Replace with:**
```blade
@vite(['resources/js/app.js', 'resources/css/design.css'])
```

### 2. Make Profile Info Clickable
**Find:**
```blade
<div class="profile-info">
    <i class="fas fa-user-circle"></i>
    <div class="profile-text">
        <h2>Hardcoded Name</h2>
    </div>
</div>
```

**Replace with (by role):**

**Admin:**
```blade
<a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#adminProfileModal">
    <div class="profile-info">
        <i class="fas fa-user-circle"></i>
        <div class="profile-text">
            <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
    </div>
</a>
```

**Faculty:**
```blade
<a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#facultyProfileModal">
    <div class="profile-info">
        <i class="fas fa-user-circle"></i>
        <div class="profile-text">
            <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
    </div>
</a>
```

**Assistant:**
```blade
<a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#assistantProfileModal">
    <div class="profile-info">
        <i class="fas fa-user-circle"></i>
        <div class="profile-text">
            <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
    </div>
</a>
```

**Head Librarian:**
```blade
<a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
    <div class="profile-info">
        <i class="fas fa-user-circle"></i>
        <div class="profile-text">
            <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
    </div>
</a>
```

### 3. Add Profile Modal Include
**Before `</body>` tag, add:**
```blade
@include('{role}.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

**Replace `{role}` with:**
- `Admin` for Admin views
- `faculty` for Faculty views
- `assistant` for Assistant views
- `head` for Head Librarian views
- `student` for Student views (already done)

## Files Already Updated

✅ **Dashboards:**
- `resources/views/Admin/admin_dashboard.blade.php`
- `resources/views/faculty/faculty_dashboard.blade.php`
- `resources/views/assistant/assistant_dashboard.blade.php`
- `resources/views/head/head_dashboard.blade.php`
- `resources/views/student/student_dashboard.blade.php`

✅ **Other Views:**
- `resources/views/Admin/users.blade.php`
- `resources/views/Admin/announcement.blade.php`
- `resources/views/faculty/books.blade.php`
- All student views (already have profile modal)

## Files Still Needing Updates

### Admin Views
- `resources/views/Admin/all_books.blade.php`
- `resources/views/Admin/all_ebooks.blade.php`
- `resources/views/Admin/books.blade.php`
- `resources/views/Admin/reports.blade.php`

### Faculty Views
- `resources/views/faculty/all_books.blade.php`
- `resources/views/faculty/all_ebooks.blade.php`
- `resources/views/faculty/announcement.blade.php`
- `resources/views/faculty/book_details.blade.php`
- `resources/views/faculty/borrowed_books.blade.php`
- `resources/views/faculty/ebook_details.blade.php`
- `resources/views/faculty/notification.blade.php`
- `resources/views/faculty/request_books.blade.php`

### Assistant Views
- `resources/views/assistant/all_book.blade.php`
- `resources/views/assistant/all_ebooks.blade.php`
- `resources/views/assistant/announcement.blade.php`
- `resources/views/assistant/manage_books.blade.php`
- `resources/views/assistant/notification.blade.php`
- `resources/views/assistant/reservation.blade.php`
- `resources/views/assistant/student.blade.php`
- `resources/views/assistant/users.blade.php`

### Head Librarian Views
- `resources/views/head/all_books.blade.php`
- `resources/views/head/all_ebooks.blade.php`
- `resources/views/head/announcement.blade.php`
- `resources/views/head/books.blade.php`
- `resources/views/head/reports.blade.php`
- `resources/views/head/reservation.blade.php`
- `resources/views/head/student_record.blade.php`

## Quick Update Script

You can use find/replace in your IDE with these patterns:

### For Admin Views:
1. Find: `@vite(['resources/css/design.css'])`
   Replace: `@vite(['resources/js/app.js', 'resources/css/design.css'])`

2. Find: `<div class="profile-info">` (with hardcoded name)
   Replace: Clickable link pattern above

3. Find: `</body>`
   Replace: `@include('Admin.partials.profile-modal')` + Bootstrap JS + `</body>`

### For Faculty Views:
Same pattern but use `faculty` instead of `Admin` and `#facultyProfileModal`

### For Assistant Views:
Same pattern but use `assistant` instead of `Admin` and `#assistantProfileModal`

### For Head Views:
Same pattern but use `head` instead of `Admin` and `#headlibrarianProfileModal`

## Verification

After updating, verify:
1. ✅ Vue.js is included: `@vite(['resources/js/app.js', ...])`
2. ✅ Profile info is clickable with correct modal target
3. ✅ Profile modal partial is included before `</body>`
4. ✅ Bootstrap JS is included
5. ✅ User name uses `{{ auth()->user()->first_name }}` not hardcoded

## Testing

1. Run `npm run dev` to compile Vue components
2. Visit each role's dashboard
3. Click on profile info in sidebar
4. Verify modal opens and form works
5. Test profile update functionality



