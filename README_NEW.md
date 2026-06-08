# Admin Monitoring System

A comprehensive Laravel-based monitoring and reporting application designed for system health checks, performance monitoring, and detailed report generation.

## 📋 Features

### Core Monitoring
- **System Health Checks**: Real-time database, cache, storage, and filesystem monitoring
- **Performance Monitoring**: Track application response times, query counts, and resource usage
- **User Activity Tracking**: Monitor user engagement and session data
- **Custom Reporting**: Create and manage custom monitoring reports

### Report Management
- **Create Reports**: Generate new monitoring reports with custom data
- **Publish & Archive**: Control report visibility and lifecycle
- **Excel Export**: Export reports to `.xlsx` format using Maatwebsite Excel
- **PDF Export**: Generate PDF reports using Laravel DomPDF
- **Advanced Filtering**: Filter reports by status, type, date range using Spatie Query Builder

### Dashboard & Visualization
- **Interactive Dashboard**: Real-time overview of monitoring metrics
- **Charts & Graphs**: Data visualization using Chart.js
- **Report Analytics**: Statistics and insights on reports
- **System Metrics**: CPU, memory, disk space, and database monitoring

### Development Features
- **Testing Ready**: PHPUnit tests included
- **Code Quality**: Laravel Pint for code formatting
- **Hot Module Reload**: Vite with live reload during development
- **Type-Safe**: Built with modern PHP 8.3+

## 🚀 Quick Start

### Prerequisites
- PHP ^8.3
- Composer
- Node.js & npm
- Git

### Installation

```bash
# 1. Clone the repository
git clone [repository-url]
cd admin-monitoring

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate

# 5. (Optional) Seed sample data
php artisan db:seed ReportSeeder

# 6. Build assets
npm run build

# 7. Start the application
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 📦 Included Packages

### PHP Packages
| Package | Purpose |
|---------|---------|
| `laravel/framework` | Core Laravel framework (v13.8) |
| `maatwebsite/excel` | Excel export functionality |
| `barryvdh/laravel-dompdf` | PDF generation |
| `spatie/laravel-health` | Health check monitoring |
| `spatie/laravel-query-builder` | Advanced query filtering |
| `guzzlehttp/guzzle` | HTTP client |
| `predis/predis` | Redis client |

### JavaScript/NPM Packages
| Package | Purpose |
|---------|---------|
| `laravel-vite-plugin` | Vite integration |
| `tailwindcss` | Utility-first CSS |
| `chart.js` | Data visualization |
| `alpinejs` | Lightweight reactivity |
| `axios` | HTTP client |

## 📁 Project Structure

```
admin-monitoring/
├── app/
│   ├── Http/Controllers/     # Request handlers
│   ├── Models/               # Database models
│   ├── Exports/              # Export classes (Excel, PDF)
│   └── Traits/               # Reusable traits
├── database/
│   ├── migrations/           # Database schemas
│   ├── seeders/              # Sample data
│   └── factories/            # Test factories
├── resources/
│   ├── views/                # Blade templates
│   ├── css/                  # Tailwind stylesheets
│   └── js/                   # Frontend JavaScript
├── routes/                   # Application routes
├── storage/                  # Logs, cache, uploads
├── tests/                    # Feature and unit tests
├── public/                   # Public assets
├── config/                   # Configuration files
├── .env.example              # Environment template
├── SETUP.md                  # Setup instructions
├── COLLABORATORS.md          # Team collaboration guide
└── README.md                 # This file
```

## 🎯 Core Features Explained

### Report Controller
Handles all report operations:
- `index()` - List all reports with filtering
- `create()` - Show report creation form
- `store()` - Save new report
- `show()` - Display report details
- `edit()` - Edit report
- `update()` - Save report changes
- `destroy()` - Delete report
- `exportExcel()` - Export as Excel
- `exportPDF()` - Export as PDF
- `dashboard()` - Show monitoring dashboard
- `systemHealth()` - Check system health

### Report Model
Eloquent model with features:
- Mass assignment for safe data handling
- JSON casting for flexible data storage
- Scopes for common queries (`published()`, `draft()`, `ofType()`, `thisMonth()`)
- Helper methods (`isArchived()`, `publish()`, `archive()`)
- Relationships (`generatedBy()` user association)

### Monitoring Helper Trait
Reusable trait for monitoring functionality:
- `getSystemMetrics()` - Get CPU, memory, disk info
- `logMonitoring()` - Log monitoring data
- `getReportStatistics()` - Report statistics
- System health checks (database, storage, cache)

### Report Seeder
Creates sample reports for testing:
- System health reports
- Performance reports
- User activity reports
- 10+ daily monitoring reports
- Draft and archived reports

## 🛠️ Development

### Running Tests
```bash
php artisan test
php artisan test --coverage
```

### Code Quality
```bash
# Check code formatting
php artisan pint --check

