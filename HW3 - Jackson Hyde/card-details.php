<?php
require_once 'includes/db.php';
require_once 'includes/validation.php';

$cardId = filter_input(INPUT_GET, 'card_id', FILTER_VALIDATE_INT);

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

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>

<main class="container">
  <a class="back-link" href="card-list.php">&larr; Return to Gift Card List</a>
  <p class="eyebrow">THE REWARDS COLLECTION</p>
  <h1>Gift Card Details</h1>

  <section class="panel detail-layout">
    <div class="card-art">
      <img src="images/<?php echo htmlspecialchars($card['card_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    <div>
      <h2><?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
      <dl>
        <div><dt>Card ID</dt><dd><?php echo $card['card_id']; ?></dd></div>
        <div><dt>Card Name</dt><dd><?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?></dd></div>
        <div><dt>Card Type</dt><dd><?php echo htmlspecialchars($card['card_type'], ENT_QUOTES, 'UTF-8'); ?></dd></div>
        <div><dt>Card Value</dt><dd>$<?php echo htmlspecialchars($card['card_value'], ENT_QUOTES, 'UTF-8'); ?> USD</dd></div>
        <div><dt>Points Required</dt><dd class="points"><?php echo htmlspecialchars($card['points_required'], ENT_QUOTES, 'UTF-8'); ?> <small>pts</small></dd></div>
      </dl>
      <div class="actions">
        <a class="button" href="card-update.php?card_id=<?php echo $card['card_id']; ?>">Update Gift Card</a>
        <a class="button secondary" href="card-delete.php?card_id=<?php echo $card['card_id']; ?>">Delete Gift Card</a>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
