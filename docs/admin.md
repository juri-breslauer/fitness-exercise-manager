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

The catalog policies are registered in `App\Providers\AuthServiceProvider`:

- `CategoryPolicy`
- `MusclePolicy`
- `EquipmentPolicy`
- `ExercisePolicy`
- `ExerciseMediaPolicy`

The public `/api/v1` catalog endpoints remain unauthenticated, read-only GET
endpoints. Do not add API authentication or write endpoints for the public v1
API unless the API contract is intentionally changed.
