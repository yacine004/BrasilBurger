-- ============================================================================
-- BASE DE DONNEES - BRASIL BURGER (POSTGRESQL / NEON)
-- Système de Gestion des Commandes et Livraisons
-- ============================================================================
-- Script SQL créé le: 2025-12-12
-- PostgreSQL version: 15+
-- Plateforme cloud: Neon.tech
-- Partagé par: Java Spring Boot API, C# ASP.NET MVC, Symfony
-- ============================================================================

-- ============================================================================
-- 0. CONNEXION ET PREPARATION
-- ============================================================================
-- Se connecter à la base neondb (déjà créée par Neon)
-- psql 'postgresql://neondb_owner:npg_B48ZgFWfClir@ep-solitary-bread-a4ilfngv-pooler.us-east-1.aws.neon.tech/neondb?sslmode=require'

-- Supprimer les tables existantes (ordre inverse des dépendances)
DROP TABLE IF EXISTS ligne_commande_complement CASCADE;
DROP TABLE IF EXISTS ligne_commande CASCADE;
DROP TABLE IF EXISTS menu_complement CASCADE;
DROP TABLE IF EXISTS menu_burger CASCADE;
DROP TABLE IF EXISTS paiement CASCADE;
DROP TABLE IF EXISTS commande CASCADE;
DROP TABLE IF EXISTS zone_quartier CASCADE;
DROP TABLE IF EXISTS zone CASCADE;
DROP TABLE IF EXISTS livreur CASCADE;
DROP TABLE IF EXISTS menu CASCADE;
DROP TABLE IF EXISTS complement CASCADE;
DROP TABLE IF EXISTS burger CASCADE;
DROP TABLE IF EXISTS client CASCADE;
DROP TABLE IF EXISTS gestionnaire CASCADE;

-- ============================================================================
-- 1. TABLES DE BASE - UTILISATEURS
-- ============================================================================

-- Table GESTIONNAIRE
CREATE TABLE gestionnaire (
    id_gestionnaire BIGSERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    date_embauche DATE NOT NULL,
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'INACTIF')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_gestionnaire_email ON gestionnaire(email);
CREATE INDEX idx_gestionnaire_statut ON gestionnaire(statut);

-- Table CLIENT
CREATE TABLE client (
    id_client BIGSERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATE NOT NULL,
    adresse VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'INACTIF')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_client_email ON client(email);
CREATE INDEX idx_client_telephone ON client(telephone);
CREATE INDEX idx_client_statut ON client(statut);

