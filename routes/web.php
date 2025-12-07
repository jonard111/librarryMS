<?php

/**
 * Web Routes
 * * This file contains all the web routes for the Library Management System.
 * Routes are organized by authentication status and user roles.
 * * @package App\Routes
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Student Controllers
use App\Http\Controllers\Student\BookController; // Renamed to simple BookController for clarity within student routes
use App\Http\Controllers\Student\EbookController; // Renamed to simple EbookController for clarity within student routes
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ReadingListController as StudentReadingListController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;

// Faculty Controllers
use App\Http\Controllers\faculty\EbookController as FacultyEbookController;
use App\Http\Controllers\faculty\NotificationController as FacultyNotificationController;
use App\Http\Controllers\faculty\ProfileController as FacultyProfileController;
use App\Http\Controllers\faculty\BookController as FacultyBookController;
use App\Http\Controllers\faculty\ReadingListController as FacultyReadingListController;
use App\Http\Controllers\faculty\DashboardController as FacultyDashboardController;

// Assistant Controllers
use App\Http\Controllers\Assistant\AssistantController;
use App\Http\Controllers\Assistant\ProfileController as AssistantProfileController;

// Head Librarian Controllers
use App\Http\Controllers\Head\InventoryController;
use App\Http\Controllers\Head\AnnouncementController as HeadAnnouncementController;
use App\Http\Controllers\Head\ReservationController as HeadReservationController;
use App\Http\Controllers\Head\StudentRecordController;
use App\Http\Controllers\Head\ReportsController;
use App\Http\Controllers\Head\ProfileController as HeadProfileController;
use App\Http\Controllers\Head\DashboardController as HeadDashboardController;

// Models
use App\Models\Book as BookModel; // Renamed to avoid clash with controller
use App\Models\Ebook as EbookModel; // Renamed to avoid clash with controller


// ============================================
// PUBLIC ROUTES
// ============================================

// Landing page
Route::get('/', function () {
    return view('index');
})->name('home');

// Authentication Routes
Route::prefix('auth')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    
    // Login
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Logout (requires authentication)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
});

// ============================================
// ADMIN ROUTES (role:admin)
// ============================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    
    // User Management
    Route::get('/users', [AdminController::class, 'showUsers'])->name('users');
    Route::post('/user/{user}/approve', [AdminController::class, 'approve'])->name('user.approve');
    Route::post('/user/{user}/reject', [AdminController::class, 'reject'])->name('user.reject');
    Route::get('/user/{user}/edit', [AdminController::class, 'editUser'])->name('user.edit');
    Route::put('/user/{user}', [AdminController::class, 'updateUser'])->name('user.update');
    
    // Announcements
    Route::get('/announcement', [AdminAnnouncementController::class, 'index'])->name('announcement');
    Route::post('/announcement', [AdminAnnouncementController::class, 'store'])->name('announcement.store');
    Route::put('/announcement/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcement/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcement.destroy');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('reports.generate');
    
    // Books & Ebooks Overview
    Route::get('/books', [AdminController::class, 'books'])->name('books');
    Route::get('/all-books', [AdminController::class, 'allBooks'])->name('books.all');
    // NOTE: Changed to a controller method for consistency, assuming AdminController handles it.
    Route::get('/all-ebooks', [AdminController::class, 'allEbooks'])->name('ebooks.all');
});

// ============================================
// STUDENT ROUTES (role:student)
// ============================================

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    
    // BOOKS: Browse and reserve books (uses App\Http\Controllers\Student\BookController)
    Route::get('/books', [BookController::class, 'index'])->name('books');
    Route::get('/books/all', [BookController::class, 'allBooks'])->name('books.all');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{id}/check-reservation', [BookController::class, 'checkReservation'])->name('books.checkReservation');
    Route::post('/books/{id}/reserve', [BookController::class, 'reserve'])->name('books.reserve');
    
    // EBOOKS: View digital books (uses App\Http\Controllers\Student\EbookController)
    Route::get('/all-ebooks', [EbookController::class, 'allEbooks'])->name('ebooks.all');
    Route::get('/ebooks/{id}', [EbookController::class, 'show'])->name('ebooks.show');
    
    // PROFILE: Manage student profile
    Route::get('/profile', [StudentProfileController::class, 'show'])->name('profile.show'); // FIX: Used StudentProfileController
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update'); // FIX: Used StudentProfileController
    
    // RESERVATIONS & BORROWED BOOKS
    Route::get('/borrowed-books', [BookController::class, 'borrowedBooks'])->name('borrowed');
    Route::get('/request-books', fn () => redirect()->route('student.borrowed'))->name('requests');
    Route::delete('/requests/{id}/cancel', [BookController::class, 'cancelRequest'])->name('requests.cancel');
    
    // READING LIST
    Route::get('/reading-list', [StudentReadingListController::class, 'index'])->name('reading-list'); // FIX: Used StudentReadingListController
    Route::post('/reading-list/{bookId}/add', [StudentReadingListController::class, 'add'])->name('reading-list.add'); // FIX: Used StudentReadingListController
    Route::delete('/reading-list/{bookId}/remove', [StudentReadingListController::class, 'remove'])->name('reading-list.remove'); // FIX: Used StudentReadingListController
    
    // NOTIFICATIONS & ANNOUNCEMENTS
    Route::get('/announcements', fn () => redirect()->route('student.notifications'))->name('announcements');
    Route::get('/notifications', StudentNotificationController::class)->name('notifications');
});

// ============================================
// FACULTY ROUTES (role:faculty)
// ============================================

Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [FacultyDashboardController::class, 'dashboard'])->name('dashboard');
    
    // BOOKS: Browse and reserve books (uses App\Http\Controllers\faculty\BookController)
    Route::get('/books', [FacultyBookController::class, 'index'])->name('books'); // FIX: Used FacultyBookController
    Route::get('/books/all', [FacultyBookController::class, 'allBooks'])->name('books.all'); // FIX: Used FacultyBookController
    Route::get('/books/{id}', [FacultyBookController::class, 'show'])->name('books.show'); // FIX: Used FacultyBookController
    Route::get('/books/{id}/check-reservation', [FacultyBookController::class, 'checkReservation'])->name('books.checkReservation'); // FIX: Used FacultyBookController
    Route::post('/books/{id}/reserve', [FacultyBookController::class, 'reserve'])->name('books.reserve'); // FIX: Used FacultyBookController
    
    // EBOOKS: View digital books (uses App\Http\Controllers\faculty\EbookController)
    Route::get('/all-ebooks', [FacultyEbookController::class, 'allEbooks'])->name('ebooks.all'); // FIX: Used FacultyEbookController
    Route::get('/ebooks/{id}', [FacultyEbookController::class, 'show'])->name('ebooks.show'); // FIX: Used FacultyEbookController
    
    // PROFILE: Manage faculty profile
    Route::get('/profile', [FacultyProfileController::class, 'show'])->name('profile.show'); // FIX: Used FacultyProfileController
    Route::put('/profile', [FacultyProfileController::class, 'update'])->name('profile.update'); // FIX: Used FacultyProfileController
    
    // RESERVATIONS & BORROWED BOOKS
    Route::get('/borrowed-books', [FacultyBookController::class, 'borrowedBooks'])->name('borrowed'); // FIX: Used FacultyBookController
    Route::get('/request-books', fn () => redirect()->route('faculty.borrowed'))->name('requests');
    Route::delete('/requests/{id}/cancel', [FacultyBookController::class, 'cancelRequest'])->name('requests.cancel'); // FIX: Used FacultyBookController
    
    // READING LIST
    Route::get('/reading-list', [FacultyReadingListController::class, 'index'])->name('reading-list'); // FIX: Used FacultyReadingListController
    Route::post('/reading-list/{bookId}/add', [FacultyReadingListController::class, 'add'])->name('reading-list.add'); // FIX: Used FacultyReadingListController
    Route::delete('/reading-list/{bookId}/remove', [FacultyReadingListController::class, 'remove'])->name('reading-list.remove'); // FIX: Used FacultyReadingListController
    
    // NOTIFICATIONS & ANNOUNCEMENTS
    Route::get('/announcements', fn () => redirect()->route('faculty.notifications'))->name('announcements');
    Route::get('/notifications', FacultyNotificationController::class)->name('notifications');
});

// ============================================
// ASSISTANT ROUTES (role:assistant)
// ============================================

Route::middleware(['auth', 'role:assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [AssistantController::class, 'dashboard'])->name('dashboard');
    
    // PROFILE
    Route::get('/profile', [AssistantProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AssistantProfileController::class, 'update'])->name('profile.update');
    
    // BOOKS MANAGEMENT: CRUD (Using InventoryController methods for the actual actions)
    Route::get('/all-books', [AssistantController::class, 'allBooks'])->name('allBooks');
    Route::post('/all-books', [InventoryController::class, 'storeBook'])->name('books.store');
    Route::get('/all-books/{book}/edit', [InventoryController::class, 'editBook'])->name('books.edit');
    Route::put('/all-books/{book}', [InventoryController::class, 'updateBook'])->name('books.update');
    Route::delete('/all-books/{book}', [InventoryController::class, 'destroyBook'])->name('books.destroy');
    
    // EBOOKS MANAGEMENT: CRUD (Using InventoryController methods for the actual actions)
    Route::get('/all-ebooks', [AssistantController::class, 'allEbooks'])->name('allEbooks');
    Route::post('/all-ebooks', [InventoryController::class, 'storeEbook'])->name('ebooks.store');
    Route::get('/all-ebooks/{ebook}/edit', [InventoryController::class, 'editEbook'])->name('ebooks.edit');
    Route::put('/all-ebooks/{ebook}', [InventoryController::class, 'updateEbook'])->name('ebooks.update');
    Route::delete('/all-ebooks/{ebook}', [InventoryController::class, 'destroyEbook'])->name('ebooks.destroy');
    
    // RESERVATIONS MANAGEMENT: Process book reservations and returns
    Route::get('/borrow-return', fn () => redirect()->route('assistant.reservation'))->name('borrowReturn');
    Route::get('/reservation', [AssistantController::class, 'reservation'])->name('reservation');
    Route::put('/reservation/{id}/approve-request', [AssistantController::class, 'approveRequest'])->name('reservation.approveRequest');
    Route::put('/reservation/{id}/approve', [AssistantController::class, 'approveReservation'])->name('reservation.approve');
    Route::put('/reservation/{id}/return', [AssistantController::class, 'returnBook'])->name('reservation.return');
    Route::put('/reservation/{id}/settle-fine', [AssistantController::class, 'settleFine'])->name('reservation.settleFine');
    Route::delete('/reservation/{id}', [AssistantController::class, 'destroyReservation'])->name('reservation.destroy');
    
    // ADDITIONAL FEATURES
    Route::get('/manage-books', [AssistantController::class, 'manageBooks'])->name('manageBooks');
    Route::get('/student', [AssistantController::class, 'student'])->name('student');
    Route::get('/notification', [AssistantController::class, 'notification'])->name('notification');
    Route::get('/announcement', [AssistantController::class, 'announcement'])->name('announcement');
});

// ============================================
// HEAD LIBRARIAN ROUTES (role:headlibrarian)
// ============================================

Route::middleware(['auth', 'role:headlibrarian'])->prefix('head')->name('head.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HeadDashboardController::class, 'dashboard'])->name('dashboard'); // FIX: Used HeadDashboardController
    
    // Profile
    Route::get('/profile', [HeadProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [HeadProfileController::class, 'update'])->name('profile.update');
    
    // Student Records
    Route::get('/student-record', [StudentRecordController::class, 'index'])->name('studentRecord');
    
    // Announcements
    Route::get('/announcement', [HeadAnnouncementController::class, 'index'])->name('announcement');
    Route::post('/announcement', [HeadAnnouncementController::class, 'store'])->name('announcement.store');
    Route::put('/announcement/{announcement}', [HeadAnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcement/{announcement}', [HeadAnnouncementController::class, 'destroy'])->name('announcement.destroy');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::post('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    
    // Books Overview
    Route::get('/books', function () {
        // FIX: Used aliased models (BookModel, EbookModel)
        $popularBooks = BookModel::latest()->take(4)->get(); 
        $popularEbooks = EbookModel::latest()->take(4)->get();
        return view('head.books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    })->name('books');

    
    
    // Reservations Management
    Route::get('/borrow-return', fn () => redirect()->route('head.reservation'))->name('borrowReturn');
    Route::get('/reservation', [HeadReservationController::class, 'index'])->name('reservation');
    Route::put('/reservation/{id}/approve-request', [HeadReservationController::class, 'approveRequest'])->name('reservation.approveRequest');
    Route::put('/reservation/{id}/approve', [HeadReservationController::class, 'approve'])->name('reservation.approve');
    Route::put('/reservation/{id}/return', [HeadReservationController::class, 'returnBook'])->name('reservation.return');
    Route::delete('/reservation/{id}', [HeadReservationController::class, 'destroy'])->name('reservation.destroy');
    
    // Inventory Management - Books
    Route::get('/all-books', [InventoryController::class, 'books'])->name('books.all');
    Route::post('/all-books', [InventoryController::class, 'storeBook'])->name('books.store');
    Route::get('/all-books/{book}/edit', [InventoryController::class, 'editBook'])->name('books.edit');
    Route::put('/all-books/{book}', [InventoryController::class, 'updateBook'])->name('books.update');
    Route::delete('/all-books/{book}', [InventoryController::class, 'destroyBook'])->name('books.destroy');
    
    // Inventory Management - Ebooks
    Route::get('/all-ebooks', [InventoryController::class, 'ebooks'])->name('ebooks.all');
    Route::post('/all-ebooks', [InventoryController::class, 'storeEbook'])->name('ebooks.store');
    Route::get('/all-ebooks/{ebook}/edit', [InventoryController::class, 'editEbook'])->name('ebooks.edit');
    Route::put('/all-ebooks/{ebook}', [InventoryController::class, 'updateEbook'])->name('ebooks.update');
    Route::delete('/all-ebooks/{ebook}', [InventoryController::class, 'destroyEbook'])->name('ebooks.destroy');

    //fines
    Route::put('/reservation/{id}/settle-fine', [\App\Http\Controllers\Head\ReservationController::class, 'settleFine'])->name('reservation.settleFine');
});