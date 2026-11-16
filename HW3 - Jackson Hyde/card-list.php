<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$stmt = $pdo->query('SELECT card_id, card_name, card_type, points_required, card_image FROM GIFTCARD ORDER BY card_name');
$cards = $stmt->fetchAll();
?>

<main class="container">
  <div class="page-heading">
    <div>
      <p class="eyebrow">THE REWARDS COLLECTION</p>
      <h1>Gift Card List</h1>
      <p class="intro">Discover a little something worth looking forward to.</p>
    </div>
    <a class="button" href="card-add.php">Add Gift Card</a>
  </div>

  <section class="panel table-panel">
    <table>
      <caption>Available gift cards</caption>
      <thead>
        <tr>
          <th>Image</th>
          <th>Card Name</th>
          <th>Points Required</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cards as $card) { ?>
          <tr>
            <td>
              <a href="card-details.php?card_id=<?php echo $card['card_id']; ?>">
                <img class="card-thumbnail" src="images/<?php echo htmlspecialchars($card['card_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?>">
              </a>
            </td>
            <td>
              <a class="card-name" href="card-details.php?card_id=<?php echo $card['card_id']; ?>"><?php echo htmlspecialchars($card['card_name'], ENT_QUOTES, 'UTF-8'); ?></a>
              <span class="category"><?php echo htmlspecialchars($card['card_type'], ENT_QUOTES, 'UTF-8'); ?></span>
            </td>
            <td><span class="points"><?php echo htmlspecialchars($card['points_required'], ENT_QUOTES, 'UTF-8'); ?> <small>pts</small></span></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
