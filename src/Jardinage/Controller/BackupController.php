<?php

namespace CHRIST\Modules\Jardinage\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Kernel\Modules\BackUp\BackupMySQL;

/**
 * Description of backupControler
 *
 * @author ezs824
 */
class BackupController extends GlobalController {

    protected function backupAction(Request $request, Application $app) {
        
        $r = new BackupMySQL(array(
            'username' => 'root',
            'passwd' => '',
            'dbname' => 'christian',
            'nom_fichier' => 'jardinageBkp_',
            'prefix' => 'jardin',
        ));

        return $this->app['twig']->render('app/Resources/views/fragments/messageBackUp.html.twig', array(
                    'application' => 'le jardinage',
                    'route' => 'Jardinage',
                    'messages' => $r->getMess(),
        ));
    }

}
