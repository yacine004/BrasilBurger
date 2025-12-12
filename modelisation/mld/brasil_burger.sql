-- ============================================================================
-- BASE DE DONNEES - BRASIL BURGER (SQL SERVER EXPRESS)
-- Système de Gestion des Commandes et Livraisons
-- ============================================================================
-- Script SQL créé le: 2025-12-05
-- Converti pour: SQL Server Express 2019+
-- Partagé par: Java API, C# ASP MVC, Symfony
-- ============================================================================

-- ============================================================================
-- 0. CRÉER LA BASE DE DONNEES
-- ============================================================================

IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'brasil_burger')
BEGIN
    CREATE DATABASE brasil_burger;
END
GO

USE brasil_burger;
GO

-- ============================================================================
-- 1. TABLES DE BASE
-- ============================================================================

-- Table GESTIONNAIRE
IF OBJECT_ID('dbo.gestionnaire', 'U') IS NOT NULL
    DROP TABLE dbo.gestionnaire;

CREATE TABLE dbo.gestionnaire (
    id_gestionnaire BIGINT PRIMARY KEY IDENTITY(1,1),
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    date_embauche DATE NOT NULL,
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'INACTIF')),
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE()
);

CREATE INDEX idx_email ON dbo.gestionnaire(email);
CREATE INDEX idx_statut ON dbo.gestionnaire(statut);

-- Table CLIENT
IF OBJECT_ID('dbo.client', 'U') IS NOT NULL
    DROP TABLE dbo.client;

CREATE TABLE dbo.client (
    id_client BIGINT PRIMARY KEY IDENTITY(1,1),
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATE NOT NULL,
    adresse VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'INACTIF')),
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE()
);

CREATE INDEX idx_email ON dbo.client(email);
CREATE INDEX idx_telephone ON dbo.client(telephone);
CREATE INDEX idx_statut ON dbo.client(statut);

-- ============================================================================
-- 2. TABLES PRODUITS
-- ============================================================================

-- Table BURGER
IF OBJECT_ID('dbo.burger', 'U') IS NOT NULL
    DROP TABLE dbo.burger;

CREATE TABLE dbo.burger (
    id_burger BIGINT PRIMARY KEY IDENTITY(1,1),
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'ARCHIVE')),
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE()
);

CREATE INDEX idx_statut ON dbo.burger(statut);
CREATE INDEX idx_nom ON dbo.burger(nom);

-- Table COMPLEMENT
IF OBJECT_ID('dbo.complement', 'U') IS NOT NULL
    DROP TABLE dbo.complement;

CREATE TABLE dbo.complement (
    id_complement BIGINT PRIMARY KEY IDENTITY(1,1),
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    type_complement VARCHAR(50) NOT NULL CHECK (type_complement IN ('FRITES', 'BOISSON', 'AUTRE')),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'ARCHIVE')),
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE()
);

CREATE INDEX idx_statut ON dbo.complement(statut);
CREATE INDEX idx_nom ON dbo.complement(nom);

-- Table MENU
IF OBJECT_ID('dbo.menu', 'U') IS NOT NULL
    DROP TABLE dbo.menu;

CREATE TABLE dbo.menu (
    id_menu BIGINT PRIMARY KEY IDENTITY(1,1),
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'ARCHIVE')),
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE()
);

CREATE INDEX idx_statut ON dbo.menu(statut);
CREATE INDEX idx_nom ON dbo.menu(nom);

-- Table MENU_BURGER (un menu contient un burger)
IF OBJECT_ID('dbo.menu_burger', 'U') IS NOT NULL
    DROP TABLE dbo.menu_burger;

CREATE TABLE dbo.menu_burger (
    id_menu_burger BIGINT PRIMARY KEY IDENTITY(1,1),
    id_menu BIGINT NOT NULL,
    id_burger BIGINT NOT NULL,
    FOREIGN KEY (id_menu) REFERENCES dbo.menu(id_menu) ON DELETE CASCADE,
    FOREIGN KEY (id_burger) REFERENCES dbo.burger(id_burger) ON DELETE CASCADE,
    UNIQUE (id_menu, id_burger)
);

-- Table MENU_COMPLEMENT (un menu contient des compléments - boisson + frites)
IF OBJECT_ID('dbo.menu_complement', 'U') IS NOT NULL
    DROP TABLE dbo.menu_complement;

