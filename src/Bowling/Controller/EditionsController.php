<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of EditionsController
 *
 * @author Christian ALCON
 */
class EditionsController  extends GlobalController  {

    protected function feuilleSaisietAction(Request $request, Application $app) {

        return $this->app['twig']->render(
                        'src/Bowling/Resources/views/editions/saisieScores.html.twig', array(
                    'repository' => 'journee',
                    'tri' => 'dateJournee',
        ));
    }

}
