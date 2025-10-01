# Project Features & Services Documentation

## Recent Updates & Features

### User Avatar Component System
**Location**: `app/View/Components/UserAvatar.php` & `resources/views/components/user-avatar.blade.php`

A reusable avatar component that automatically handles:
- User profile images (from `avatar_url` or `avatar` fields)
- Dynamic initials generation from user's name
- Guest user fallback ("GE")
- Responsive sizing

**Usage**:
```html
<!-- Default size (w-8 h-8) -->
<x-user-avatar />

<!-- Custom size -->
<x-user-avatar size="w-12 h-12" />
```

**Component Features**:
- Automatic initials from first + last name
- Fallback to splitting single name field
- Email-based initials as last resort
- Image overflow handling with rounded corners

### Service Provider Traits System
**Location**: `app/Traits/`

#### NotificationTrait
**File**: `app/Traits/NotificationTrait.php`

Handles all notification-related data for global view sharing:
- Total notification count
- Unread notification count  
- Latest notification message parsing
- Error handling for malformed notification data

#### UserInitialsTrait  
**File**: `app/Traits/UserInitialsTrait.php`

Manages user avatar and initials logic:
- Avatar URL detection
- Intelligent initials generation
- Guest user handling
- Name parsing algorithms

### File Upload Service
**Location**: `app/Services/FileUploadService.php`

Centralized file upload handling with Backblaze B2 integration:

**Usage Examples**:
```php
// Inject the service
$fileService = app(FileUploadService::class);

// Upload user avatar
$result = $fileService->uploadFile($request->file('avatar'), 'social/avatars', auth()->id());

// Upload post image
$result = $fileService->uploadFile($request->file('image'), 'social/posts', auth()->id());

// Upload event file
$result = $fileService->uploadFile($request->file('document'), 'events/2024');
```

**Test Routes**:
```php
// Test upload page
Route::get('test-backblaze', function () {
    return view('test-upload');
});

// Test upload handler
Route::post('test-backblaze', function (Request $request, FileUploadService $fileService) {
    try {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $result = $fileService->uploadFile($file, 'social/test', 999);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!',
            'data' => $result
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});
```

### Global View Data System
**Location**: `app/Providers/AppServiceProvider.php`

Automatically provides data to all views:
- `$notification_count` - Total notifications
- `$unread_notification_count` - Unread notifications  
- `$user_avatar` - User's avatar URL
- `$user_initials` - Generated user initials
- `$notification_message` - Latest notification message
- `$settings` - Global settings (future: GlobalSettings model)

### Professional Notifications Page
**Location**: `resources/views/notifications/index.blade.php`

Features:
- Clean card-based layout
- Professional filter tabs (All, Unread, Likes, Comments, Follows)
- Dynamic notification counts
- Mobile-responsive design
- Active state management
- Smooth animations

**Filter Tab Structure**:
- Mobile: Icon-only display
- Desktop: Icon + text
- Horizontal scrolling on mobile
- Professional active states

## Environment Configuration

