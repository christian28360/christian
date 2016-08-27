<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller of application
 *
 * @author Christian ALCON
 */
class DefaultController extends GlobalController {

    protected function homeAction(Request $request, Application $app) {
        // retourne le nombre de lignes de chaque table
        $em = $this->app['orm.ems']['christian'];

        // compte le nombre d'éléments de chaque table pour affichage stats sur accueil
        return $this->app['twig']->render('src/Bowling/Resources/views/index.html.twig', array(
                    "journeesSaison" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Journee')->countSaison($this->periodeCourante()),
                    "partiesSaison" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Journee')->countPartiesSaison($this->periodeCourante()),
                    "tournoisSaison" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Tournoi')->countSaison($this->periodeCourante()),
                    "tournoisToPlanif" => count($em->getRepository('CHRIST\Modules\Bowling\Entity\Tournoi')->findAllToPlanif()),
                    "saisonsNombre" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Periode')->countAll(),
                    "journeesTotalite" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Journee')->countAll(),
                    "partiesTotalite" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Score')->countPartiesTotales(),
                    "tournoisTotalite" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Tournoi')->countAll(),
                    "carnetsTotalite" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Carnet')->countAll(),
                    "bowlings" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Bowling')->countAll(),
                    "typesJeu" => $em->getRepository('CHRIST\Modules\Bowling\Entity\TypeJeu')->countAll(),
                    "periodes" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Periode')->countAll(),
                    "formations" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Formation')->countAll(),
                    "evenements" => $em->getRepository('CHRIST\Modules\Bowling\Entity\Evenement')->countAll(),
                    'periode' => $this->periodeCourante(),
        ));
    }

}
