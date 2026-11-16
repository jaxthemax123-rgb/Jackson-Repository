CREATE TABLE IF NOT EXISTS ACCOUNT (
  account_id INTEGER PRIMARY KEY,
  username TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  first_name TEXT NOT NULL,
  last_name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  role TEXT NOT NULL DEFAULT 'Customer',
  points_balance INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS GIFTCARD (
  card_id INTEGER PRIMARY KEY,
  card_name TEXT NOT NULL,
  card_type TEXT NOT NULL,
  card_value REAL NOT NULL,
  points_required INTEGER NOT NULL,
  card_image TEXT NOT NULL
);
