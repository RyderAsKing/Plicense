# AGENTS.md

## Cursor Cloud specific instructions

Plicense is a Laravel 13 (PHP 8.3+) license-management web app. Users log in to a
client area; admins issue/expire IP-locked licenses and manage users. There is a
public JSON API for license verification (`routes/api.php`) and a web UI
(`routes/web.php`) backed by AdminLTE Blade views.

### Environment already provisioned (by the update script + VM snapshot)

- PHP 8.3 + extensions and Composer are installed system-wide. Prefer
  `/usr/bin/php8.3` (or `update-alternatives` so `php` resolves to 8.3).
- `composer install` and `npm install` are handled by the startup update script.
- `.env` exists (copied from `.env.example`) with an app key generated, and is
  configured to use **SQLite** at `database/database.sqlite`. `.env` is
  gitignored and persists in the VM snapshot, so it is not recreated on startup.

### Database

- This environment uses SQLite (not MySQL/MariaDB as production often would) to
  avoid running a DB server. The file is `database/database.sqlite`; migrations
  and seeders have already been run. If you reset the DB, recreate it with:
  `touch database/database.sqlite && php artisan migrate --seed --force`.
- Tests use SQLite in-memory via `phpunit.xml` (or `php artisan test`):
  `DB_CONNECTION=sqlite DB_DATABASE=:memory:`.

### Running / building (standard commands)

- Run the app (dev): `php artisan serve --host=0.0.0.0 --port=8000`.
- Frontend (Vite, optional): `npm run build` / `npm run dev`. AdminLTE UI assets
  come from the package's published `public/vendor` files, so the app runs
  without a Vite build for the Blade AdminLTE screens.
- Create an admin (interactive): `php artisan command:admin`.

### Non-obvious caveats

- The Blade views do NOT reference Vite/`mix()`; the UI is served by AdminLTE's
  pre-published `public/vendor` assets.
- API admin auth uses the classic `token` guard with a hashed `users.api_token`
  column (`Authorization: Bearer …`), not Sanctum PATs.
- Schedule: `command:license_expiration` runs every minute (see `bootstrap/app.php`).
