# Deployment Notes

These notes describe how to run Fitness Exercise Manager in a production-like
environment. They are intentionally platform-neutral and do not assume a
specific cloud provider.

## Health Check

Use the lightweight health endpoint for load balancers and uptime checks:

```bash
curl http://localhost:8001/health
```

Expected response:

```json
{
  "status": "ok",
  "app": "Fitness Exercise Manager"
}
```

The endpoint does not perform database, Redis, queue, filesystem, or external
service checks. It is only a cheap application liveness check.

## Required Environment Variables

Set these values explicitly for production:

| Variable | Notes |
| --- | --- |
| `APP_NAME` | Defaults to `Fitness Exercise Manager`. |
| `APP_ENV` | Use `production`. |
| `APP_KEY` | Generate once with `php artisan key:generate --show` and store securely. |
| `APP_DEBUG` | Use `false` in production. |
| `APP_URL` | Public base URL of the deployed application. |
| `LOG_CHANNEL` | `stack` is fine for container logs when the stack writes to stdout/stderr or mounted storage. |
| `LOG_LEVEL` | Use `info` or stricter unless debugging an incident. |
| `DB_CONNECTION` | `pgsql` for the default Docker setup. |
| `DB_HOST` | Database hostname, `postgres` in Docker compose. |
| `DB_PORT` | Database port, `5432` in Docker compose. |
| `DB_DATABASE` | Application database name. |
| `DB_USERNAME` | Application database user. |
| `DB_PASSWORD` | Application database password. |
| `CACHE_STORE` | Use `redis` for production-like Docker deployments. |
| `QUEUE_CONNECTION` | Use `redis` when workers are enabled. |
| `SESSION_DRIVER` | Use `redis` for production-like Docker deployments. |
| `REDIS_CLIENT` | `phpredis` matches the Docker image extension. |
| `REDIS_HOST` | Redis hostname, `redis` in Docker compose. |
| `REDIS_PORT` | Redis port, `6379` in Docker compose. |
| `FILESYSTEM_DISK` | `local` unless media storage is intentionally moved elsewhere. |
| `MAIL_MAILER` | `log` is acceptable for local/dev only; configure a real mailer if email is used. |

Host-only Docker variables:

| Variable | Notes |
| --- | --- |
| `APP_PORT` | Host port mapped to the app container, default `8001`. |
| `POSTGRES_PORT` | Host port mapped to PostgreSQL, default `54320`. |
| `HOST_REDIS_PORT` | Host port mapped to Redis, default `63790`. |

## First Run

For the Docker Compose path used by this repository, create the environment file
and start the stack:

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

For a non-Docker production-like install, install dependencies and create the
environment file:

```bash
cp .env.example .env
composer install --no-dev --optimize-autoloader
npm install --ignore-scripts
npm run build
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate --force
```

Seed only when the target environment should receive the bundled seed data:

```bash
php artisan db:seed --force
```

## Cache Optimization

After environment variables are final, rebuild Laravel caches:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Re-run these commands after changing environment variables, routes, config, or
views. Use `php artisan optimize:clear` before investigating stale config or
route behavior.

## Queue Worker

The default Docker environment uses Redis for queues. Run at least one queue
worker when queued jobs are introduced or enabled:

```bash
php artisan queue:work redis --queue=default --tries=3 --timeout=90
```

In production, run the worker under a process supervisor so it restarts after
deploys, memory exits, or failures. Restart workers after every deploy:

```bash
php artisan queue:restart
```

The current application does not require a dedicated worker for the public read
API, but the command should be part of production readiness when background jobs
are used.

## Scheduler

Laravel scheduled tasks should be triggered once per minute by the host,
container orchestrator, or process manager:

```bash
php artisan schedule:run
```

No project-specific scheduled tasks are currently defined in `routes/console.php`.
Keep the scheduler command documented so future scheduled maintenance can be
enabled without changing deployment runbooks.

## Docker Compose

Build and start the local production-like stack:

```bash
docker compose up -d --build
```

Run setup commands inside the app container:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

Check health:

```bash
curl http://localhost:8001/health
```

Stop the stack:

```bash
docker compose down
```

The provided compose file is suitable for local and production-like validation.
Review secrets, persistent volumes, ports, backup strategy, and process
supervision before using it as a real production runtime.

The application image installs build-only packages in a temporary virtual
package, removes them before the final layer, and runs Laravel as the non-root
`www-data` user.

Published Docker images are built by `.github/workflows/docker-publish.yml` with
SBOM and provenance attestations enabled for Docker Scout and supply-chain
review.

## Storage, Logs, and Permissions

Laravel must be able to write to:

- `storage`
- `storage/logs`
- `storage/framework/cache`
- `storage/framework/sessions`
- `storage/framework/views`
- `bootstrap/cache`

Typical permission repair command:

```bash
chmod -R ug+rw storage bootstrap/cache
```

When running in containers, ensure the PHP process user owns or can write to
mounted volumes. Do not commit generated logs, cached views, or local uploads.

## Deployment Flow

1. Build the release image or install dependencies with production flags.
2. Provide production environment variables and a valid `APP_KEY`.
3. Run `php artisan migrate --force`.
4. Build frontend assets with `npm run build`.
5. Rebuild Laravel caches.
6. Restart PHP, queue workers, and scheduler processes.
7. Verify `GET /health`.
8. Verify critical public API endpoints under `/api/v1`.
