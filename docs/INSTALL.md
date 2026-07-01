# Installation Guide - Hana-Eunhaeng

## Prerequisites

### System Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher
- **Apache**: 2.4+ with mod_rewrite enabled
- **Composer**: Optional (for package management)

### Required PHP Extensions
```bash
# Ubuntu/Debian
sudo apt-get install php php-mysql php-mbstring php-curl php-json php-intl

# macOS (using Homebrew)
brew install php mysql

# Windows
# Download PHP from php.net and add to PATH
```

## Installation Steps

### 1. Clone Repository

```bash
git clone https://github.com/Green-1122/hana-eunhaeng.git
cd hana-eunhaeng
```

### 2. Configure Environment

```bash
# Copy environment template
cp app/config/.env.example app/config/.env

# Edit configuration
nano app/config/.env
```

Update the following variables:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=hana_eunhaeng
DB_USER=root
DB_PASS=your_password

APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

SESSION_SECURE=true
BCRYPT_ROUNDS=12
```

### 3. Create Database

#### Via Command Line
```bash
mysql -u root -p < database/schema.sql
```

#### Via phpMyAdmin
1. Login to phpMyAdmin
2. Create new database: `hana_eunhaeng`
3. Import `database/schema.sql`

#### Via MySQL Command
```sql
CREATE DATABASE hana_eunhaeng CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hana_eunhaeng;
-- Import schema file
```

### 4. Set Permissions

```bash
# Create necessary directories
mkdir -p storage/logs
mkdir -p storage/uploads

# Set permissions
chmod -R 755 storage/
chmod 644 public/.htaccess
chmod 600 app/config/.env
```

### 5. Configure Web Server

#### Apache Configuration

Create `/etc/apache2/sites-available/hana-eunhaeng.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/hana-eunhaeng/public

    <Directory /var/www/hana-eunhaeng/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted

        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php/$1 [L]
        </IfModule>
    </Directory>

    # Enable SSL in production
    # SSLEngine on
    # SSLCertificateFile /etc/ssl/certs/your-cert.crt
    # SSLCertificateKeyFile /etc/ssl/private/your-key.key

    ErrorLog ${APACHE_LOG_DIR}/hana-eunhaeng-error.log
    CustomLog ${APACHE_LOG_DIR}/hana-eunhaeng-access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite hana-eunhaeng
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx Configuration

Create `/etc/nginx/sites-available/hana-eunhaeng`:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/hana-eunhaeng/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # SSL configuration
    # listen 443 ssl http2;
    # ssl_certificate /etc/ssl/certs/your-cert.crt;
    # ssl_certificate_key /etc/ssl/private/your-key.key;

    error_log /var/log/nginx/hana-eunhaeng-error.log;
    access_log /var/log/nginx/hana-eunhaeng-access.log;
}
```

### 6. Start Development Server

#### Using PHP Built-in Server
```bash
php -S localhost:8000 -t public/
```

#### Using Docker
```bash
docker-compose up -d
```

### 7. Initial Setup

1. **Access Application**
   - Navigate to `http://localhost:8000`
   - Or `http://yourdomain.com`

2. **Create Admin Account**
   ```bash
   php bin/console admin:create
   ```

3. **Seed Demo Data** (optional)
   ```bash
   php bin/console db:seed
   ```

### 8. Enable SSL (Production)

```bash
# Using Let's Encrypt with Certbot
sudo certbot certonly --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renew
sudo systemctl enable certbot.timer
```

## Configuration

### Environment Variables

#### Database
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=hana_eunhaeng
DB_USER=root
DB_PASS=password
```

#### Application
```env
APP_NAME=Hana-Eunhaeng
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

#### Security
```env
SESSION_SECURE=true
SESSION_SAME_SITE=Strict
SESSION_LIFETIME=1440
BCRYPT_ROUNDS=12
CSRF_ENABLED=true
```

#### Mail (Optional)
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

## Verification

### Check Installation

```bash
# Test database connection
php -r "require 'app/config/config.php'; \App\Core\Database::getConnection(); echo 'Database OK';"

# Check file permissions
ls -la storage/
ls -la public/.htaccess

# Verify web server
curl http://localhost:8000/
```

### Common Issues

#### "Database connection failed"
- Verify MySQL is running
- Check database credentials in `.env`
- Ensure database exists

#### "404 Not Found"
- Enable mod_rewrite on Apache
- Check `.htaccess` file exists
- Verify DocumentRoot setting

#### "Permission Denied"
```bash
sudo chown -R www-data:www-data /var/www/hana-eunhaeng
chmod -R 755 /var/www/hana-eunhaeng
```

#### "PHP Extension Missing"
```bash
# Ubuntu/Debian
sudo apt-get install php-extension-name
sudo systemctl restart apache2
```

## Production Deployment

### Security Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Enable HTTPS/SSL
- [ ] Set `SESSION_SECURE=true`
- [ ] Configure firewall
- [ ] Set up backups
- [ ] Enable monitoring
- [ ] Configure rate limiting
- [ ] Set up logging

### Performance Optimization

```bash
# Enable PHP opcode caching
php --ini | grep opcache

# Configure MySQL
mysql> SET GLOBAL max_connections = 1000;

# Set up Redis (optional)
redis-server
```

### Backup Strategy

```bash
# Daily database backup
0 2 * * * mysqldump -u root -p hana_eunhaeng > /backup/hana_$(date +\%Y\%m\%d).sql

# Weekly file backup
0 3 * * 0 tar -czf /backup/hana_files_$(date +\%Y\%m\%d).tar.gz /var/www/hana-eunhaeng
```

## Support

For issues:
1. Check the documentation
2. Review server logs
3. Open an issue on GitHub
4. Contact support team

## Next Steps

- [Configuration Guide](CONFIGURATION.md)
- [API Documentation](API.md)
- [Database Schema](DATABASE.md)
- [Security Guide](SECURITY.md)
