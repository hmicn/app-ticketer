# Deploy Symfony on Apache (Debian)

This guide explains how to deploy a Symfony application on an Apache server running on a Debian machine with PHP 8.3, MySQL 9.1, and Composer. It also includes steps to enable `mod_rewrite`.

## Prerequisites

Ensure the following are installed on your Debian machine:
- PHP 8.3
- MySQL 9.1
- Apache2
- Composer

## Steps

### 1. Install Required PHP Extensions
Install necessary PHP extensions for Symfony:
```bash
sudo apt update
sudo apt install php8.3-cli php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-intl php8.3-mysql
```

### 2. Enable Apache Modules
Enable `mod_rewrite` and restart Apache:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 3. Configure Apache Virtual Host
Create a virtual host configuration for your Symfony project:
```bash
sudo nano /etc/apache2/sites-available/symfony.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/symfony/public

    <Directory /var/www/symfony/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/symfony_error.log
    CustomLog ${APACHE_LOG_DIR}/symfony_access.log combined
</VirtualHost>
```

Enable the site and reload Apache:
```bash
sudo a2ensite symfony.conf
sudo systemctl reload apache2
```

### 4. Set Up Symfony Project
Navigate to your project directory and install dependencies:
```bash
cd /var/www/symfony
composer install
```

Set proper permissions:
```bash
sudo chown -R www-data:www-data /var/www/symfony
sudo chmod -R 775 /var/www/symfony/var /var/www/symfony/public
```

### 5. Configure Database
Update the `.env` file with your MySQL credentials:
```
DATABASE_URL="mysql://username:password@127.0.0.1:3306/dbname"
```

Run database migrations:
```bash
php bin/console doctrine:migrations:migrate
```

### 6. Test the Application
Access your Symfony application in the browser at `http://your-domain.com`.

## Troubleshooting
- Check Apache logs for errors:
  ```bash
  sudo tail -f /var/log/apache2/error.log
  ```
- Verify PHP version:
  ```bash
  php -v
  ```
