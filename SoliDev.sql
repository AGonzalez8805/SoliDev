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
    title VARCHAR(255) NOT NULL,             -- Titre de l’article
    category VARCHAR(100) NOT NULL,          -- Catégorie (php, js, etc.)
    content LONGTEXT NOT NULL,               -- Contenu principal de l’article
    status ENUM('draft','published') DEFAULT 'draft', -- Statut de publication
    cover_image VARCHAR(255) DEFAULT NULL,   -- Chemin de l’image uploadée
    allow_comments TINYINT(1) DEFAULT 1,     -- Autorisation commentaires (1=oui, 0=non)
    featured TINYINT(1) DEFAULT 0,           -- Article vedette
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Date de mise à jour
);

ALTER TABLE blog
ADD COLUMN excerpt VARCHAR(500) DEFAULT NULL;





