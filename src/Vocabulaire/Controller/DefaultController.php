<?php

namespace CHRIST\Modules\Vocabulaire\Controller;

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

        return $this->app['twig']->render('src/Vocabulaire/Resources/views/index.html.twig', array(
                    "nbMot" => $em->getRepository('CHRIST\Modules\Vocabulaire\Entity\Mot')->countAll(),
                    "nbTypeMot" => $em->getRepository('CHRIST\Modules\Vocabulaire\Entity\TypeMot')->countAll(),
                    "nbDictionnaire" => $em->getRepository('CHRIST\Modules\Vocabulaire\Entity\Dictionnaire')->countAll(),
        ));
    }

}