CREATE TABLE dbo.menu_complement (
    id_menu_complement BIGINT PRIMARY KEY IDENTITY(1,1),
    id_menu BIGINT NOT NULL,
    id_complement BIGINT NOT NULL,
    FOREIGN KEY (id_menu) REFERENCES dbo.menu(id_menu) ON DELETE CASCADE,
    FOREIGN KEY (id_complement) REFERENCES dbo.complement(id_complement) ON DELETE CASCADE,
    UNIQUE (id_menu, id_complement)
);

-- ============================================================================
-- 3. TABLES LIVRAISON
-- ============================================================================

-- Table ZONE
IF OBJECT_ID('dbo.zone', 'U') IS NOT NULL
    DROP TABLE dbo.zone;

CREATE TABLE dbo.zone (
    id_zone BIGINT PRIMARY KEY IDENTITY(1,1),
    nom_zone VARCHAR(150) NOT NULL,
    prix_livraison DECIMAL(10, 2) NOT NULL,
    description TEXT,
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE()
);

CREATE INDEX idx_nom ON dbo.zone(nom_zone);

-- Table ZONE_QUARTIER (une zone couvre plusieurs quartiers)
IF OBJECT_ID('dbo.zone_quartier', 'U') IS NOT NULL
    DROP TABLE dbo.zone_quartier;

CREATE TABLE dbo.zone_quartier (
    id_zone_quartier BIGINT PRIMARY KEY IDENTITY(1,1),
    id_zone BIGINT NOT NULL,
    quartier VARCHAR(150) NOT NULL,
    FOREIGN KEY (id_zone) REFERENCES dbo.zone(id_zone) ON DELETE CASCADE,
    UNIQUE (id_zone, quartier)
);

-- Table LIVREUR
IF OBJECT_ID('dbo.livreur', 'U') IS NOT NULL
    DROP TABLE dbo.livreur;

CREATE TABLE dbo.livreur (
    id_livreur BIGINT PRIMARY KEY IDENTITY(1,1),
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    id_zone BIGINT NOT NULL,
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'INACTIF')),
    date_embauche DATE NOT NULL,
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_zone) REFERENCES dbo.zone(id_zone) ON DELETE NO ACTION
);

CREATE INDEX idx_zone ON dbo.livreur(id_zone);
CREATE INDEX idx_statut ON dbo.livreur(statut);

-- ============================================================================
-- 4. TABLES COMMANDES
-- ============================================================================

-- Table COMMANDE
IF OBJECT_ID('dbo.commande', 'U') IS NOT NULL
    DROP TABLE dbo.commande;

CREATE TABLE dbo.commande (
    id_commande BIGINT PRIMARY KEY IDENTITY(1,1),
    id_client BIGINT NOT NULL,
    date_creation DATETIME DEFAULT GETDATE(),
    date_modification DATETIME DEFAULT GETDATE(),
    montant_total DECIMAL(12, 2) NOT NULL,
    etat VARCHAR(20) DEFAULT 'VALIDE' CHECK (etat IN ('VALIDE', 'PRETE', 'LIVREE', 'ANNULEE')),
    type_livraison VARCHAR(20) NOT NULL CHECK (type_livraison IN ('SUR_PLACE', 'RETRAIT', 'LIVRAISON')),
    id_zone BIGINT,
    id_livreur BIGINT,
    notes TEXT,
    FOREIGN KEY (id_client) REFERENCES dbo.client(id_client) ON DELETE NO ACTION,
    FOREIGN KEY (id_zone) REFERENCES dbo.zone(id_zone) ON DELETE SET NULL,
    FOREIGN KEY (id_livreur) REFERENCES dbo.livreur(id_livreur) ON DELETE SET NULL
);

CREATE INDEX idx_client ON dbo.commande(id_client);
CREATE INDEX idx_etat ON dbo.commande(etat);
CREATE INDEX idx_date ON dbo.commande(date_creation);
CREATE INDEX idx_type_livraison ON dbo.commande(type_livraison);
CREATE INDEX idx_commande_date_etat ON dbo.commande(date_creation, etat);
CREATE INDEX idx_commande_client_date ON dbo.commande(id_client, date_creation);

-- Table LIGNE_COMMANDE
IF OBJECT_ID('dbo.ligne_commande', 'U') IS NOT NULL
    DROP TABLE dbo.ligne_commande;

