-- ========================================
-- Script d'insertion des données de test
-- À exécuter dans SQL Editor de Neon
-- PostgreSQL syntax
-- ========================================

-- Supprimer les données existantes pour recommencer proprement
DELETE FROM ligne_commande_complement;
DELETE FROM ligne_commande;
DELETE FROM menu_complement;
DELETE FROM menu_burger;
DELETE FROM commande;
DELETE FROM paiement;
DELETE FROM burger;
DELETE FROM complement;
DELETE FROM menu;
DELETE FROM client;
DELETE FROM gestionnaire;
DELETE FROM livreur;
DELETE FROM zone_quartier;
DELETE FROM zone;

-- Insérer des compléments (boissons, frites, sauces)
INSERT INTO complement (nom, description, prix, type_complement, statut, date_creation, date_modification) VALUES
('Coca-Cola', 'Boisson gazeuse 33cl', 2.50, 'BOISSON', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Fanta Orange', 'Boisson gazeuse 33cl', 2.50, 'BOISSON', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Sprite', 'Boisson gazeuse 33cl', 2.50, 'BOISSON', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Eau Minérale', 'Eau plate 50cl', 1.50, 'BOISSON', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Frites Classiques', 'Portion moyenne', 3.00, 'FRITES', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Frites Cheddar', 'Avec fromage cheddar fondu', 4.50, 'FRITES', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Onion Rings', '6 pièces', 3.50, 'FRITES', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Sauce Ketchup', 'Sachet 20ml', 0.50, 'AUTRE', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Sauce Mayonnaise', 'Sachet 20ml', 0.50, 'AUTRE', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Sauce Barbecue', 'Sachet 20ml', 0.50, 'AUTRE', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insérer des burgers
INSERT INTO burger (nom, description, prix, statut, date_creation, date_modification) VALUES
('Brasil Classic', 'Notre burger signature avec steak haché 150g, cheddar, salade, tomate, oignons', 8.90, 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Picanha Burger', 'Steak de picanha 180g, fromage coalho, chimichurri', 11.90, 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Frango Crispy', 'Poulet pané croustillant, sauce aioli, salade, cornichons', 8.50, 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Veggie Tropical', 'Galette végétale, ananas grillé, avocat, sauce teriyaki', 9.90, 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Bacon Lovers', 'Double steak, double bacon, double cheddar, sauce BBQ', 12.90, 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Spicy Brasil', 'Steak épicé, jalapeños, piment, cheddar, sauce pimentée', 10.50, 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insérer des menus
INSERT INTO menu (nom, description, statut, date_creation, date_modification) VALUES
('Menu Brasil Classic', 'Burger Brasil Classic + Frites + Boisson', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Menu Picanha', 'Burger Picanha + Frites Cheddar + Boisson', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Menu Frango', 'Burger Frango Crispy + Frites + Boisson', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Menu Veggie', 'Burger Veggie Tropical + Frites + Boisson', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insérer des clients de test (mot de passe BCrypt: client123)
INSERT INTO client (nom, prenom, email, telephone, mot_de_passe, statut, date_inscription, date_creation, date_modification) VALUES
('Silva', 'João', 'joao.silva@example.com', '+33123456789', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Santos', 'Maria', 'maria.santos@example.com', '+33987654321', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Oliveira', 'Pedro', 'pedro.oliveira@example.com', '+33111222333', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ACTIF', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insérer des gestionnaires (mot de passe BCrypt: admin123)
INSERT INTO gestionnaire (nom, prenom, email, telephone, mot_de_passe, statut, date_embauche, date_creation, date_modification) VALUES
('Admin', 'Brasil', 'admin@brasilburger.com', '+33100000000', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ACTIF', CURRENT_DATE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Manager', 'Brasil', 'manager@brasilburger.com', '+33200000000', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ACTIF', CURRENT_DATE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Vérifier les insertions
SELECT 'Burgers' as table_name, COUNT(*) as count FROM burger
UNION ALL
SELECT 'Compléments' as table_name, COUNT(*) as count FROM complement
UNION ALL
SELECT 'Menus' as table_name, COUNT(*) as count FROM menu
UNION ALL
SELECT 'Clients' as table_name, COUNT(*) as count FROM client
UNION ALL
SELECT 'Gestionnaires' as table_name, COUNT(*) as count FROM gestionnaire;
