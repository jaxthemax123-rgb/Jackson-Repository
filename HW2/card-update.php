<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Gift Card | AirAsia Rewards</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="site-header"><div class="header-inner"><a class="brand" href="card-list.php"><strong>airasia</strong><span>REWARDS</span></a><nav><a href="card-list.php">Gift cards</a><a href="login.php">Log out</a></nav></div></header>
<main id="main" class="container">
<a class="back-link" href="card-list.php">&larr; Back to Gift Card List</a><p class="eyebrow">MANAGE THE COLLECTION</p><h1>Update Gift Card</h1><p class="intro">Edit the sample gift card information. All fields are required.</p>
<section class="panel form-layout"><form action="card-list.php" method="get">
<label for="card-name">Card Name</label><input id="card-name" name="cardName" type="text" maxlength="100" value="Amazon $10 Gift Card" required>
<label for="card-type">Card Type</label><select id="card-type" name="cardType" required><option value="">Select a card type</option><option value="Retail" selected>Retail</option><option value="Food &amp; Beverage">Food &amp; Beverage</option><option value="Travel">Travel</option><option value="Entertainment">Entertainment</option></select>
<div class="field-pair"><div><label for="card-value">Card Value (USD)</label><input id="card-value" name="cardValue" type="number" min="0.01" step="0.01" value="10.00" required></div><div><label for="points">Points Required</label><input id="points" name="points" type="number" min="1" step="1" value="100" required></div></div>
<div class="actions"><button class="button" type="submit">Save Changes</button><a class="button secondary" href="card-list.php">Cancel</a></div><p class="note">Submitting returns to the list. Changes are not saved in this prototype.</p>
</form><div class="form-preview"><p class="eyebrow">CURRENT GIFT CARD</p><img src="images/amazon-gift-card.png" alt="Amazon $10 gift card example"><p>Amazon $10 Gift Card</p><p class="note">The current image is shown for reference.</p></div></section>
</main>
<footer>AirAsia Rewards &middot; HW2 student prototype</footer>
</body>
</html>
