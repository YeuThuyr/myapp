<?php
/**
 * About Page
 * URL: /socialnet/about.php
 *
 * Static page with student info.
 */

require_once __DIR__ . '/includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About — CS-ClassB</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>

  <?php include __DIR__ . '/includes/menubar.php'; ?>

  <main class="page-content">
    <div class="simple-page" style="animation: fadeInUp 0.5s ease;">

      <h1>ℹ️ About</h1>
      <p class="subtitle">Student information and project details.</p>

      <div class="card" style="margin-bottom:20px;">
        <h3 style="font-size:18px; margin-bottom:16px; color:var(--text-primary);">Student Information</h3>

        <div class="info-row">
          <span class="info-label">Student Name</span>
          <span class="info-value">Your Name Here</span>
        </div>
        <div class="info-row">
          <span class="info-label">Student Number</span>
          <span class="info-value">20XXXXXX</span>
        </div>
      </div>

      <div class="card" style="margin-bottom:20px;">
        <h3 style="font-size:18px; margin-bottom:16px; color:var(--text-primary);">Project Details</h3>

        <div class="info-row">
          <span class="info-label">Project</span>
          <span class="info-value">CS-ClassB</span>
        </div>
        <div class="info-row">
          <span class="info-label">Stack</span>
          <span class="info-value">PHP · MySQL · Nginx · Linux</span>
        </div>
        <div class="info-row">
          <span class="info-label">Version</span>
          <span class="info-value">1.0.0</span>
        </div>
      </div>

      <div class="card">
        <h3 style="font-size:16px; margin-bottom:12px; color:var(--text-primary);">Features</h3>
        <ul style="list-style:none; padding:0;">
          <li style="padding:8px 0; color:var(--text-secondary); font-size:14px; border-bottom:1px solid var(--border);">
            🔐 Secure login with hashed passwords (password_hash / password_verify)
          </li>
          <li style="padding:8px 0; color:var(--text-secondary); font-size:14px; border-bottom:1px solid var(--border);">
            🛡️ Admin panel for creating user accounts
          </li>
          <li style="padding:8px 0; color:var(--text-secondary); font-size:14px; border-bottom:1px solid var(--border);">
            👤 User profiles with editable descriptions
          </li>
          <li style="padding:8px 0; color:var(--text-secondary); font-size:14px; border-bottom:1px solid var(--border);">
            🔒 Session-based authentication with protected pages
          </li>
          <li style="padding:8px 0; color:var(--text-secondary); font-size:14px; border-bottom:1px solid var(--border);">
            🛡️ Prepared statements for all SQL queries
          </li>
          <li style="padding:8px 0; color:var(--text-secondary); font-size:14px;">
            🎨 Modern glassmorphism UI design
          </li>
        </ul>
      </div>

    </div>
  </main>

</body>
</html>
