# Admin Access Rules

The Filament admin panel is available at `/admin` and uses Laravel session
authentication.

Admin access is intentionally simple:

- Guests are redirected to `/admin/login`.
- Authenticated users must have `users.is_admin = true` to access the admin
  panel.
- `App\Models\User::canAccessPanel()` grants access only to the Filament panel
  with id `admin` and only when `User::isAdmin()` returns true.
- Catalog model policies allow only admin users to view and manage categories,
  muscles, equipment, exercises, and exercise media in Filament.
- Non-admin users receive a forbidden response for admin panel pages and catalog
  management actions.
- Admin users can edit their own profile settings at `/admin/profile`,
  including name, email, and password.
- Admin users can import exercise datasets at `/admin/import-exercises`.

The catalog policies are registered in `App\Providers\AuthServiceProvider`:

- `CategoryPolicy`
- `MusclePolicy`
- `EquipmentPolicy`
- `ExercisePolicy`
- `ExerciseMediaPolicy`

The public `/api/v1` catalog endpoints remain unauthenticated, read-only GET
endpoints. Do not add API authentication or write endpoints for the public v1
API unless the API contract is intentionally changed.

## Dataset Import Workflow

Exercise dataset import is a Filament admin workflow, not an Artisan command.

The import page is available to admin users at `/admin/import-exercises` and
provides two actions:

- `Dry Run` validates the uploaded JSON dataset and previews created or updated
  exercises, categories, muscles, and equipment without persisting changes.
- `Import JSON` validates the uploaded JSON dataset and persists valid rows.

The import service expects a JSON array of exercise objects. It uses `slug` as
the exercise idempotency key, upserts taxonomy records by normalized slug, syncs
primary and secondary muscle relationships, and syncs equipment relationships.

Use `tests/Fixtures/exercises.json` as the small local fixture for release and
fresh install verification. Large production datasets should stay outside the
repository.

Media import is not part of the current dataset import contract.
