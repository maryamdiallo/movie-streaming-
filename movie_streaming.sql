-- StreamZone CIS 344 database. WARNING: importing resets the movie_streaming database.
-- Export/back up existing records first. Intended for local class-project setup.
DROP DATABASE IF EXISTS movie_streaming;
CREATE DATABASE movie_streaming CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE movie_streaming;
CREATE TABLE users (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 first_name VARCHAR(50) NOT NULL,
 last_name VARCHAR(50) NOT NULL DEFAULT '',
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE movies (
 movie_id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(100) NOT NULL,
 genre VARCHAR(50) NOT NULL,
 release_year INT NOT NULL,
 rating DECIMAL(3,1) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE subscriptions (
 subscription_id INT AUTO_INCREMENT PRIMARY KEY,
 plan_name VARCHAR(50) NOT NULL UNIQUE,
 price DECIMAL(6,2) NOT NULL,
 duration_months INT NOT NULL
) ENGINE=InnoDB;
CREATE TABLE user_subscriptions (
 user_subscription_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 subscription_id INT NOT NULL,
 start_date DATE NOT NULL,
 INDEX (user_id, start_date),
 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
 FOREIGN KEY (subscription_id) REFERENCES subscriptions(subscription_id)
) ENGINE=InnoDB;
CREATE TABLE watchlist (
 watchlist_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 movie_id INT NOT NULL,
 UNIQUE KEY unique_user_movie (user_id,movie_id),
 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
 FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE watch_history (
 history_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 movie_id INT NOT NULL,
 watched_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX (user_id,watched_at),
 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
 FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE
) ENGINE=InnoDB;
-- Original project's movie and subscription seed data.
INSERT INTO movies (title,genre,release_year,rating) VALUES
('Inception','Sci-Fi',2010,8.8),('The Dark Knight','Action',2008,9.0),
('How to lose a guy in 10 days','Romance',2003,6.6),('Toy Story','Animation',1995,8.3),
('Scream','Horror',1996,7.4);
INSERT INTO subscriptions (plan_name,price,duration_months) VALUES
('Basic',8.99,1),('Standard',13.99,1),('Premium',17.99,1);
-- Register through the website. Never seed plaintext demo passwords.
