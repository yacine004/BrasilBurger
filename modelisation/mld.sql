-- =============================================================================
-- Brasil Burger - Script PostgreSQL pour Neon.com
-- Base de données: neondb
-- Version: PostgreSQL 15
-- Date: 12/12/2025
-- =============================================================================
-- IMPORTANT: Exécuter ce script sur Neon.tech en utilisant psql
-- Connexion: psql 'postgresql://[USER]:[PASSWORD]@[HOST]/neondb?sslmode=require'
-- =============================================================================

-- Supprimer les tables si elles existent (en respectant les dépendances)
DROP TABLE IF EXISTS order_lines CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS burger_complements CASCADE;
DROP TABLE IF EXISTS burgers CASCADE;
DROP TABLE IF EXISTS complements CASCADE;
DROP TABLE IF EXISTS menus CASCADE;
DROP TABLE IF EXISTS clients CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- =============================================================================
-- TABLE: users (Utilisateurs du système)
-- =============================================================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'CLIENT',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_role CHECK (role IN ('ADMIN', 'MANAGER', 'CLIENT'))
);

CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- =============================================================================
-- TABLE: clients (Clients du restaurant)
-- =============================================================================
CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    postal_code VARCHAR(10),
    loyalty_points INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_client_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_clients_user_id ON clients(user_id);
CREATE INDEX idx_clients_phone ON clients(phone);

-- =============================================================================
-- TABLE: menus (Menus disponibles)
-- =============================================================================
CREATE TABLE menus (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_menu_price CHECK (price >= 0)
);

CREATE INDEX idx_menus_category ON menus(category);
CREATE INDEX idx_menus_available ON menus(is_available);

-- =============================================================================
-- TABLE: complements (Compléments: boissons, frites, sauces, desserts)
-- =============================================================================
CREATE TABLE complements (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    type VARCHAR(50) NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_complement_type CHECK (type IN ('DRINK', 'SIDE', 'SAUCE', 'DESSERT')),
    CONSTRAINT chk_complement_price CHECK (price >= 0)
);

CREATE INDEX idx_complements_type ON complements(type);
CREATE INDEX idx_complements_available ON complements(is_available);

-- =============================================================================
-- TABLE: burgers (Burgers disponibles)
-- =============================================================================
CREATE TABLE burgers (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    bread_type VARCHAR(50),
    meat_type VARCHAR(50),
    is_vegetarian BOOLEAN DEFAULT FALSE,
    is_available BOOLEAN DEFAULT TRUE,
    spicy_level INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_burger_price CHECK (base_price >= 0),
    CONSTRAINT chk_spicy_level CHECK (spicy_level BETWEEN 0 AND 5)
);

CREATE INDEX idx_burgers_available ON burgers(is_available);
CREATE INDEX idx_burgers_vegetarian ON burgers(is_vegetarian);

-- =============================================================================
-- TABLE: burger_complements (Relation Many-to-Many entre Burgers et Compléments)
-- =============================================================================
CREATE TABLE burger_complements (
    id SERIAL PRIMARY KEY,
    burger_id INTEGER NOT NULL,
    complement_id INTEGER NOT NULL,
    quantity INTEGER DEFAULT 1,
    CONSTRAINT fk_bc_burger FOREIGN KEY (burger_id) REFERENCES burgers(id) ON DELETE CASCADE,
    CONSTRAINT fk_bc_complement FOREIGN KEY (complement_id) REFERENCES complements(id) ON DELETE CASCADE,
    CONSTRAINT chk_bc_quantity CHECK (quantity > 0),
    CONSTRAINT uq_burger_complement UNIQUE (burger_id, complement_id)
);

CREATE INDEX idx_bc_burger ON burger_complements(burger_id);
CREATE INDEX idx_bc_complement ON burger_complements(complement_id);

-- =============================================================================
-- TABLE: orders (Commandes clients)
-- =============================================================================
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'PENDING',
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status VARCHAR(20) DEFAULT 'PENDING',
    delivery_address TEXT,
    delivery_type VARCHAR(20) DEFAULT 'DINE_IN',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    CONSTRAINT chk_order_status CHECK (status IN ('PENDING', 'CONFIRMED', 'PREPARING', 'READY', 'DELIVERED', 'CANCELLED')),
    CONSTRAINT chk_payment_status CHECK (payment_status IN ('PENDING', 'PAID', 'REFUNDED')),
    CONSTRAINT chk_delivery_type CHECK (delivery_type IN ('DINE_IN', 'TAKEAWAY', 'DELIVERY')),
    CONSTRAINT chk_total_amount CHECK (total_amount >= 0)
);

