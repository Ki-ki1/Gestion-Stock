-- 1. Insert into Utilisateurs
INSERT INTO Utilisateurs (nom, prenom, login, mdp)
VALUES ('Toumi', 'Malek', 'malek_admin', 'admin123');

-- 2. Get the last inserted matricule (if you're using MySQL CLI)
SELECT LAST_INSERT_ID();  -- Let’s say it returns 1

-- 3. Insert into Administrateurs
INSERT INTO Administrateurs (id_admin)
VALUES (1);
INSERT INTO Utilisateurs (nom, prenom, login, mdp)
VALUES ('Ben Ali', 'Sami', 'sami_user', 'user123');

INSERT INTO Produits (nom, quantite, designation)
VALUES ('Clavier mécanique', 50, 'Clavier rétroéclairé avec switches rouges');
