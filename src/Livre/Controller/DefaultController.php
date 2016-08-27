<?php

namespace CHRIST\Modules\Livre\Controller;

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

        return $this->app['twig']->render('src/Livre/Resources/views/index.html.twig', array(
                    "livres" => $em->getRepository('CHRIST\Modules\Livre\Entity\Livre')->countAll(),
                    "ecrivains" => $em->getRepository('CHRIST\Modules\Livre\Entity\Ecrivain')->countAll(),
                    "editeurs" => $em->getRepository('CHRIST\Modules\Livre\Entity\Editeur')->countAll(),
                    "themes" => $em->getRepository('CHRIST\Modules\Livre\Entity\Theme')->countAll(),
                    "couvertures" => $em->getRepository('CHRIST\Modules\Livre\Entity\Couverture')->countAll(),
                        )
        );
    }

}
