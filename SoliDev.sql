CREATE DATABASE SoliDev;

USE SoliDev;

CREATE TABLE users(
    users_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    email VARCHAR (255) NOT NULL UNIQUE,
    password VARCHAR (255) NOT NULL,
    role ENUM('admin', 'utilisateur') DEFAULT 'utilisateur',
    registrationDate DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE blog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id int NOT NULL,
    title VARCHAR(255) NOT NULL,            
    category VARCHAR(100) NOT NULL,          
    content LONGTEXT NOT NULL,    
    status ENUM('draft','published') DEFAULT 'draft',
    cover_image VARCHAR(255) DEFAULT NULL,
    allow_comments TINYINT(1) DEFAULT 1,    
    featured TINYINT(1) DEFAULT 0,        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    excerpt varchar(500) DEFAULT NULL,
    CONSTRAINT blog_ibfk_1 FOREIGN KEY (author_id) REFERENCES users(users_id) ON DELETE CASCADE
);

CREATE TABLE user_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('snippet','comment','project','like','other') NOT NULL,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(users_id) ON DELETE CASCADE
);

ALTER TABLE users ADD COLUMN photo VARCHAR(255) NULL AFTER email;
ALTER TABLE users
ADD COLUMN github_url VARCHAR(255) DEFAULT NULL,
ADD COLUMN linkedin_url VARCHAR(255) DEFAULT NULL,
ADD COLUMN website_url VARCHAR(255) DEFAULT NULL;

ALTER TABLE users 
ADD COLUMN bio TEXT DEFAULT NULL,
ADD COLUMN skills VARCHAR(255) DEFAULT NULL;







