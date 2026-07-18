# AGENTS.md

## Cursor Cloud specific instructions

Plicense is a Laravel 8 (PHP 8.0) license-management web app. Users log in to a
client area; admins issue/expire IP-locked licenses and manage users. There is a
public JSON API for license verification (`routes/api.php`) and a web UI
(`routes/web.php`) backed by AdminLTE Blade views.

### Environment already provisioned (by the update script + VM snapshot)

- PHP 8.0 + extensions and Composer are installed system-wide. `php` resolves to
  PHP 8.0. Do not upgrade to PHP 8.1+; this Laravel 8 codebase targets 8.0 (same
  as CI, see `.github/workflows/laravel.yml`).
- `composer install` and `npm install` are handled by the startup update script.
- `.env` exists (copied from `.env.example`) with an app key generated, and is
  configured to use **SQLite** at `database/database.sqlite`. `.env` is
  gitignored and persists in the VM snapshot, so it is not recreated on startup.

### Database

- This environment uses SQLite (not MySQL/MariaDB as the README describes) to
  avoid running a DB server. The file is `database/database.sqlite`; migrations
  and seeders have already been run. If you reset the DB, recreate it with:
  `touch database/database.sqlite && php artisan migrate --seed --force`.
- Tests (`phpunit.xml`) run against SQLite via env overrides, matching CI:
  `DB_CONNECTION=sqlite DB_DATABASE=/workspace/database/database.sqlite vendor/bin/phpunit`.

### Running / building (standard commands)

- Run the app (dev): `php artisan serve --host=0.0.0.0 --port=8000`.
- Build frontend assets (dev): `npm run dev` (Laravel Mix). Watch: `npm run watch`.
- Create an admin (interactive): `php artisan command:admin`.

### Non-obvious caveats

- The Blade views do NOT reference `mix()`; the UI is served by AdminLTE's
  pre-published `public/vendor` assets. So the app runs fine even if the Mix
  build has not been run. `public/js/app.js` and `public/css/app.css` are Mix
  build outputs (not committed).
- The repo has no committed npm lockfile. Because `laravel-mix` v6 breaks with
  the newest webpack 5 (missing `webpack/lib/SizeFormatHelpers`), `package.json`
  pins webpack via an npm `overrides` entry so `npm run dev` works. Keep that pin.
- The default `tests/Feature/ExampleTest.php` asserts `GET /` returns 200, but
  `/` redirects (302) to the auth-gated home page, so that one test fails. This
  is a pre-existing repo test/behavior mismatch, not an environment problem.
