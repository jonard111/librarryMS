# Profile Modal Vue Component - Usage Guide

## Overview

The Profile Modal is now a reusable Vue.js component that works for all user roles (Admin, Student, Faculty, Assistant, Head Librarian). It provides a consistent, interactive profile editing experience across the entire application.

## Component Location

- **Vue Component**: `resources/js/Components/ProfileModal.vue`
- **Partials**: `resources/views/{role}/partials/profile-modal.blade.php`

## Features

- ✅ Real-time form validation
- ✅ AJAX form submission (no page reload)
- ✅ Role-based styling (colors change per role)
- ✅ Loading states
- ✅ Success/error messages
- ✅ Password change (optional)
- ✅ Auto-close on success

## Setup

### 1. Vue App Registration

The component is already registered in `resources/js/app.js`:

```javascript
import ProfileModal from "./Components/ProfileModal.vue";
app.component("profile-modal", ProfileModal);
```

### 2. Include in Views

Add the profile modal partial at the end of your Blade view (before `</body>`):

```blade
@include('{role}.partials.profile-modal')
```

**Available partials:**
- `student.partials.profile-modal`
- `Admin.partials.profile-modal`
- `faculty.partials.profile-modal`
- `assistant.partials.profile-modal`
- `head.partials.profile-modal`

### 3. Include Vue App

Make sure your view includes the Vue app:

```blade
@vite(['resources/js/app.js', 'resources/css/design.css'])
```

### 4. Trigger the Modal

Add a link/button to trigger the modal:

```blade
<a href="javascript:void(0)" 
   class="profile-info-link" 
   data-bs-toggle="modal" 
   data-bs-target="#{{ auth()->user()->role }}ProfileModal">
    <!-- Profile info content -->
</a>
```

**Modal IDs by role:**
- Student: `#studentProfileModal`
- Admin: `#adminProfileModal`
- Faculty: `#facultyProfileModal`
- Assistant: `#assistantProfileModal`
- Head Librarian: `#headlibrarianProfileModal`

## Routes Required

Each role needs profile routes:

```php
// In routes/web.php for each role group
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
```

## Controllers

Each role has its own ProfileController that:
- Returns JSON for AJAX requests
- Returns redirect for regular form submissions
- Validates user input
- Updates user profile

**Controller locations:**
- `app/Http/Controllers/Student/ProfileController.php`
- `app/Http/Controllers/Admin/ProfileController.php`
- `app/Http/Controllers/Faculty/ProfileController.php`
- `app/Http/Controllers/Assistant/ProfileController.php`
- `app/Http/Controllers/Head/ProfileController.php`

## Component Props

The Vue component accepts these props:

| Prop | Type | Required | Description |
|------|------|----------|-------------|
| `user` | Object | Yes | User object with profile data |
| `updateUrl` | String | Yes | Route URL for profile update |
| `role` | String | Yes | User role (student, admin, faculty, assistant, headlibrarian) |

## Role-Based Styling

The component automatically applies role-specific colors:

| Role | Header Color | Icon Color | Button Color |
|------|--------------|------------|--------------|
| Admin | Red (bg-danger) | Red | Red |
| Student | Green (bg-success) | Green | Green |
| Faculty | Blue (bg-info) | Blue | Blue |
| Assistant | Yellow (bg-warning) | Yellow | Yellow |
| Head Librarian | Primary Blue | Primary | Primary |

## Example Usage

### Student View Example

```blade
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/js/app.js', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Sidebar with profile link -->
    <div class="sidebar">
        <a href="javascript:void(0)" 
           class="profile-info-link" 
           data-bs-toggle="modal" 
           data-bs-target="#studentProfileModal">
            <div class="profile-info">
                <i class="fas fa-user-circle"></i>
                <div class="profile-text">
                    <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
                </div>
            </div>
        </a>
        <!-- Navigation -->
    </div>
    
    <!-- Page content -->
    
    <!-- Include profile modal -->
    @include('student.partials.profile-modal')
</body>
</html>
```

## API Response Format

The controller should return JSON in this format:

```json
{
    "success": true,
    "message": "Profile updated successfully.",
    "user": {
        "userId": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "role": "student",
        "account_status": "approved"
    }
}
```

## Error Handling

The component handles:
- **422 Validation Errors**: Displays field-specific errors
- **Other Errors**: Shows general error message
- **Network Errors**: Shows connection error message

## Events

The component emits:
- `profile-updated`: When profile is successfully updated (passes updated user data)

## Customization

To customize the component:
1. Edit `resources/js/Components/ProfileModal.vue`
2. Rebuild assets: `npm run dev` or `npm run build`
3. Clear cache: `php artisan optimize:clear`

## Troubleshooting

### Modal not showing
- Check that Bootstrap JS is loaded
- Verify modal ID matches the trigger's `data-bs-target`
- Ensure Vue app is mounted (`@vite(['resources/js/app.js'])`)

### Form not submitting
- Check browser console for errors
- Verify the `updateUrl` prop is correct
- Ensure CSRF token is included (handled automatically by axios)

### Styling issues
- Make sure Bootstrap CSS is loaded
- Check that role prop is correctly passed
- Verify Vite is compiling CSS correctly

## Benefits

✅ **Consistent UX**: Same experience across all roles  
✅ **No Page Reload**: AJAX submission for better UX  
✅ **Real-time Validation**: Instant feedback  
✅ **Maintainable**: Single component for all roles  
✅ **Reusable**: Easy to add to new views  

---

**Note**: After making changes to the Vue component, run `npm run dev` (development) or `npm run build` (production) to compile the changes.



