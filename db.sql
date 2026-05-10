-- ============================================
-- SocialNet Database Setup
-- ============================================

-- Create the database
CREATE DATABASE IF NOT EXISTS socialnet
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE socialnet;

-- Create database user and grant privileges
CREATE USER IF NOT EXISTS 'socialnet_user'@'localhost' IDENTIFIED BY '123456';
-- In case the user already exists, let's also alter the password to ensure it is '123456'
ALTER USER 'socialnet_user'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON socialnet.* TO 'socialnet_user'@'localhost';
FLUSH PRIVILEGES;

-- Create the account table
CREATE TABLE IF NOT EXISTS account (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(50)  NOT NULL UNIQUE,
  fullname    VARCHAR(100) NOT NULL,
  password    VARCHAR(255) NOT NULL,
  description TEXT         DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Sample users (all passwords: password123)
-- ============================================
-- Passwords are hashed with password_hash('password123', PASSWORD_DEFAULT)

INSERT IGNORE INTO account (username, fullname, password, description) VALUES
  ('admin',   'Administrator',  '$2y$10$1zpY7M/XrERFM41h24NFKevHPbwhzeKUWqjke70LAAPVQ82APL80y', 'System administrator'),
  ('alice',   'Alice Nguyen',   '$2y$10$1zpY7M/XrERFM41h24NFKevHPbwhzeKUWqjke70LAAPVQ82APL80y', 'Hello! I am Alice.'),
  ('bob',     'Bob Tran',       '$2y$10$1zpY7M/XrERFM41h24NFKevHPbwhzeKUWqjke70LAAPVQ82APL80y', NULL);

-- ============================================
-- Create the friendship table
-- ============================================
CREATE TABLE IF NOT EXISTS friendship (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  status ENUM('pending', 'accepted') NOT NULL DEFAULT 'pending',
  UNIQUE KEY unique_friendship (sender_id, receiver_id),
  FOREIGN KEY (sender_id) REFERENCES account(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES account(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
