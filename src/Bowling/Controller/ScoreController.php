<?php

namespace CHRIST\Modules\Bowling\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use CHRIST\Modules\Bowling\Entity\Score;
use CHRIST\Modules\Bowling\Form\ScoreForm;

/**
 * Description of ScoreController
 *
 * @author Christian Alcon
 */
class ScoreController extends GlobalController {

    protected function listeAction(Request $request, Application $app) {

        return $this->templateRender('src/Bowling/Resources/views/score/liste.html.twig', array(
                    'repository' => 'journee',
                    'tri' => 'dateJournee',
        ));
    }

    protected function saisieAction(Request $request, Application $app) {

        return $this->getForm(
                        new Score(new \DateTime(), $this->periodeCourante()), 'src/Bowling/Resources/views/score/ajout.html.twig', null
        );
    }

    protected function ajoutAction(Request $request, Application $app) {
        return $this->getForm(
                        new Score(new \DateTime(), $this->periodeCourante()), 'src/Bowling/Resources/views/score/ajout.html.twig', null
        );
    }

    protected function updateAction(Request $request, Application $app) {
        $record = $this->getCurrent();

        return $this->getForm($record, 'src/Bowling/Resources/views/score/ajout.html.twig', 'update');
    }

    protected function deleteAction(Request $request, Application $app) {

        $record = $this->getCurrent();
        // les suppressions des séries et des scores se font par l'attribut de l'entity
        // sur chaque relation avec l'anotation ORM :
        // cascade={"persist", "remove", "merge"}
        $this->app['orm.ems']['christian']->remove($record);
        $this->app['orm.ems']['christian']->flush();

        return $this->reload();
    }

    /**
     * Return form view
     */
    private function getForm($entity, $view, $old = null) {

        $form = $this->app["form.factory"]->create(
                new ScoreForm(), $entity, array(
            'method' => $this->request->getMethod(),
                )
        );
        $form->handleRequest($this->request);

        if ($form->isValid()) {

            $entity = $form->getData();

            // controler ici que le total strike + spare + split n'exède as 12
            //
//            if ($entity->getStrike() + $entity->getSpare() + $entity->getSplit() > 12) {
//                $this->app['session']->getFlashBag()->add(
//                        'ERrreur', 'Le cumuls doit être < 13');
//                throw new \Exception('Le cumuls doit être < 13');
//            }

            $this->writeData($entity);

            return $this->app->redirect(
                            $this->app['url_generator']->generate('bowling-score')
            );
        }

        return $this->app['twig']->render(
                        $view, array(
                    'form' => $form->createView(),
                    'mode' => is_null($old) ? 'création' : 'maj',
                    'periode' => $this->periodeCourante(),
                        )
        );
    }

    protected function getCurrent() {
        return $this->getCourant('Bowling', 'Score', $this->request->get('id'));
    }

}
