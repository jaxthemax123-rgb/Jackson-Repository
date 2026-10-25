<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | AirAsia Rewards</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header"><div class="header-inner"><a class="brand" href="login.php"><strong>airasia</strong><span>REWARDS</span></a></div></header>
<main id="main" class="login-main">
<section class="panel login-panel" aria-labelledby="login-heading">
<p class="eyebrow">AIRASIA REWARDS</p><h1 id="login-heading">Welcome back</h1>
<p class="intro">Log in to explore the gift card collection.</p>
<!-- Static navigation only. Omit credential names so demo credentials are not sent in the URL. -->
<form action="card-list.php" method="get">
<label for="username">Username</label><input id="username" type="text" autocomplete="username" required>
<label for="password">Password</label><input id="password" type="password" autocomplete="current-password" required>
<button class="button full-width" type="submit">Login</button>
</form><p class="note">Demo only. Use made-up credentials; no authentication takes place.</p></section>
</main>
<footer>AirAsia Rewards &middot; HW2 student prototype</footer>
</body>
</html>