-- Table LIVREUR
CREATE TABLE livreur (
    id_livreur BIGSERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    vehicule VARCHAR(50),
    statut VARCHAR(20) DEFAULT 'DISPONIBLE' CHECK (statut IN ('DISPONIBLE', 'EN_LIVRAISON', 'INDISPONIBLE')),
    date_embauche DATE NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_livreur_statut ON livreur(statut);
CREATE INDEX idx_livreur_email ON livreur(email);

-- ============================================================================
-- 2. TABLES PRODUITS
-- ============================================================================

-- Table BURGER
CREATE TABLE burger (
    id_burger BIGSERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL CHECK (prix >= 0),
    image VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'ARCHIVE')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_burger_statut ON burger(statut);
CREATE INDEX idx_burger_nom ON burger(nom);

-- Table COMPLEMENT
CREATE TABLE complement (
    id_complement BIGSERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL CHECK (prix >= 0),
    image VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'ARCHIVE')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_complement_statut ON complement(statut);
CREATE INDEX idx_complement_nom ON complement(nom);

-- Table MENU
CREATE TABLE menu (
    id_menu BIGSERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'ARCHIVE')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_menu_statut ON menu(statut);
CREATE INDEX idx_menu_nom ON menu(nom);

-- Table MENU_BURGER (Association Menu-Burger)
CREATE TABLE menu_burger (
    id_menu_burger BIGSERIAL PRIMARY KEY,
    id_menu BIGINT NOT NULL,
    id_burger BIGINT NOT NULL,
    quantite INT DEFAULT 1 CHECK (quantite > 0),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE,
    FOREIGN KEY (id_burger) REFERENCES burger(id_burger) ON DELETE CASCADE,
    UNIQUE(id_menu, id_burger)
);

CREATE INDEX idx_menu_burger_menu ON menu_burger(id_menu);
CREATE INDEX idx_menu_burger_burger ON menu_burger(id_burger);

-- Table MENU_COMPLEMENT (Association Menu-Complément)
CREATE TABLE menu_complement (
    id_menu_complement BIGSERIAL PRIMARY KEY,
    id_menu BIGINT NOT NULL,
    id_complement BIGINT NOT NULL,
    quantite INT DEFAULT 1 CHECK (quantite > 0),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE,
    FOREIGN KEY (id_complement) REFERENCES complement(id_complement) ON DELETE CASCADE,
    UNIQUE(id_menu, id_complement)
);

CREATE INDEX idx_menu_complement_menu ON menu_complement(id_menu);
CREATE INDEX idx_menu_complement_complement ON menu_complement(id_complement);

-- ============================================================================
-- 3. TABLES ZONES ET QUARTIERS
-- ============================================================================

-- Table ZONE
CREATE TABLE zone (
    id_zone BIGSERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL UNIQUE,
    prix_livraison DECIMAL(10, 2) NOT NULL CHECK (prix_livraison >= 0),
    description TEXT,
    statut VARCHAR(20) DEFAULT 'ACTIF' CHECK (statut IN ('ACTIF', 'INACTIF')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_zone_nom ON zone(nom);
CREATE INDEX idx_zone_statut ON zone(statut);

-- Table ZONE_QUARTIER (Quartiers couverts par une zone)
CREATE TABLE zone_quartier (
    id_zone_quartier BIGSERIAL PRIMARY KEY,
    id_zone BIGINT NOT NULL,
    nom_quartier VARCHAR(150) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_zone) REFERENCES zone(id_zone) ON DELETE CASCADE,
    UNIQUE(id_zone, nom_quartier)
);

CREATE INDEX idx_zone_quartier_zone ON zone_quartier(id_zone);
CREATE INDEX idx_zone_quartier_nom ON zone_quartier(nom_quartier);

-- ============================================================================
-- 4. TABLES COMMANDES
-- ============================================================================

-- Table COMMANDE
CREATE TABLE commande (
    id_commande BIGSERIAL PRIMARY KEY,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    id_client BIGINT NOT NULL,
    id_zone BIGINT,
    id_livreur BIGINT,
    type_commande VARCHAR(20) NOT NULL CHECK (type_commande IN ('SUR_PLACE', 'A_EMPORTER', 'LIVRAISON')),
    statut VARCHAR(20) DEFAULT 'EN_ATTENTE' CHECK (statut IN ('EN_ATTENTE', 'EN_PREPARATION', 'PRETE', 'EN_LIVRAISON', 'LIVREE', 'ANNULEE')),
    montant_total DECIMAL(10, 2) NOT NULL CHECK (montant_total >= 0),
    adresse_livraison VARCHAR(500),
    commentaire TEXT,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_livraison_prevue TIMESTAMP,
    date_livraison_effective TIMESTAMP,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES client(id_client) ON DELETE CASCADE,
    FOREIGN KEY (id_zone) REFERENCES zone(id_zone) ON DELETE SET NULL,
    FOREIGN KEY (id_livreur) REFERENCES livreur(id_livreur) ON DELETE SET NULL
);

CREATE INDEX idx_commande_numero ON commande(numero_commande);
CREATE INDEX idx_commande_client ON commande(id_client);
CREATE INDEX idx_commande_statut ON commande(statut);
CREATE INDEX idx_commande_date ON commande(date_commande);
CREATE INDEX idx_commande_livreur ON commande(id_livreur);
CREATE INDEX idx_commande_zone ON commande(id_zone);

-- Table LIGNE_COMMANDE (Détails des produits commandés)
CREATE TABLE ligne_commande (
    id_ligne_commande BIGSERIAL PRIMARY KEY,
    id_commande BIGINT NOT NULL,
    id_burger BIGINT,
    id_menu BIGINT,
    quantite INT NOT NULL CHECK (quantite > 0),
    prix_unitaire DECIMAL(10, 2) NOT NULL CHECK (prix_unitaire >= 0),
    sous_total DECIMAL(10, 2) NOT NULL CHECK (sous_total >= 0),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_burger) REFERENCES burger(id_burger) ON DELETE CASCADE,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE,
    CHECK ((id_burger IS NOT NULL AND id_menu IS NULL) OR (id_burger IS NULL AND id_menu IS NOT NULL))
);

CREATE INDEX idx_ligne_commande_commande ON ligne_commande(id_commande);
CREATE INDEX idx_ligne_commande_burger ON ligne_commande(id_burger);
CREATE INDEX idx_ligne_commande_menu ON ligne_commande(id_menu);

-- Table LIGNE_COMMANDE_COMPLEMENT (Compléments ajoutés aux produits)
CREATE TABLE ligne_commande_complement (
    id_ligne_commande_complement BIGSERIAL PRIMARY KEY,
    id_ligne_commande BIGINT NOT NULL,
    id_complement BIGINT NOT NULL,
    quantite INT NOT NULL CHECK (quantite > 0),
    prix_unitaire DECIMAL(10, 2) NOT NULL CHECK (prix_unitaire >= 0),
    sous_total DECIMAL(10, 2) NOT NULL CHECK (sous_total >= 0),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ligne_commande) REFERENCES ligne_commande(id_ligne_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_complement) REFERENCES complement(id_complement) ON DELETE CASCADE
);

CREATE INDEX idx_ligne_complement_ligne ON ligne_commande_complement(id_ligne_commande);
CREATE INDEX idx_ligne_complement_complement ON ligne_commande_complement(id_complement);

-- ============================================================================
-- 5. TABLE PAIEMENT
-- ============================================================================