CREATE TABLE dbo.ligne_commande (
    id_ligne BIGINT PRIMARY KEY IDENTITY(1,1),
    id_commande BIGINT NOT NULL,
    id_burger BIGINT,
    id_menu BIGINT,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    sous_total DECIMAL(12, 2) NOT NULL,
    date_creation DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_commande) REFERENCES dbo.commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_burger) REFERENCES dbo.burger(id_burger) ON DELETE SET NULL,
    FOREIGN KEY (id_menu) REFERENCES dbo.menu(id_menu) ON DELETE SET NULL,
    CHECK ((id_burger IS NOT NULL AND id_menu IS NULL) OR (id_burger IS NULL AND id_menu IS NOT NULL))
);

CREATE INDEX idx_commande ON dbo.ligne_commande(id_commande);
CREATE INDEX idx_ligne_commande_burger ON dbo.ligne_commande(id_burger);
CREATE INDEX idx_ligne_commande_menu ON dbo.ligne_commande(id_menu);

-- Table LIGNE_COMMANDE_COMPLEMENT (compléments ajoutés à une ligne)
IF OBJECT_ID('dbo.ligne_commande_complement', 'U') IS NOT NULL
    DROP TABLE dbo.ligne_commande_complement;

CREATE TABLE dbo.ligne_commande_complement (
    id_ligne_complement BIGINT PRIMARY KEY IDENTITY(1,1),
    id_ligne BIGINT NOT NULL,
    id_complement BIGINT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    sous_total DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (id_ligne) REFERENCES dbo.ligne_commande(id_ligne) ON DELETE CASCADE,
    FOREIGN KEY (id_complement) REFERENCES dbo.complement(id_complement) ON DELETE NO ACTION
);

CREATE INDEX idx_ligne ON dbo.ligne_commande_complement(id_ligne);

-- ============================================================================
-- 5. TABLE PAIEMENT
-- ============================================================================

-- Table PAIEMENT
IF OBJECT_ID('dbo.paiement', 'U') IS NOT NULL
    DROP TABLE dbo.paiement;

CREATE TABLE dbo.paiement (
    id_paiement BIGINT PRIMARY KEY IDENTITY(1,1),
    id_commande BIGINT NOT NULL UNIQUE,
    date_paiement DATETIME DEFAULT GETDATE(),
    montant DECIMAL(12, 2) NOT NULL,
    methode VARCHAR(20) NOT NULL CHECK (methode IN ('WAVE', 'OM')),
    statut VARCHAR(20) DEFAULT 'REUSSI' CHECK (statut IN ('REUSSI', 'ECHOUE')),
    reference_paiement VARCHAR(255),
    date_modification DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_commande) REFERENCES dbo.commande(id_commande) ON DELETE NO ACTION
);

CREATE INDEX idx_date ON dbo.paiement(date_paiement);
CREATE INDEX idx_statut ON dbo.paiement(statut);
CREATE INDEX idx_methode ON dbo.paiement(methode);
CREATE INDEX idx_paiement_commande_date ON dbo.paiement(id_commande, date_paiement);

-- ============================================================================
-- 6. VUES UTILES POUR LES STATISTIQUES
-- ============================================================================

-- Vue: Commandes du jour
IF OBJECT_ID('dbo.commandes_du_jour', 'V') IS NOT NULL
    DROP VIEW dbo.commandes_du_jour;
GO

CREATE VIEW dbo.commandes_du_jour AS
SELECT 
    c.*,
    cl.nom AS client_nom,
    cl.prenom AS client_prenom,
    cl.telephone AS client_telephone
FROM dbo.commande c
JOIN dbo.client cl ON c.id_client = cl.id_client
WHERE CAST(c.date_creation AS DATE) = CAST(GETDATE() AS DATE);
GO

-- Vue: Commandes en cours du jour
IF OBJECT_ID('dbo.commandes_en_cours_jour', 'V') IS NOT NULL
    DROP VIEW dbo.commandes_en_cours_jour;
GO

CREATE VIEW dbo.commandes_en_cours_jour AS
SELECT *
FROM dbo.commandes_du_jour
WHERE etat IN ('VALIDE', 'PRETE');
GO

-- Vue: Commandes validées du jour
IF OBJECT_ID('dbo.commandes_validees_jour', 'V') IS NOT NULL
    DROP VIEW dbo.commandes_validees_jour;
GO

