<?php $navRole = $_SESSION['role'] ?? ''; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="../index.php">Peer Tutoring Hub</a>
    <div class="navbar-nav">
<?php if ($navRole === ''): ?>
      <a class="nav-link" href="login.php">Log In</a>
      <a class="nav-link" href="register.php">Create Account</a>
<?php else: ?>
<?php if ($navRole === 'Student'): ?>
      <a class="nav-link" href="requests.php">My Requests</a>
      <a class="nav-link" href="request-form.php">Request a Session</a>
<?php endif; ?>
<?php if ($navRole === 'Administrator'): ?>
      <a class="nav-link" href="requests.php">All Requests</a>
      <a class="nav-link" href="admin-users.php">Manage Users</a>
<?php endif; ?>
      <a class="nav-link" href="profile.php">My Profile</a>
      <form method="post" action="logout.php" class="d-inline"><?= csrfField() ?><button class="btn btn-link nav-link">Log Out</button></form>
<?php endif; ?>
    </div>
  </div>
</nav>
