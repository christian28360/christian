<?php

namespace CHRIST\Modules\Livre\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CHRIST\Common\Kernel\Modules\BackUp\BackupMySQL;

/**
 * Description of backupControler
 *
 * @author ezs824
 */
class BackupController extends GlobalController
{

    protected function backupAction(Request $request, Application $app)
    {
        $r = new BackupMySQL(array(
            'username' => 'root',
            'passwd' => '',
            'dbname' => 'christian',
            'nom_fichier' => 'livresBkp_',
            'prefix' => 'livre',
        ));


        return $this->app['twig']->render('app/Resources/views/fragments/messageBackUp.html.twig', array(
                    'application' => 'la bibliothèque personnelle',
                    'route' => 'Livre',
                    'messages' => $r->getMess(),
        ));
    }

}
