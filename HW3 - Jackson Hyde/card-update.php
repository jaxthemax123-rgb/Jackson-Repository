<?php
require_once 'includes/db.php';
require_once 'includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardId = filter_input(INPUT_POST, 'card_id', FILTER_VALIDATE_INT);
} else {
    $cardId = filter_input(INPUT_GET, 'card_id', FILTER_VALIDATE_INT);
}

if (validateCardId($cardId) !== '') {
    http_response_code(400);
    exit('Choose a valid gift card.');
}

$stmt = $pdo->prepare('SELECT * FROM GIFTCARD WHERE card_id = :card_id');
$stmt->execute(['card_id' => $cardId]);
$card = $stmt->fetch();

if ($card === false) {
    http_response_code(404);
    exit('Gift card not found.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card = [
        'card_id' => $cardId,
        'card_name' => trim($_POST['card_name'] ?? ''),
        'card_type' => $_POST['card_type'] ?? '',
        'card_value' => trim($_POST['card_value'] ?? ''),
        'points_required' => trim($_POST['points_required'] ?? ''),
        'card_image' => $_POST['card_image'] ?? ''
    ];

    $errors = validateGiftCard($card);

    if ($errors === []) {
        $stmt = $pdo->prepare(
            'UPDATE GIFTCARD
             SET card_name = :card_name, card_type = :card_type, card_value = :card_value,
                 points_required = :points_required, card_image = :card_image
             WHERE card_id = :card_id'
        );
        $stmt->execute($card);

        header('Location: card-details.php?card_id=' . $cardId);
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
  <a class="back-link" href="card-details.php?card_id=<?php echo $cardId; ?>">&larr; Back to Gift Card Details</a>
  <p class="eyebrow">MANAGE THE COLLECTION</p>
  <h1>Update Gift Card</h1>
  <p class="intro">Edit the gift card information. All fields are required.</p>

  <?php if ($errors !== []) { ?>
    <ul class="errors">
      <?php foreach ($errors as $error) { ?>
        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
      <?php } ?>
    </ul>
  <?php } ?>

  <section class="panel form-layout">
    <form action="card-update.php" method="post">
      <input type="hidden" name="card_id" value="<?php echo $cardId; ?>">

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
        <button class="button" type="submit">Save Changes</button>
        <a class="button secondary" href="card-details.php?card_id=<?php echo $cardId; ?>">Cancel</a>
      </div>
    </form>
    <aside class="form-preview">
      <p class="eyebrow">CURRENT GIFT CARD</p>
      <img src="images/<?php echo htmlspecialchars($card['card_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?>">
    </aside>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
