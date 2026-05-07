# Release Checklist

Use this checklist before publishing a GitHub release or Docker image. Keep the
items unchecked in the repository and check them only during a real release
verification pass.

## Version and Notes

- [ ] Confirm the release version and tag name.
- [ ] Confirm `CHANGELOG.md` has an updated release section.
- [ ] Confirm the README release baseline does not contradict the release tag.
- [ ] Prepare GitHub release notes from the changelog.
- [ ] Confirm no unrelated product, schema, API, or admin behavior changes are
  included in a documentation or polish release.

Suggested GitHub release notes template:

```markdown
## Summary

Short release summary.

## Added

- 

## Changed

- 

## Documentation

- 

## Infrastructure

- 

## Verification

- Fresh install verified
- Migrations and seeders passed
- Test suite passed
- Pint passed
- `/health` checked
- `/api/v1` endpoints checked
- `/admin` login checked
- Dataset import fixture checked
- Docker build/run docs checked
```

## Fresh Install

- [ ] Start from a clean checkout.
- [ ] Copy the environment file:

```bash
cp .env.example .env
```

- [ ] Build and start the Docker stack:

```bash
docker compose up -d --build
```

- [ ] Generate the application key:

```bash
docker compose exec app php artisan key:generate
```

- [ ] Run migrations and seeders:

```bash
docker compose exec app php artisan migrate --seed
```

## Quality Gates

- [ ] Validate Composer metadata:

```bash
composer validate --strict
```

- [ ] Run the test suite:

```bash
composer test
```

- [ ] Run Pint in check mode:

```bash
vendor/bin/pint --test
```

- [ ] Run Composer PSR checks:

```bash
composer dump-autoload --strict-psr
```

- [ ] Run PHP syntax checks:

```bash
find app bootstrap config database routes tests -name '*.php' -print0 | xargs -0 -n 1 php -l
```

## Runtime Verification

- [ ] Check the health endpoint:

```bash
curl http://localhost:8001/health
```

- [ ] Check public `/api/v1` endpoints:

```bash
curl http://localhost:8001/api/v1/categories
curl http://localhost:8001/api/v1/muscles
curl http://localhost:8001/api/v1/equipment
curl "http://localhost:8001/api/v1/exercises?sort=name"
```

- [ ] Check `/admin/login` loads.
- [ ] Log in to `/admin` with a seeded admin account or release-test admin.
- [ ] Confirm catalog resource pages load for admin users.
- [ ] Confirm non-admin users cannot access admin pages when tested.

## Dataset Import

- [ ] Open `/admin/import-exercises`.
- [ ] Upload `tests/Fixtures/exercises.json`.
- [ ] Run `Dry Run` and confirm the preview summary is reasonable.
- [ ] Run `Import JSON` in a disposable environment and confirm records are
  created or updated.
- [ ] Confirm media import is still documented as out of scope.

## Docker and Deployment Docs

- [ ] Verify Docker Compose starts app, PostgreSQL, and Redis.
- [ ] Verify `APP_PORT`, `POSTGRES_PORT`, and `HOST_REDIS_PORT` documentation
  matches `docker-compose.yml`.
- [ ] Verify deployment notes still match `Dockerfile`, `docker-compose.yml`,
  Composer scripts, and `.env.example`.
- [ ] Verify `docs/production-checklist.md` still matches release operations.

## Documentation Links

- [ ] Verify README links point to existing files.
- [ ] Verify docs examples still use `/api/v1`.
- [ ] Verify admin docs consistently use `/admin`.
- [ ] Verify deployment and production docs consistently use `/health`.