# Auto-fix formatting
php artisan pint
```

### Development Server with Live Reload
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Create new migration
php artisan make:migration create_table_name

# Rollback changes
php artisan migrate:rollback

# Reset database
php artisan migrate:reset --seed
```

## 📊 Using Monitoring Features

### Creating a Report
```php
use App\Models\Report;

Report::create([
    'title' => 'System Performance Report',
    'description' => 'Weekly system metrics',
    'type' => 'performance',
    'status' => 'published',
    'data' => [
        'uptime' => '99.95%',
        'avg_response_time' => '340ms',
        'errors' => 2,
    ],
]);
```

### Using Monitoring Trait
```php
use App\Traits\MonitoringHelper;

class YourClass {
    use MonitoringHelper;
    
    public function monitor() {
        $metrics = $this->getSystemMetrics();
        $stats = $this->getReportStatistics();
        $this->logMonitoring('daily', $metrics);
    }
}
```

### Exporting Reports
```php
// Via URL
GET /reports/{id}/export-excel
GET /reports/{id}/export-pdf

// Via code
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

Excel::download(new ReportExport($report), 'report.xlsx');
```

## 🔐 Security

- **Environment Variables**: Sensitive data in `.env` (never commit)
- **Input Validation**: All user inputs validated via Request classes
- **SQL Injection Prevention**: Eloquent ORM usage throughout
- **CSRF Protection**: Enabled by default in Laravel
- **Authentication**: Ready for user authentication setup
- **Rate Limiting**: Can be added to API routes

## 📝 Documentation

- [SETUP.md](SETUP.md) - Detailed installation and setup guide
- [COLLABORATORS.md](COLLABORATORS.md) - Team development workflow
- [Laravel Docs](https://laravel.com/docs) - Framework documentation
- [Spatie Packages](https://spatie.be/open-source) - Package documentation

## 🚢 Deployment

### Production Checklist
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build assets
npm run build

# Generate app key
php artisan key:generate

# Configure .env for production
# Update APP_DEBUG=false, APP_ENV=production

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (Linux/Mac)
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
```

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stack
DB_CONNECTION=mysql
CACHE_STORE=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## 🐛 Troubleshooting

### Common Issues

**"Class not found" errors**
```bash
composer dump-autoload
php artisan cache:clear
```

**Database connection issues**
- Check `.env` file database credentials
- Ensure database exists
- For SQLite: `touch database/database.sqlite`

**Assets not loading**
```bash
npm run build
php artisan view:clear
```

**Permission denied**
```bash
# Linux/Mac
chmod -R 755 storage bootstrap/cache

# Windows - usually not needed; check disk permissions
```

## 📞 Support & Contribution

- Report issues via GitHub Issues
- Create Pull Requests for contributions
- Follow conventional commit messages
- Maintain code quality standards
- Update documentation with changes

## 📄 License

MIT License - See LICENSE file for details

## 🙏 Acknowledgments

Built with:
- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Chart.js](https://www.chartjs.org/) - Data visualization
- [Spatie Packages](https://spatie.be/open-source) - Community packages
- [Vite](https://vitejs.dev) - Frontend build tool

---

**Last Updated**: June 2026
**Version**: 1.0.0
**Status**: Active Development

For questions or issues, please refer to the documentation or contact the development team.
