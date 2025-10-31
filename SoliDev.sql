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
    users_id INT NOT NULL,
    type ENUM('snippet','comment','project','like','other') NOT NULL,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(users_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS snippets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(500) NOT NULL,
    language VARCHAR(50) NOT NULL,
    category VARCHAR(100) NOT NULL,
    code LONGTEXT NOT NULL,
    usage_example LONGTEXT DEFAULT NULL,
    tags VARCHAR(200) DEFAULT NULL,
    visibility ENUM('public','private') DEFAULT 'public',
    allow_comments TINYINT(1) DEFAULT 1,
    allow_fork TINYINT(1) DEFAULT 1,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_snippet_author FOREIGN KEY (author_id) REFERENCES users(users_id) ON DELETE CASCADE,
    INDEX idx_author (author_id),
    INDEX idx_language (language),
    INDEX idx_category (category),
    INDEX idx_visibility (visibility),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favorites (
    users_id INT NOT NULL,
    snippet_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (users_id, snippet_id),
    FOREIGN KEY (users_id) REFERENCES users(users_id) ON DELETE CASCADE,
    FOREIGN KEY (snippet_id) REFERENCES snippets(id) ON DELETE CASCADE
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    short_description VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('planning','active','seeking','completed') DEFAULT 'planning',
    technologies JSON DEFAULT NULL,
    team_size ENUM('solo','small','medium','large') DEFAULT NULL,
    looking_for TEXT DEFAULT NULL,
    repository_url VARCHAR(255) DEFAULT NULL,
    demo_url VARCHAR(255) DEFAULT NULL,
    documentation_url VARCHAR(255) DEFAULT NULL,
    cover_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(users_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index utiles
CREATE INDEX idx_owner ON projects(owner_id);
CREATE INDEX idx_status ON projects(status);
CREATE INDEX idx_created ON projects(created_at);

CREATE TABLE project_collaborators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    users_id INT NOT NULL,
    role VARCHAR(50) DEFAULT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (users_id) REFERENCES users(users_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index pour accélérer les requêtes de comptage
CREATE INDEX idx_project ON project_collaborators(project_id);
CREATE INDEX idx_user ON project_collaborators(users_id);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blog(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(users_id) ON DELETE CASCADE,
    INDEX idx_blog (blog_id),
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

RENAME TABLE comments TO commentsBlog;

ALTER TABLE users ADD COLUMN photo VARCHAR(255) NULL AFTER email;
ALTER TABLE users
ADD COLUMN github_url VARCHAR(255) DEFAULT NULL,
ADD COLUMN linkedin_url VARCHAR(255) DEFAULT NULL,
ADD COLUMN website_url VARCHAR(255) DEFAULT NULL;

ALTER TABLE users 
ADD COLUMN bio TEXT DEFAULT NULL,
ADD COLUMN skills VARCHAR(255) DEFAULT NULL;

ALTER TABLE users 
ADD COLUMN email_verification_token VARCHAR(255) DEFAULT NULL,
ADD COLUMN is_email_verified TINYINT(1) DEFAULT 0;


ALTER TABLE user_activities DROP FOREIGN KEY user_activities_ibfk_1;


ALTER TABLE user_activities 
MODIFY COLUMN type ENUM('snippet','comment','project','blog','forum_post','like','follow','other') NOT NULL;


ALTER TABLE user_activities 
ADD CONSTRAINT user_activities_ibfk_1 
FOREIGN KEY (users_id) REFERENCES users(users_id) ON DELETE CASCADE;


CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(users_id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE users 
ADD COLUMN theme VARCHAR(10) DEFAULT 'light',
ADD COLUMN notify_comments BOOLEAN DEFAULT TRUE,
ADD COLUMN notify_likes BOOLEAN DEFAULT TRUE,


ALTER TABLE users
ADD COLUMN notify_forum BOOLEAN DEFAULT TRUE,
ADD COLUMN notify_blog BOOLEAN DEFAULT TRUE,
ADD COLUMN notify_projet BOOLEAN DEFAULT TRUE,
ADD COLUMN notify_snippet BOOLEAN DEFAULT TRUE;

ALTER TABLE users
ADD COLUMN profile_email BOOLEAN DEFAULT TRUE,
ADD COLUMN profile_description BOOLEAN DEFAULT TRUE,
ADD COLUMN profile_competence BOOLEAN DEFAULT TRUE,
ADD COLUMN profile_sociaux BOOLEAN DEFAULT TRUE;

ALTER TABLE users 
ADD INDEX idx_email (email),
ADD INDEX idx_role (role),
ADD INDEX idx_theme (theme);

ALTER TABLE blog 
ADD INDEX idx_status (status),
ADD INDEX idx_category (category),
ADD INDEX idx_featured (featured),
ADD INDEX idx_created (created_at);

ALTER TABLE user_activities 
ADD INDEX idx_user (users_id),
ADD INDEX idx_type (type),
ADD INDEX idx_created (created_at);


SELECT 'Structure de user_activities' AS Info;
DESCRIBE user_activities;

SELECT 'Structure de notifications' AS Info;
DESCRIBE notifications;

SELECT 'Colonnes ajoutées à users' AS Info;
SELECT 
    COLUMN_NAME, 
    COLUMN_TYPE, 
    COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'SoliDev' 
    AND TABLE_NAME = 'users' 
    AND COLUMN_NAME IN (
        'theme', 'language', 'timezone', 
        'profile_public', 'show_online_status', 'allow_search_indexing',
        'notify_comments', 'notify_likes', 'notify_followers', 'notify_newsletter'
    );

SELECT 'Clés étrangères' AS Info;
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'SoliDev'
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Insertion de données de test dans la table notifications
INSERT INTO notifications (user_id, type, message, link, is_read, created_at) 
VALUES
(4, 'like', 'Marie Dupont a aimé votre snippet "Validation de formulaire"', '/snippets/123', FALSE, DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
(4, 'comment', 'Jean Martin a commenté votre projet "API REST"', '/projects/456', FALSE, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(4, 'featured', 'Votre article a été mis en avant sur la page d\'accueil', '/blog/789', TRUE, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(4, 'follow', 'Sophie Leroy a commencé à vous suivre', '/users/42', TRUE, DATE_SUB(NOW(), INTERVAL 1 DAY));



