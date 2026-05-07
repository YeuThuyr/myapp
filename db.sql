-- ============================================
-- SocialNet Database Setup
-- ============================================

-- Create the database
CREATE DATABASE IF NOT EXISTS socialnet
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE socialnet;

-- Create the account table
CREATE TABLE IF NOT EXISTS account (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(50)  NOT NULL UNIQUE,
  fullname    VARCHAR(100) NOT NULL,
  password    VARCHAR(255) NOT NULL,
  description TEXT         DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Safety: add 'description' column if missing
-- (for existing databases that lack this column)
-- ============================================
-- If you get "Duplicate column name" that is harmless.
-- ALTER TABLE account ADD COLUMN description TEXT DEFAULT NULL;

-- ============================================
-- Sample users (all passwords: password123)
-- ============================================
-- Passwords are hashed with password_hash('password123', PASSWORD_DEFAULT)

INSERT INTO account (username, fullname, password, description) VALUES
  ('admin',   'Administrator',  '$2y$10$1zpY7M/XrERFM41h24NFKevHPbwhzeKUWqjke70LAAPVQ82APL80y', 'System administrator'),
  ('alice',   'Alice Nguyen',   '$2y$10$1zpY7M/XrERFM41h24NFKevHPbwhzeKUWqjke70LAAPVQ82APL80y', 'Hello! I am Alice.'),
  ('bob',     'Bob Tran',       '$2y$10$1zpY7M/XrERFM41h24NFKevHPbwhzeKUWqjke70LAAPVQ82APL80y', NULL);
