# Attendance Management System

A production-oriented attendance management application built with Laravel, Blade, Bootstrap, Sanctum, and Spatie Laravel Permission.

## Features

- Authentication and password recovery
- Role and permission management
- Employee management
- Attendance create, update, view, and delete workflows
- Attendance duration calculations, including overnight shifts
- Weekly, monthly, and yearly attendance reporting
- Dashboard attendance metrics
- Policy- and permission-based authorization
- FormRequest-based validation
- Service-layer business logic
- API v1 endpoints with Laravel API Resources
- Responsive management UI
- Automated tests and GitHub Actions CI
- Production health check at `/up`

## Architecture

The application keeps web and API responsibilities separate:

```text
Web/API Request
      |
      v
FormRequest
      |
      v
Controller
      |
      v
Service
      |
      v
Model / Query
      |
      +----> Blade View (Web)
      |
      +----> API Resource (JSON API)
```

### Key directories

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/V1/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Policies/
└── Services/

database/
├── factories/
├── migrations/
└── seeders/

tests/
├── Feature/
└── Unit/

resources/views/
├── attendances/
├── users/
├── roles/
└── dashboard.blade.php
```

## Requirements

- PHP 8.1+
- Composer 2+
- Node.js and npm
- MySQL, MariaDB, PostgreSQL, or another Laravel-supported database

The current dependency baseline is Laravel 10. Before upgrading Laravel or PHP, review dependency compatibility and run the full regression suite.

## Local setup

```bash
git clone https://github.com/imiantalha/attendance-management-system.git
cd attendance-management-system

composer install
cp .env.example .env
php artisan key:generate

# Configure DB_* values in .env first
php artisan migrate --seed

npm install
npm run build

php artisan serve
```

Open the application at the URL shown by `php artisan serve`.

## Testing

Run the full suite with:

```bash
php artisan test
```

Run formatting checks with:

```bash
./vendor/bin/pint --test
```

Check dependencies for known vulnerabilities with:

```bash
composer audit
```

GitHub Actions runs the project's quality checks for pull requests targeting `main`.

## API

API endpoints are versioned under `/api/v1` and protected with Sanctum where authentication is required.

Example endpoints include:

```text
GET    /api/v1/me
GET    /api/v1/users
GET    /api/v1/users/{user}
GET    /api/v1/attendances
GET    /api/v1/attendances/{attendance}
POST   /api/v1/attendances
PUT    /api/v1/attendances/{attendance}
PATCH  /api/v1/attendances/{attendance}
DELETE /api/v1/attendances/{attendance}
```

API responses use dedicated Laravel API Resources instead of exposing Eloquent models directly.

## Production deployment

For production, set `APP_ENV=production`, `APP_DEBUG=false`, use HTTPS, keep secrets outside source control, and configure persistent database/cache/session/queue infrastructure.

Recommended deployment commands:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Verify the health endpoint after deployment:

```text
GET /up
```

See [`docs/production-readiness.md`](docs/production-readiness.md) for the complete deployment, security, CI, backup, and post-deployment checklist.

## Environment files

- `.env.example` — local/development configuration template.
- `.env.production.example` — production configuration reference. Never commit real credentials.

## Security

If you discover a security issue, do not publish credentials, tokens, or exploit details in a public issue. Report the issue privately to the repository owner/maintainer.

## License

This project is open-sourced under the MIT License unless otherwise stated by the repository owner.
