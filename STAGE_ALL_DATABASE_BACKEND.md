# LearnHub — Database Backend Conversion

This version converts the LearnHub frontend prototype into a database-backed Laravel application while keeping the existing Blade/CSS/JavaScript UI.

## What was changed

- Demo mode disabled (`LEARNHUB_DEMO=false`).
- Public demo login shortcuts removed.
- Demo/test account seeders removed.
- Student registration creates both `users` and `students` records in a transaction.
- Login uses Laravel session authentication and redirects by `usertype`.
- Student, instructor and administrator portal routes require authentication and the correct role.
- Student identity is loaded from the authenticated user instead of the fixed demo student.
- Courses, instructors, enrollments, lessons, assignments and submissions are read from MySQL.
- Course enrollment is a real database operation.
- Assignment submission stores uploaded files in the public disk.
- Instructor course creation/editing writes to MySQL.
- Student profile and password updates are real database operations.
- Course metadata (`summary`, `category`, `level`) was added to the courses table.
- Course `admin_id` is optional so instructors can create courses even before an administrator assigns them.
- All LearnHub courses remain completely free. There is no payment, checkout, price, revenue, refund or payout workflow.

## Setup in your existing project

1. Back up your current project and database.
2. Copy the changed project files into your Laravel project.
3. Make sure `.env` contains:

```env
LEARNHUB_DEMO=false
```

4. Confirm your MySQL credentials in `.env`.
5. Run:

```powershell
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

6. If the project dependencies are missing, run:

```powershell
composer install
```

## Important: no demo accounts are created

`DatabaseSeeder` and `UserSeeder` intentionally do not create demo accounts.

To create your first administrator, use Tinker once:

```powershell
php artisan tinker
```

Then create the account using your own details:

```php
$user = \App\Models\User::create([
    'name' => 'YOUR ADMIN NAME',
    'email' => 'YOUR ADMIN EMAIL',
    'password' => \Illuminate\Support\Facades\Hash::make('YOUR ADMIN PASSWORD'),
    'usertype' => 'administrator',
]);

\App\Models\Adminstrator::create([
    'user_id' => $user->id,
    'phone' => 'YOUR PHONE',
]);
```

Then:

```php
exit
```

## Recommended test order

### Student

1. Register a new student.
2. Confirm a row exists in `users` with `usertype = student`.
3. Confirm a matching row exists in `students` with the same `user_id`.
4. Log in.
5. Confirm the dashboard displays the logged-in student's name.
6. Open the public course catalogue.
7. Enroll in a published course.
8. Confirm the enrollment appears in `enrollments`.
9. Open the course from My Courses.
10. Submit an assignment if one exists.

### Administrator

1. Log in using the administrator account created above.
2. Open `/admin/dashboard`.
3. Confirm student/instructor/course/enrollment counts come from MySQL.
4. Create an instructor.

### Instructor

1. Log in with the instructor created by the administrator.
2. Open Instructor Dashboard.
3. Create a course.
4. Confirm it appears in `courses`.
5. Publish it and verify students can see it in the catalogue.

## Existing database data

The application does not automatically delete existing rows from your database. If your current database still contains old demo accounts, remove those rows manually after verifying they are not needed.

Do not run `php artisan migrate:fresh` unless you intentionally want to delete all existing database tables and data.
