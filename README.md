# Fitness Exercise Manager

Fitness Exercise Manager is a Laravel-powered application for managing a structured fitness exercise catalog with media support, an admin panel, and an API-first architecture.

The project is intended as a portfolio-grade backend system that demonstrates clean application structure, dataset import workflows, media handling, and REST API development in a real-world Laravel environment.

## Features

- Exercise catalog with categories and detailed exercise data
- Media support for images and GIF demonstrations
- Admin panel for managing exercises, categories, and related content
- REST API designed as the primary integration layer
- JSON dataset import via the Filament admin panel
- Service and Repository based application structure

## Tech Stack

- PHP 8.4
- Laravel
- PostgreSQL
- Redis
- Docker

## Installation

```bash
git clone git@github.com:juri-breslauer/fitness-exercise-manager.git
cd fitness-exercise-manager

cp .env.example .env

docker compose up -d --build

docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

The application is available at `http://localhost:8001` by default. PostgreSQL is exposed on host port `54320` and Redis on host port `63790`.

## Exercise Dataset Import

Exercise imports use a small project-owned JSON contract. Uploaded files must
match the canonical format below.

Large production datasets should not be committed to this repository. Keep only
small fixtures for tests.

The v1 import format is single-locale. Translated exercise content is out of
scope until the application has a supported translation storage model.

### Fitness Exercise Import JSON v1

The import file must be a JSON array of exercise objects:

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
    "tips": [
      "Keep your body in a straight line."
    ],
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

Import behavior:

- `slug` is the idempotency key for exercises.
- `category`, `primary_muscles`, `secondary_muscles`, and `equipment` contain
  taxonomy values that are imported with the exercise.
- Taxonomy values are normalized into slugs. For example, `body only` becomes
  `body-only`.
- Taxonomy names are generated from the normalized values when new records are
  created. For example, `body only` becomes `Body Only`.
- Categories, muscles, and equipment are upserted by slug before the exercise is
  saved.
- Exercises are upserted by slug.
- `primary_muscles` are synced with `role = primary`.
- `secondary_muscles` are synced with `role = secondary`.
- `equipment` is always an array; use an empty array when no equipment is required.
- Media import is intentionally out of scope until it has its own supported
  contract.

Taxonomy example:

```json
{
  "category": "strength",
  "primary_muscles": ["chest", "triceps"],
  "secondary_muscles": ["shoulders"],
  "equipment": ["body only"]
}
```

This creates or reuses `Strength` in categories, `Chest`, `Triceps`, and
`Shoulders` in muscles, and `Body Only` in equipment. The exercise is then linked
to those records through `exercise_muscle` and `exercise_equipment`.

Admins import exercise datasets from the Filament admin panel at `/admin`.

## Admin Access

Filament admin access is restricted to authenticated users with
`users.is_admin = true`. Catalog management actions are protected by model
policies for categories, muscles, equipment, exercises, and exercise media.

See [`docs/admin.md`](docs/admin.md) for the complete admin access rules.

## API Endpoints

The public exercise catalog API is versioned under `/api/v1`. Full request and
response documentation is available in [`docs/api.md`](docs/api.md).

Taxonomy endpoints return JSON API Resource collections ordered by `name`.
Exercise endpoints return only published exercises.

| Method | Endpoint                         | Description                                      |
| ------ | -------------------------------- | ------------------------------------------------ |
| GET    | `/api/v1/categories`             | List exercise categories                         |
| GET    | `/api/v1/muscles`                | List muscles                                     |
| GET    | `/api/v1/equipment`              | List equipment                                   |
| GET    | `/api/v1/exercises`              | List published exercises with filters and pages  |
| GET    | `/api/v1/exercises/{slug}`       | Show a published exercise by slug                |

`GET /api/v1/exercises` supports filtering by `search`, `category`, `muscle`,
`equipment`, `difficulty`, `force`, and `mechanic`; pagination with `page` and
`per_page`; and sorting with `sort=name`, `sort=-name`, `sort=created_at`,
`sort=-created_at`, `sort=difficulty`, and `sort=-difficulty`.

Example response:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Strength",
      "slug": "strength"
    }
  ]
}
```

Example exercise queries:

```bash
curl "http://localhost:8001/api/v1/exercises?search=curl"
curl "http://localhost:8001/api/v1/exercises?category=strength"
curl "http://localhost:8001/api/v1/exercises?page=2&per_page=20"
curl "http://localhost:8001/api/v1/exercises?sort=-created_at"
```

## Project Structure

- Services: business logic
- Repositories: data access layer
- DTOs: data transfer objects
- Console Commands: dataset import and maintenance tasks
- API Resources: consistent JSON responses


## Author

Juri Breslauer
