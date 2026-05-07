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

## API Endpoints

Taxonomy endpoints return JSON API Resource collections ordered by `name`.

| Method | Endpoint          | Description              |
| ------ | ----------------- | ------------------------ |
| GET    | `/api/categories` | List exercise categories |
| GET    | `/api/muscles`    | List muscles             |
| GET    | `/api/equipment`  | List equipment           |

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

## Project Structure

- Services: business logic
- Repositories: data access layer
- DTOs: data transfer objects
- Console Commands: dataset import and maintenance tasks
- API Resources: consistent JSON responses


## Author

Juri Breslauer
