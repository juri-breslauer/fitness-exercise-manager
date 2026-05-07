# Production Checklist

Use this checklist before promoting a release.

## Application

- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_KEY` is generated, present, and kept secret.
- [ ] `APP_URL` matches the public URL.
- [ ] `GET /health` returns `{"status":"ok","app":"Fitness Exercise Manager"}`.
- [ ] Public `/api/v1` endpoints still return the documented JSON contracts.

## Database

- [ ] PostgreSQL connection variables are set.
- [ ] Database credentials are not committed.
- [ ] `php artisan migrate --force` has run successfully.
- [ ] Seeders are only run intentionally.
- [ ] Backups and restore procedures are handled outside this repository.

## Cache and Config

- [ ] `CACHE_STORE=redis` for production-like deployments.
- [ ] `SESSION_DRIVER=redis` for production-like deployments.
- [ ] `php artisan optimize:clear` was run before rebuilding caches.
- [ ] `php artisan config:cache` completed successfully.
- [ ] `php artisan route:cache` completed successfully.
- [ ] `php artisan view:cache` completed successfully.

## Queue and Scheduler

- [ ] `QUEUE_CONNECTION=redis` when background jobs are enabled.
- [ ] Queue worker command is supervised:

```bash
php artisan queue:work redis --queue=default --tries=3 --timeout=90
```

- [ ] `php artisan queue:restart` is run after deploys.
- [ ] Scheduler command is triggered once per minute if scheduled tasks are used:

```bash
php artisan schedule:run
```

- [ ] No project-specific scheduled tasks are currently defined.

## Docker

- [ ] `docker compose up -d --build` starts app, PostgreSQL, and Redis locally.
- [ ] The app container runs as a non-root user.
- [ ] Host ports are configured with `APP_PORT`, `POSTGRES_PORT`, and
  `HOST_REDIS_PORT` when defaults conflict.
- [ ] Persistent PostgreSQL and Redis volumes are understood before reuse.
- [ ] Secrets are injected by the deployment environment, not baked into images.
- [ ] Published images include SBOM and provenance attestations.

## Filesystem and Logs

- [ ] PHP can write to `storage` and `bootstrap/cache`.
- [ ] Logs are collected from `storage/logs` or container output.
- [ ] Generated logs, caches, and uploads are not committed.
- [ ] Local media storage strategy is acceptable for the target environment.

## Verification

- [ ] Formatter has passed:

```bash
./vendor/bin/pint
```

- [ ] Tests have passed:

```bash
composer test
```

- [ ] Health endpoint has been checked:

```bash
curl http://localhost:8001/health
```

- [ ] Deployment notes in `docs/deployment.md` still match the Dockerfile,
  compose file, Composer scripts, and environment example.
