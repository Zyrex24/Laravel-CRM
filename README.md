# Laravel CRM

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

A comprehensive Customer Relationship Management (CRM) system built with Laravel, featuring a modern dashboard, API endpoints, and email integration capabilities.

## Features

- **Professional Dashboard** - Modern, responsive dashboard with real-time statistics
- **Contact Management** - Complete contact lifecycle management
- **Lead Tracking** - Lead capture, qualification, and conversion tracking
- **Email Integration** - Comprehensive email system with templates and tracking
- **Activity Management** - Task scheduling and activity logging
- **RESTful API** - Full API coverage for all CRM operations
- **Modern UI** - Built with Tailwind CSS and responsive design
- **Fast Development** - Powered by Vite for lightning-fast asset compilation

## Quick Start

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & npm
- MySQL/PostgreSQL (optional for full functionality)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Zyrex24/Laravel-CRM.git
   cd Laravel-CRM
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Build frontend assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - Open your browser and navigate to `http://localhost:8000`
   - You'll see the professional CRM dashboard

## API Endpoints

### Dashboard
- `GET /api/dashboard` - Dashboard statistics and metrics

### Contacts
- `GET /api/contacts` - List all contacts
- `POST /api/contacts` - Create new contact
- `GET /api/contacts/{id}` - Get specific contact
- `PUT /api/contacts/{id}` - Update contact
- `DELETE /api/contacts/{id}` - Delete contact

### Leads
- `GET /api/leads` - List all leads
- `POST /api/leads` - Create new lead
- `GET /api/leads/{id}` - Get specific lead
- `PUT /api/leads/{id}` - Update lead
- `DELETE /api/leads/{id}` - Delete lead

### Activities
- `GET /api/activities` - List all activities
- `POST /api/activities` - Create new activity
- `GET /api/activities/{id}` - Get specific activity
- `PUT /api/activities/{id}` - Update activity
- `DELETE /api/activities/{id}` - Delete activity

## Architecture

### Backend Structure
```
app/
├── Http/Controllers/Api/     # API Controllers
├── Models/                   # Eloquent Models
└── Services/                 # Business Logic Services

packages/CRM/                 # CRM Package Structure
├── Activity/                 # Activity management
├── Admin/                    # Admin functionality
├── Attribute/                # Custom attributes
├── Contact/                  # Contact management
├── Email/                    # Email integration
├── Lead/                     # Lead management
├── Organization/             # Organization management
└── User/                     # User management
```

### Frontend Structure
```
resources/
├── css/                      # Stylesheets
├── js/                       # JavaScript/Vue components
└── views/                    # Blade templates
```

## Email Integration

The CRM includes a comprehensive email system with:

- **Email Templates** - Customizable templates with variable substitution
- **Email Tracking** - Open and click tracking with analytics
- **IMAP/SMTP Support** - Full email server integration
- **Email Automation** - Workflow-based email automation
- **Thread Management** - Email conversation threading
- **Attachment Handling** - File attachment support with security scanning

##  Development

### Running in Development Mode

1. **Start the Laravel server**
   ```bash
   php artisan serve
   ```

2. **Start Vite development server**
   ```bash
   npm run dev
   ```

3. **Run tests**
   ```bash
   php artisan test
   ```

### Building for Production

```bash
# Build optimized assets
npm run build

# Optimize Laravel for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Dashboard Features

The CRM dashboard provides:

- **Quick Statistics** - Total contacts, active leads, open activities, revenue metrics
- **API Status Indicators** - Real-time API endpoint status monitoring
- **Recent Activities** - Latest system activities and updates
- **Quick Actions** - One-click access to common CRM tasks
- **System Information** - Laravel version, PHP version, environment details

## Configuration

### Database Setup (Optional)

1. Configure your database in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_crm
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

3. Seed sample data:
   ```bash
   php artisan db:seed
   ```

### Email Configuration

Configure email settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Built with [Laravel](https://laravel.com/) - The PHP Framework for Web Artisans
- UI powered by [Tailwind CSS](https://tailwindcss.com/)
- Frontend tooling by [Vite](https://vitejs.dev/)
- Icons from [Heroicons](https://heroicons.com/)
