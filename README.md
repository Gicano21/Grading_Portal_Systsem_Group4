# Grading Portal Management System

A CodeIgniter 4 application for managing students, class offerings, enrollment, instructors, and academic grades.

> **Project status:** Student, instructor, administrator, and registrar workflows are implemented for local development. Configure a database, migrate, and seed it before signing in.

## Requirements

- PHP 8.2 or newer with `intl`, `mbstring`, and `mysqli` enabled
- Composer
- MySQL or MariaDB
- `sqlite3` and `pdo_sqlite` PHP extensions to run the test suite

## Local setup

Run these commands from the project root (the directory containing `spark` and `composer.json`):

```powershell
composer install
if (-not (Test-Path .env)) {
    Copy-Item vendor\codeigniter4\framework\env .env
}
```

Edit `.env` and set the application URL and database connection. For example:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = localhost
database.default.database = grading_portal
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Create the `grading_portal` database (or use the database name you configured), then apply the schema and load the example records:

```powershell
php spark migrate
php spark db:seed InitialData
```

The seeder inserts sample records for students, accounts, courses, programs, sections, academic years, enrollments, instructors, course enrollments, and grades. It includes hard-coded demo account passwords; use seed data only in a local development database and change the seeder credentials before using it in any shared environment. The seeder uses batch inserts, so do not run it repeatedly against the same database unless you have cleared the existing sample rows.

The local demo credentials are:

| Role | Username | Password |
| --- | --- | --- |
| System administrator | `admin` | `admin123` |
| Registrar | `registrar` | `registrar123` |
| Instructor | `teacher1` | `teacher123` |
| Student | `student1` | `student123` |

These are development-only credentials. Change them or remove the demo accounts before deploying the application.

Start the local development server:

```powershell
php spark serve
```

Open `http://localhost:8080/` and sign in with a seeded account. The application redirects each account to its role-specific workspace.

## Database migrations

Migrations are in `app/Database/Migrations`. They define these tables:

| Table | Purpose |
| --- | --- |
| `students` | Student records |
| `accounts` | Account names, password hashes, and role labels |
| `instructors` | Instructor records |
| `courses` | Course catalog |
| `programs` | Academic programs |
| `sections` | Class sections |
| `academic_years` | Academic years and semesters |
| `enrolments` | Student program, year-level, academic-year, and section enrollment |
| `class_offerings` | Course, section, academic term, and assigned instructor |
| `courses_enrollments` | Student enrollment in a class offering |
| `grades` | Period and final grades |

Useful migration commands:

```powershell
php spark migrate:status
php spark migrate
php spark migrate:rollback
```

`migrate:rollback` rolls back the most recent migration batch. Back up any database with data you need before rolling migrations back.

The original prototype migrations were adjusted for the completed workflows. For a database created from the earlier prototype, back it up and use a fresh development database before applying this version; do not assume the edited initial migrations will automatically update an already-migrated database.

## Use cases

The role-protected workspaces implement the following use cases:

### Student

- Log in securely using student credentials.
- View grades recorded for the student.

### System administrator

- Log in with administrative credentials.
- Encode new student grades.
- Update or correct existing grades.
- View class lists.

### Instructor

- Log in using faculty credentials.
- Encode initial grades for assigned courses.
- Update previously submitted grades.
- View class lists for assigned courses.

### Registrar

- Log in to the administrative portal.
- Add class sections or offerings.
- Enroll students in classes.
- Assign instructors to classes.
- Generate and print student grade reports.

## Project progress

- [x] Password-hash-based login, logout, and role-protected routes.
- [x] Student grade view scoped to the signed-in student's records.
- [x] Instructor class list restricted to assigned offerings, with grade entry and updates.
- [x] Administrator class lists and grade entry/update across offerings.
- [x] Registrar class-offering and section setup, student enrollment, and faculty assignment.
- [x] Printable grade report for each class offering.
- [x] Grade validation from 0 to 100 and final-grade arithmetic mean across all four periods.
- [x] Responsive role-specific portal pages and local demo seeding.
- [x] Database tests for migrations, seed data, student grade scoping, and grade calculations.
- [ ] Production account provisioning and password-management workflow.
- [ ] Route-level authorization and registrar enrollment tests.

The final grade is calculated only after Prelim, Midterm, Pre-final, and Final are all entered. Until then, it remains pending.

## Tests

Run the PHPUnit suite from the project root:

```powershell
composer test -- --no-coverage
```

The test database is configured separately in `app/Config/Database.php` (or `.env`) and defaults to in-memory SQLite. Do not point automated tests at a database containing important data.

## Project structure

```text
app/
  Controllers/       Request handlers
  Database/
    Migrations/      Database schema changes
    Seeds/           Development seed data
  Views/             Page templates
public/              Web server document root
tests/               PHPUnit tests
```

Configure your web server to use `public/` as its document root. For local development, `php spark serve` does this for you.
