# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A Laravel 12 RESTful API ("Medical AI Assistant") that lets authenticated users run timed medical chat sessions with an AI model, plus a small Inertia/React front end scaffold. Roles (Admin/Doctor/Patient) and a doctor/appointment domain are being layered on via spatie/laravel-permission (see "Current branch" below).

## Commands

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed          # creates Admin/Doctor/Patient roles + an admin and a test user
php artisan serve

npm install
npm run dev                  # vite dev server
npm run build

composer test                 # clears config cache, then `php artisan test`
php artisan test --filter=TestName   # single test
php artisan test tests/Feature/ExampleTest.php  # single file

vendor/bin/pint                # code style (Laravel Pint)
```

`composer dev` runs the server, queue listener, `pail` log tailer, and vite concurrently — the closest thing to a full local dev environment.

Tests run against an in-memory SQLite DB (`phpunit.xml` sets `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), independent of whatever `.env` configures.

## Architecture

**Controller → Service → Model.** Controllers (`app/Http/Controllers`) stay thin: validate via a `FormRequest`, delegate to a `Service` class (`app/Services`), return JSON. Business logic (password hashing, role assignment, profile lookups, chat orchestration) lives in the services, not the controllers.

**Auth & identity**
- `User` (`app/Models/User.php`) uses a UUID primary key (`incrementing = false`, generated in a `creating` boot hook — this pattern is repeated on `MedicalSession` and `Message`), Sanctum (`HasApiTokens`) for API tokens, and Spatie `HasRoles` for Admin/Doctor/Patient roles.
- Registration (`UserService::register`) creates a `User`, assigns the `Patient` role, and creates a linked `Profile` (medical file: blood type, height/weight, chronic diseases, allergies, medication — stored as JSON-cast arrays) in one call.
- Login issues a Sanctum token; there's no session-based web login wired to a real controller yet (see routing note below).

**Medical chat flow** (`routes/api.php` → `ChatController::chat`)
1. `medical.session` middleware (`App\Http\Middleware\SessionMiddleware`, aliased in `bootstrap/app.php`) runs first on every chat request: it finds or creates the user's active `MedicalSession`, and auto-closes any session older than 30 minutes, opening a fresh one if needed. This is the *session lifecycle*, separate from Laravel's HTTP session.
2. `ChatController` loads the active session's message history and calls `ChatService::chat()`, which posts to the Ollama Cloud chat API (`https://ollama.com/api/chat`, model `gemma3:27b-cloud`, bearer token from `config('services.ollama.key')` / `OLLAMA_API_KEY` env var) and persists both the user's message and the AI's reply as `Message` rows tied to the `MedicalSession`.
3. `TextCleanService::clean()` post-processes the raw AI response (normalizes newlines/markdown bullets into a single readable paragraph) before it's returned to the client.

Ollama Cloud may require a VPN in some countries (see README).

**Roles/permissions**: `spatie/laravel-permission` migration + `RoleSeeder` establish `Admin`, `Doctor`, `Patient` roles. Route-level `role` / `permission` / `role_or_permission` middleware aliases are registered in `bootstrap/app.php` but not yet applied to any routes.

**Routing split**: `routes/api.php` is the real, working API (register/login/logout, profile show/update, chat send) — all Sanctum-protected except register/login. `routes/web.php` renders Inertia/React pages and currently references `App\Http\Controllers\AuthController`, which does not exist in `app/Http/Controllers` — the web/Inertia auth flow is unfinished; treat `routes/api.php` + `UserController` as the source of truth for auth logic.

**Enums**: `App\Enums\GenderEnum` and `BloodTypeEnum` are backed enums used in form request validation via `Rule::enum(...)`.

## In-progress work (current branch: feature/spatie-user-roles)

Two new migrations (`doctors_profiles`, `appointments`) are being added to extend the schema for doctor profiles and appointment booking, alongside the Spatie roles work. Check migration state before assuming the schema in `database/migrations` is complete/runnable — new files may be mid-edit.
