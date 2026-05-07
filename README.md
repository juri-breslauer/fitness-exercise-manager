# Fitness Exercise Manager

[![CI](https://github.com/juri-breslauer/fitness-exercise-manager/actions/workflows/ci.yml/badge.svg)](https://github.com/juri-breslauer/fitness-exercise-manager/actions/workflows/ci.yml)
[![Docker Publish](https://github.com/juri-breslauer/fitness-exercise-manager/actions/workflows/docker-publish.yml/badge.svg)](https://github.com/juri-breslauer/fitness-exercise-manager/actions/workflows/docker-publish.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777bb4.svg)](composer.json)

Fitness Exercise Manager is a Laravel application for managing a structured
fitness exercise catalog with taxonomy data, media records, a Filament admin
panel, and a public read-only API.

The project is portfolio-ready backend work: it demonstrates a practical
Laravel/Filament architecture, policy-protected admin workflows, Docker-based
runtime setup, dataset import through the admin panel, and documented API
contracts.

Current release baseline: `0.3.1`.

## Current Capabilities

- Filament admin panel at `/admin` with session login and admin-only access.
- Catalog management for exercises, categories, muscles, equipment, and exercise
  media.
- Admin profile management at `/admin/profile`.
- JSON exercise dataset import from the Filament admin page
  `/admin/import-exercises`, including dry-run preview and import summary.
- Public read-only API under `/api/v1` for categories, muscles, equipment, and
  published exercises.
- Exercise filtering, pagination, sorting, and JSON Resource responses.
- Lightweight application health endpoint at `/health`.
- Docker Compose stack for local app, PostgreSQL, and Redis.
- CI for Composer validation, PHPUnit, Pint, Composer PSR checks, PHP syntax
  checks, and optional static analysis when PHPStan or Psalm is installed.
- Docker image publish workflow with SBOM and provenance attestations.

## Tech Stack

- PHP `^8.4`
- Laravel `^13.7`
- Filament `^5.6`
- PostgreSQL 17 Alpine in Docker Compose
- Redis 7 Alpine in Docker Compose
- Vite 8 and Tailwind CSS 4 for frontend asset building
- PHPUnit 12 and Laravel Pint for quality checks
- Docker image based on `php:8.4-fpm-alpine`

## Quick Start

```bash
git clone git@github.com:juri-breslauer/fitness-exercise-manager.git
cd fitness-exercise-manager

cp .env.example .env

docker compose up -d --build

docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

The Docker Compose app is available at `http://localhost:8001` by default.
PostgreSQL is exposed on host port `54320`, and Redis is exposed on host port
`63790`.

Default seeded admin credentials:

```text
Email: admin@example.com
Password: password
```

These defaults are for local development only. Override `ADMIN_EMAIL`,
`ADMIN_NAME`, and `ADMIN_PASSWORD` before seeding any shared environment.

## Admin Access

The Filament admin panel is available at:

```text
http://localhost:8001/admin
```

Admin access requires an authenticated user with `users.is_admin = true`.
Guests are redirected to `/admin/login`, and non-admin users are forbidden from
admin pages and catalog management actions.

See [docs/admin.md](docs/admin.md) for the complete admin access and import
workflow notes.

## API Overview

The public catalog API is read-only and versioned under `/api/v1`.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/health` | Lightweight application health check |
| GET | `/api/v1/categories` | List exercise categories |
| GET | `/api/v1/muscles` | List muscles |
| GET | `/api/v1/equipment` | List equipment |
| GET | `/api/v1/exercises` | List published exercises with filters, pagination, and sorting |
| GET | `/api/v1/exercises/{slug}` | Show a published exercise by slug |

Example requests:

```bash
curl http://localhost:8001/health
curl http://localhost:8001/api/v1/categories
curl "http://localhost:8001/api/v1/exercises?category=strength&sort=name"
curl http://localhost:8001/api/v1/exercises/push-up
```

`GET /api/v1/exercises` supports filtering by `search`, `category`, `muscle`,
`equipment`, `difficulty`, `force`, `mechanic`, and `status=published`;
pagination with `page` and `per_page`; and sorting with `sort=name`,
`sort=-name`, `sort=created_at`, `sort=-created_at`, `sort=difficulty`, and
`sort=-difficulty`.

Full request and response documentation is available in [docs/api.md](docs/api.md).

## Dataset Import

Exercise imports are handled from the Filament admin panel, not from an Artisan
command.

Open `/admin/import-exercises` as an admin user and use:

- `Dry Run` to validate the uploaded JSON and preview created or updated
  records.
- `Import JSON` to persist valid rows.

The import accepts a JSON array of exercise objects. The `slug` field is the
idempotency key. Categories, muscles, and equipment are upserted by normalized
slug before exercises and relationships are saved.

Minimal example:

```json
[
  {
    "slug": "push-up",
    "name": "Push Up",
    "display_name": "Push-Up",
    "aliases": ["press up"],
    "description": "A bodyweight upper-body pushing exercise.",
    "instructions": [
      "Start in a high plank position.",
      "Lower your chest toward the floor.",
      "Press back to the starting position."
    ],
    "tips": ["Keep your body in a straight line."],
    "difficulty": "beginner",
    "force": "push",
    "mechanic": "compound",
    "category": "strength",
    "primary_muscles": ["chest", "triceps"],
    "secondary_muscles": ["shoulders"],
    "equipment": ["body only"],
    "status": "published"
  }
]
```

Media import is intentionally out of scope until a separate media import
contract exists. Large production datasets should not be committed to this
repository; keep only small fixtures for tests.

## Quality Checks

Commands used by CI:

```bash
composer validate --strict
composer install --no-interaction --no-progress --prefer-dist
composer test
vendor/bin/pint --test
composer dump-autoload --strict-psr
find app bootstrap config database routes tests -name '*.php' -print0 | xargs -0 -n 1 php -l
```

Local formatting:

```bash
vendor/bin/pint
```

Fresh install verification should include Docker build/start, migrations and
seeders, `/health`, representative `/api/v1` requests, `/admin` login, and the
dataset import fixture at [tests/Fixtures/exercises.json](tests/Fixtures/exercises.json).

## Documentation

- [Public API v1](docs/api.md)
- [Admin access and import workflow](docs/admin.md)
- [Deployment notes](docs/deployment.md)
- [Production checklist](docs/production-checklist.md)
- [Release checklist](docs/release-checklist.md)
- [Changelog](CHANGELOG.md)

## Roadmap

- Expand catalog content with curated, source-controlled fixture coverage.
- Define a supported media import contract for images, GIFs, and videos.
- Add richer API examples for common client integrations.
- Add optional static analysis tooling once the codebase is ready to enforce it
  in CI.
- Introduce release-specific verification notes as production workflows mature.
- Keep Docker and deployment docs aligned with any future runtime changes.

## License

This project is open-sourced under the [MIT license](LICENSE).

Copyright (c) 2026 Juri Breslauer.

## Author

Juri Breslauer
