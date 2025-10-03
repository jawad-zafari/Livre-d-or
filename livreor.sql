-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS livreor;

-- Utilisation de la base de données
USE livreor;

-- Tableau pour stocker les utilisateurs
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE, -- Email unique pour chaque utilisateur
    password VARCHAR(255) NOT NULL -- Mot de passe crypté
);

-- Tableau pour stocker les commentaires
CREATE TABLE commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commentaire TEXT NOT NULL, -- Texte du commentaire
    id_utilisateur INT NOT NULL, -- ID de l'utilisateur qui a posté
    date DATETIME NOT NULL, -- Date et heure du commentaire
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) -- Clé étrangère vers utilisateurs
);