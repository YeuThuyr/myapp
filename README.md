# CS-ClassB

A simple web application built with PHP, MySQL, Nginx, and Linux. Features user account management, profiles, and a modern glassmorphism UI.

## Tech Stack

| Component    | Technology       |
|-------------|-----------------|
| Backend     | PHP 8.x         |
| Database    | MySQL / MariaDB  |
| Web Server  | Nginx            |
| OS          | Linux            |

## Setup Instructions

### 1. MySQL Database

Import the database schema:

```bash
mysql -u root -p < db.sql
```

This will:
- Create the `socialnet` database
- Create the `account` table
- Insert 3 sample users (all passwords: `password123`)

#### Grant permissions to the app user:

```sql
CREATE USER 'myapp_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON socialnet.* TO 'myapp_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. PHP

Ensure PHP 8.x and `php-fpm` are installed with the `mysqli` extension:

```bash
sudo apt install php-fpm php-mysql
```

### 3. Nginx Configuration

Example Nginx server block:

```nginx
server {
    listen 80;
    server_name your-domain.com;

    root /var/www/html;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Deploy the project files to `/var/www/html/` (or your chosen web root).

### 4. File Permissions

```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

## Page URLs

| Page         | URL                                      | Description                          |
|-------------|------------------------------------------|--------------------------------------|
| Admin        | `/admin/newuser.php`                     | Create a new user account            |
| Sign In      | `/socialnet/signin.php`                  | Login with username & password       |
| Home         | `/socialnet/index.php`                   | User directory (requires login)      |
| Setting      | `/socialnet/setting.php`                 | Edit profile description             |
| Profile      | `/socialnet/profile.php`                 | View own profile                     |
| Profile      | `/socialnet/profile.php?owner=username`  | View another user's profile          |
| About        | `/socialnet/about.php`                   | Student info & project details       |
| Sign Out     | `/socialnet/signout.php`                 | End session & redirect to sign in    |

## Sample Accounts

| Username | Password      | Full Name       |
|----------|--------------|-----------------|
| admin    | password123  | Administrator   |
| alice    | password123  | Alice Nguyen    |
| bob      | password123  | Bob Tran        |

## Project Structure

```
project-root/
├── admin/
│   └── newuser.php           # Admin: create new users
├── socialnet/
│   ├── index.php             # Home page (user list)
│   ├── signin.php            # Sign in page
│   ├── signout.php           # Sign out (destroys session)
│   ├── setting.php           # Edit profile description
│   ├── profile.php           # View profile (?owner=username)
│   ├── about.php             # Static about page
│   └── includes/
│       ├── db.php            # Database connection
│       ├── auth.php          # Authentication helper
│       └── menubar.php       # Shared navigation bar
├── style.css                 # Global CSS
├── db.sql                    # Database schema + sample data
└── README.md                 # This file
```

## Security Features

- Passwords hashed with `password_hash()` (bcrypt)
- Login verified with `password_verify()`
- Prepared statements for all SQL queries
- Output escaped with `htmlspecialchars()` to prevent XSS
- Session-based authentication on all protected pages
- Unauthenticated users redirected to sign-in page

## Extra Features

- **Glassmorphism UI** — Modern dark theme with blur effects, gradients, and smooth animations
- **Responsive Design** — Works on desktop and mobile devices
- **Shared MenuBar** — Consistent navigation across all protected pages with active page highlighting
- **User Avatars** — Auto-generated initials-based avatars
- **Profile Linking** — Users can view each other's profiles via `?owner=username`