-- Table PAIEMENT
CREATE TABLE paiement (
    id_paiement BIGSERIAL PRIMARY KEY,
    id_commande BIGINT NOT NULL UNIQUE,
    montant DECIMAL(10, 2) NOT NULL CHECK (montant >= 0),
    mode_paiement VARCHAR(20) NOT NULL CHECK (mode_paiement IN ('WAVE', 'OM', 'ESPECES', 'CARTE')),
    statut VARCHAR(20) DEFAULT 'EN_ATTENTE' CHECK (statut IN ('EN_ATTENTE', 'VALIDE', 'ECHOUE', 'REMBOURSE')),
    reference_transaction VARCHAR(100),
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE
);

CREATE INDEX idx_paiement_commande ON paiement(id_commande);
CREATE INDEX idx_paiement_statut ON paiement(statut);
CREATE INDEX idx_paiement_mode ON paiement(mode_paiement);
CREATE INDEX idx_paiement_date ON paiement(date_paiement);

-- ============================================================================
-- 6. DONNEES DE TEST (Optionnel)
-- ============================================================================

-- Insertion données test gestionnaire
INSERT INTO gestionnaire (nom, prenom, email, mot_de_passe, telephone, date_embauche) VALUES
('Admin', 'Sistema', 'admin@brasilburger.com', '$2a$10$demoPasswordHash123', '221234567', CURRENT_DATE);

-- Insertion données test burgers
INSERT INTO burger (nom, description, prix, statut) VALUES
('Brasil Classique', 'Burger classique avec steak, salade, tomate, oignons', 3500.00, 'ACTIF'),
('Brasil Double', 'Double steak, double fromage, sauce spéciale', 5500.00, 'ACTIF'),
('Brasil Spicy', 'Burger épicé avec sauce pimentée', 4000.00, 'ACTIF');

-- Insertion données test compléments
INSERT INTO complement (nom, description, prix, statut) VALUES
('Frites', 'Frites croustillantes', 1000.00, 'ACTIF'),
('Boisson 33cl', 'Coca, Sprite, Fanta', 500.00, 'ACTIF');

-- Insertion données test zones
INSERT INTO zone (nom, prix_livraison, description) VALUES
('Zone Centre', 500.00, 'Centre-ville et environs'),
('Zone Banlieue', 1000.00, 'Banlieue éloignée');

-- Insertion quartiers
INSERT INTO zone_quartier (id_zone, nom_quartier) VALUES
(1, 'Plateau'),
(1, 'Medina'),
(2, 'Parcelles Assainies'),
(2, 'Guediawaye');

-- ============================================================================
-- 7. FONCTIONS UTILES
-- ============================================================================

-- Fonction pour générer un numéro de commande unique
CREATE OR REPLACE FUNCTION generer_numero_commande()
RETURNS TEXT AS $$
BEGIN
    RETURN 'CMD-' || TO_CHAR(CURRENT_DATE, 'YYYYMMDD') || '-' || LPAD(NEXTVAL('commande_id_commande_seq')::TEXT, 6, '0');
END;
$$ LANGUAGE plpgsql;

-- Fonction pour calculer le prix d'un menu
CREATE OR REPLACE FUNCTION calculer_prix_menu(p_id_menu BIGINT)
RETURNS DECIMAL(10,2) AS $$
DECLARE
    v_total DECIMAL(10,2) := 0;
BEGIN
    -- Prix des burgers du menu
    SELECT COALESCE(SUM(b.prix * mb.quantite), 0) INTO v_total
    FROM menu_burger mb
    JOIN burger b ON b.id_burger = mb.id_burger
    WHERE mb.id_menu = p_id_menu;
    
    -- Ajouter prix des compléments
    SELECT v_total + COALESCE(SUM(c.prix * mc.quantite), 0) INTO v_total
    FROM menu_complement mc
    JOIN complement c ON c.id_complement = mc.id_complement
    WHERE mc.id_menu = p_id_menu;
    
    RETURN v_total;
END;
$$ LANGUAGE plpgsql;

-- ============================================================================
-- 8. TRIGGERS
-- ============================================================================

-- Trigger pour mettre à jour date_modification automatiquement
CREATE OR REPLACE FUNCTION update_date_modification()
RETURNS TRIGGER AS $$
BEGIN
    NEW.date_modification = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Appliquer le trigger sur toutes les tables avec date_modification
CREATE TRIGGER trg_gestionnaire_update BEFORE UPDATE ON gestionnaire
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_client_update BEFORE UPDATE ON client
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_livreur_update BEFORE UPDATE ON livreur
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_burger_update BEFORE UPDATE ON burger
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_complement_update BEFORE UPDATE ON complement
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_menu_update BEFORE UPDATE ON menu
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_zone_update BEFORE UPDATE ON zone
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

CREATE TRIGGER trg_commande_update BEFORE UPDATE ON commande
    FOR EACH ROW EXECUTE FUNCTION update_date_modification();

-- ============================================================================
-- FIN DU SCRIPT
-- ============================================================================
-- Script exécuté avec succès !
-- Base de données Brasil Burger (PostgreSQL/Neon) prête pour les 3 applications
-- ============================================================================
