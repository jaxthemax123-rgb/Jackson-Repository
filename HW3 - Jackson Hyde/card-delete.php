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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM GIFTCARD WHERE card_id = :card_id');
    $stmt->execute(['card_id' => $cardId]);

    header('Location: card-list.php');
    exit;
}

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>

<main class="container">
  <a class="back-link" href="card-details.php?card_id=<?php echo $cardId; ?>">&larr; Back to Gift Card Details</a>
  <p class="eyebrow">MANAGE THE COLLECTION</p>
  <h1>Delete Gift Card</h1>
  <p class="intro">Are you sure you want to delete this gift card? This cannot be undone.</p>

  <section class="panel detail-layout">
    <div class="card-art">
      <img src="images/<?php echo htmlspecialchars($card['card_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    <div>
      <h2><?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
      <dl>
        <div><dt>Card ID</dt><dd><?php echo $card['card_id']; ?></dd></div>
        <div><dt>Card Type</dt><dd><?php echo htmlspecialchars($card['card_type'], ENT_QUOTES, 'UTF-8'); ?></dd></div>
        <div><dt>Points Required</dt><dd class="points"><?php echo htmlspecialchars($card['points_required'], ENT_QUOTES, 'UTF-8'); ?> <small>pts</small></dd></div>
      </dl>
      <form action="card-delete.php" method="post" class="actions">
        <input type="hidden" name="card_id" value="<?php echo $cardId; ?>">
        <button class="button" type="submit">Yes, Delete Gift Card</button>
        <a class="button secondary" href="card-details.php?card_id=<?php echo $cardId; ?>">Cancel</a>
      </form>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
