<?php

namespace CHRIST\Modules\Vocabulaire\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Kernel\Modules\BackUp\BackupMySQL;

/**
 * Description of backupControler
 * sauvegarde les objets préfixés 'bow" de la base de données ci-dessous
 * @author ezs824
 */
class BackupController extends GlobalController {

    protected function backupAction(Request $request, Application $app) {

        $r = new BackupMySQL(array(
            'username' => 'root',
            'passwd' => '',
            'dbname' => 'christian',
            'nom_fichier' => 'vocabulaireBkp_',
            'prefix' => 'vocabulaire',
        ));

        return $this->app['twig']->render('app/Resources/views/fragments/messageBackUp.html.twig', array(
                    'application' => 'le vocabulaire',
                    'route' => 'Vocabulaire',
                    'messages' => $r->getMess(),
        ));
    }

}
