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

## Deploy online with the existing database

The application stores live records in MySQL, not in the fixture SQL file. Admin
login uses `users`; customer records and customer logins use `customer_accounts`.
Export the whole `electric_company` database, including `migrations`, to preserve
the existing accounts and password hashes. Importing only the fixture will not
include the admin account or the current login schema.

Hosting must be free and must not require a credit card. [InfinityFree](https://www.infinityfree.com/)
explicitly offers PHP/MySQL hosting without a credit card, including a free
subdomain. Check the actual account supports `intl`, `mbstring`, and `mysqli`
before publishing this CodeIgniter application. The existing app requires PHP
8.1 or higher; use a supported PHP version such as 8.3 when available.

Render can host the PHP application using Docker, but [MySQL on Render](https://render.com/docs/deploy-mysql)
needs persistent storage on a paid service. Its [free Postgres database](https://render.com/docs/free)
is not a drop-in replacement for this MySQL application and expires after 30 days.
A possible alternative is Render for PHP plus [Aiven's free MySQL plan](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier),
which advertises no credit card requirement. That option needs a Docker deployment
and a verified TLS database connection configured separately; the shared-hosting
ZIP below is not a Render deployment. InfinityFree databases cannot be used by a
Render application because [external database access is restricted](https://forum.infinityfree.com/t/connecting-to-mysql-from-an-external-application/49339).

### Prepare the upload and database export

Keep MySQL running in XAMPP. Run from this project directory:

```powershell
powershell -ExecutionPolicy Bypass -File deploy/package.ps1 -ExportDatabase
```

The command produces `demoelectric-site.zip` and `electric-company.sql` in a new
folder under `writable/deployment/`. The SQL is a private export with existing
password hashes; never place it in `public/` or commit it to Git. The website ZIP
excludes your `.env`, old sessions, logs, database backups, and test scripts.
It contains writable directories with placeholder files (so hosting ZIP extractors
retain them) and the production configuration template.
If local MySQL needs a password, add `-AskDatabasePassword` to enter it privately.

For a host with a fixed `htdocs/` document root (including InfinityFree), use:

```powershell
powershell -ExecutionPolicy Bypass -File deploy/package.ps1 -SharedHosting -ExportDatabase
```

This creates `demoelectric-shared-hosting.zip`, which also includes the root
`index.php` and `.htaccess` used by XAMPP. The rewrite rules serve assets from
`public/` and deny direct access to private application directories.

### Configure the hosting account

1. Create the free hosting account and choose its free subdomain. With
   InfinityFree, extract `demoelectric-shared-hosting.zip` locally and upload its
   contents to the domain's `htdocs/` directory using the host's file transfer
   instructions. Include all hidden `.htaccess` files. With a host that permits
   setting the document root, extract `demoelectric-site.zip` into a private
   project directory and point the site's document root at its `public/` folder.
2. Create a NEW, EMPTY MySQL/MariaDB database and a database user in the hosting
   control panel. Import `electric-company.sql` into that database using its
   phpMyAdmin import feature. Keep the SQL outside the website directory.
   Do not import over an existing populated database.
3. Copy `env.production.example` to `.env` beside `app/` on the server. Replace
   every `CHANGE_ME` with the actual HTTPS site URL, database host, database name,
   database username, and database password from the hosting control panel.
   Hosted database names may have an account prefix. The database password is
   separate from the website admin password. Do not upload a local `.env`.
4. Confirm PHP and the `intl`, `mbstring`, and `mysqli` extensions are enabled.
   The bundled framework already includes its runtime libraries. Make sure the
   hosting PHP process can write to `writable/` and its subdirectories.
5. Enable HTTPS. The template uses HTTPS URLs and secure session cookies.
   Do not expose `app/`, `system/`, `writable/`, or `.env` as public files.
6. Open `https://YOUR-SITE/admin/login` and use the existing admin
   credentials. The full database import preserves them; no reseeding is needed.
   The classroom default `admin123` is publicly documented, so use a private
   admin password before putting real customer information online.

The online site will use the hosted database for all visitors. Later changes to
your local XAMPP database will not automatically sync to it. Users access records
through the website; they do not need database credentials. Admin pages remain
restricted to signed-in admins and customers see their own profile.

### Verify after publishing

The bundled CodeIgniter 4.5.5 `system/I18n/TimeTrait.php` includes a targeted
backport of the PHP 8.4 `createFromTimestamp()` signature fix, based on
[the upstream compatibility patch](https://github.com/codeigniter4/CodeIgniter4/pull/9134/files).
It preserves the old default timezone and whole-second precision, and supports
late static binding. This addresses the observed declaration fatal error; it is
not a full framework upgrade or a claim of complete PHP 8.4 compatibility.
For an already uploaded copy, replace only `htdocs/system/I18n/TimeTrait.php`
with the corrected local file, then refresh and check the server log if needed.

Open the HTTPS URL from another device or mobile data. Check Home, About,
Services, Contact, Register, Customer Login, and Admin Login; verify links and
assets do not point to `localhost`. Log in as admin and verify the imported
records. Register a temporary customer, log in on another device, and verify that
the same record appears in the admin dashboard. Confirm logout works and a guest
cannot open `/admin/dashboard`. Verify `/.env` and `/app/Config/Database.php` are
not downloadable. The existing `tests/smoke.php` deliberately runs only locally.

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
