# SocialNet (MockProjectSocial)

A web-based social network mock project built with PHP, MySQL, Nginx, and Linux. This application features user authentication, a profile directory, editable user descriptions, and a modern glassmorphism UI.

## ✨ Features

- **Secure Authentication:** User login and registration with hashed passwords (bcrypt).
- **User Directory:** A homepage listing all registered users in the system.
- **Profiles:** Individual user profile pages displaying their information and descriptions.
- **Profile Settings:** Users can edit their own profile descriptions.
- **Admin Panel:** Special page for creating new user accounts.
- **Modern UI:** Glassmorphism design, responsive layouts, and smooth animations.

## 🛠️ Tech Stack

- **Backend:** PHP 8.x
- **Database:** MySQL / MariaDB
- **Web Server:** Nginx
- **OS:** Linux (Ubuntu/Debian recommended)

## 🚀 Setup & Installation Guide

Follow these steps to get the application running smoothly on your server.

### 1. Prerequisites

Ensure you have Nginx, PHP, and MySQL installed on your system:

```bash
sudo apt update
sudo apt install nginx mysql-server php-fpm php-mysql
```

### 2. Deploy Project Files

Clone the repository directly into the root directory (`/`). You can use either HTTPS or SSH:

**Option A: Using HTTPS**
```bash
cd /
sudo git clone https://github.com/YeuThuyr/myapp.git
```

**Option B: Using SSH**
```bash
cd /
sudo git clone git@github.com:YeuThuyr/myapp.git
```

Set the correct file permissions so Nginx can serve the files:

```bash
sudo chown -R www-data:www-data /myapp
sudo chmod -R 755 /myapp
```

### 3. Setup the Database

We have provided a fully automated database script. It will create the database, the required tables, a dedicated application database user (`socialnet_user`), and populate the app with sample data.

Run the following command in your terminal (you may need `sudo` or to provide your MySQL root password):

```bash
sudo mysql < db.sql
```
*(No manual user creation or privilege granting is required!)*

### 4. Configure Nginx

Create a new Nginx server block or update your default one. Here is an example configuration for `/etc/nginx/sites-available/myapp`:

```nginx
server {
    listen 80;
    server_name localhost; # Change to your domain or IP

    # Set the root to / so the app is accessed at /myapp
    root /;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        # Adjust the PHP version below if you are not using 8.3
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    # Deny access to hidden files like .htaccess
    location ~ /\.ht {
        deny all;
    }
}
```

Enable the configuration and restart Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/myapp /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

---

## 🔑 Sample Accounts

After setting up the database, you can immediately log in using any of the following pre-created accounts:

| Username | Password      | Role                  |
|----------|--------------|-----------------------|
| `admin`  | `password123` | System Administrator  |
| `alice`  | `password123` | Standard User         |
| `bob`    | `password123` | Standard User         |

---

## 📂 Project Structure

```text
/
├── admin/
│   └── newuser.php           # Admin panel to create users
├── includes/
│   ├── auth.php              # Authentication and session logic
│   ├── db.php                # Database connection credentials
│   └── menubar.php           # Global navigation component
├── about.php                 # Static project information
├── db.sql                    # Automated DB schema & setup script
├── index.php                 # Homepage (User directory)
├── profile.php               # User profile viewing page
├── setting.php               # Profile editing page
├── signin.php                # Login page
├── signout.php               # Logout logic
├── style.css                 # Global stylesheets (Glassmorphism)
└── README.md                 # Project documentation
```

## 🛡️ Security Notes
- Passwords are encrypted using PHP's native `password_hash()`.
- SQL injection is prevented by exclusively using Prepared Statements (`mysqli_stmt`) for all database interactions.
- Cross-Site Scripting (XSS) is mitigated by using `htmlspecialchars()` when displaying user-generated content.
- Unauthenticated access to private pages automatically redirects users to `signin.php`.
