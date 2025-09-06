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

