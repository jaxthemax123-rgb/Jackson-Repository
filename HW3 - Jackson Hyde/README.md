# AirAsia Rewards Gift Card Management (HW3)

## Running the app

From this folder in Codespaces:

```bash
sqlite3 database/rewards.db < database/schema.sql
sqlite3 database/rewards.db < database/seed.sql
php -S 0.0.0.0:8000
```

The first two lines are only needed to rebuild the database. Then open `card-list.php` on port 8000.

## Use Case Traceability

| Use case | PHP page or pages | Database table | Test evidence |
| --- | --- | --- | --- |
| View a List of Gift Cards | card-list.php | GIFTCARD | Opened card-list.php and checked that all 20 seeded gift cards showed with their image, name, and points, and that each name linked to its details page. |
| View Details of One Gift Card | card-details.php | GIFTCARD | Opened card-details.php?card_id=12 and checked it showed the right card, then tried card_id=abc and card_id=999 and got an error message instead of a card. |
| Add a Gift Card | card-add.php | GIFTCARD | Submitted valid Gift Card data and confirmed the new record appeared on card-list.php. |
| Update a Gift Card | card-update.php | GIFTCARD | Changed a card's name, type, value, and points, saved it, and checked the details page showed the new values. |
| Delete a Gift Card | card-delete.php | GIFTCARD | Deleted a test card through the confirmation form and checked it was gone from card-list.php and its details page said not found. |
