# Admin Monitoring System - Setup Guide

This is a Laravel-based monitoring and reporting application. Follow these steps to set up the project on your local machine.

## Prerequisites

- PHP ^8.3
- Composer
- Node.js and npm
- Git
- A working Laragon/XAMPP/WAMP environment (or similar local development server)

## Installation Steps

### 1. Clone or Pull the Repository

```bash
git clone [repository-url] admin-monitoring
cd admin-monitoring
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
composer run-script post-root-package-install
php artisan key:generate
```

**Important:** Update the `.env` file with your local settings:
- `APP_NAME`: Application name for monitoring system
- `APP_URL`: Your local development URL (e.g., http://mir-admin.test)
- `DB_CONNECTION`: Database driver (sqlite, mysql, pgsql)
- `DB_DATABASE`: Database path or name
- `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`: Database credentials if using MySQL/PostgreSQL

### 5. Create Database

**For SQLite (default):**
```bash
touch database/database.sqlite
```

**For MySQL:**
```bash
mysql -u root -e "CREATE DATABASE admin_monitoring CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database (Optional)

```bash
php artisan db:seed
```

### 8. Build Frontend Assets

```bash
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000` (or your configured APP_URL).

## Installed Packages for Monitoring & Reporting

### PHP Packages:
- **laravel/framework**: Core framework
- **maatwebsite/excel**: Excel export functionality for reports
- **barryvdh/laravel-dompdf**: PDF generation for reports
- **spatie/laravel-health**: System health checks and monitoring
- **spatie/laravel-query-builder**: Advanced filtering and querying for reports
- **guzzlehttp/guzzle**: HTTP client for external API monitoring
- **predis/predis**: Redis client for caching and queues

### JavaScript/Frontend Packages:
- **chart.js**: Charts and graphs for report visualization
- **alpinejs**: Lightweight reactive component framework
- **axios**: HTTP client for frontend API calls
- **tailwindcss**: Utility-first CSS framework
- **laravel-vite-plugin**: Vite integration with Laravel

## Development Commands

### Run Development Server with Live Reload

```bash
npm run dev
```

In another terminal, start the Laravel server:
```bash
php artisan serve
```

### Run Tests

```bash
php artisan test
```

### Code Formatting and Linting

```bash
php artisan pint
```

### Database Migrations

```bash
# Create a new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset
```

### Generate Models, Controllers, etc.

```bash
# Create a model with migration and controller
php artisan make:model ModelName -mcr

# Create a migration
php artisan make:migration create_table_name

# Create a controller
php artisan make:controller ControllerName

# Create a request class
php artisan make:request StoreRequest

# Create a resource for API responses
php artisan make:resource ResourceName
```

## Project Structure

- `app/`: Application code (models, controllers, requests, resources)
- `app/Http/Controllers/`: Request handlers
- `app/Models/`: Database models
- `bootstrap/`: Laravel bootstrap files
- `config/`: Configuration files
- `database/`: Migrations, seeders, and factories
- `public/`: Publicly accessible files and build assets
- `resources/`: Views (Blade templates) and frontend assets (CSS, JavaScript)
- `routes/`: Route definitions (web, API, console)
- `storage/`: Logs, cache, uploads, and temporary files
- `tests/`: Test files (Feature and Unit tests)
- `vendor/`: Composer packages (auto-generated)

## Key Features for Monitoring

### Health Checks
The application includes `spatie/laravel-health` for system monitoring. Access health checks via:
```bash
php artisan health:check
```

### Excel Reports
Export monitoring data as Excel files using `maatwebsite/excel`. Example usage:
```php
use Maatwebsite\Excel\Facades\Excel;

Excel::store(new ReportExport($data), 'reports/report.xlsx', 'local');
```

### PDF Reports
Generate PDF reports using `barryvdh/laravel-dompdf`:
```php
use Barryvdh\DomPDF\Facade\Pdf;

return Pdf::load(view('reports.template', $data))->download('report.pdf');
```

### Database Query Filtering
Use `spatie/laravel-query-builder` for advanced filtering in reports:
```php
QueryBuilder::for(Report::class)
    ->allowedFilters(['status', 'date_range'])
    ->paginate();
```

### Charts and Visualization
Use Chart.js in your Blade views for data visualization:
```blade
<canvas id="reportChart"></canvas>
<script>
    new Chart(document.getElementById('reportChart'), {
        type: 'line',
        data: {{ $chartData }},
        options: { /* ... */ }
    });
</script>
```

## Troubleshooting

### PHP Extensions Missing
If you see errors about missing extensions (zip, mbstring, etc.), ensure they're enabled in your `php.ini`:
```ini
extension=zip
extension=mbstring
extension=pdo_mysql
```

### Permission Issues
On Windows with Laragon, permissions are usually not an issue. On Linux/Mac:
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs storage/framework
```

### Database Connection Issues
Verify your `.env` file database settings match your actual database configuration.

### Asset Not Found Errors
Rebuild assets if you modify CSS/JavaScript:
```bash
npm run build
```

For development with live reload:
```bash
npm run dev
```

## Deploying to Production

1. Install production dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. Build assets for production:
   ```bash
   npm run build
   ```

3. Configure `.env` for production
4. Run migrations:
   ```bash
   php artisan migrate --force
   ```

5. Cache configuration:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Support & Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Spatie Laravel Packages](https://spatie.be/open-source)
- [Chart.js Documentation](https://www.chartjs.org/)
- [Alpine.js Documentation](https://alpinejs.dev/)

## Quick Useful Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize autoloader
composer dump-autoload -o

# Interactive shell
php artisan tinker

# Create admin user
php artisan tinker
>>> User::factory()->create(['email' => 'admin@example.com'])

# Check system health
php artisan health:check
```

## Notes

- The project uses SQLite by default for easy local development
- All sensitive information (API keys, database passwords) should be in `.env` (never commit `.env`)
- Use `.env.example` as template for new team members
- Database backups are recommended before major migrations
- Monitor `storage/logs/laravel.log` for application errors

---

For issues or questions, please refer to the Laravel documentation or contact your team lead.