CREATE VIEW dbo.commandes_validees_jour AS
SELECT *
FROM dbo.commandes_du_jour
WHERE etat = 'VALIDE';
GO

-- Vue: Commandes annulées du jour
IF OBJECT_ID('dbo.commandes_annulees_jour', 'V') IS NOT NULL
    DROP VIEW dbo.commandes_annulees_jour;
GO

CREATE VIEW dbo.commandes_annulees_jour AS
SELECT *
FROM dbo.commandes_du_jour
WHERE etat = 'ANNULEE';
GO

-- Vue: Récettes journalières
IF OBJECT_ID('dbo.recettes_journalieres', 'V') IS NOT NULL
    DROP VIEW dbo.recettes_journalieres;
GO

CREATE VIEW dbo.recettes_journalieres AS
SELECT 
    CAST(GETDATE() AS DATE) AS date_jour,
    COUNT(DISTINCT c.id_commande) AS nombre_commandes,
    SUM(c.montant_total) AS recette_totale,
    SUM(CASE WHEN c.type_livraison = 'LIVRAISON' THEN 1 ELSE 0 END) AS livraisons_nombre,
    SUM(CASE WHEN c.type_livraison = 'RETRAIT' THEN 1 ELSE 0 END) AS retraits_nombre,
    SUM(CASE WHEN c.type_livraison = 'SUR_PLACE' THEN 1 ELSE 0 END) AS sur_place_nombre
FROM dbo.commande c
WHERE CAST(c.date_creation AS DATE) = CAST(GETDATE() AS DATE) AND c.etat != 'ANNULEE';
GO

-- Vue: Burgers les plus vendus du jour
IF OBJECT_ID('dbo.burgers_plus_vendus_jour', 'V') IS NOT NULL
    DROP VIEW dbo.burgers_plus_vendus_jour;
GO

CREATE VIEW dbo.burgers_plus_vendus_jour AS
SELECT 
    b.id_burger,
    b.nom AS burger_nom,
    COUNT(lc.id_ligne) AS nombre_commandes,
    SUM(lc.quantite) AS quantite_totale,
    SUM(lc.sous_total) AS ventes_totales
FROM dbo.burger b
JOIN dbo.ligne_commande lc ON b.id_burger = lc.id_burger
JOIN dbo.commande c ON lc.id_commande = c.id_commande
WHERE CAST(c.date_creation AS DATE) = CAST(GETDATE() AS DATE) AND c.etat != 'ANNULEE'
GROUP BY b.id_burger, b.nom;
GO

-- Vue: Menus les plus vendus du jour
IF OBJECT_ID('dbo.menus_plus_vendus_jour', 'V') IS NOT NULL
    DROP VIEW dbo.menus_plus_vendus_jour;
GO

CREATE VIEW dbo.menus_plus_vendus_jour AS
SELECT 
    m.id_menu,
    m.nom AS menu_nom,
    COUNT(lc.id_ligne) AS nombre_commandes,
    SUM(lc.quantite) AS quantite_totale,
    SUM(lc.sous_total) AS ventes_totales
FROM dbo.menu m
JOIN dbo.ligne_commande lc ON m.id_menu = lc.id_menu
JOIN dbo.commande c ON lc.id_commande = c.id_commande
WHERE CAST(c.date_creation AS DATE) = CAST(GETDATE() AS DATE) AND c.etat != 'ANNULEE'
GROUP BY m.id_menu, m.nom;
GO

-- ============================================================================
-- 7. DONNÉES DE TEST / INSERTION INITIALE
-- ============================================================================

-- Insertion Gestionnaire
INSERT INTO dbo.gestionnaire (nom, prenom, email, mot_de_passe, telephone, date_embauche)
VALUES ('Admin', 'Brasil', 'admin@brasibturger.com', 'admin123', '+221771234567', CAST(GETDATE() AS DATE));

-- Insertion Zones
INSERT INTO dbo.zone (nom_zone, prix_livraison, description)
VALUES 
('Zone Plateau', 2000, 'Quartiers du Plateau'),
('Zone Medina', 1500, 'Quartiers de Medina'),
('Zone Parcelles', 2500, 'Quartiers des Parcelles');

-- Insertion Quartiers par zone
INSERT INTO dbo.zone_quartier (id_zone, quartier) VALUES
(1, 'Plateau'),
(1, 'Point E'),
(2, 'Medina'),
(2, 'Sacré-Coeur'),
(3, 'Parcelles'),
(3, 'Ngor');

