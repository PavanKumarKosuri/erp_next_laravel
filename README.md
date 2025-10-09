# Next ERP - Modular Laravel Application

A modern, modular ERP system built with Laravel 10, Filament v3, PostgreSQL, and Docker.

## 🚀 Features

- ✅ **Modular Architecture** - Self-contained modules with independent functionality
- ✅ **Role-Based Access Control (RBAC)** - Using Spatie Laravel Permission
- ✅ **Filament Admin Panel** - Modern, responsive admin interface
- ✅ **Docker Support** - Containerized development environment
- ✅ **PostgreSQL Database** - Robust database support
- ✅ **Auto-Discovery** - Modules are automatically registered
- ✅ **Command Line Tools** - Easy module generation via artisan commands

## 📦 Tech Stack

- **Framework:** Laravel 10
- **Admin Panel:** Filament v3
- **Database:** PostgreSQL (with Docker)
- **Authentication:** Laravel Breeze
- **RBAC:** Spatie Laravel Permission
- **Containerization:** Docker + Docker Compose
- **PHP:** 8.3
- **Web Server:** Nginx

## 🏗️ Architecture

### Modular Structure
```
modules/
├── HR/
│   ├── Filament/
│   │   └── Resources/
│   │       └── EmployeeResource.php
│   ├── Models/
│   │   └── Employee.php
│   ├── Providers/
│   │   └── HRServiceProvider.php
│   ├── database/
│   │   └── migrations/
│   ├── routes/
│   └── module.json
├── Inventory/
│   └── [similar structure]
└── Finance/
    └── [similar structure]
```

### Built-in Modules

#### 🧑‍💼 HR Module
- **Employee Management** - Complete CRUD operations
- **Department Organization** - Organize by departments
- **Employee Status Tracking** - Active, Inactive, Terminated
- **Salary Management** - Track employee compensation
- **Personal Information** - Contact details, addresses

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url> next-erp
   cd next-erp
   ```

2. **Setup with Docker**
   ```bash
   make setup
   ```
   This command will:
   - Copy environment file
   - Build Docker containers
   - Install dependencies
   - Generate app key
   - Run migrations
   - Set proper permissions

3. **Create admin user**
   ```bash
   make create-admin
   ```

4. **Access the application**
   - Frontend: http://localhost:8080
   - Admin Panel: http://localhost:8080/admin

### Alternative Setup (Without Docker)

1. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database setup**
   ```bash
   # Configure your database in .env
   php artisan migrate --seed
   ```

4. **Create admin user**
   ```bash
   php artisan make:filament-user
   ```

## 🛠️ Development

### Available Make Commands

```bash
make help          # Show all available commands
make build         # Build Docker containers
make up            # Start the application
make down          # Stop the application
make restart       # Restart the application
make logs          # View application logs
make shell         # Access application shell
make migrate       # Run database migrations
make migrate-fresh # Fresh migrate with seed
make seed          # Run database seeders
make test          # Run tests
make cache-clear   # Clear all caches
make permissions   # Fix file permissions
```

### Creating New Modules

```bash
php artisan module:create ModuleName
```

This will create a complete module structure with:
- Model
- Filament Resource
- Migration
- Service Provider
- Module configuration

### Module Configuration

Each module has a `module.json` file:

```json
{
    "name": "HR",
    "description": "Human Resources Management Module",
    "version": "1.0.0",
    "enabled": true,
    "dependencies": [],
    "author": "Your Name",
    "autoload": {
        "psr-4": {
            "Modules\HR": ""
        }
    }
}
```

## 🔒 Authentication & Authorization

### Default Roles

- **Super Admin** - Full system access
- **Admin** - Administrative access
- **Manager** - Departmental management
- **Staff** - Basic access

### Default Admin User

After running seeders:
- **Email:** admin@next-erp.com
- **Password:** password

### Permissions

The system includes granular permissions for:
- User management (view, create, edit, delete)
- Role management (view, create, edit, delete)
- Permission management (view, create, edit, delete)
- Module-specific permissions (HR: employees)

## 📊 Filament Admin Features

### Navigation Groups

- **User Management** - Users, Roles, Permissions
- **HR Management** - Employees, Departments
- **Dashboard** - System overview

### Resource Features

- **Advanced Filtering** - Department, status filters
- **Bulk Actions** - Mass operations
- **Export/Import** - Data management
- **Search** - Global and column-specific search
- **Responsive Design** - Mobile-friendly interface

## 🗄️ Database

### Included Tables

- `users` - System users
- `roles` - User roles
- `permissions` - System permissions
- `model_has_permissions` - Permission assignments
- `model_has_roles` - Role assignments
- `role_has_permissions` - Role-permission mapping
- `employees` - HR module employees

### Migration Management

Modules automatically register their migrations. Use standard Laravel migration commands:

```bash
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh
```

## 🐳 Docker Configuration

### Services

- **app** - Laravel application (PHP-FPM)
- **nginx** - Web server
- **postgres** - PostgreSQL database
- **redis** - Caching and sessions

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Next ERP"
APP_URL=http://localhost:8080

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_DATABASE=next_erp
DB_USERNAME=next_erp_user
DB_PASSWORD=password
```

## 🧪 Testing

Run the test suite:

```bash
make test
# or
php artisan test
```

## 📝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## 🗺️ Roadmap

### Planned Modules

- [ ] **Inventory Management**
  - Products, Categories, Stock levels
  - Purchase orders, Suppliers
  - Warehouse management

- [ ] **Financial Management**
  - Accounting, Invoices, Payments
  - Budget tracking, Reports
  - Tax management

- [ ] **Sales Management**
  - Customer management, Orders
  - Quotations, Sales reports
  - Commission tracking

- [ ] **Project Management**
  - Projects, Tasks, Time tracking
  - Resource allocation
  - Progress monitoring

### Planned Features

- [ ] **Multi-tenancy** - Support for multiple organizations
- [ ] **API Layer** - RESTful API for integrations
- [ ] **Reporting Engine** - Advanced reporting and analytics
- [ ] **Notification System** - Email and in-app notifications
- [ ] **Audit Trails** - Complete activity logging
- [ ] **Advanced Permissions** - Field-level and data-level permissions
- [ ] **Theming System** - Customizable UI themes
- [ ] **Plugin System** - Third-party extensions

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🤝 Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review existing issues and discussions

## 🎯 Status

**Current Version:** 1.0.0-alpha

**Development Status:** Active Development

This is an initial implementation with core functionality. The system is ready for development and testing but should not be used in production without thorough testing and security review.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
