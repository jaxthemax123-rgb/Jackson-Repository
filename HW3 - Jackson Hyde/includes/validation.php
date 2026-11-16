<?php

function validateCardName(string $cardName): string
{
    $length = mb_strlen(trim($cardName));

    if ($length < 1 || $length > 100) {
        return 'Enter a card name using 1 to 100 characters.';
    }

    return '';
}

function validateCardType(string $cardType): string
{
    $allowedTypes = ['Retail', 'Food & Beverage', 'Travel', 'Entertainment'];

    if (!in_array($cardType, $allowedTypes, true)) {
        return 'Choose a valid card type.';
    }

    return '';
}

function validateCardValue(string $cardValue): string
{
    $value = filter_var($cardValue, FILTER_VALIDATE_FLOAT);

    if ($value === false || $value <= 0) {
        return 'Enter a card value greater than 0.';
    }

    return '';
}

function validatePointsRequired(string $pointsRequired): string
{
    $points = filter_var($pointsRequired, FILTER_VALIDATE_INT);

    if ($points === false || $points < 1) {
        return 'Enter points required as a whole number greater than 0.';
    }

    return '';
}

function validateCardImage(string $cardImage): string
{
    $allowedImages = ['amazon-gift-card.png', 'starbucks-gift-card.png', 'gift-card.png'];

    if (!in_array($cardImage, $allowedImages, true)) {
        return 'Choose a valid card image.';
    }

    return '';
}

function validateCardId($cardId): string
{
    if ($cardId === false || $cardId === null || $cardId < 1) {
        return 'Choose a valid gift card.';
    }

    return '';
}

function validateGiftCard(array $card): array
{
    $errors = [
        'card_name' => validateCardName($card['card_name']),
        'card_type' => validateCardType($card['card_type']),
        'card_value' => validateCardValue($card['card_value']),
        'points_required' => validatePointsRequired($card['points_required']),
        'card_image' => validateCardImage($card['card_image'])
    ];

    // Keep only the fields that have an error message.
    return array_filter($errors);
}
