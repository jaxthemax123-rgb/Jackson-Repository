# Lab 7 Peer Tutoring Hub (User Management)

Lab 6 app extended with user accounts, login, logout, sessions, roles (Student, Tutor, Administrator) and CSRF tokens. Use case IDs UM-01 to UM-10 come from the User Management Design Document.

## Run in GitHub Codespaces

1. `cd lab7/database`
2. `sqlite3 tutoring.db < setup-database.sql`
3. `sqlite3 tutoring.db < upgrade-to-user-management.sql` (run once)
4. `php create-admin.php "Test Administrator" admin@example.com "YourPassword12"`
5. `cd ..` then `php -S 0.0.0.0:8000` and open port 8000.
6. Tests: `php tests/validation-tests.php`, `php tests/user-management-tests.php`, `php tests/integration-tests.php`

**Classroom only:** use test data only and do not deploy publicly.