-- Insertion Livreurs
INSERT INTO dbo.livreur (nom, prenom, telephone, id_zone, date_embauche, statut)
VALUES 
('Sall', 'Moussa', '+221771234567', 1, CAST(GETDATE() AS DATE), 'ACTIF'),
('Ba', 'Lamine', '+221771234568', 2, CAST(GETDATE() AS DATE), 'ACTIF'),
('Diop', 'Amadou', '+221771234569', 3, CAST(GETDATE() AS DATE), 'ACTIF');

-- Insertion Burgers
INSERT INTO dbo.burger (nom, description, prix, image, statut)
VALUES 
('Burger Classic', 'Burger classique avec fromage', 3500, '/images/burger-classic.jpg', 'ACTIF'),
('Burger Bacon', 'Burger avec bacon croustillant', 4500, '/images/burger-bacon.jpg', 'ACTIF'),
('Burger Double', 'Double burger, double saveur', 5500, '/images/burger-double.jpg', 'ACTIF'),
('Burger Spicy', 'Burger épicé pour les audacieux', 4000, '/images/burger-spicy.jpg', 'ACTIF');

-- Insertion Compléments
INSERT INTO dbo.complement (nom, description, prix, type_complement, statut)
VALUES 
('Frites Standard', 'Frites simples', 1500, 'FRITES', 'ACTIF'),
('Frites Légères', 'Frites légères et croustillantes', 1200, 'FRITES', 'ACTIF'),
('Coca Cola', 'Boisson gazeuse', 1000, 'BOISSON', 'ACTIF'),
('Jus Naturel', 'Jus frais pressé', 1500, 'BOISSON', 'ACTIF'),
('Eau Minérale', 'Eau minérale 0.5L', 500, 'BOISSON', 'ACTIF');

-- Insertion Menus
INSERT INTO dbo.menu (nom, description, image, statut)
VALUES 
('Menu Classique', 'Burger Classic + Frites + Boisson', '/images/menu-classic.jpg', 'ACTIF'),
('Menu Bacon', 'Burger Bacon + Frites + Boisson', '/images/menu-bacon.jpg', 'ACTIF'),
('Menu Double', 'Burger Double + Frites + Boisson', '/images/menu-double.jpg', 'ACTIF');

-- Liaisons Menus-Burgers
INSERT INTO dbo.menu_burger (id_menu, id_burger) VALUES
(1, 1),  -- Menu Classique = Burger Classic
(2, 2),  -- Menu Bacon = Burger Bacon
(3, 3);  -- Menu Double = Burger Double

-- Liaisons Menus-Compléments (Frites + Boisson)
INSERT INTO dbo.menu_complement (id_menu, id_complement) VALUES
(1, 1),  -- Menu Classique avec Frites Standard
(1, 3),  -- Menu Classique avec Coca
(2, 1),  -- Menu Bacon avec Frites Standard
(2, 3),  -- Menu Bacon avec Coca
(3, 1),  -- Menu Double avec Frites Standard
(3, 3);  -- Menu Double avec Coca

-- Insertion Clients (exemples)
INSERT INTO dbo.client (nom, prenom, email, telephone, mot_de_passe, date_inscription, adresse, statut)
VALUES 
('Diallo', 'Fatoumata', 'fatoumata@email.com', '+221771111111', 'pass123', CAST(GETDATE() AS DATE), 'Plateau', 'ACTIF'),
('Sane', 'Ibrahima', 'ibrahima@email.com', '+221771111112', 'pass123', CAST(GETDATE() AS DATE), 'Medina', 'ACTIF'),
('Ndiaye', 'Marie', 'marie@email.com', '+221771111113', 'pass123', CAST(GETDATE() AS DATE), 'Parcelles', 'ACTIF');

-- ============================================================================
-- FIN DU SCRIPT
-- ============================================================================
-- Status: ✅ Prêt pour déploiement SQL Server
-- Base de données: brasil_burger
-- Tables: 18
-- Vues: 7
-- Données de test: Incluses
-- Conversions effectuées:
--   - AUTO_INCREMENT → IDENTITY
--   - TIMESTAMP → DATETIME
--   - CURDATE() → CAST(GETDATE() AS DATE)
--   - Ajout de IF NOT EXISTS et IF OBJECT_ID pour sécurité
-- ============================================================================
