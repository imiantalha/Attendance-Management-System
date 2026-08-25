# API Structure

The application exposes a versioned JSON API under `/api/v1` while keeping the existing Blade/web application separate.

## Architecture

```text
API Request
    |
    v
FormRequest
    |
    v
API Controller
    |
    v
Service
    |
    v
Model / Query
    |
    v
API Resource
    |
    v
JSON Response
```

## Principles

- `/api/v1/*` is the public API boundary for API consumers.
- Blade controllers remain responsible for HTML views and redirects.
- FormRequests own validation and request-level authorization.
- Policies and Spatie permissions protect resource operations.
- Services own reusable business logic such as attendance duration calculations and reports.
- API Resources own response transformation and explicitly control exposed fields.
- Relationships are eager-loaded when required by Resources to avoid N+1 queries.
- Sensitive model attributes such as passwords and remember tokens are never exposed by `UserResource`.
- API versioning allows future response-contract changes without silently breaking existing consumers.

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

Versioned endpoints require Sanctum authentication. Resource-management endpoints additionally enforce the application's permissions and policies.

The legacy `/api/user` endpoint remains available for backward compatibility and uses `UserResource` as well.

## Response contract

Successful Resource responses follow Laravel's standard `data` envelope. Paginated collections also expose Laravel pagination metadata and links.

Example:

```json
{
  "data": {
    "id": 1,
    "name": "Example User",
    "email": "user@example.com"
  }
}
```

Validation and authorization failures use Laravel's standard HTTP error behavior. API consumers should use the HTTP status code and `errors` payload rather than relying on HTML responses.

## Adding a new endpoint

When adding a new API resource:

1. Add a versioned route under `/api/v1`.
2. Use a dedicated FormRequest when input validation is required.
3. Authorize the operation with the relevant Policy/permission.
4. Keep business logic in a service when it is reusable or domain-specific.
5. Return an API Resource rather than an Eloquent model.
6. Eager-load relationships required by the Resource.
7. Add feature tests for authentication, authorization, validation, status codes, and response structure.
8. Document the endpoint here.
