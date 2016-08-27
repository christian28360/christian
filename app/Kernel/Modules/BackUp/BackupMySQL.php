<?php

namespace CHRIST\Common\Kernel\Modules\BackUp;

/**
 * Sauvegarde MySQL
 *
 * @package	BackupMySQL
 * @author	Benoit Asselin <contact@ab-d.fr>
 * @version	backup.php, 2013/01/13
 * @link	http://www.ab-d.fr/
 *
 */
error_reporting(E_ALL);
ini_set('display_errors', true);

/**
 * Sauvegarde MySQL
 */
class BackupMySQL extends \mysqli {

    /**
     * Tableau des résultats de la sauvegarde et purge
     * @var array
     */
    protected $mess = array();

    /**
     * Dossier des fichiers de sauvegardes
     * @var string
     */
    protected $dossier;

    /**
     * Nom du fichier
     * @var string
     */
    protected $nom_fichier;

    /**
     * Ressource du fichier GZip
     * @var ressource
     */
    protected $gz_fichier;

    /**
     * Constructeur
     * @param array $options
     */
    public function __construct($options = array()) {

        $default = array(
            'host' => ini_get('mysqli.default_host'),
            'username' => ini_get('mysqli.default_user'),
            'passwd' => ini_get('mysqli.default_pw'),
            'dbname' => 'ff',
            'port' => ini_get('mysqli.default_port'),
            'socket' => ini_get('mysqli.default_socket'),
            // autres options, pouvant être modifiées lors de l'appel : 
            // (sinon, ce sont les valeurs ci-dessous qui sont prises en compte)
            'dossier' => '../backup/',
            'nbr_fichiers' => 3,
            'nom_fichier' => 'backup',
            'prefix' => '',
        );

        $options = array_merge($default, $options);
        extract($options);

        // Connexion de la DdB
        @parent::__construct($host, $username, $passwd, $dbname, $port, $socket);
        if ($this->connect_error) {
            $this->message('Erreur de connexion (' . $this->connect_errno . ') ' . $this->connect_error);
            $this->message('<b><u>paramètres traités :</u></b><br />');
            $this->message('<b>Host : </b>' . $host . '<br />');
            $this->message('<b>username : </b>' . $username . '<br />');
            $this->message('<b>passwd : </b>' . $passwd . '<br />');
            $this->message('<b>dbname : </b>' . $dbname . '<br />');
            $this->message('<b>port : </b>' . $port . '<br />');
            $this->message('<b>socket : </b>' . $socket . '<br />');

            return $this->mess;
        }

        // Controle du dossier
        $this->dossier = $dossier .= (!is_null($prefix)) ? $prefix . '/' : '';
        $this->prefix = $prefix;

        if (!is_dir($this->dossier)) {
            $this->message('Erreur de dossier &quot;' . htmlspecialchars($this->dossier) . '&quot;');
            return;
        }

        // Controle du fichier
        $this->nom_fichier = $nom_fichier . date('Ymd-His') . '.sql.gz';
        $this->gz_fichier = @gzopen($this->dossier . $this->nom_fichier, 'w');
        if (!$this->gz_fichier) {
            $this->message('Erreur de fichier &quot;' . htmlspecialchars($this->nom_fichier) . '&quot;');
            return;
        }

        // Demarrage du traitement
        $this->sauvegarder();
        $this->purger_fichiers($nbr_fichiers);

        // retourner les résultats pour affichage
        return $this->mess;
    }
    /**
     * Message d'information ( commenter le "echo" pour rendre le script invisible )
     * @param string $message HTML
     */
    protected function message($message = '&nbsp;') {
        //echo '<p style="padding:0; margin:1px 10px; font-family:sans-serif;">' . $message . '</p>';
        // version remastérisée pour être affichée dans une page html "propre"
        $this->mess[] = $message;
    }
    
    function getMess()
    {
        return $this->mess;
    }
    
    /**
     * Protection des quot SQL
     * @param string $string
     * @return string
     */
    protected function insert_clean($string) {
        // Ne pas changer l'ordre du tableau !!!
        $s1 = array("\\", "'", "\r", "\n",);
        $s2 = array("\\\\", "''", '\r', '\n',);
        return str_replace($s1, $s2, $string);
    }

