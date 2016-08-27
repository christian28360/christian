
Nous allons voir aujourd’hui comment sauvegarder une ou plusieurs bases de données MySQL de manière automatisée avec un jeu de sauvegardes tournantes compressés au format GZip et ceci sur un serveur mutualisé (cette méthode fonctionne aussi sur un serveur dédié). Cette méthode ne requiert aucun accès root ou administrateur à votre serveur. Il vous suffit simplement d’avoir les droits en lecture/écriture sur la base de données et dans l’espace de stockage où vous allez enregistrer vos fichiers !

Cet article se décompose en 2 sections :

L’utilisation du script backup.php,télécharger
Fonctionnement et explication du code source.
1) UTILISATION DU SCRIPT BACKUP.PHP

Pour utiliser le script, il vous suffit d’ouvrir le fichier avec votre éditeur de code préféré et de vous rendre à la fin de la classe BackupMySQL présente dans le fichier backup.php.

La classe BackupMySQL fonctionne de la manière suivante : une instance de la classe déclenchera automatiquement une sauvegarde ! C’est aussi simple que cela…

Exemple :

new BackupMySQL(array(
	'username' => 'root',
	'passwd' => 'root',
	'dbname' => 'ma_base'
	));
Explication : La classe va tenter de créer une sauvegarde entière de la base de données ma_base avec le serveur MySQL localhost ayant pour identifiant root et mot de passe root.

Vous constatez que l’instance de la classe new BackupMySQL n’est allouée à aucune variable. Ceci est tout à fait normal car la classe effectue une seule action : la sauvegarde, puis la classe se détruit d’elle-même.

La ligne new BackupMySQL n’utilise qu’un seul argument array() tableau. Ce tableau vous permet d’inscrire les différentes options dont vous avez besoin pour personnaliser votre sauvegarde. Toutes les clés du tableau sont optionnelles en théorie. En pratique, certaines sont nécessaires comme l’identifiant et mot de passe de la base de données MySQL. Voici le détail complet de ce tableau magique avec ses valeurs par défaut :

$default = array(
		'host' => ini_get('mysqli.default_host'),
		'username' => ini_get('mysqli.default_user'),
		'passwd' => ini_get('mysqli.default_pw'),
		'dbname' => '',
		'port' => ini_get('mysqli.default_port'),
		'socket' => ini_get('mysqli.default_socket'),
		'dossier' => './',
		'nbr_fichiers' => 5,
		'nom_fichier' => 'backup'
		);
La clé host vous permet de définir l’hôte du serveur MySQL,
La clé username vous permet de définir l’identifiant de connexion au serveur MySQL,
La clé passwd vous permet de définir le mot de passe de connexion au serveur MySQL,
La clé dbname vous permet de définir le nom de la base à sauvegarder,
La clé port vous permet de définir le port de connexion au serveur MySQL,
La clé socket vous permet de définir le socket à utiliser pour le serveur MySQL,
La clé dossier vous permet de définir le dossier où se trouveront les sauvegardes de votre base. L’emplacement du dossier se fait selon l’emplacement du fichier PHP qui appellera votre script de sauvegarde,
La clé nbr_fichiers vous permet de définir le nombre de sauvegardes à conserver,
La clé nom_fichier vous permet de définir le préfix du nom du fichier pour la sauvegarde (Exemple : backup20130130-153012.sql.gz)
Une fois votre script PHP réglé avec les valeurs ci-dessus, il vous suffit simplement de lancer le script et la sauvegarde se fait toute seule !

Les anciennes sauvegardes seront effacées automatiquement pour ne pas saturer votre espace disque.

En ce qui concerne la restauration, elle pourra être effectuée avec n’importe quel outil d’administration MySQL tel que phpMyAdmin.

Vous souhaitez effectuer plusieurs sauvegardes ? Rien de plus simple. Il vous suffit de faire autant d’instance de classe que nécessaire. Je vous déconseille de placer toutes vos sauvegardes dans le même fichier PHP, car les scripts PHP ne peuvent pas s’exécuter indéfiniment sur le serveur (cf. max_execution_time dans le fichier php.ini). Un fichier PHP par sauvegarde me semble plus sage car ce script est plutôt gourmand en ressources serveur 😉

