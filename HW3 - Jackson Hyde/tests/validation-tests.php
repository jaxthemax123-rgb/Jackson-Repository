<?php
require_once '../includes/validation.php';

$validCard = [
    'card_name' => 'Amazon $10 Gift Card',
    'card_type' => 'Retail',
    'card_value' => '10.00',
    'points_required' => '100',
    'card_image' => 'amazon-gift-card.png'
];

$blankName = $validCard;
$blankName['card_name'] = '';

$alteredType = $validCard;
$alteredType['card_type'] = 'Free Stuff';

$zeroValue = $validCard;
$zeroValue['card_value'] = '0';

$negativeValue = $validCard;
$negativeValue['card_value'] = '-5';

$badPoints = $validCard;
$badPoints['points_required'] = '12.5';

$tests = [
    ['name' => 'Valid Gift Card data', 'input' => $validCard, 'expected' => []],
    ['name' => 'Blank Card Name', 'input' => $blankName, 'expected' => ['card_name' => 'Enter a card name using 1 to 100 characters.']],
    ['name' => 'Altered Card Type', 'input' => $alteredType, 'expected' => ['card_type' => 'Choose a valid card type.']],
    ['name' => 'Zero Card Value', 'input' => $zeroValue, 'expected' => ['card_value' => 'Enter a card value greater than 0.']],
    ['name' => 'Negative Card Value', 'input' => $negativeValue, 'expected' => ['card_value' => 'Enter a card value greater than 0.']],
    ['name' => 'Invalid Points Required', 'input' => $badPoints, 'expected' => ['points_required' => 'Enter points required as a whole number greater than 0.']]
];

foreach ($tests as $test) {
    $actual = validateGiftCard($test['input']);
    $passed = $actual === $test['expected'];

    echo '<p>' . htmlspecialchars($test['name'], ENT_QUOTES, 'UTF-8') . ': ';
    echo $passed ? 'PASS' : 'FAIL';
    echo '</p>';
}

$idTests = [
    ['name' => 'Valid Card ID', 'input' => filter_var('12', FILTER_VALIDATE_INT), 'expected' => ''],
    ['name' => 'Text Card ID', 'input' => filter_var('abc', FILTER_VALIDATE_INT), 'expected' => 'Choose a valid gift card.'],
    ['name' => 'Zero Card ID', 'input' => filter_var('0', FILTER_VALIDATE_INT), 'expected' => 'Choose a valid gift card.']
];

foreach ($idTests as $test) {
    $actual = validateCardId($test['input']);
    $passed = $actual === $test['expected'];

    echo '<p>' . htmlspecialchars($test['name'], ENT_QUOTES, 'UTF-8') . ': ';
    echo $passed ? 'PASS' : 'FAIL';
    echo '</p>';
}
