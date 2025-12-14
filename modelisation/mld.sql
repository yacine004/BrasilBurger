-- =========================================================
-- MLD Brasil Burger
-- Compatible PostgreSQL / Neon
-- Basé sur MLD + Diagramme de classes
-- =========================================================

-- ======================
-- SUPPRESSION
-- ======================
DROP TABLE IF EXISTS livraison CASCADE;
DROP TABLE IF EXISTS paiement CASCADE;
DROP TABLE IF EXISTS commande_burger CASCADE;
DROP TABLE IF EXISTS commande_menu CASCADE;
DROP TABLE IF EXISTS commande_complement CASCADE;
DROP TABLE IF EXISTS menu_burger CASCADE;
DROP TABLE IF EXISTS commande CASCADE;
DROP TABLE IF EXISTS client CASCADE;
DROP TABLE IF EXISTS burger CASCADE;
DROP TABLE IF EXISTS menu CASCADE;
DROP TABLE IF EXISTS complement CASCADE;
DROP TABLE IF EXISTS quartier CASCADE;
DROP TABLE IF EXISTS zone CASCADE;
DROP TABLE IF EXISTS livreur CASCADE;

-- ======================
-- ZONE
-- ======================
CREATE TABLE zone (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix_livraison DECIMAL(10,2) NOT NULL
);

-- ======================
-- QUARTIER
-- ======================
CREATE TABLE quartier (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_zone INT NOT NULL,
    CONSTRAINT fk_quartier_zone
        FOREIGN KEY (id_zone) REFERENCES zone(id)
);

-- ======================
-- CLIENT
-- ======================
CREATE TABLE client (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(30),
    email VARCHAR(150),
    password VARCHAR(255)
);

-- ======================
-- BURGER
-- ======================
CREATE TABLE burger (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    etat BOOLEAN DEFAULT TRUE
);

-- ======================
-- MENU
-- ======================
CREATE TABLE menu (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    image VARCHAR(255)
);

-- ======================
-- COMPLEMENT
-- ======================
CREATE TABLE complement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    etat BOOLEAN DEFAULT TRUE
);

-- ======================
-- MENU_BURGER (N-N)
-- ======================
CREATE TABLE menu_burger (
    id SERIAL PRIMARY KEY,
    id_menu INT NOT NULL,
    id_burger INT NOT NULL,
    CONSTRAINT fk_mb_menu
        FOREIGN KEY (id_menu) REFERENCES menu(id),
    CONSTRAINT fk_mb_burger
        FOREIGN KEY (id_burger) REFERENCES burger(id)
);

-- ======================
-- COMMANDE
-- ======================
CREATE TABLE commande (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    etat VARCHAR(50),
    mode VARCHAR(50),
    montant DECIMAL(10,2),
    id_client INT NOT NULL,
    CONSTRAINT fk_commande_client
        FOREIGN KEY (id_client) REFERENCES client(id)
);

-- ======================
-- COMMANDE_BURGER
-- ======================
CREATE TABLE commande_burger (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL,
    id_burger INT NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT fk_cb_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id),
    CONSTRAINT fk_cb_burger
        FOREIGN KEY (id_burger) REFERENCES burger(id)
);

-- ======================
-- COMMANDE_MENU
-- ======================
CREATE TABLE commande_menu (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL,
    id_menu INT NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT fk_cm_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id),
    CONSTRAINT fk_cm_menu
        FOREIGN KEY (id_menu) REFERENCES menu(id)
);

-- ======================
-- COMMANDE_COMPLEMENT
-- ======================
CREATE TABLE commande_complement (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL,
    id_complement INT NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT fk_cc_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id),
    CONSTRAINT fk_cc_complement
        FOREIGN KEY (id_complement) REFERENCES complement(id)
);

-- ======================
-- LIVREUR
-- ======================
CREATE TABLE livreur (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(30)
);

-- ======================
-- LIVRAISON
-- ======================
CREATE TABLE livraison (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL UNIQUE,
    id_livreur INT NOT NULL,
    CONSTRAINT fk_livraison_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id),
    CONSTRAINT fk_livraison_livreur
        FOREIGN KEY (id_livreur) REFERENCES livreur(id)
);

-- ======================
-- PAIEMENT
-- ======================
CREATE TABLE paiement (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    montant DECIMAL(10,2) NOT NULL,
    mode VARCHAR(50),
    id_commande INT NOT NULL UNIQUE,
    CONSTRAINT fk_paiement_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id)
);