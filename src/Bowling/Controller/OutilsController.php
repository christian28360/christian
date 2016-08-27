<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ScoreController
 *
 * @author Christian Alcon
 */
class OutilsController extends GlobalController {

    protected function scorerAction(Request $request, Application $app) {

        return $this->app['twig']->render(
                        'src/Bowling/Resources/views/scorer.html.twig', array(
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

}
