<?php
/**
 * Home Page
 * URL: /socialnet/index.php
 *
 * Shows the logged-in user's info and lists other users, requests, and friends.
 */

require_once __DIR__ . '/includes/auth.php';
requireLogin();

require_once __DIR__ . '/includes/db.php';

$user_id = $_SESSION['user_id'];

$other_users = [];
$friend_requests = [];
$friends = [];

try {
    $sql = "
        SELECT 
            a.id, a.username, a.fullname,
            f1.status AS outgoing_status,
            f2.status AS incoming_status
        FROM account a
        LEFT JOIN friendship f1 ON f1.sender_id = ? AND f1.receiver_id = a.id
        LEFT JOIN friendship f2 ON f2.sender_id = a.id AND f2.receiver_id = ?
        WHERE a.id != ?
        ORDER BY a.id ASC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $status = 'none';
        if ($row['outgoing_status'] === 'accepted' || $row['incoming_status'] === 'accepted') {
            $status = 'accepted';
        } elseif ($row['incoming_status'] === 'pending') {
            $status = 'pending_incoming';
        } elseif ($row['outgoing_status'] === 'pending') {
            $status = 'pending_outgoing';
        }
        
        $row['rel_status'] = $status;
        
        if ($status === 'accepted') {
            $friends[] = $row;
        } elseif ($status === 'pending_incoming') {
            $friend_requests[] = $row;
        } else {
            $other_users[] = $row;
        }
    }
    $stmt->close();
} catch (Exception $e) {
    // Query failed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home — CS-ClassB</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <?php include __DIR__ . '/includes/menubar.php'; ?>

  <main class="page-content">

    <!-- Welcome banner -->
    <div class="home-hero">
      <div class="greeting-emoji">👋</div>
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
      <p>Signed in as <strong>@<?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
    </div>

    <!-- Other users list -->
    <div class="card card-wide" style="animation: fadeInUp 0.5s ease; margin-bottom: 24px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
          <h2 style="font-size:22px; font-weight:700; margin-bottom:4px;">Other Users</h2>
          <p style="color:var(--text-muted); font-size:14px;">Find people to connect with.</p>
        </div>
        <div style="background:var(--surface-hover); padding:8px 16px; border-radius:var(--radius-xl); font-size:14px; font-weight:600; color:var(--primary-light);">
          <?php echo count($other_users); ?> user<?php echo count($other_users) !== 1 ? 's' : ''; ?>
        </div>
      </div>

      <?php if (empty($other_users)): ?>
        <p style="text-align:center; color:var(--text-muted); padding:32px 0;">No other users to discover.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Full Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($other_users as $u): ?>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-cell-avatar">
                        <?php echo strtoupper(substr($u['username'], 0, 1)); ?>
                      </div>
                      <span class="user-cell-name">@<?php echo htmlspecialchars($u['username']); ?></span>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                  <td style="text-align:right;">
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                      <a href="profile.php?owner=<?php echo urlencode($u['username']); ?>" class="btn btn-secondary" style="padding:6px 12px; font-size:12px;">Profile</a>
                      <?php if ($u['rel_status'] === 'pending_outgoing'): ?>
                        <button class="btn btn-secondary" style="padding:6px 12px; font-size:12px; opacity:0.7; cursor:not-allowed;" disabled>Pending...</button>
                      <?php else: ?>
                        <form method="POST" action="friend_action.php" style="margin:0;">
                          <input type="hidden" name="action" value="add">
                          <input type="hidden" name="target_id" value="<?php echo $u['id']; ?>">
                          <button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:12px;">+ Add Friend</button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- Friend Requests list -->
    <div class="card card-wide" style="animation: fadeInUp 0.6s ease; margin-bottom: 24px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
          <h2 style="font-size:22px; font-weight:700; margin-bottom:4px;">Friend Requests</h2>
          <p style="color:var(--text-muted); font-size:14px;">People who want to connect with you.</p>
        </div>
        <div style="background:var(--surface-hover); padding:8px 16px; border-radius:var(--radius-xl); font-size:14px; font-weight:600; color:var(--primary-light);">
          <?php echo count($friend_requests); ?> request<?php echo count($friend_requests) !== 1 ? 's' : ''; ?>
        </div>
      </div>

      <?php if (empty($friend_requests)): ?>
        <p style="text-align:center; color:var(--text-muted); padding:32px 0;">No pending friend requests.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Full Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($friend_requests as $u): ?>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-cell-avatar">
                        <?php echo strtoupper(substr($u['username'], 0, 1)); ?>
                      </div>
                      <span class="user-cell-name">@<?php echo htmlspecialchars($u['username']); ?></span>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                  <td style="text-align:right;">
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                      <a href="profile.php?owner=<?php echo urlencode($u['username']); ?>" class="btn btn-secondary" style="padding:6px 12px; font-size:12px;">Profile</a>
                      <form method="POST" action="friend_action.php" style="margin:0;">
                        <input type="hidden" name="action" value="accept">
                        <input type="hidden" name="sender_id" value="<?php echo $u['id']; ?>">
                        <button type="submit" class="btn btn-accent" style="padding:6px 12px; font-size:12px;">✓ Accept</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- Friends list -->
    <div class="card card-wide" style="animation: fadeInUp 0.7s ease;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
          <h2 style="font-size:22px; font-weight:700; margin-bottom:4px;">Friend(s)</h2>
          <p style="color:var(--text-muted); font-size:14px;">Your connected friends.</p>
        </div>
        <div style="background:var(--surface-hover); padding:8px 16px; border-radius:var(--radius-xl); font-size:14px; font-weight:600; color:var(--primary-light);">
          <?php echo count($friends); ?> friend<?php echo count($friends) !== 1 ? 's' : ''; ?>
        </div>
      </div>

      <?php if (empty($friends)): ?>
        <p style="text-align:center; color:var(--text-muted); padding:32px 0;">You have no friends yet. Send some requests!</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Full Name</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($friends as $u): ?>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-cell-avatar" style="background:var(--accent); color:white;">
                        <?php echo strtoupper(substr($u['username'], 0, 1)); ?>
                      </div>
                      <span class="user-cell-name">@<?php echo htmlspecialchars($u['username']); ?></span>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                  <td style="text-align:right;">
                    <a href="profile.php?owner=<?php echo urlencode($u['username']); ?>"
                       class="btn btn-secondary" style="padding:6px 12px; font-size:12px;">
                      View Profile →
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

  </main>

</body>
</html>
