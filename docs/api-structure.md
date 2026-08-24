# API Structure

The application uses a versioned JSON API under `/api/v1` while keeping the existing Blade/web application separate.

## Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/
│   │   │       ├── AttendanceController.php
│   │   │       └── UserController.php
│   │   ├── Controllers used by Blade/web
│   │   ├── Requests/
│   │   └── Resources/
│   │       ├── AttendanceResource.php
│   │       └── UserResource.php
│   └── ...
├── Services/
│   └── AttendanceService.php
└── Models/
```

## Principles

- `/api/v1/*` is reserved for API consumers and returns JSON Resources.
- Blade controllers remain responsible for HTML views and redirects.
- FormRequests own validation and request authorization.
- Services own reusable business logic such as attendance duration calculation.
- API Resources own response transformation and prevent accidental model-field exposure.
- Relationships are eager-loaded before being exposed by Resources to avoid N+1 queries.
- Sensitive model attributes such as passwords and remember tokens are never exposed by `UserResource`.

## Current endpoints

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/v1/me` | Authenticated user |
| GET | `/api/v1/users` | Paginated users |
| GET | `/api/v1/users/{user}` | User details |
| GET | `/api/v1/attendances` | Paginated attendance |
| GET | `/api/v1/attendances/{attendance}` | Attendance details |
| POST | `/api/v1/attendances` | Create attendance |
| PUT/PATCH | `/api/v1/attendances/{attendance}` | Update attendance |
| DELETE | `/api/v1/attendances/{attendance}` | Delete attendance |

All versioned endpoints require Sanctum authentication. Resource-management endpoints additionally enforce the application's Spatie permissions.

The existing `/api/user` endpoint remains available for backward compatibility and now uses `UserResource` as well.
