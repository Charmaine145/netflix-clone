-- SQL script for user authentication

-- Create a table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a sample user (hashed password for security)

-- Create a table for subscriptions
CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_type VARCHAR(50) NOT NULL,
    subscription_expiry DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, password) VALUES ('admin', '$2y$10$e0MYz1Q1Q1Q1Q1Q1Q1Q1QO1Q1Q1Q1Q1Q1Q1Q1Q1Q1Q1Q1Q1Q1');
-- Note: The password should be hashed using a secure hashing algorithm like bcrypt.