    /**
     * Sauvegarder les tables
     */
    protected function sauvegarder() {

        $this->message('<b><u>Eléments sauvegardés</u></b>');

        $sql = '--' . "\n";
        $sql .= '-- ' . $this->nom_fichier . "\n";
        gzwrite($this->gz_fichier, $sql);

        // Liste les tables
        $result_tables = $this->query('SHOW TABLE STATUS');

        if ($result_tables && $result_tables->num_rows) {
            while ($obj_table = $result_tables->fetch_object()) {

                $nomTable = htmlspecialchars($obj_table->{'Name'});

                // détermine si l'on backupe la table : nom de table jusqu'au "_" compris dans préfix
                $pos = strpos($nomTable, '_');
                $pos = ( $pos === false ? strlen($this->prefix) + 1 : $pos);
                //  retourne 0 si trouvé donc sauvegarde
                if (\substr_compare($this->prefix, $nomTable, 0, $pos) === 0) {
                    $this->message('- ' . htmlspecialchars($obj_table->{'Name'}));

                    // DROP ...
                    $sql = "\n\n";
                    $sql .= 'DROP TABLE IF EXISTS `' . $obj_table->{'Name'} . '`' . ";\n";

                    $result_create = $this->query('SHOW CREATE TABLE `' . $obj_table->{'Name'} . '`');
                    if ($result_create && $result_create->num_rows) {
                        $obj_create = $result_create->fetch_object();
                        $sql .= $obj_create->{'Create Table'} . ";\n";
                        $result_create->free_result();
                    }

                    // INSERT ...
                    $result_insert = $this->query('SELECT * FROM `' . $obj_table->{'Name'} . '`');
                    if ($result_insert && $result_insert->num_rows) {
                        $sql .= "\n";
                        while ($obj_insert = $result_insert->fetch_object()) {
                            $virgule = false;

                            $sql .= 'INSERT INTO `' . $obj_table->{'Name'} . '` VALUES (';
                            foreach ($obj_insert as $val) {
                                $sql .= ($virgule ? ',' : '');
                                if (is_null($val)) {
                                    $sql .= 'NULL';
                                } else {
                                    $sql .= '\'' . $this->insert_clean($val) . '\'';
                                }
                                $virgule = true;
                            } // for

                            $sql .= ')' . ";\n";
                        } // while
                        $result_insert->free_result();
                    }
                }

                gzwrite($this->gz_fichier, $sql);
            } // while
            $result_tables->free_result();
        }
        gzclose($this->gz_fichier);
        $this->message('<br /><b>Nom du fichier généré : </b>' .
                '<strong style="color:green;">' . htmlspecialchars($this->nom_fichier) . '</strong>');

        $this->message('Sauvegarde terminée !');
    }

    /**
     * Purger les anciens fichiers
     * @param int $nbr_fichiers_max Nombre maximum de sauvegardes
     */
    protected function purger_fichiers($nbr_fichiers_max) {
        $this->message();
        $this->message('<b>Purge des anciens fichiers ...</b>');
        $fichiers = array();

        // On recupere le nom des fichiers gz
        if ($dossier = dir($this->dossier)) {
            while (false !== ($fichier = $dossier->read())) {
                if ($fichier != '.' && $fichier != '..') {
                    if (is_dir($this->dossier . $fichier)) {
                        // Ceci est un dossier ( et non un fichier )
                        continue;
                    } else {
                        // On ne prend que les fichiers se terminant par ".gz"
                        if (preg_match('/\.gz$/i', $fichier)) {
                            $fichiers[] = $fichier;
                        }
                    }
                }
            } // end while
            $dossier->close();
        }

        // On supprime les  anciens fichiers
        $nbr_fichiers_total = count($fichiers);
        if ($nbr_fichiers_total >= $nbr_fichiers_max) {
            // Inverser l'ordre des fichiers gz pour ne pas supprimer les derniers fichiers
            rsort($fichiers);

            // Suppression...
            for ($i = $nbr_fichiers_max; $i < $nbr_fichiers_total; $i++) {
                $this->message('<strong style="color:red;">' . htmlspecialchars($fichiers[$i]) . '</strong>');
                unlink($this->dossier . $fichiers[$i]);
            }
        }
        $this->message('Purge terminée !');
    }

}

// Instance de la classe ( a copier autant que necessaire, mais attention au timeout )
// Rq: pour les parametres, reprendre une ou plusieurs cles de $default ( dans la methode __construct() )
/*
 * new BackupMySQL(array(
    'username' => 'root',
    'passwd' => '',
    'dbname' => 'christian'
        ));
 */

//new BackupMySQL(array(
//	'username' => 'root',
//	'passwd' => 'root',
//	'dbname' => 'mabase',
//	'dossier' => './dossier2/'
//	));
