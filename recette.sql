-- Suppression des tables existantes
DROP TABLE IF EXISTS `ingredients`;
DROP TABLE IF EXISTS `recettes`;

-- Création des tables
CREATE TABLE recettes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recette_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    FOREIGN KEY (recette_id) REFERENCES recettes(id) ON DELETE CASCADE
);

-- Insertion des recettes supplémentaires
INSERT INTO recettes (titre, description, image) VALUES
('Ratatouille', 'Un plat provençal à base de légumes d\'été mijotés.', 'img/ratatouille.jpg'),
('Bœuf Bourguignon', 'Un classique français avec du bœuf mijoté au vin rouge.', 'img/boeuf_bourguignon.jpg'),
('Salade César', 'Une salade croquante avec poulet, parmesan et sauce césar.', 'img/salade_cesar.jpg'),
('Lasagnes Bolognaises', 'Pâtes feuilletées avec sauce bolognaise et béchamel.', 'img/lasagnes.jpg'),
('Mousse au Chocolat', 'Un dessert léger et chocolaté.', 'img/mousse_chocolat.jpg'),
('Crêpes', 'Pâte à crêpes fine et légère, sucrée ou salée.', 'img/crepes.jpg'),
('Gratin Dauphinois', 'Pommes de terre en gratin avec crème et ail.', 'img/gratin_dauphinois.jpg'),
('Tajine d\'Agneau', 'Agneau mijoté avec fruits secs et épices.', 'img/tajine_agneau.jpg'),
('Sushi Maki', 'Roulés de riz vinaigré et poisson cru.', 'img/sushi_maki.jpg'),
('Pizza Margherita', 'Pizza simple avec tomate, mozzarella et basilic.', 'img/pizza_margherita.jpg'),
('Guacamole', 'Purée d\'avocat mexicaine avec coriandre et citron vert.', 'img/guacamole.jpg'),
('Tiramisu', 'Dessert italien à base de café et mascarpone.', 'img/tiramisu.jpg'),
('Poulet Rôti', 'Poulet rôti avec herbes de Provence.', 'img/poulet_roti.jpg'),
('Quiche Lorraine', 'Tarte salée avec lardons et œufs.', 'img/quiche_lorraine.jpg'),
('Soupe à l\'Oignon', 'Soupe française gratinée avec oignons caramélisés.', 'img/soupe_oignon.jpg');

-- Insertion des ingrédients pour chaque recette
INSERT INTO ingredients (recette_id, nom) VALUES
(1, 'Aubergine'),
(1, 'Courgette'),
(1, 'Poivron'),
(1, 'Tomate'),
(1, 'Oignon'),
(1, 'Ail'),
(1, 'Herbes de Provence');

INSERT INTO ingredients (recette_id, nom) VALUES
(2, 'Bœuf à bourguignon'),
(2, 'Vin rouge'),
(2, 'Carotte'),
(2, 'Oignon'),
(2, 'Lardons'),
(2, 'Champignons'),
(2, 'Bouquet garni');

INSERT INTO ingredients (recette_id, nom) VALUES
(3, 'Laitue romaine'),
(3, 'Poulet grillé'),
(3, 'Parmesan'),
(3, 'Croûtons'),
(3, 'Sauce César'),
(3, 'Anchois');

INSERT INTO ingredients (recette_id, nom) VALUES
(4, 'Pâtes à lasagnes'),
(4, 'Viande hachée'),
(4, 'Sauce tomate'),
(4, 'Béchamel'),
(4, 'Mozzarella'),
(4, 'Oignon'),
(4, 'Ail');

INSERT INTO ingredients (recette_id, nom) VALUES
(5, 'Chocolat noir'),
(5, 'Œufs'),
(5, 'Sucre'),
(5, 'Crème fraîche');

INSERT INTO ingredients (recette_id, nom) VALUES
(6, 'Farine'),
(6, 'Œufs'),
(6, 'Lait'),
(6, 'Beurre'),
(6, 'Sel');

INSERT INTO ingredients (recette_id, nom) VALUES
(7, 'Pommes de terre'),
(7, 'Crème fraîche'),
(7, 'Lait'),
(7, 'Ail'),
(7, 'Noix de muscade');

INSERT INTO ingredients (recette_id, nom) VALUES
(8, 'Agneau'),
(8, 'Abricots secs'),
(8, 'Amandes'),
(8, 'Miel'),
(8, 'Ras el hanout'),
(8, 'Oignon');

INSERT INTO ingredients (recette_id, nom) VALUES
(9, 'Riz à sushi'),
(9, 'Vinaigre de riz'),
(9, 'Saumon frais'),
(9, 'Avocat'),
(9, 'Concombre'),
(9, 'Algues nori');

INSERT INTO ingredients (recette_id, nom) VALUES
(10, 'Pâte à pizza'),
(10, 'Sauce tomate'),
(10, 'Mozzarella'),
(10, 'Basilic frais'),
(10, 'Huile d\olive');

INSERT INTO ingredients (recette_id, nom) VALUES
(11, 'Avocat'),
(11, 'Citron vert'),
(11, 'Tomate'),
(11, 'Oignon rouge'),
(11, 'Coriandre'),
(11, 'Piment jalapeño');

INSERT INTO ingredients (recette_id, nom) VALUES
(12, 'Mascarpone'),
(12, 'Œufs'),
(12, 'Biscuits cuillère'),
(12, 'Café fort'),
(12, 'Cacao en poudre');

INSERT INTO ingredients (recette_id, nom) VALUES
(13, 'Poulet entier'),
(13, 'Herbes de Provence'),
(13, 'Ail'),
(13, 'Citron'),
(13, 'Beurre');

INSERT INTO ingredients (recette_id, nom) VALUES
(14, 'Pâte brisée'),
(14, 'Lardons'),
(14, 'Œufs'),
(14, 'Crème fraîche'),
(14, 'Lait'),
(14, 'Muscade');

INSERT INTO ingredients (recette_id, nom) VALUES
(15, 'Oignons'),
(15, 'Beurre'),
(15, 'Bouillon de bœuf'),
(15, 'Vin blanc'),
(15, 'Pain'),
(15, 'Fromage râpé');