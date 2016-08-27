SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Création BdD
CREATE DATABASE IF NOT EXISTS christian

-- Table users
DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS user (
   id                           int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
   username                     varchar(32)   NOT NULL DEFAULT '',
   auth_key                     varchar(32)   NOT NULL DEFAULT '',
   password_hash                varchar(255)  NOT NULL DEFAULT '',
   password_reset_token         varchar(255)  NOT NULL DEFAULT '',
   email                        varchar(255)  NOT NULL DEFAULT '',
   status                       int(11)       NOT NULL DEFAULT 10,
   created_at                   TIMESTAMP     NOT NULL DEFAULT NOW(),
   updated_at                   TIMESTAMP     NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- Table livres
DROP TABLE IF EXISTS liv_livre;
CREATE TABLE IF NOT EXISTS liv_livre (
  livre_ID 			int(11) 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
  livre_auteur_ID 		int(11) 		NOT NULL,
  livre_sujet_ID 		int(11) 		NOT NULL,
  livre_editeur_ID 		int(11) 		NOT NULL,
  livre_reliure_ID 		int(11) 		NOT NULL,
  livre_titre 			varchar(255)            NOT NULL DEFAULT '',
  livre_anneeCopyright          int(11)			NULL,
  livre_date_achat 		TIMESTAMP               NULL 	 DEFAULT '0000-00-00 00:00:00',
  livre_prix_achat 		decimal(10,2)           NULL,
  livre_nb_pages		int(11)			NULL,
  livre_remarques	 	TEXT			NULL 	 DEFAULT '',
  livre_a_lire			BOOLEAN			NOT NULL DEFAULT TRUE,
  livre_vocabulaire		BOOLEAN			NOT NULL DEFAULT FALSE,
  livre_en_stock		BOOLEAN			NOT NULL DEFAULT TRUE,
  livre_created_on 		TIMESTAMP 		NOT NULL DEFAULT NOW(),
  livre_updated_on 		TIMESTAMP 		NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- Table themes
DROP TABLE IF EXISTS liv_theme;
CREATE TABLE IF NOT EXISTS liv_theme (
  liv_them_ID 			int(11) 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
  liv_them_libelle 		varchar(100)            NOT NULL DEFAULT '',
  liv_them_created_on 		TIMESTAMP 		NOT NULL DEFAULT NOW(),
  liv_them_updated_on 		TIMESTAMP 		NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- Table ecrivains
DROP TABLE IF EXISTS liv_ecrivain;
CREATE TABLE IF NOT EXISTS liv_ecrivain (
  liv_ecri_ID 			int(11) 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
  liv_ecri_nom	 		varchar(100)            NOT NULL DEFAULT '',
  liv_ecri_prenom	 	varchar(100)            NULL 	 DEFAULT '',
  liv_ecri_nationalite 		varchar(30)		NULL 	 DEFAULT '',
  liv_ecri_remarques 		TEXT			NULL 	 DEFAULT '',
  liv_ecri_created_on 		TIMESTAMP 		NOT NULL DEFAULT NOW(),
  liv_ecri_updated_on 		TIMESTAMP 		NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- Table liv_ecrit_par (auteurs des livres)
DROP TABLE IF EXISTS liv_ecrit_par;
CREATE TABLE IF NOT EXISTS liv_ecrit_par (
  liv_par_ID			int(11) 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
  liv_par_livre_ID	 	int(11) 		NOT NULL,
  liv_par_ecri_ID	 	int(11) 		NOT NULL,
  liv_par_created_on 		TIMESTAMP 		NOT NULL DEFAULT NOW(),
  liv_par_updated_on 		TIMESTAMP 		NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- Table editeurs
DROP TABLE IF EXISTS liv_editeur;
CREATE TABLE IF NOT EXISTS liv_editeur (
  liv_edit_ID 			int(11) 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
  liv_edit_nom	 		varchar(100)            NOT NULL DEFAULT '',
  liv_edit_remarques 		TEXT			NULL 	 DEFAULT '',
  liv_edit_created_on 		TIMESTAMP 		NOT NULL DEFAULT NOW(),
  liv_edit_updated_on 		TIMESTAMP 		NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- Table couvertures
DROP TABLE IF EXISTS liv_couverture;
CREATE TABLE IF NOT EXISTS liv_couverture (
  liv_couv_ID 			int(11) 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
  liv_couv_libelle 		varchar(100)            NOT NULL DEFAULT '',
  liv_couv_created_on 		TIMESTAMP 		NOT NULL DEFAULT NOW(),
  liv_couv_updated_on 		TIMESTAMP 		NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 1 ;

-- création utilisateurs
CREATE USER 'christian'@'localhost' IDENTIFIED BY '***';
GRANT SELECT, INSERT, UPDATE, DELETE, FILE, CREATE TEMPORARY TABLES ON *.* TO 'christian'@'localhost' IDENTIFIED BY '***' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `christian\_%`.* TO 'christian'@'localhost';
