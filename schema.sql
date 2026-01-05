CREATE DATABASE photosphere ;


use photosphere ;  



CREATE TABLE utilisateur (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username varchar (50) UNIQUE, 
    email varchar (100) UNIQUE, 
    password varchar (50) , 
    created_at datetime DEFAULT CURRENT_TIMESTAMP ,
    last_login datetime DEFAULT CURRENT_TIMESTAMP, 
    bio varchar (100) , 
    profile_picture varchar (100) , 
    role varchar (20) CHECK ( role ='basicUser' or role ='moderateur' or role ='admin' or role ='proUser' ),
    uploadCount int DEFAULT null, 
    level varchar(20) DEFAULT null , 
    isSuper boolean ,
    subscriptionStart date DEFAULT null, 
    subscriptionEnd date DEFAULT null 
) 

CREATE TABLE photo (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    photo_title VARCHAR(150),
    photo_description TEXT,
    photo_imageLink VARCHAR(255) NOT NULL,
    photo_fileSize INT,
    photo_dimensions VARCHAR(50),
    photo_state ENUM('draft','published','archived') DEFAULT 'draft',
    photo_viewCount INT DEFAULT 0,
    photo_publishedAt DATETIME NULL,
    photo_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    photo_updatedAt DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user)
);


CREATE TABLE album (
    album_id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    album_name VARCHAR(150) NOT NULL,
    album_description TEXT,
    album_isPublic BOOLEAN DEFAULT TRUE,
    album_photoCount INT DEFAULT 0,
    album_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    album_updatedAt DATETIME NULL,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user)
);


CREATE TABLE tag (
    tag_id INT AUTO_INCREMENT PRIMARY KEY,
    tag_name VARCHAR(100) NOT NULL UNIQUE,
    tag_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE comment (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    photo_id INT NOT NULL,
    comment_content TEXT NOT NULL,
    comment_isArchived BOOLEAN DEFAULT FALSE,
    comment_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comment_updatedAt DATETIME NULL,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user),
    FOREIGN KEY (photo_id) REFERENCES photo(photo_id)
);


CREATE TABLE ph_like (
    id_user INT NOT NULL,
    photo_id INT NOT NULL,
    like_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user, photo_id),
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user),
    FOREIGN KEY (photo_id) REFERENCES photo(photo_id)
);


INSERT INTO utilisateur (username, email, password, bio, profile_picture, role, uploadCount, level, isSuper, subscriptionStart, subscriptionEnd)
VALUES
('noha', 'noha@example.com', 'pass123', 'Photographe amateur', 'noha.jpg', 'basicUser', 5, 'beginner', FALSE, NULL, NULL),
('youssef', 'youssef@example.com', 'yous123', 'Pro en nature', 'youssef.jpg', 'proUser', 20, 'expert', TRUE, '2025-01-01', '2026-01-01'),
('sara', 'sara@example.com', 'sara123', 'Passionnée de portrait', 'sara.png', 'moderateur', 15, 'intermediate', FALSE, NULL, NULL),
('amine', 'amine@example.com', 'amin123', 'Photographe urbain', 'amine.png', 'admin', 50, 'expert', TRUE, '2025-06-01', '2026-06-01');

INSERT INTO photo (id_user, photo_title, photo_description, photo_imageLink, photo_fileSize, photo_dimensions, photo_state, photo_viewCount, photo_publishedAt)
VALUES
(1, 'Lever du soleil', 'Magnifique lever du soleil sur la plage', 'sunrise.jpg', 2048, '1920x1080', 'published', 120, '2026-01-01 08:00:00'),
(2, 'Forêt enchantée', 'Balade en forêt avec ambiance féerique', 'forest.jpg', 3072, '2048x1365', 'published', 95, '2026-01-02 09:30:00'),
(3, 'Portrait de femme', 'Portrait artistique en lumière naturelle', 'portrait.jpg', 1024, '1080x1350', 'draft', 0, NULL),
(1, 'Ville la nuit', 'Photographie de la ville illuminée', 'citynight.jpg', 4096, '2560x1440', 'archived', 45, '2025-12-20 20:00:00');

INSERT INTO album (id_user, album_name, album_description, album_isPublic, album_photoCount)
VALUES
(1, 'Vacances d ete', 'Photos de mes vacances a la mer', TRUE, 2),
(2, 'Nature et paysages', 'Collection de paysages naturels', TRUE, 1),
(3, 'Portraits', 'Mes portraits réalisés en studio', FALSE, 1);

INSERT INTO tag (tag_name)
VALUES
('nature'),
('portrait'),
('urbain'),
('paysage'),
('nocturne');

INSERT INTO comment (id_user, photo_id, comment_content, comment_isArchived)
VALUES
(2, 1, 'Superbe photo !', FALSE),
(3, 1, 'J adore les couleurs du ciel', FALSE),
(1, 2, 'Magnifique ambiance forestière', FALSE),
(3, 4, 'C est un style intéressant', TRUE);

INSERT INTO ph_like (id_user, photo_id)
VALUES
(1, 2),
(2, 1),
(3, 1),
(4, 1),
(2, 3);