-- ======================
-- Brasil Burger - MLD
-- PostgreSQL Neon
-- Exécuter ce script manuellement une seule fois
-- Partagé entre Java, C#, Symfony
-- ======================

-- ======================
-- SUPPRESSION
-- ======================
DROP TABLE IF EXISTS paiement CASCADE;
DROP TABLE IF EXISTS livraison CASCADE;
DROP TABLE IF EXISTS commande_complement CASCADE;
DROP TABLE IF EXISTS commande_menu CASCADE;
DROP TABLE IF EXISTS commande_burger CASCADE;
DROP TABLE IF EXISTS menu_burger CASCADE;
DROP TABLE IF EXISTS commande CASCADE;
DROP TABLE IF EXISTS client CASCADE;
DROP TABLE IF EXISTS livreur CASCADE;
DROP TABLE IF EXISTS burger CASCADE;
DROP TABLE IF EXISTS menu CASCADE;
DROP TABLE IF EXISTS complement CASCADE;
DROP TABLE IF EXISTS quartier CASCADE;
DROP TABLE IF EXISTS zone CASCADE;

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
        FOREIGN KEY (id_zone) REFERENCES zone(id) ON DELETE CASCADE
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
    CONSTRAINT fk_menu_burger_menu
        FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE CASCADE,
    CONSTRAINT fk_menu_burger_burger
        FOREIGN KEY (id_burger) REFERENCES burger(id) ON DELETE CASCADE
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
        FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE
);

-- ======================
-- COMMANDE_BURGER
-- ======================
CREATE TABLE commande_burger (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL,
    id_burger INT NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT fk_commande_burger_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    CONSTRAINT fk_commande_burger_burger
        FOREIGN KEY (id_burger) REFERENCES burger(id) ON DELETE CASCADE
);

-- ======================
-- COMMANDE_MENU
-- ======================
CREATE TABLE commande_menu (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL,
    id_menu INT NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT fk_commande_menu_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    CONSTRAINT fk_commande_menu_menu
        FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE CASCADE
);

-- ======================
-- COMMANDE_COMPLEMENT
-- ======================
CREATE TABLE commande_complement (
    id SERIAL PRIMARY KEY,
    id_commande INT NOT NULL,
    id_complement INT NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT fk_commande_complement_commande
        FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    CONSTRAINT fk_commande_complement_complement
        FOREIGN KEY (id_complement) REFERENCES complement(id) ON DELETE CASCADE
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
        FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    CONSTRAINT fk_livraison_livreur
        FOREIGN KEY (id_livreur) REFERENCES livreur(id) ON DELETE CASCADE
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
        FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE
);

-- ======================
-- DONNÉES DE TEST
-- ======================

-- Zones
INSERT INTO zone (nom, prix_livraison) VALUES
('Centre-Ville', 2.50),
('Banlieue Nord', 3.50),
('Banlieue Sud', 3.50);

-- Burgers (13 types)
INSERT INTO burger (nom, prix, image, etat) VALUES
('Burger Classique', 12.99, '', TRUE),
('Burger Bacon Deluxe', 14.99, '', TRUE),
('Burger Fromage Triple', 15.99, '', TRUE),
('Burger Végétarien', 11.99, '', TRUE),
('Burger Spicy Brésilien', 13.99, '', TRUE),
('Burger Premium BBQ', 16.99, '', TRUE),
('Burger Poulet Crispy', 13.50, '', TRUE),
('Burger Double Patty', 15.99, '', TRUE),
('Burger Cheddar Gourmet', 14.99, '', TRUE),
('Burger Bleu Deluxe', 16.99, '', TRUE),
('Burger Portobeillo Gourmet', 13.99, '', TRUE),
('Burger Brésilien Tropical', 15.50, '', TRUE),
('Burger Falafel Vegan', 12.99, '', TRUE);

-- Menus (3 types)
INSERT INTO menu (nom, image) VALUES
('Menu Petit', ''),
('Menu Moyen', ''),
('Menu Grand', '');

-- Compléments (4 types)
INSERT INTO complement (nom, prix, image, etat) VALUES
('Frites', 2.99, '', TRUE),
('Salade', 1.99, '', TRUE),
('Coca', 2.49, '', TRUE),
('Bière', 3.99, '', TRUE);

-- Livreurs
INSERT INTO livreur (nom, prenom, telephone) VALUES
('Silva', 'João', '21987654321'),
('Santos', 'Maria', '21987654322'),
('Oliveira', 'Pedro', '21987654323');

-- Quartiers
INSERT INTO quartier (nom, id_zone) VALUES
('Copacabana', 1),
('Ipanema', 1),
('Leblon', 1),
('Niterói', 2),
('São Gonçalo', 2),
('Duque de Caxias', 3),
('Nova Iguaçu', 3);

-- Confirmation
SELECT 'Tables créées avec succès!' as message;
SELECT COUNT(*) as nombre_burgers FROM burger;
SELECT COUNT(*) as nombre_menus FROM menu;
SELECT COUNT(*) as nombre_complements FROM complement;
