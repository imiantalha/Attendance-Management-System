# Production Readiness

## Application configuration

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Generate a unique `APP_KEY` and keep it out of source control.
- Configure `APP_URL` with the HTTPS application URL.
- Configure production database credentials through environment variables or the deployment secret manager.
- Configure SMTP credentials through secrets; never commit credentials.
- Use a persistent cache, session, and queue backend appropriate for the deployment.

## Deployment

1. Install dependencies with `composer install --no-dev --prefer-dist --optimize-autoloader`.
2. Run `php artisan migrate --force` after taking the required database backup.
3. Run `php artisan storage:link` when public storage is required.
4. Run `php artisan config:cache`.
5. Run `php artisan route:cache`.
6. Run `php artisan view:cache`.
7. Restart queue workers after every deployment when queues are enabled.
8. Verify `GET /up` returns `{ "status": "ok" }`.
9. Confirm HTTPS, cookies, mail delivery, login, attendance CRUD, reports, and authorization after deployment.

## Security

- Keep `.env` and production secrets outside source control.
- Enforce HTTPS at the reverse proxy/load balancer.
- Keep database access private and restricted to the application network.
- Use least-privilege database credentials.
- Keep dependencies updated and review `composer audit` findings.
- Do not enable Laravel debug mode in production.
- Configure log retention and monitoring.
- Configure scheduled database backups and periodically test restoration.

## CI requirements

Every pull request to `main` should pass:

- Composer validation
- Laravel Pint style checks
- PHPUnit/Laravel test suite
- Composer dependency audit

`main` should be protected in GitHub so changes are merged through reviewed pull requests with required CI checks.

## Operational checks

After deployment, verify:

- `/up` is healthy.
- Authentication works.
- Permission-restricted pages reject unauthorized users.
- Attendance create/update/delete works.
- Attendance reports show correct totals for normal and overnight shifts.
- API authentication and Resource response contracts work.
- Error logs contain no unexpected application exceptions.
