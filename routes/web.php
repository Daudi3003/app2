<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AdminstratorController;
use App\Http\Controllers\InstructorPortalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordPageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPortalController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/courses', [PageController::class, 'courses'])->name('courses.index');
Route::get('/courses/{id}', [PageController::class, 'courseShow'])->whereNumber('id')->name('courses.show');
Route::get('/instructors', [PageController::class, 'instructors'])->name('instructors.index');
Route::get('/instructors/{id}', [PageController::class, 'instructorShow'])->whereNumber('id')->name('instructors.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/forgot-password', [PasswordPageController::class, 'forgot'])->name('password.request');
Route::get('/reset-password/{token?}', [PasswordPageController::class, 'reset'])->name('password.reset');

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [StudentPortalController::class, 'courses'])->name('courses');
    Route::get('/my-courses', [StudentPortalController::class, 'courses'])->name('my-courses');
    Route::get('/course/{id}', [StudentPortalController::class, 'course'])->whereNumber('id')->name('course');
    Route::get('/lesson/{id}', [StudentPortalController::class, 'lesson'])->whereNumber('id')->name('lesson');
    Route::get('/assignments', [StudentPortalController::class, 'assignments'])->name('assignments');
    Route::get('/certificates', [StudentPortalController::class, 'certificates'])->name('certificates');
    Route::get('/bookmarks', [StudentPortalController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/messages', [StudentPortalController::class, 'messages'])->name('messages');
    Route::get('/notifications', [StudentPortalController::class, 'notifications'])->name('notifications');
    Route::get('/profile', [StudentPortalController::class, 'profile'])->name('profile');
    Route::get('/settings', [StudentPortalController::class, 'settings'])->name('settings');
    Route::put('/profile', [StudentPortalController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [StudentPortalController::class, 'updatePassword'])->name('password.update');
    Route::post('/courses/{course}/enroll', [StudentController::class, 'enroll'])->name('enroll');
    Route::post('/assignments/{assignment}/submit', [StudentController::class, 'submitAssignment'])->name('assignment.submit');
});

Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [InstructorPortalController::class, 'courses'])->name('courses');
    Route::get('/courses/create', [InstructorPortalController::class, 'courseCreate'])->name('courses.create');
    Route::post('/courses', [InstructorPortalController::class, 'courseStore'])->name('courses.store');
    Route::get('/courses/{id}/edit', [InstructorPortalController::class, 'courseEdit'])->whereNumber('id')->name('courses.edit');
    Route::put('/courses/{id}', [InstructorPortalController::class, 'courseUpdate'])->whereNumber('id')->name('courses.update');
    Route::get('/lessons', [InstructorPortalController::class, 'lessons'])->name('lessons');
    Route::get('/materials', [InstructorPortalController::class, 'materials'])->name('materials');
    Route::get('/assignments', [InstructorPortalController::class, 'assignments'])->name('assignments');
    Route::get('/students', [InstructorPortalController::class, 'students'])->name('students');
    Route::get('/enrollments', [InstructorPortalController::class, 'enrollments'])->name('enrollments');
    Route::get('/messages', [InstructorPortalController::class, 'messages'])->name('messages');
    Route::get('/reports', [InstructorPortalController::class, 'reports'])->name('reports');
    Route::get('/profile', [InstructorPortalController::class, 'profile'])->name('profile');
    Route::get('/settings', [InstructorPortalController::class, 'settings'])->name('settings');
});

Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminPortalController::class, 'users'])->name('users');
    Route::get('/students', [AdminPortalController::class, 'students'])->name('students');
    Route::get('/instructors', [AdminPortalController::class, 'instructors'])->name('instructors');
    Route::get('/courses', [AdminPortalController::class, 'courses'])->name('courses');
    Route::get('/lessons', [AdminPortalController::class, 'lessons'])->name('lessons');
    Route::get('/materials', [AdminPortalController::class, 'materials'])->name('materials');
    Route::get('/assignments', [AdminPortalController::class, 'assignments'])->name('assignments');
    Route::get('/enrollments', [AdminPortalController::class, 'enrollments'])->name('enrollments');
    Route::get('/reports', [AdminPortalController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminPortalController::class, 'settings'])->name('settings');
    Route::get('/instructors/create', [AdminstratorController::class, 'createInstructor'])->name('instructor.create');
    Route::post('/instructors', [AdminstratorController::class, 'storeInstructor'])->name('instructor.store');
});

// Legacy aliases retained for old links.
Route::get('/adminstrator/dashboard', fn () => redirect()->route('admin.dashboard'))->middleware(['auth', 'role:administrator'])->name('adminstrator.dashboard');
Route::get('/adminstrator/instructors/create', fn () => redirect()->route('admin.instructor.create'))->middleware(['auth', 'role:administrator'])->name('adminstrator.instructor.create');
Route::post('/adminstrator/instructors', [AdminstratorController::class, 'storeInstructor'])->middleware(['auth', 'role:administrator'])->name('adminstrator.instructor.store');
