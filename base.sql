-- Création de la base
CREATE DATABASE IF NOT EXISTS gestion_systeme;
USE gestion_systeme;

-- Table Utilisateurs (classe mère)
CREATE TABLE Utilisateurs (
    matricule INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    login VARCHAR(50) UNIQUE,
    mdp VARCHAR(100)
);

-- Table Administrateurs (hérite de Utilisateurs)
CREATE TABLE Administrateurs (
    id_admin INT PRIMARY KEY,
    FOREIGN KEY (id_admin) REFERENCES Utilisateurs(matricule) ON DELETE CASCADE
);

-- Table Agents (hérite de Utilisateurs)
CREATE TABLE Agents (
    id_agent INT PRIMARY KEY,
    FOREIGN KEY (id_agent) REFERENCES Utilisateurs(matricule) ON DELETE CASCADE
);

-- Table Produits
CREATE TABLE Produits (
    ALTER TABLE produits
MODIFY idP VARCHAR(20),

    quantite INT,
    designation VARCHAR(100),
    ALTER TABLE Produits ADD seuil INT;

);

-- Table Factures
CREATE TABLE Factures (
    num INT PRIMARY KEY AUTO_INCREMENT,
    fournisseur VARCHAR(100),
    prix FLOAT,
    date DATE,
    idP INT,
    FOREIGN KEY (idP) REFERENCES Produits(idP) ON DELETE SET NULL
);

-- Table Demandes
CREATE TABLE Demandes (
    numD INT PRIMARY KEY AUTO_INCREMENT,
    quantite INT,
    etat VARCHAR(50),
    description TEXT,
    utilisateur_id INT,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(matricule) ON DELETE CASCADE
);

-- Attribut manquant
-- Add 'nom' column to 'Produits' table
ALTER TABLE Produits
ADD nom VARCHAR(100);

-- Add 'idProduit' column to 'Demandes' table
ALTER TABLE Demandes
ADD idProduit INT,
ADD FOREIGN KEY (idProduit) REFERENCES Produits(idP) ON DELETE CASCADE;
