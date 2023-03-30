-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 mars 2023 à 21:39
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api-article-db`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `article_id` varchar(10) NOT NULL,
  `content` varchar(999) NOT NULL,
  `date_de_publication` date NOT NULL,
  `author` varchar(8) NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `username` (`author`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`article_id`, `content`, `date_de_publication`, `author`) VALUES
('FJH542', 'Mauris eu odio vel elit aliquet dapibus. Nunc congue enim id mauris efficitur, vel mollis quam tristique. Duis eget augue eget dolor auctor scelerisque in vel dolor. Nullam eget volutpat sapien. Nullam pulvinar nibh sit amet odio ullamcorper malesuada. Donec a convallis risus. Curabitur tempor, turpis nec interdum venenatis, turpis nisi egestas augue, eu egestas dolor nisl sit amet nulla.', '2023-03-22', 'riperpro'),
('KSD129', 'Praesent eget metus sit amet eros dignissim imperdiet. Morbi malesuada enim sapien, quis bibendum ipsum consectetur ac. Maecenas hendrerit, enim a luctus fermentum, turpis lectus malesuada eros, sit amet pulvinar est felis eget urna. Donec euismod vel arcu in faucibus. Sed convallis, arcu nec aliquam rhoncus, justo lacus blandit enim, quis aliquam eros sapien sed nibh.', '2023-03-22', 'baran'),
('GTF983', 'Suspendisse eu fringilla sapien, sit amet egestas augue. Sed malesuada, augue in dictum placerat, sapien nulla finibus velit, a malesuada metus quam ut nibh. Pellentesque ac posuere augue. Maecenas vitae interdum neque. Praesent ornare dolor et turpis faucibus rhoncus. Nunc sit amet tellus id ipsum faucibus tristique. Donec posuere varius augue.', '2023-03-22', 'bonbily'),
('LAS428', 'Le monde d\'hier.', '2023-03-22', 'bonbily'),
('LAL456', 'La conquête de la lune.', '2023-03-18', 'fujitoo'),
('AJG935', 'Suspendisse potenti. Praesent vel odio est. Ut eleifend arcu at magna sagittis, nec lobortis libero tincidunt.', '2023-03-18', 'yahyanft'),
('PKH824', 'Phasellus nec neque blandit, suscipit eros nec, finibus felis. Duis quis leo eget arcu tincidunt fringilla.', '2023-03-18', 'riperpro'),
('QWE148', 'Vestibulum lobortis vel ipsum vitae molestie. Nam varius, sapien at consectetur facilisis, ipsum orci posuere massa, nec tristique elit magna non elit.', '2023-03-18', 'baran'),
('MNB538', 'Maecenas in lacinia erat. Vivamus vitae nibh interdum, bibendum est quis, euismod mauris. Aliquam lacinia erat non augue luctus, vel vestibulum ex pulvinar.', '2023-03-18', 'bonbily'),
('KLO673', 'Donec hendrerit molestie nibh, sed lacinia quam gravida vitae. Fusce laoreet nisl sit amet nibh lobortis vestibulum. Vestibulum feugiat nisi vitae lectus vulputate tincidunt.', '2023-03-18', 'riperpro'),
('POI426', 'Pellentesque in feugiat odio. Aliquam eget tortor euismod, iaculis nibh id, suscipit nisi. Suspendisse tempor sapien in eros interdum bibendum.', '2023-03-18', 'baran'),
('TRE846', 'In et felis tristique, posuere tellus in, cursus mauris. Nulla volutpat, est vel ullamcorper posuere, sapien nisi tristique leo, vel congue nulla turpis a metus.', '2023-03-18', 'bonbily'),
('SDF234', 'Vestibulum at justo vitae lectus bibendum aliquam. Fusce eget dui eu nulla efficitur lobortis. Sed consectetur commodo mi, nec ornare nisi ultrices sed.', '2023-03-18', 'yahyanft'),
('YUI867', 'Suspendisse fermentum pretium risus eu bibendum. Integer ut augue vitae metus dictum euismod quis ut nisl. Aliquam eget congue lacus.', '2023-03-18', 'riperpro'),
('MNB864', 'Maecenas mollis magna et tortor consectetur, vitae consequat urna imperdiet. Morbi dignissim enim nec purus rhoncus, eget dictum velit iaculis. Sed bibendum vel urna nec aliquet.', '2023-03-18', 'baran');

-- --------------------------------------------------------

--
-- Structure de la table `dislikes`
--

DROP TABLE IF EXISTS `dislikes`;
CREATE TABLE IF NOT EXISTS `dislikes` (
  `article_id` varchar(10) NOT NULL,
  `id_username` char(8) NOT NULL,
  PRIMARY KEY (`article_id`,`id_username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `article_id` varchar(10) NOT NULL,
  `id_username` char(8) NOT NULL,
  PRIMARY KEY (`article_id`,`id_username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`article_id`, `id_username`) VALUES
('LAS428', 'yahyanft');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` char(8) NOT NULL,
  `password` varchar(999) NOT NULL,
  `role` varchar(25) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('maxiwere', '0236bfa420bcb17e716e364e15b592e54a49ffabe248d54debc83b933cba64ce', 'moderator'),
('otsu', 'e8118a06c716700aed9af6c900107c02dbd226abc34935bd9ab83f3ca7a77e73', 'moderator'),
('iutprof', 'b38bb9429239744b50dfc9ef13d1a96b1985eb2b1afc9d056d3650b97c015cb7', 'moderator'),
('bonbily', '8e3c6948098ae3149f733a34631597918ead9b9be0e86171f74bee7121323066', 'publisher'),
('yahyanft', '700a9493ae0585465a816dd5ad4244ef181f16b5de9374b3afb0a7759457dabb', 'publisher'),
('fujitoo', 'bf9f601d680c6aacca166393fc57ba3bbf0e6ace3f865252270b09de3034e4fb', 'publisher'),
('riperpro', 'a680a492637fe0bf4270cb90701c3ac41eabce648e3f67805c7580c649d199de', 'publisher'),
('baran', 'a060587546cf0b8beed94f9701b613203fadc098062e222777e82f50b6497474', 'publisher');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