Comment planifier vos sauvegardes de manières automatisées ? Certains hébergeurs proposent les tâches planifiées (cron, crontab) dans leur hébergement. Il vous suffit de spécifier l’emplacement du script PHP, la date et l’heure de l’exécution, et le tour est joué.

Si votre hébergeur ne propose pas ce type de prestation, Google vous proposera quelques adresses avec la recherche suivante : crontab gratuit.

2) FONCTIONNEMENT ET EXPLICATION DU CODE SOURCE

Prérequis : Avant de commencer à modifier ce script tête baissée, je vous invite à réviser les concepts de la POO Programmation Orientée Objet. http://www.php.net/manual/fr/language.oop5.php

Si vous avez déjà ouvert le script PHP (et je présume que c’est le cas), vous avez pu constater que la classe BackupMySQL est étendue de mysqli, ceci veut dire que toutes les méthodes disponibles dans mysqli sont à votre disposition dans BackupMySQL sans instancier mysqli et pour cause, elle hérite de mysqli.

Le constructeur de la classe public function __construct reprend le principe d’écriture des paramètres avec un tableau array() et non avec des arguments à la suite. Ceci permet une certaine souplesse dans l’écriture des paramètres : vous êtes libre de l’ordre des paramètres et tous les paramètres sont en option (ce dernier point peut aussi être un problème, à vous d’affiner les contrôles si vous souhaitez éviter les plantages).

Les valeurs par défaut, vues dans la première partie, proviennent de la documentation officielle PHP du constructeur mysqli.

mysqli est donc réellement lancé (si je puis m’exprimer ainsi) avec la ligne parent::__construct( … );

Ci-dessous, vous avez les divers contrôles d’usage :

– La connexion au serveur fonctionne –t-elle ?

– Le dossier spécifié existe-il ?

– Un fichier peut-il être écrit ?

Et si tout va bien, la sauvegarde démarre…

La méthode protected function message() vous permet de suivre l’évolution de votre script. Si vous ne souhaitez plus voir les opérations par soucis de confidentialité, il vous suffit de commenter le echo.

La méthode protected function insert_clean() a pour but de protéger les caractères spéciaux dans votre fichier de sauvegarde et surtout ne pas avoir de bugs lors de la restauration du fichier avec les apostrophes et autres retour-charriots.

La méthode protected function sauvegarder() comme son nom l’indique, déclenche LA sauvegarde et l’écriture du fichier GZip sur l’espace disque.

Comment procède-t-elle ?

On demande à MySQL de retourner le nom des tables présentes dans la base avec la requête SHOW TABLE STATUS,
Nous voici dans une boucle while qui tournera pour chaque table,
On inscrit dans le fichier sauvegarde un DROP TABLE IF EXISTS pour supprimer les anciennes tables,
La requête SHOW CREATE TABLE nous retourne une requête toute faite pour la création de la table en cours,
On inscrit les données par INSER INTO que tout le monde connaît, je présume,
On écrit les données avec gzwrite() et le fichier se clôt avec gzclose(),
Votre sauvegarde est terminée !
La dernière méthode protected function purger_fichiers() a pour but de ? de ?… Oui ! De purger les vieux fichiers !

Comment procède-t-elle ?

On parcourt le dossier avec la classe Directory par l’intermédiaire de la fonction dir(),
La boucle while permet de lister les dossiers et fichiers présents, nous allons uniquement sélectionner les fichiers (et non les dossiers) se terminant par l’extension .gz avec l’expression régulière preg_match(‘/\.gz$/i’, $fichier)
Une fois la boucle terminée, la variables $fichiers contient tous les fichiers de sauvegarde .gz, la date de la sauvegarde étant présente dans le nom du ficher, il suffit d’inverser l’ordre du nom des fichiers avec rsort() et le script n’aura plus qu’à supprimer les anciens fichiers dans la boucle for avec unlink().
Et voilà, vos anciens fichiers ont été supprimés !
Cette classe étant livrée en l’état, libre à vous de la modifier, de l’améliorer comme bon vous semble. N’oubliez pas qu’elle hérite de mysqli, donc, faites attention de ne pas écraser une méthode ou une variable présente dans la classe mysqli.

http://www.php.net/manual/fr/book.mysqli.php

[Total : 11    Moyenne : 4.6/5]
MySQL backup ecris parjeanluc moyenne des votes4.6/5 - 11 evaluations 