-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 16 Août 2015 à 23:43
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `christian`
--

-- --------------------------------------------------------

--
-- Structure de la table `liv_couverture`
--
drop table `liv_couverture`;

CREATE TABLE IF NOT EXISTS `liv_couverture` (
  `liv_couv_id` int(11) NOT NULL AUTO_INCREMENT,
  `liv_couv_libelle` varchar(100) NOT NULL DEFAULT '',
  `liv_couv_created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `liv_couv_updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`liv_couv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Contenu de la table `liv_couverture`
--

INSERT INTO `liv_couverture` (`liv_couv_id`, `liv_couv_libelle`, `liv_couv_created_on`, `liv_couv_updated_on`) VALUES
(1, 'Souple', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'CartonnÃ©eÃªÃª', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Rigide', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Avec rabat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Ã©Ã Ã§Ã¹', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `liv_ecrit_par`
--
drop table `liv_ecrit_par`;

CREATE TABLE IF NOT EXISTS `liv_ecrit_par` (
  `liv_par_id` int(11) NOT NULL AUTO_INCREMENT,
  `liv_par_livre_id` int(11) NOT NULL,
  `liv_par_ecri_id` int(11) NOT NULL,
  PRIMARY KEY (`liv_par_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `liv_ecrivain`
--

drop table `liv_ecrivain`;

CREATE TABLE IF NOT EXISTS `liv_ecrivain` (
  `liv_ecri_id` int(11) NOT NULL AUTO_INCREMENT,
  `liv_ecri_nom` varchar(100) NOT NULL DEFAULT '',
  `liv_ecri_prenom` varchar(100) DEFAULT '',
  `liv_ecri_nationalite` varchar(30) DEFAULT '',
  `liv_ecri_remarques` text,
  `liv_ecri_created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `liv_ecri_updated_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`liv_ecri_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Contenu de la table `liv_ecrivain`
--

INSERT INTO `liv_ecrivain` (`liv_ecri_id`, `liv_ecri_nom`, `liv_ecri_prenom`, `liv_ecri_nationalite`, `liv_ecri_remarques`, `liv_ecri_created_on`, `liv_ecri_updated_on`) VALUES
(26, 'King', 'Stephen', NULL, 'Le mari', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'test2', '', '', NULL, '2015-08-15 19:14:57', NULL),
(29, 'King', 'Tabita', NULL, 'L''Ã©pouse', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `liv_editeur`
--

drop table `liv_editeur`;

CREATE TABLE IF NOT EXISTS `liv_editeur` (
  `liv_edit_id` int(11) NOT NULL AUTO_INCREMENT,
  `liv_edit_nom` varchar(100) NOT NULL DEFAULT '',
  `liv_edit_remarques` text,
  `liv_edit_created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `liv_edit_updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`liv_edit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Contenu de la table `liv_editeur`
--

INSERT INTO `liv_editeur` (`liv_edit_id`, `liv_edit_nom`, `liv_edit_remarques`, `liv_edit_created_on`, `liv_edit_updated_on`) VALUES
(15, 'Plon', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Grand Livre du Mois', 'CoÃ»te plus cher que chez Amazon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Poche', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'J''ai Lu', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `liv_livre`
--

drop table `liv_livre`;

CREATE TABLE IF NOT EXISTS `liv_livre` (
  `livre_id` int(11) NOT NULL AUTO_INCREMENT,
  `livre_auteur_id` int(11) NOT NULL,
  `livre_theme_id` int(11) NOT NULL,
  `livre_editeur_id` int(11) NOT NULL,
  `livre_couverture_id` int(11) NOT NULL,
  `livre_titre` varchar(255) NOT NULL DEFAULT '',
  `livre_anneeCopyright` int(11) DEFAULT NULL,
  `livre_date_achat` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `livre_prix_achat` decimal(10,2) DEFAULT NULL,
  `livre_nb_pages` int(11) DEFAULT NULL,
  `livre_remarques` text,
  `livre_a_lire` tinyint(1) NOT NULL DEFAULT '1',
  `livre_vocabulaire` tinyint(1) NOT NULL DEFAULT '0',
  `livre_en_stock` tinyint(1) NOT NULL DEFAULT '1',
  `livre_created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `livre_updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`livre_id`),
  KEY `idx_livre_theme_id` (`livre_theme_id`),
  KEY `FK_editeur_idx` (`livre_editeur_id`),
  KEY `FK_couverture_idx` (`livre_couverture_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `liv_livre`
--

INSERT INTO `liv_livre` (`livre_id`, `livre_auteur_id`, `livre_theme_id`, `livre_editeur_id`, `livre_couverture_id`, `livre_titre`, `livre_anneeCopyright`, `livre_date_achat`, `livre_prix_achat`, `livre_nb_pages`, `livre_remarques`, `livre_a_lire`, `livre_vocabulaire`, `livre_en_stock`, `livre_created_on`, `livre_updated_on`) VALUES
(1, 17, 2, 15, 1, 'mon premier livre', 2015, '0000-00-00 00:00:00', '123456.79', 1777, 'L''encodage accentué est très bien géré', 1, 0, 1, '2015-05-24 06:25:52', '0000-00-00 00:00:00'),
(2, 20, 2, 17, 1, 'Mon deuxième livre', 2015, '0000-00-00 00:00:00', '11.56', 500, 'For test application', 1, 0, 1, '2015-05-31 09:34:11', '0000-00-00 00:00:00'),
(5, 17, 1, 15, 2, 'Un autre livre', 2015, '0000-00-00 00:00:00', '22.00', 250, 'remarques', 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `liv_theme`
--

drop table `liv_theme`;

CREATE TABLE IF NOT EXISTS `liv_theme` (
  `liv_them_id` int(11) NOT NULL AUTO_INCREMENT,
  `liv_them_libelle` varchar(100) NOT NULL DEFAULT '',
  `liv_them_created_on` timestamp NULL DEFAULT NULL,
  `liv_them_updated_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`liv_them_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `liv_theme`
--

INSERT INTO `liv_theme` (`liv_them_id`, `liv_them_libelle`, `liv_them_created_on`, `liv_them_updated_on`) VALUES
(1, 'Fantastique', '2015-05-12 22:00:00', NULL),
(2, 'Educatif', '2015-05-13 22:00:00', '0000-00-00 00:00:00'),
(3, 'Culinaire', '2015-05-12 22:00:00', '2015-05-12 22:00:00'),
(5, 'Science fiction', '2015-05-12 22:00:00', NULL),
(6, 'Policier', '2015-05-12 22:00:00', NULL),
(8, 'Suspense', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Frisson', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

drop table `user`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `auth_key` varchar(32) NOT NULL DEFAULT '',
  `password_hash` varchar(255) NOT NULL DEFAULT '',
  `password_reset_token` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '10',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'sa', '', '', 'ezs824', '', 10, '2015-05-29 16:46:58', '0000-00-00 00:00:00');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `liv_livre`
--
ALTER TABLE `liv_livre`
  ADD CONSTRAINT `FK_theme` FOREIGN KEY (`livre_theme_id`) REFERENCES `liv_theme` (`liv_them_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
