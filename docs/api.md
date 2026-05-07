# Public API v1

Fitness Exercise Manager exposes a public JSON API for reading the exercise
catalog. The public API is versioned under `/api/v1`.

The API is read-only. It does not require authentication for the documented
endpoints.

## Base URL

Local development:

```text
http://localhost:8001/api/v1
```

All endpoint paths in this document are relative to `/api/v1`.

## Response Format

All resources are returned through Laravel JSON resources with a top-level
`data` key.

Collection responses use:

```json
{
  "data": []
}
```

Paginated collection responses use:

```json
{
  "data": [],
  "links": {
    "first": "http://localhost:8001/api/v1/exercises?page=1",
    "last": "http://localhost:8001/api/v1/exercises?page=3",
    "prev": null,
    "next": "http://localhost:8001/api/v1/exercises?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "path": "http://localhost:8001/api/v1/exercises",
    "per_page": 20,
    "to": 20,
    "total": 50
  }
}
```

Single resource responses use:

```json
{
  "data": {}
}
```

## Public Endpoints

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/categories` | List exercise categories ordered by name. |
| GET | `/muscles` | List muscles ordered by name. |
| GET | `/equipment` | List equipment ordered by name. |
| GET | `/exercises` | List published exercises with filters, pagination, and sorting. |
| GET | `/exercises/{exercise:slug}` | Show a published exercise by slug. |

Taxonomy detail endpoints such as `/categories/{category:slug}`,
`/muscles/{muscle:slug}`, and `/equipment/{equipment:slug}` are not part of the
current public route set.

## Taxonomy Resources

### Category

```json
{
  "id": 1,
  "name": "Strength",
  "slug": "strength"
}
```

### Muscle

Muscles returned inside exercise relationships include `role`.

```json
{
  "id": 5,
  "name": "Biceps",
  "slug": "biceps",
  "role": "primary"
}
```

Top-level muscle collection items omit `role`.

### Equipment

Equipment returned inside exercise relationships includes `is_optional`.

```json
{
  "id": 3,
  "name": "Dumbbell",
  "slug": "dumbbell",
  "is_optional": false
}
```

Top-level equipment collection items omit `is_optional`.

## Exercise Resource

Exercise responses include the exercise attributes and loaded relationships.

| Field | Type | Notes |
| --- | --- | --- |
| `id` | integer | Exercise identifier. |
| `category_id` | integer | Related category identifier. |
| `slug` | string | Public exercise slug. |
| `name` | string | Canonical exercise name. |
| `display_name` | string or null | Optional display name. |
| `aliases` | array or null | Optional alternate names. |
| `description` | string or null | Optional description. |
| `instructions` | array or null | Optional ordered instruction strings. |
| `tips` | array or null | Optional training tips. |
| `difficulty` | string or null | `beginner`, `intermediate`, or `expert`. |
| `force` | string or null | `push`, `pull`, or `static`. |
| `mechanic` | string or null | `compound` or `isolation`. |
| `status` | string | Public responses only include `published` exercises. |
| `category` | object | Category resource. |
| `primary_muscles` | array | Muscle resources with `role = primary`. |
| `secondary_muscles` | array | Muscle resources with `role = secondary`. |
| `equipment` | array | Equipment resources with `is_optional`. |
| `media` | array | Exercise media resources ordered by position. |
| `primary_media` | object or null | Primary media resource when present. |

### Exercise Media Resource

```json
{
  "id": 20,
  "type": "image",
  "url": "https://example.com/dumbbell-curl.jpg",
  "disk": null,
  "path": null,
  "source": "example",
  "position": 1,
  "is_primary": true,
  "metadata": {
    "alt": "Dumbbell curl start position"
  }
}
```

`type` is one of `image`, `gif`, or `video`.

## Endpoints

### GET /categories

Returns all exercise categories ordered by `name`.

Example request:

```bash
curl http://localhost:8001/api/v1/categories
```

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

### GET /muscles

Returns all muscles ordered by `name`.

Example request:

```bash
curl http://localhost:8001/api/v1/muscles
```

Example response:

```json
{
  "data": [
    {
      "id": 5,
      "name": "Biceps",
      "slug": "biceps"
    }
  ]
}
```

### GET /equipment

Returns all equipment ordered by `name`.

Example request:

```bash
curl http://localhost:8001/api/v1/equipment
```

Example response:

```json
{
  "data": [
    {
      "id": 3,
      "name": "Dumbbell",
      "slug": "dumbbell"
    }
  ]
}
```

### GET /exercises

Returns published exercises with related category, muscles, equipment, media,
and primary media.

Example request:

```bash
curl "http://localhost:8001/api/v1/exercises"
```

Example response:

```json
{
  "data": [
    {
      "id": 10,
      "category_id": 1,
      "slug": "dumbbell-curl",
      "name": "Dumbbell Curl",
      "display_name": "Dumbbell Curl",
      "aliases": ["db curl"],
      "description": "An upper-arm pulling exercise performed with dumbbells.",
      "instructions": [
        "Stand tall with a dumbbell in each hand.",
        "Curl the dumbbells while keeping your elbows close to your sides.",
        "Lower the dumbbells under control."
      ],
      "tips": ["Avoid swinging your torso."],
      "difficulty": "beginner",
      "force": "pull",
      "mechanic": "isolation",
      "status": "published",
      "category": {
        "id": 1,
        "name": "Strength",
        "slug": "strength"
      },
      "primary_muscles": [
        {
          "id": 5,
          "name": "Biceps",
          "slug": "biceps",
          "role": "primary"
        }
      ],
      "secondary_muscles": [],
      "equipment": [
        {
          "id": 3,
          "name": "Dumbbell",
          "slug": "dumbbell",
          "is_optional": false
        }
      ],
      "media": [],
      "primary_media": null
    }
  ],
  "links": {
    "first": "http://localhost:8001/api/v1/exercises?page=1",
    "last": "http://localhost:8001/api/v1/exercises?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://localhost:8001/api/v1/exercises",
    "per_page": 20,
    "to": 1,
    "total": 1
  }
}
```

Full example files:

- `docs/examples/exercise-list-response.json`
- `docs/examples/exercise-detail-response.json`

### GET /exercises/{exercise:slug}

Returns a single published exercise by slug. Draft exercises return `404`.

Example request:

```bash
curl http://localhost:8001/api/v1/exercises/push-up
```

Example response:

```json
{
  "data": {
    "id": 10,
    "category_id": 1,
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
    "status": "published",
    "category": {
      "id": 1,
      "name": "Strength",
      "slug": "strength"
    },
    "primary_muscles": [],
    "secondary_muscles": [],
    "equipment": [],
    "media": [],
    "primary_media": null
  }
}
```

## Exercise Filters

All filters are optional and can be combined. Unknown taxonomy slugs or invalid
enum values return a validation error.

| Query parameter | Description | Allowed values |
| --- | --- | --- |
| `search` | Case-insensitive search against `name` and `display_name`. | String, max 255 characters. |
| `category` | Filter by category slug. | Existing category slug. |
| `muscle` | Filter by primary or secondary muscle slug. | Existing muscle slug. |
| `equipment` | Filter by equipment slug. | Existing equipment slug. |
| `difficulty` | Filter by difficulty. | `beginner`, `intermediate`, `expert`. |
| `force` | Filter by force. | `push`, `pull`, `static`. |
| `mechanic` | Filter by mechanic. | `compound`, `isolation`. |
| `status` | Accepted for compatibility with the public query contract. | `published` only. |

Example requests:

```bash
curl "http://localhost:8001/api/v1/exercises?search=curl"
curl "http://localhost:8001/api/v1/exercises?category=strength"
curl "http://localhost:8001/api/v1/exercises?muscle=biceps"
curl "http://localhost:8001/api/v1/exercises?equipment=dumbbell"
curl "http://localhost:8001/api/v1/exercises?difficulty=beginner"
curl "http://localhost:8001/api/v1/exercises?force=pull"
curl "http://localhost:8001/api/v1/exercises?mechanic=isolation"
```

## Pagination

`GET /exercises` is paginated.

| Query parameter | Default | Limits |
| --- | --- | --- |
| `page` | `1` | Integer, minimum `1`. |
| `per_page` | `20` | Integer, minimum `1`, maximum `100`. |

Example request:

```bash
curl "http://localhost:8001/api/v1/exercises?page=2&per_page=20"
```

Pagination metadata is returned in `links` and `meta`.

## Sorting

`GET /exercises` defaults to `sort=name`.

Allowed sort values:

| Sort value | Description |
| --- | --- |
| `name` | Sort by name ascending. |
| `-name` | Sort by name descending. |
| `created_at` | Sort by creation time ascending. |
| `-created_at` | Sort by creation time descending. |
| `difficulty` | Sort by difficulty ascending. |
| `-difficulty` | Sort by difficulty descending. |

Example requests:

```bash
curl "http://localhost:8001/api/v1/exercises?sort=name"
curl "http://localhost:8001/api/v1/exercises?sort=-created_at"
```

## Validation Errors

Invalid `GET /exercises` query parameters return `422 Unprocessable Content`.

Example request:

```bash
curl "http://localhost:8001/api/v1/exercises?category=unknown&difficulty=advanced&per_page=101&sort=slug&status=draft"
```

Example response:

```json
{
  "message": "The selected category is invalid. (and 4 more errors)",
  "errors": {
    "category": ["The selected category is invalid."],
    "difficulty": ["The selected difficulty is invalid."],
    "per_page": ["The per page field must not be greater than 100."],
    "sort": ["The selected sort is invalid."],
    "status": ["The selected status is invalid."]
  }
}
```

Common validation cases:

| Parameter | Invalid example | Result |
| --- | --- | --- |
| `search` | More than 255 characters. | `422` with `errors.search`. |
| `category` | `unknown`. | `422` with `errors.category`. |
| `muscle` | `unknown`. | `422` with `errors.muscle`. |
| `equipment` | `unknown`. | `422` with `errors.equipment`. |
| `difficulty` | `advanced`. | `422` with `errors.difficulty`. |
| `force` | `mixed`. | `422` with `errors.force`. |
| `mechanic` | `dynamic`. | `422` with `errors.mechanic`. |
| `page` | `0`. | `422` with `errors.page`. |
| `per_page` | `101`. | `422` with `errors.per_page`. |
| `sort` | `slug`. | `422` with `errors.sort`. |
| `status` | `draft`. | `422` with `errors.status`. |
