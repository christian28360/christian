<?php

namespace CHRIST\Modules\Jardinage\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller of application
 *
 * @author Christian Alcon
 */
class DefaultController extends \CHRIST\Common\Kernel\MasterController {

    protected function homeAction(Request $request, Application $app) {

        // retourne le nombre de lignes de chaque table
        $em = $this->app['orm.ems']['christian'];

        return $this->app['twig']->render('src/Jardinage/Resources/views/index.html.twig', array(
                    "nbTypePlante" => $em->getRepository('CHRIST\Modules\Jardinage\Entity\TypePlante')->countAll(),
        ));
    }

}
