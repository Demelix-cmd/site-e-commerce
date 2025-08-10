-- Table pour stocker les messages de contact
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
);
