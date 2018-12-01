-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  sam. 01 déc. 2018 à 17:31
-- Version du serveur :  10.1.36-MariaDB
-- Version de PHP :  7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `clothsupply`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'pull'),
(2, 'manteau'),
(3, 'pantalon'),
(4, 'chaussette'),
(5, 'robe'),
(6, 'jean');

-- --------------------------------------------------------

--
-- Structure de la table `categories_has_products`
--

CREATE TABLE `categories_has_products` (
  `categories_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories_has_products`
--

INSERT INTO `categories_has_products` (`categories_id`, `products_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `imgheader`
--

CREATE TABLE `imgheader` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `active` int(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `imgheader`
--

INSERT INTO `imgheader` (`id`, `name`, `active`) VALUES
(1, 'f9eb62865664c030963786d250584bdf.jpg', 3),
(2, '97fda2b414b403b7bb435fb4fa653e42.jpg', 2),
(3, '7174237d94a86a7978c7f93102a4fd5b.jpg', 1),
(4, '0292ba2832308a5fe8e06aa44e109f34.jpg', 4),
(5, '17e7d485c67c130d60b7f5926a089ee8.jpg', 0),
(6, '5c80cca158e6cb4a1005d7f8104e9d9b.png', 0);

-- --------------------------------------------------------

--
-- Structure de la table `imgproduct`
--

CREATE TABLE `imgproduct` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `id_product` int(11) NOT NULL,
  `mainpage` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `imgproduct`
--

INSERT INTO `imgproduct` (`id`, `name`, `id_product`, `mainpage`) VALUES
(2, '666df37b907792b289563414c8af053b.jpg', 1, 0),
(3, 'cfe3c336f1b1637eb4a365650726cf0a.jpg', 1, 0),
(4, 'eede2c45097054e0da0ab0dbfcbe532e.jpg', 1, 0),
(5, 'c1e49195f79288a0338ef8a14dbc1e39.jpg', 2, 0),
(6, '046c8a72a75807775198dd59f9c38dcd.jpg', 2, 0),
(7, 'a0b1498639cd681d68353b2031684b93.jpg', 2, 0),
(8, '9df4ca3f29acc5f03043b335af5730af.jpg', 2, 0),
(9, '3fc8f76e2410bbeb5c112e905f99c4f5.jpg', 2, 0),
(11, '07c3dee6bf3bfef2c0978de5f7db4bc2.jpg', 1, 1),
(12, 'f4c6217f6e87d54a3ca8d1205f8e527c.jpg', 2, 1),
(13, '61de5b77b2381c7fbb07e0b493a199d9.jpg', 2, 1),
(14, '7414c0ca7d01880c28e3b9bf5b3d5020.jpg', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `availability` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `availability`, `description`) VALUES
(1, 'manteaux en ours', 250, NULL, 'Jolie manteau en ours blanc du bingale'),
(2, 'pull en laine', 50, '5', 'Un pull qui ravivra mamie de son design'),
(3, 'pantalon patte d\'eph', 70, '5', 'Vous vivez dans les années 80 coucou');

-- --------------------------------------------------------

--
-- Structure de la table `shop`
--

CREATE TABLE `shop` (
  `id` int(11) NOT NULL,
  `adress` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shop`
--

INSERT INTO `shop` (`id`, `adress`) VALUES
(1, 'place de la victoire, Bordeaux');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`) VALUES
(1, 'kiki@admin.fr', '123456', 'ROLE_ADMIN');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories_has_products`
--
ALTER TABLE `categories_has_products`
  ADD PRIMARY KEY (`categories_id`,`products_id`),
  ADD KEY `fk_categorie_has_produits_produits1_idx` (`products_id`),
  ADD KEY `fk_categorie_has_produits_categorie_idx` (`categories_id`);

--
-- Index pour la table `imgheader`
--
ALTER TABLE `imgheader`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `imgproduct`
--
ALTER TABLE `imgproduct`
  ADD PRIMARY KEY (`id`,`id_product`),
  ADD KEY `fk_imgProduit_produits1_idx` (`id_product`) USING BTREE;

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `imgheader`
--
ALTER TABLE `imgheader`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `imgproduct`
--
ALTER TABLE `imgproduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categories_has_products`
--
ALTER TABLE `categories_has_products`
  ADD CONSTRAINT `fk_categorie_has_produits_categorie` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_categorie_has_produits_produits1` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `imgproduct`
--
ALTER TABLE `imgproduct`
  ADD CONSTRAINT `fk_imgProduit_produits1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
