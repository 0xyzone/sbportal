# Copilot Instructions for SB Portal

## Project Overview
SB Portal is a Laravel 12 + Filament 4 admin panel application for managing schools, individuals, and participants. It uses **role-based access control** with **Spatie Permission** and supports **multiple Filament panels** via panel switching.

**Tech Stack:**
- Backend: Laravel 12, PHP 8.2+, Livewire 3
- Admin UI: Filament 4 (with Shield, Image Gallery, Panel Switch)
- Frontend: Vite, Tailwind CSS 4
- Database: MySQL/PostgreSQL (migrations-based)
- Testing: PHPUnit 11
- Formatting: Laravel Pint

## Architecture Patterns

### Multi-Panel Architecture
The application uses **three independent Filament panels** (Mukhiya, School, Individual) accessible via role-based panel switching configured in [AppServiceProvider.php](app/Providers/AppServiceProvider.php#L24):
- Panel visibility controlled by `super_admin` role check
- Each panel likely has dedicated resources in `app/Filament/{Individual,School}` directories
- Resources auto-discovered by Filament; register in respective `PanelProvider` classes

### Data Model Structure
**Core Models** ([app/Models/](app/Models/)):
- `User` - Core auth model with `HasRoles` trait (Spatie Permission), implements `FilamentUser` & `HasAvatar`
  - Relations: `profiles()`, `school_profiles()`, `participants()` (all HasMany)
- `Profile` - User profile records (BelongsTo User)
- `SchoolProfile` - School-specific profiles (BelongsTo User)
- `Participant` - Participants linked to users, includes computed `participantId` attribute (ID + 1000)

**Permission Model:**
- Uses Spatie Permission package; configured in [config/permission.php](config/permission.php)
- Super admin automatically has all permissions
- Roles & permissions managed via Filament Shield resource at `/admin/shield/roles`

### Authorization & Policies
Access control implemented via:
1. **Role-based Gates** (Spatie Permission + Filament Shield)
2. **Model Policies** in [app/Policies/](app/Policies/) - `ParticipantPolicy`, `RolePolicy`, `UserPolicy`
3. **Filament Resource Protection** - Shield auto-generates permissions per resource action

## Development Workflows

### Setup
```bash
composer run setup  # Installs dependencies, runs migrations, builds frontend
```

### Local Development
```bash
composer run dev  # Runs concurrently: Laravel server, queue listener, Pail logs, Vite dev server
npm run dev       # Vite dev mode (already included in composer run dev)
```

### Testing & Quality
```bash
composer test     # Clears config cache, runs PHPUnit tests
./vendor/bin/pint  # Auto-fix code style (Laravel standard)
```

### Database
- Migrations auto-load from `database/migrations/` with timestamp naming
- Seeders in `database/seeders/`; use `DatabaseSeeder` for test data
- Access via `php artisan migrate`, `php artisan seed`

## Key Conventions & Patterns

### Filament Resources
- Resources extend `Filament\Resources\Resource`
- Auto-registered by Filament; no manual route registration needed
- Shield auto-generates permissions: `view_{resource}`, `create_{resource}`, `edit_{resource}`, `delete_{resource}`
- Customize panel assignment via `getPages()` or `register()` in `PanelProvider`

### Livewire Components
- Located in [app/Livewire/](app/Livewire/)
- Widget base class: `Filament\Widgets\Widget`
- Event system: Use `#[On('eventName')]` attribute for listeners (e.g., `ProfileCompleteness.php`)
- Dispatch events via `dispatch('eventName')` and re-render views on listener invocation

### Frontend Build
- Entry points: `resources/css/app.css`, `resources/js/app.js` (+ panel-specific theme CSS)
- Panel themes in `resources/css/filament/{mukhiya,individual,school}/theme.css`
- Tailwind CSS 4 integrated via `@tailwindcss/vite`
- Vite ignores `storage/framework/views/` during watch

### Database Relationships
- Use typed relation methods with return type hints (e.g., `public function profiles(): HasMany`)
- Cast attributes where needed (e.g., `Participant->participantId` computed attribute)
- Mass assignment: Check `$fillable` arrays; `Model::unguard()` enabled in AppServiceProvider

## Critical Files & Integration Points

| File | Purpose |
|------|---------|
| [config/permission.php](config/permission.php) | Spatie Permission configuration (roles/permissions tables) |
| [config/filament-shield.php](config/filament-shield.php) | Shield setup (super admin, permission builder, tenant support) |
| [routes/web.php](routes/web.php) | Public routes; Filament routes auto-registered |
| [vite.config.js](vite.config.js) | Vite build entry points & plugins |
| [phpunit.xml](phpunit.xml) | Test runner configuration |

## Common Tasks

**Add a New Filament Resource:**
1. Generate class: `php artisan make:filament-resource {ModelName}`
2. Define form & table schemas in resource
3. Shield auto-registers permissions; assign to roles in `/admin/shield/roles`
4. Optionally create custom `PanelProvider` method for panel-specific registration

**Add Role/Permission:**
1. Create via Filament Shield UI (`/admin/shield/roles`) or seeder
2. Assign to users: `$user->assignRole('role_name')`
3. Check in controllers/policies: `auth()->user()->hasRole('role_name')`

**Modify Panel Configuration:**
Edit [AppServiceProvider.php](app/Providers/AppServiceProvider.php#L24); panels auto-sync via `PanelSwitch::configureUsing()`

**Run Queue/Jobs:**
```bash
php artisan queue:listen  # Included in composer run dev
php artisan queue:work    # Production
```

## Testing Notes
- Test classes in [tests/Feature/](tests/Feature/) and [tests/Unit/](tests/Unit/)
- Base class: `Tests\TestCase`
- Run with `composer test`; uses PHPUnit configuration from `phpunit.xml`
