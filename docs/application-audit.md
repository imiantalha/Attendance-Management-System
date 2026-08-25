# Application Audit

## Scope

This audit covers routing, controllers, models, validation, attendance calculations, authorization, database integrity, API design, reporting, views, automated testing, CI, and production deployment safety.

## Current status

The major findings identified during the application hardening work have been addressed or intentionally deferred with a documented reason. `main` is the source of truth for the current implementation.

## Severity scale

- **Critical** — security, data integrity, or core business-logic defect.
- **High** — significant functionality, authorization, or maintainability risk.
- **Medium** — correctness, UX, or performance issue that may become important as usage grows.
- **Low** — cleanup, consistency, or future improvement.

## Findings and resolution status

| Severity | Finding | Status |
|---|---|---|
| Critical | Attendance CRUD was authenticated but not permission-protected. | Fixed |
| Critical | Attendance reports accepted arbitrary user IDs without explicit authorization. | Fixed |
| Critical | `attendance_by` could be trusted from request input. | Fixed; actor is determined server-side |
| Critical | Attendance duration logic was duplicated and handled overnight/midnight inconsistently. | Fixed; centralized in `AttendanceService` |
| Critical | Open attendance records with null checkout could break calculations/views. | Fixed |
| Critical | Different report types could calculate the same attendance record differently. | Fixed through shared service/report logic |
| High | Duplicate attendance for the same employee/date was not prevented. | Fixed in application + database constraint |
| High | User CRUD lacked consistent authorization. | Fixed with policies/permissions |
| High | User CRUD accepted overly broad request payloads. | Fixed with FormRequests |
| High | Role management bypassed the Spatie model layer. | Fixed |
| High | Role names were not consistently unique on update. | Fixed |
| High | Product CRUD used broad request input. | Fixed with FormRequests |
| High | Controller-level validation was inconsistent. | Fixed with dedicated FormRequests across applicable flows |
| High | API responses could expose Eloquent models directly. | Fixed with API Resources at the API boundary |
| High | Attendance business logic was mixed into controllers. | Fixed with service layer |
| Medium | Attendance reports could perform large PHP-side aggregations. | Partially addressed; profile SQL/report queries with realistic data before high-scale deployment |
| Medium | Test coverage was initially minimal. | Significantly expanded with unit/feature/API regression coverage |
| Medium | PHPUnit lacked an isolated SQLite test setup. | Fixed |
| Medium | Automated CI was missing/incomplete. | Fixed with GitHub Actions quality checks |
| Medium | Production configuration and deployment requirements were undocumented. | Fixed; see `docs/production-readiness.md` |
| Medium | Dashboard and management screens were basic. | Modernized with responsive UI/UX improvements |
| Low | Unused/debug code existed in management flows. | Removed |
| Low | Pagination and empty states were inconsistent. | Improved across key management screens |
| Low | Laravel 10 / PHP 8.1 baseline is aging. | Intentionally deferred until compatibility and regression testing are ready |

## Architecture now

Web requests follow:

```text
FormRequest -> Controller -> Service -> Model/Query -> Blade View
```

API requests follow:

```text
FormRequest -> API Controller -> Service -> Model/Query -> API Resource -> JSON
```

Policies and permission checks protect sensitive operations. API Resources define the public response contract and prevent accidental exposure of model attributes.

## Regression coverage

Coverage includes attendance calculations for:

- normal same-day shifts;
- overnight shifts;
- midnight checkout;
- open attendance with no checkout;
- equal start/end times;
- duration formatting;
- report date filtering and user isolation;
- dashboard authorization and metrics;
- API authentication and Resource response structures;
- attendance authorization/policy behavior.

CI also performs Composer validation, dependency installation, formatting checks, tests, and dependency vulnerability auditing.

## Production readiness roadmap

Before declaring a deployment production-ready, complete and verify:

1. CI is green on the exact commit intended for deployment.
2. Production secrets are supplied through the deployment environment, never committed.
3. `APP_ENV=production` and `APP_DEBUG=false` are confirmed.
4. HTTPS and secure cookies are enabled.
5. Database backups and restoration procedures are tested.
6. `/up` is monitored by the deployment/load balancer health check.
7. Queue workers, scheduler, logs, and monitoring are configured where used.
8. Database indexes and report queries are profiled against realistic production-scale data.
9. Authentication, RBAC, attendance CRUD, reports, and API endpoints are smoke-tested after deployment.
10. GitHub `main` is protected with required PR review and successful CI checks.
11. A Laravel/PHP modernization plan is completed and tested before changing the framework/runtime baseline.

## Intentional non-goals

The project does not introduce repositories, DTOs, or additional abstraction layers merely for architectural appearance. New abstractions should be added only when they solve a real reuse, testing, or domain-complexity problem.

## Migration safety

Attendance uniqueness migrations must not silently delete or merge historical duplicates. Existing duplicate data must be reviewed before a uniqueness constraint can safely be introduced.
