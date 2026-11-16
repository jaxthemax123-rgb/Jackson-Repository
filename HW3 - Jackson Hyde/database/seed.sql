INSERT INTO ACCOUNT (username, password_hash, first_name, last_name, email, role, points_balance)
VALUES ('admin', '$2y$10$O9teZZOgYIm.rYI3GIos/e5eUGOSolaoO9NcwuX/zC.p5JqHUQZ5K', 'Admin', 'User', 'admin@example.com', 'Admin', 0);

INSERT INTO ACCOUNT (username, password_hash, first_name, last_name, email, role, points_balance)
VALUES ('jlee', '$2y$10$O9teZZOgYIm.rYI3GIos/e5eUGOSolaoO9NcwuX/zC.p5JqHUQZ5K', 'Jordan', 'Lee', 'jordan.lee@example.com', 'Customer', 1200);

INSERT INTO ACCOUNT (username, password_hash, first_name, last_name, email, role, points_balance)
VALUES ('apatel', '$2y$10$O9teZZOgYIm.rYI3GIos/e5eUGOSolaoO9NcwuX/zC.p5JqHUQZ5K', 'Avery', 'Patel', 'avery.patel@example.com', 'Customer', 850);

INSERT INTO ACCOUNT (username, password_hash, first_name, last_name, email, role, points_balance)
VALUES ('mlopez', '$2y$10$O9teZZOgYIm.rYI3GIos/e5eUGOSolaoO9NcwuX/zC.p5JqHUQZ5K', 'Maria', 'Lopez', 'maria.lopez@example.com', 'Customer', 2400);

INSERT INTO ACCOUNT (username, password_hash, first_name, last_name, email, role, points_balance)
VALUES ('snguyen', '$2y$10$O9teZZOgYIm.rYI3GIos/e5eUGOSolaoO9NcwuX/zC.p5JqHUQZ5K', 'Sam', 'Nguyen', 'sam.nguyen@example.com', 'Customer', 300);


INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Amazon $10 Gift Card', 'Retail', 10.00, 100, 'amazon-gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Amazon $25 Gift Card', 'Retail', 25.00, 250, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Amazon $50 Gift Card', 'Retail', 50.00, 500, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Starbucks $10 Gift Card', 'Food & Beverage', 10.00, 100, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Starbucks $25 Gift Card', 'Food & Beverage', 25.00, 250, 'starbucks-gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Starbucks $50 Gift Card', 'Food & Beverage', 50.00, 500, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Target $25 Gift Card', 'Retail', 25.00, 250, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Walmart $25 Gift Card', 'Retail', 25.00, 250, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Best Buy $50 Gift Card', 'Retail', 50.00, 500, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Nike $50 Gift Card', 'Retail', 50.00, 500, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Chipotle $15 Gift Card', 'Food & Beverage', 15.00, 150, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Pizza Hut $20 Gift Card', 'Food & Beverage', 20.00, 200, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Subway $10 Gift Card', 'Food & Beverage', 10.00, 100, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Panera Bread $25 Gift Card', 'Food & Beverage', 25.00, 250, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('AirAsia Flight $100 Voucher', 'Travel', 100.00, 1000, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Airbnb $100 Gift Card', 'Travel', 100.00, 1000, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Uber $25 Gift Card', 'Travel', 25.00, 250, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Netflix $30 Gift Card', 'Entertainment', 30.00, 300, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('Spotify $30 Gift Card', 'Entertainment', 30.00, 300, 'gift-card.png');

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
VALUES ('AMC Theatres $25 Gift Card', 'Entertainment', 25.00, 250, 'gift-card.png');