CREATE INDEX idx_orders_client ON orders(client_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_orders_payment_status ON orders(payment_status);

-- =============================================================================
-- TABLE: order_lines (Lignes de commande - détail des items commandés)
-- =============================================================================
CREATE TABLE order_lines (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    item_type VARCHAR(20) NOT NULL,
    item_id INTEGER NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    quantity INTEGER NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ol_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT chk_item_type CHECK (item_type IN ('BURGER', 'MENU', 'COMPLEMENT')),
    CONSTRAINT chk_ol_quantity CHECK (quantity > 0),
    CONSTRAINT chk_unit_price CHECK (unit_price >= 0),
    CONSTRAINT chk_subtotal CHECK (subtotal >= 0)
);

CREATE INDEX idx_ol_order ON order_lines(order_id);
CREATE INDEX idx_ol_item_type ON order_lines(item_type);

-- =============================================================================
-- DONNÉES DE TEST (Optionnel)
-- =============================================================================

-- Utilisateur admin par défaut (password: admin123 - CHANGER EN PRODUCTION!)
-- Hash BCrypt pour "admin123": $2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy
INSERT INTO users (username, email, password_hash, role) VALUES
('admin', 'admin@brasilburger.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ADMIN'),
('manager', 'manager@brasilburger.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'MANAGER'),
('client1', 'client1@example.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'CLIENT');

-- Client de test
INSERT INTO clients (user_id, first_name, last_name, phone, address, city, postal_code) VALUES
(3, 'João', 'Silva', '+33123456789', '123 Rue de Paris', 'Paris', '75001');

-- Compléments
INSERT INTO complements (name, description, price, type) VALUES
('Coca-Cola', 'Boisson gazeuse 33cl', 2.50, 'DRINK'),
('Fanta Orange', 'Boisson gazeuse 33cl', 2.50, 'DRINK'),
('Sprite', 'Boisson gazeuse 33cl', 2.50, 'DRINK'),
('Eau Minérale', 'Eau plate 50cl', 1.50, 'DRINK'),
('Frites Classiques', 'Portion moyenne', 3.00, 'SIDE'),
('Frites Cheddar', 'Avec fromage cheddar fondu', 4.50, 'SIDE'),
('Onion Rings', '6 pièces', 3.50, 'SIDE'),
('Sauce Ketchup', 'Sachet 20ml', 0.50, 'SAUCE'),
('Sauce Mayonnaise', 'Sachet 20ml', 0.50, 'SAUCE'),
('Sauce Barbecue', 'Sachet 20ml', 0.50, 'SAUCE'),
('Sauce Pimentée', 'Sachet 20ml', 0.50, 'SAUCE'),
('Brownie Chocolat', 'Fait maison', 4.00, 'DESSERT'),
('Cheesecake', 'New York style', 5.00, 'DESSERT');

-- Burgers
INSERT INTO burgers (name, description, base_price, bread_type, meat_type, is_vegetarian, spicy_level) VALUES
('Brasil Classic', 'Notre burger signature avec steak haché 150g, cheddar, salade, tomate, oignons', 8.90, 'Brioche', 'Boeuf', FALSE, 0),
('Picanha Burger', 'Steak de picanha 180g, fromage coalho, chimichurri', 11.90, 'Sésame', 'Boeuf', FALSE, 1),
('Frango Crispy', 'Poulet pané croustillant, sauce aioli, salade, cornichons', 8.50, 'Brioche', 'Poulet', FALSE, 0),
('Veggie Tropical', 'Galette végétale, ananas grillé, avocat, sauce teriyaki', 9.90, 'Complet', 'Végétarien', TRUE, 0),
('Bacon Lovers', 'Double steak, double bacon, double cheddar, sauce BBQ', 12.90, 'Brioche', 'Boeuf', FALSE, 1),
('Spicy Brasil', 'Steak épicé, jalapeños, piment, cheddar, sauce pimentée', 10.50, 'Sésame', 'Boeuf', FALSE, 4);

-- Menus
INSERT INTO menus (name, description, price, category) VALUES
('Menu Brasil Classic', 'Burger Brasil Classic + Frites + Boisson', 13.90, 'MENU_BURGER'),
('Menu Picanha', 'Burger Picanha + Frites Cheddar + Boisson + Dessert', 18.90, 'MENU_PREMIUM'),
('Menu Frango', 'Burger Frango Crispy + Frites + Boisson', 12.90, 'MENU_BURGER'),
('Menu Enfant', 'Petit burger + Petites frites + Jus de fruits + Surprise', 8.90, 'MENU_ENFANT'),
('Menu Veggie', 'Burger Veggie Tropical + Frites + Boisson + Dessert', 15.90, 'MENU_VEGETARIAN');

-- =============================================================================
-- FONCTIONS ET TRIGGERS (Mise à jour automatique updated_at)
-- =============================================================================

CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_clients_updated_at BEFORE UPDATE ON clients
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_menus_updated_at BEFORE UPDATE ON menus
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_complements_updated_at BEFORE UPDATE ON complements
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_burgers_updated_at BEFORE UPDATE ON burgers
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_orders_updated_at BEFORE UPDATE ON orders
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- =============================================================================
-- VÉRIFICATION DES TABLES CRÉÉES
-- =============================================================================

SELECT 
    table_name,
    (SELECT COUNT(*) FROM information_schema.columns WHERE table_name = t.table_name) as column_count
FROM information_schema.tables t
WHERE table_schema = 'public' 
  AND table_type = 'BASE TABLE'
ORDER BY table_name;

-- =============================================================================
-- FIN DU SCRIPT
-- Script créé pour Brasil Burger - PostgreSQL/Neon
-- Exécuter sur: https://console.neon.tech/app/projects
-- =============================================================================
