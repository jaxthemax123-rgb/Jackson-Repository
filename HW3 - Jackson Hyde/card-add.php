<?php
require_once 'includes/db.php';
require_once 'includes/validation.php';

$card = [
    'card_name' => '',
    'card_type' => '',
    'card_value' => '',
    'points_required' => '',
    'card_image' => ''
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card = [
        'card_name' => trim($_POST['card_name'] ?? ''),
        'card_type' => $_POST['card_type'] ?? '',
        'card_value' => trim($_POST['card_value'] ?? ''),
        'points_required' => trim($_POST['points_required'] ?? ''),
        'card_image' => $_POST['card_image'] ?? ''
    ];

    $errors = validateGiftCard($card);

    if ($errors === []) {
        $stmt = $pdo->prepare(
            'INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
             VALUES (:card_name, :card_type, :card_value, :points_required, :card_image)'
        );
        $stmt->execute($card);

        header('Location: card-list.php');
        exit;
    }

    http_response_code(400);
}

$cardTypes = ['Retail', 'Food & Beverage', 'Travel', 'Entertainment'];
$cardImages = ['amazon-gift-card.png', 'starbucks-gift-card.png', 'gift-card.png'];

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>

<main class="container">
  <a class="back-link" href="card-list.php">&larr; Back to Gift Card List</a>
  <p class="eyebrow">MANAGE THE COLLECTION</p>
  <h1>Add Gift Card</h1>
  <p class="intro">Enter the information for a new gift card. All fields are required.</p>

  <?php if ($errors !== []) { ?>
    <ul class="errors">
      <?php foreach ($errors as $error) { ?>
        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
      <?php } ?>
    </ul>
  <?php } ?>

  <section class="panel form-layout">
    <form action="card-add.php" method="post">
      <label for="card-name">Card Name</label>
      <input id="card-name" name="card_name" type="text" maxlength="100" value="<?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?>" required>

      <label for="card-type">Card Type</label>
      <select id="card-type" name="card_type" required>
        <option value="">Select a card type</option>
        <?php foreach ($cardTypes as $cardType) { ?>
          <option value="<?php echo htmlspecialchars($cardType, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $card['card_type'] === $cardType ? 'selected' : ''; ?>><?php echo htmlspecialchars($cardType, ENT_QUOTES, 'UTF-8'); ?></option>
        <?php } ?>
      </select>

      <div class="field-pair">
        <div>
          <label for="card-value">Card Value (USD)</label>
          <input id="card-value" name="card_value" type="number" min="0.01" step="0.01" value="<?php echo htmlspecialchars($card['card_value'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div>
          <label for="points">Points Required</label>
          <input id="points" name="points_required" type="number" min="1" step="1" value="<?php echo htmlspecialchars($card['points_required'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
      </div>

      <label for="card-image">Card Image</label>
      <select id="card-image" name="card_image" required>
        <option value="">Select a card image</option>
        <?php foreach ($cardImages as $cardImage) { ?>
          <option value="<?php echo $cardImage; ?>" <?php echo $card['card_image'] === $cardImage ? 'selected' : ''; ?>><?php echo $cardImage; ?></option>
        <?php } ?>
      </select>

      <div class="actions">
        <button class="button" type="submit">Add Gift Card</button>
        <a class="button secondary" href="card-list.php">Cancel</a>
      </div>
    </form>
    <aside class="form-preview">
      <p class="eyebrow">SAMPLE GIFT CARD</p>
      <img src="images/amazon-gift-card.png" alt="Amazon $10 gift card example">
      <p>Gift card artwork</p>
    </aside>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
