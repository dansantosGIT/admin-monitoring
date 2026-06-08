# Collaborators Guide

Welcome to the Admin Monitoring System! This document provides essential information for team members working on this project.

## Quick Start

1. **Clone the repository**
   ```bash
   git clone [repo-url]
   cd admin-monitoring
   ```

2. **Run one-time setup**
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   npm run build
   php artisan db:seed ReportSeeder
   ```

3. **Start the application**
   ```bash
   php artisan serve
   ```

   In another terminal:
   ```bash
   npm run dev
   ```

4. **Access the application**
   - Main app: `http://localhost:8000`
   - Dashboard: `http://localhost:8000/dashboard`
   - Reports: `http://localhost:8000/reports`

## Development Workflow

### Creating Features

1. **Create a new branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Create necessary files**
   ```bash
   # Create a model with migration and controller
   php artisan make:model YourModel -mcr

   # Create a request class for validation
   php artisan make:request StoreYourRequest

   # Create a resource for API responses
   php artisan make:resource YourResource
   ```

3. **Write code following Laravel conventions**
   - Models in `app/Models/`
   - Controllers in `app/Http/Controllers/`
   - Migrations in `database/migrations/`
   - Views in `resources/views/`
   - Tests in `tests/Feature/` or `tests/Unit/`

4. **Commit with clear messages**
   ```bash
   git add .
   git commit -m "feat: add new monitoring feature"
   ```

### Testing Your Code

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/YourTest.php

# Run with coverage
php artisan test --coverage
```

### Code Quality

```bash
# Check code style
php artisan pint --check

# Auto-fix code style
php artisan pint
```

## Database Operations

### Creating Migrations

```bash
# Create a new migration
php artisan make:migration create_reports_table

# Create migration with model
php artisan make:model Report -m
```

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Run migrations in specific directory
php artisan migrate --path=database/migrations/2026_06_08/

# Rollback last batch
php artisan migrate:rollback

# Reset database (danger!)
php artisan migrate:reset
```

### Seeding Data

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=ReportSeeder

# Refresh and seed
php artisan migrate:refresh --seed
```

## Debugging

### Using Tinker (Interactive Shell)

```bash
php artisan tinker

# Inside tinker:
>>> User::all()
>>> Report::where('status', 'published')->get()
>>> DB::table('reports')->count()
```

### Checking Logs

```bash
# View logs in real-time
php artisan pail

# Or check the log file directly
tail -f storage/logs/laravel.log
```

### Database Debugging

```bash
# Enable query logging in tinker
>>> DB::enableQueryLog()
>>> Report::all()
>>> DB::getQueryLog()
```

## Git Workflow

### Before Making Changes

```bash
# Update your local repository
git fetch origin
git pull origin main
```

### Before Committing

```bash
# Check what you've changed
git status
git diff

# Stage changes
git add .
git add app/Models/YourModel.php  # Or specific files

# Commit with message
git commit -m "type: description"
```

### Commit Message Format

Use conventional commits:
- `feat: add new feature`
- `fix: fix a bug`
- `docs: update documentation`
- `style: code formatting`
- `refactor: restructure code`
- `test: add tests`
- `chore: maintenance tasks`

### Push and Create Pull Request

```bash
git push origin feature/your-feature-name
```

Then create a Pull Request on GitHub/GitLab for review.

## Common Issues & Solutions

### "Class not found" Error
- Run: `composer dump-autoload`
- Run: `php artisan cache:clear`

### Database Connection Error
- Check `.env` file settings
- Verify database exists and credentials are correct
- For SQLite: `touch database/database.sqlite`

### Permission Denied Error
- Linux/Mac: `chmod -R 755 storage bootstrap/cache`
- Windows: Usually not an issue; check disk permissions

### Assets Not Loading
- Run: `npm run build`
- Check `public/build/manifest.json` exists
- Clear browser cache or use `php artisan view:clear`

### "No such table" Error
- Run: `php artisan migrate`
- Check migrations have been created properly
- Use `php artisan migrate:refresh --seed` to reset

## Monitoring Features

### Health Checks

Access system health information:
```bash
php artisan health:check
```

Or via the API:
```bash
curl http://localhost:8000/health
```

### Creating Reports

Use the ReportController methods:

```php
// Via the HTTP interface
POST /reports
{
    "title": "My Report",
    "description": "Report description",
    "type": "custom",
    "status": "draft"
}

// Via the trait in your code
use App\Traits\MonitoringHelper;

$this->createMonitoringReport('Report Title', 'system', [
    'metric1' => 'value1',
    'metric2' => 'value2'
]);
```

### Exporting Reports

```bash
# Export as Excel
GET /reports/{id}/export-excel

# Export as PDF
GET /reports/{id}/export-pdf
```

## Directory Structure Reference

```
admin-monitoring/
├── app/
│   ├── Http/
│   │   └── Controllers/       # HTTP request handlers
│   ├── Models/                # Eloquent models
│   ├── Traits/                # Reusable code traits
│   └── Exports/               # Excel/Data exports
├── database/
│   ├── migrations/            # Database schemas
│   ├── seeders/               # Sample data
│   └── factories/             # Model factories for testing
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript files
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
├── storage/
│   └── logs/                  # Application logs
├── tests/
│   ├── Feature/               # Feature tests
│   └── Unit/                  # Unit tests
├── .env.example               # Environment template
├── composer.json              # PHP dependencies
├── package.json               # JavaScript dependencies
└── README.md                  # Project documentation
```

## Important Files

- `.env` - Environment configuration (DO NOT commit)
- `.env.example` - Template for environment config (COMMIT this)
- `.gitignore` - Files to ignore in Git
- `composer.json` - PHP package definitions
- `package.json` - NPM package definitions
- `vite.config.js` - Frontend build configuration
- `tailwind.config.js` - Tailwind CSS configuration
- `phpunit.xml` - Testing configuration

## Team Communication

- **Issues**: Use GitHub/GitLab issues for tracking work
- **Code Review**: All PRs require approval before merging
- **Discussions**: Use project board or chat for questions
- **Documentation**: Update README and SETUP.md when needed

## Performance Tips

1. **Eager Loading**: Use `with()` to avoid N+1 queries
   ```php
   Report::with('generatedBy')->get()
   ```

2. **Caching**: Use Laravel's cache for expensive operations
   ```php
   Cache::remember('reports.count', 3600, function() {
       return Report::count();
   });
   ```

3. **Database Indexing**: Add indexes to frequently queried columns

4. **Query Optimization**: Use `only()` and `pluck()` for specific columns
   ```php
   Report::pluck('title', 'id')
   Report::only(['id', 'title', 'status'])
   ```

## Security Reminders

- Never commit `.env` file with credentials
- Always validate and sanitize user input
- Use Eloquent ORM to prevent SQL injection
- Keep dependencies updated: `composer update`, `npm update`
- Use HTTPS in production
- Implement rate limiting for public APIs
- Follow CSRF protection (Laravel does this by default)

## Need Help?

1. Check the [SETUP.md](SETUP.md) for installation help
2. Review [Laravel Documentation](https://laravel.com/docs)
3. Check existing code examples in the project
4. Ask team members or create an issue
5. Review logs in `storage/logs/laravel.log`

---

Happy coding! 🚀
