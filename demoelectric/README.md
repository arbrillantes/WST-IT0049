# Puihaha Electric — class project

Me finish professor tasks: customer CRUD, Puihaha website connection, and login/session redirect to dashboard. Updated 4 October 2026.

## Run here

1. Start Apache and MySQL in XAMPP. Both were started during setup.
2. Customers register at http://localhost/Brillantes/IT0049/demoelectric/register, then use `/login` with email/password. Customer login opens `/customer/dashboard`, showing only their own profile.
3. Admin uses http://localhost/Brillantes/IT0049/demoelectric/admin/login with username **admin** and password **admin123**. There is no admin registration page or endpoint.
4. Admin login opens `/admin/dashboard`: customer records, 10 per page, with Add customer, View, Edit, Delete. New registrations appear here automatically. Customers cannot access these pages or submit CRUD requests.
5. Logout ends session. Log out before switching between customer and admin accounts. Old `/dashboard` links redirect to the correct dashboard for the current role.

AdminSeeder creates the requested classroom admin in `users` with a hashed password. Re-running it does not reset an existing admin password. `users` is for admins only. Public registration creates the profile, hashed password, and generated account number directly in `customer_accounts`; customer login reads this same table. Submitted admin roles/usernames are ignored. Admin-created records without a password cannot log in, and public registration cannot claim an existing record by reusing its email.

The customer-login migration moved the existing customer out of `users`, preserving all profile fields and the password hash. There are now 26 customer records (25 supplied + 1 migrated), and only the admin in `users`. A pre-migration database backup is at `writable/backups/before-customer-auth-20261004-160758.sql`. Old sessions expire to avoid collisions between IDs in the two tables; log in again after migration.

Guest navigation order: Login, Customer register, Admin login (far right).

## Database setup on another copy

Database: `electric_company`, local MySQL port 3306, user `root`, blank password (XAMPP defaults). Existing local database had no application tables; me created them and imported the supplied customer rows.

Run from this project directory:

```powershell
& C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS electric_company CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php spark migrate
php spark db:seed CustomerAccountSeeder
php spark db:seed AdminSeeder
```

Seeder reads the exact supplied SQL data in `app/Database/Fixtures/customer_accounts.sql`. It skips a nonempty customer table. Migration creates missing tables without replacing existing ones; automatic rollback is disabled to protect imported tables. Back up a database before structural changes.

For a different folder/database, copy `env` to `.env` and set `app.baseURL` (include trailing slash) and `database.default.*`. Current URL uses the root `.htaccess` to serve assets from `public/assets`. The two ZIPs and original Downloads SQL were left unchanged.

## What was wrong / what me changed

- Code used `electriccompany`; supplied SQL described `electric_company`. Me aligned config and created missing `users` table.
- SQL had only customer records. Login ZIP expected `user_accounts.username`; project registration used `users.email`. Me connected login to existing hashed-password registration.
- No customer CRUD existed. Me added validated create/edit/delete, detail pages, duplicate account-number checks, and delete confirmation.
- ZIP examples both used `Home`; direct copying would conflict with the public website. Me kept home and added separate `Auth` and `Dashboard` controllers.
- Pagination example applied only one filter and tied dates gave unstable ordering. Me combined search/status/type, added ID sorting, kept filters in Bootstrap page links, and added empty/missing-record handling.
- Wrong base URL and asset location broke local links/styles. Me fixed config and asset rewrites, blocked web access to environment/config files, and corrected mobile-menu attribute and duplicate/broken JavaScript anchor/animation handlers.
- Added login filter to every customer route, POST-only writes/logout, active CSRF protection, session regeneration at login and destruction at logout, no-store protected responses, and escaped dynamic output.
- Corrected initial shared-dashboard mistake: customer and admin logins now query their respective roles, registration remains customer-only, and all management routes require admin. The guard rechecks the active user/role in the database on each protected request, including existing sessions.
- Corrected identity storage: registration/customer login now use `customer_accounts`; admin login uses `users`. Added customer profile/password fields and unique optional emails, migrated the existing customer, and separated session identity stores. Existing SQL sample rows keep their data and have no login password.
- Failed login/registration no longer put passwords in flashdata or show database errors. Registration accepts province/state text and four-digit postal codes.
- Contact controller declared a string return but returned redirects on submit; me fixed that type. Contact remains the original demo: it does not send mail or save inquiries.

## Where code lives

- `app/Controllers/Auth.php`, `app/Filters/AuthFilter.php`: login, logout, session guard.
- `app/Controllers/Customer.php`, `app/Views/customer_dashboard.php`: customer-only profile page. Shared login view renders separate customer/email and admin/username forms at separate URLs.
- `app/Database/Migrations/2026-10-04-170000_AddAdminUsername.php`, `app/Database/Seeds/AdminSeeder.php`: optional unique usernames and the requested admin account.
- `app/Database/Migrations/2026-10-04-180000_MoveCustomerLogins.php`: customer credentials/profile schema, email uniqueness, and verified transaction to relocate former customer users. Conflicting emails stop migration for review instead of merging identities.
- `app/Controllers/Dashboard.php`, `app/Models/CustomerAccount.php`: customer CRUD, search, filters, pagination.
- `app/Views/login.php`, `dashboard.php`, `account*.php`, `pagers/bootstrap.php`: screens and pager.
- `app/Controllers/Register.php`, `app/Models/CustomerAccount.php`: customer registration and password hashing. `app/Models/User.php` handles admins only.
- `app/Config/Routes.php`, `Filters.php`, `Security.php`, `Session.php`, `App.php`, `Database.php`, `Pager.php`: wiring and local configuration.
- `app/Database/Migrations`, `Seeds`, `Fixtures`: reproducible schema/sample data.
- `app/Views/layout.php`, `public/assets`: shared navigation and styling.

## Checks

```powershell
php tests/smoke.php
node --check public/assets/js/app.js
php spark routes
```

HTTP smoke test passed 83 checks: registration into `customer_accounts` with no write to `users`, hashed passwords, generated customer numbers, visibility in admin's list, far-right admin navigation, separate logins, role restrictions, duplicate validation, full CRUD, filtering/pagination, CSRF, logout and old-session rejection. It creates random temporary records and removes only those records; existing customers and the admin remain. Run against the seeded local database with Apache/MySQL running. Test DB credentials can use `DEMO_DB_USER`, `DEMO_DB_PASSWORD`, `DEMO_DB_PORT`; a changed admin password can use `DEMO_ADMIN_PASSWORD`.

Only admins can manage customer records. Bootstrap and Font Awesome still load from CDNs. The requested `admin123` credential is for this local classroom demo.

---

# CodeIgniter 4 Framework

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds the distributable version of the framework.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Contributing

We welcome contributions from the community.

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/CONTRIBUTING.md) section in the development repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
