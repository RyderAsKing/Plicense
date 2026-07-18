# Plicense

IP-locked license management built with [Laravel](https://laravel.com) 13 and PHP 8.3+.

Issue licenses, bind them to a client IP on first use, and let customers reissue when their IP changes — from a web client area or a small JSON API.

## Features

- **IP-locked licenses** — keys bind to the verifying IP on first use
- **Client area** — users view licenses and reissue IP locks
- **Admin panel** — manage users and licenses (AdminLTE)
- **Public verify API** — `GET/POST /api/license/...` for product checks
- **Admin API** — token-authenticated user create/delete endpoints
- **Expiration job** — marks due licenses expired on a schedule

## Requirements

- PHP 8.3+
- Composer
- SQLite, MySQL, or MariaDB
- Node.js (optional; UI uses AdminLTE published assets)

## Stack

| Component | Version |
|-----------|---------|
| PHP | ^8.3 |
| Laravel | ^13.0 |
| AdminLTE | jeroennoten/laravel-adminlte ^3.16 |
| Auth scaffolding | laravel/ui ^4.6 |

## License

This project is open source under the [MIT License](LICENSE).

Copyright (c) 2021–2026 Ryder Asking

## Contributing

Pull requests are welcome. For larger changes, open an issue first so we can align on direction. Please update tests when you change behavior.