### Email Service (Brevo/Sendinblue)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=8eade3001@smtp-brevo.com
MAIL_PASSWORD=your_api_key_here
```

**Setup Instructions**:
1. Visit [Brevo.com](https://www.brevo.com)
2. Create account and verify email
3. Go to SMTP & API → SMTP
4. Generate SMTP key
5. Use the provided SMTP settings

### Redis Cloud Configuration  
```env
REDIS_URL="redis://default:wN9t7CIfNt1CXKzkLcKG9xrfQJhqJq5d@redis-17111.c338.eu-west-2-1.ec2.redns.redis-cloud.com:17111/0"

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**Setup Instructions**:
1. Visit [Redis Cloud](https://redis.com/redis-enterprise-cloud/)
2. Create free account (30MB free tier)
3. Create new database
4. Copy the Redis URL from database details
5. Set drivers to use Redis for caching, sessions, and queues

### Backblaze B2 Storage
```env
BACKBLAZE_ACCOUNT_ID=your_account_id
BACKBLAZE_APPLICATION_KEY=your_application_key
BACKBLAZE_BUCKET=your_bucket_name
BACKBLAZE_BUCKET_ID=your_bucket_id
BACKBLAZE_REGION=us-west-002
```

**Setup Instructions**:
1. Visit [Backblaze.com](https://www.backblaze.com/b2/cloud-storage.html)
2. Create account (10GB free)
3. Create bucket (public or private)
4. Generate application key with read/write permissions
5. Copy credentials to environment file

## File Structure

```
app/
├── View/Components/
│   └── UserAvatar.php
├── Traits/
│   ├── NotificationTrait.php
│   └── UserInitialsTrait.php
├── Services/
│   └── FileUploadService.php
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── components/
│   └── user-avatar.blade.php
└── notifications/
    └── index.blade.php
```

## Features Summary

1. **User Avatar System** - Automatic avatar/initials with fallbacks
2. **Notification System** - Real-time counts and professional UI
3. **File Upload Service** - Centralized Backblaze B2 integration  
4. **Trait Organization** - Clean separation of concerns
5. **Global View Data** - Automatic data sharing across views
6. **Professional UI** - Mobile-first responsive design

## Future Enhancements

- [ ] Replace `stdClass` settings with `GlobalSettings` model
- [ ] Add avatar upload/crop functionality  
- [ ] Implement real-time notification updates
- [ ] Add notification preferences system
- [ ] Create admin notification management



# Deploying Laravel to Fly.io

Complete guide for deploying a Laravel application to Fly.io with PostgreSQL (Supabase) and Redis (Upstash).

## Prerequisites

- Fly.io account (free tier available)
- Supabase PostgreSQL database (Europe region)
- Upstash Redis (Europe region)
- Git repository with your Laravel project
- Credit card for Fly.io verification (no charges on free tier)

## Step 1: Install Fly CLI

**Windows (PowerShell):**
```powershell
powershell -Command "iwr https://fly.io/install.ps1 -useb | iex"
```

**Close and reopen your terminal** after installation.

Verify installation:
```powershell
flyctl version
```

## Step 2: Create Dockerfile

Create a file named `Dockerfile` in your project root:
```dockerfile
FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    oniguruma-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application code
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 8080
EXPOSE 8080

# Start application
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=8080
```

## Step 3: Login to Fly.io
```powershell
flyctl auth login
```

This opens your browser for authentication.

## Step 4: Launch Application

Navigate to your project directory:
```powershell
cd path/to/your/project
```

Launch the app (without deploying yet):
```powershell
flyctl launch --region fra --no-deploy
```

**Important:** When prompted:
- Choose your app name (e.g., `cyberforum`)
- PostgreSQL database? Type `n` (you have Supabase)
- Redis? Type `n` (you have Upstash)

This creates a `fly.toml` configuration file.

## Step 5: Set Environment Variables (Secrets)

Set all your environment variables as Fly.io secrets:
```powershell
flyctl secrets set APP_NAME="YourAppName" APP_KEY="base64:your-app-key-here" APP_ENV=production APP_DEBUG=false APP_URL="https://your-app.fly.dev" DB_CONNECTION=pgsql DB_HOST="aws-1-eu-west-1.pooler.supabase.com" DB_PORT=5432 DB_DATABASE=postgres DB_USERNAME="postgres.your-username" DB_PASSWORD="your-password" REDIS_URL="your-redis-url" CACHE_DRIVER=redis SESSION_DRIVER=redis QUEUE_CONNECTION=redis MAIL_MAILER=smtp MAIL_HOST="smtp-relay.brevo.com" MAIL_PORT=587 MAIL_USERNAME="your-mail-username" MAIL_PASSWORD="your-mail-password" MAIL_ENCRYPTION=tls MAIL_FROM_ADDRESS="your-email@domain.com"
```

**Replace with your actual credentials from `.env` file.**

## Step 6: Deploy
```powershell
flyctl deploy
```

This will:
- Build your Docker image (3-5 minutes)
- Push to Fly.io registry
- Create and start machines
- Deploy your app

## Step 7: Run Database Migrations

Connect to your app via SSH:
```powershell
flyctl ssh console
```

Inside the container, run migrations:
```bash
php artisan migrate --force
exit
```

## Step 8: Verify Deployment

Check app status:
```powershell
flyctl status
```

View logs:
```powershell
flyctl logs
```

Open your app:
```powershell
flyctl open
```

Or visit: `https://your-app-name.fly.dev`

## Important Configuration Notes

### Fly.io Free Tier Limits
- 3 shared-cpu-1x VMs (256MB RAM each)
- 3GB persistent storage
- 160GB outbound data transfer/month

### Database Setup
- **Local Development:** Use Docker/Sail
- **Production (Fly.io):** Use external Supabase PostgreSQL
- Region: `aws-1-eu-west-1` (Europe/Frankfurt)

### Redis Setup
- **Production:** Use external Upstash Redis
- Region: Europe (Frankfurt or nearby)

### Environment Differences
- **Local:** `APP_ENV=local`, `APP_DEBUG=true`
- **Production:** `APP_ENV=production`, `APP_DEBUG=false`

## Useful Commands
```powershell
# Check app status
flyctl status

# View logs (real-time)
flyctl logs

# SSH into container
flyctl ssh console

# Restart app
flyctl apps restart your-app-name

# Scale app (changes pricing)
flyctl scale vm shared-cpu-1x --memory 512

# Update secrets
flyctl secrets set KEY=value

# List secrets
flyctl secrets list

# Deploy after changes
flyctl deploy

# Destroy app
flyctl apps destroy your-app-name
```

## Troubleshooting

### Database Connection Error
Verify secrets are correct:
```powershell
flyctl secrets list
```

Update if needed:
```powershell
flyctl secrets set DB_HOST="correct-host" DB_USERNAME="correct-user" DB_PASSWORD="correct-password"
```

### App Won't Start
Check logs:
```powershell
flyctl logs
```

### Wrong Region
Check regions:
```powershell
flyctl regions list
```

Set preferred regions:
```powershell
flyctl regions set fra
```

## Security Notes

- Never commit `.env` file to Git
- Rotate passwords after exposure
- Use strong, unique passwords
- Enable 2FA on all services
- Regularly update dependencies

## Cost Management

- Monitor usage: https://fly.io/dashboard
- Set billing alerts
- Stay within free tier limits
- Scale down unused apps
