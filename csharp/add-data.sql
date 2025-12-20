-- ======================
-- Ajout de nouveaux compléments
-- ======================
INSERT INTO complement (nom, prix, image, etat) VALUES
('Sprite', 2.49, '', TRUE),
('Cocktail Tropical', 4.99, '', TRUE);

-- ======================
-- Ajout de nouveaux burgers
-- ======================
INSERT INTO burger (nom, prix, image, etat) VALUES
('Burger Grillé Premium', 17.99, '', TRUE),
('Burger Fusion Asiatique', 16.50, '', TRUE),
('Burger Caramelisé aux Oignons', 14.50, '', TRUE);

-- ======================
-- Vérification
-- ======================
SELECT 'Données ajoutées avec succès!' as message;
SELECT COUNT(*) as nombre_burgers FROM burger;
SELECT COUNT(*) as nombre_complements FROM complement;
SELECT nom, prix FROM burger WHERE nom LIKE 'Burger %' ORDER BY id DESC LIMIT 3;
SELECT nom, prix FROM complement WHERE nom IN ('Sprite', 'Cocktail Tropical');
