-- Base MySQL : plateforme de vente de vetements et d'articles de sport
CREATE DATABASE IF NOT EXISTS ambam_sport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ambam_sport;

CREATE TABLE categorie (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL UNIQUE,
  description TEXT
);

CREATE TABLE marque (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE utilisateur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100),
  email VARCHAR(150) NOT NULL UNIQUE,
  telephone VARCHAR(30),
  mot_de_passe_hash VARCHAR(255) NOT NULL,
  role VARCHAR(30) NOT NULL DEFAULT 'client',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE adresse (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  nom_destinataire VARCHAR(150) NOT NULL,
  telephone VARCHAR(30) NOT NULL,
  quartier VARCHAR(100),
  ville VARCHAR(100) NOT NULL DEFAULT 'Ambam',
  description TEXT,
  est_principale BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);

CREATE TABLE produit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT,
  prix INT NOT NULL,
  sport VARCHAR(100),
  statut VARCHAR(30) NOT NULL DEFAULT 'brouillon',
  categorie_id INT NOT NULL,
  marque_id INT,
  vendeur_id INT NOT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (categorie_id) REFERENCES categorie(id),
  FOREIGN KEY (marque_id) REFERENCES marque(id),
  FOREIGN KEY (vendeur_id) REFERENCES utilisateur(id)
);

CREATE TABLE variante (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produit_id INT NOT NULL,
  reference_sku VARCHAR(100) UNIQUE,
  taille VARCHAR(20),
  couleur VARCHAR(50),
  stock INT NOT NULL DEFAULT 0,
  prix_specifique INT,
  FOREIGN KEY (produit_id) REFERENCES produit(id)
);

CREATE TABLE image_produit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produit_id INT NOT NULL,
  url_image VARCHAR(500) NOT NULL,
  texte_alternatif VARCHAR(200),
  image_principale BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (produit_id) REFERENCES produit(id)
);

CREATE TABLE panier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  statut VARCHAR(30) NOT NULL DEFAULT 'actif',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);

CREATE TABLE ligne_panier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  panier_id INT NOT NULL,
  variante_id INT NOT NULL,
  quantite INT NOT NULL DEFAULT 1,
  UNIQUE KEY (panier_id, variante_id),
  FOREIGN KEY (panier_id) REFERENCES panier(id),
  FOREIGN KEY (variante_id) REFERENCES variante(id)
);

CREATE TABLE commande (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(50) NOT NULL UNIQUE,
  utilisateur_id INT NOT NULL,
  adresse_id INT,
  date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  statut VARCHAR(30) NOT NULL DEFAULT 'en_attente',
  montant_total INT NOT NULL DEFAULT 0,
  frais_livraison INT NOT NULL DEFAULT 0,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id),
  FOREIGN KEY (adresse_id) REFERENCES adresse(id)
);

CREATE TABLE ligne_commande (
  id INT AUTO_INCREMENT PRIMARY KEY,
  commande_id INT NOT NULL,
  variante_id INT,
  nom_produit VARCHAR(150) NOT NULL,
  taille VARCHAR(20),
  couleur VARCHAR(50),
  prix_unitaire INT NOT NULL,
  quantite INT NOT NULL,
  sous_total INT NOT NULL,
  FOREIGN KEY (commande_id) REFERENCES commande(id),
  FOREIGN KEY (variante_id) REFERENCES variante(id)
);

CREATE TABLE paiement (
  id INT AUTO_INCREMENT PRIMARY KEY,
  commande_id INT NOT NULL,
  reference_transaction VARCHAR(150) UNIQUE,
  methode VARCHAR(50) NOT NULL,
  montant INT NOT NULL,
  statut VARCHAR(30) NOT NULL DEFAULT 'en_attente',
  date_paiement TIMESTAMP NULL,
  FOREIGN KEY (commande_id) REFERENCES commande(id)
);

CREATE TABLE livraison (
  id INT AUTO_INCREMENT PRIMARY KEY,
  commande_id INT NOT NULL,
  adresse_id INT,
  transporteur VARCHAR(100),
  numero_suivi VARCHAR(100),
  statut VARCHAR(30) NOT NULL DEFAULT 'en_attente',
  date_expedition TIMESTAMP NULL,
  date_livraison TIMESTAMP NULL,
  FOREIGN KEY (commande_id) REFERENCES commande(id),
  FOREIGN KEY (adresse_id) REFERENCES adresse(id)
);

CREATE TABLE avis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  produit_id INT NOT NULL,
  note INT NOT NULL,
  commentaire TEXT,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (utilisateur_id, produit_id),
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id),
  FOREIGN KEY (produit_id) REFERENCES produit(id)
);

CREATE TABLE favori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  produit_id INT NOT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (utilisateur_id, produit_id),
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id),
  FOREIGN KEY (produit_id) REFERENCES produit(id)
);

-- ===== Donnees de test =====
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe_hash, role)
VALUES ('Admin', 'Boutique', 'admin@boutique.cm', 'A_REMPLACER', 'administrateur');

INSERT INTO categorie (nom) VALUES ('Ballons'), ('Chaussures'), ('Maillots'), ('Survêtements');

INSERT INTO produit (nom, prix, sport, statut, categorie_id, vendeur_id) VALUES
('Ballon de basketball', 13000, 'Basketball', 'disponible', 1, 1),
('Ballon de football', 10000, 'Football', 'disponible', 1, 1),
('Ballon de handball', 12000, 'Handball', 'disponible', 1, 1),
('Chaussures de football', 25000, 'Football', 'disponible', 2, 1),
('Chaussures de handball', 22000, 'Handball', 'disponible', 2, 1),
('Maillot Domicile', 12000, 'Football', 'disponible', 3, 1),
('Maillot Lions Indomptables', 15000, 'Football', 'disponible', 3, 1),
('Short de sport', 8000, 'Multisport', 'disponible', 4, 1),
('Survêtement Training', 20000, 'Multisport', 'disponible', 4, 1);

INSERT INTO variante (produit_id, taille, couleur, stock) VALUES
(1, '7', 'Orange', 10), (2, '5', 'Blanc', 15), (3, '2', 'Bleu', 8),
(4, '40', 'Noir', 5), (4, '42', 'Noir', 4), (4, '44', 'Blanc', 3),
(5, '41', 'Bleu', 6), (5, '43', 'Rouge', 2),
(6, 'M', 'Bleu', 8), (6, 'L', 'Bleu', 6), (6, 'XL', 'Blanc', 4),
(7, 'M', 'Vert', 7), (7, 'L', 'Rouge', 5),
(8, 'M', 'Noir', 12), (8, 'L', 'Noir', 9),
(9, 'M', 'Gris', 6), (9, 'L', 'Noir', 5), (9, 'XL', 'Noir', 3);
